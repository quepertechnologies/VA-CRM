<?php
if (!function_exists("make_password_manager_tabs_data")) {

    function make_password_manager_tabs_data($default_password_manager_tabs = array()) {
        $password_manager_tab_order = get_setting("password_manager_tab_order");
        $custom_password_manager_tabs = array();

        if ($password_manager_tab_order) {
            $custom_password_manager_tabs = explode(',', $password_manager_tab_order);
        }

        $final_password_manager_tabs = array();
        if ($custom_password_manager_tabs) {
            foreach ($custom_password_manager_tabs as $custom_password_manager_tab) {
                if (array_key_exists($custom_password_manager_tab, $default_password_manager_tabs)) {
                    $final_password_manager_tabs[$custom_password_manager_tab] = get_array_value($default_password_manager_tabs, $custom_password_manager_tab);
                }
            }
        }

        $final_password_manager_tabs = $final_password_manager_tabs ? $final_password_manager_tabs : $default_password_manager_tabs;

        foreach ($final_password_manager_tabs as $key => $value) {
            echo "<li><a role='presentation' data-bs-toggle='tab' href='" . get_uri($value) . "' data-bs-target='#password-manager-$key-tab'>" . app_lang($key) . "</a></li>";
        }
    }

}
?>

<div id="page-content" class="page-wrapper clearfix">
    <div class="card clearfix">
        <ul id="password-manager-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs scrollable-tabs border-top-radius title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("password_manager_passwords"); ?></h4></li>

            <?php
            //default tab order
            $password_manager_tabs = array(
                "general" => "password_manager/general",
                "email" => "password_manager/email",
                "password_manager_credit_card" => "password_manager/credit_card",
                "password_manager_bank_account" => "password_manager/bank_account",
                "password_manager_software_license" => "password_manager/software_license",
            );

            make_password_manager_tabs_data($password_manager_tabs);
            ?>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("password_manager/general_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('password_manager_add_general'), array("id" => "add-button", "class" => "btn btn-default", "title" => app_lang('password_manager_add_general'))); ?>
                </div>
            </div>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="password-manager-general-tab"></div>
            <div role="tabpanel" class="tab-pane fade" id="password-manager-email-tab"></div>
            <div role="tabpanel" class="tab-pane fade" id="password-manager-password_manager_credit_card-tab"></div>
            <div role="tabpanel" class="tab-pane fade" id="password-manager-password_manager_bank_account-tab"></div>
            <div role="tabpanel" class="tab-pane fade" id="password-manager-password_manager_software_license-tab"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        //change the add button attributes on changing tab panel
        var addButton = $("#add-button");
        $(".nav-tabs li").click(function () {
            var activeField = $(this).find("a").attr("data-bs-target");

            //task status
            if (activeField === "#password-manager-general-tab") {
                addButton.attr("title", "<?php echo app_lang("password_manager_add_general"); ?>");
                addButton.attr("data-title", "<?php echo app_lang("password_manager_add_general"); ?>");
                addButton.attr("data-action-url", "<?php echo get_uri("password_manager/general_modal_form"); ?>");

                addButton.html("<?php echo "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('password_manager_add_general'); ?>");
                feather.replace();
            } else if (activeField === "#password-manager-email-tab") {
                addButton.attr("title", "<?php echo app_lang("password_manager_add_email"); ?>");
                addButton.attr("data-title", "<?php echo app_lang("password_manager_add_email"); ?>");
                addButton.attr("data-action-url", "<?php echo get_uri("password_manager/email_modal_form"); ?>");

                addButton.html("<?php echo "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('password_manager_add_email'); ?>");
                feather.replace();
            } else if (activeField === "#password-manager-password_manager_credit_card-tab") {
                addButton.attr("title", "<?php echo app_lang("password_manager_add_credit_card"); ?>");
                addButton.attr("data-title", "<?php echo app_lang("password_manager_add_credit_card"); ?>");
                addButton.attr("data-action-url", "<?php echo get_uri("password_manager/credit_card_modal_form"); ?>");

                addButton.html("<?php echo "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('password_manager_add_credit_card'); ?>");
                feather.replace();
            } else if (activeField === "#password-manager-password_manager_bank_account-tab") {
                addButton.attr("title", "<?php echo app_lang("password_manager_add_bank_account"); ?>");
                addButton.attr("data-title", "<?php echo app_lang("password_manager_add_bank_account"); ?>");
                addButton.attr("data-action-url", "<?php echo get_uri("password_manager/bank_account_modal_form"); ?>");

                addButton.html("<?php echo "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('password_manager_add_bank_account'); ?>");
                feather.replace();
            } else if (activeField === "#password-manager-password_manager_software_license-tab") {
                addButton.attr("title", "<?php echo app_lang("password_manager_add_software_license"); ?>");
                addButton.attr("data-title", "<?php echo app_lang("password_manager_add_software_license"); ?>");
                addButton.attr("data-action-url", "<?php echo get_uri("password_manager/software_license_modal_form"); ?>");

                addButton.html("<?php echo "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('password_manager_add_software_license'); ?>");
                feather.replace();
            }
        });
    });
</script>