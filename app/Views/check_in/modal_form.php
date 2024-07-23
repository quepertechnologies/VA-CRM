<?php echo form_open(get_uri("check_in/save"), array("id" => "attendance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
         <div class="row">
            <label for="account_type" class="<?php echo $label_column; ?>">Consultation Type</label>
            <div class="<?php echo $field_column; ?>">
                <?php                
                echo form_dropdown("account_type", $account_types_filter_dropdown, $client_info->account_type, 'class="form-control id="account_type" select2" data-rule-required="true" data-msg-required="' . app_lang("field_required") . '"');
                ?>
            </div>
        </div>
        </div>
        <div class="form-group" id="location-id-field">
            <div class="row">
                <label for="location_id" class="<?php echo $label_column; ?>"><?php echo app_lang('select_location'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $location_id = $model_info->location_id ? [$model_info->location_id] : $login_user->location_id;
                    echo form_dropdown(
                        'location_id',
                        $locations_dropdown,
                        $location_id,
                        'class="form-control select2" data-rule-required="true" data-msg-required="' . app_lang("field_required") . '"',
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group" id="first-name-field">
            <div class="row">
                <label for="first_name" class="<?php echo $label_column; ?>"><?php echo app_lang('first_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "first_name",
                        "name" => "first_name",
                        'value' => $model_info->first_name ? $model_info->first_name : '',
                        "class" => "form-control",
                        'placeholder' => app_lang('first_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        // 'value' => isset($model_info) && $model_info->first_name ? $model_info->first_name : ''
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group" id="last-name-field">
            <div class="row">
                <label for="last_name" class="<?php echo $label_column; ?>"><?php echo app_lang('last_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "last_name",
                        "name" => "last_name",
                        'value' => $model_info->last_name ? $model_info->last_name : '',
                        "class" => "form-control",
                        'placeholder' => app_lang('last_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group" id="email-field">
            <div class="row">
                <label for="email" class="<?php echo $label_column; ?>"><?php echo app_lang('email'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        'value' => $model_info->email ? $model_info->email : '',
                        "class" => "form-control",
                        'placeholder' => app_lang('email'),
                        'type' => 'email',
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="phone" class="<?php echo $label_column; ?> phone_section"><?php echo app_lang('contact_number'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <div class="input-group-prepend">
                        <?php
                        $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                        if (empty($countries)) {
                            $countries = [];
                        } else {
                            $countries = json_decode($countries);
                        }
                        $list = [];

                        foreach ($countries as $country) {
                            $list['+' . $country->phonecode] = $country->nicename . ' +' . $country->phonecode;
                        }

                        $list[''] = '-';
                        ksort($list);
                        echo form_dropdown(
                            'phone_code',
                            $list,
                            isset($client_info->phone_code) && !empty($client_info->phone_code) ? $client_info->phone_code : '',
                            "class='form-control select2' id='select-phone-code'"
                        );
                        ?>
                    </div>
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                        "value" => isset($client_info->phone) ? $client_info->phone : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('contact_number'),
                        
                        "type" => 'number',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <!-- <div class="form-group hide" id="company-name-field">
            <div class="row">
                <label for="company_name" class="<?php echo $label_column; ?>"><?php echo app_lang('company_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "company_name",
                        "name" => "company_name",
                        "class" => "form-control",
                        'placeholder' => app_lang('company_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div> -->
        <div class="form-group">
            <div class="row">
                <label for="visit_purpose" class="<?php echo $label_column; ?>"><?php echo app_lang('visit_purpose'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "visit_purpose",
                        "name" => "note",
                        'value' => $model_info->note ? $model_info->note : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('visit_purpose'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <?php if ($model_info->id) { ?>
            <div class="form-group" id="assignee-field">
                <div class="row">
                    <label for="assignee" class="<?php echo $label_column; ?>"><?php echo app_lang('select_checkin_assignee'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "assignee",
                            "name" => "assignee",
                            "value" => $model_info->assignee ? $model_info->assignee : '',
                            "class" => "form-control company_name_input_section",
                            "placeholder" => app_lang('select_checkin_assignee'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($model_info->id) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="status" class="<?php echo $label_column; ?>"><?php echo app_lang('status'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        $list = array(
                            '' => '-',
                            'Waiting' => 'Waiting',
                            'Attending' => 'Attending',
                            'Completed' => 'Completed',
                            'Archived' => 'Archived',
                        );
                        ksort($list);
                        echo form_dropdown(
                            'status',
                            $list,
                            $model_info->status ? $model_info->status : '',
                            "class='form-control select2' id='status'"
                        );
                        ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <input type="hidden" name="status" value="Waiting">
        <?php }  ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#attendance-form").appForm({
            onSuccess: function(result) {
                $(".dataTable:visible").appTable({
                    newData: result.data,
                    dataId: result.id
                });
            }
        });

        setTimeout(function() {
            $("#clients").focus();
        }, 200);

        $("#assignee").select2({
            data: <?php echo $team_members_dropdown; ?>
        });
        
        $('#account_type').on('change', function() {
              value  = this.value;
                if(value == 4)
                  {
                    $('#company_field').show();
                    $('#heading').show();
                  }
                  else
                  {
                    $('#company_field').hide();
                    $('#heading').hide();
                  }
            });

    });

        $(".select2").select2();

        // $(document).on('click', '#new-client', function() {
        //     if ($(this).is(':checked')) {
        //         $('#first-name-field').removeClass('hide');
        //         $('#last-name-field').removeClass('hide');
        //         $('#client-id-field').addClass('hide');
        //         $('#account-type-field').removeClass('hide');
        //         // $('#email-field').removeClass('hide');
        //     } else {
        //         $('#company-name-field').addClass('hide');
        //         $('#first-name-field').addClass('hide');
        //         $('#last-name-field').addClass('hide');
        //         $('#client-id-field').removeClass('hide');
        //         $('#account-type-field').addClass('hide');
        //         // $('#email-field').addClass('hide');
        //     }
        // })

        // $(document).on('change', 'input[name="account_type"]:checked', function() {
        //     const account_type = $(this).val();
        //     if (account_type === 'organization') {
        //         $('#company-name-field').removeClass('hide');
        //         $('#first-name-field').addClass('hide');
        //         $('#last-name-field').addClass('hide');
        //     } else {
        //         $('#company-name-field').addClass('hide');
        //         $('#first-name-field').removeClass('hide');
        //         $('#last-name-field').removeClass('hide');
        //     }
        // });

</script>