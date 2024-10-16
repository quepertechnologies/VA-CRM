<?php echo form_open(get_uri("settings/save_location"), array("id" => "location-form", "class" => "general-form", "role" => "form")); ?>

<input type="hidden" name="location_id" value="<?php echo $location_id; ?>" />

<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="title" class="col-md-3"><?php echo app_lang('title'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(
                        array(
                            "id" => "title",
                            "name" => "title",
                            "value" => isset($model_info->title) && $model_info->title ? $model_info->title : '',
                            "class" => "form-control",
                            "placeholder" => app_lang('title'),
                            "autofocus" => true,
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-upload" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary start-upload"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {

        window.locationForm = $("#location-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                if (result.success) {
                    appAlert.success(result.message, {
                        duration: 10000
                    });
                    if (result.newData) {}
                    $("#location-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                    window.locationForm.closeModal();
                }
            }
        });

        setTimeout(function() {
            $("#title").focus();
        }, 200);
    });
</script>