<?php

namespace App\Controllers;

use XEROSessionStorage;

class Xero_api extends Security_Controller
{
    protected $request;

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        show_404();
    }

    function auth_callback()
    {
        $storage = new XEROSessionStorage();
        $provider = $this->_xero_provider();

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {
            echo "Something went wrong, no authorization code found";
            exit("Something went wrong, no authorization code found");

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            echo "Invalid State";
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        } else {
            try {
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $config = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken((string)$accessToken->getToken());
                $identityApi = new \XeroAPI\XeroPHP\Api\IdentityApi(
                    new \GuzzleHttp\Client(),
                    $config
                );

                $result = $identityApi->getConnections();

                // Save my tokens, expiration tenant_id
                $storage->setToken(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $result[0]->getTenantId(),
                    $accessToken->getRefreshToken(),
                    $accessToken->getValues()["id_token"]
                );

                if (isset($_SESSION['XERO_REDIRECT_URL'])) {
                    show_message("XERO Auth Success!", "<div class='col-md-12 d-flex justify-content-center'><p>You've authenticated with XERO successfully, Please go back & try again</p>" . anchor($_SESSION['XERO_REDIRECT_URL'], "<i data-feather='arrow-left' class='icon-18'></i>Go Back", array('class' => 'btn btn-default')) . "</div>");
                } else {
                    show_message("XERO Auth Success!", "<div class='col-md-12 d-flex justify-content-center'><p>You've authenticated with XERO successfully, Please " . anchor(get_uri("invoices"), 'go back') . " & try again</p></div>");
                }
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                echo "Callback failed";
                exit();
            }
        }

        return response()
            ->setContentType('application/json')
            ->setStatusCode(200, "Success")
            ->setBody(array('status' => 'success'));
    }

    function invoice_callback() {}

    function create_invoice()
    {
        $invoice_id = $this->request->getPost('invoice_id');
        $xero_account_id = $this->request->getPost('xero_account_id');

        validate_numeric_value($invoice_id);

        $invoice = $this->Invoices_model->get_one($invoice_id);
        $default_tax = $this->Taxes_model->get_details(array('is_default' => 1))->getRow();

        if ($invoice) {
            if ($invoice->xero_invoice_id) {
                echo json_encode(array('success' => true, 'message' => "This Invoice is already created on XERO"));
                exit();
            }
            $invoice_options = array('invoice_id' => $invoice_id);
            $invoice_items = $this->Invoice_items_model->get_details($invoice_options)->getResult();

            if ($invoice->invoice_type == 'gross_claim' || $invoice->invoice_type == 'net_claim') {
                $institute_options = array(
                    'project_id' => $invoice->project_id,
                    'partner_type' => 'institute'
                );
                $institute = $this->Project_partners_model->get_details($institute_options)->getRow();
                if ($institute) {
                    $xero_contact_id = $this->_xero_contact_id($institute->partner_id);
                } else {
                    echo json_encode(array('success' => false, 'message' => "No institute partner is attached with this application invoice. Please check the application info & make sure to attach the institute partner to send the Gross Claim invoice"));
                }
            } else {
                $xero_contact_id = $this->_xero_contact_id($invoice->client_id);
            }

            $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setContactID($xero_contact_id);

            $lineItems = [];
            $total_tax = 0;
            $total_discount = 0;
            $total_amount = 0;
            foreach ($invoice_items as $invoice_item) {
                $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;

                $lineItemItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItemItem;
                $lineItemItem->setName((strlen($invoice_item->title) > 50) ? substr($invoice_item->title, 0, 47) . '...' : $invoice_item->title);
                $lineItemItem->setCode($invoice_item->item_id ? $invoice_item->item_id : uniqid('VA-ITM-'));

                $lineItem->setItem($lineItemItem);
                $lineItem->setDescription($invoice_item->description ? $invoice_item->description : "-");
                $lineItem->setQuantity((float)$invoice_item->quantity);
                $unit_amount = 0;
                if ($invoice->invoice_type == 'gross_claim') {
                    $commission = (float)calc_per((float)$invoice_item->rate, (float)$invoice_item->commission);

                    $unit_amount = $commission;
                    $lineItem->setUnitAmount($commission);
                } else {
                    $unit_amount = (float)$invoice_item->rate;
                    $lineItem->setUnitAmount($unit_amount);
                }

                if ($invoice_item->taxable && $default_tax) {
                    $unit_tax = calc_per($unit_amount, $default_tax->percentage);
                    $total_tax += $unit_tax;
                    $lineItem->setTaxAmount($unit_tax);
                }
                $total_amount += $unit_amount;
                $lineItem->setAccountCode($xero_account_id);
                array_push($lineItems, $lineItem);
            }

            if (count($lineItems) == 0) {
                $lineItems = null;
            }

            if ($invoice->discount_amount) {
                if ($invoice->discount_amount_type == 'fixed_amount') {
                    $total_discount = $invoice->discount_amount;
                } else {
                    $total_discount = calc_per($total_amount, $invoice->discount_amount);
                }
            }

            $dateValue = $invoice->bill_date;
            $dueDateValue = $invoice->due_date;

            $_invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
            $_invoice->setType(\XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCREC);
            $_invoice->setContact($contact);
            $_invoice->setDate($dateValue);
            $_invoice->setDueDate($dueDateValue);
            $_invoice->setLineItems($lineItems);
            $_invoice->setTotalTax($total_tax);
            $_invoice->setIsDiscounted($total_discount > 0 ? true : false);
            $_invoice->setTotalDiscount($total_discount);
            $_invoice->setReference(html_decode($invoice->note));
            $_invoice->setStatus(\XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_DRAFT);

            $invoices = new \XeroAPI\XeroPHP\Models\Accounting\Invoices;
            $arr_invoices = [];
            array_push($arr_invoices, $_invoice);
            $invoices->setInvoices($arr_invoices);

            $xero_invoice_id = $this->_xero_create_invoice($invoices);

            if ($xero_invoice_id) {
                $invoice_data = array('xero_invoice_id' => $xero_invoice_id);
                $this->Invoices_model->ci_save($invoice_data, $invoice_id);

                $this->_handle_income_sharing($invoice_id, $invoice->project_id);
            }
        }

        echo json_encode(array('success' => true, 'message' => "XERO Invoice created Successfully"));
    }

    function create_invoice_modal_form()
    {
        $invoice_id = $this->request->getPost('invoice_id');
        validate_numeric_value($invoice_id);

        $storage = new XEROSessionStorage();

        $view_data['invoice_id'] = $invoice_id;
        $view_data['label_column'] = "col-md-2";
        $view_data['field_column'] = "col-md-10";

        $invoice = $this->Invoices_model->get_one($invoice_id);
        if ($storage->isAccessTokenExpired()) {
            $view_data['xero_accounts_dropdown'] = array();
            $view_data['xero_session_expired'] = TRUE;
        } elseif (!is_null($invoice->xero_invoice_id)) {
            $view_data['xero_accounts_dropdown'] = array();
            $view_data['xero_invoice_available'] = TRUE;
        } else {
            $accounts = $this->_xero_get_accounts($storage);

            $_accounts = array();
            foreach ($accounts->getAccounts() as $account) {
                if ($account) {
                    $name = $account->getName();
                    $description = $account->getDescription();
                    $account_type = $account->getType();
                    $_accounts[] = array(
                        'id' => $account->getCode(),
                        'text' => $name,
                        'html' => "<div><p>$name<strong>($account_type)</strong></p><small>$description</small></div>",
                        'title' => $name
                    );
                }
            }

            $view_data['xero_accounts_dropdown'] = $_accounts;
        }

        return $this->template->view('invoices/xero/modal_form', $view_data);
    }

    private function _log($data)
    {
        $source = $_SERVER["DOCUMENT_ROOT"] . '/' . get_logs_file_path("import") . 'xero_api.log';
        file_put_contents($source, json_encode($data), FILE_APPEND);
    }

    private function _xero_create_invoice($invoices = array())
    {
        try {
            $storage = new XEROSessionStorage();

            $xeroTenantId = $storage->getXeroTenantId();
            $summarizeErrors = true;
            $unitdp = 4;
            $idempotencyKey = make_random_string(64);

            $xero_api = $this->_xero_finance_api($storage->getAccessToken());
            $result = $xero_api->createInvoices($xeroTenantId, $invoices, $summarizeErrors, $unitdp, $idempotencyKey);

            $xero_invoices = $result->getInvoices();
            if ($xero_invoices) {
                $xero_invoice_id = $xero_invoices[0]->getInvoiceId();

                return $xero_invoice_id;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // echo $e->getMessage(), PHP_EOL;
            return null;
        }
    }

    private function _xero_contact_id($client_id)
    {
        $client = $this->Clients_model->get_one($client_id);

        if ($client && $client->xero_contact_id) {
            return $client->xero_contact_id;
        } else {
            $storage = new XEROSessionStorage();
            $xero_api = $this->_xero_finance_api($storage->getAccessToken());
            $xeroTenantId = $storage->getXeroTenantId();
            $where = 'Name=="' . $this->get_client_full_name(0, $client) . " " . "VA" . $client->id . '"';

            try {
                $result = $xero_api->getContacts($xeroTenantId, null, $where, null);

                if ($result) {
                    return $result->getContacts()[0]->getContactId();
                }
            } catch (\Exception $e) {
                // echo $e->getMessage(), PHP_EOL;
            }

            $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName($this->get_client_full_name(0, $client) . " " . "VA" . $client->id)
                ->setFirstName($client->first_name)
                ->setLastName($client->last_name)
                ->setEmailAddress($client->email);

            $arr_contacts = [];
            array_push($arr_contacts, $contact);
            $contacts = new \XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);

            try {
                $apiResponse = $xero_api->createContacts($xeroTenantId, $contacts, false);
                $contact_id = $apiResponse->getContacts()[0]->getContactId();
            } catch (\Exception $e) {
                // echo $e->getMessage(), PHP_EOL;
            }

            $client_data = array(
                'xero_contact_id' => $contact_id
            );
            $this->Clients_model->ci_save($client_data, $client_id);

            return $contact_id;
        }
    }

    private function _xero_get_accounts($storage = new XEROSessionStorage())
    {
        $xero_api = $this->_xero_finance_api($storage->getAccessToken());
        $xeroTenantId = $storage->getXeroTenantId();
        $ifModifiedSince = new \DateTime("2020-02-06T12:17:43.202-08:00");
        $where = 'Status=="' . \XeroAPI\XeroPHP\Models\Accounting\Account::STATUS_ACTIVE . '"';
        $order = "Name ASC";

        try {
            $result = $xero_api->getAccounts($xeroTenantId, $ifModifiedSince, $where, $order);

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL;
            return array();
        }
    }

    private function _xero_provider($invoice_id = 0)
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => get_setting("xero_api_client_id"),
            'clientSecret'            => get_setting("xero_api_client_secret"),
            'redirectUri'             => get_setting("xero_api_redirect_uri"),
            'urlAuthorize'            => get_setting("xero_api_url_authorize"),
            'urlAccessToken'          => get_setting("xero_api_url_access_token"),
            'urlResourceOwnerDetails' => get_setting("xero_api_url_resource_owner_details")
        ]);

        if ($invoice_id) {
            $_SESSION['XERO_REDIRECT_URL'] = get_uri('invoices/view/' . $invoice_id);
        } else {
            $_SESSION['XERO_REDIRECT_URL'] = null;
        }

        return $provider;
    }

    private function _xero_finance_api($access_token)
    {
        $config = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken($access_token);

        $apiInstance = new \XeroAPI\XeroPHP\Api\AccountingApi(
            new \GuzzleHttp\Client(),
            $config
        );

        return $apiInstance;
    }

    private function _handle_income_sharing($invoice_id = 0, $project_id = 0)
    {
        $income_sharing = $this->Invoice_incomes_model->get_details(array('invoice_id' => $invoice_id))->getResult();
        if ($income_sharing && count($income_sharing)) {
            foreach ($income_sharing as $_income_sharing) {
                $this->Invoice_incomes_model->delete($_income_sharing->id);
            }
        }

        $income_sharing_partners = $this->Project_partners_model->get_details(array('project_id' => $project_id, 'only_partner_types' => 'subagent,referral'))->getResult();
        if (count($income_sharing_partners)) {
            $invoice_meta_info = $this->Invoices_model->get_invoice_total_meta($invoice_id);
            $net_total_income = $invoice_meta_info->net_total_income; // net income after discount deduction
            foreach ($income_sharing_partners as $partner) {

                $shared_income = calc_per($net_total_income, $partner->commission);

                
                $tax = 0;
                // if ($partner->partner_type == 'subagent') { // GST tax will only be given to subagents
                //     $default_tax_info = $this->Taxes_model->get_details(array('is_default' => true))->getRow();
                //     $tax = calc_per($shared_income, $default_tax_info->percentage);
                // }

                $income_sharing_data = array(
                    'invoice_id' => $invoice_id,
                    'partner_id' => $partner->partner_id,
                    'commission' => $partner->commission,
                    'amount' => $shared_income,
                    'tax' => $tax,
                    'status' => 'not_initiated',
                    'created_date' => get_current_utc_time()
                );

                $this->Invoice_incomes_model->ci_save($income_sharing_data);
            }
        }
    }
}

/* End of file Xero_api.php */
/* Location: ./app/controllers/Xero_api.php */

// 5859c8b4-0e96-4727-8b51-699ec9885df2