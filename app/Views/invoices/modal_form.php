<?php echo form_open(get_uri("invoices/save"), array("id" => "invoice-form", "class" => "general-form", "role" => "form")); ?>
<div id="invoices-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">

            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
            <?php if ($is_clone || $estimate_id || $order_id || $contract_id || $proposal_id) { ?>
                <?php if ($is_clone) { ?>
                    <input type="hidden" name="is_clone" value="1" />
                <?php } ?>
                <input type="hidden" name="discount_amount" value="<?php echo $model_info->discount_amount; ?>" />
                <input type="hidden" name="discount_amount_type" value="<?php echo $model_info->discount_amount_type; ?>" />
                <input type="hidden" name="discount_type" value="<?php echo $model_info->discount_type; ?>" />
            <?php } ?>

            <div class="form-group">
                <div class="row">
                    <label for="invoice_bill_date" class=" col-md-3"><?php echo app_lang('bill_date'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "invoice_bill_date",
                            "name" => "invoice_bill_date",
                            "value" => $model_info->bill_date ? $model_info->bill_date : get_my_local_time("Y-m-d"),
                            "class" => "form-control recurring_element",
                            "placeholder" => app_lang('bill_date'),
                            "autocomplete" => "off",
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="invoice_due_date" class=" col-md-3"><?php echo app_lang('due_date'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "invoice_due_date",
                            "name" => "invoice_due_date",
                            "value" => $model_info->due_date,
                            "class" => "form-control",
                            "placeholder" => app_lang('due_date'),
                            "autocomplete" => "off",
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                            "data-rule-greaterThanOrEqual" => "#invoice_bill_date",
                            "data-msg-greaterThanOrEqual" => app_lang("end_date_must_be_equal_or_greater_than_start_date")
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <?php if (count($companies_dropdown) > 1) { ?>
                <div class="form-group">
                    <div class="row">
                        <label for="company_id" class=" col-md-3"><?php echo app_lang('company'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "company_id",
                                "name" => "company_id",
                                "value" => $model_info->company_id,
                                "class" => "form-control",
                                "placeholder" => app_lang('company')
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($client_id && !$project_id) { ?>
                <input type="hidden" name="invoice_client_id" value="<?php echo $client_id; ?>" />
            <?php } else { ?>
                <div class="form-group">
                    <div class="row">
                        <label for="invoice_client_id" class=" col-md-3"><?php echo app_lang('client'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown("invoice_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='invoice_client_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <input type="hidden" name="invoice_project_id" value="<?php echo $project_id ? $project_id : 0; ?>" />
            <!-- <input type="hidden" name="invoice_project_id" value="<?php echo $project_id; ?>" />
            <div class="form-group">
                <div class="row">
                    <label for="invoice_project_id" class=" col-md-3"><?php echo app_lang('project'); ?></label>
                    <div class="col-md-9" id="invoice-porject-dropdown-section">
                        <?php
                        echo form_input(array(
                            "id" => "invoice_project_id",
                            "name" => "invoice_project_id",
                            "value" => $model_info->project_id,
                            "class" => "form-control",
                            "placeholder" => app_lang('project')
                        ));
                        ?>
                    </div>
                </div>
            </div> -->

            <input type="hidden" name="tax_id" value="<?php echo $default_tax->id; ?>" />
            <!-- <div class="form-group">
                <div class="row">
                    <label for="tax_id" class=" col-md-3"><?php echo app_lang('tax'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("tax_id", $taxes_dropdown, array($model_info->tax_id), "class='select2 tax-select2'");
                        ?>
                    </div>
                </div>
            </div> -->
            <div class="form-group d-none">
                <div class="row">
                    <label for="tax_id" class=" col-md-3"><?php echo app_lang('second_tax'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2 tax-select2'");
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group d-none">
                <div class="row">
                    <label for="tax_id" class=" col-md-3"><?php echo app_lang('tax_deducted_at_source'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("tax_id3", $taxes_dropdown, array($model_info->tax_id3), "class='select2 tax-select2'");
                        ?>
                    </div>
                </div>
            </div>

            <?php // echo view("invoices/recurring_fields"); 
            ?>

            <div class="form-group">
                <div class="row">
                    <label for="invoice_note" class=" col-md-3"><?php echo app_lang('note'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_textarea(array(
                            "id" => "invoice_note",
                            "name" => "invoice_note",
                            "value" => $model_info->note ? process_images_from_content($model_info->note, false) : "",
                            "class" => "form-control",
                            "placeholder" => app_lang('note'),
                            "data-rich-text-editor" => true
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="invoice_labels" class=" col-md-3"><?php echo app_lang('labels'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "invoice_labels",
                            "name" => "labels",
                            "value" => $model_info->labels,
                            "class" => "form-control",
                            "placeholder" => app_lang('labels')
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="tax_id" class=" col-md-3"><?php echo app_lang('invoice_type'); ?></label>
                    <div class="col-md-9">
                        <?php
                        $list = array(
                            '' => '-',
                            'general' => 'General Invoice',
                            'gross_claim' => 'Gross Claim',
                            'net_claim' => 'Net Claim'
                        );
                        ksort($list);

                        echo form_dropdown(
                            "invoice_type",
                            $list,
                            !$schedule_id ? array('general') : array($model_info->invoice_type),
                            "class='form-control validate-hidden " . (isset($schedule_info) ? 'invoice-type-select2' : '') . "' " . (isset($schedule_info) ? '' : 'disabled') . " data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'"
                        );
                        ?>
                        <small><?php echo isset($schedule_info) ? '' : 'You can change invoice type on the payment schedule only' ?></small>
                    </div>
                </div>
            </div>

            <?php if (isset($schedule_info)) { ?>
                <div class="form-group" id="partner-dropdown-container">
                    <div class="row">
                        <label for="tax_id" class=" col-md-3"><?php echo app_lang('select_partner_to_bill'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown(
                                "invoice_partner_id",
                                $partners_dropdown,
                                array($model_info->partner_id),
                                "class='form-control validate-hidden partner-select2' id='partners-dropdown' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'"
                            );
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?>

            <?php if ($schedule_id) { ?>
                <input type="hidden" name="schedule_id" value="<?php echo $schedule_id; ?>" />
            <?php } ?>

            <?php if ($estimate_id) { ?>
                <input type="hidden" name="estimate_id" value="<?php echo $estimate_id; ?>" />
                <div class="form-group">
                    <div class="row">
                        <label for="estimate_id_checkbox" class=" col-md-12">
                            <input type="hidden" name="copy_items_from_estimate" value="<?php echo $estimate_id; ?>" />
                            <?php
                            echo form_checkbox("estimate_id_checkbox", $estimate_id, true, " class='float-start form-check-input' disabled='disabled'");
                            ?>
                            <span class="float-start ml15"> <?php echo app_lang('include_all_items_of_this_estimate'); ?> </span>
                        </label>
                    </div>
                </div>
            <?php } ?>
            <?php if ($order_id) { ?>
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
                <div class="form-group">
                    <div class="row">
                        <label for="order_id_checkbox" class=" col-md-12">
                            <input type="hidden" name="copy_items_from_order" value="<?php echo $order_id; ?>" />
                            <?php
                            echo form_checkbox("order_id_checkbox", $order_id, true, " class='float-start form-check-input' disabled='disabled'");
                            ?>
                            <span class="float-start ml15"> <?php echo app_lang('include_all_items_of_this_order'); ?> </span>
                        </label>
                    </div>
                </div>
            <?php } ?>

            <?php if ($contract_id) { ?>
                <input type="hidden" name="contract_id" value="<?php echo $contract_id; ?>" />
                <div class="form-group">
                    <div class="row">
                        <label for="contract_id_checkbox" class=" col-md-12">
                            <input type="hidden" name="copy_items_from_contract" value="<?php echo $contract_id; ?>" />
                            <?php
                            echo form_checkbox("contract_id_checkbox", $contract_id, true, " class='float-start form-check-input' disabled='disabled'");
                            ?>
                            <span class="float-start ml15"> <?php echo app_lang('include_all_items_of_this_contract'); ?> </span>
                        </label>
                    </div>
                </div>
            <?php } ?>

            <?php if ($proposal_id) { ?>
                <input type="hidden" name="proposal_id" value="<?php echo $proposal_id; ?>" />
                <div class="form-group">
                    <div class="row">
                        <label for="proposal_id_checkbox" class=" col-md-12">
                            <input type="hidden" name="copy_items_from_proposal" value="<?php echo $proposal_id; ?>" />
                            <?php
                            echo form_checkbox("proposal_id_checkbox", $proposal_id, true, " class='float-start form-check-input' disabled='disabled'");
                            ?>
                            <span class="float-start ml15"> <?php echo app_lang('include_all_items_of_this_proposal'); ?> </span>
                        </label>
                    </div>
                </div>
            <?php } ?>

            <?php if ($is_clone) { ?>
                <div class="form-group">
                    <div class="row">
                        <label for="copy_items" class=" col-md-12">
                            <?php
                            echo form_checkbox("copy_items", "1", true, "id='copy_items' disabled='disabled' class='form-check-input float-start mr15'");
                            ?>
                            <?php echo app_lang('copy_items'); ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="copy_discount" class=" col-md-12">
                            <?php
                            echo form_checkbox("copy_discount", "1", true, "id='copy_discount' disabled='disabled' class='form-check-input float-start mr15'");
                            ?>
                            <?php echo app_lang('copy_discount'); ?>
                        </label>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo view("includes/file_list", array("files" => $model_info->files));
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 border rounded bg-gray hide p15 mb-3" id='invoice-summary'></div>

            <?php echo view("includes/dropzone_preview"); ?>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-default upload-file-button float-start btn-sm round me-auto" type="button" style="color:#7988a2"><i data-feather="camera" class="icon-16"></i> <?php echo app_lang("upload_file"); ?></button>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('confirm_and_save'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        if ("<?php echo $estimate_id; ?>" || "<?php echo $proposal_id; ?>" || "<?php echo $order_id; ?>" || "<?php echo $contract_id; ?>") {
            RELOAD_VIEW_AFTER_UPDATE = false; //go to related page
        }

        $("#partner-dropdown-container").hide();

        var uploadUrl = "<?php echo get_uri("invoices/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("invoices/validate_invoices_file"); ?>";

        var dropzone = attachDropzoneWithForm("#invoices-dropzone", uploadUrl, validationUri);

        $("#invoice-form").appForm({
            onSuccess: function(result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('invoices/view'); ?>/" + result.id;
                }
            },
            onAjaxSuccess: function(result) {
                if (!result.success && result.next_recurring_date_error) {
                    $("#next_recurring_date").val(result.next_recurring_date_value);
                    $("#next_recurring_date_container").removeClass("hide");

                    $("#invoice-form").data("validator").showErrors({
                        "next_recurring_date": result.next_recurring_date_error
                    });
                }
            }
        });

        $("#invoice-form .tax-select2").select2();
        $("#invoice-form .partner-select2").select2();
        $("#invoice-form .invoice-type-select2").select2().change(function(e) {

            const summary = <?php echo isset($schedule_info) ? json_encode($schedule_info) : 'false'; ?>;
            const default_tax = <?php echo isset($default_tax) ? json_encode($default_tax) : 'false'; ?>;
            const subagent = <?php echo isset($subagent) ? json_encode($subagent) : 'false'; ?>;

            if (summary !== 'false' && subagent !== 'false' && default_tax !== 'false') {
                $('#invoice-summary').removeClass("hide");
                if (e.val == 'gross_claim') {

                    if ($("#partner-dropdown-container").length) {
                        $("#partner-dropdown-container").show();
                        $('#partners-dropdown')
                            .addClass("validate-hidden")
                            .attr("data-rule-required", 'true')
                            .attr('data-msg-required', '<?php echo app_lang("field_required"); ?>');
                    }

                    claimable = [...summary.fees].filter((fee) => +fee.is_claimable);

                    let subtotal = claimable.reduce((total, fee) => total + (+fee.amount * (Number(subagent.commission) / 100)), 0),
                        total_tax = claimable.reduce((total, fee) => +fee.is_taxable ? total + (+fee.amount * (Number(subagent.commission) / 100) * (default_tax.percentage / 100)) : total, 0),
                        total_taxable = claimable.reduce((total, fee) => +fee.is_taxable ? total + fee.amount : total, 0);

                    let _summary = `
                        <p><b>Invoice Summary</b></p>
                        <h2><b>Gross Claim</b></h2>
                        <p>Your total item(s) on this schedule are as follows:</p>
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fee</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">Is Taxable</th>
                                        <th class="text-right">Commission (%)</th>
                                        <th class="text-right">Claimable Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${claimable.map((fee)=> {
                                        let title = fee.fee_type,
                                        amount = fee.amount,
                                        tax = (default_tax && + Number(fee.is_taxable) ? (fee.amount * (default_tax.percentage / 100)) : 0),
                                        commission = (fee.amount * (Number(subagent.commission) / 100)),
                                        tax_title = (default_tax &&  Number(fee.is_taxable) ? "<small>" + default_tax.title + "</small><br>$" + (fee.amount * (default_tax.percentage / 100)) : "(No Tax)"),
                                        total = (default_tax &&  Number(fee.is_taxable) ? (fee.amount * (default_tax.percentage / 100)) : fee.amount);
                                        return `
                                        <tr>
                                            <td>${title}</td>
                                            <td class="text-right">$${Number(amount).toFixed(2)}</td>
                                            <td class="text-right">${+fee.is_taxable == 1 ? "Yes" : "No"}</td>
                                            <td class="text-right">${subagent.commission}%</td>
                                            <td class="text-right">$${Number(commission).toFixed(2)}</td>
                                        </tr>`;
                                    })
                                    }
                                </tbody>
                            </table>
                            <b>
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-2">Subtotal</div>
                                    <div class="col-md-2 text-right">$${Number(subtotal).toFixed(2)}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-2">${default_tax.title}</div>
                                    <div class="col-md-2 text-right">$${Number(total_tax).toFixed(2)}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-2">Total claimable</div>
                                    <div class="col-md-2 text-right">$${Number(subtotal + total_tax).toFixed(2)}</div>
                                </div>
                            <b>
                            <div class="col-md-12 mt20 row">
                                <div class="col-md-3 bg-light border p-2 rounded ml-1">
                                    <p>Discount</p>
                                    <h2>$${Number(summary.discount).toFixed(2)}</h2>
                                </div>
                                <div class="col-md-3 bg-light border p-2 rounded" style="margin-left: 15px">
                                    <p>Income</p>
                                    <h2>$${Number(subtotal - Number(summary.discount)).toFixed(2)}</h2>
                                </div>
                            </div>
                        </div>
                    `;
                    // $('#invoice-summary').html(_summary);
                    $('#invoice-summary').html(`
                        <p><b>Invoice Summary</b></p>
                        <h2><b>Gross Claim</b></h2>
                        <p class="text-danger">Preview is not available at the moment.</p>`);

                } else if (e.val == 'net_claim') {

                    if ($("#partner-dropdown-container").length) {
                        $("#partner-dropdown-container").show();
                        $('#partners-dropdown')
                            .addClass("validate-hidden")
                            .attr("data-rule-required", 'true')
                            .attr('data-msg-required', '<?php echo app_lang("field_required"); ?>');
                    }

                    claimable = [...summary.fees].filter((fee) => +fee.is_claimable);

                    let subtotal_payable = claimable.reduce((total, fee) => total + (fee.amount - (+fee.amount * (Number(subagent.commission) / 100))), 0),
                        subtotal_claimable = claimable.reduce((total, fee) => total + (+fee.amount * (Number(subagent.commission) / 100)), 0),
                        net_total = claimable.reduce((total, fee) => Number(fee.is_taxable) == 1 ? total + (fee.amount + (+fee.amount * (default_tax.percentage / 100))) : total + fee.amount, 0),
                        total_tax = claimable.reduce((total, fee) => Number(fee.is_taxable) == 1 ? total + ((fee.amount - (+fee.amount * (Number(subagent.commission) / 100))) * (default_tax.percentage / 100)) : total, 0);

                    let _summary = `
                        <p><b>Invoice Summary</b></p>
                        <h2><b>Net Claim</b></h2>
                        <p>Your total item(s) on this schedule are as follows:</p>
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fee</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">Is Taxable</th>
                                        <th class="text-right">Commission (%)</th>
                                        <th class="text-right">Payable Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${claimable.map((fee)=> {
                                        let title = fee.fee_type,
                                        amount = fee.amount,
                                        tax = (default_tax && + fee.is_taxable ? (fee.amount * (default_tax.percentage / 100)) : 0),
                                        commission = (fee.amount * (Number(subagent.commission) / 100)),
                                        tax_title = (default_tax && + fee.is_taxable ? "<small>" + default_tax.title + "</small><br>$" + (fee.amount * (default_tax.percentage / 100)) : "(No Tax)"),
                                        total = (default_tax && + fee.is_taxable ? (fee.amount * (default_tax.percentage / 100)) : fee.amount);
                                        return `
                                        <tr>
                                            <td>${title}</td>
                                            <td class="text-right">$${Number(amount).toFixed(2)}</td>
                                            <td class="text-right">${+fee.is_taxable == 1 ? "Yes" : "No"}</td>
                                            <td class="text-right">${subagent.commission}%</td>
                                            <td class="text-right">$${Number(amount - commission).toFixed(2)}</td>
                                        </tr>`;
                                    })
                                    }
                                </tbody>
                            </table>
                            <b>
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-4">Subtotal</div>
                                    <div class="col-md-2 text-right">$${Number(subtotal_payable).toFixed(2)}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-4">${default_tax.title}</div>
                                    <div class="col-md-2 text-right">$${Number(total_tax).toFixed(2)}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-4">Total payable to partner</div>
                                    <div class="col-md-2 text-right">$${Number(subtotal_payable + total_tax).toFixed(2)}</div>
                                </div>
                            <b>
                            <div class="col-md-12 mt20 row">
                                <div class="col-md-3 bg-light border p-2 rounded ml-1">
                                    <p>Discount</p>
                                    <h2>$${Number(summary.discount).toFixed(2)}</h2>
                                </div>
                                <div class="col-md-3 bg-light border p-2 rounded ml-1 mr-1" style="margin-left: 1rem; margin-right: 1rem;">
                                    <p>Net fee received</p>
                                    <h2>$${Number(net_total).toFixed(2)}</h2>
                                </div>
                                <div class="col-md-3 bg-light border p-2 rounded">
                                    <p>Income</p>
                                    <h2>$${Number(subtotal_claimable - Number(summary.discount)).toFixed(2)}</h2>
                                </div>
                            </div>
                        </div>
                    `;
                    // $('#invoice-summary').html(_summary);
                    $('#invoice-summary').html(`
                        <p><b>Invoice Summary</b></p>
                        <h2><b>Net Claim</b></h2>
                        <p class="text-danger">Preview is not available at the moment.</p>`);
                } else if (e.val == 'general') {

                    if ($("#partner-dropdown-container").length) {
                        $("#partner-dropdown-container").hide();
                        $('#partners-dropdown')
                            .removeClass("validate-hidden")
                            .removeAttr("data-rule-required")
                            .removeAttr('data-msg-required');
                    }

                    $('#invoice-summary').addClass("hide");
                    $('#invoice-summary').html("");
                }
            } else {
                $('#invoice-summary').addClass("hide");
            }
        });
        $("#invoice_labels").select2({
            multiple: true,
            data: <?php echo json_encode($label_suggestions); ?>
        });
        $("#company_id").select2({
            data: <?php echo json_encode($companies_dropdown); ?>
        });

        setDatePicker("#invoice_bill_date, #invoice_due_date");

        //load all projects of selected client
        $("#invoice_client_id").select2().on("change", function() {
            var client_id = $(this).val();
            if ($(this).val()) {
                $('#invoice_project_id').select2("destroy");
                $("#invoice_project_id").hide();
                appLoader.show({
                    container: "#invoice-porject-dropdown-section"
                });
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_project_suggestion") ?>" + "/" + client_id,
                    dataType: "json",
                    success: function(result) {
                        $("#invoice_project_id").show().val("");
                        $('#invoice_project_id').select2({
                            data: result
                        });
                        appLoader.hide();
                    }
                });
            }
        });

        $('#invoice_project_id').select2({
            data: <?php echo json_encode($projects_suggestion); ?>
        });

        if ("<?php echo $project_id; ?>") {
            $("#invoice_client_id").select2("readonly", true);
        }

        //show/hide recurring fields
        $("#invoice_recurring").click(function() {
            if ($(this).is(":checked")) {
                $("#recurring_fields").removeClass("hide");
            } else {
                $("#recurring_fields").addClass("hide");
            }
        });

        setDatePicker("#next_recurring_date", {
            startDate: moment().add(1, 'days').format("YYYY-MM-DD") //set min date = tomorrow
        });

        var defaultDue = "<?php echo get_setting('default_due_date_after_billing_date'); ?>";
        var id = "<?php echo $model_info->id; ?>";

        //disable this operation in edit mode
        if (defaultDue && !id) {
            //for auto fill the due date based on bill date
            setDefaultDueDate = function() {
                var dateFormat = getJsDateFormat().toUpperCase();

                var billDate = $('#invoice_bill_date').val();
                var dueDate = moment(billDate, dateFormat).add(defaultDue, 'days').format(dateFormat);
                $("#invoice_due_date").val(dueDate);

            };

            $("#invoice_bill_date").change(function() {
                setDefaultDueDate();
            });

            setDefaultDueDate();
        }

    });
</script>