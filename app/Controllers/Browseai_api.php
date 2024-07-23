<?php

namespace App\Controllers;

class Browseai_api extends App_Controller
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

  function handleBrowseAI_callback()
  {
    $data = $this->request->getJSON(true);
    if ($data) {
      $event = get_array_value($data, 'event');

      if ($event == 'task.finishedSuccessfully') {
        $task = get_array_value($data, 'task');

        $capturedList = get_array_value($task, 'capturedLists');
        $capturedText = get_array_value($task, 'capturedTexts');

        $client_activities = get_array_value($capturedList, 'client_activities');
        $client_notes = get_array_value($capturedList, 'client_notes');
        $application_activities = get_array_value($capturedList, 'application_activities');
        $application_notes = get_array_value($capturedList, 'application_notes');
        $application_schedules = get_array_value($capturedList, 'application_schedules');
        $application_fees = get_array_value($capturedList, 'application_fees');

        if ($client_activities && count($client_activities)) {
          // $internal_id = get_array_value($capturedText, 'internal_id');
          // $added_by = get_array_value($capturedText, 'added_by');
          $email = get_array_value($capturedText, 'email');
          $client_options = array('email' => $email);
          $client = $this->Clients_model->get_details($client_options)->getRow();

          foreach ($client_activities as $activity) {
            $date = get_array_value($activity, 'date');
            $_activity = get_array_value($activity, 'activity');
            $title = get_array_value($activity, 'title');

            if ($client && $_activity && $title) {

              $timeline_data = array(
                'client_id' => $client->id,
                'title' => $title,
                'caption' => $_activity,
                'created_at' => date_format(date_create_from_format("d M Y, h:i A", $date), "Y-m-d h:m:s")
              );
              $this->Timeline_model->ci_save($timeline_data);

              // $data = array(
              //   'created_at' => date_format(date_create_from_format("d M Y, h:i A", $date), "Y-m-d"),
              //   'created_by' => 0,
              //   'action' => 'created',
              //   'log_type' => 'activity',
              //   'log_type_title' => $_activity,
              //   'log_type_id' => 0,
              //   'changes' => serialize(array('content' => array('from' => '', 'to' => $title))),
              //   'log_for' => 'client',
              //   'log_for_id' => $client->id,
              //   'log_for2' => '',
              //   'log_for_id2' => 0,
              //   'log_for3' => '',
              //   'log_for_id3' => 0,
              //   'deleted' => 0
              // );

              // $this->Activity_logs_model->ci_save($data);
            }
          }
        }

        if ($client_notes && count($client_notes)) {
        }
      }

      return response()
        ->setContentType('application/json')
        ->setStatusCode(200, 'OK')
        ->setBody(array('status' => 'success', 'data' => json_encode($data)));
    }

    return response()
      ->setContentType('application/json')
      ->setStatusCode(417, "Failed")
      ->setBody(array('status' => 'failed'));
  }

  function _log($data)
  {
    $source = $_SERVER["DOCUMENT_ROOT"] . '/' . get_logs_file_path("import") . 'browse_ai.log';
    file_put_contents($source, json_encode($data), FILE_APPEND);
  }

  function _browse_ai_api($data = null, $config = array('rt' => 'GET', 'p' => 'status'))
  {
    $curl = curl_init();

    $api_key = get_setting("browse_ai_api_key");

    $p = get_array_value($config, 'p');
    $rt = get_array_value($config, 'rt');

    $url = "https://api.browse.ai/v2/$p";

    $options = array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 60,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $rt,
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $api_key"
      ],
    );

    if ($rt == 'POST' || $rt == "PUT") {
      $options[CURLOPT_POSTFIELDS] = json_encode($data);
    }

    curl_setopt_array($curl, $options);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return false;
    } else {
      return $response;
    }
  }
}

/* End of file Browseai_api.php */
/* Location: ./app/controllers/Browseai_api.php */