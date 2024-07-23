<div class="card rounded-bottom">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('offices'); ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("team_members/office_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_office'), array("class" => "btn btn-default", "title" => app_lang('add_office'), "data-post-user_id" => $user_id)); ?>
        </div>
    </div>
    <div class="table-responsive">
        <table id="office-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#office-table").appTable({
            source: '<?php echo_uri("team_members/office_list_data/" . $user_id) ?>',
            order: [
                [0, 'desc']
            ],
            columns: [{
                    targets: [1],
                    visible: false
                },
                {
                    title: '<?php echo app_lang("location"); ?>'
                },
                {
                    title: '<?php echo app_lang("created_date"); ?>',
                    "class": "w200"
                },
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center option w100"
                }
            ]
        });
    });
</script>