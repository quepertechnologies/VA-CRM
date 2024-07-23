<?php echo form_open(get_uri("projects/save_milestone_deadline"), array("id" => "milestone-deadline-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="deadline" class="col-md-3"><?php echo app_lang('set_deadline'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(
                        "due_date",
                        isset($model_info->due_date) ? $model_info->due_date : '',
                        "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='deadline'",
                    );
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
        window.milestoneDeadlineForm = $("#milestone-deadline-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                location.reload();
                window.milestoneDeadlineForm.closeModal();
            }
        });

        setTimeout(function() {
            $("#deadline").focus();
        }, 200);

        setDatePicker("#deadline");
    });
</script>