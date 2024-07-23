<?php if (isset($page_view) && $page_view) { ?>
    <div id="page-content" class="page-wrapper clearfix">
        <div class="card clearfix">
        <?php } ?>

        <div class="card rounded-bottom">
            <div class="tab-title clearfix">
                <h4><?php echo app_lang('emails'); ?></h4>
            </div>

            <div class="table-responsive">
                <table id="client-file-table" class="display" width="100%">
                </table>
            </div>
        </div>

        <?php if (isset($page_view) && $page_view) { ?>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#client-file-table").appTable({
            source: '<?php echo_uri("migration_clients/emails_data/" . $client_id) ?>',
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
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });

        $('#client-file-table > thead').css("display", 'none');
    });
</script>