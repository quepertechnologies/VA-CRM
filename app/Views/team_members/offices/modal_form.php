<?php echo form_open(get_uri("team_members/save_office"), array("id" => "note-form", "class" => "general-form", "role" => "form")); ?>
<div id="notes-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "location_id",
                        "name" => "location_id",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => app_lang('location'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        window.team_member_office_location = $("#note-form").appForm({
            onSuccess: function(result) {
                $("#office-table").appTable({
                    newData: result.data,
                    dataId: result.id
                });

                window.team_member_office_location.closeModal();
            }
        });

        $("#location_id").select2({
            data: <?php echo json_encode($location_dropdown); ?>
        })
    });
</script>