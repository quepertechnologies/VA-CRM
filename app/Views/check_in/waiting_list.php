<div class="table-responsive">
    <table id="checkin-waiting-table" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#checkin-waiting-table").appTable({
            source: '<?php echo_uri("check_in/waiting_list_data/"); ?>',
            order: [
                [2, "desc"]
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
            printColumns: [0, 2, 3],
            xlsColumns: [0, 2, 3],
            tableRefreshButton: true,
            columnShowHideOption: true

        });
    });
</script>