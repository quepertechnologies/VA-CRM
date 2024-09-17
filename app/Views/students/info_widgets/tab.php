<?php
$card = "";
$icon = "";
$value = "N/A";
$link = "";

$view_type = "client_dashboard";
if ($login_user->user_type == "staff") {
    $view_type = "";
}

if (!is_object($client_info)) {
    $client_info = new stdClass();
    $client_info->id = 0;
    $client_info->total_projects = 0;
    $client_info->invoice_value = 0;
    $client_info->currency_symbol = "$";
    $client_info->payment_received = 0;
}


if ($tab == "projects") {
    $card = "bg-info";
    $icon = "globe";
    $tab = 'Nationality'; // This is not a language and going custom for now
    if (isset($nationality)) {
        if ($student_onshore == 1) {
            $tab = 'The Student is onshore';
            $value = $nationality;
        } else {
            $value = $nationality;
        }
    }
    if ($view_type == "client_dashboard") {
        $link = "";
    } else {
        $link = "";
    }
} else if ($tab == "total_invoiced") {
    $card = "bg-primary";
    $icon = "book-open";
    $tab = 'Visa Info'; // This is not a language and going custom for now
    if (property_exists($client_info, "visa_type") && !empty($client_info->visa_type)) {
        $value = 'Subclass ' . $client_info->visa_type;
    } else {
        $value = "N/A";
    }

    if (property_exists($client_info, "visa_expiry") && !empty($client_info->visa_expiry)) {

        $tab = 'Expires at ' . format_to_date($client_info->visa_expiry);
    }
    // if ($view_type == "client_dashboard") {
    //     $link = get_uri('invoices/index');
    // } else {
    //     $link = get_uri('students/view/' . $client_info->id . '/info');
    // }
    $link = get_uri('students/view/' . $client_info->id . '/info');
} else if ($tab == "payments") {
    $card = "bg-success";
    $icon = "user";
    $tab = 'Assignee'; // This is not a language and going custom for now
    if (isset($assignee_full_name)) {
        $value = $assignee_full_name;
    }
    // if ($view_type == "client_dashboard") {
    //     $link = get_uri('invoice_payments/index');
    // } else {
    //     $link = get_uri('students/view/' . $client_info->id . '/tasks');
    // }
    $link = get_uri('students/view/' . $client_info->id . '/tasks');
} else if ($tab == "due") {
    $card = "bg-coral";
    $icon = "help-circle";
    $tab = 'Source'; // This is not a language and going custom for now
    if (property_exists($client_info, "source") && $client_info->source) {
        $value = $client_info->source;
    }
    // if ($view_type == "client_dashboard") {
    //     $link = get_uri('invoices/index');
    // } else {
    //     $link = get_uri('students/view/' . $client_info->id . '/invoices');
    // }
    $link = get_uri('students/view/' . $client_info->id . '/invoices');
}
?>

<a href="<?php echo $link; ?>" class="white-link">
    <div class="card dashboard-icon-widget">
        <div class="card-body">
            <div class="widget-icon <?php echo $card ?>">
                <i data-feather="<?php echo $icon; ?>" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo strlen($value) > 18 ? substr($value, 0, 16) . '...' : $value; ?></h1>
                <span class="bg-transparent-white"><?php echo $tab; ?></span>
            </div>
        </div>
    </div>
</a>