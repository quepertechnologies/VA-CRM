<div class="modal-body clearfix general-form">
    <div class="container-fluid">
        <div class="clearfix">
            <div class="row">
                <div class="col-md-12 mb10">
                    <strong class="font-18"><?php echo $model_info->name; ?></strong>
                </div>

                <?php if ($model_info->description) { ?>
                    <div class="col-md-12 mb5">
                        <blockquote class="font-14 text-justify border-warning"><?php echo $model_info->description ? nl2br(link_it($model_info->description)) : "-"; ?></blockquote>
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
                    <div class="col-md-12 mb15">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('url') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14"><a href="<?php echo $model_info->url; ?>" target="_blank"><?php echo $model_info->url; ?></a> <span data-feather="copy" class="icon-14 float-end clickable text-secondary copy-url hide pe-auto mt-1"></span></pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-md-12 mb15">
                    <div class="row">
                        <div class="col-md-2">
                            <strong class="float-start mr10 mt-2"><?php echo app_lang('username') . ": "; ?></strong>
                        </div>
                        <div class="col-md-10">
                            <pre class="font-14"><?php echo $model_info->username; ?> </pre>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb15 pin-section">
                    <div class="row">
                        <div class="col-md-2">
                            <strong class="float-start mr10 mt-2"><?php echo app_lang('password_manager_pin') . ": "; ?></strong>
                        </div>
                        <div class="col-md-10">
                            <pre class="font-14">
                                <a href="#" class="password_show mr5 text-decoration-underline"><?php echo app_lang('password_manager_show'); ?></a> <span class="password hide"><?php echo $model_info->pin; ?></span>
                                <span style="position: inherit; float: right;" class="help" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo app_lang('copy') ?>">
                                    <span data-feather="copy" class="icon-14 float-end clickable text-secondary copy-button copy-pin hide pe-auto mt-1" data-type=".password"></span>
                                </span>
                            </pre>
                        </div>
                    </div>
                </div>

                <?php if ($model_info->bank_name) { ?>
                    <div class="col-md-12 mb15">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('password_manager_bank_name') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14"><?php echo $model_info->bank_name; ?> </pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($model_info->bank_code) { ?>
                    <div class="col-md-12 mb15">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('password_manager_bank_code') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14"><?php echo $model_info->bank_code; ?> <span data-feather="copy" class="icon-14 float-end clickable text-secondary copy-bank-code hide pe-auto mt-1"></span></pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($model_info->account_holder) { ?>
                    <div class="col-md-12 mb15">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('password_manager_account_holder') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14"><?php echo $model_info->account_holder; ?> </pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($model_info->account_number) { ?>
                    <div class="col-md-12 mb15 account-number-section">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('password_manager_account_number') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14">
                                    <span class="account-number"><?php echo $model_info->account_number; ?></span>
                                    <span style="position: inherit; float: right;" class="help" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo app_lang('copy') ?>">
                                        <span data-feather="copy" class="icon-14 float-end clickable text-secondary copy-button copy-account-number hide pe-auto mt-1" data-type=".account-number"></span>
                                    </span>
                                </pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($model_info->iban) { ?>
                    <div class="col-md-12 mb15">
                        <div class="row">
                            <div class="col-md-2">
                                <strong class="float-start mr10 mt-2"><?php echo app_lang('password_manager_iban') . ": "; ?></strong>
                            </div>
                            <div class="col-md-10">
                                <pre class="font-14"><?php echo $model_info->iban; ?> </pre>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?php if ($model_info->created_by == $login_user->id || ($model_info->created_by_client == 1 && $login_user->is_admin)) { ?>
        <?php echo modal_anchor(get_uri("password_manager/bank_account_modal_form/"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit'), array("class" => "btn btn-default", "title" => app_lang('edit'), "data-post-id" => $model_info->id)); ?>
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

        $(".account-number-section, .pin-section").hover(
                function () {
                    $(this).find('.copy-account-number, .copy-pin').removeClass("hide").show();
                },
                function () {
                    $(this).find('.copy-account-number, .copy-pin').hide();
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