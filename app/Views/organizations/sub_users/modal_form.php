<?php echo form_open(get_uri("organizations/save_sub_user"), array("id" => "sub-user-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <?php echo view("organizations/sub_users/client_form_fields"); ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <?php if (!$model_info->id) { ?>
        <button type="button" id="from-save-and-continue" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_continue'); ?></button>
    <?php } ?>
    <button type="submit" id="form-submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        var ticket_id = "<?php echo $ticket_id; ?>";

        window.clientForm = $("#sub-user-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                var $addMultipleContactsLink = $("#link-of-add-contact-modal").find("a");

                if (result.view === "details" || ticket_id) {
                    if (window.showAddNewModal) {
                        $addMultipleContactsLink.attr("data-post-client_id", result.id);
                        $addMultipleContactsLink.attr("data-title", "<?php echo app_lang('add_multiple_contacts') ?>");
                        $addMultipleContactsLink.attr("data-post-add_type", "multiple");

                        $addMultipleContactsLink.trigger("click");
                    } else {
                        appAlert.success(result.message, {
                            duration: 10000
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    }
                } else if (window.showAddNewModal) {
                    $addMultipleContactsLink.attr("data-post-client_id", result.id);
                    $addMultipleContactsLink.attr("data-title", "<?php echo app_lang('add_multiple_contacts') ?>");
                    $addMultipleContactsLink.attr("data-post-add_type", "multiple");

                    $addMultipleContactsLink.trigger("click");

                    $("#sub-users-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                } else {
                    $("#sub-users-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                    window.clientForm.closeModal();
                }
            }
        });
        setTimeout(function() {
            $("#first_name").focus();
        }, 200);

        //save and open add new contact member modal
        window.showAddNewModal = false;

        $("#from-save-and-continue").click(function() {
            window.showAddNewModal = true;
            $(this).trigger("submit");
        });
    });
</script>