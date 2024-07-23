<div class="card no-border clearfix mb0">
    <?php echo form_open(get_uri("settings/save_browse_ai_settings"), array("id" => "browse-ai-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="card-body">

        <div class="form-group">
            <div class="row">
                <label for="enable_browse_ai_api" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('enable_browse_ai_api'); ?></label>
                <div class="col-md-10 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("enable_browse_ai_api", "1", get_setting("enable_browse_ai_api") ? true : false, "id='enable_browse_ai_api' class='form-check-input ml15'");
                    ?>
                    <span class="dropbox-hellosign-show-hide-area ml10 <?php echo get_setting("enable_browse_ai_api") ? "" : "hide" ?>"><i data-feather="alert-triangle" class="icon-16 text-danger"></i> <?php echo app_lang("browse_ai_activation_help_message"); ?></span>
                </div>
            </div>
        </div>

        <div class="browse-ai-show-hide-area <?php echo get_setting("enable_browse_ai_api") ? "" : "hide" ?>">

            <div class="form-group">
                <div class="row">
                    <label for="browse_ai_api_key" class=" col-md-2"><?php echo app_lang('browse_ai_api_key'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "browse_ai_api_key",
                            "name" => "browse_ai_api_key",
                            "value" => get_setting('browse_ai_api_key'),
                            "class" => "form-control",
                            "placeholder" => app_lang('browse_ai_api_key'),
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
        <button id="save-button" type="submit" class="btn btn-primary <?php echo get_setting("enable_browse_ai_api") ? "hide" : "" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        <button id="save-and-authorize-button" type="submit" class="btn btn-primary ml5 <?php echo get_setting("enable_browse_ai_api") ? "" : "hide" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_authorize'); ?></button>
    </div>
    <?php echo form_close(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        var $saveAndAuthorizeBtn = $("#save-and-authorize-button"),
            $saveBtn = $("#save-button"),
            $driveDetailsArea = $(".browse-ai-show-hide-area");

        $("#browse-ai-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {
                    duration: 10000
                });

            }
        });

        //show/hide dropbox hellosign details area
        $("#enable_browse_ai_api").click(function() {
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