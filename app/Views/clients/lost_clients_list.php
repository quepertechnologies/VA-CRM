<div id="page-content" class="page-wrapper clearfix">
    <div class="clearfix grid-button">
        <div class="card">
            <h4 class="pl15 pt10 pr15"><?php echo app_lang("lost_clients"); ?></h4>
            <div class="table-responsive">
                <table id="client-table" class="display" cellspacing="0" width="100%">
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var showInvoiceInfo = true;
        if (!"<?php echo $show_invoice_info; ?>") {
            showInvoiceInfo = false;
        }

        var showOptions = true;
        if (!"<?php echo $can_edit_clients; ?>") {
            showOptions = false;
        }

        var ignoreSavedFilter = false;
        var quick_filters_dropdown = <?php echo view("students/quick_filters_dropdown"); ?>;
        if (window.selectedClientQuickFilter) {
            var filterIndex = quick_filters_dropdown.findIndex(x => x.id === window.selectedClientQuickFilter);
            if ([filterIndex] > -1) {
                //match found
                ignoreSavedFilter = true;
                quick_filters_dropdown[filterIndex].isSelected = true;
            }
        }

        var table = $("#client-table").appTable({
            source: '<?php echo_uri("clients/lost_clients_list_data") ?>',
            serverSide: true,
            smartFilterIdentity: "all_clients_list", //a to z and _ only. should be unique to avoid conflicts
            ignoreSavedFilter: ignoreSavedFilter,
            filterDropdown: [{
                    name: "quick_filter",
                    class: "w200",
                    options: quick_filters_dropdown
                },
                <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "client") === "all") { ?> {
                        name: "created_by",
                        class: "w200",
                        options: <?php echo $team_members_dropdown; ?>
                    },
                <?php } ?> {
                    name: "account_type",
                    class: "w200",
                    options: <?php echo $account_types_dropdown; ?>
                }, {
                    name: "group_id",
                    class: "w200",
                    options: <?php echo $groups_dropdown; ?>
                },
                {
                    name: "label_id",
                    class: "w200",
                    options: <?php echo $labels_dropdown; ?>
                },
                {
                    name: "visa_type",
                    class: "w200",
                    options: <?php echo $visa_types; ?>
                },
                <?php if (is_dev_mode()) { ?> {
                        name: "page_no",
                        class: "w200",
                        options: <?php echo $jump_to_page_dropdown; ?>
                    },
                <?php } ?>
                <?php echo $custom_field_filters; ?>
            ],
            singleDatepicker: [{
                name: "expiry",
                defaultText: "<?php echo app_lang('visa_expiry') ?>",
                options: [{
                        value: "expired",
                        text: "<?php echo app_lang('expired') ?>"
                    },
                    {
                        value: moment().format("YYYY-MM-DD"),
                        text: "<?php echo app_lang('today') ?>"
                    },
                    {
                        value: moment().add(1, 'days').format("YYYY-MM-DD"),
                        text: "<?php echo app_lang('tomorrow') ?>"
                    },
                    {
                        value: moment().add(30, 'days').format("YYYY-MM-DD"),
                        text: "<?php echo sprintf(app_lang('in_number_of_days'), 30); ?>"
                    },
                    {
                        value: moment().add(60, 'days').format("YYYY-MM-DD"),
                        text: "<?php echo sprintf(app_lang('in_number_of_days'), 60); ?>"
                    }
                ]
            }],
            columns: [{
                    title: "<?php echo app_lang("id") ?>",
                    "class": "text-center w50 all",
                    order_by: "id"
                },
                {
                    title: "<?php echo app_lang("name") ?>",
                    "class": "all",
                    order_by: "full_name"
                },
                {
                    title: "<?php echo app_lang("visa") ?>",
                },
                {
                    title: "<?php echo app_lang("created_at") ?>",
                    order_by: "created_at"
                },
                {
                    title: "<?php echo app_lang("phone") ?>",
                },
                {
                    title: "<?php echo app_lang("email") ?>",
                },
                {
                    title: "<?php echo app_lang("location") ?>",
                },
                {
                    title: "<?php echo app_lang("projects") ?>"
                }
                <?php echo $custom_field_headers; ?>,
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center dropdown-option w100",
                    visible: showOptions
                }
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7], '<?php echo $custom_field_headers; ?>')
        });

    });
</script>