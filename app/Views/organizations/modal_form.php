<?php echo form_open(get_uri("organizations/save"), array("id" => "client-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <!-- <div class="form-widget">
            <div class="widget-title clearfix">
                <div class="row">
                    <div id="organization-info-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('organization_info'); ?></strong></div>
                    <div id="address-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('address'); ?></strong></div>
                    <div id="financial-standing-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('financial_and_human_resource_overview'); ?></strong></div>
                    <div id="visa-requirements-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('visa_requirements'); ?></strong></div>
                    <div id="additional-information-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('additional_information'); ?></strong></div>
                    <div id="internal-label" class="col-sm-4"><i data-feather="circle" class="icon-16"></i><strong> <?php echo app_lang('internal'); ?></strong></div>
                </div>
            </div>

            <div class="progress ml15 mr15">
                <div id="form-progress-bar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                </div>
            </div>
        </div> -->
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>" />
        <?php echo view("organizations/client_form_fields"); ?>
    </div>
</div>

<div class="modal-footer">
    <div id="link-of-add-contact-modal" class="hide">
        <?php echo modal_anchor(get_uri("organizations/add_new_contact_modal_form"), "", array()); ?>
    </div>

    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <!-- <button id="form-previous" type="button" class="btn btn-default hide"><span data-feather="arrow-left-circle" class="icon-16"></span> <?php echo app_lang('previous'); ?></button>
    <button id="form-next" type="button" class="btn btn-info text-white"><span data-feather="arrow-right-circle" class="icon-16"></span> <?php echo app_lang('next'); ?></button> -->
    <button type="button" id="from-save-and-continue" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_continue'); ?></button>
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
        setTimeout(function() {
            $("#company_name").focus();
        }, 200);

        // $("#form-previous").click(function() {
        //     var $tab1 = $("#organization-info-tab"),
        //         $tab2 = $("#address-tab"),
        //         $tab3 = $("#financial-standing-tab"),
        //         $tab4 = $("#visa-requirements-tab"),
        //         $tab5 = $("#additional-information-tab"),
        //         $tab6 = $("#internal-tab"),
        //         $previousButton = $("#form-previous"),
        //         $nextButton = $("#form-next"),
        //         $submitButton1 = $("#form-submit"),
        //         $submitButton2 = $("#from-save-and-continue");

        //     if ($tab6.hasClass("active")) {
        //         $tab6.removeClass("active");
        //         $tab5.addClass("active");
        //         $nextButton.removeClass("hide");
        //         $submitButton1.addClass("hide");
        //         $submitButton2.addClass("hide");
        //         $("#form-progress-bar").width("100%");
        //     } else if ($tab5.hasClass("active")) {
        //         $tab5.removeClass("active");
        //         $tab4.addClass("active");
        //         $("#form-progress-bar").width("80%");
        //     } else if ($tab4.hasClass("active")) {
        //         $tab4.removeClass("active");
        //         $tab3.addClass("active");
        //         $("#form-progress-bar").width("64%");
        //     } else if ($tab3.hasClass("active")) {
        //         $tab3.removeClass("active");
        //         $tab2.addClass("active");
        //         $("#form-progress-bar").width("48%");
        //     } else if ($tab2.hasClass("active")) {
        //         $tab2.removeClass("active");
        //         $tab1.addClass("active");
        //         $previousButton.addClass("hide");
        //         $nextButton.removeClass("hide");
        //         $("#form-progress-bar").width("16%");
        //     }
        // });

        // $("#form-next").click(function() {
        //     var $tab1 = $("#organization-info-tab"),
        //         $tab2 = $("#address-tab"),
        //         $tab3 = $("#financial-standing-tab"),
        //         $tab4 = $("#visa-requirements-tab"),
        //         $tab5 = $("#additional-information-tab"),
        //         $tab6 = $("#internal-tab"),
        //         $previousButton = $("#form-previous"),
        //         $nextButton = $("#form-next"),
        //         $submitButton1 = $("#form-submit"),
        //         $submitButton2 = $("#from-save-and-continue");
        //     if (!$("#client-form").valid()) {
        //         return false;
        //     }
        //     if ($tab1.hasClass("active")) {
        //         $tab1.removeClass("active");
        //         $tab2.addClass("active");
        //         $previousButton.removeClass("hide");
        //         $("#form-progress-bar").width("16%");
        //         $("#organization-info-label").find("svg").remove();
        //         $("#organization-info-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
        //         feather.replace();
        //     } else if ($tab2.hasClass("active")) {
        //         $tab2.removeClass("active");
        //         $tab3.addClass("active");
        //         $("#form-progress-bar").width("32%");
        //         $("#address-label").find("svg").remove();
        //         $("#address-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
        //         feather.replace();
        //     } else if ($tab3.hasClass("active")) {
        //         $tab3.removeClass("active");
        //         $tab4.addClass("active");
        //         $("#form-progress-bar").width("48%");
        //         $("#financial-standing-label").find("svg").remove();
        //         $("#financial-standing-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
        //         feather.replace();
        //     } else if ($tab4.hasClass("active")) {
        //         $tab4.removeClass("active");
        //         $tab5.addClass("active");
        //         $("#form-progress-bar").width("80%");
        //         $("#visa-requirements-label").find("svg").remove();
        //         $("#visa-requirements-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
        //         feather.replace();
        //     } else if ($tab5.hasClass("active")) {
        //         $tab5.removeClass("active");
        //         $tab6.addClass("active");
        //         $nextButton.addClass("hide");
        //         $submitButton1.removeClass("hide");
        //         $submitButton2.removeClass("hide");
        //         $("#form-progress-bar").width("100%");
        //         $("#additional-information-label").find("svg").remove();
        //         $("#additional-information-label").prepend('<i data-feather="check-circle" class="icon-16"></i>');
        //         feather.replace();
        //     }
        // });


        //save and open add new contact member modal
        window.showAddNewModal = false;

        $("#from-save-and-continue").click(function() {
            window.showAddNewModal = true;
            $(this).trigger("submit");
        });

        $("#email").on('keyup', function() {
            if ($(':button[type="submit"]').is(":disabled")) {
                // $(':button[type="submit"]').prop("disabled", false);
                $("#email-alert-cont").addClass("d-none").html("");
            }
        });

        $("#email").on('blur', function() {
            $(':button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: "<?php echo get_uri("clients/validate_email"); ?>",
                data: {
                    email: $(this).val()
                },
                cache: false,
                type: 'POST',
                dataType: "json",
                success: function(response) {
                    if (response && response.success) {
                        $("#email-alert-cont").removeClass("d-none").html(response.message);
                    } else if (response) {
                        $(':button[type="submit"]').prop("disabled", false);
                        $("#email-alert-cont").addClass("d-none");
                    }
                }
            });
        });
    });
</script>