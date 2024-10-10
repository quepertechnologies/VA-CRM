 <style type="text/css">
   h6
   {
    font-weight: bold;
}
</style>
<div id="page-content" class="page-wrapper clearfix">
    <div class="view-container">
        <div class="card">
            <div class="page-title clearfix"><h1>Free Assessment Form</h1></div>

            <div class="card-body">
             <small>The form is designed to assess and determine the appropriate Visa Subclass for applicants based on their eligibility criteria</small>
              <?php echo form_open("request_estimate/save_estimate_request", array("id" => "free_assessment", "class" => "general-form", "role" => "form")); 
              
              echo "<input type='hidden' name='form_id' value='14' />";
              echo "<input type='hidden' name='assigned_to' value='7114' />";
              echo "<input type='hidden' name='json' value='1' />";

              ?>
                <div class=" pt10 mt15">
                    <!-- CLIENT FIELDS -->
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <h6 for="first_name">First name*</h6>
                            <div>
                                <input type="text" name="first_name" value="" id="first_name" class="form-control" placeholder="First name" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <h6 for="last_name">Last name*</h6>
                            <div>
                                <input type="text" name="last_name" value="" id="last_name" class="form-control" placeholder="Last name" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <h6 for="email">Email*</h6>
                            <div>
                                <input type="email" name="email" value="" id="email" class="form-control" placeholder="Email" autofocus="" autocomplete="off" data-rule-email="" data-msg-email="Please enter a valid email address." required>
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <h6 for="phone">Phone*</h6>
                            <div>
                                <input type="number" name="phone" value="" id="phone" class="form-control" placeholder="Phone" required>
                            </div>
                        </div>

                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <h6 for="date_of_birth">Date of birth*</h6>
                            <div>
                                <input type="date" required name="date_of_birth" value="" id="date_of_birth" class="form-control" placeholder="Date of birth" required data-convert-date-format="1">
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <h6>Select Visa Type*</h6>
                            <select id="visa_type" name="visa_type" class="form-control" required>
                                <option value="">Please select</option>
                                <option value="Temporary Skill Shortage visa (subclass 482)">Temporary Skill Shortage visa (subclass 482)</option>
                                <option value="Partner visa (subclass 820 and 801)">Partner visa (subclass 820 and 801)</option>
                                <option value="Visitor visa (subclass 600)">Visitor visa (subclass 600)</option>
                                <option value="Working Holiday visa (subclass 417)">Working Holiday visa (subclass 417)</option>
                                <option value="Temporary Graduate visa (subclass 485)">Temporary Graduate visa (subclass 485)</option>
                                <option value="Employer Nomination Scheme visa (subclass 186)">Employer Nomination Scheme visa (subclass 186)</option>
                                <option value="Permanent Resident (Skilled Regional) visa (subclass 191)">Permanent Resident (Skilled Regional) visa (subclass 191)</option>
                            </select>
                        </div>


                        <div>
                        </div>

                    </div>
                    <hr>
                </div>
                <!-- Visa Selection -->
                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <h6>What visa are you looking for?</h6>
                    <div>
                        <label class="radio-inline"><input type="radio" name="visa_type" value="student_visa" required> Student Visa</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline"><input type="radio" name="visa_type" value="skilled_migration_visa"> Skilled Migration Visa</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline"><input type="radio" name="visa_type" value="employer_sponsored"> Employer Sponsored</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline"><input type="radio" name="visa_type" value="other"> Other</label>
                    </div>
                </div>
                <section id="student_visa_section" class="hide">
                   <hr>    
                   <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <h6>Are you inside Australia or outside?</h6>
                    <select id="location" name="location" class="form-control" required>
                        <option value="">Please select</option>
                        <option value="inside">Inside</option>
                        <option value="outside">Outside</option>
                    </select>
                </div>

                <!-- Date of Entry -->
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <h6>When did you first come to Australia?</h6>
                    <input type="date" id="arrival-date" name="arrival_date" class="form-control" placeholder="DD-MM-YYYY" required>
                </div>

                <!-- New Student Visa Application Dropdown -->
                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                 <h6>Is this a new Student Visa Application?</h6>
                 <select id="new_student_visa" class="form-control" name="new_student_visa" required>
                    <option value="">Please select</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <!-- Educational Background -->
            <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                <h6>Please share your qualifications and how they prepare you for your chosen course in Australia:</h6>
                <div>
                    <textarea class="form-control"></textarea>
                </div>
            </div>

            <!-- English Proficiency Test -->

            <h6>Please enter your English proficiency test scores (TEST, IELTS, PTE):</h6>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <input type="number" class="form-control" name="reading_score" placeholder="Reading">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="listening_score" placeholder="Listening">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="speaking_score" placeholder="Speaking">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="writing_score" placeholder="Writing">
                </div>
            </div>
            <br>
            <div class="row">
               <div class="col-md-4">
                <label>Overall Score</label>
                <input type="number" class="form-control" name="overall_score">
            </div>

            <div class="col-md-4">
                <label>Test Date</label>
                <input type="date" id="test-date" class="form-control" name="test_date" placeholder="DD-MM-YYYY">
            </div>
        </div>

        <br><!-- Financial Planning Dropdown -->
        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
            <h6 for="financial_plan">Are you prepared to spend at least 10k to 15k AUD on your studies and do you have a bank balance of at least 40k AUD?</h6>
            <select id="financial_plan" name="financial_plan" class="form-control" required>
                <option value="">Please select</option>
                <option value="yes_prepared">Yes, I am prepared</option>
                <option value="need_assistance">I need assistance with this</option>
            </select>
        </div>

        <!-- Motivation -->
        <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
            <h6>What motivates you to study in Australia and what do you envision doing after your studies?</h6>
            <div>
              <textarea class="form-control"></textarea>
          </div>
      </div>

      <!-- Health Check and Legal Record Dropdown -->
      <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <h6 for="health_legal">Have you recently had a health check-up and do you have a clean legal record?</h6>
        <select id="health_legal" name="health_legal" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Plan for Student Visa Dropdown -->
    <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <h6 for="visa_plan">When do you plan to apply for your student visa?</h6>
        <select id="visa_plan" name="visa_plan" class="form-control" required>
            <option value="">Please select</option>
            <option value="3_months">3 months</option>
            <option value="6_months">6 months</option>
            <option value="12_months">12 months</option>
        </select>
    </div>
