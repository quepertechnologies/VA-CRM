<div><b><?php echo app_lang("bill_to"); ?></b></div>
<?php if (get_setting("invoice_style") != "style_3") { ?>
    <div class="b-b" style="line-height: 2px; border-bottom: 1px solid #f2f4f6;"> </div>
<?php } ?>

<?php
if ($invoice_info->invoice_type === 'gross_claim' || $invoice_info->invoice_type === 'net_claim') {
    $_client_info = $institute;
} else {
    $_client_info = $client_info;
} ?>

<div style="line-height: 3px;"> </div>
<strong><?php
        echo $_client_info->company_name ? $_client_info->company_name : $_client_info->first_name . ' ' . $_client_info->last_name;
        ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta text-default" style="font-size: 90%; color: #666;">
    <?php if ($_client_info->address || $_client_info->vat_number || (isset($_client_info->custom_fields) && $_client_info->custom_fields)) { ?>
        <div><?php echo nl2br($_client_info->address ? $_client_info->address : ""); ?>
            <?php if ($_client_info->city) { ?>
                <br /><?php echo $_client_info->city; ?>
            <?php } ?>
            <?php if ($_client_info->state) { ?>
                <br /><?php echo $_client_info->state; ?>
            <?php } ?>
            <?php if ($_client_info->zip) { ?>
                <br /><?php echo $_client_info->zip; ?>
            <?php } ?>
            <?php if ($_client_info->country) { ?>
                <br /><?php echo $_client_info->country; ?>
            <?php } ?>
            <?php if ($_client_info->vat_number || $_client_info->gst_number) { ?>
                <?php if ($_client_info->vat_number) { ?>
                    <br /><?php echo app_lang("vat_number") . ": " . $_client_info->vat_number; ?>
                <?php } else { ?>
                    <br /><?php echo app_lang("gst_number") . ": " . $_client_info->gst_number; ?>
                <?php } ?>
            <?php } ?>
            <?php
            if (isset($_client_info->custom_fields) && $_client_info->custom_fields) {
                foreach ($_client_info->custom_fields as $field) {
                    if ($field->value) {
                        echo "<br />" . $field->custom_field_title . ": " . view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value));
                    }
                }
            }
            ?>


        </div>
    <?php } ?>
</span>