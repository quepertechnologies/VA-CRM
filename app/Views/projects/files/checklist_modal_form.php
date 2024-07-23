<?php echo form_open(get_uri("projects/save_doc_checklist"), array("id" => "add-document-checklist-id-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix p30">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="doc_check_list_id" class=" col-md-3"><?php echo app_lang('attach_document_check_list'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("doc_check_list_id", $doc_check_list_dropdown, array(isset($model_info->doc_check_list_id) ? $model_info->doc_check_list_id : ""), "class='form-control select2 validate-hidden' data-rule-required='true',data-msg-required='" . app_lang('field_required') . "' id='doc-check-list-id-dropdown'");
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
        window.addChecklistModalForm = $("#add-document-checklist-id-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                $("#check-list-table").appTable({
                    reload: true
                });
                window.addChecklistModalForm.closeModal();

                var checklistButton = $("#checklist_add_button");
                if (checklistButton) {
                    checklistButton.addClass('d-none');
                }
            }
        });

        $("#doc-check-list-id-dropdown").select2();
        $("#doc-check-list-id-dropdown").focus();
    });
</script>