</section>

<section id="migration_visa_section" class="form-section hide">
    <hr>
    <!-- Introduction -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h4>The points test is a critical component of Australia’s skilled migration program, designed to select applicants who have the skills and attributes beneficial to the Australian economy.</h4>
        <p>Please start answering the questions below to the best of your knowledge to know your eligibility.</p>
    </div>

    <!-- Age Range -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Your age range</h6>
        <select id="age_range" name="age_range" class="form-control" required>
            <option value="">Please select</option>
            <option value="18_to_24">18 to 24</option>
            <option value="25_to_32">25 to 32</option>
            <option value="33_to_39">33 to 39</option>
            <option value="40_to_44">40 to 44</option>
        </select>
    </div>

    <!-- Highest Level of Education -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Your highest level of education</h6>
        <select id="education_level" name="education_level" class="form-control" required>
            <option value="">Please select</option>
            <option value="phd">A Doctorate Degree (PhD)</option>
            <option value="bachelor">Bachelor Degree</option>
            <option value="diploma">Diploma or trade qualification</option>
            <option value="no_qualification">No Recognised Qualifications</option>
        </select>
    </div>

    <!-- Degree from Australian Institute -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Do you have at least 1 Degree, Diploma or Trade qualification which was for at least 2 years (minimum 92 weeks CRICOS registered) from an Australian institute?</h6>
        <select id="degree_from_australia" name="degree_from_australia" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Study in Regional Australia -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Did you live and study in a 'regional and low population growth metropolitan area' of Australia and complete a course that meets the 'Australian study requirement' at the time of invitation to apply for the visa?</h6>
        <select id="regional_study" name="regional_study" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Masters or Doctorate Degree -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Do you have a Master’s degree by research or a Doctorate degree from an Australian educational institution that included at least 2 academic years to a relevant field?</h6>
        <select id="masters_degree" name="masters_degree" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Work Experience (Nominated Occupation) -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>How long have you been working in your nominated occupation in the past 10 years, as a full-time employee, working at least 20 hours per week?</h6>
        <select id="nominated_occupation_experience" name="nominated_occupation_experience" class="form-control" required>
            <option value="">Please select</option>
            <option value="8_years_more">8 Years or More</option>
            <option value="5_years_more">5 Years or More</option>
            <option value="3_years_more">3 Years or More</option>
            <option value="less_than_3_years">Less than 3 Years</option>
        </select>
    </div>

    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>How long have you worked in your nominated or closely nominated occupation in the past 10 years, as a full-time employee, working at least 20 hours per week?</h6>
        <select id="nominated_experience" name="nominated_experience" class="form-control" required>
            <option value="">Please select</option>
            <option value="8_years_more">8 Years or More</option>
            <option value="5_years_more">5 Years or More</option>
            <option value="3_years_more">3 Years or More</option>
            <option value="1_year_more">1 Year or More</option>
            <option value="no_experience">No Experience</option>
        </select>
    </div>


    <!-- English Language Ability -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>How would you rate your English language ability?</h6>
        <select id="english_ability" name="english_ability" class="form-control" required>
            <option value="">Please select</option>
            <option value="competent">Competent English</option>
            <option value="proficient">Proficient English</option>
            <option value="superior">Superior English</option>
        </select>
    </div>

    <!-- Partner Skill Points -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Do you have a spouse or partner and want to claim Partner skill point? Do they meet any one of these criteria?</h6>
        <select id="partner_skills" name="partner_skills" class="form-control" required>
            <option value="">Please select</option>
            <option value="meet_all_criteria">Your spouse or de facto partner must also be an applicant for this visa and meet age, English, and skill criteria</option>
            <option value="competent_english">Your spouse or de facto partner must be an applicant and has competent English</option>
            <option value="australian_citizen">You are single or your partner is an Australian citizen or permanent resident</option>
            <option value="not_applicable">Not applicable</option>
        </select>
    </div>

    <!-- NAATI Accreditation -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Have you been accredited at the paraprofessional level or above for interpreting or translating in a language offered by the National Accreditation Authority for Translator and Interpreters (NAATI)?</h6>
        <select id="naati_accreditation" name="naati_accreditation" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Professional Program in Australia -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Have you completed your professional program in Australia in the past 48 months?</h6>
        <select id="professional_program" name="professional_program" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Visa Subclass -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Choose Visa Subclass</h6>
        <select id="visa_subclass" name="visa_subclass" class="form-control" required>
            <option value="">Please select</option>
            <option value="subclass_189">Subclass 189: The Skilled Independent Visa</option>
            <option value="subclass_190">Subclass 190: The Skilled Nominated Visa</option>
            <option value="subclass_491">Subclass 491: The Skilled Work Regional (Provisional) Visa</option>
        </select>
    </div>

    <!-- Points Calculation -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Calculation</h6>
        <input type="number" class="form-control" id="calculation" name="calculation" placeholder="To be eligible you need to score greater than 60 points" required>
    </div>

