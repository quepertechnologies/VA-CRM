<div class="card no-border clearfix mb0">
    <?php echo form_open(get_uri("settings/save_xero_api_settings"), array("id" => "xero-api-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label for="enable_xero_api" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('enable_xero_api'); ?></label>
                <div class="col-md-10 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("enable_xero_api", "1", get_setting("enable_xero_api") ? true : false, "id='enable_xero_api' class='form-check-input ml15'");
                    ?>
                    <span class="dropbox-hellosign-show-hide-area ml10 <?php echo get_setting("enable_xero_api") ? "" : "hide" ?>"><i data-feather="alert-triangle" class="icon-16 text-danger"></i> <?php echo app_lang("xero_api_activation_help_message"); ?></span>
                </div>
            </div>
        </div>

        <div class="xero-api-show-hide-area <?php echo get_setting("enable_xero_api") ? "" : "hide" ?>">

            <div class="form-group">
                <div class="row">
                    <label for="xero_api_client_id" class=" col-md-2"><?php echo app_lang('xero_api_client_id'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "xero_api_client_id",
                            "name" => "xero_api_client_id",
                            "value" => get_setting('xero_api_client_id'),
                            "class" => "form-control",
                            "placeholder" => app_lang('xero_api_client_id'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="xero_api_client_secret" class=" col-md-2"><?php echo app_lang('xero_api_client_secret'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "xero_api_client_secret",
                            "name" => "xero_api_client_secret",
                            "value" => get_setting('xero_api_client_secret'),
                            "class" => "form-control",
                            "placeholder" => app_lang('xero_api_client_secret'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="xero_api_webhook_key" class=" col-md-2"><?php echo app_lang('xero_api_webhook_key'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "xero_api_webhook_key",
                            "name" => "xero_api_webhook_key",
                            "value" => get_setting('xero_api_webhook_key'),
                            "class" => "form-control",
                            "placeholder" => app_lang('xero_api_webhook_key'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="xero_api_redirect_uri" class=" col-md-2"><?php echo app_lang('xero_api_redirect_uri'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "xero_api_redirect_uri",
                            "name" => "xero_api_redirect_uri",
                            "value" => get_setting('xero_api_redirect_uri'),
                            "class" => "form-control",
                            "placeholder" => app_lang('xero_api_redirect_uri'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="xero_api_url_authorize" class=" col-md-2"><?php echo app_lang('xero_api_url_authorize'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "xero_api_url_authorize",
                            "name" => "xero_api_url_authorize",
                            "value" => get_setting('xero_api_url_authorize'),
                            "class" => "form-control",
                            "placeholder" => app_lang('xero_api_url_authorize'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="xero_api_url_access_token" class=" col-md-2"><?php echo app_lang('xero_api_url_access_token'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "xero_api_url_access_token",
                            "name" => "xero_api_url_access_token",
                            "value" => get_setting('xero_api_url_access_token'),
                            "class" => "form-control",
                            "placeholder" => app_lang('xero_api_url_access_token'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="xero_api_url_resource_owner_details" class=" col-md-2"><?php echo app_lang('xero_api_url_resource_owner_details'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "xero_api_url_resource_owner_details",
                            "name" => "xero_api_url_resource_owner_details",
                            "value" => get_setting('xero_api_url_resource_owner_details'),
                            "class" => "form-control",
                            "placeholder" => app_lang('xero_api_url_resource_owner_details'),
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
        <button id="save-button" type="submit" class="btn btn-primary <?php echo get_setting("enable_xero_api") ? "hide" : "" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        <button id="save-and-authorize-button" type="submit" class="btn btn-primary ml5 <?php echo get_setting("enable_xero_api") ? "" : "hide" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_authorize'); ?></button>
    </div>
    <?php echo form_close(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        var $saveAndAuthorizeBtn = $("#save-and-authorize-button"),
            $saveBtn = $("#save-button"),
            $driveDetailsArea = $(".xero-api-show-hide-area");

        $("#xero-api-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {
                    duration: 10000
                });

            }
        });

        //show/hide dropbox hellosign details area
        $("#enable_xero_api").click(function() {
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