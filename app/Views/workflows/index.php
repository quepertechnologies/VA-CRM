<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "workflows";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>
        <div class="col-sm-9 col-lg-10">
            <div class="card clearfix">
                <div class="card rounded-bottom">
                    <div class="tab-title clearfix">
                        <h4><?php echo app_lang('workflows'); ?></h4>
                        <div class="title-button-group">
                            <?php
                            if ($login_user->user_type == "staff") {
                                echo modal_anchor(get_uri("settings/workflow_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_workflow'), array("class" => "btn btn-default", "title" => app_lang('add_workflow'), "data-post-client_id" => 0));
                            }
                            ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="client-file-table" class="display" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#client-file-table").appTable({
            source: '<?php echo_uri("settings/workflows_data/") ?>',
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