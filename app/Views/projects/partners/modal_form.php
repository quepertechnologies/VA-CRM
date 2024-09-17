<?php echo form_open(get_uri("projects/save_project_partner"), array("id" => "project-partner-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <div class="form-group" style="min-height: 50px">
            <div class="row">
                <label for="partner_id" class=" col-md-3"><?php echo app_lang('partner'); ?></label>
                <div class="col-md-9">
                    <div class="select-partner-field">
                        <div class="select-partner-form clearfix pb10">
                            <?php echo form_dropdown("partner_id[]", $partners_dropdown, array($model_info->partner_id), "class='partner_select2 col-md-12 p0' id='partner_id'"); ?>
                            <?php echo form_input('commission[]', $model_info->commission, 'class="commission_rate form-control col-md-4" placeholder="Commission (%)" id="commission_rate" min="0" max="100"', 'number') ?>
                            <small class="text-info" id='default-commission-msg'></small>
                            <?php echo js_anchor("<i data-feather='x' class='icon-16'></i> ", array("class" => "remove-partner delete ml20")); ?>
                        </div>
                    </div>
                    <?php echo js_anchor("<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_more'), array("class" => "add-partner", "id" => "add-more-user")); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div id="link-of-add-task-modal" class="hide">
        <?php echo modal_anchor(get_uri("tasks/modal_form"), "", array()); ?>
    </div>

    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>

    <?php if ($view_type == "from_project_modal") { ?>
        <button type="button" id="next-button" class="btn btn-info text-white"><span data-feather="arrow-right-circle" class="icon-16"></span> <?php echo app_lang('next'); ?></button>
        <button type="button" id="save-and-continue-button" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_continue'); ?></button>
    <?php } else { ?>
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    <?php } ?>

</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        window.projectPartnerForm = $("#project-partner-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                if (result.id !== "exists") {
                    // for (i = 0; i < result.data.length; i++) {
                    //     $("#project-partner-table").appTable({
                    //         newData: result.data[i],
                    //         dataId: result.id[i]
                    //     });
                    // }
                    location.reload();
                }

                if (window.showAddMultipleTasksModal) {
                    showAddMultipleTaskModal();
                } else {
                    window.projectPartnerForm.closeModal();
                }
            }
        });

        var $wrapper = $('.select-partner-field'),
            $field = $('.select-partner-form:first-child', $wrapper).clone(); //keep a clone for future use.

        const knownReferrals = <?php echo json_encode($known_referrals); ?>

        $(".add-partner", $(this)).click(function(e) {
            var $newField = $field.clone();

            //remove used options
            $('.partner_select2').each(function() {
                $newField.find("option[value='" + $(this).val() + "']").remove();
            });

            var $newObj = $newField.appendTo($wrapper);
            $newObj.find(".partner_select2").select2();
            if (knownReferrals.includes($(this).val())) {
                $newObj.find(".commission_rate").val('10').attr('disabled', 'true');
            }

            $newObj.find('.remove-partner').click(function() {
                $(this).parent('.select-partner-form').remove();
                showHideAddMore($field);
            });

            showHideAddMore($field);
        });

        $(document.body).on('change', '#partner_id', function() {
            known_id = $(this).attr("id");
            if (known_id == 'partner_id') {
                var partner_id = $(this).val();
                if (knownReferrals.includes(partner_id)) {
                    $(this).parent().find('#commission_rate').val('10').attr("readonly", 'true');
                    $(this).parent().find('#default-commission-msg').html("<small class='text-info' id='default-commission-msg'>For Referrals, only default commission rate 10% is allowed.</small>");
                } else {
                    $(this).parent().find('#commission_rate').val('').removeAttr("readonly");
                    $(this).parent().find('#default-commission-msg').html('');
                }
            }
        });

        showHideAddMore($field);

        $(".remove-partner").hide();
        $(".partner_select2").select2();

        function showHideAddMore($field) {
            //hide add more button if there are no options 
            if ($('.select-partner-form').length < $field.find("option").length) {
                $("#add-more-user").show();
            } else {
                $("#add-more-user").hide();
            }
        }

        //open add multiple task modal
        window.showAddMultipleTasksModal = false;

        $("#save-and-continue-button").click(function() {
            window.showAddMultipleTasksModal = true;
            $(this).trigger("submit");
        });

        $("#next-button").click(function() {
            showAddMultipleTaskModal();
        });

        function showAddMultipleTaskModal() {
            var $addMultipleTasksLink = $("#link-of-add-task-modal").find("a");
            $addMultipleTasksLink.attr("data-post-project_id", <?php echo $project_id; ?>);
            $addMultipleTasksLink.attr("data-title", "<?php echo app_lang('add_multiple_tasks') ?>");
            $addMultipleTasksLink.attr("data-post-add_type", "multiple");

            $addMultipleTasksLink.trigger("click");
        }

    });
</script>