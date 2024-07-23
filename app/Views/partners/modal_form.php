<?php echo form_open(get_uri("partners/save"), array("id" => "client-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <!-- <div class="form-widget">
            <div class="widget-title clearfix">
                <div class="row">
                    <div id="personal-info-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('personal_info'); ?></strong></div>
                    <div id="contact-info-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('contact_info'); ?></strong></div>
                    <div id="address-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('address'); ?></strong></div>
                    <div id="current-visa-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('current_visa_info'); ?></strong></div>
                    <div id="secondary-applicant-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('secondary_applicant'); ?></strong></div>
                    <div id="applications-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('applications'); ?></strong></div>
                    <div id="internal-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('internal'); ?></strong></div>
                </div>
            </div>

            <div class="progress ml15 mr15">
                <div id="form-progress-bar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                </div>
            </div>
        </div> -->
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>" />
        <?php echo view("partners/client_form_fields"); ?>
    </div>
</div>

<div class="modal-footer">
    <div id="link-of-add-contact-modal" class="hide">
        <?php echo modal_anchor(get_uri("partners/add_new_contact_modal_form"), "", array()); ?>
    </div>

    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <!-- <button id="form-previous" type="button" class="btn btn-default hide"><span data-feather="arrow-left-circle" class="icon-16"></span> <?php echo app_lang('previous'); ?></button>
    <button id="form-next" type="button" class="btn btn-info text-white"><span data-feather="arrow-right-circle" class="icon-16"></span> <?php echo app_lang('next'); ?></button> -->
    <?php if (!$model_info->id) { ?>
        <button type="button" id="from-save-and-continue" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_continue'); ?></button>
    <?php } ?>
    <button type="submit" id="form-submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        var ticket_id = "<?php echo $ticket_id; ?>";

        window.clientForm = $("#client-form").appForm({
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

                    $("#client-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                } else {
                    $("#client-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                    window.clientForm.closeModal();
                }
            }
        });
        
        $("#assignee").select2({
            data: <?php echo $team_members_dropdown; ?>
        });

        setTimeout(function() {
            $("#company_name").focus();
        }, 200);

        if ($('#sec-applicant').is(':checked')) {
            $('#sec-applin-first-name').removeClass('hide');
            $('#sec-applin-last-name').removeClass('hide');
            $('#sec-applin-dob').removeClass('hide');
            $('#sec-applin-phone').removeClass('hide');
            $('#sec-applin-email').removeClass('hide');
        }

        $('#sec-applicant').click(function() {
            if ($(this).is(':checked')) {
                $('#sec-applin-first-name').removeClass('hide');
                $('#sec-applin-last-name').removeClass('hide');
                $('#sec-applin-dob').removeClass('hide');
                $('#sec-applin-phone').removeClass('hide');
                $('#sec-applin-email').removeClass('hide');
            } else {
                $('#sec-applin-first-name').addClass('hide');
                $('#sec-applin-last-name').addClass('hide');
                $('#sec-applin-dob').addClass('hide');
                $('#sec-applin-phone').addClass('hide');
                $('#sec-applin-email').addClass('hide');
            }

        });


        $("#form-previous").click(function() {
            var $tab1 = $("#personal-info-tab"),
                $tab2 = $("#contact-info-tab"),
                $tab3 = $("#address-tab"),
                $tab4 = $("#current-visa-tab"),
                // $tab5 = $("#corporate-client-tab"),
                // $tab5 = $("#primary-applicant-tab"),
                $tab5 = $("#secondary-applicant-tab"),
                $tab6 = $("#applications-tab"),
                $tab7 = $("#internal-tab"),
                $previousButton = $("#form-previous"),
                $nextButton = $("#form-next"),
                $submitButton1 = $("#form-submit");
            $submitButton2 = $("#from-save-and-continue");

            if ($tab7.hasClass("active")) {
                $tab7.removeClass("active");
                $tab6.addClass("active");
                $nextButton.removeClass("hide");
                $submitButton1.addClass("hide");
                $submitButton2.addClass("hide");
                $("#form-progress-bar").width("100%");
            } else if ($tab6.hasClass("active")) {
                $tab6.removeClass("active");
                $tab5.addClass("active");
                $("#form-progress-bar").width("80%");
            } else if ($tab5.hasClass("active")) {
                $tab5.removeClass("active");
                $tab4.addClass("active");
                $("#form-progress-bar").width("64%");
            } else if ($tab4.hasClass("active")) {
                $tab4.removeClass("active");
                $tab3.addClass("active");
                $("#form-progress-bar").width("48%");
            } else if ($tab3.hasClass("active")) {
                $tab3.removeClass("active");
                $tab2.addClass("active");
                $("#form-progress-bar").width("32%");
            } else if ($tab2.hasClass("active")) {
                $tab2.removeClass("active");
                $tab1.addClass("active");
                $previousButton.addClass("hide");
                $nextButton.removeClass("hide");
                $("#form-progress-bar").width("16%");
            }
        });

        $("#form-next").click(function() {
            var $tab1 = $("#personal-info-tab"),
                $tab2 = $("#contact-info-tab"),
                $tab3 = $("#address-tab"),
                $tab4 = $("#current-visa-tab"),
                // $tab5 = $("#corporate-client-tab"),
                // $tab5 = $("#primary-applicant-tab"),
                $tab5 = $("#secondary-applicant-tab"),
                $tab6 = $("#applications-tab"),
                $tab7 = $("#internal-tab"),
                $previousButton = $("#form-previous"),
                $nextButton = $("#form-next"),
                $submitButton1 = $("#form-submit");
            $submitButton2 = $("#from-save-and-continue");
            if (!$("#client-form").valid()) {
                return false;
            }
            if ($tab1.hasClass("active")) {
                $tab1.removeClass("active");
                $tab2.addClass("active");
                $previousButton.removeClass("hide");
                $("#form-progress-bar").width("16%");
                $("#personal-info-label").find("svg").remove();
                $("#personal-info-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
            } else if ($tab2.hasClass("active")) {
                $tab2.removeClass("active");
                $tab3.addClass("active");
                $("#form-progress-bar").width("32%");
                $("#contact-info-label").find("svg").remove();
                $("#contact-info-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
            } else if ($tab3.hasClass("active")) {
                $tab3.removeClass("active");
                $tab4.addClass("active");
                $("#form-progress-bar").width("48%");
                $("#address-label").find("svg").remove();
                $("#address-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
            } else if ($tab4.hasClass("active")) {
                $tab4.removeClass("active");
                $tab5.addClass("active");
                $("#form-progress-bar").width("64%");
                $("#current-visa-label").find("svg").remove();
                $("#current-visa-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
            } else if ($tab5.hasClass("active")) {
                $tab5.removeClass("active");
                $tab6.addClass("active");
                $("#form-progress-bar").width("80%");
                $("#secondary-applicant-label").find("svg").remove();
                $("#secondary-applicant-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
            } else if ($tab6.hasClass("active")) {
                $tab6.removeClass("active");
                $tab7.addClass("active");
                $nextButton.addClass("hide");
                $submitButton1.removeClass("hide");
                $submitButton2.removeClass("hide");
                $("#form-progress-bar").width("100%");
                $("#applications-label").find("svg").remove();
                $("#applications-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
                feather.replace();
            }
        });


        //save and open add new contact member modal
        window.showAddNewModal = false;

        $("#from-save-and-continue").click(function() {
            window.showAddNewModal = true;
            $(this).trigger("submit");
        });
    });
</script>