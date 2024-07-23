<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />

<div class="tab-content mt15">
    <div class="form-group">
        <div class="row">
            <label for="client_id" class="<?php echo $label_column; ?>"><?php echo app_lang('select_client'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "clients",
                    "name" => "client_id",
                    "class" => "form-control select2",
                    'placeholder' => app_lang('select_client'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label for="visit_purpose" class="<?php echo $label_column; ?>"><?php echo app_lang('visit_purpose'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_textarea(array(
                    "id" => "visit_purpose",
                    "name" => "visit_purpose",
                    "value" => '',
                    "class" => "form-control company_name_input_section",
                    "placeholder" => app_lang('visit_purpose')
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label for="assignee" class="<?php echo $label_column; ?>"><?php echo app_lang('select_checkin_assignee'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "assignee",
                    "name" => "assignee",
                    "value" => '',
                    "class" => "form-control company_name_input_section",
                    "placeholder" => app_lang('select_checkin_assignee'),
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($clients_dropdown)) { ?>
            $('#clients').select2({
                data: <?php echo $clients_dropdown; ?>
            });
        <?php } ?>
        <?php if (isset($team_members_dropdown)) { ?>
            $('#assignee').select2({
                data: <?php echo $team_members_dropdown; ?>
            });
        <?php } ?>

        $(".select2").select2();
    });
</script>