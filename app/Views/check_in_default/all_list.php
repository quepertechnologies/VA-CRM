<div class="card">
    <div class="table-responsive">
        <table id="checkin-all-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<script type="text/javascript">
    loadCheckInTable = function(selector) {
        var showInvoiceInfo = true;
        if (!"<?php echo $show_invoice_info; ?>") {
            showInvoiceInfo = false;
        }

        var showOptions = true;

        var ignoreSavedFilter = false;
        var quick_filters_dropdown = <?php echo view("check_in/quick_filters_dropdown"); ?>;
        if (window.selectedClientQuickFilter) {
            var filterIndex = quick_filters_dropdown.findIndex(x => x.id === window.selectedClientQuickFilter);
            if ([filterIndex] > -1) {
                //match found
                ignoreSavedFilter = true;
                quick_filters_dropdown[filterIndex].isSelected = true;
            }
        }

        $(selector).appTable({
            source: '<?php echo_uri("check_in/list_data") ?>',
            serverSide: true,
            smartFilterIdentity: "checkin_all_list", //a to z and _ only. should be unique to avoid conflicts
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
                    name: "group_id",
                    class: "w200",
                    options: <?php echo $groups_dropdown; ?>
                },
                {
                    name: "label_id",
                    class: "w200",
                    options: <?php echo $labels_dropdown; ?>
                },
                <?php echo $custom_field_filters; ?>
            ],
            columns: [{
                    title: "<?php echo app_lang("id") ?>",
                    "class": "text-center w50 all",
                    order_by: "id"
                },
                {
                    title: "<?php echo app_lang("date") ?>",
                    "class": "all",
                    order_by: "company_name"
                },
                {
                    title: "<?php echo app_lang("start") ?>",
                    order_by: "primary_contact"
                },
                {
                    title: "<?php echo app_lang("end") ?>",
                    order_by: "primary_contact"
                },
                {
                    title: "<?php echo app_lang("contact_name") ?>",
                    order_by: "client_groups"
                },
                {
                    title: "<?php echo app_lang("contact_type") ?>",
                    order_by: "client_groups"
                },
                {
                    title: "<?php echo app_lang("visit_purpose") ?>"
                },
                {
                    title: "<?php echo app_lang("assignee") ?>"
                },
                {
                    title: "<?php echo app_lang("status") ?>"
                },
                <?php echo $custom_field_headers; ?>
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7], '<?php echo $custom_field_headers; ?>')
        });
    };
    $(document).ready(function() {
        loadCheckInTable("#checkin-all-table");
    });
</script>