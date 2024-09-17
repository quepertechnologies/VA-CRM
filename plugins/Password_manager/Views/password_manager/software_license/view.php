<div class="modal-body clearfix general-form">
    <div class="container-fluid">
        <div class="clearfix">
            <div class="row">
                <div class="col-md-12 mb10">
                    <strong class="font-18"><?php echo $model_info->name; ?></strong>
                </div>

                <?php if ($model_info->description) { ?>
                    <div class="col-md-12 mb5">
                        <blockquote class="font-14 text-justify border-success"><?php echo $model_info->description ? nl2br(link_it($model_info->description)) : "-"; ?></blockquote>
                    </div>
                <?php } ?>

                <div class="col-md-12 mb15">
                    <span class="text-off font-12">
                        <?php echo app_lang("created_by") . " " . $model_info->created_by_user; ?>
                    </span>
                    <span class="text-off float-end font-12">
                        <?php echo format_to_datetime($model_info->created_at); ?>
                    </span>
                </div>

                <?php if ($model_info->url) { ?>
                    <div class="col-md-12 mb15 url-section">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('url') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14">
                                    <a class="url" href="<?php echo $model_info->url; ?>" target="_blank"><?php echo $model_info->url; ?></a>
                                    <span style="position: inherit; float: right;" id="copy-tooltip" class="help" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo app_lang('copy') ?>">
                                        <span data-feather="copy" class="icon-14 float-end clickable text-secondary copy-button copy-url hide pe-auto mt-1" data-type=".url"></span>
                                    </span>
                                </pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($model_info->version) { ?>
                    <div class="col-md-12 mb15">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('version') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14"><?php echo $model_info->version; ?> </pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-md-12 mb15 license-key-section">
                    <div class="row">
                        <div class="col-md-2">
                            <strong class="float-start mr10 mt-2"><?php echo app_lang('password_manager_license_key') . ": "; ?></strong>
                        </div>
                        <div class="col-md-10">
                            <pre class="font-14">
                                <a href="#" class="password_show mr5 text-decoration-underline"><?php echo app_lang('password_manager_show'); ?></a>
                                <span class="password hide"><?php echo $model_info->license_key; ?></span>
                                <span style="position: inherit; float: right;" id="copy-tooltip" class="help" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo app_lang('copy') ?>">
                                    <span data-feather="copy" class="icon-14 float-end clickable text-secondary copy-button copy-license-key hide pe-auto mt-1" data-type=".password"></span>
                                </span>
                            </pre>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?php if ($model_info->created_by == $login_user->id || ($model_info->created_by_client == 1 && $login_user->is_admin)) { ?>
        <?php echo modal_anchor(get_uri("password_manager/software_license_modal_form/"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit'), array("class" => "btn btn-default", "title" => app_lang('edit'), "data-post-id" => $model_info->id)); ?>
    <?php } ?>

    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>

<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        $(".password_show").on("click", function () {
            $(".password").toggleClass("hide");
            var buttonText = $(this).text();

            if (buttonText === "<?php echo app_lang('password_manager_show'); ?>") {
                $(this).text("<?php echo app_lang('password_manager_hide'); ?>");
            } else if (buttonText === "<?php echo app_lang('password_manager_hide'); ?>") {
                $(this).text("<?php echo app_lang('password_manager_show'); ?>");
            }
        });

        $(".license-key-section, .url-section").hover(
                function () {
                    $(this).find('.copy-license-key, .copy-url').removeClass("hide").show();
                },
                function () {
                    $(this).find('.copy-license-key, .copy-url').hide();
                }
        );

        $('[data-bs-toggle="tooltip"]').tooltip();

        $(".copy-button").on("click", function () {
            var type = $(this).data().type;
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(type).text()).select();
            document.execCommand("copy");
            $temp.remove();

            var $button = $(this); // Store the reference to the clicked element

            // Change tooltip title to "Copied to clipboard"
            $button.parent().attr('title', '<?php echo app_lang('password_manager_copied'); ?>')
                    .tooltip('dispose')
                    .tooltip('enable')
                    .tooltip('show');

            setTimeout(function () {
                // Change tooltip title back to "Copy" again
                $button.parent().attr('title', '<?php echo app_lang('copy'); ?>')
                        .tooltip('dispose')
                        .tooltip('enable');
            }, 1500);
        });
    });

</script>