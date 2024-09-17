<?php

$invoice_statuses_dropdown = array(
    array("id" => "", "text" => "- " . app_lang("status") . " -"),
    array("id" => "not_paid", "text" => app_lang("not_paid")),
    array("id" => "fully_paid", "text" => app_lang("fully_paid"))
);
echo json_encode($invoice_statuses_dropdown);
