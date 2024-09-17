<div class="card rounded-bottom">
    <small class="text-warn text-right" style="margin-right:20px; margin-top:15px;">
        <?php foreach ($doc_location_breadcrumbs as $key => $location) {
            if ($key) {
                echo " > " . $location;
            } else {
                echo $location;
            }
        } ?>
    </small>
    <div class="table-responsive">
        <table id="check-list-table" class="display" width="100%">
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("#check-list-table").appTable({
            source: '<?php echo_uri("projects/check_list_data/" . $project_id) ?>',
            order: [
                [0, "desc"]
            ],
            columns: [{
                    title: '<?php echo app_lang("label") ?>'
                },
                {
                    title: '<?php echo app_lang("status") ?>'
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