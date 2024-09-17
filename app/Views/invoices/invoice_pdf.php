<div style=" margin: auto;">
    <?php
    $is_general = $invoice_info->invoice_type == 'general' ? true : false;
    $colspan = $is_general ? 3 : 4;
    // $show_taxable = false;
    // if (get_setting('taxable_column') == "always_show") {
    //     $show_taxable = true;
    //     $colspan = 4;
    // } else if (get_setting('taxable_column') == "never_show") {
    //     $show_taxable = false;
    // } else {
    //     $taxable_fields = array();
    //     foreach ($invoice_items as $item) {
    //         $taxable_fields[] = $item->taxable;
    //     }
    //     if (count(array_unique($taxable_fields)) == 2) {
    //         $show_taxable = true;
    //         $colspan = 4;
    //     }
    // }


    $color = get_setting("invoice_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $invoice_style = get_setting("invoice_style");
    $data = array(
        "client_info" => $client_info,
        "color" => $color,
        "invoice_info" => $invoice_info
    );

    if ($invoice_style === "style_3") {
        echo view('invoices/invoice_parts/header_style_3.php', $data);
    } else if ($invoice_style === "style_2") {
        echo view('invoices/invoice_parts/header_style_2.php', $data);
    } else {
        echo view('invoices/invoice_parts/header_style_1.php', $data);
    }

    $discount_row = '<tr>
                        <td colspan="' . $colspan . '" style="text-align: right;">' . app_lang("discount") . '</td>
                        <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                    </tr>';

    $total_after_discount_row = '<tr>
                                    <td colspan="' . $colspan . '" style="text-align: right;">' . app_lang("total_after_discount") . '</td>
                                    <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                                </tr>';
    ?>
</div>

<br />
<table class="table-responsive" style="width: 100%; color: #444; font-size: 13.5px;">
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 43%; border-right: 1px solid #eee;"> <?php echo app_lang("item"); ?> </th>
        <th style="text-align: right;  width: 13%; border-right: 1px solid #eee;"> <?php echo app_lang("fee"); ?></th>
        <?php if (!$is_general) { ?><th style="text-align: right;  width: 13%; border-right: 1px solid #eee;"> <?php echo app_lang("commission"); ?></th><?php } ?>
        <th style="text-align: center;  width: 13%; border-right: 1px solid #eee;"> <?php echo app_lang("tax"); ?></th>
        <th style="text-align: right;  width: <?php echo !$is_general ? "18%" : "31%"; ?>; "> <?php echo app_lang("total"); ?></th>
    </tr>
    <?php
    foreach ($invoice_items as $item) {
        $is_general = $invoice_info->invoice_type == 'general' ? true : false;
        $cur = $invoice_total_summary->currency_symbol;
        $item_commission = "0% (No Commission)";
        $commission_amount = 0;
        $total_amount = to_currency($item->total, $cur);
        // $item_tax_amount = to_currency(0, $cur);
        if ($item->commission > 0) {
            $item_commission = "(" . $item->commission . '%)';
            $commission_amount = calc_per($item->rate, $item->commission);
        }

        $tax_title = "(No Tax)";
        $item_tax_amount = to_currency(0, $cur);
        $tax_amount = 0;
        $total_amount = to_currency(0, $cur);
        if ($invoice_info->invoice_type == 'general') {
            if ($item->taxable) {
                $tax_title = $invoice_total_summary->tax_name;
                $tax_per = $invoice_total_summary->tax_percentage;
                $tax_amount = calc_per($item->rate, $tax_per);
                $item_tax_amount = to_currency($tax_amount, $cur);
            }
            $total_amount = to_currency((float)$item->rate + $tax_amount, $cur);
        } elseif ($invoice_info->invoice_type == 'gross_claim') {
            if ($item->taxable) {
                $tax_title = $invoice_total_summary->tax_name;
                $tax_per = $invoice_total_summary->tax_percentage;
                $tax_amount = calc_per($commission_amount, $tax_per);
                $item_tax_amount = to_currency($tax_amount, $cur);
            }
            $total_amount = to_currency((float)$commission_amount + $tax_amount, $cur);
        } elseif ($invoice_info->invoice_type == 'net_claim') {
            if ($item->taxable) {
                $tax_title = $invoice_total_summary->tax_name;
                $tax_per = $invoice_total_summary->tax_percentage;
                $tax_amount = calc_per($commission_amount, $tax_per);
                $item_tax_amount = to_currency($tax_amount, $cur);
            }
            $total_amount = to_currency((float)$item->rate - ($commission_amount + $tax_amount), $cur);
        }
    ?>
        <tr style="background-color: #f4f4f4;">
            <td style="width: 43%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description ? $item->description : ""); ?></span>
            </td>
            <td style="text-align: right; width: 13%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            <?php if (!$is_general) { ?><td style="text-align: center; width: 13%; border: 1px solid #fff;"> <?php echo '<small>' . $item_commission . '</small><br>' . to_currency($commission_amount, $item->currency_symbol); ?></td><?php } ?>
            <td style="text-align: center; width: 13%; border: 1px solid #fff;"> <?php echo '<small>' . $tax_title . '</small><br>' . $item_tax_amount; ?></td>
            <td style="text-align: right; width: <?php echo !$is_general ? '18%' : '31%'; ?>; border: 1px solid #fff;"> <?php echo $total_amount; ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><b><?php echo $invoice_info->invoice_type == 'net_claim' ? app_lang('total_fee') : app_lang("sub_total"); ?></b></td>
        <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">
            <b><?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?></b>
        </td>
    </tr>
    <?php
    if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "before_tax" && $is_general) {
        echo $discount_row . $total_after_discount_row;
    }
    ?>
    <?php if ($invoice_total_summary->commission_total && $invoice_info->invoice_type == 'net_claim') { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo app_lang('commission_claimed'); ?></td>
            <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_meta->commission_total, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo $invoice_total_summary->tax_name; ?></td>
            <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->tax2, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax3) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo $invoice_total_summary->tax_name3; ?></td>
            <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->tax3, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->invoice_total_incl_tax) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><b><?php echo $invoice_info->invoice_type == 'net_claim' ? app_lang('commission_claimed') . ' (Incl. Tax)' : app_lang('total_amount') . " (Incl. Tax)"; ?></b></td>
            <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">
                <b><?php echo to_currency($invoice_total_meta->invoice_total_incl_tax, $invoice_total_summary->currency_symbol); ?></b>
            </td>
        </tr>
    <?php } ?>
    <?php
    if ($invoice_total_summary->discount_total && ($invoice_total_summary->discount_type == "after_tax" || $invoice_total_summary->discount_type == "on_income" && $is_general)) {
        echo $discount_row;
    }
    ?>
    <?php if ($invoice_total_summary->total_paid && $invoice_info->invoice_type !== 'net_claim') { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo app_lang("paid"); ?></td>
            <td style="text-align: right; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><b><?php echo $invoice_info->invoice_type == 'net_claim' ? app_lang('total_payment') . ' (To Partner)' : app_lang("balance_due"); ?></b></td>
        <td style="text-align: right; background-color: <?php echo $color; ?>; color: #fff;">
            <b><?php echo to_currency($invoice_info->invoice_type == 'net_claim' ? $invoice_total_summary->invoice_total : $invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?></b>
        </td>
    </tr>
</table>
<?php if (isset($project_info) && isset($client_info) && $invoice_info->invoice_type !== 'general') { ?>
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br />
        <p><span style="color: black; border-bottom: 2px solid #f2f2f2; padding-bottom: 5px">Client Details</span><br />
            <?php echo _get_client_full_name(0, $client_info) ?><br />
            ID: <?php echo $project_info->partner_client_id ? $project_info->partner_client_id : '-' ?><br />
            Course: <?php echo $project_info->title ? $project_info->title : '-' ?><br />
            <!-- E-mail: <?php echo $client_info->email ?><br />
            tel: <?php echo $client_info->phone ? $client_info->phone_code . '' . $client_info->phone : '-' ?> -->
        </p>
    </div>
<?php } ?>
<?php if ($invoice_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br /><?php echo nl2br(process_images_from_content($invoice_info->note)); ?></div>
<?php } else { ?> <!-- use table to avoid extra spaces -->
    <br /><br />
    <table class="invoice-pdf-hidden-table" style="border-top: 2px solid #f2f2f2; margin: 0; padding: 0; display: block; width: 100%; height: 10px;"></table>
<?php } ?>
<span style="color:#444; line-height: 14px;">
    <?php echo get_setting("invoice_footer"); ?>
</span>