<?php echo form_open(get_uri("projects/save_milestone_activity"), array("id" => "milestone-activity-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="milestone_id" value="<?php echo $milestone_id; ?>" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="log_type" class="col-md-3"><?php echo "Activity Type"; ?></label>
                <div class="col-md-9">
                    <?php
                    $list = array(
                        'task' => 'Task',
                        'milestone' => "Stage",
                        'project_comment' => 'Comment',
                        'customer_feedback' => 'Customer Feedback',
                        'project_file' => 'Project File',
                        'note' => 'Note',
                        'application_date' => 'Application Date',
                        'application_end_date' => 'Application End Date',
                    );

                    $_list = array('' => '-');

                    foreach ($list as $key => $val) {
                        $_list[$key] = $val;
                    }

                    echo form_dropdown('log_type', $_list, '', 'class="form-control validate-hidden select2" data-rule-required="true" data-msg-required="This field is required" id="log_type"');
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="activity" class="col-md-3"><?php echo app_lang('activity'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "activity",
                        "name" => "activity",
                        "value" => '',
                        "class" => "form-control",
                        "placeholder" => "Activity",
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="created_date" class="col-md-3"><?php echo "Added Date"; ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "created_date",
                        "name" => "created_date",
                        "value" => '',
                        "class" => "form-control",
                        "placeholder" => app_lang('added_date'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="created_date" class="col-md-3"><?php echo "Added By"; ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "created_by",
                        "name" => "created_by",
                        "value" => '',
                        "class" => "form-control",
                        "placeholder" => app_lang('added_by'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">

    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        window.milestoneActivityForm = $("#milestone-activity-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                location.reload();
                window.milestoneActivityForm.closeModal();
            }
        });

        <?php if (is_dev_mode()) { ?>

            setDatePicker('#created_date');

            $('#log_type').select2();

            $("#created_by").select2({
                data: <?php echo $team_members_dropdown; ?>
            });

        <?php } ?>
    });
</script>