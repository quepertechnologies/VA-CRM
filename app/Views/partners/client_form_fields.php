<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />
<input type="hidden" name="type" value="person" />
<input type="hidden" name="account_type" value="3" />
<?php $model_info->type = "person" ?>

<div class="tab-content mt15">

<div class="row">
    <div class="col-md-6">    <div class="form-group">
            <label for="first_name" class="strong first_name_section"><?php echo app_lang('first_name'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "first_name",
                    "name" => "first_name",
                    "value" => isset($model_info->first_name) ? $model_info->first_name : '',
                    "class" => "form-control company_name_input_section",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div></div>
    <div class="col-md-6">    <div class="form-group">
            <label for="last_name" class="strong last_name_section"><?php echo app_lang('last_name'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "last_name",
                    "name" => "last_name",
                    "value" => isset($model_info->last_name) ? $model_info->last_name : '',
                    "class" => "form-control company_name_input_section",
                ));
                ?>
            </div>
    </div></div>
    <div class="col-md-6">    <div class="form-group">
        
            <label for="email" class="strong email_section"><?php echo app_lang('email'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "email",
                    "name" => "email",
                    "value" => isset($model_info->email) ? $model_info->email : '',
                    "class" => "form-control company_name_input_section",
                    "type" => 'email',
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
                <p class="text-danger d-none" id="email-alert-cont"></p>
            </div>
        </div>
               
            </div>
    

    <?php
                echo form_input(array(
                    "id" => "unique_id",
                    "name" => "unique_id",
                    "value" => isset($model_info->unique_id) && !empty($model_info->unique_id) ? $model_info->unique_id : '',
                    "class" => "form-control company_name_input_section",
                    'disabled' => true,
                    'Hidden' => true,
                    'style' => "cursor: not-allowed"
                ));
                ?>

    <!-- <div class="form-group">
            <div class="row">
                <label for="date_of_birth" class="<?php echo $label_column; ?> date_of_birth_section"><?php echo app_lang('date_of_birth'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "date_of_birth",
                        "name" => "date_of_birth",
                        "value" => isset($model_info->date_of_birth) ? $model_info->date_of_birth : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('date_of_birth'),
                        "autofocus" => true,
                        "type" => 'date',
                    ));
                    ?>
                </div>
            </div>
        </div> -->

    <div class="col-md-6">    <div class="form-group">
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
                        $phone_codes['+' . $country->phonecode] = $country->nicename . ' +' . $country->phonecode;
                    }

                    $phone_codes[''] = '-';
                    asort($phone_codes);
                    echo form_dropdown(
                        'phone_code',
                        $phone_codes,
                        isset($model_info->phone_code) && !empty($model_info->phone_code) ? $model_info->phone_code : '',
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
                    "type" => 'number',
                ));
                ?>
            </div>
    </div></div>


         <div class="col-md-6">    <div class="form-group">
            <label for="tax_id_number" class="strong"><?php echo app_lang('tax_id_number'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "tax_id_number",
                    "name" => "tax_id_num",
                    "value" => isset($model_info->tax_id_num) ? $model_info->tax_id_num : '',
                    "class" => "form-control company_name_input_section",

                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div></div>


    </div>

    <h3 class="mb-5"><?php echo app_lang('address'); ?></h3>
    <hr>
    <div class="row">
        <div class="col-md-6">    <div class="form-group">
        
            <label for="address" class="strong"><?php echo app_lang('address'); ?></label>
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
        <div class="col-md-6">    <div class="form-group">
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
        <div class="col-md-6">    <div class="form-group">
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

   
    <div class="col-md-6">    <div class="form-group">
      
            <label for="zip" class="strong"><?php echo app_lang('zip'); ?></label>
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
        <div class="col-md-6">    <div class="form-group">
            <label for="country" class="strong"><?php echo app_lang('country'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                if (empty($countries)) {
                    $countries = [];
                } else {
                    $countries = json_decode($countries);
                }
                $country_codes = [];

                foreach ($countries as $country) {
                    $country_codes[$country->nicename] = $country->nicename;
                }
                $country_codes[''] = '-';
                asort($country_codes);
                echo form_dropdown(
                    'country',
                    $country_codes,
                    isset($model_info->country) ? $model_info->country : 'Australia',
                    "class='form-control select2' id='select-country-code'"
                );
                ?>
            </div>
    </div></div>
        <div class="col-md-6"></div>

    </div>


    <!-- <div <?php if (!isset($is_overview)) {
                    echo 'role="tabpanel" class="tab-pane active"';
                } ?> id="personal-info-tab">
        <h3 class="mb-5"><?php echo app_lang('personal_info'); ?></h3>
    </div> -->

    <!-- <div <?php if (!isset($is_overview)) {
                    echo 'role="tabpanel" class="tab-pane"';
                } ?> id="contact-info-tab">
        <h3 class="mb-5"><?php echo app_lang('contact_info'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="secondary_email" class="<?php echo $label_column; ?> secondary_email_section"><?php echo app_lang('secondary_email'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "secondary_email",
                        "name" => "secondary_email",
                        "value" => isset($model_info->secondary_email) ? $model_info->secondary_email : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('secondary_email'),
                        "autofocus" => true,
                        "type" => 'email',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="contact_preference" class="<?php echo $label_column; ?>"><?php echo app_lang('contact_preference'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_radio(array(
                        "id" => "type_email",
                        "name" => "contact_preference",
                        "class" => "form-check-input account_type",
                    ), "email", (isset($model_info->contact_preference) && $model_info->contact_preference === "email") ? true : false);
                    ?>
                    <label for="type_email" class="mr15"><?php echo app_lang('email'); ?></label>
                    <?php
                    echo form_radio(array(
                        "id" => "type_phone",
                        "name" => "contact_preference",
                        "checked" => "checked",
                        "class" => "form-check-input account_type",
                    ), "phone", (isset($model_info->contact_preference) && $model_info->contact_preference === "phone") ? true : false);
                    ?>
                    <label for="type_phone" class="strong"><?php echo app_lang('phone'); ?></label>
                </div>
            </div>
        </div>
    </div>

    <div <?php if (!isset($is_overview)) {
                echo 'role="tabpanel" class="tab-pane"';
            } ?> id="address-tab">
        <div class="form-group">
            <div class="row">
                <label for="marital_status" class="<?php echo $label_column; ?>"><?php echo app_lang('marital_status'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list = array(
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Divorced' => 'Divorced',
                        'Separated' => 'Separated',
                        'Widowed' => 'Widowed',
                        'Annulled' => 'Annulled',
                        'Domestic Partnership / Civil Union' => 'Domestic Partnership / Civil Union'
                    );
                    asort($list);
                    echo form_multiselect(
                        "marriage_status",
                        $list,
                        isset($model_info->marriage_status) ? explode(',', $model_info->marriage_status) : [],
                        "class='select2' placeholder='" . app_lang('marital_status') . "'"
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div <?php if (!isset($is_overview)) {
                echo 'role="tabpanel" class="tab-pane"';
            } ?> id="current-visa-tab">
        <h3 class="mb-5"><?php echo app_lang('current_visa_info'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="country_of_passport" class="<?php echo $label_column; ?> country_of_passport_section"><?php echo app_lang('country_of_passport'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    if (empty($countries)) {
                        $countries = [];
                    } else {
                        $countries = json_decode($countries);
                    }
                    $country_codes = [];

                    foreach ($countries as $country) {
                        $country_codes[$country->nicename] = $country->nicename;
                    }

                    $country_codes[''] = '-';
                    asort($country_codes);
                    echo form_dropdown(
                        'country_of_citizenship',
                        $country_codes,
                        isset($model_info->country_of_citizenship) ? $model_info->country_of_citizenship : 'Australia',
                        "class='form-control select2' id='select-country-code'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="passport_number" class="<?php echo $label_column; ?> passport_number_section"><?php echo app_lang('passport_number'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "passport_number",
                        "name" => "passport_number",
                        "value" => isset($model_info->passport_number) ? $model_info->passport_number : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('passport_number'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="main_applin_pass_exp_date" class="<?php echo $label_column; ?> main_applin_pass_exp_date_section"><?php echo app_lang('main_applin_pass_exp_date'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "main_applin_pass_exp_date",
                        "name" => "main_applin_pass_exp_date",
                        "value" => isset($model_info->main_applin_pass_exp_date) ? $model_info->main_applin_pass_exp_date : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('main_applin_pass_exp_date'),
                        "type" => 'date',
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="visa_type" class="<?php echo $label_column; ?> visa_type_section"><?php echo app_lang('visa_type'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list = array(
                        'Tourist Visa (Visitor Visa)',
                        'Business Visa',
                        'Student Visa',
                        'Work Visa (Employment Visa)',
                        'Temporary Resident Visa',
                        'Permanent Resident Visa',
                        'Family Reunification Visa',
                        'Diplomatic Visa',
                        'Official Visa',
                        'Transit Visa',
                        'Refugee Visa (Asylum Visa)',
                        'Humanitarian Visa',
                        'Journalist Visa',
                        'Crew Visa',
                        'Volunteer Visa',
                        'Investor Visa (Business Investor Visa)',
                        'Artist or Entertainer Visa',
                        'Pilgrimage Visa',
                        'Retirement Visa',
                        'Special Program Visas'
                    );
                    asort($list);
                    echo form_datalist(
                        'visa_type',
                        isset($model_info->visa_type) ? $model_info->visa_type : '',
                        $list,
                        "class='form-control company_name_input_section' placeholder='" . app_lang('visa_type') . "'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="visa_expiry" class="<?php echo $label_column; ?> visa_expiry_section"><?php echo app_lang('visa_expiry'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "visa_expiry",
                        "name" => "visa_expiry",
                        "value" => isset($model_info->visa_expiry) ? $model_info->visa_expiry : '',
                        "class" => "form-control company_name_input_section",
                        "type" => 'date',
                        "placeholder" => app_lang('visa_expiry'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="student_info" class="<?php echo $label_column; ?> student_info_section"><?php echo app_lang('student_info'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "student_info",
                        "name" => "student_info",
                        "value" => isset($model_info->student_info) ? $model_info->student_info : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('student_info'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="preferred_intake" class="<?php echo $label_column; ?> preferred_intake_section"><?php echo app_lang('preferred_intake'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "preferred_intake",
                        "name" => "preferred_intake",
                        "value" => isset($model_info->preferred_intake) ? $model_info->preferred_intake : '',
                        "class" => "form-control company_name_input_section",
                        "type" => 'month',
                        "placeholder" => app_lang('preferred_intake'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div <?php if (!isset($is_overview)) {
                echo 'role="tabpanel" class="tab-pane"';
            } ?> id="secondary-applicant-tab">
        <h3 class="mb-5"><?php echo app_lang('secondary_applicant'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="sec-applicant" class="<?php echo $label_column; ?> sec_applicant_section"><?php echo app_lang('sec_applicant_required'); ?></label>
                <div class="<?php echo $field_column; ?> form-check form-switch">
                    <?php
                    echo form_checkbox(
                        'sec_applicant',
                        isset($model_info->sec_applicant) ? $model_info->sec_applicant : '',
                        isset($model_info->sec_applicant) && $model_info->sec_applicant == 1 ? true : false,
                        "class='form-check-input' id='sec-applicant'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group hide" id='sec-applin-first-name'>
            <div class="row">
                <label for="sec_applicant_first_name" class="<?php echo $label_column; ?> sec_applicant_first_name_section"><?php echo app_lang('sec_applicant_first_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "sec_applicant_first_name",
                        "name" => "sec_applicant_first_name",
                        "value" => isset($model_info->sec_applicant_first_name) ? $model_info->sec_applicant_first_name : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('sec_applicant_first_name'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group hide" id='sec-applin-last-name'>
            <div class="row">
                <label for="sec_applicant_last_name" class="<?php echo $label_column; ?> sec_applicant_last_name_section"><?php echo app_lang('sec_applicant_last_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "sec_applicant_last_name",
                        "name" => "sec_applicant_last_name",
                        "value" => isset($model_info->sec_applicant_last_name) ? $model_info->sec_applicant_last_name : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('sec_applicant_last_name'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group hide" id='sec-applin-dob'>
            <div class="row">
                <label for="sec_applicant_dob" class="<?php echo $label_column; ?> sec_applicant_dob_section"><?php echo app_lang('sec_applicant_dob'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "sec_applicant_dob",
                        "name" => "sec_applicant_dob",
                        "value" => isset($model_info->sec_applicant_dob) ? $model_info->sec_applicant_dob : '',
                        "class" => "form-control company_name_input_section",
                        "type" => 'date',
                        "placeholder" => app_lang('sec_applicant_dob'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group hide" id='sec-applin-phone'>
            <div class="row">
                <label for="sec_applicant_phone" class="<?php echo $label_column; ?> sec_applicant_phone_section"><?php echo app_lang('sec_applicant_phone'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "sec_applicant_phone",
                        "name" => "sec_applicant_phone",
                        "value" => isset($model_info->sec_applicant_phone) ? $model_info->sec_applicant_phone : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('sec_applicant_phone'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group hide" id='sec-applin-email'>
            <div class="row">
                <label for="sec_applicant_email" class="<?php echo $label_column; ?> sec_applicant_email_section"><?php echo app_lang('sec_applicant_email'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "sec_applicant_email",
                        "name" => "sec_applicant_email",
                        "value" => isset($model_info->sec_applicant_email) ? $model_info->sec_applicant_email : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('sec_applicant_email'),
                        "type" => 'email',
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div <?php if (!isset($is_overview)) {
                echo 'role="tabpanel" class="tab-pane"';
            } ?> id="applications-tab">
        <h3 class="mb-5"><?php echo app_lang('applications'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="applications" class="<?php echo $label_column; ?> applications_section"><?php echo app_lang('applications'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "applications",
                        "name" => "application_ids",
                        "value" => isset($model_info->application_ids) ? $model_info->application_ids : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('applications'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div> -->

    <div id="internal-tab">
        <h3 class="mb-5"><?php echo app_lang('internal'); ?></h3>
        <hr>
            <div class="row">
                <div class="col-md-6">            <div class="form-group">
                
                    <label for="consultancy_type" class="strong"><?php echo app_lang('consultancy_type'); ?>
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
                </div></div>
                <div class="col-md-6">      <div class="form-group">
                <label for="location_id" class="strong"><?php echo app_lang('location'); ?></label>
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
                <div class="col-md-6">        <div class="form-group">
                <label for="partner_type" class="strong partner_type_section"><?php echo app_lang('type'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list = array(
                        "referral" => "Referral",
                        "institute" => "Institute",
                        'subagent' => 'Sub Agent',
                        'superagent' => "Super Agent"
                    );
                    asort($list);
                    echo form_dropdown(
                        'partner_type',
                        $list,
                        isset($model_info->partner_type) ? $model_info->partner_type : 'referral',
                        "class='form-control select2' id='select-country-code'"
                    );
                    ?>
                </div>
        </div></div>

        
    <div class="col-md-6">        <div class="form-group">
                <label for="assignee" class="strong"><?php echo app_lang('assignee'); ?>
                    <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
                </label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "assignee",
                        "name" => "assignee",
                        "value" => isset($model_info->assignee) ? $model_info->assignee : '',
                        "class" => "form-control",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div></div>
    <div class="col-md-6">        <div class="form-group">
                <label for="com_percentage" class="strong com_percentage">Commission Percentage</label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "com_percentage",
                        "name" => "com_percentage",
                        "value" => isset($model_info->com_percentage) ? $model_info->com_percentage : '',
                        "class" => "form-control company_name_input_section",
                        "type" => 'number'
                    ));
                    ?>
                </div>
        </div></div>
    <div class="col-md-6">
            <div class="row">
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

       
    <div class="col-md-6">
        <div class="form-group">
            
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
            </div>
        </div>
    </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();

        <?php if (isset($currency_dropdown)) { ?>
            if ($('#currency').length) {
                $('#currency').select2({
                    data: <?php echo json_encode($currency_dropdown); ?>
                });
            }
        <?php } ?>

        <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "client") === "all") { ?>
            $('#assignee').select2({
                data: <?php echo $team_members_dropdown; ?>
            });
            $('#assignee-manager').select2({
                data: <?php echo $team_members_dropdown; ?>
            });
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
        <?php } ?>

        <?php if ($login_user->user_type === "staff") { ?>
            $("#client_labels").select2({
                multiple: true,
                data: <?php echo json_encode($label_suggestions); ?>
            });
            $('#source').select2({
                data: <?php echo $sources_dropdown; ?>
            });
            $('#location_id').select2({
                data: <?php echo json_encode($locations_dropdown); ?>
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