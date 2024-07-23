<div class="card">
    <div class="table-responsive">
        <table id="leads-prospects-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        var ignoreSavedFilter = false;
        var hasString = window.location.hash.substring(1);
        if (hasString) {
            var ignoreSavedFilter = true;
        }

        $("#leads-prospects-table").appTable({
            source: '<?php echo_uri("leads/list_data/2") ?>',
            serverSide: true,
            smartFilterIdentity: "all_prospects_list", //a to z and _ only. should be unique to avoid conflicts
            ignoreSavedFilter: ignoreSavedFilter,
            columns: [
                {
                    title: "<?php echo app_lang("lead_contact") ?>",
                    "class": "all",
                    order_by: "first_name"
                },
                {
                    title: "<?php echo app_lang("contact_info") ?>",
                    "class": "all",
                },
                {
                    title: "<?php echo app_lang("owner") ?>",
                    order_by: "owner_name"
                },
                {
                    title: "<?php echo app_lang("location") ?>",
                },
                {
                    visible: false,
                    searchable: false,
                    order_by: "created_date"
                },
                {
                    title: "<?php echo app_lang("created_date") ?>",
                    "iDataSort": 4,
                    order_by: "created_date"
                },
                {
                    title: "<?php echo app_lang("label") ?>",
                },
                {
                    title: "<?php echo app_lang("status") ?>",
                    order_by: "status"
                }
                <?php echo $custom_field_headers; ?>,
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center option w100"
                }
            ],
            filterDropdown: [
                <?php if (get_array_value($login_user->permissions, "lead") !== "own") { ?> {
                        name: "created_by",
                        class: "w200",
                        options: <?php echo json_encode($owners_dropdown); ?>
                    },
                <?php } ?> {
                    name: "location_id",
                    class: "w200",
                    options: <?php echo json_encode($locations_filter_dropdown); ?>
                },
                {
                    name: "source",
                    class: "w200",
                    options: <?php echo view("leads/lead_sources"); ?>
                },

                <?php echo $custom_field_filters; ?>
            ],
            rangeDatepicker: [{
                startDate: {
                    name: "start_date",
                    value: ""
                },
                endDate: {
                    name: "end_date",
                    value: ""
                },
                showClearButton: true
            }],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 4, 5], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 4, 5], '<?php echo $custom_field_headers; ?>')
        });
    });
</script>