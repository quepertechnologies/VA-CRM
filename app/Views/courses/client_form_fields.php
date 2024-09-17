<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />
<input type="hidden" name="account_type" value="person" />
<?php $model_info->type = "person" ?>

<div class="tab-content mt15">
    <div role="tabpanel" class="tab-pane active" id="student-profile-tab">
        <h3 class="mb-5"><?php echo app_lang('student_profile'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="profile_image_file_upload" class="<?php echo $label_column; ?>"><?php echo app_lang('profile_image'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_upload(array(
                        "id" => "profile_image_file_upload",
                        "name" => "profile_image",
                        "class" => "form-control"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group hide">
            <div class="row">
                <label for="client_id" class="<?php echo $label_column; ?> client_id_section"><?php echo app_lang('client_id'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "client_id",
                        "name" => "client_id",
                        "value" => isset($model_info->client_id) ? $model_info->client_id : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('client_id'),
                        "autofocus" => true,
                        'disabled' => true,
                        'style' => "cursor: not-allowed"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="first_name" class="<?php echo $label_column; ?> first_name_section"><?php echo app_lang('first_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "first_name",
                        "name" => "first_name",
                        "value" => isset($model_info->first_name) ? $model_info->first_name : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('first_name'),
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
                <label for="last_name" class="<?php echo $label_column; ?> last_name_section"><?php echo app_lang('last_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "last_name",
                        "name" => "last_name",
                        "value" => isset($model_info->last_name) ? $model_info->last_name : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('last_name'),
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
                <label for="email" class="<?php echo $label_column; ?> email_section"><?php echo app_lang('email'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        "value" => isset($model_info->email) ? $model_info->email : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('email'),
                        "autofocus" => true,
                        "type" => 'email',
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="nationality" class="<?php echo $label_column; ?>"><?php echo app_lang('nationality'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    $list = [];

                    foreach (json_decode($countries) as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    echo form_dropdown(
                        'nationality',
                        $list,
                        isset($model_info->nationality) ? $model_info->nationality : 'Australia',
                        "class='form-control select2' id='nationality'"
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="student_onshore" class="<?php echo $label_column; ?>"><?php echo app_lang('student_onshore'); ?></label>
                <div class="<?php echo $field_column; ?> form-check form-switch">
                    <?php
                    echo form_checkbox(
                        'student_onshore',
                        isset($model_info->student_onshore) ? $model_info->student_onshore : '',
                        isset($model_info->student_onshore) ? true : false,
                        "class='form-check-input ml-2' id='student-onshore'"
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group hide" id='profile-student-onshore'>
            <div class="row">
                <label for="country_of_residence" class="<?php echo $label_column; ?>"><?php echo app_lang('country_of_residence'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    $list = [];

                    foreach (json_decode($countries) as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    echo form_dropdown(
                        'country_of_residence',
                        $list,
                        isset($model_info->country_of_residence) ? $model_info->country_of_residence : 'Australia',
                        "class='form-control select2' id='country_of_residence'"
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="preferred_language" class="<?php echo $label_column; ?>"><?php echo app_lang('preferred_language'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $languages = isset($languages_dropdown) ? $languages_dropdown : '';
                    $list = [];

                    foreach (json_decode($languages) as $language) {
                        $list[$language->value] = $language->value;
                    }

                    echo form_dropdown(
                        'preferred_language',
                        $list,
                        isset($model_info->preferred_language) ? $model_info->preferred_language : 'English',
                        "class='form-control select2' id='preferred_language'"
                    );
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
                        $phone_codes = [];

                        foreach (json_decode($countries) as $country) {
                            $phone_codes['+' . $country->phonecode] = $country->nicename . ' +' . $country->phonecode;
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
                        "placeholder" => app_lang('contact_number'),
                        "autofocus" => true,
                        "type" => 'number',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="counsellor_contact" class="<?php echo $label_column; ?>"><?php echo app_lang('counsellor_contact'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <div class="input-group-prepend">
                        <?php
                        $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                        $phone_codes = [];

                        foreach (json_decode($countries) as $country) {
                            $phone_codes['+' . $country->phonecode] = $country->nicename . ' +' . $country->phonecode;
                        }

                        echo form_dropdown(
                            'phone_code',
                            $phone_codes,
                            isset($model_info->co_phone_code) ? $model_info->co_phone_code : '+61',
                            "class='form-control select2' id='select-co-phone-code'"
                        );
                        ?>
                    </div>
                    <?php
                    echo form_input(array(
                        "id" => "co_phone",
                        "name" => "co_phone",
                        "value" => isset($model_info->co_phone) ? $model_info->co_phone : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('counsellor_contact'),
                        "autofocus" => true,
                        "type" => 'number',
                    ));
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
    </div>
    <div role="tabpanel" class="tab-pane" id="academic-achievement-tab">
        <h3 class="mb-5"><?php echo app_lang('academic_achievement'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="lvl_of_edu" class="<?php echo $label_column; ?>"><?php echo app_lang('lvl_of_edu'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_dropdown(
                        'lvl_of_edu',
                        array(
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
                        ),
                        isset($model_info->lvl_of_edu) ? $model_info->lvl_of_edu : '+61',
                        "class='form-control select2' id='lvl_of_edu'"
                    );
                    ?>
                    <small><?php echo app_lang('lvl_of_edu_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="country_of_study" class="<?php echo $label_column; ?>"><?php echo app_lang('country_of_study'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    $list = [];

                    foreach (json_decode($countries) as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    echo form_dropdown(
                        'country_of_study',
                        $list,
                        isset($model_info->country_of_study) ? $model_info->country_of_study : 'Australia',
                        "class='form-control select2' id='country_of_study'"
                    );
                    ?>
                    <small><?php echo app_lang('country_of_study_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="result_of_study" class="<?php echo $label_column; ?>"><?php echo app_lang('result_of_study'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "result_of_study",
                        "name" => "result_of_study",
                        "value" => isset($model_info->result_of_study) ? $model_info->result_of_study : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('result_of_study'),
                        "autofocus" => true,
                    ));
                    ?>
                    <small><?php echo app_lang('result_of_study_caption'); ?></small>
                </div>
            </div>
        </div>

    </div>
    <div role="tabpanel" class="tab-pane" id="study-preference-tab">
        <h3 class="mb-5"><?php echo app_lang('study_preference'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="area_of_study" class="<?php echo $label_column; ?>"><?php echo app_lang('area_of_study'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list =
                        array(
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
                        "class='form-control select2' id='area_of_study'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_course_level" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_course_level'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list = array(
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
                    asort($list);
                    echo form_dropdown(
                        'intended_course_level',
                        $list,
                        isset($model_info->intended_course_level) ? $model_info->intended_course_level : '',
                        "class='form-control select2' id='intended_course_level'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="course_fields_comments" class="<?php echo $label_column; ?>"><?php echo app_lang('course_fields_comments'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "course_fields_comments",
                        "name" => "course_fields_comments",
                        "value" => isset($model_info->course_fields_comments) ? $model_info->course_fields_comments : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('course_fields_comments'),
                        "autofocus" => true,
                    ));
                    ?>
                    <small><?php echo app_lang('course_fields_comments_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="career_paths" class="<?php echo $label_column; ?>"><?php echo app_lang('career_paths'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "career_paths",
                        "name" => "career_paths",
                        "value" => isset($model_info->career_paths) ? $model_info->career_paths : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('career_paths'),
                        "autofocus" => true,
                    ));
                    ?>
                    <small><?php echo app_lang('career_paths_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_institutions" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_institutions'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "intended_institutions",
                        "name" => "intended_institutions",
                        "value" => isset($model_info->intended_institutions) ? $model_info->intended_institutions : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('intended_institutions'),
                        "autofocus" => true,
                    ));
                    ?>
                    <small><?php echo app_lang('intended_institutions_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_intake_quarter" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_intake_quarter'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list = array(
                        'Q1: Jan, Feb, Mar' => 'Q1: Jan, Feb, Mar',
                        'Q2: Apr, May, Jun' => 'Q2: Apr, May, Jun',
                        'Q3: Jul, Aug, Sep' => 'Q3: Jul, Aug, Sep',
                        'Q4: Oct, Nov, Dec' => 'Q4: Oct, Nov, Dec'
                    );
                    echo form_dropdown(
                        'intended_intake_quarter',
                        $list,
                        isset($model_info->intended_intake_quarter) ? $model_info->intended_intake_quarter : '',
                        "class='form-control select2' id='intended_intake_quarter'"
                    );
                    ?>
                    <small><?php echo app_lang('intended_intake_quarter_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_intake_year" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_intake_year'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $currentYear = date("Y");
                    $list = range($currentYear, $currentYear + 10);
                    echo form_dropdown(
                        'intended_intake_year',
                        $list,
                        isset($model_info->intended_intake_year) ? $model_info->intended_intake_year : '',
                        "class='form-control select2' id='intended_intake_year'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_intake_comments" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_intake_comments'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "intended_intake_comments",
                        "name" => "intended_intake_comments",
                        "value" => isset($model_info->intended_intake_comments) ? $model_info->intended_intake_comments : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('intended_intake_comments'),
                        "autofocus" => true,
                    ));
                    ?>
                    <small><?php echo app_lang('intended_intake_comments_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="funding_source" class="<?php echo $label_column; ?>"><?php echo app_lang('funding_source'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "funding_source",
                        "name" => "funding_source",
                        "value" => isset($model_info->funding_source) ? $model_info->funding_source : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('funding_source'),
                        "autofocus" => true,
                    ));
                    ?>
                    <small><?php echo app_lang('funding_source_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_dest_1" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_dest_1'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    $list = [];

                    foreach (json_decode($countries) as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    echo form_dropdown(
                        'intended_dest_1',
                        $list,
                        isset($model_info->intended_dest_1) ? $model_info->intended_dest_1 : 'Australia',
                        "class='form-control select2' id='intended_dest_1'"
                    );
                    ?>
                    <small><?php echo app_lang('intended_dest_1_caption'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_dest_2" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_dest_2'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    $list = [];

                    foreach (json_decode($countries) as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    echo form_dropdown(
                        'intended_dest_2',
                        $list,
                        isset($model_info->intended_dest_2) ? $model_info->intended_dest_2 : 'Australia',
                        "class='form-control select2' id='intended_dest_2'"
                    );
                    ?>
                    <small><?php echo app_lang('optional'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_dest_3" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_dest_3'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    $list = [];

                    foreach (json_decode($countries) as $country) {
                        $list[$country->nicename] = $country->nicename;
                    }

                    echo form_dropdown(
                        'intended_dest_3',
                        $list,
                        isset($model_info->intended_dest_3) ? $model_info->intended_dest_3 : 'Australia',
                        "class='form-control select2' id='intended_dest_3'"
                    );
                    ?>
                    <small><?php echo app_lang('optional'); ?></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="intended_dest_comments" class="<?php echo $label_column; ?>"><?php echo app_lang('intended_dest_comments'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "intended_dest_comments",
                        "name" => "intended_dest_comments",
                        "value" => isset($model_info->intended_dest_comments) ? $model_info->intended_dest_comments : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('intended_dest_comments'),
                        "autofocus" => true,
                    ));
                    ?>
                    <small><?php echo app_lang('intended_dest_comments_caption'); ?></small>
                </div>
            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="personal-info-tab">
        <h3 class="mb-5"><?php echo app_lang('personal_info'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="address" class="<?php echo $label_column; ?>"><?php echo app_lang('address'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "address",
                        "name" => "address",
                        "value" => $model_info->address ? $model_info->address : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('address')
                    ));
                    ?>

                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="city" class="<?php echo $label_column; ?>"><?php echo app_lang('city'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "city",
                        "name" => "city",
                        "value" => $model_info->city,
                        "class" => "form-control",
                        "placeholder" => app_lang('city')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="state" class="<?php echo $label_column; ?>"><?php echo app_lang('state'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "state",
                        "name" => "state",
                        "value" => $model_info->state,
                        "class" => "form-control",
                        "placeholder" => app_lang('state')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="zip" class="<?php echo $label_column; ?>"><?php echo app_lang('zip'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "zip",
                        "name" => "zip",
                        "value" => $model_info->zip,
                        "class" => "form-control",
                        "placeholder" => app_lang('zip')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="country" class="<?php echo $label_column; ?>"><?php echo app_lang('country'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $countries = isset($countries_dropdown) ? $countries_dropdown : '';
                    $country_codes = [];

                    foreach (json_decode($countries) as $country) {
                        $country_codes[$country->nicename] = $country->nicename;
                    }

                    echo form_dropdown(
                        'country',
                        $country_codes,
                        isset($model_info->country) ? $model_info->country : 'Australia',
                        "class='form-control select2' id='select-country-code'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
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
        </div>
        <div class="form-group">
            <div class="row">
                <label for="marital_status" class="<?php echo $label_column; ?>"><?php echo app_lang('marital_status'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_dropdown(
                        "marital_status",
                        array(
                            'Single' => 'Single',
                            'Married' => 'Married',
                            'Divorced' => 'Divorced',
                            'Separated' => 'Separated',
                            'Widowed' => 'Widowed',
                            'Annulled' => 'Annulled',
                            'Domestic Partnership / Civil Union' => 'Domestic Partnership / Civil Union'
                        ),
                        isset($model_info->marital_status) ? $model_info->marital_status : '',
                        "class='select2' placeholder='" . app_lang('marital_status') . "'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="timezone" class="<?php echo $label_column; ?>"><?php echo app_lang('timezone'); ?></label>
                <div class="<?php echo $field_column; ?>">
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
                        "class='select2' placeholder='" . app_lang('timezone') . "'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="currency" class="<?php echo $label_column; ?>"><?php echo app_lang('currency'); ?></label>
                <div class="<?php echo $field_column; ?>">
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
                        "id='currency' class='select2' placeholder='" . app_lang('currency') . "'"
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="internal-tab">
        <h3 class="mb-5"><?php echo app_lang('internal'); ?></h3>
        <div class="form-group">
            <div class="row">
                <label for="assignee" class="<?php echo $label_column; ?>"><?php echo app_lang('assignee'); ?>
                    <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
                </label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "assignee",
                        "name" => "assignee",
                        "value" => isset($model_info->assignee) ? $model_info->assignee : '',
                        "class" => "form-control",
                        "placeholder" => app_lang('assignee'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="assignee-manager" class="<?php echo $label_column; ?> assignee_manager_section"><?php echo app_lang('assignee_manager'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "assignee-manager",
                        "name" => "assignee_manager",
                        "value" => isset($model_info->assignee_manager) ? $model_info->assignee_manager : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('assignee_manager'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="source" class="<?php echo $label_column; ?> source_section"><?php echo app_lang('source'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "source",
                        "name" => "source",
                        "value" => isset($model_info->source) ? $model_info->source : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('source'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="tag_name" class="<?php echo $label_column; ?> tag_name_section"><?php echo app_lang('tag_name'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "tag_name",
                        "name" => "tag_name",
                        "value" => isset($model_info->tag_name) ? $model_info->tag_name : '',
                        "class" => "form-control company_name_input_section",
                        "placeholder" => app_lang('tag_name'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
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
        <?php } ?>

        <?php if ($login_user->user_type === "staff") { ?>
            $("#client_labels").select2({
                multiple: true,
                data: <?php echo json_encode($label_suggestions); ?>
            });
        <?php } ?>

        $(".select2").select2();
    });
</script>