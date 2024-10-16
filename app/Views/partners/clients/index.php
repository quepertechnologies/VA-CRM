<div class="card">
    <div class="table-responsive">
        <table id="partner-clients-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<script type="text/javascript">
    loadClientsTable = function(selector) {
        var showOptions = true;
        if (!"<?php echo $can_edit_clients; ?>") {
            showOptions = false;
        }

        var ignoreSavedFilter = false;
        var quick_filters_dropdown = <?php echo view("partners/quick_filters_dropdown"); ?>;
        if (window.selectedClientQuickFilter) {
            var filterIndex = quick_filters_dropdown.findIndex(x => x.id === window.selectedClientQuickFilter);
            if ([filterIndex] > -1) {
                //match found
                ignoreSavedFilter = true;
                quick_filters_dropdown[filterIndex].isSelected = true;
            }
        }

        $(selector).appTable({
            source: '<?php echo_uri("partners/clients_list_data/" . $client_id) ?>',
            serverSide: false,
            smartFilterIdentity: "all_clients_list", //a to z and _ only. should be unique to avoid conflicts
            ignoreSavedFilter: ignoreSavedFilter,
            filterDropdown: [{
                name: "quick_filter",
                class: "w200",
                options: quick_filters_dropdown
            }],
            columns: [{
                    title: "<?php echo app_lang("id") ?>",
                    "class": "text-center w50 all",
                    order_by: "id"
                },
                {
                    title: "<?php echo app_lang("name") ?>",
                    "class": "all",
                    order_by: "first_name"
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
                },
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center option w100",
                    visible: showOptions
                }
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7]),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7])
        });
    };
    $(document).ready(function() {
        loadClientsTable("#partner-clients-table");
    });
</script>