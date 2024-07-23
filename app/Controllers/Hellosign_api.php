<?php

namespace App\Controllers;

class Hellosign_api extends App_Controller
{
    protected $request;

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        show_404();
        // echo 'Hellosign_api';
    }

    function handleHellosign_callback()
    {
        // $_POST = json_decode(file_get_contents("php://input"), true);
        if (array_key_exists('json', $_POST)) {
            $data = json_decode($_POST['json'], true);
            $callback_event = \Dropbox\Sign\Model\EventCallbackRequest::init($data);

            $api_key = get_setting("dropbox_hellosign_api_key");
            // verify that a callback came from HelloSign.com
            if (\Dropbox\Sign\EventCallbackHelper::isValid($api_key, $callback_event)) {
                // one of "account_callback" or "api_app_callback"
                // $callback_type = \Dropbox\Sign\EventCallbackHelper::getCallbackType($callback_event);
                $event = $callback_event->getEvent();
                $event_type = $event->getEventType();

                switch ($event_type) {
                    case 'callback_test':
                        # test callback...
                        break;
                    case 'signature_request_all_signed':

                        $signature_request = $this->get_signature_request($data);

                        if ($signature_request) {
                            $metadata =  $signature_request->getMetadata();

                            if ($metadata) {
                                $contract_id = get_array_value($metadata, 'contract_id');
                                $project_id = get_array_value($metadata, 'project_id');
                                $milestone_id = get_array_value($metadata, 'milestone_id');
                                $title = $signature_request->getTitle() ?? "";

                                $signed_by = "";
                                $signatures = $signature_request->getSignatures();

                                if (count($signatures) === 1) {
                                    $signature = $signatures[0];

                                    $status_code = $signature->getStatusCode();

                                    if ($status_code == 'signed') {
                                        $name = $signature->getSignerName();
                                        $email = $signature->getSignerEmailAddress();

                                        $signed_by = $name . ' (' . $email . ')';
                                    }
                                }

                                $activity_data = array(
                                    'status' => 'signed',
                                    'subject' => $title,
                                    'contract_id' => $contract_id,
                                    'project_id' => $project_id,
                                    'milestone_id' => $milestone_id,
                                    'signed_by' => $signed_by,
                                    'created_by' => 0
                                );

                                $this->Contract_activity_model->ci_save($activity_data);
                            }
                        }

                        break;
                    case 'signature_request_declined':

                        $signature_request = $this->get_signature_request($data);

                        if ($signature_request) {
                            $metadata =  $signature_request->getMetadata();

                            if ($metadata) {
                                $contract_id = get_array_value($metadata, 'contract_id');
                                $project_id = get_array_value($metadata, 'project_id');
                                $milestone_id = get_array_value($metadata, 'milestone_id');
                                $title = $signature_request->getTitle() ?? "";

                                $declined_by = "";
                                $signatures = $signature_request->getSignatures();

                                if (count($signatures) === 1) {
                                    $signature = $signatures[0];

                                    $status_code = $signature->getStatusCode();

                                    if ($status_code == 'declined') {
                                        $name = $signature->getSignerName();
                                        $email = $signature->getSignerEmailAddress();

                                        $declined_by = $name . ' (' . $email . ')';
                                    }
                                }

                                $activity_data = array(
                                    'status' => 'declined',
                                    'subject' => $title,
                                    'contract_id' => $contract_id,
                                    'project_id' => $project_id,
                                    'milestone_id' => $milestone_id,
                                    'declined_by' => $declined_by,
                                    'created_by' => 0
                                );

                                $this->Contract_activity_model->ci_save($activity_data);
                            }
                        }

                        break;
                    case 'signature_request_canceled':
                        # test callback...
                        break;
                    case 'signature_request_expired':
                        # test callback...
                        break;

                    default:
                        # code...
                        break;
                }
                return response()
                    ->setContentType('application/json')
                    ->setStatusCode(200)
                    ->setBody('Hello API event received'); // do not change body response. HelloSign expects this string as a successful callback
            }
        }
        return response()
            ->setContentType('application/json')
            ->setStatusCode(417)->setBody('invalid operation');
    }

    function get_signature_request($data)
    {

        $signature_request = \Dropbox\Sign\Model\SignatureRequestGetResponse::init($data);

        if ($signature_request) {
            return $signature_request->getSignatureRequest();
        } else {
            return null;
        }
    }
}

/* End of file Hellosign_api.php */
/* Location: ./app/controllers/Hellosign_api.php */