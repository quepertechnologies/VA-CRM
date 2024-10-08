<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />
<input type="hidden" name="type" value="person" />
<input type="hidden" name="password" id="password" value="">

<!-- <div class="form-group">
    <div class="row">
        <label for="capture_passport" class="<?php echo $label_column; ?>"><?php echo app_lang('capture_passport'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_upload(array(
                "id" => "capture_passport",
                "name" => "capture_passport",
                "class" => "form-control"
            ));
            ?>
        </div>
    </div>
</div> -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="student-onshore" class="strong"><?php echo app_lang('client_onshore'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                $list = array(
                    '' => '-',
                    '0' => 'No',
                    '1' => "Yes"
                );
                echo form_dropdown(
                    'student_onshore',
                    $list,
                    $model_info->student_onshore ? $model_info->student_onshore : 1,
                    "class='form-control validate-hidden' id='student-onshore' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"
                );
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="first_name" class="strong"><?php echo app_lang('first_name'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "first_name",
                    "name" => "first_name",
                    "value" => isset($model_info->first_name) ? $model_info->first_name : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="last_name" class="strong"><?php echo app_lang('last_name'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "last_name",
                    "name" => "last_name",
                    "value" => isset($model_info->last_name) ? $model_info->last_name : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div></div>

        
        <?php
        echo form_input(array(
            "id" => "unique_id",
            "name" => "unique_id",
            "value" => isset($model_info->unique_id) && !empty($model_info->unique_id) ? $model_info->unique_id : '',
            "class" => "form-control company_name_input_section",
            "autofocus" => true,
            'disabled' => true,
            'hidden' => true,
            'style' => "cursor: not-allowed"
        ));
        ?>
        



        <div class="col-md-6"><div class="form-group">
            <label for="preferred_name" class="strong"><?php echo app_lang('preferred_name'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "preferred_name",
                    "name" => "preferred_name",
                    "value" => isset($model_info->preferred_name) ? $model_info->preferred_name : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="date_of_birth" class="strong"><?php echo app_lang('date_of_birth'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "date_of_birth",
                    "name" => "date_of_birth",
                    "value" => isset($model_info->date_of_birth) ? $model_info->date_of_birth : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="country_of_birth" class="strong"><?php echo app_lang('country_of_birth'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "country_of_birth",
                    "name" => "country",
                    "value" => isset($model_info->country) ? $model_info->country : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div></div>


        <div class="col-md-6"><div class="form-group">
            
            <label for="country_of_citizenship" class="strong">Country of Citizenship</label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "country_of_citizenship",
                    "name" => "country_of_citizenship",
                    "value" => isset($model_info->country_of_citizenship) ? $model_info->country_of_citizenship : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="country_of_residence" class="strong">Country of Residence</label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "country_of_residence",
                    "name" => "country_of_residence",
                    "value" => isset($model_info->country_of_residence) ? $model_info->country_of_residence : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="visa_type" class="strong"><?php echo app_lang('visa_type'); ?>
                <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
            </label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "visa_type",
                    "name" => "visa_type",
                    "value" => isset($model_info->visa_type) ? $model_info->visa_type : '',
                    "class" => "form-control validate-hidden",
                ));
                ?>
            </div>
        </div></div>


        <div class="col-md-6"><div class="form-group" id="visa_2_field">
            
            <label for="visa_2" class="strong">Visa Applied For</label>
            <div class=" <?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "visa_2",
                    "name" => "visa_2",
                    "value" => isset($model_info->visa_2) ? $model_info->visa_2 : '',
                    "class" => "form-control select2",
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="visa_expiry" class="visa_expiry strong"><?php echo app_lang('visa_expiry'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "visa_expiry",
                    "name" => "visa_expiry",
                    "value" => isset($model_info->visa_expiry) ? $model_info->visa_expiry : '',
                    "class" => "form-control company_name_input_section",
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6"><div class="form-group">
        <label for="state" class="strong"><?php echo app_lang('state'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "state",
                "name" => "state",
                "value" => $model_info->state,
                "class" => "form-control",
            ));
            ?>
        </div>
    </div></div>


    <div class="col-md-6"><div class="form-group">
        
        <label for="city" class="strong"><?php echo app_lang('city'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "city",
                "name" => "city",
                "value" => $model_info->city,
                "class" => "form-control",
            ));
            ?>
        </div>
    </div></div>
    <div class="col-md-6"><div class="form-group">
        <label for="zip" class="strong"><?php echo app_lang('post_code'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "zip",
                "name" => "zip",
                "value" => $model_info->zip,
                "class" => "form-control",
            ));
            ?>
        </div>
    </div></div>



    <div class="col-md-6"><div class="form-group">
        <label for="address" class="strong"><?php echo app_lang('address_line_1'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_textarea(array(
                "id" => "address",
                "name" => "address",
                "value" => $model_info->address ? $model_info->address : "",
                "class" => "form-control",
            ));
            ?>
        </div>
    </div></div>
    <div class="col-md-6"><div class="form-group">

        <label for="address" class="strong"><?php echo app_lang('address_line_2'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_textarea(array(
                "id" => "address_2",
                "name" => "address_2",
                "value" => isset($model_info->address_2) ? $model_info->address_2 : "",
                "class" => "form-control",
            ));
            ?>
        </div>
    </div></div>
    <div class="col-md-6"><div class="form-group">
     
        <label for="phone" class="strong phone_section"><?php echo app_lang('phone'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <div class="input-group-prepend">
                <?php
                $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                if (empty($countries)) {
                    $countries = [];
                } else {
                    $countries = json_decode($countries);
                }
                $phone_codes = [];

                foreach ($countries as $country) {
                    $phone_codes['+' . $country->phonecode] = '(+' . $country->phonecode . ') ' . $country->nicename;
                }

                echo form_dropdown(
                    'phone_code',
                    $phone_codes,
                    isset($model_info->phone_code) ? $model_info->phone_code : '+61',
                    "class='form-control select2' id='select-phone-code'"
                );
                ?>
            </div>
            <?php
            echo form_input(array(
                "id" => "phone",
                "name" => "phone",
                "value" => isset($model_info->phone) ? $model_info->phone : '',
                "class" => "form-control company_name_input_section",
                "autofocus" => true,
                "type" => 'number',
            ));
            ?>
        </div>
    </div></div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="email" class="strong"><?php echo app_lang('email'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "email",
                    "name" => "email",
                    "value" => isset($model_info->email) ? $model_info->email : '',
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
                <p class="text-danger d-none" id="email-alert-cont"></p>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="landline" class="strong"><?php echo app_lang('landline'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "landline",
                    "name" => "landline",
                    "value" => isset($model_info->landline) ? $model_info->landline : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                    "type" => 'number',
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="gender" class="strong"><?php echo app_lang('gender'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "gender",
                    "name" => "gender",
                    "value" => isset($model_info->gender) ? $model_info->gender : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div></div>


        <div class="col-md-6"><div class="form-group">
         
            <label for="marriage_status" class="strong"><?php echo app_lang('marriage_status'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_dropdown(
                    "marriage_status",
                    array(
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Divorced' => 'Divorced',
                        'Separated' => 'Separated',
                        'Widowed' => 'Widowed',
                        'Annulled' => 'Annulled',
                        'Domestic Partnership / Civil Union' => 'Domestic Partnership / Civil Union'
                    ),
                    isset($model_info->marriage_status) ? $model_info->marriage_status : '',
                    "class='form-control select2' id='marriage-status'"
                );
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="areas_of_interest" class="strong"><?php echo app_lang('areas_of_interest'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "areas_of_interest",
                    "name" => "areas_of_interest",
                    "value" => isset($model_info->areas_of_interest) ? $model_info->areas_of_interest : '',
                    "class" => "form-control company_name_input_section",
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"></div>

    </div>


    <div <?php if (!isset($is_overview)) {
        echo 'role="tabpanel" class="tab-pane"';
    } ?> id="internal-tab">
    <h3 class="mb-5"><?php echo app_lang('internal'); ?></h3>
    <hr>
    <div class="row">
       <div class="col-md-6">    
        <div class="form-group">
            
            <label for="consultancy_type" class=""><?php echo app_lang('consultancy_type'); ?>
            <span class="help" data-container="body" data-bs-toggle="tooltip" title="Change the consultancy type of the client"><i data-feather="help-circle" class="icon-16"></i></span>
        </label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "consultancy_type",
                "name" => "consultancy_type",
                "value" => isset($model_info->account_type) ? $model_info->account_type : '',
                "class" => "form-control",
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required")
            ));
            ?>
        </div>
    </div>
</div>
<div class="col-md-6">    <div class="form-group">
    <label for="location_id" class=""><?php echo app_lang('location'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "location_id",
            "name" => "location_id",
            "value" => isset($model_info->location_id) ? $model_info->location_id : '',
            "class" => "form-control",
            "data-rule-required" => true,
            "data-msg-required" => app_lang("field_required")
        ));
        ?>
    </div>
</div></div>
<div class="col-md-6">    <div class="form-group">
    <label for="assignee" class=""><?php echo app_lang('assignee'); ?>
    <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
</label>
<div class="<?php echo $field_column; ?>">
    <?php
    echo form_input(array(
        "id" => "created_by",
        "name" => "assignee",
        "value" => isset($model_info->assignee) ? $model_info->assignee : '',
        "class" => "form-control",
        "data-rule-required" => true,
        "data-msg-required" => app_lang("field_required")
    ));
    ?>
</div>
</div></div>
<div class="col-md-6">    <?php if ($login_user->user_type === "staff") { ?>
    <div class="form-group">
     
        <label for="groups" class=""><?php echo app_lang('client_groups'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "group_ids",
                "name" => "group_ids",
                "value" => $model_info->group_ids,
                "class" => "form-control",
            ));
            ?>
        </div>
    </div>
</div>
<?php } ?>
<div class="col-md-6">    <div class="form-group">
    <label for="parent_id" class=""><?php echo app_lang('attach_to_organization'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "attach-organization",
            "name" => "parent_id",
            "value" => isset($model_info->parent_id) ? $model_info->parent_id : '',
            "class" => "form-control select2",

        ));
        ?>
    </div>
</div></div>
<div class="col-md-6">    <div class="form-group">
    <label for="partner_id" class=""><?php echo app_lang('subagent'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "partner_id",
            "name" => "partner_id",
            "value" => isset($model_info->partner_id) ? $model_info->partner_id : '',
            "class" => "form-control select2",

        ));
        ?>
    </div>
</div></div>


    <!-- <div class="form-group">
        <div class="row">
            <label for="assignee-manager" class="<?php echo $label_column; ?> assignee_manager_section"><?php echo app_lang('assignee_manager'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "created_by_manager",
                    "name" => "assignee_manager",
                    "value" => isset($model_info->assignee_manager) ? $model_info->assignee_manager : '',
                    "class" => "form-control company_name_input_section",
                    "placeholder" => app_lang('assignee_manager'),

                ));
                ?>
            </div>
        </div>
    </div> -->

    <div class="col-md-6">   <div class="form-group">
        <label for="source" class="strong source_section"><?php echo app_lang('source'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "source",
                "name" => "source",
                "value" => isset($model_info->source) ? $model_info->source : '',
                "class" => "form-control",
            ));
            ?>
        </div>
    </div></div>
    <div class="col-md-6">    <div class="form-group">
        <label for="tag_name" class="strong tag_name_section"><?php echo app_lang('tag_name'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "client_labels",
                "name" => "tag_name",
                "value" => isset($model_info->tag_name) ? $model_info->tag_name : '',
                "class" => "form-control company_name_input_section",

            ));
            ?>
        </div>
    </div></div>

    
</div>

</div>

<!-- <div class="form-group">
    <div class="row">
        <label for="vevo_check" class="<?php echo $label_column; ?>"><?php echo app_lang('vevo_check'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_radio(array(
                "id" => "vevo_check_yes",
                "name" => "vevo_check",
                "checked" => "checked",
                "class" => "form-check-input account_type",
            ), "yes", (isset($model_info->vevo_check) && $model_info->vevo_check === "yes") ? true : false);
            ?>
            <label for="vevo_check_yes" class="mr15"><?php echo app_lang('yes'); ?></label>
            <?php
            echo form_radio(array(
                "id" => "vevo_check_no",
                "name" => "vevo_check",
                "class" => "form-check-input account_type",
            ), "no", (isset($model_info->vevo_check) && $model_info->vevo_check === "no") ? true : false);
            ?>
            <label for="vevo_check_no" class="strong"><?php echo app_lang('no'); ?></label>
        </div>
    </div>
</div> -->

<script type="text/javascript">
    $(document).ready(function() {

        $("#visa_2_field").show();

        setDatePicker("#visa_expiry,#date_of_birth");

        $('#marriage-status').select2();
        $('#select-phone-code').select2();

        $('#visa_type').select2({
            data: <?php echo $visa_type_dropdown; ?>
        }).on('change', function() {
            value = $(this).val();
            if (['010', '020', '030', '040', '600'].includes(value)) {
                $('#visa_expiry')
                .removeAttr('data-rule-required')
                .removeAttr('required');
                $("#visa_2_field").show();
            } else {
                $('#visa_expiry').attr('data-rule-required', true);
                $("#visa_2_field").hide();
            }
        });

        $('#student-onshore').select2().on('change', function() {
            if ($(this).val() == '1') {
                $('#visa_type')
                .attr('required', 'required')
                .attr('data-rule-required', true)
                .attr('data-msg-required', '<?php echo app_lang("field_required") ?>');
                $('#visa_expiry')
                .attr('required', 'required')
                .attr('data-rule-required', true)
                .attr('data-msg-required', '<?php echo app_lang("field_required") ?>');
            } else {
                $('#visa_type')
                .removeAttr('data-rule-required')
                .removeAttr('data-msg-required')
                .removeAttr('required')
                .removeAttr('aria-describedby')
                .parent()
                .parent()
                .parent()
                .removeClass("has-error");
                $('#visa_expiry')
                .removeAttr('data-rule-required')
                .removeAttr('data-msg-required')
                .removeAttr('required')
                .removeAttr('aria-describedby')
                .parent()
                .parent()
                .parent()
                .removeClass("has-error");
                $('#visa_expiry-error').remove();
                $('#visa_expiry_msg_cont').remove();
                $('#visa_type-error').remove();
                $('#visa_type_msg_count').remove();
            }
        });

        // $('#visa_type').on('change', function() {
        //     value = this.value;
        //     if (value == '010' || value == '020' || value == '030' || value == '040' || value == '600') {
        //         $('#visa_expiry').prop('required', false);
        //         $("#visa_2_field").show();
        //     } else {
        //         $('#visa_expiry').prop('required', true);
        //         $("#visa_2_field").hide();
        //     }
        // });

        // $('#student-onshore').select2().on('change', function() {
        //     if (this.checked) {
        //         $('#visa_type').prop('required', true);
        //         $('#visa_expiry').prop('required', true);
        //     } else {
        //         $('#visa_type').prop('required', false);
        //         $('#visa_expiry').prop('required', false);
        //     }
        // });

        $("#password").val(getRndomString(8));
        $('[data-bs-toggle="tooltip"]').tooltip();

        <?php if (isset($currency_dropdown)) { ?>
            if ($('#currency').length) {
                $('#currency').select2({
                    data: <?php echo json_encode($currency_dropdown); ?>
                });
            }
        <?php } ?>

        <?php if (isset($groups_dropdown)) { ?>
            $("#group_ids").select2({
                multiple: true,
                data: <?php echo json_encode($groups_dropdown); ?>
            });
        <?php } ?>

        <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "client") === "all") { ?>
            $('#created_by').select2({
                data: <?php echo $team_members_dropdown; ?>
            });

            $('#created_by_manager').select2({
                data: <?php echo $team_members_dropdown; ?>
            });

            $('#partner_id').select2({
                data: <?php echo $partners_members_dropdown; ?>
            });

            $('#source').select2({
                data: <?php echo $sources_dropdown; ?>
            });

            $('#location_id').select2({
                data: <?php echo json_encode($locations_dropdown); ?>
            });

            $('#attach-organization').select2({
                data: <?php echo json_encode($organizations_dropdown); ?>
            });

            // $('#visa_type').select2({
            //     data: <?php echo $visa_type_dropdown; ?>
            // });
        <?php } ?>

        <?php if ($login_user->user_type === "staff") { ?>
            $("#client_labels").select2({
                multiple: true,
                data: <?php echo json_encode($label_suggestions); ?>
            });
        <?php } ?>

        $("#consultancy_type").select2({
            data: <?php echo json_encode($account_types_dropdown); ?>
        });

        $('.account_type').click(function() {
            var inputValue = $(this).attr("value");
            if (inputValue === "person") {
                $(".company_name_section").html("Name");
                $(".company_name_input_section").attr("placeholder", "Name");
            } else {
                $(".company_name_section").html("Company name");
                $(".company_name_input_section").attr("placeholder", "Company name");
            }
        });

        $(".select2").select2();
    });
</script>