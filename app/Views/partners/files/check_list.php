<div class="card rounded-bottom">
    <div class="table-responsive">
        <table id="check-list-table" class="display" width="100%">
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("#check-list-table").appTable({
            source: '<?php echo_uri("partners/check_list_data/" . $client_id) ?>',
            order: [
                [0, "desc"]
            ],
            columns: [{
                    title: '<?php echo app_lang("label") ?>'
                },
                {
                    title: '<?php echo app_lang("no_of_uploaded_docs") ?>'
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