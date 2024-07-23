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

    function create_invoice()
    {
        $invoice_id = $this->request->getPost('invoice_id');
        $xero_account_id = $this->request->getPost('xero_account_id');

        validate_numeric_value($invoice_id);

        $invoice = $this->Invoices_model->get_one($invoice_id);

        if ($invoice) {
            if ($invoice->xero_invoice_id) {
                echo json_encode(array('success' => true, 'message' => "This Invoice is already created on XERO"));
            }
            $invoice_options = array('invoice_id' => $invoice_id);
            $invoice_items = $this->Invoice_items_model->get_details($invoice_options)->getResult();

            $xero_contact_id = $this->_xero_contact_id($invoice->client_id);

            $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setContactID($xero_contact_id);

            $lineItems = [];
            foreach ($invoice_items as $invoice_item) {
                $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
                $lineItem->setDescription($invoice_item->title);
                $lineItem->setQuantity((float)$invoice_item->quantity);
                $lineItem->setUnitAmount((float)$invoice_item->rate);
                $lineItem->setAccountCode($xero_account_id);
                array_push($lineItems, $lineItem);
            }

            if (count($lineItems) == 0) {
                $lineItems = null;
            }

            $dateValue = $invoice->bill_date;
            $dueDateValue = $invoice->due_date;
            $_invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
            $_invoice->setType(\XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCREC);
            $_invoice->setContact($contact);
            $_invoice->setDate($dateValue);
            $_invoice->setDueDate($dueDateValue);
            $_invoice->setLineItems($lineItems);
            $_invoice->setReference($invoice->note);
            $_invoice->setStatus(\XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_DRAFT);

            $invoices = new \XeroAPI\XeroPHP\Models\Accounting\Invoices;
            $arr_invoices = [];
            array_push($arr_invoices, $_invoice);
            $invoices->setInvoices($arr_invoices);

            $xero_invoice_id = $this->_xero_create_invoice($invoices);

            $invoice_data = array('xero_invoice_id' => $xero_invoice_id);
            $this->Invoices_model->ci_save($invoice_data, $invoice_id);
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
            echo $e->getMessage(), PHP_EOL;
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
            $where = 'Name=="' . "VA" . $client->id . " " . $this->get_client_full_name(0, $client) . '"';

            try {
                $result = $xero_api->getContacts($xeroTenantId, null, $where, null);

                if ($result) {
                    return $result->getContacts()[0]->getContactId();
                }
            } catch (\Exception $e) {
                // echo $e->getMessage(), PHP_EOL;
            }

            $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName("VA" . $client->id . " " . $this->get_client_full_name(0, $client))
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
}

/* End of file Xero_api.php */
/* Location: ./app/controllers/Xero_api.php */

// 5859c8b4-0e96-4727-8b51-699ec9885df2