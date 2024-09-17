<div id="invoice-status-bar">
    <h6 class="mb15"><strong><?php echo app_lang("invoice_info"); ?> </strong></h6>
    <ul class="list-group list-group-flush">
        <?php if ($invoice_info->type == "invoice") { ?>
            <li class="d-flex border-top justify-content-between pt-2">
                <p><?php echo app_lang("status"); ?></p>
                <strong><?php echo $invoice_status_label; ?></strong>
            </li>
        <?php } ?>
        <?php if ($invoice_info->labels_list) { ?>
            <li class="d-flex border-top justify-content-between pt-2">
                <p><?php echo app_lang("label"); ?></p>
                <strong><?php echo make_labels_view_data($invoice_info->labels_list, "", true); ?></strong>
            </li>
        <?php } ?>

        <li class="d-flex border-top justify-content-between pt-2">
            <p><?php echo app_lang("last_email_sent"); ?></p>
            <strong><?php echo (is_date_exists($invoice_info->last_email_sent_date)) ? format_to_date($invoice_info->last_email_sent_date, FALSE) : app_lang("never"); ?></strong>
        </li>

        <?php if ($invoice_info->recurring_invoice_id) { ?>
            <li class="d-flex border-top justify-content-between pt-2">
                <p><?php echo app_lang("created_from"); ?></p>
                <strong><?php echo anchor(get_uri("invoices/view/" . $invoice_info->recurring_invoice_id), get_invoice_id($invoice_info->recurring_invoice_id)); ?></strong>
            </li>
        <?php } ?>
        <?php if ($invoice_info->subscription_id) { ?>
            <li class="d-flex border-top justify-content-between pt-2">
                <p><?php echo app_lang("created_from"); ?></p>
                <strong><?php echo anchor(get_uri("subscriptions/view/" . $invoice_info->subscription_id), get_subscription_id($invoice_info->subscription_id)); ?></strong>
            </li>
        <?php } ?>
        <?php if ($invoice_info->estimate_id && $login_user->is_admin) { ?>
            <li class="d-flex border-top justify-content-between pt-2">
                <p><?php echo app_lang("created_from"); ?></p>
                <strong><?php echo anchor(get_uri("estimates/view/" . $invoice_info->estimate_id), get_estimate_id($invoice_info->estimate_id)); ?></strong>
            </li>
        <?php } ?>

        <?php if ($invoice_info->cancelled_at) { ?>
            <li class="d-flex border-top justify-content-between pt-2">
                <p><?php echo app_lang("cancelled_at"); ?></p>
                <strong><?php echo format_to_relative_time($invoice_info->cancelled_at); ?></strong>
            </li>
        <?php } ?>

        <?php if ($invoice_info->cancelled_by) { ?>
            <li class="d-flex border-top justify-content-between pt-2">
                <p><?php echo app_lang("cancelled_by"); ?></p>
                <strong><?php echo get_team_member_profile_link($invoice_info->cancelled_by, $invoice_info->cancelled_by_user); ?></strong>
            </li>
        <?php } ?>
    </ul>
</div>