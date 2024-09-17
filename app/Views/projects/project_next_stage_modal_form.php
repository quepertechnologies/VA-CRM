<?php echo form_open(get_uri("projects/next_milestone"), array("id" => "milestone-next-stage-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />


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
                        "placeholder" => "Write a note",
                        "style" => "height:150px;",
                        "data-rich-text-editor" => true,
                        "data-rule-required" => true,
                        'data-msg-required' => app_lang('field_required'),
                        'autofocus' => true
                    ));
                    ?>
                    <small class="text-info">Write a note to proceed to the next stage.</small>
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
        window.milestoneNextStageForm = $("#milestone-next-stage-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                window.milestoneNextStageForm.closeModal();
                location.reload();
            }
        });
    });
</script>