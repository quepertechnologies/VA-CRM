<?php
$card = "";
$icon = "";
$value = "";
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
    $icon = "grid";
    $tab = 'Application Status'; // This is not a language and going custom for now
    if (property_exists($client_info, "total_projects")) {
        $value = to_decimal_format($client_info->total_projects);
    }
    if ($view_type == "client_dashboard") {
        $link = get_uri('projects/index');
    } else {
        $link = get_uri('clients/view/' . $client_info->id . '/projects');
    }
    $value = 'Processing'; // This is not a language and going custom for now
} else if ($tab == "total_invoiced") {
    $card = "bg-primary";
    $icon = "file-text";
    $tab = 'Current Visa Status'; // This is not a language and going custom for now
    if (property_exists($client_info, "invoice_value")) {
        $value = to_currency($client_info->invoice_value, $client_info->currency_symbol);
    }
    if ($view_type == "client_dashboard") {
        $link = get_uri('invoices/index');
    } else {
        $link = get_uri('clients/view/' . $client_info->id . '/invoices');
    }
   $value = 'Subclass 500'; // This is not a language and going custom for now 
} else if ($tab == "payments") {
    $card = "bg-success";
    $icon = "check-square";
    $tab = 'Active Tasks'; // This is not a language and going custom for now
    if (property_exists($client_info, "payment_received")) {
        $value = to_currency($client_info->payment_received, $client_info->currency_symbol);
    }
    if ($view_type == "client_dashboard") {
        $link = get_uri('invoice_payments/index');
    } else {
        $link = get_uri('clients/view/' . $client_info->id . '/payments');
    }
    $value = '0'; // This is not a language and going custom for now
} else if ($tab == "due") {
    $card = "bg-coral";
    $icon = "compass";
    $tab = 'Total Invoices'; // This is not a language and going custom for now
    if (property_exists($client_info, "invoice_value")) {
        $value = to_currency(ignor_minor_value($client_info->invoice_value - $client_info->payment_received), $client_info->currency_symbol);
    }
    if ($view_type == "client_dashboard") {
        $link = get_uri('invoices/index');
    } else {
        $link = get_uri('clients/view/' . $client_info->id . '/invoices');
    }
}
?>

<a href="<?php echo $link; ?>" class="white-link">
    <div class="card dashboard-icon-widget">
        <div class="card-body">
            <div class="widget-icon <?php echo $card ?>">
                <i data-feather="<?php echo $icon; ?>" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $value; ?></h1>
                <span class="bg-transparent-white"><?php echo $tab; ?></span>
            </div>
        </div>
    </div>
</a>