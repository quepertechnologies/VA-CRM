<?php echo form_open(get_uri("projects/save_project_deadline"), array("id" => "project-deadline-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $modal_info->id; ?>" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
        
        <div class="form-group">
                <div class="row">
                    <label for="project-start-date" class=" col-md-3"><?php echo app_lang('set_start_date'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(
                            "start_date",
                            isset($modal_info->start_date) ? $modal_info->start_date : '',
                            "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='project-start-date'"
                        );
                        ?>
                    </div>
                </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="project-deadline" class=" col-md-3"><?php echo app_lang('set_deadline'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(
                        "deadline",
                        isset($modal_info->deadline) ? $modal_info->deadline : '',
                        "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='project-deadline'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group" id='note-container'>
            <div class="row">
                <label for="note" class=" col-md-3"><?php echo app_lang('note'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "note",
                        "name" => "note",
                        "value" => "",
                        "class" => "form-control validate-hidden",
                        "placeholder" => "Write a note when you change the deadline",
                        "style" => "height:150px;",
                        "data-rich-text-editor" => true,
                        "data-rule-required" => true,
                        'data-msg-required' => app_lang('field_required')
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
        window.projectDeadlineForm = $("#project-deadline-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                location.reload();
                window.projectDeadlineForm.closeModal();
            }
        });

        setTimeout(function() {
            $("#project-deadline").focus();
        }, 200);

            setDatePicker("#project-deadline, #project-start-date");
       

    });
</script>