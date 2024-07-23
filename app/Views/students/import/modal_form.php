<?php echo form_open(get_uri("import/run"), array("id" => "import-trigger-modal-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label for="import-trigger" class=" col-md-3"><?php echo app_lang('import_trigger'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown(
                        'trigger',
                        $trigger_dropdown,
                        [],
                        "class='form-control validate-hidden' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='import-trigger'"
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="offset" class=" col-md-3"><?php echo app_lang('offset'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(
                        "offset",
                        '0',
                        "class='form-control' min='0' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='offset'",
                        'number'
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
        window.importTriggerForm = $("#import-trigger-modal-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {
                    duration: 10000
                });
                window.importTriggerForm.closeModal();
            }
        });

        $('#import-trigger').select2();

    });
</script>