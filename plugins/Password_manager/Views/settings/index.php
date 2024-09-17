<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "password_manager";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="card">
                <div class="page-title clearfix">
                    <h4> <?php echo app_lang('password_manager'); ?></h4>
                </div>

                <?php echo form_open(get_uri("password_manager_settings/save"), array("id" => "password-manager-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label for="password_manager_tab_order" class=" col-md-2"><?php echo app_lang('password_manager_set_password_manager_tab_order'); ?></label>
                            <div class=" col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "password_manager_tab_order",
                                    "name" => "password_manager_tab_order",
                                    "value" => get_setting("password_manager_tab_order"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('password_manager_password_manager_tab_order')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#password-manager-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#password_manager_tab_order").select2({
            multiple: true,
            data: <?php echo ($password_manager_tabs_dropdown); ?>
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>