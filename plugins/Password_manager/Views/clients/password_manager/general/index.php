<div class="card">
    <div class="table-responsive">
        <table id="password-manager-general-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>
<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        $("#password-manager-general-table").appTable({
            source: '<?php echo_uri("password_manager/general_list_data_of_client/" . $client_id . "/" . $view_type) ?>',
            columns: [
                {title: "<?php echo app_lang('id'); ?>"},
                {title: "<?php echo app_lang('name'); ?>"},
                {title: "<?php echo app_lang('category'); ?>"},
                {title: "<?php echo app_lang('url'); ?>", "class": "w10p"},
                {title: "<?php echo app_lang('username'); ?>"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>