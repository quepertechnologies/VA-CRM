<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />
<input type="hidden" name="type" value="person" />
<input type="hidden" name="account_type" value="1" />
<input type="hidden" name="password" id="password" value="">

<?php $model_info->type = "person" ?>


<div class="tab-content mt15">

    <div <?php if (!isset($is_overview)) {
                echo 'role="tabpanel" class="tab-pane active"';
            } ?> id="student-profile-tab">
            
       <!--  <h3 class="mb-5"><?php echo app_lang('student_profile'); ?></h3>
        <hr> -->
        <div class="row">
         <div class="col-md-4">
          <div class="form-group">
                <label for="student-onshore" class="strong"><?php echo app_lang('student_onshore'); ?></label>
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
                        $model_info->student_onshore ? $model_info->student_onshore : '',
                        "class='form-control validate-hidden' id='student-onshore' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"
                    );
                    ?>
                </div>
            </div>
          </div>
          <div class="col-md-4">
                    <div class="form-group">
                <label for="first_name" class="strong"><?php echo app_lang('first_name'); ?></label>
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
        </div>
          </div>
          <div class="col-md-4">
                    <div class="form-group">
                <label for="last_name" class="strong"><?php echo app_lang('last_name'); ?></label>
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
        </div>
           </div>
        </div>
        <div class=" form-group hide">
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "unique_id",
                        "name" => "unique_id",
                        "value" => isset($model_info->unique_id) && !empty($model_info->unique_id) ? $model_info->unique_id : '',
                        "class" => "form-control form-control company_name_input_section",

                        'readonly' => true,
                        'style' => "cursor: not-allowed"
                    ));
                    ?>
                </div>
        </div>




      <div class="row">
        <div class="col-md-4">
                    <div class="form-group">
                <label for="email" class="strong"><?php echo app_lang('email'); ?></label>
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
        <div class="col-md-4">
                    <div class="form-group">
                <label for="phone" class="strong">Country Code</label>
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
                        asort($list);
                        echo form_dropdown(
                            'phone_code',
                            $list,
                            isset($model_info->phone_code) && !empty($model_info->phone_code) ? $model_info->phone_code : '',
                            "class='select2 form-control' id='select-phone-code'"
                        );
                        ?>
                    </div>

                </div>
        </div>
        </div>
        <div class="col-md-4">
                    <div class="form-group">
            <div class="row">
                <label for="phone" class="strong">Phone #</label>
                <div class="<?php echo $field_column; ?>">
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
            </div>
        </div>
        </div>
        </div>




     <div class=" row">
        <div class="col-md-4">        
            <div class="form-group">
                <label for="nationality" class="strong"><?php echo app_lang('nationality'); ?></label>
                <div class=" <?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    if (empty($countries)) {
                        $countries = [];
                    } else {
                        $countries = json_decode($countries);
                    }
                    $list = [];

                    foreach ($countries as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    $list[''] = '-';
                    ksort($list);
                    echo form_dropdown(
                        'nationality',
                        $list,
                        isset($model_info->nationality) && !empty($model_info->nationality) ? $model_info->nationality : '',
                        "class='select2 form-control' id='nationality'"
                    );
                    ?>
                </div>
            </div></div>
        <div class="col-md-4">        
            <div class="form-group">
                <label for="visa_type" class="strong"><?php echo app_lang('visa_type'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "visa_type",
                        "name" => "visa_type",
                        "value" => isset($model_info->visa_type) ? $model_info->visa_type : '',
                        "class" => "form-control validate-hidden",
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                    <span class='text-danger' id='visa_type_msg_cont'></span>
                </div>
        </div></div>
        <div class="col-md-4">
         <div class="form-group" id="visa_2_field">
                <label for="visa_2" class="strong">Visa Applied For</label>
                <div class=" <?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "visa_2",
                        "name" => "visa_2",
                        "value" => isset($model_info->visa_2) ? $model_info->visa_2 : '',
                        "class" => "form-control select2",
                        "required" => "required"
                    ));
                    ?>
                </div>
        </div>
        </div>

        </div>




       <div class="row">
        <div class="col-md-4">        <div class="form-group">
           
                <label for="visa_expiry" class="strong"><?php echo app_lang('visa_expiry'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "visa_expiry",
                        "name" => "visa_expiry",
                        "value" => isset($model_info->visa_expiry) ? $model_info->visa_expiry : '',
                        "class" => "form-control company_name_input_section",
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                    <span class='text-danger' id='visa_expiry_msg_cont'></span>
                </div>
            </div></div>
        <div class="col-md-4">        
            <div class="form-group">
                <label for="preferred_language" class="strong"><?php echo app_lang('preferred_language'); ?></label>
                <div class=" <?php echo $field_column; ?>">
                    <?php
                    $languages = isset($languages_dropdown) ? $languages_dropdown : '';
                    if (empty($languages)) {
                        $languages = [];
                    } else {
                        $languages = json_decode($languages);
                    }
                    $list = [];

                    foreach ($languages as $language) {
                        $list[$language->value] = $language->value;
                    }

                    $list[''] = '-';
                    ksort($list);
                    echo form_dropdown(
                        'preferred_language',
                        $list,
                        isset($model_info->preferred_language) && !empty($model_info->preferred_language) ? $model_info->preferred_language : 'English',
                        "class='select2 form-control' id='preferred_language'"
                    );
                    ?>
                </div>
        </div>
</div>
        <div class="col-md-4">
                    <div class="form-group">
                <label for="counsellor_contact" class="strong">Counsellor Phone Code</label>
                <div class=" <?php echo $field_column; ?>">
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
                        'co_phone_code',
                        $list,
                        isset($model_info->co_phone_code) && !empty($model_info->co_phone_code) ? $model_info->co_phone_code : '',
                        "class='select2 form-control' id='select-co-phone-code'"
                    );
                    ?>
                </div>
        </div>
        </div>

        </div>



 <div class=" row">
    <div class="col-md-4">        <div class="form-group">
           
                <label for="counsellor_contact" class="strong"><?php echo app_lang('counsellor_contact'); ?></label>
                <div class=" <?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "co_phone",
                        "name" => "co_phone",
                        "value" => isset($model_info->co_phone) ? $model_info->co_phone : '',
                        "class" => "form-control company_name_input_section",
                        "type" => 'number',
                    ));
                    ?>
                </div>
            </div></div>
    <div class="col-md-4">        <div class="form-group">
                <label for="passport_number" class="strong"><?php echo app_lang('passport_number'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "passport_number",
                        "name" => "passport_number",
                        "value" => isset($model_info->passport_number) ? $model_info->passport_number : '',
                        "class" => "form-control company_name_input_section",

                    ));
                    ?>
                </div>
        </div></div>
    <div class="col-md-4"></div>

        </div>



        <!-- <div class="form-group">
            <div class="row">
                <label for="visa_phase" "><?php echo app_lang('visa_phase'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list = array(
                        '' => '-',
                        "Counselling" => "Counselling",
                        "Lodgment" => "Lodgment",
                        "Offer" => "Offer",
                        "Acceptance" => "Acceptance",
                        "Visa" => "Visa",
                        "Commencement" => "Commencement"
                    );
                    ksort($list);
                    echo form_dropdown(
                        'visa_phase',
                        $list,
                        isset($model_info->visa_phase) ? $model_info->visa_phase : '',
                        "class='select2 form-control' id='visa_phase'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="visa_sub_phase" "><?php echo app_lang('visa_sub_phase'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_datalist(
                        'visa_sub_phase',
                        isset($model_info->visa_sub_phase) ? $model_info->visa_sub_phase : '',
                        [],
                        "class='' id='visa_sub_phase' placeholder='" . app_lang('visa_sub_phase') . "'"
                    );
                    ?>
                </div>
            </div>
        </div> -->
    </div>

    <div <?php if (!isset($is_overview)) {
                echo 'role="tabpanel" class="tab-pane"';
            } ?> id="academic-achievement-tab">
        <h3 class="mb-5"><?php echo app_lang('academic_achievement'); ?></h3>
        <hr>

<div class="row">
    <div class="col-md-4">        
        <div class="form-group">
                <label for="lvl_of_edu" class="strong"><?php echo app_lang('lvl_of_edu'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list =
                        array(
                            '' => '-',
                            'High School' => 'High School',
                            'Language Pathway' => 'Language Pathway',
                            'Undergraduate - Foundation' => 'Undergraduate - Foundation',
                            'Undergraduate - Certificate' => 'Undergraduate - Certificate',
                            'Undergraduate - Diploma' => 'Undergraduate - Diploma',
                            'Undergraduate - Associate Degree' => 'Undergraduate - Associate Degree',
                            'Undergraduate - Bachelor' => 'Undergraduate - Bachelor',
                            'Postgraduate - Certificate' => 'Postgraduate - Certificate',
                            'Postgraduate - Diploma' => 'Postgraduate - Diploma',
                            'Masters' => 'Masters',
                            'Doctorate / PHD' => 'Doctorate / PHD'
                        );

                    ksort($list);
                    echo form_dropdown(
                        'lvl_of_edu',
                        $list,
                        isset($model_info->lvl_of_edu) ? $model_info->lvl_of_edu : '',
                        "class='form-control select2' id='lvl_of_edu'"
                    );
                    ?>
                    <small><?php echo app_lang('lvl_of_edu_caption'); ?></small>
                </div>
            </div></div>
    <div class="col-md-4">        
        <div class=" form-group">
                <label for="country_of_study" class="strong"><?php echo app_lang('country_of_study'); ?></label>
                <div class=" <?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    if (empty($countries)) {
                        $countries = [];
                    } else {
                        $countries = json_decode($countries);
                    }
                    $list = [];

                    foreach ($countries as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    $list[''] = '-';
                    ksort($list);
                    echo form_dropdown(
                        'country_of_citizenship',
                        $list,
                        isset($model_info->country_of_citizenship) && !empty($model_info->country_of_citizenship) ? $model_info->country_of_citizenship : '',
                        "class='select2 form-control' id='country_of_study'"
                    );
                    ?>
                    <small><?php echo app_lang('country_of_study_caption'); ?></small>
                </div>
        </div></div>
    <div class="col-md-4">        <div class="form-group">
                <label for="result_of_study" class="strong"><?php echo app_lang('result_of_study'); ?></label>
                <div class=" <?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "result_of_study",
                        "name" => "result_of_study",
                        "value" => isset($model_info->result_of_study) ? $model_info->result_of_study : '',
                        "class" => "form-control company_name_input_section",

                    ));
                    ?>
                    <small><?php echo app_lang('result_of_study_caption'); ?></small>
                </div>
        </div></div>

        </div>







        <div <?php if (!isset($is_overview)) {
                    echo 'role="tabpanel" class="tab-pane"';
                } ?> id="study-preference-tab">
            <h3 class="mb-5"><?php echo app_lang('study_preference'); ?></h3>
            <hr>

 <div class="row">
    <div class="col-md-4">  <div class="form-group">
               
                    <label for="area_of_study" class="strong"><?php echo app_lang('area_of_study'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $list =
                            array(
                                '' => '-',
                                'Natural Sciences' => array(
                                    'Physics' => 'Physics',
                                    'Chemistry' => 'Chemistry',
                                    'Biology' => 'Biology',
                                    'Earth Sciences (Geology, Environmental Science)' => 'Earth Sciences (Geology, Environmental Science)'
                                ),
                                'Mathematics and Statistics' => array(
                                    'Mathematics' => 'Mathematics',
                                    'Statistics' => 'Statistics'
                                ),
                                'Engineering and Technology' => array(
                                    'Civil Engineering' => 'Civil Engineering',
                                    'Mechanical Engineering' => 'Mechanical Engineering',
                                    'Electrical Engineering' => 'Electrical Engineering',
                                    'Computer Science and Engineering' => 'Computer Science and Engineering',
                                    'Information Technology' => 'Information Technology'
                                ),
                                'Social Sciences' => array(
                                    'Sociology' => 'Sociology',
                                    'Psychology' => 'Psychology',
                                    'Anthropology' => 'Anthropology',
                                    'Political Science' => 'Political Science',
                                    'Economics' => 'Economics'
                                ),
                                'Humanities' => array(
                                    'Literature' => 'Literature',
                                    'Philosophy' => 'Philosophy',
                                    'History' => 'History',
                                    'Linguistics' => 'Linguistics',
                                    'Cultural Studies' => 'Cultural Studies'
                                ),
                                'Fine Arts and Performing Arts' => array(
                                    'Visual Arts' => 'Visual Arts',
                                    'Music' => 'Music',
                                    'Theater' => 'Theater',
                                    'Dance' => 'Dance'
                                ),
                                'Health Sciences' => array(
                                    'Medicine' => 'Medicine',
                                    'Nursing' => 'Nursing',
                                    'Public Health' => 'Public Health',
                                    'Pharmacy' => 'Pharmacy'
                                ),
                                'Business and Management' => array(
                                    'Business Administration' => 'Business Administration',
                                    'Marketing' => 'Marketing',
                                    'Finance' => 'Finance',
                                    'Human Resources' => 'Human Resources'
                                ),
                                'Education' => array(
                                    'Elementary Education' => 'Elementary Education',
                                    'Secondary Education' => 'Secondary Education',
                                    'Higher Education Administration' => 'Higher Education Administration'
                                ),
                                'Communication and Media Studies' => array(
                                    'Journalism' => 'Journalism',
                                    'Public Relations' => 'Public Relations',
                                    'Film Studies' => 'Film Studies',
                                    'Mass Communication' => 'Mass Communication'
                                ),
                                'Computer Science and Information Technology' => array(
                                    'Computer Science' => 'Computer Science',
                                    'Information Systems' => 'Information Systems',
                                    'Cybersecurity' => 'Cybersecurity'
                                ),
                                'Agriculture and Environmental Sciences' => array(
                                    'Agriculture' => 'Agriculture',
                                    'Environmental Science' => 'Environmental Science',
                                    'Forestry' => 'Forestry'
                                ),
                                'Law and Legal Studies' => array(
                                    'Law' => 'Law',
                                    'Legal Studies' => 'Legal Studies'
                                ),
                                'Physical Education and Sports Science' => array(
                                    'Physical Education' => 'Physical Education',
                                    'Sports Science' => 'Sports Science'
                                ),
                                'Architecture and Design' => array(
                                    'Architecture' => 'Architecture',
                                    'Interior Design' => 'Interior Design',
                                    'Graphic Design' => 'Graphic Design'
                                ),
                                'Languages and Linguistics' => array(
                                    'Linguistics' => 'Linguistics',
                                    'Modern Languages' => 'Modern Languages',
                                    'Comparative Literature' => 'Comparative Literature'
                                ),
                                'Public Administration and Policy' => array(
                                    'Public Administration' => 'Public Administration',
                                    'Policy Studies' => 'Policy Studies'
                                ),
                                'Social Work and Human Services' => array(
                                    'Social Work' => 'Social Work',
                                    'Human Services' => 'Human Services'
                                ),
                                'Religious Studies and Theology' => array(
                                    'Religious Studies' => 'Religious Studies',
                                    'Theology' => 'Theology'
                                ),
                                'Interdisciplinary Studies' => array(
                                    'Environmental Studies' => 'Environmental Studies',
                                    'Gender Studies' => 'Gender Studies',
                                    'International Studies' => 'International Studies'
                                ),
                                'Library and Information Science' => array(
                                    'Library Science' => 'Library Science',
                                    'Information Management' => 'Information Management'
                                ),
                                'Psychology and Counseling' => array(
                                    'Clinical Psychology' => 'Clinical Psychology',
                                    'Counseling Psychology' => 'Counseling Psychology',
                                    'School Psychology' => 'School Psychology'
                                ),
                                'Tourism and Hospitality Management' => array(
                                    'Hotel Management' => 'Hotel Management',
                                    'Tourism Management' => 'Tourism Management',
                                    'Event Management' => 'Event Management'
                                ),
                                'Social Work' => array(
                                    'Clinical Social Work' => 'Clinical Social Work',
                                    'Community Social Work' => 'Community Social Work'
                                ),
                                'Natural Resource Management' => array(
                                    'Forestry' => 'Forestry',
                                    'Wildlife Management' => 'Wildlife Management',
                                    'Conservation Biology' => 'Conservation Biology'
                                ),
                                'Criminal Justice and Criminology' => array(
                                    'Criminal Justice' => 'Criminal Justice',
                                    'Criminology' => 'Criminology'
                                ),
                                'Military Science' => array(
                                    'Military History' => 'Military History',
                                    'Strategic Studies' => 'Strategic Studies'
                                ),
                                'Philanthropy and Nonprofit Management' => array(
                                    'Nonprofit Administration' => 'Nonprofit Administration',
                                    'Fundraising Management' => 'Fundraising Management'
                                ),
                                'Public Health' => array(
                                    'Epidemiology' => 'Epidemiology',
                                    'Health Policy' => 'Health Policy',
                                    'Global Health' => 'Global Health'
                                ),
                                'Artificial Intelligence and Machine Learning' => array(
                                    'Artificial Intelligence' => 'Artificial Intelligence',
                                    'Machine Learning' => 'Machine Learning',
                                    'Data Science' => 'Data Science'
                                ),
                                'Geography and GIS (Geographic Information Systems)' => array(
                                    'Physical Geography' => 'Physical Geography',
                                    'Human Geography' => 'Human Geography',
                                    'GIS Analysis' => 'GIS Analysis'
                                ),
                                'Bio-Informatics' => array(
                                    'Computational Biology' => 'Computational Biology',
                                    'Genomics' => 'Genomics'
                                ),
                                'Astrophysics and Astronomy' => array(
                                    'Astrophysics' => 'Astrophysics',
                                    'Astronomy' => 'Astronomy'
                                ),
                                'Paleontology' => array(
                                    'Vertebrate Paleontology' => 'Vertebrate Paleontology',
                                    'Invertebrate Paleontology' => 'Invertebrate Paleontology'
                                ),
                                'Fashion Design and Merchandising' => array(
                                    'Fashion Design' => 'Fashion Design',
                                    'Fashion Merchandising' => 'Fashion Merchandising'
                                ),
                                'Food Science and Nutrition' => array(
                                    'Food Science' => 'Food Science',
                                    'Nutrition' => 'Nutrition'
                                ),
                                'Sustainability Studies' => array(
                                    'Sustainable Development' => 'Sustainable Development',
                                    'Environmental Sustainability' => 'Environmental Sustainability'
                                ),
                                'Neuroscience' => array(
                                    'Cognitive Neuroscience' => 'Cognitive Neuroscience',
                                    'Behavioral Neuroscience' => 'Behavioral Neuroscience'
                                ),
                                'Emergency Management' => array(
                                    'Disaster Preparedness' => 'Disaster Preparedness',
                                    'Crisis Management' => 'Crisis Management'
                                ),
                                'Urban Planning and Design' => array(
                                    'Urban Planning' => 'Urban Planning',
                                    'Urban Design' => 'Urban Design'
                                ),
                            );
                        ksort($list);
                        echo form_dropdown(
                            'area_of_study',
                            $list,
                            isset($model_info->area_of_study) ? $model_info->area_of_study : '',
                            "class='select2 form-control' id='area_of_study'"
                        );
                        ?>
                    </div>
                </div>
            </div>

    <div class="col-md-4">   <div class="form-group">
                    <label for="intended_course_level" class="strong"><?php echo app_lang('intended_course_level'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $list = array(
                            '' => '-',
                            'Advanced Diploma' => 'Advanced Diploma',
                            'All course levels' => 'All course levels',
                            'Associate Degree' => 'Associate Degree',
                            'Attestation d’études collégiales' => 'Attestation d’études collégiales',
                            'Attestation of College Studies' => 'Attestation of College Studies',
                            'Bachelor Degree' => 'Bachelor Degree',
                            'Bachelor Honours Degree' => 'Bachelor Honours Degree',
                            'Certificate 1 to 4' => 'Certificate 1 to 4',
                            'Diploma' => 'Diploma',
                            'Diplôme d’études professionnelles' => 'Diplôme d’études professionnelles',
                            'Doctorate (PhD)' => 'Doctorate (PhD)',
                            'Dual Degree' => 'Dual Degree',
                            'English Pathway Program' => 'English Pathway Program',
                            'Foundation Program' => 'Foundation Program',
                            'Graduate Pathway' => 'Graduate Pathway',
                            'High School Program' => 'High School Program',
                            'Integrated Masters' => 'Integrated Masters',
                            'Language Pathway' => 'Language Pathway',
                            'Masters Coursework' => 'Masters Coursework',
                            'Masters Research' => 'Masters Research',
                            'Middle School (Grades 6 to 8)' => 'Middle School (Grades 6 to 8)',
                            'Pathway Program' => 'Pathway Program',
                            'Post Graduate Certificate' => 'Post Graduate Certificate',
                            'Post Graduate Diploma' => 'Post Graduate Diploma',
                            'Pre-Masters' => 'Pre-Masters',
                            'Pre-Qualifying Program' => 'Pre-Qualifying Program',
                            'Primary School (Elementary)' => 'Primary School (Elementary)',
                            'Professional Programs' => 'Professional Programs',
                            'Short Courses' => 'Short Courses',
                            'Study Abroad' => 'Study Abroad',
                            'Summer School' => 'Summer School'
                        );
                        ksort($list);
                        echo form_dropdown(
                            'intended_course_level',
                            $list,
                            isset($model_info->intended_course_level) ? $model_info->intended_course_level : '',
                            "class='select2 form-control' id='intended_course_level'"
                        );
                        ?>
                    </div>
            </div>
</div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="course_fields_comments" class="strong"><?php echo app_lang('course_fields_comments'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "course_fields_comments",
                            "name" => "course_fields_comments",
                            "value" => isset($model_info->course_fields_comments) ? $model_info->course_fields_comments : '',
                            "class" => "form-control company_name_input_section",

                        ));
                        ?>
                        <small><?php echo app_lang('course_fields_comments_caption'); ?></small>
                    </div>
            </div></div>
          
         

<div class="row">
    <div class="col-md-4">            <div class="form-group">
                
                    <label for="career_paths" class="strong"><?php echo app_lang('career_paths'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_textarea(array(
                            "id" => "career_paths",
                            "name" => "career_paths",
                            "value" => isset($model_info->career_paths) ? $model_info->career_paths : '',
                            "class" => "form-control company_name_input_section",

                        ));
                        ?>
                        <small><?php echo app_lang('career_paths_caption'); ?></small>
                    </div>
                </div></div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="intended_institutions" class="strong"><?php echo app_lang('intended_institutions'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_textarea(array(
                            "id" => "intended_institutions",
                            "name" => "intended_institutions",
                            "value" => isset($model_info->intended_institutions) ? $model_info->intended_institutions : '',
                            "class" => "form-control company_name_input_section",

                        ));
                        ?>
                        <small><?php echo app_lang('intended_institutions_caption'); ?></small>
                    </div>
            </div></div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="intended_intake_quarter" class="strong"><?php echo app_lang('intended_intake_quarter'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $list = array(
                            '' => '-',
                            'Q1: Jan, Feb, Mar' => 'Q1: Jan, Feb, Mar',
                            'Q2: Apr, May, Jun' => 'Q2: Apr, May, Jun',
                            'Q3: Jul, Aug, Sep' => 'Q3: Jul, Aug, Sep',
                            'Q4: Oct, Nov, Dec' => 'Q4: Oct, Nov, Dec'
                        );

                        echo form_dropdown(
                            'intended_intake_quarter',
                            $list,
                            isset($model_info->intended_intake_quarter) ? $model_info->intended_intake_quarter : '',
                            "class='select2 form-control' id='intended_intake_quarter'"
                        );
                        ?>
                        <small><?php echo app_lang('intended_intake_quarter_caption'); ?></small>
                    </div>
            </div></div>

            </div>




 <div class="row">
    <div class="col-md-4">            <div class="form-group">
               
                    <label for="intended_intake_year" class="strong"><?php echo app_lang('intended_intake_year'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $currentYear = date("Y");
                        $list = array_combine($r = range($currentYear, $currentYear + 10), $r);
                        $list = ['' => '-'] + $list;

                        echo form_dropdown(
                            'intended_intake_year',
                            $list,
                            isset($model_info->intended_intake_year) ? $model_info->intended_intake_year : '',
                            "class='select2 form-control' id='intended_intake_year'"
                        );
                        ?>
                    </div>
                </div></div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="intended_intake_comments" class="strong"><?php echo app_lang('intended_intake_comments'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_textarea(array(
                            "id" => "intended_intake_comments",
                            "name" => "intended_intake_comments",
                            "value" => isset($model_info->intended_intake_comments) ? $model_info->intended_intake_comments : '',
                            "class" => "form-control company_name_input_section",
                        ));
                        ?>
                        <small><?php echo app_lang('intended_intake_comments_caption'); ?></small>
                    </div>
            </div></div>
    <div class="col-md-4">   <div class="form-group">
                    <label for="funding_source" class="strong"><?php echo app_lang('funding_source'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $list = array(
                            "Institutional Scholarship",
                            "Government Grant",
                            "Corporate Scholarship",
                            "Federal Work-Study Program",
                            "Pell Grant",
                            "Direct Student Loan",
                            "State-sponsored Grant",
                            "Financial Aid from University",
                            "Internship Compensation",
                            "Co-op Program Stipend",
                            "Research Assistantship",
                            "Part-Time Job Income",
                            "GoFundMe Campaign",
                            "Kick-starter Campaign",
                            "Professional Association Scholarship",
                            "Community Organization Support",
                            "Nonprofit Organization Grant",
                            "Government Educational Program",
                            "GI Bill Benefits",
                            "Employer Tuition Assistance",
                            "Family Financial Support",
                            "Private Foundation Scholarship",
                            "Online Platform Funding",
                            "Local Business Sponsorship",
                            "Educational Loan",
                            "Tuition Reimbursement",
                            "Military Educational Benefits",
                            "Freelance Work Income",
                            "Crowdfunding on Patreon",
                            "Academic Department Grant",
                            "Minority Scholarship",
                            "Athletic Scholarship",
                            "Artistic Achievement Grant",
                            "STEM Scholarship",
                            "Travel Grant for Research",
                            "Merit-Based Scholarship",
                            "Need-Based Scholarship",
                            "Emergency Financial Aid",
                            "Alumni Association Grant"
                        );
                        asort($list);
                        echo form_datalist(
                            'funding_source',
                            isset($model_info->funding_source) && !empty($model_info->funding_source) ? $model_info->funding_source : '',
                            $list,
                            'id="funding_source" class="company_name_input_section form-control"'
                        );
                        ?>
                        <small><?php echo app_lang('funding_source_caption'); ?></small>
                    </div>
            </div></div>

            </div>



          <div class="row">
            <div class="col-md-4">            <div class="form-group">
                    <label for="intended_dest_1" class="strong"><?php echo app_lang('intended_dest_1'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                        if (empty($countries)) {
                            $countries = [];
                        } else {
                            $countries = json_decode($countries);
                        }
                        $list = [];

                        foreach ($countries as $country) {
                            $list[$country->nicename] = $country->nicename;
                        }

                        $list[""] = "-";
                        ksort($list);
                        echo form_dropdown(
                            'intended_dest_1',
                            $list,
                            isset($model_info->intended_dest_1) ? $model_info->intended_dest_1 : '',
                            "class='select2 form-control' id='intended_dest_1'"
                        );
                        ?>
                        <small><?php echo app_lang('intended_dest_1_caption'); ?></small>
                    </div>
                </div></div>
            <div class="col-md-4">            <div class="form-group">
                    <label for="intended_dest_2" class="strong"><?php echo app_lang('intended_dest_2'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                        if (empty($countries)) {
                            $countries = [];
                        } else {
                            $countries = json_decode($countries);
                        }
                        $list = [];

                        foreach ($countries as $country) {
                            $list[$country->nicename] = $country->nicename;
                        }

                        $list[""] = "-";
                        ksort($list);
                        echo form_dropdown(
                            'intended_dest_2',
                            $list,
                            isset($model_info->intended_dest_2) ? $model_info->intended_dest_2 : '',
                            "class='select2 form-control' id='intended_dest_2'"
                        );
                        ?>
                        <small><?php echo app_lang('optional'); ?></small>
                    </div>
            </div></div>
            <div class="col-md-4">            <div class="form-group">
                    <label for="intended_dest_3" class="strong"><?php echo app_lang('intended_dest_3'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                        if (empty($countries)) {
                            $countries = [];
                        } else {
                            $countries = json_decode($countries);
                        }
                        $list = [];

                        foreach ($countries as $country) {
                            $list[$country->nicename] = $country->nicename;
                        }

                        $list[""] = "-";
                        ksort($list);
                        echo form_dropdown(
                            'intended_dest_3',
                            $list,
                            isset($model_info->intended_dest_3) ? $model_info->intended_dest_3 : '',
                            "class='select2 form-control' id='intended_dest_3'"
                        );
                        ?>
                        <small><?php echo app_lang('optional'); ?></small>
                    </div>
            </div></div>

            </div>

<div class="row">
    <div class="col-md-4">            <div class="form-group">
                
                    <label for="intended_dest_comments" class="strong"><?php echo app_lang('intended_dest_comments'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_textarea(array(
                            "id" => "intended_dest_comments",
                            "name" => "intended_dest_comments",
                            "value" => isset($model_info->intended_dest_comments) ? $model_info->intended_dest_comments : '',
                            "class" => "form-control company_name_input_section",

                        ));
                        ?>
                        <small><?php echo app_lang('intended_dest_comments_caption'); ?></small>
                    </div>
                </div></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>

            </div>
        </div>

        <div <?php if (!isset($is_overview)) {
                    echo 'role="tabpanel" class="tab-pane"';
                } ?> id="personal-info-tab">
            <h3 class="mb-5"><?php echo app_lang('personal_info'); ?></h3>
            <hr>
            <div class="row">
                <div class="col-md-4">            <div class="form-group">
                    <label for="address" class="strong"><?php echo app_lang('address'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_textarea(array(
                            "id" => "address",
                            "name" => "address",
                            "value" => $model_info->address ? $model_info->address : "",
                            "class" => "form-control ",
                        ));
                        ?>

                    </div>
                </div></div>
                <div class="col-md-4">            
                    <div class="form-group">
                    <label for="city" class="strong"><?php echo app_lang('city'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "city",
                            "name" => "city",
                            "value" => $model_info->city,
                            "class" => "form-control ",
                        ));
                        ?>
                    </div>
            </div></div>
                <div class="col-md-4">
            <div class="form-group">
                    <label for="state" class="strong"><?php echo app_lang('state'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "state",
                            "name" => "state",
                            "value" => $model_info->state,
                            "class" => "form-control ",
                        ));
                        ?>
                    </div>
            </div></div>

            </div>
 <div class="row">
    <div class="col-md-4">      <div class="form-group">
               
                    <label for="zip" class="strong"><?php echo app_lang('zip'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "zip",
                            "name" => "zip",
                            "value" => $model_info->zip,
                            "class" => "form-control ",
                        ));
                        ?>
                    </div>
                </div></div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="country" class="strong"><?php echo app_lang('country'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
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

                        $list[""] = "-";
                        ksort($list);
                        echo form_dropdown(
                            'country',
                            $country_codes,
                            isset($model_info->country) ? $model_info->country : 'Australia',
                            "class='select2 form-control' id='country'"
                        );
                        ?>
                    </div>
            </div></div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="date_of_birth" class="strong"><?php echo app_lang('date_of_birth'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "date_of_birth",
                            "name" => "date_of_birth",
                            "value" => isset($model_info->date_of_birth) ? $model_info->date_of_birth : '',
                            "class" => "form-control company_name_input_section",
                        ));
                        ?>
                    </div>
            </div></div>
      
            </div>

 <div class="row">
    <div class="col-md-4">            <div class="form-group">
               
                    <label for="marital_status" class="strong"><?php echo app_lang('marital_status'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
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
                            "id='marriage_status' class='select2 form-control'"
                        );
                        ?>
                    </div>
                </div></div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="timezone" class="strong"><?php echo app_lang('timezone'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $data = isset($timezone_dropdown) ? $timezone_dropdown : [];
                        $list = [];
                        foreach ($data as $item) {
                            $list[$item['text']] = $item['text'];
                        }
                        echo form_dropdown(
                            "timezone",
                            $list,
                            isset($model_info->timezone) ? $model_info->timezone : '',
                            "id='timezone' class='select2 form-control'"
                        );
                        ?>
                    </div>
            </div></div>
    <div class="col-md-4">            <div class="form-group">
                    <label for="currency" class="strong"><?php echo app_lang('currency'); ?></label>
                    <div class=" <?php echo $field_column; ?>">
                        <?php
                        $data = isset($currency_dropdown) ? $currency_dropdown : [];
                        $list = [];
                        foreach ($data as $item) {
                            $list[$item['text']] = $item['text'];
                        }
                        echo form_dropdown(
                            "currency",
                            $list,
                            isset($model_info->currency) ? $model_info->currency : '',
                            "id='currency' class='select2 form-control'"
                        );
                        ?>
                    </div>
            </div></div>

            </div>


        </div>

        <div <?php if (!isset($is_overview)) {
                    echo 'role="tabpanel" class="tab-pane"';
                } ?> id="internal-tab">
            <h3 class="mb-5"><?php echo app_lang('internal'); ?></h3>
            <hr>
            <div class="row">
                <div class="col-md-4"><?php if ($model_info->id) { ?>
                <div class="form-group">
                        <label for="consultancy_type" class="strong"><?php echo app_lang('consultancy_type'); ?>
                            <span class=" help" data-container="body" data-bs-toggle="tooltip" title="Change the consultancy type of the client"><i data-feather="help-circle" class="icon-16"></i></span>
                        </label>
                        <div class="<?php echo $field_column; ?>">
                            <?php
                            echo form_input(array(
                                "id" => "consultancy_type",
                                "name" => "consultancy_type",
                                "value" => isset($model_info->account_type) ? $model_info->account_type : '',
                                "class" => "form-control ",
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required")
                            ));
                            ?>
                        </div>
                </div>
            <?php } ?></div>
                <div class="col-md-4">            <div class="form-group">
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
                <div class="col-md-4">            

            <div class="form-group">
                    <label for="assignee" class="strong"><?php echo app_lang('assignee'); ?>
                        <span class=" help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
                    </label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "created_by",
                            "name" => "assignee",
                            "value" => isset($model_info->assignee) ? $model_info->assignee : '',
                            "class" => "form-control select2",
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
            </div>
        </div>
    </div>
      
      <div class="row">
        <div class="col-md-4">            <?php if ($login_user->user_type === "staff") { ?>
                <div class="form-group">
                    
                        <label for="groups" class="strong"><?php echo app_lang('client_groups'); ?></label>
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
              
            <?php } ?></div>
        <div class="col-md-4">         <div class="form-group">
                    <label for="parent_id" class="strong"><?php echo app_lang('attach_to_organization'); ?></label>
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
        <div class="col-md-4">            <div class="form-group">
                    <label for="partner_id" class="strong"><?php echo app_lang('subagent'); ?></label>
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

              </div>
            <!-- <div class="form-group">
                <div class="row">
                    <label for="assignee-manager" class="strong"><?php echo app_lang('assignee'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "created_by_manager",
                            "name" => "assignee_manager",
                            "value" => isset($model_info->assignee_manager) ? $model_info->assignee_manager : '',
                            "class" => "form-control select2",
                            "placeholder" => app_lang('assignee'),
                        ));
                        ?>
                    </div>
                </div>
            </div> -->
   
<div class="row">
    <div class="col-md-4">            <div class="form-group">
                
                    <label for="source" class="strong"><?php echo app_lang('source'); ?></label>
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
    <div class="col-md-4">            <div class="form-group">
                    <label for="tag_name" class="strong"><?php echo app_lang('tag_name'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "client_labels",
                            "name" => "tag_name",
                            "value" => isset($model_info->tag_name) ? $model_info->tag_name : '',
                            "class" => "form-control",
                        ));
                        ?>
                    </div>
            </div></div>
    <div class="col-md-4"></div>

            </div>

        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#visa_2_field").hide();
            $("#student-onshore, #select-phone-code, #nationality, #select-co-phone-code, #preferred_language, #currency, #timezone, #marriage_status, #country, #intended_dest_3, #intended_dest_2, #intended_dest_1, #intended_intake_year, #intended_intake_quarter, #intended_course_level, #area_of_study, #country_of_study, #lvl_of_edu").select2();

            setDatePicker("#visa_expiry, #date_of_birth");

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

            $("#password").val(getRndomString(8));
            $('[data-bs-toggle="tooltip"]').tooltip();

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

                // $('#visa_type').select2();
            <?php } ?>

            <?php if ($login_user->user_type === "staff") { ?>
                $("#client_labels").select2({
                    multiple: true,
                    data: <?php echo json_encode($label_suggestions); ?>
                });
            <?php } ?>

            //$(".select2").select2();

            $("#consultancy_type").select2({
                data: <?php echo json_encode($account_types_dropdown); ?>
            });

            handleSubPhaseList(false); // run once when the page is ready

            $("#visa_phase").on("change", function() {
                handleSubPhaseList(true);
            });

            function handleSubPhaseList(emptyList = false) {
                const selectedOption = $("#visa_phase").find(":selected").val();

                if (emptyList) {
                    $("#visa_sub_phase_list")
                        .empty();
                }

                switch (selectedOption) {
                    case "Counselling":

                        $.each([
                            "Pre-Assessment Counseling",
                            "Documentation Guidance",
                            "Understanding Visa Options",
                            "Application Form Assistance",
                            "Financial Planning",
                            "Mock Interviews",
                            "Visa Fee and Payment Guidance",
                            "Submission Strategy",
                            "Waiting Period Support",
                            "Decision Communication",
                            "Appeals and Reapplication",
                            "Post-Approval Support"
                        ], function(key, value) {
                            $('#visa_sub_phase_list')
                                .append($('<option>', {
                                        value
                                    })
                                    .text(value));
                        });

                        break;
                    case "Lodgment":

                        $.each([
                            "Preparation of Required Documents",
                            "Document Verification",
                            "Online Application Submission",
                            "Application Fee Payment",
                            "Appointment Scheduling",
                            "Submission of Physical Documents",
                            "Biometric Data Collection",
                            "Interview Preparation",
                            "Submission Confirmation",
                            "Application Tracking",
                            "Communication with Immigration Authorities",
                            "Notification of Visa Decision",
                        ], function(key, value) {
                            $('#visa_sub_phase_list')
                                .append($('<option>', {
                                        value
                                    })
                                    .text(value));
                        });

                        break;
                    case "Offer":

                        $.each([
                            "Application Review",
                            "Decision Making",
                            "Approval Notification",
                            "Conditional Offer",
                            "Confirmation of Acceptance",
                            "Visa Issuance",
                            "Visa Denial",
                            "Visa Validity Period",
                            "Visa Conditions and Restrictions",
                            "Post-Offer Support",
                        ], function(key, value) {
                            $('#visa_sub_phase_list')
                                .append($('<option>', {
                                        value
                                    })
                                    .text(value));
                        });

                        break;
                    case "Acceptance":

                        $.each([
                            "Receipt of Approval Notice",
                            "Review of Approval Terms",
                            "Acknowledgment of Approval",
                            "Payment of Visa Fees",
                            "Scheduling Visa Issuance",
                            "Completion of Additional Requirements",
                            "Visa Issuance",
                            "Confirmation of Travel Plans",
                            "Compliance with Visa Conditions",
                            "Pre-Departure Preparation",
                            "Communication with Authorities",
                        ], function(key, value) {
                            $('#visa_sub_phase_list')
                                .append($('<option>', {
                                        value
                                    })
                                    .text(value));
                        });

                        break;
                    case "Visa":

                        $.each([
                            "Application Submission",
                            "Document Verification",
                            "Biometric Data Collection",
                            "Application Processing",
                            "Security and Background Checks",
                            "Visa Interview",
                            "Decision Making",
                            "Notification of Decision",
                            "Visa Issuance",
                            "Visa Denial",
                            "Visa Validity Period",
                            "Visa Conditions and Restrictions",
                            "Visa Activation and Utilization",
                            "Monitoring and Compliance",
                        ], function(key, value) {
                            $('#visa_sub_phase_list')
                                .append($('<option>', {
                                        value
                                    })
                                    .text(value));
                        });

                        break;
                    case "Commencement":

                        $.each([
                            "Travel Arrangements",
                            "Pre-Departure Orientation",
                            "Final Checks and Packing",
                            "Arrival at Port of Entry",
                            "Visa Activation",
                            "Immigration Clearance",
                            "Collection of Biometric Data",
                            "Transfer to Destination",
                            "Accommodation Check-In",
                            "Orientation and Integration",
                            "Local Registration",
                            "Health and Safety Compliance",
                            "Initial Settling-In Period",
                            "Monitoring and Compliance",
                        ], function(key, value) {
                            $('#visa_sub_phase_list')
                                .append($('<option>', {
                                        value
                                    })
                                    .text(value));
                        });

                        break;

                    default:

                        break;
                }
            }

        });
    </script>