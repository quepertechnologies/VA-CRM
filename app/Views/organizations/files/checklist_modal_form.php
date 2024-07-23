<?php echo form_open(get_uri("organizations/save_checklist"), array("id" => "checklist-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />

        <div class="row">
            <label for="doc_check_list_id" class="<?php echo $label_column; ?>"><?php echo app_lang('select_checklist'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <div class="input-group-prepend">
                    <?php
                    echo form_dropdown(
                        'doc_check_list_id',
                        $checklist_dropdown,
                        '',
                        "class='form-control select2' id='doc-check-list-id'"
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

        $('#doc-check-list-id').select2();

        $("#checklist-form").appForm({
            onSuccess: function(result) {
                location.reload()
            }
        });

    });
</script>