<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />
<input type="hidden" name="type" value="organization" />
<input type="hidden" name="account_type" value="4" />
<?php $model_info->type = 'organization' ?>

<div class="tab-content mt15">

    <?php if (isset($is_overview)) { ?>
        <?php echo '<div id="organization-info-tab">' ?>
    <?php } else { ?>
        <?php echo '<div role="tabpanel" class="tab-pane active" id="organization-info-tab">' ?>
    <?php } ?>

    <h3 class="mb-5"><?php echo app_lang('organization_info'); ?></h3>
    <hr>
    <div class="row">
        <div class="col-md-6">    <div class="form-group">
            
            <label for="organization_name" class="strong"><?php echo app_lang('organization_name'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "organization_name",
                    "name" => "company_name",
                    "value" => isset($model_info->company_name) ? $model_info->company_name : '',
                    "class" => "form-control company_name_input_section",

                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div>
    </div>
    <?php
    echo form_input(array(
        "id" => "unique_id",
        "name" => "unique_id",
        "value" => isset($model_info->unique_id) && !empty($model_info->unique_id) ? $model_info->unique_id : '',
        "class" => "",
        "autofocus" => true,
        'disabled' => true,
        'style' => "cursor: not-allowed",
        'hidden' => true
    ));
    ?>
    <div class="col-md-6">    
        <div class="form-group">
            <label for="legal_structure" class="strong"><?php echo app_lang('legal_structure'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                $list = array(
                    '' => '-',
                    'Sole Proprietor / Sole Trader' => 'Sole Proprietor / Sole Trader',
                    'Government Department' => 'Government Department',
                    'Unincorporated Body' => 'Unincorporated Body',
                    'Incorporated Association' => 'Incorporated Association',
                    'Partnership' => 'Partnership',
                    'Proprietary Company' => 'Proprietary Company',
                    'Public Company' => 'Public Company',
                );
                asort($list);
                echo form_dropdown(
                    "legal_structure",
                    $list,
                    isset($model_info->legal_structure) ? $model_info->legal_structure : '',
                    "class='select2' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "' title='" . app_lang('legal_structure') . "' required"
                );
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

        
        <div class="col-md-6"><div class="form-group">
            <label for="nature_of_business" class="strong"><?php echo app_lang('nature_of_business'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                $list = array(
                    "Automotive manufacturing",
                    "Electronics manufacturing",
                    "Food and beverage production",
                    "Clothing and apparel retail",
                    "Electronics retail",
                    "Grocery stores",
                    "Software development",
                    "IT consulting",
                    "Hardware manufacturing",
                    "Banking",
                    "Investment services",
                    "Insurance",
                    "Hospitals and healthcare providers",
                    "Pharmaceutical companies",
                    "Medical equipment manufacturing",
                    "Schools and universities",
                    "Educational technology (EdTech) companies",
                    "Training and development services",
                    "Property development",
                    "Real estate agencies",
                    "Property management",
                    "Hotels and resorts",
                    "Travel agencies",
                    "Restaurants and cafes",
                    "Airlines and aviation services",
                    "Shipping and logistics companies",
                    "Public transportation services",
                    "Renewable energy companies",
                    "Oil and gas exploration",
                    "Power generation and utilities",
                    "Broadcasting companies",
                    "Film and television production",
                    "Publishing",
                    "Legal services",
                    "Accounting and auditing",
                    "Consulting firms",
                    "Charities and foundations",
                    "Social advocacy organizations",
                    "Community service providers",
                    "Farming and agriculture",
                    "Agribusiness",
                    "Food processing",
                    "Telecommunication services",
                    "Internet service providers",
                    "Cable and satellite providers",
                    "Online retail platforms",
                    "E-commerce marketplaces",
                    "Digital goods and services providers",
                    "Gyms and fitness centers",
                    "Health and wellness products",
                    "Nutrition services",
                    "Car dealerships",
                    "Auto repair services",
                    "Automotive parts manufacturing"
                );
                asort($list);
                echo form_datalist(
                    'nature_of_business',
                    isset($model_info->nature_of_business) ? $model_info->nature_of_business : '',
                    $list,
                    "class='form-control company_name_input_section'"
                );
                ?>
            </div>
        </div></div>
        <div class="col-md-6">    <div class="form-group">
            <label for="registration_number" class="strong"><?php echo app_lang('registration_number'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "registration_number",
                    "name" => "reg_no",
                    "value" => isset($model_info->reg_no) ? $model_info->reg_no : '',
                    "class" => "form-control company_name_input_section",

                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div></div>
        <div class="col-md-6"><div class="form-group">
            <label for="industry" class="strong"><?php echo app_lang('industry'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                $list =
                array(
                    '' => '-',
                    "Technology" => array(
                        'Software Development' => 'Software Development',
                        'Hardware Manufacturing' => 'Hardware Manufacturing',
                        'Information Technology Services' => 'Information Technology Services'
                    ),
                    "Healthcare" => array(
                        'Hospitals' => 'Hospitals',
                        'Pharmaceuticals' => 'Pharmaceuticals',
                        'Medical Devices' => 'Medical Devices'
                    ),
                    "Finance" => array(
                        'Banking' => 'Banking',
                        'Insurance' => 'Insurance',
                        'Investment' => 'Investment'
                    ),
                    "Manufacturing" => array(
                        'Automotive' => 'Automotive',
                        'Consumer Goods' => 'Consumer Goods',
                        'Industrial Equipment' => 'Industrial Equipment'
                    ),
                    "Retail" => array(
                        'Department Stores' => 'Department Stores',
                        'Specialty Retailers' => 'Specialty Retailers',
                        'E-commerce' => 'E-commerce'
                    ),
                    "Telecommunications" => array(
                        'Mobile Network Operators' => 'Mobile Network Operators',
                        'Internet Service Providers' => 'Internet Service Providers'
                    ),
                    "Energy" => array(
                        'Oil and Gas' => 'Oil and Gas',
                        'Renewable Energy' => 'Renewable Energy',
                        'Utilities' => 'Utilities'
                    ),
                    "Agriculture" => array(
                        'Farming' => 'Farming',
                        'Agribusiness' => 'Agribusiness'
                    ),
                    "Transportation" => array(
                        'Airlines' => 'Airlines',
                        'Shipping' => 'Shipping',
                        'Logistics' => 'Logistics'
                    ),
                    "Entertainment and Media" => array(
                        'Film and Television' => 'Film and Television',
                        'Publishing' => 'Publishing',
                        'Broadcasting' => 'Broadcasting'
                    ),
                    "Hospitality" => array(
                        'Hotels and Resorts' => 'Hotels and Resorts',
                        'Restaurants' => 'Restaurants',
                        'Travel and Tourism' => 'Travel and Tourism'
                    ),
                    "Real Estate" => array(
                        'Property Development' => 'Property Development',
                        'Property Management' => 'Property Management'
                    ),
                    "Education" => array(
                        'Schools' => 'Schools',
                        'Colleges' => 'Colleges',
                        'Universities' => 'Universities'
                    ),
                    "Professional Services" => array(
                        'Legal Services' => 'Legal Services',
                        'Consulting' => 'Consulting',
                        'Accounting' => 'Accounting'
                    ),
                    "Health and Fitness" => array(
                        'Gyms and Fitness Centers' => 'Gyms and Fitness Centers',
                        'Health and Wellness Services' => 'Health and Wellness Services'
                    ),
                    "Nonprofit and Social Services" => array(
                        'Charities' => 'Charities',
                        'NGOs' => 'NGOs',
                        'Advocacy Organizations' => 'Advocacy Organizations'
                    ),
                    "Government" => array(
                        'Federal, State, and Local Government Agencies' => 'Federal, State, and Local Government Agencies',
                    ),
                    "Construction" => array(
                        'Residential Construction' => 'Residential Construction',
                        'Commercial Construction' => 'Commercial Construction'
                    ),
                    "Biotechnology and Pharmaceuticals" => array(
                        'Biotech Research and Development' => 'Biotech Research and Development',
                        'Pharmaceutical Manufacturing' => 'Pharmaceutical Manufacturing'
                    ),
                    "Environmental" => array(
                        'Conservation Organizations' => 'Conservation Organizations',
                        'Environmental Consulting' => 'Environmental Consulting'
                    ),
                );
asort($list);
echo form_dropdown(
    "industry",
    $list,
    isset($model_info->industry) ? $model_info->industry : '',
    "class='select2'"
);
?>
</div>
</div></div>


<div class="col-md-6">    <div class="form-group">
  
    <label for="website" class="strong"><?php echo app_lang('website'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "website",
            "name" => "website",
            "value" => isset($model_info->website) ? $model_info->website : '',
            "class" => "form-control company_name_input_section",

        ));
        ?>
    </div>
</div></div>
<div class="col-md-6">    <div class="form-group">
    <label for="num_emp" class="strong"><?php echo app_lang('number_of_employees'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "num_emp",
            "name" => "num_emp",
            "value" => isset($model_info->num_emp) ? $model_info->num_emp : '',
            "class" => "form-control company_name_input_section",

            "type" => 'number'
        ));
        ?>
    </div>
</div></div>

<div class="col-md-6">    <div class="form-group">
    <label for="visa_type" class="strong visa_type_section"><?php echo app_lang('visa_sponsorship_type'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(
            'visa_type',
            isset($model_info->visa_type) ? $model_info->visa_type : '',
            "class='form-control company_name_input_section validate-hidden' id='visa_type'"
        );
        ?>
    </div>
</div></div>
<div class="col-md-6">    <div class="form-group" id="visa_expiry_container">
    <label for="visa_expiry" class="strong visa_expiry_section"><?php echo "SBS " . app_lang('expiry'); ?></label>
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
</div></div></div>
</div>


<div <?php if (!isset($is_overview)) {
    echo 'role="tabpanel" class="tab-pane active"';
} ?> id="student-profile-tab">
<h3 class="mb-5"><?php echo app_lang('contact_person'); ?></h3>
<hr>
<div class="row">
    <div class="col-md-6">        <div class="form-group">
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
    <div class="col-md-6">        <div class="form-group">
        <label for="last_name" class="strong last_name_section"><?php echo app_lang('last_name'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                "value" => isset($model_info->last_name) ? $model_info->last_name : '',
                "class" => "form-control company_name_input_section",

                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ));
            ?>
        </div>
    </div></div>
    <div class="col-md-6">        <div class="form-group">
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
    </div></div>

    
    <div class="col-md-6">        <div class="form-group">
        <label for="phone" class="strong phone_section"><?php echo app_lang('contact_number'); ?></label>
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
    <div class="col-md-6"></div>
    <div class="col-md-6"></div>
</div>


<?php if (isset($is_overview)) { ?>
    <?php echo '<div id="address-tab">' ?>
<?php } else { ?>
    <?php echo '<div role="tabpanel" class="tab-pane" id="address-tab">' ?>
<?php } ?>
<h3 class="mb-5"><?php echo app_lang('address'); ?></h3>
<hr>
<div class="row">
    <div class="col-md-6">        <div class="form-group">
        <label for="address" class="strong"><?php echo app_lang('address'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "address",
                "name" => "address",
                "value" => $model_info->address ? $model_info->address : "",
                "class" => "form-control",
            ));
            ?>

        </div>
    </div></div>
    <div class="col-md-6">        <div class="form-group">
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
    <div class="col-md-6">        <div class="form-group">
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


    
    <div class="col-md-6">        <div class="form-group">
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
    <div class="col-md-6">        <div class="form-group">
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
                isset($model_info->country) && !empty($model_info->country) ? $model_info->country : '',
                "class='form-control select2' id='select-country-code'"
            );
            ?>
        </div>
    </div>
    <?php echo '</div>' ?></div>
    <div class="col-md-6"></div>

</div>


        <!-- <?php if (isset($is_overview)) { ?>
            <?php echo '<div id="financial-standing-tab">' ?>
        <?php } else { ?>
            <?php echo '<div role="tabpanel" class="tab-pane" id="financial-standing-tab">' ?>
        <?php } ?>

            <h3 class="mb-5"><?php echo app_lang('financial_and_human_resource_overview'); ?></h3>
            <div class="form-group">
                <div class="row">
                    <label for="annual_rev" class="<?php echo $label_column; ?> email_section"><?php echo app_lang('annual_revenue'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "annual_rev",
                            "name" => "annual_rev",
                            "value" => isset($model_info->annual_rev) ? $model_info->annual_rev : '',
                            "class" => "form-control company_name_input_section",
                            "placeholder" => app_lang('annual_revenue'),

                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="profitability" class="<?php echo $label_column; ?>"><?php echo app_lang('profitability'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        $list = array(
                            '' => '-',
                            'Profitable' => 'Profitable',
                            'Break-even' => 'Break-even',
                            'Loss-Making (Unprofitable)' => 'Loss-Making (Unprofitable)',
                            'Operating at Capacity' => 'Operating at Capacity',
                            'Seasonal Profitability' => 'Seasonal Profitability',
                            'Growth Profitability' => 'Growth Profitability',
                            'Stable Profitability' => 'Stable Profitability',
                            'Improving Profitability' => 'Improving Profitability',
                            'Declining Profitability' => 'Declining Profitability'
                        );
                        asort($list);
                        echo form_dropdown(
                            "profitability",
                            $list,
                            isset($model_info->profitability) ? $model_info->profitability : '',
                            "class='select2'"
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="avg_emp_salary" class="<?php echo $label_column; ?> email_section"><?php echo app_lang('avg_emp_salary'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "avg_emp_salary",
                            "name" => "avg_emp_salary",
                            "value" => isset($model_info->avg_emp_salary) ? $model_info->avg_emp_salary : '',
                            "class" => "form-control company_name_input_section",
                            "placeholder" => app_lang('avg_emp_salary'),

                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="emp_benefits_offered" class="<?php echo $label_column; ?>"><?php echo app_lang('emp_benefits_offered'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        $list = array(
                            'Health Insurance' => 'Health Insurance',
                            'Disability Insurance' => 'Disability Insurance',
                            'Flexible Spending Accounts (FSAs) or Health Savings Accounts (HSAs)' => 'Flexible Spending Accounts (FSAs) or Health Savings Accounts (HSAs)',
                            'Employee Assistance Programs (EAPs)' => 'Employee Assistance Programs (EAPs)',
                            'Professional Development' => 'Professional Development',
                            'Flexible Work Arrangements' => 'Flexible Work Arrangements',
                            'Wellness Programs' => 'Wellness Programs',
                            'Transportation Benefits' => 'Transportation Benefits',
                            'Stock Options or Equity Grants' => 'Stock Options or Equity Grants',
                            'Family and Parental Leave' => 'Family and Parental Leave',
                            'Legal Assistance' => 'Legal Assistance',
                            'Employee Discounts' => 'Employee Discounts',
                            'Social Activities' => 'Social Activities',
                            'Recognition and Awards' => 'Recognition and Awards',
                            'Sabbaticals' => 'Sabbaticals',
                            'Profit-Sharing' => 'Profit-Sharing',
                            'Bonuses' => 'Bonuses',
                            'Paid Leave' => 'Paid Leave',
                            'Retirement Plan' => 'Retirement Plan',
                        );
                        asort($list);
                        echo form_multiselect(
                            "emp_benefits_offered",
                            $list,
                            isset($model_info->avg_emp_salary) ? explode(',', $model_info->avg_emp_salary) : [],
                            "class='select2' placeholder='" . app_lang('emp_benefits_offered') . "'"
                        );
                        ?>
                    </div>
                </div>
            </div>
            <?php echo '</div>' ?> -->

        <!-- 
        <?php if (isset($is_overview)) { ?>
            <?php echo '<div id="visa-requirements-tab">' ?>
        <?php } else { ?>
            <?php echo '<div role="tabpanel" class="tab-pane" id="visa-requirements-tab">' ?>
        <?php } ?>
        <h3 class="mb-5"><?php echo app_lang('visa_requirements'); ?></h3>
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
                        isset($model_info->country_of_citizenship) && !empty($model_info->country_of_citizenship) ? $model_info->country_of_citizenship : '',
                        "class='form-control select2' id='select-country-of-passport'"
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
                    echo form_input(
                        'visa_type',
                        isset($model_info->visa_type) ? $model_info->visa_type : '',
                        "class='form-control company_name_input_section validate-hidden' id='visa_type' placeholder='" . app_lang('visa_type') . "'"
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
                        "placeholder" => app_lang('visa_expiry'),

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

                    ));
                    ?>
                </div>
            </div>
        </div>
        <?php echo '</div>' ?> -->


        <?php if (isset($is_overview)) { ?>
            <?php echo '<div id="additional-information-tab">' ?>
        <?php } else { ?>
            <?php echo '<div role="tabpanel" class="tab-pane" id="additional-information-tab">' ?>
        <?php } ?>
        <h3 class="mb-5"><?php echo app_lang('additional_information'); ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">        <div class="form-group">
              
                <label for="expected_timeline" class="strong"><?php echo app_lang('expected_timeline'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "expected_timeline",
                        "name" => "expected_timeline",
                        "value" => isset($model_info->expected_timeline) ? $model_info->expected_timeline : '',
                        "class" => "form-control company_name_input_section",

                    ));
                    ?>
                </div>
            </div></div>
            <div class="col-md-6">        <div class="form-group">
                <label for="qualification_required" class="strong"><?php echo app_lang('qualification_required'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "qualification_required",
                        "name" => "qualification_required",
                        "value" => isset($model_info->qualification_required) ? $model_info->qualification_required : '',
                        "class" => "form-control company_name_input_section",

                    ));
                    ?>
                </div>
            </div></div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="additional_info" class="strong"><?php echo app_lang('additional_info'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "additional_info",
                            "name" => "additional_info",
                            "value" => isset($model_info->additional_info) ? $model_info->additional_info : '',
                            "class" => "form-control company_name_input_section",

                        ));
                        ?>
                    </div>
                </div>
                <?php echo '</div>' ?></div>
                

            </div>



            <?php if (isset($is_overview)) { ?>
                <?php echo '<div id="internal-tab">' ?>
            <?php } else { ?>
                <?php echo '<div role="tabpanel" class="tab-pane" id="internal-tab">' ?>
            <?php } ?>
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
            <div class="col-md-6">        <div class="form-group">
                <div class="row">
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
                </div>
            </div></div>
            <div class="col-md-6">        <div class="form-group">
                <div class="row">
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
            </div>
        </div></div>

        
        


        <!-- <div class="form-group">
            <div class="row">
                <label for="assignee-manager" class="<?php echo $label_column; ?> assignee_manager_section"><?php echo app_lang('assignee'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "assignee-manager",
                        "name" => "assignee_manager",
                        "value" => isset($model_info->assignee_manager) ? $model_info->assignee_manager : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('assignee'),

                    ));
                    ?>
                </div>
            </div>
        </div> -->
        
        <div class="col-md-6">        <div class="form-group">
            
            <label for="client_labels" class="strong tag_name_section"><?php echo app_lang('tag_name'); ?></label>
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
        <div class="col-md-6">        <div class="form-group">
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
        <div class="col-md-6"></div>

    </div>

    <?php echo '</div>' ?>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#visa_expiry_container").show();
        if ($('#visa_type').val()) {
            $("#visa_expiry_container").show();
        }

        $('[data-bs-toggle="tooltip"]').tooltip();

        setDatePicker("#visa_expiry");

        $('#visa_type').select2({
            data: <?php echo $visa_type_dropdown; ?>
        }).on('change', function() {
            value = $(this).val();
            if (value == 'Standard Business Sponsorship') {
                    //$("#visa_expiry_container").show();
            } else {
                    //$("#visa_expiry_container").hide();
            }
        });

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
            $('#assignee').select2({
                data: <?php echo $team_members_dropdown; ?>
            });
            $('#assignee-manager').select2({
                data: <?php echo $team_members_dropdown; ?>
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