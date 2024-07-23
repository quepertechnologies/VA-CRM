<?php echo form_open(get_uri("contracts/send_contract"), array("id" => "send-contract-form", "class" => "general-form", "role" => "form")); ?>
<div id="project-contract-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">

            <!-- <div class="form-group">
                <div class="row">
                    <label for="id" class=" col-md-3"><?php echo app_lang('select_contract'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("id", $contracts_dropdown, array(), "class='select2 validate-hidden' id='id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div> -->

            <div class="form-group">
                <div class="row">
                    <label for="contact_id" class=" col-md-3"><?php echo app_lang('to'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("contact_id", $contacts_dropdown, array(), "class='select2 validate-hidden' id='contact_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="contract_date" class=" col-md-3">Contract Date</label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "contract_date",
                            "name" => "contract_date",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "Contract Date",
                            'data-rule-required' => "true",
                            'data-msg-required' => app_lang('field_required')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="valid_until" class=" col-md-3">Valid Until</label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "valid_until",
                            "name" => "valid_until",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "Valid Until",
                            'data-rule-required' => "true",
                            'data-msg-required' => app_lang('field_required')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="contract_cc" class=" col-md-3">CC</label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "contract_cc",
                            "name" => "contract_cc",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "CC"
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <!-- <div class="form-group">
                <div class="row">
                    <label for="contract_bcc" class=" col-md-3">BCC</label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "contract_bcc",
                            "name" => "contract_bcc",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => "BCC"
                        ));
                        ?>
                    </div>
                </div>
            </div> -->

            <div class="form-group">
                <div class="row">
                    <label for="subject" class=" col-md-3"><?php echo app_lang("subject"); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "subject",
                            "name" => "subject",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => app_lang("subject"),
                            'data-rule-required' => 'true',
                            'data-msg-required' => app_lang('field_required')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="message" class=" col-md-3"><?php echo app_lang("message"); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "message",
                            "name" => "message",
                            // "value" => process_images_from_content($message, false),
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => app_lang("message"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo view("includes/file_list", array("files" => []));
                    ?>
                </div>
            </div>

            <?php echo view("includes/dropzone_preview"); ?>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default upload-file-button float-start me-auto btn-sm round" type="button" style="color:#7988a2"><i data-feather="file-text" class="icon-16"></i> <?php echo app_lang("upload_your_contract_here"); ?></button>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('send'); ?></button>
    </div>
</div>


<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {

        $('#send-contract-form .select2').select2();

        var uploadUrl = "<?php echo get_uri("projects/upload_contract_file"); ?>";
        var validationUri = "<?php echo get_uri("projects/validate_contract_file"); ?>";

        var dropzone = attachDropzoneWithForm("#project-contract-dropzone", uploadUrl, validationUri);

        window.sendContractForm = $("#send-contract-form").appForm({
            // beforeAjaxSubmit: function(data) {
            //     var custom_message = encodeAjaxPostData(getWYSIWYGEditorHTML("#message"));
            //     $.each(data, function(index, obj) {
            //         if (obj.name === "message") {
            //             data[index]["value"] = custom_message;
            //         }
            //     });
            // },
            onSuccess: function(result) {
                if (result.success) {
                    appAlert.success(result.message, {
                        duration: 10000
                    });
                    // updateContractStatusBar(result.contract_id);

                    window.sendContractForm.closeModal();
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        setDatePicker("#contract_date, #valid_until");

        //load template view on changing of client contact
        // $("#id").select2().on("change", function() {
        //     var contract_id = $(this).val();
        //     var contact_id = $("#contact_id").val();
        //     if (contract_id && contact_id) {
        //         // $("#message").summernote("destroy");
        //         $("#subject").val("");
        //         appLoader.show();
        //         $.ajax({
        //             url: "<?php echo get_uri('contracts/get_send_contract_template/') ?>" + "/" + contract_id + "/" + contact_id + "/json",
        //             dataType: "json",
        //             success: function(result) {
        //                 if (result.success) {
        //                     $("#subject").val(result.subject_view);
        //                     // $("#message").val(result.message_view);
        //                     // initWYSIWYGEditor("#message", {
        //                     //     height: 400,
        //                     //     toolbar: []
        //                     // });
        //                     appLoader.hide();
        //                 }
        //             }
        //         });
        //     }
        // });

    });
</script>