<div class="clearfix default-bg">
    <div class="row">
        <div class="col-md-9 d-flex">
            <div class="card p15 w-100">
                <div id="page-content" class="clearfix grid-button">
                    <div style="max-width: 1000px; margin: auto;">
                        <div class="clearfix p20">
                            <!-- small font size is required to generate the pdf, overwrite that for screen -->
                            <style type="text/css">
                                .invoice-meta {
                                    font-size: 100% !important;
                                }
                            </style>

                            <?php
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
                            ?>
                        </div>

                        <div class="table-responsive mt15 pl15 pr15">
                            <table id="invoice-item-table" class="display" width="100%">
                            </table>
                            <?php echo $can_edit_invoices ? modal_anchor(get_uri("invoices/item_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang("add_item"), array('title' => app_lang("add_item"), 'class' => "btn btn-primary m-3", "data-post-invoice_id" => $invoice_info->id)) : "" ?>
                        </div>

                        <div class="clearfix">
                            <div class="float-end pr15" id="invoice-total-section">
                                <?php echo view("invoices/invoice_total_section", array("invoice_id" => $invoice_info->id, "can_edit_invoices" => $can_edit_invoices)); ?>
                            </div>
                        </div>

                        <?php
                        $files = @unserialize($invoice_info->files);
                        if ($files && is_array($files) && count($files)) {
                        ?>
                            <div class="clearfix">
                                <div class="col-md-12 mt20">
                                    <p class="b-t"></p>
                                    <div class="mb5 strong"><?php echo app_lang("files"); ?></div>
                                    <?php
                                    foreach ($files as $key => $value) {
                                        $file_name = get_array_value($value, "file_name");
                                        echo "<div>";
                                        echo js_anchor(remove_file_prefix($file_name), array("data-toggle" => "app-modal", "data-sidebar" => "0", "data-url" => get_uri("invoices/file_preview/" . $invoice_info->id . "/" . $key)));
                                        echo "</div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <p class="b-t b-info pt10 m15"><?php echo nl2br($invoice_info->note ? process_images_from_content($invoice_info->note) : ""); ?></p>

                        <div class="col-md-12 row">
                            <div class="col-md-3 bg-light border p-2 rounded">
                                <p>Discount</p>
                                <h2><?php echo to_currency($invoice_total_meta->discount_total, $invoice_info->currency_symbol); ?></h2>
                            </div>
                            <div class="col-md-3 bg-light border p-2 rounded" style="margin-left: 1rem;">
                                <p>Payable</p>
                                <h2><?php echo to_currency($invoice_total_meta->total_payable, $invoice_info->currency_symbol); ?></h2>
                            </div>
                            <div class="col-md-3 bg-light border p-2 rounded" style="margin-left: 1rem;">
                                <p>Income</p>
                                <h2><?php echo to_currency($invoice_total_meta->net_total_income, $invoice_info->currency_symbol); ?></h2>
                            </div>
                            <?php if ($invoice_info->invoice_type == 'net_claim') { ?>
                                <div class="col-md-3 bg-light border p-2 rounded" style="margin-top: 1rem;">
                                    <p>Due Amount</p>
                                    <h2><?php echo to_currency(($invoice_total_summary->invoice_subtotal - $invoice_total_meta->discount_total) - $invoice_total_summary->total_paid, $invoice_info->currency_symbol); ?></h2>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12 row">
                            <?php if (isset($income_sharing_partners) && count($income_sharing_partners)) { ?>
                                <div class="col-md-5 bg-light border p-2 rounded" style="margin-top: 1rem;">
                                    <?php $net_revenue_after_commission_deduction = 0; ?>
                                    <h4><b>Income Sharing</b></h4>
                                    <p>Your net income on the invoice is <?php echo '<strong>' . to_currency($invoice_total_meta->net_total_income, $invoice_info->currency_symbol) . '</strong>'; ?>, from which you are liable to pay the following subagent(s):</p>
                                    <ul>
                                        <?php foreach ($income_sharing_partners as $subagent) {
                                            $net_revenue_after_commission_deduction += $subagent->amount + $subagent->tax;
                                        ?>
                                            <li><?php echo timeline_label($subagent->partner_type) . ' <b>' . $subagent->partner_full_name . '</b> a <b>' . $subagent->commission . '%</b> commission rate' . ($subagent->tax ? ' <b>(plus ' . to_currency($subagent->tax) . ' tax)</b>' : '') . ' which is <b>' . to_currency($subagent->amount + $subagent->tax, $invoice_info->currency_symbol) . '</b>' ?></li>
                                        <?php } ?>
                                    </ul>
                                    <p>
                                        The amount left after the deduction of the commission is <b><?php echo to_currency($invoice_total_meta->net_total_income - $net_revenue_after_commission_deduction, $invoice_info->currency_symbol) ?></b>
                                    </p>
                                </div>
                            <?php } ?>
                            <?php if (isset($project_info) && isset($client_info)) { ?>
                                <div class="col-md-4 bg-light border p-2 rounded" style="margin-top: 1rem; margin-left:1rem">
                                    <p>Client Details</p>
                                    <h4><b><?php echo _get_client_full_name(0, $client_info) ?></b></h4>
                                    <p><i data-feather='hash' class='icon-16'></i> <b><?php echo $project_info->partner_client_id ? $project_info->partner_client_id : '-' ?></b></p>
                                    <p><i data-feather='book' class='icon-16'></i> <b><?php echo $project_info->title ? $project_info->title : '-' ?></b></p>
                                    <!-- <p><i data-feather='mail' class='icon-16'></i> <b><?php echo $client_info->email ?></b></p>
                                    <p><i data-feather='smartphone' class='icon-16'></i> <b><?php echo $client_info->phone ? $client_info->phone_code . '' . $client_info->phone : '-' ?></b></p> -->
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 align-items-stretch">
            <div class="card p15 w-100">
                <div class="clearfix">
                    <div class="row">
                        <?php echo view("invoices/invoice_status_bar"); ?>

                        <ul class="list-group list-group-flush">
                            <?php if ($invoice_info->recurring) { ?>
                                <?php
                                $recurring_stopped = false;
                                $recurring_cycle_class = "";
                                if ($invoice_info->no_of_cycles_completed > 0 && $invoice_info->no_of_cycles_completed == $invoice_info->no_of_cycles) {
                                    $recurring_cycle_class = "text-danger";
                                    $recurring_stopped = true;
                                }

                                $cycles = $invoice_info->no_of_cycles_completed . "/" . $invoice_info->no_of_cycles;
                                if (!$invoice_info->no_of_cycles) { //if not no of cycles, so it's infinity
                                    $cycles = $invoice_info->no_of_cycles_completed . "/&#8734;";
                                }
                                ?>

                                <li class="d-flex border-top justify-content-between">
                                    <p><?php echo app_lang("repeat_every"); ?></p>
                                    <strong><?php echo $invoice_info->repeat_every . " " . app_lang("interval_" . $invoice_info->repeat_type); ?></strong>
                                </li>

                                <li class="d-flex border-top justify-content-between">
                                    <p><?php echo app_lang("cycles"); ?></p>
                                    <strong><?php echo $cycles; ?></strong>
                                </li>

                                <?php if (!$recurring_stopped && (int) $invoice_info->next_recurring_date) { ?>
                                    <li class="d-flex border-top justify-content-between">
                                        <p><?php echo app_lang("next_recurring_date"); ?></p>
                                        <strong><?php echo format_to_date($invoice_info->next_recurring_date, false); ?></strong>
                                    </li>
                                <?php } ?>

                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card p15 w-100">
                <div class="clearfix">
                    <h6 class="mb15"><strong><?php echo app_lang("client"); ?> </strong></h6>
                    <ul class="list-group list-group-flush">
                        <li class="d-flex border-top justify-content-between pt-2">
                            <p><?php echo app_lang("name"); ?></p>
                            <strong><?php echo get_client_contact_profile_link($invoice_info->client_id, $client_full_name); ?></strong>
                        </li>
                        <li class="d-flex border-top justify-content-between pt-2">
                            <p><?php echo app_lang("date_of_birth"); ?></p>
                            <strong><?php echo get_array_value($client_info, 'date_of_birth') ? format_to_date(get_array_value($client_info, 'date_of_birth'), false) : "-"; ?></strong>
                        </li>
                        <li class="d-flex border-top justify-content-between pt-2">
                            <p><?php echo app_lang("branch"); ?></p>
                            <strong><?php echo $client_location_label ? $client_location_label : '-'; ?></strong>
                        </li>
                        <li class="d-flex border-top justify-content-between pt-2">
                            <p><?php echo app_lang("assignee"); ?></p>
                            <strong><?php echo isset($assignee_info) && $assignee_info ? get_team_member_profile_link($assignee_info->id, $assignee_info->first_name . " " . $assignee_info->last_name) : '-'; ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
            <?php if (isset($project_info)) { ?>
                <div class="card p15 w-100">
                    <div class="clearfix">
                        <h6 class="mb15"><strong><?php echo app_lang("project"); ?> </strong></h6>
                        <ul class="list-group list-group-flush">
                            <li class="d-flex border-top justify-content-between pt-2">
                                <p style="width: 50%;"><?php echo app_lang("title"); ?></p>
                                <strong style="width: 50%; text-align: right;"><?php echo anchor(get_uri('projects/view/' . $project_info->id), $project_info->title); ?></strong>
                            </li>
                            <li class="d-flex border-top justify-content-between pt-2">
                                <p><?php echo app_lang("workflow"); ?></p>
                                <strong><?php echo isset($project_workflow) ? $project_workflow->description : "-"; ?></strong>
                            </li>
                        </ul>
                        <h6 class="mb15"><strong><?php echo app_lang("partner") . "(s)"; ?></strong></h6>

                        <div class="table-responsive">
                            <table id="invoice-project-partner-table" class="b-b-only no-thead" width="100%">
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="card p15 w-100">
                <div class="clearfix">
                    <div class="row">
                        <?php if (can_access_reminders_module()) { ?>
                            <div class="col-md-12 mb15" id="invoice-reminders">
                                <h6 class="mb15"><strong><?php echo app_lang("reminders") . " (" . app_lang('private') . ")"; ?> </strong></h6>
                                <?php echo view("reminders/reminders_view_data", array("invoice_id" => $invoice_info->id, "hide_form" => true, "reminder_view_type" => "invoice")); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var optionVisibility = false;
        if ("<?php echo $can_edit_invoices ?>") {
            optionVisibility = true;
        }
        var delay;
        var taxableRows = [];

        $("#invoice-item-table").appTable({
            source: '<?php echo_uri("invoices/item_list_data/" . $invoice_info->id . "/") ?>',
            order: [
                [0, "asc"]
            ],
            hideTools: true,
            displayLength: 100,
            stateSave: false,
            columns: [{
                    visible: false,
                    searchable: false
                },
                {
                    title: '<?php echo app_lang("item") ?> ',
                    sortable: false
                },
                {
                    title: '<?php echo app_lang("income_type") ?>',
                    "class": "text-right w15p",
                    sortable: false
                },
                {
                    title: '<?php echo app_lang("fee") ?>',
                    "class": "text-right w15p",
                    sortable: false
                },
                {
                    title: '<?php echo app_lang("commission") ?>',
                    "class": "text-right w15p",
                    sortable: false,
                    visible: '<?php echo $invoice_info->invoice_type ?>' == 'general' ? false : true,
                },
                {
                    title: '<?php echo app_lang("tax") ?>',
                    "class": "text-right w15p",
                    sortable: false
                },
                {
                    title: '<?php echo app_lang("total") ?>',
                    "class": "text-right w15p",
                    sortable: false
                },
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center option w100",
                    sortable: false,
                    visible: optionVisibility
                }
            ],
            // rowCallback: function(nRow, aData) {
            //     var column = $("#invoice-item-table").DataTable().column(4);
            //     var taxableColumn = "<?php echo get_setting('taxable_column'); ?>";
            //     if (taxableColumn == "always_show") {
            //         column.visible(true);
            //     } else if (taxableColumn == "never_show") {
            //         column.visible(false);
            //     } else {
            //         taxableRows.push(aData[4]);
            //         clearTimeout(delay);
            //         delay = setTimeout(function() {
            //             var unique = getUniqueArray(taxableRows);

            //             if (unique.length === 2) {
            //                 column.visible(true);
            //             } else {
            //                 column.visible(false);
            //             }
            //             taxableRows = [];
            //         }, 100);
            //     }

            // },
            onInitComplete: function() {
                <?php if ($can_edit_invoices) { ?>
                    //apply sortable
                    $("#invoice-item-table").find("tbody").attr("id", "invoice-item-table-sortable");
                    var $selector = $("#invoice-item-table-sortable");

                    Sortable.create($selector[0], {
                        animation: 150,
                        chosenClass: "sortable-chosen",
                        ghostClass: "sortable-ghost",
                        onUpdate: function(e) {
                            appLoader.show();
                            //prepare sort indexes 
                            var data = "";
                            $.each($selector.find(".item-row"), function(index, ele) {
                                if (data) {
                                    data += ",";
                                }

                                data += $(ele).attr("data-id") + "-" + index;
                            });

                            //update sort indexes
                            $.ajax({
                                url: '<?php echo_uri("invoices/update_item_sort_values") ?>',
                                type: "POST",
                                data: {
                                    sort_values: data
                                },
                                success: function() {
                                    appLoader.hide();
                                }
                            });
                        }
                    });

                <?php } ?>

            },
            onDeleteSuccess: function(result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function(result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        $("#invoice-project-partner-table").appTable({
            source: '<?php echo_uri("projects/project_partner_list_data/" . $invoice_info->project_id) ?>',
            hideTools: true,
            displayLength: 500,
            columns: [{
                title: ''
            }]
        });
    });
</script>