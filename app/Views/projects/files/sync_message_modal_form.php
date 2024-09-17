<?php echo form_open(get_uri("projects/sync_checklist_documents"), array("id" => "sync-document-checklist-msg", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix p30">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <p>Please allow up to 1-2 minutes for the syncing all of the document checklist.</p>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="refresh-cw" class="icon-16"></span> <?php echo app_lang('sync'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        window.addChecklistModalForm = $("#sync-document-checklist-msg").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                $("#check-list-table").appTable({
                    reload: true
                });
                window.addChecklistModalForm.closeModal();
            }
        });
    });
</script>