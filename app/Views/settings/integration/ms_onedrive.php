<div class="card no-border clearfix mb0">
    <?php echo form_open(get_uri("settings/save_ms_onedrive_settings"), array("id" => "ms-onedrive-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label for="enable_ms_onedrive_api_to_upload_file" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('enable_ms_onedrive_api_to_upload_file'); ?></label>
                <div class="col-md-10 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("enable_ms_onedrive_api_to_upload_file", "1", get_setting("enable_ms_onedrive_api_to_upload_file") ? true : false, "id='enable_ms_onedrive_api_to_upload_file' class='form-check-input ml15'");
                    ?>
                    <span class="ms-onedrive-show-hide-area ml10 <?php echo get_setting("enable_ms_onedrive_api_to_upload_file") ? "" : "hide" ?>"><i data-feather="alert-triangle" class="icon-16 text-danger"></i> <?php echo app_lang("ms_onedrive_activation_help_message"); ?></span>
                </div>
            </div>
        </div>

        <div class="ms-onedrive-show-hide-area <?php echo get_setting("enable_ms_onedrive_api_to_upload_file") ? "" : "hide" ?>">

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_client_id" class=" col-md-2"><?php echo app_lang('ms_onedrive_client_id'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_client_id",
                            "name" => "ms_onedrive_client_id",
                            "value" => get_setting('ms_onedrive_client_id'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_client_id'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_client_secret" class=" col-md-2"><?php echo app_lang('ms_onedrive_client_secret'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_client_secret",
                            "name" => "ms_onedrive_client_secret",
                            "value" => get_setting('ms_onedrive_client_secret'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_client_secret'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_tenant_id" class=" col-md-2"><?php echo app_lang('ms_onedrive_tenant_id'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_tenant_id",
                            "name" => "ms_onedrive_tenant_id",
                            "value" => get_setting('ms_onedrive_tenant_id'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_tenant_id'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_redirect_uri" class=" col-md-2"><?php echo app_lang('ms_onedrive_redirect_uri'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_redirect_uri",
                            "name" => "ms_onedrive_redirect_uri",
                            "value" => get_setting('ms_onedrive_redirect_uri'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_redirect_uri'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_grant_type" class=" col-md-2"><?php echo app_lang('ms_onedrive_grant_type'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_grant_type",
                            "name" => "ms_onedrive_grant_type",
                            "value" => get_setting('ms_onedrive_grant_type'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_grant_type'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_refresh_token" class=" col-md-2"><?php echo app_lang('ms_onedrive_refresh_token'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_refresh_token",
                            "name" => "ms_onedrive_refresh_token",
                            "value" => get_setting('ms_onedrive_refresh_token'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_refresh_token'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_clients_directory_id" class=" col-md-2"><?php echo app_lang('ms_onedrive_clients_directory_id'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_clients_directory_id",
                            "name" => "ms_onedrive_clients_directory_id",
                            "value" => get_setting('ms_onedrive_clients_directory_id'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_clients_directory_id'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="ms_onedrive_base_directory_path" class=" col-md-2"><?php echo app_lang('ms_onedrive_base_directory_path'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "ms_onedrive_base_directory_path",
                            "name" => "ms_onedrive_base_directory_path",
                            "value" => get_setting('ms_onedrive_base_directory_path'),
                            "class" => "form-control",
                            "placeholder" => app_lang('ms_onedrive_base_directory_path'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <button id="save-button" type="submit" class="btn btn-primary <?php echo get_setting("enable_ms_onedrive_api_to_upload_file") ? "hide" : "" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        <button id="save-and-authorize-button" type="submit" class="btn btn-primary ml5 <?php echo get_setting("enable_ms_onedrive_api_to_upload_file") ? "" : "hide" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_authorize'); ?></button>
    </div>
    <?php echo form_close(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        var $saveAndAuthorizeBtn = $("#save-and-authorize-button"),
            $saveBtn = $("#save-button"),
            $driveDetailsArea = $(".ms-onedrive-show-hide-area");

        $("#ms-onedrive-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {
                    duration: 10000
                });

            }
        });

        //show/hide google drive details area
        $("#enable_ms_onedrive_api_to_upload_file").click(function() {
            if ($(this).is(":checked")) {
                $saveAndAuthorizeBtn.removeClass("hide");
                $driveDetailsArea.removeClass("hide");
                $saveBtn.addClass("hide");
            } else {
                $saveAndAuthorizeBtn.addClass("hide");
                $driveDetailsArea.addClass("hide");
                $saveBtn.removeClass("hide");
            }
        });

    });
</script>