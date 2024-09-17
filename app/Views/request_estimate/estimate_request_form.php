<?php if ($embedded) { ?>
    <style type="text/css">
        .post-file-previews {
            border: none !important;
        }

        #page-content.page-wrapper {
            padding: 10px !important
        }

        #content {
            margin-top: 15px !important
        }
    </style>
<?php } else { ?>
    <style type="text/css">
        .post-file-previews {
            border: none !important;
        }
    </style>
<?php } ?>

<div id="page-content" class="page-wrapper clearfix">
    <div id="estimate-form-container">
        <?php
        echo form_open(get_uri("request_estimate/save_estimate_request"), array("id" => "estimate-request-form", "class" => "general-form", "role" => "form"));
        echo "<input type='hidden' name='form_id' value='$model_info->id' />";
        echo "<input type='hidden' name='assigned_to' value='$model_info->assigned_to' />";
        ?>

        <div id="estimate-form-preview" class="card p15 no-border clearfix post-dropzone" style="max-width: 1000px; margin: auto;">

            <h3 id="estimate-form-title" class=" pl10 pr10"> <?php echo $model_info->title; ?></h3>

            <div class="pl10 pr10"><?php echo nl2br($model_info->description ? $model_info->description : ""); ?></div>
            <div class=" pt10 mt15">
                <!-- CLIENT FIELDS -->
                <div class="row">
                    <?php $hidden_fields = explode(",", get_setting("hidden_client_fields_on_public_estimate_requests")); ?>

                    <?php if (!in_array("company_name", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="company_name"><?php echo app_lang('company_name'); ?>*</label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "company_name",
                                    "name" => "company_name",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('company_name'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("first_name", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="first_name"><?php echo app_lang('first_name'); ?>*</label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "first_name",
                                    "name" => "first_name",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('first_name'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("last_name", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="last_name"><?php echo app_lang('last_name'); ?>*</label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "last_name",
                                    "name" => "last_name",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('last_name'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("email", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="email"><?php echo app_lang('email'); ?>*</label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "email",
                                    "name" => "email",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('email'),
                                    "autofocus" => true,
                                    "autocomplete" => "off",
                                    "data-rule-email" => true,
                                    "data-msg-email" => app_lang("enter_valid_email"),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("address", $hidden_fields)) { ?>
                        <div class="form-group col-md-12">
                            <label for="address"><?php echo app_lang('address'); ?></label>
                            <div>
                                <?php
                                echo form_textarea(array(
                                    "id" => "address",
                                    "name" => "address",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('address')
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("city", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="city"><?php echo app_lang('city'); ?></label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "city",
                                    "name" => "city",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('city')
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("state", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="state"><?php echo app_lang('state'); ?></label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "state",
                                    "name" => "state",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('state')
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("zip", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="zip"><?php echo app_lang('zip'); ?></label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "zip",
                                    "name" => "zip",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('zip')
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("country", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="country"><?php echo app_lang('country'); ?></label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "country",
                                    "name" => "country",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('country')
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("phone", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="phone"><?php echo app_lang('phone'); ?></label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "phone",
                                    "name" => "phone",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('phone'),
                                    'type' => 'number'
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!in_array("date_of_birth", $hidden_fields)) { ?>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <label for="date_of_birth"><?php echo app_lang('date_of_birth'); ?>*</label>
                            <div>
                                <?php
                                echo form_input(array(
                                    "id" => "date_of_birth",
                                    "name" => "date_of_birth",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('date_of_birth'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div>
                        <?php echo view("signin/re_captcha"); ?>
                    </div>

                </div>

                <!-- <div class="table-responsive general-form ">
                        <table id="estimate-form-table" class="display b-t no-thead b-b-only no-hover" cellspacing="0" width="100%">
                        </table>
                    </div> -->
                <div id="estimate-form-table" class='row'></div>
            </div>
            <?php if ($model_info->enable_attachment) { ?>
                <div class="clearfix pl10 pr10 b-b">
                    <?php echo view("includes/dropzone_preview"); ?>
                </div>
            <?php } ?>
            <div class="p15">
                <?php if ($model_info->enable_attachment) { ?>
                    <button class="btn btn-default upload-file-button round" type="button" style="color:#7988a2"><i data-feather='camera' class='icon-16'></i> <?php echo app_lang("upload_updated_cv_resume"); ?></button>
                <?php } ?>
                <button type="submit" class="btn btn-primary"><i data-feather="send" class="icon-16"></i> <?php echo app_lang('request_an_estimate'); ?></button>
                <br /><i data-feather="info" class="text-info icon-16"></i> <small class="text-info mr15">Accepted file type: <b>PDF</b></small>
            </div>
        </div>

        <?php
        echo form_close();
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        if ($('#date_of_birth')) {
            setDatePicker('#date_of_birth');
        }

        $.ajax({
            url: '<?php echo_uri("request_estimate/estimate_form_filed_list_data_as_single_row/" . $model_info->id); ?>',
            type: "GET",
            dataType: 'json',
            success: function(result) {
                if (result.data) {
                    $('#estimate-form-table').html(result.data);
                }
            }
        });
        // $("#estimate-form-table").appTable({
        //     source: '<?php echo_uri("request_estimate/estimate_form_filed_list_data/" . $model_info->id) ?>',
        //     order: [
        //         [1, "asc"]
        //     ],
        //     hideTools: true,
        //     displayLength: 100,
        //     columns: [{
        //             title: "<?php echo app_lang("title") ?>"
        //         },
        //         {
        //             visible: false
        //         },
        //         {
        //             visible: false
        //         }
        //     ],
        //     onInitComplete: function() {
        //         $(".dataTables_empty").hide();
        //     }
        // });
        var enable_attachment = "<?php echo $model_info->enable_attachment; ?>";

        if (enable_attachment === "1") {

            var uploadUrl = "<?php echo get_uri("request_estimate/upload_file"); ?>";
            var validationUrl = "<?php echo get_uri("request_estimate/validate_file"); ?>";
            var dropzone = attachDropzoneWithForm("#estimate-form-preview", uploadUrl, validationUrl);
        }
    });


    $("#estimate-request-form").appForm({
        isModal: false,
        onSubmit: function() {
            appLoader.show();
            $("#estimate-request-form").find('[type="submit"]').attr('disabled', 'disabled');
        },
        onSuccess: function(result) {
            appLoader.hide();
            $("#estimate-form-container").html("");
            appAlert.success(result.message, {
                container: "#estimate-form-container",
                animate: false
            });
            $('.scrollable-page').scrollTop(0); //scroll to top
        }
    });
</script>