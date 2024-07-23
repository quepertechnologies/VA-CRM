<div class="card rounded-bottom">
    <div class="table-responsive">
        <table id="uploaded-file-table" class="display" width="100%">
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#uploaded-file-table").appTable({
            source: '<?php echo_uri("students/files_list_data/" . $client_id) ?>',
            order: [
                [0, "desc"]
            ],
            columns: [{
                    title: '<?php echo app_lang("id") ?>'
                },
                {
                    title: '<?php echo app_lang("file") ?>'
                },
                {
                    title: '<?php echo "Drive Link" ?>'
                },
                {
                    title: '<?php echo app_lang("size") ?>'
                },
                {
                    title: '<?php echo app_lang("uploaded_by") ?>'
                },
                {
                    title: '<?php echo app_lang("created_date") ?>'
                },
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center option w100"
                }
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>