</section>

<section id="occupation_section" class="form-section hide">
    <hr>
    <!-- Select Occupation -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Select your Occupation</h6>
        <select id="occupation" name="occupation" class="form-control" required>
            <option value="">Please select</option>
            <option value="occupation_1">Occupation 1</option>
            <option value="occupation_2">Occupation 2</option>
            <!-- Add more occupation options as needed -->
        </select>
    </div>

    <!-- Work Experience (More than 1 year) -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Do you have more than one year of full-time work experience?</h6>
        <select id="work_experience" name="work_experience" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Qualification Related to Occupation -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Do you have qualification related to your selected Occupation?</h6>
        <select id="qualification_related" name="qualification_related" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Educational Qualification -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>What is your highest level of educational qualification?</h6>
        <select id="education_level" name="education_level" class="form-control" required>
            <option value="">Please select</option>
            <option value="phd">PhD</option>
            <option value="masters">Master’s Degree</option>
            <option value="bachelor">Bachelor’s Degree</option>
            <option value="diploma">Diploma</option>
            <option value="no_qualification">No Qualification</option>
        </select>
    </div>

    <!-- English Test (IELTS, PTE) -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Have you done the English TEST, IELTS, PTE?</h6>
        <select id="english_test" name="english_test" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>

    <!-- Company Name -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>Please Enter your Company Name</h6>
        <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter your company name" required>
    </div>

</section>



<div id="buttons" class="right hide"><!-- Submit Buttons -->
   <button type="submit" class="btn btn-primary" style="float: right;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send icon-16"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> Submit</button>
</div>
 <?php
        echo form_close();
        ?>

</div>
</div>
</div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {

    $("#free_assessment").appForm({
            isModal: false,
            onSubmit: function () {
                appLoader.show();
            },
            onSuccess: function (result) {
                appLoader.hide();
                appAlert.success(result.message, {container: '.card-body', animate: false});
                $("#free_assessment").remove();
                $('#occupation_section').addClass('hide');
                $('#student_visa_section').addClass('hide');
                $('#migration_visa_section').addClass('hide');
                $('#buttons').addClass('hide');     
            },
            onError: function (result) {
                appLoader.hide();
                appAlert.error(result.message, {container: '.card-body', animate: false});
                return false;
            }
    });

     $("input[type='radio']").click(function(){
        var radioValue = $("input[name='visa_type']:checked").val();
        if(radioValue == 'student_visa')
        {
            $('#student_visa_section').removeClass('hide');
            $('#migration_visa_section').addClass('hide');
            $('#occupation_section').addClass('hide');
            $('#buttons').removeClass('hide');                
        }
        else if(radioValue == 'skilled_migration_visa')
        {
            $('#migration_visa_section').removeClass('hide');
            $('#occupation_section').addClass('hide');
            $('#student_visa_section').addClass('hide');
            $('#buttons').removeClass('hide');     
        }
        else if(radioValue == 'employer_sponsored')
        {
            $('#occupation_section').removeClass('hide');
            $('#student_visa_section').addClass('hide');
            $('#migration_visa_section').addClass('hide');
            $('#buttons').removeClass('hide');     
        }
        else
        {
            $('#occupation_section').addClass('hide');
            $('#student_visa_section').addClass('hide');
            $('#migration_visa_section').addClass('hide');
            $('#buttons').removeClass('hide');     
        }
    });

 });
</script>