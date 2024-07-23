<div class="table-responsive">
    <table id="checkin-all-table" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#checkin-all-table").appTable({
            source: '<?php echo_uri("check_in/all_list_data/"); ?>',
            order: [
                [0, "asc"]
            ],
            filterDropdown: [{
                name: "user_id",
                class: "w200",
                options: <?php echo $team_members_dropdown; ?>
            }],
            columns: [{
                    title: "Check In Date/Time",
                    "class": "w15p",
                    iDataSort: 1
                },
                {
                    title: "<?php echo app_lang("contact_name"); ?>",
                },
                {
                    title: "<?php echo app_lang("contact_info"); ?>",
                },
                {
                    title: "<?php echo app_lang("assignee"); ?>",
                },
                {
                    title: "Consultation Type",
                    "class": ""
                },
                {
                    title: "<?php echo app_lang("branch"); ?>",
                    "class": ""
                },
                {
                    title: "<?php echo app_lang("status"); ?>",
                    "class": ""
                },
                {
                    title: "<?php echo app_lang("note"); ?>",
                    "class": "option"
                },
                {
                    title: "<?php echo app_lang("convert_to_lead"); ?>",
                    "class": "option"
                },
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center option w100"
                }
            ],
            printColumns: [1, 2, 3, 4],
            xlsColumns: [1, 2, 3, 4],
            tableRefreshButton: true,
            columnShowHideOption: true
        });
    });
</script>