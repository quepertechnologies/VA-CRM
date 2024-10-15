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
               
               echo "<input type='hidden' name='form_id' value='$model_info->id' />";
               echo "<input type='hidden' name='assigned_to' value='$model_info->assigned_to' />";
               echo "<input type='hidden' name='location_id' value='$model_info->location_id' />";
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
                            <option value="">Please select</option>
                            <option value="subclass_400_temporary_work">Subclass 400 - Temporary Work (Short Stay Specialist) Visa</option>
                            <option value="subclass_491_skilled_work_regional">Subclass 491 - Skilled Work Regional (Provisional) Visa</option>
                            <option value="subclass_186_employer_nomination_scheme">Subclass 186 - Employer Nomination Scheme Visa</option>
                            <option value="subclass_600_visitor_visa">Subclass 600 - Visitor Visa</option>
                            <option value="subclass_500_student_visa">Subclass 500 - Student Visa</option>
                            <option value="subclass_485_graduate_temporary_visa">Subclass 485 - Graduate Temporary Visa</option>
                            <option value="subclass_189_skilled_independent">Subclass 189 - Skilled Independent Visa</option>
                            <option value="subclass_190_skilled_nominated">Subclass 190 - Skilled Nominated Visa</option>
                            <option value="subclass_482_temporary_skill_shortage">Subclass 482 - Temporary Skill Shortage Visa</option>
                            <option value="subclass_820_partner_temporary">Subclass 820 - Partner Visa (Temporary)</option>
                            <option value="subclass_801_partner_permanent">Subclass 801 - Partner Visa (Permanent)</option>
                            <option value="subclass_188_business_innovation_investment">Subclass 188 - Business Innovation and Investment Visa</option>
                            <option value="subclass_187_regional_sponsored_migration_scheme">Subclass 187 - Regional Sponsored Migration Scheme Visa</option>
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
             <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <h6>Are you inside Australia or outside?</h6>
                <select id="location" name="location" class="form-control" required>
                    <option value="">Please select</option>
                    <option value="inside">Inside</option>
                    <option value="outside">Outside</option>
                </select>
            </div>

            <!-- Date of Entry -->
            <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <h6>When did you first come to Australia?</h6>
                <input type="date" id="arrival-date" name="arrival_date" class="form-control" placeholder="DD-MM-YYYY" required>
            </div>

            <!-- New Student Visa Application Dropdown -->
            <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
               <h6>Is this a new Student Visa Application?</h6>
               <select id="new_student_visa" class="form-control" name="new_student_visa" required>
                <option value="">Please select</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>

        <!-- Educational Background -->
        <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
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
                <input type="number" class="form-control" name="reading_score" placeholder="Reading" required>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="listening_score" placeholder="Listening" required>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="speaking_score" placeholder="Speaking" required>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="writing_score" placeholder="Writing" required>
            </div>
        </div>
        <br>
        <div class="row">
         <div class="col-md-4">
            <label>Overall Score</label>
            <input type="number" class="form-control" name="overall_score" required>
        </div>

        <div class="col-md-4">
            <label>Test Date</label>
            <input type="date" id="test-date" class="form-control" name="test_date" placeholder="DD-MM-YYYY" required>
        </div>
    </div>

    <br><!-- Financial Planning Dropdown -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6 for="financial_plan">Are you prepared to spend at least 10k to 15k AUD on your studies and do you have a bank balance of at least 40k AUD?</h6>
        <select id="financial_plan" name="financial_plan" class="form-control" required>
            <option value="">Please select</option>
            <option value="yes_prepared">Yes, I am prepared</option>
            <option value="need_assistance">I need assistance with this</option>
        </select>
    </div>

    <!-- Motivation -->
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h6>What motivates you to study in Australia and what do you envision doing after your studies?</h6>
        <div>
          <textarea class="form-control"></textarea>
      </div>
  </div>

  <!-- Health Check and Legal Record Dropdown -->
  <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <h6 for="health_legal">Have you recently had a health check-up and do you have a clean legal record?</h6>
    <select id="health_legal" name="health_legal" class="form-control" required>
        <option value="">Please select</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
</div>

<!-- Plan for Student Visa Dropdown -->
<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
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
        <p style="float:right; font-size: 24px;">Your Points: <span><font color="red" id="points">0</font></span></p>
        <br>
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
    <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12 hide">
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
            <option value="Construction Project Manager">Construction Project Manager</option>
            <option value="Engineering Manager">Engineering Manager</option>
            <option value="Child Care Centre Manager">Child Care Centre Manager</option>
            <option value="Nursing Clinical Director">Nursing Clinical Director</option>
            <option value="Primary Health Organisation Manager">Primary Health Organisation Manager</option>
            <option value="Welfare Centre Manager">Welfare Centre Manager</option>
            <option value="Arts Administrator or Manager">Arts Administrator or Manager</option>
            <option value="Environmental Manager">Environmental Manager</option>
            <option value="Dancer or Choreographer">Dancer or Choreographer</option>
            <option value="Music Director">Music Director</option>
            <option value="Musician (Instrumental)">Musician (Instrumental)</option>
            <option value="Artistic Director">Artistic Director</option>
            <option value="Accountant (General)">Accountant (General)</option>
            <option value="Management Accountant">Management Accountant</option>
            <option value="Taxation Accountant">Taxation Accountant</option>
            <option value="External Auditor">External Auditor</option>
            <option value="Internal Auditor">Internal Auditor</option>
            <option value="Actuary">Actuary</option>
            <option value="Statistician">Statistician</option>
            <option value="Economist">Economist</option>
            <option value="Land Economist">Land Economist</option>
            <option value="Valuer">Valuer</option>
            <option value="Management Consultant">Management Consultant</option>
            <option value="Architect">Architect</option>
            <option value="Landscape Architect">Landscape Architect</option>
            <option value="Surveyor">Surveyor</option>
            <option value="Cartographer">Cartographer</option>
            <option value="Other Spatial Scientist">Other Spatial Scientist</option>
            <option value="Chemical Engineer">Chemical Engineer</option>
            <option value="Materials Engineer">Materials Engineer</option>
            <option value="Civil Engineer">Civil Engineer</option>
            <option value="Geotechnical Engineer">Geotechnical Engineer</option>
            <option value="Quantity Surveyor">Quantity Surveyor</option>
            <option value="Structural Engineer">Structural Engineer</option>
            <option value="Transport Engineer">Transport Engineer</option>
            <option value="Electrical Engineer">Electrical Engineer</option>
            <option value="Electronics Engineer">Electronics Engineer</option>
            <option value="Industrial Engineer">Industrial Engineer</option>
            <option value="Mechanical Engineer">Mechanical Engineer</option>
            <option value="Production or Plant Engineer">Production or Plant Engineer</option>
            <option value="Mining Engineer (Excluding Petroleum)">Mining Engineer (Excluding Petroleum)</option>
            <option value="Petroleum Engineer">Petroleum Engineer</option>
            <option value="Aeronautical Engineer">Aeronautical Engineer</option>
            <option value="Agricultural Engineer">Agricultural Engineer</option>
            <option value="Biomedical Engineer">Biomedical Engineer</option>
            <option value="Engineering Technologist">Engineering Technologist</option>
            <option value="Environmental Engineer">Environmental Engineer</option>
            <option value="Naval Architect">Naval Architect</option>
            <option value="Engineering Professionals (NEC)">Engineering Professionals (NEC)</option>
            <option value="Agricultural Consultant">Agricultural Consultant</option>
            <option value="Agricultural Scientist">Agricultural Scientist</option>
            <option value="Forester">Forester</option>
            <option value="Chemist">Chemist</option>
            <option value="Food Technologist">Food Technologist</option>
            <option value="Environmental Consultant">Environmental Consultant</option>
            <option value="Environmental Research Scientist">Environmental Research Scientist</option>
            <option value="Environmental Scientist (NEC)">Environmental Scientist (NEC)</option>
            <option value="Geophysicist">Geophysicist</option>
            <option value="Hydrogeologist">Hydrogeologist</option>
            <option value="Life Scientist (General)">Life Scientist (General)</option>
            <option value="Biochemist">Biochemist</option>
            <option value="Biotechnologist">Biotechnologist</option>
            <option value="Botanist">Botanist</option>
            <option value="Marine Biologist">Marine Biologist</option>
            <option value="Microbiologist">Microbiologist</option>
            <option value="Zoologist">Zoologist</option>
            <option value="Life Scientists (NEC)">Life Scientists (NEC)</option>
            <option value="Medical Laboratory Scientist">Medical Laboratory Scientist</option>
            <option value="Veterinarian">Veterinarian</option>
            <option value="Conservator">Conservator</option>
            <option value="Metallurgist">Metallurgist</option>
            <option value="Meteorologist">Meteorologist</option>
            <option value="Physicist">Physicist</option>
            <option value="Natural and Physical Science Professionals (NEC)">Natural and Physical Science Professionals (NEC)</option>
            <option value="Early Childhood (Pre-Primary School) Teacher">Early Childhood (Pre-Primary School) Teacher</option>
            <option value="Secondary School Teacher">Secondary School Teacher</option>
            <option value="Special Needs Teacher">Special Needs Teacher</option>
            <option value="Teacher of the Hearing Impaired">Teacher of the Hearing Impaired</option>
            <option value="Teacher of the Sight Impaired">Teacher of the Sight Impaired</option>
            <option value="Special Education Teachers (NEC)">Special Education Teachers (NEC)</option>
            <option value="University Lecturer">University Lecturer</option>
            <option value="Medical Diagnostic Radiographer">Medical Diagnostic Radiographer</option>
            <option value="Medical Radiation Therapist">Medical Radiation Therapist</option>
            <option value="Nuclear Medicine Technologist">Nuclear Medicine Technologist</option>
            <option value="Sonographer">Sonographer</option>
            <option value="Optometrist">Optometrist</option>
            <option value="Orthotist or Prosthetist">Orthotist or Prosthetist</option>
            <option value="Chiropractor">Chiropractor</option>
            <option value="Osteopath">Osteopath</option>
            <option value="Occupational Therapist">Occupational Therapist</option>
            <option value="Physiotherapist">Physiotherapist</option>
            <option value="Podiatrist">Podiatrist</option>
            <option value="Audiologist">Audiologist</option>
            <option value="Speech Pathologist">Speech Pathologist</option>
            <option value="General Practitioner">General Practitioner</option>
            <option value="Specialist Physician (General Medicine)">Specialist Physician (General Medicine)</option>
            <option value="Cardiologist">Cardiologist</option>
            <option value="Clinical Haematologist">Clinical Haematologist</option>
            <option value="Medical Oncologist">Medical Oncologist</option>
            <option value="Endocrinologist">Endocrinologist</option>
            <option value="Gastroenterologist">Gastroenterologist</option>
            <option value="Intensive Care Specialist">Intensive Care Specialist</option>
            <option value="Neurologist">Neurologist</option>
            <option value="Paediatrician">Paediatrician</option>
            <option value="Renal Medicine Specialist">Renal Medicine Specialist</option>
            <option value="Rheumatologist">Rheumatologist</option>
            <option value="Thoracic Medicine Specialist">Thoracic Medicine Specialist</option>
            <option value="Specialist Physicians (NEC)">Specialist Physicians (NEC)</option>
            <option value="Psychiatrist">Psychiatrist</option>
            <option value="Surgeon (General)">Surgeon (General)</option>
            <option value="Cardiothoracic Surgeon">Cardiothoracic Surgeon</option>
            <option value="Neurosurgeon">Neurosurgeon</option>
            <option value="Orthopaedic Surgeon">Orthopaedic Surgeon</option>
            <option value="Otorhinolaryngologist">Otorhinolaryngologist</option>
            <option value="Paediatric Surgeon">Paediatric Surgeon</option>
            <option value="Plastic and Reconstructive Surgeon">Plastic and Reconstructive Surgeon</option>
            <option value="Urologist">Urologist</option>
            <option value="Vascular Surgeon">Vascular Surgeon</option>
            <option value="Dermatologist">Dermatologist</option>
            <option value="Emergency Medicine Specialist">Emergency Medicine Specialist</option>
            <option value="Obstetrician and Gynaecologist">Obstetrician and Gynaecologist</option>
            <option value="Ophthalmologist">Ophthalmologist</option>
            <option value="Pathologist">Pathologist</option>
            <option value="Diagnostic and Interventional Radiologist">Diagnostic and Interventional Radiologist</option>
            <option value="Radiation Oncologist">Radiation Oncologist</option>
            <option value="Medical Practitioners (NEC)">Medical Practitioners (NEC)</option>
            <option value="Midwife">Midwife</option>
            <option value="Nurse Practitioner">Nurse Practitioner</option>
            <option value="Registered Nurse (Aged Care)">Registered Nurse (Aged Care)</option>
            <option value="Registered Nurse (Child and Family Health)">Registered Nurse (Child and Family Health)</option>
            <option value="Registered Nurse (Community Health)">Registered Nurse (Community Health)</option>
            <option value="Registered Nurse (Critical Care and Emergency)">Registered Nurse (Critical Care and Emergency)</option>
            <option value="Registered Nurse (Developmental Disability)">Registered Nurse (Developmental Disability)</option>
            <option value="Registered Nurse (Disability and Rehabilitation)">Registered Nurse (Disability and Rehabilitation)</option>
            <option value="Registered Nurse (Medical)">Registered Nurse (Medical)</option>
            <option value="Registered Nurse (Medical Practice)">Registered Nurse (Medical Practice)</option>
            <option value="Registered Nurse (Mental Health)">Registered Nurse (Mental Health)</option>
            <option value="Registered Nurse (Perioperative)">Registered Nurse (Perioperative)</option>
            <option value="Registered Nurse (Surgical)">Registered Nurse (Surgical)</option>
            <option value="Registered Nurse (Paediatrics)">Registered Nurse (Paediatrics)</option>
            <option value="Registered Nurses (NEC)">Registered Nurses (NEC)</option>
            <option value="ICT Business Analyst">ICT Business Analyst</option>
            <option value="Systems Analyst">Systems Analyst</option>
            <option value="Multimedia Specialist">Multimedia Specialist</option>
            <option value="Analyst Programmer">Analyst Programmer</option>
            <option value="Developer Programmer">Developer Programmer</option>
            <option value="Software Engineer">Software Engineer</option>
            <option value="Software and Applications Programmers (NEC)">Software and Applications Programmers (NEC)</option>
            <option value="ICT Security Specialist">ICT Security Specialist</option>
            <option value="Computer Network and Systems Engineer">Computer Network and Systems Engineer</option>
            <option value="Telecommunications Engineer">Telecommunications Engineer</option>
            <option value="Telecommunications Network Engineer">Telecommunications Network Engineer</option>
            <option value="Barrister">Barrister</option>
            <option value="Solicitor">Solicitor</option>
            <option value="Clinical Psychologist">Clinical Psychologist</option>
            <option value="Educational Psychologist">Educational Psychologist</option>
            <option value="Organisational Psychologist">Organisational Psychologist</option>
            <option value="Psychologists (NEC)">Psychologists (NEC)</option>
            <option value="Social Worker">Social Worker</option>
            <option value="Civil Engineering Draftsperson">Civil Engineering Draftsperson</option>
            <option value="Civil Engineering Technician">Civil Engineering Technician</option>
            <option value="Electrical Engineering Draftsperson">Electrical Engineering Draftsperson</option>
            <option value="Electrical Engineering Technician">Electrical Engineering Technician</option>
            <option value="Radio Communications Technician">Radio Communications Technician</option>
            <option value="Telecommunications Field Engineer">Telecommunications Field Engineer</option>
            <option value="Telecommunications Network Planner">Telecommunications Network Planner</option>
            <option value="Telecommunications Technical Officer or Technologist">Telecommunications Technical Officer or Technologist</option>
            <option value="Automotive Electrician">Automotive Electrician</option>
            <option value="Motor Mechanic (General)">Motor Mechanic (General)</option>
            <option value="Diesel Motor Mechanic">Diesel Motor Mechanic</option>
            <option value="Motorcycle Mechanic">Motorcycle Mechanic</option>
            <option value="Small Engine Mechanic">Small Engine Mechanic</option>
            <option value="Sheetmetal Trades Worker">Sheetmetal Trades Worker</option>
            <option value="Metal Fabricator">Metal Fabricator</option>
            <option value="Pressure Welder">Pressure Welder</option>
            <option value="Welder (First Class)">Welder (First Class)</option>
            <option value="Fitter (General)">Fitter (General)</option>
            <option value="Fitter and Turner">Fitter and Turner</option>
            <option value="Fitter-Welder">Fitter-Welder</option>
            <option value="Metal Machinist (First Class)">Metal Machinist (First Class)</option>
            <option value="Locksmith">Locksmith</option>
            <option value="Panelbeater">Panelbeater</option>
            <option value="Bricklayer">Bricklayer</option>
            <option value="Stonemason">Stonemason</option>
            <option value="Carpenter and Joiner">Carpenter and Joiner</option>
            <option value="Carpenter">Carpenter</option>
            <option value="Joiner">Joiner</option>
            <option value="Painting Trades Worker">Painting Trades Worker</option>
            <option value="Glazier">Glazier</option>
            <option value="Fibrous Plasterer">Fibrous Plasterer</option>
            <option value="Solid Plasterer">Solid Plasterer</option>
            <option value="Wall and Floor Tiler">Wall and Floor Tiler</option>
            <option value="Plumber (General)">Plumber (General)</option>
            <option value="Airconditioning and Mechanical Services Plumber">Airconditioning and Mechanical Services Plumber</option>
            <option value="Drainer">Drainer</option>
            <option value="Gasfitter">Gasfitter</option>
            <option value="Roof Plumber">Roof Plumber</option>
            <option value="Electrician (General)">Electrician (General)</option>
            <option value="Electrician (Special Class)">Electrician (Special Class)</option>
            <option value="Lift Mechanic">Lift Mechanic</option>
            <option value="Airconditioning and Refrigeration Mechanic">Airconditioning and Refrigeration Mechanic</option>
            <option value="Technical Cable Jointer">Technical Cable Jointer</option>
            <option value="Electronic Equipment Trades Worker">Electronic Equipment Trades Worker</option>
            <option value="Electronic Instrument Trades Worker (General)">Electronic Instrument Trades Worker (General)</option>
            <option value="Electronic Instrument Trades Worker (Special Class)">Electronic Instrument Trades Worker (Special Class)</option>
            <option value="Chef">Chef</option>
            <option value="Horse Trainer">Horse Trainer</option>
            <option value="Cabinetmaker">Cabinetmaker</option>
            <option value="Boat Builder and Repairer">Boat Builder and Repairer</option>
            <option value="Shipwright">Shipwright</option>
            <option value="Tennis Coach">Tennis Coach</option>
            <option value="Footballer">Footballer</option>
            <option value="Flower Grower">Flower Grower</option>
            <option value="Grape Grower">Grape Grower</option>
            <option value="Vegetable Grower (Aus) / Market Gardener (NZ)">Vegetable Grower (Aus) / Market Gardener (NZ)</option>
            <option value="Apiarist">Apiarist</option>
            <option value="Poultry Farmer">Poultry Farmer</option>
            <option value="Sales and Marketing Manager">Sales and Marketing Manager</option>
            <option value="Advertising Manager">Advertising Manager</option>
            <option value="Corporate Services Manager">Corporate Services Manager</option>
            <option value="Finance Manager">Finance Manager</option>
            <option value="Human Resource Manager">Human Resource Manager</option>
            <option value="Research and Development Manager">Research and Development Manager</option>
            <option value="Manufacturer">Manufacturer</option>
            <option value="Production Manager (Forestry)">Production Manager (Forestry)</option>
            <option value="Production Manager (Manufacturing)">Production Manager (Manufacturing)</option>
            <option value="Production Manager (Mining)">Production Manager (Mining)</option>
            <option value="Supply and Distribution Manager">Supply and Distribution Manager</option>
            <option value="Health and Welfare Services Managers (NEC)">Health and Welfare Services Managers (NEC)</option>
            <option value="School Principal">School Principal</option>
            <option value="Education Managers (NEC)">Education Managers (NEC)</option>
            <option value="ICT Project Manager">ICT Project Manager</option>
            <option value="ICT Managers (NEC)">ICT Managers (NEC)</option>
            <option value="Laboratory Manager">Laboratory Manager</option>
            <option value="Quality Assurance Manager">Quality Assurance Manager</option>
            <option value="Specialist Managers (NEC) Except: Ambassador, Archbishop, Bishop">Specialist Managers (NEC) Except: Ambassador, Archbishop, Bishop</option>
            <option value="Cafe or Restaurant Manager">Cafe or Restaurant Manager</option>
            <option value="Hotel or Motel Manager">Hotel or Motel Manager</option>
            <option value="Accommodation and Hospitality Managers (NEC)">Accommodation and Hospitality Managers (NEC)</option>
            <option value="Customer Service Manager">Customer Service Manager</option>
            <option value="Conference and Event Organiser">Conference and Event Organiser</option>
            <option value="Transport Company Manager">Transport Company Manager</option>
            <option value="Facilities Manager">Facilities Manager</option>
            <option value="Music Professionals (NEC)">Music Professionals (NEC)</option>
            <option value="Photographer">Photographer</option>
            <option value="Book or Script Editor">Book or Script Editor</option>
            <option value="Director (Film, Television, Radio or Stage)">Director (Film, Television, Radio or Stage)</option>
            <option value="Film and Video Editor">Film and Video Editor</option>
            <option value="Program Director (Television or Radio)">Program Director (Television or Radio)</option>
            <option value="Stage Manager">Stage Manager</option>
            <option value="Technical Director">Technical Director</option>
            <option value="Video Producer">Video Producer</option>
            <option value="Copywriter">Copywriter</option>
            <option value="Newspaper or Periodical Editor">Newspaper or Periodical Editor</option>
            <option value="Print Journalist">Print Journalist</option>
            <option value="Technical Writer">Technical Writer</option>
            <option value="Television Journalist">Television Journalist</option>
            <option value="Journalists and Other Writers (NEC)">Journalists and Other Writers (NEC)</option>
            <option value="Company Secretary">Company Secretary</option>
            <option value="Commodities Trader">Commodities Trader</option>
            <option value="Finance Broker">Finance Broker</option>
            <option value="Insurance Broker">Insurance Broker</option>
            <option value="Financial Brokers (NEC)">Financial Brokers (NEC)</option>
            <option value="Financial Market Dealer">Financial Market Dealer</option>
            <option value="Stockbroking Dealer">Stockbroking Dealer</option>
            <option value="Financial Dealers (NEC)">Financial Dealers (NEC)</option>
            <option value="Financial Investment Adviser">Financial Investment Adviser</option>
            <option value="Financial Investment Manager">Financial Investment Manager</option>
            <option value="Recruitment Consultant">Recruitment Consultant</option>
            <option value="ICT Trainer">ICT Trainer</option>
            <option value="Mathematician">Mathematician</option>
            <option value="Gallery or Museum Curator">Gallery or Museum Curator</option>
            <option value="Health Information Manager">Health Information Manager</option>
            <option value="Records Manager">Records Manager</option>
            <option value="Librarian">Librarian</option>
            <option value="Organisation and Methods Analyst">Organisation and Methods Analyst</option>
            <option value="Patents Examiner">Patents Examiner</option>
            <option value="Information and Organisation Professionals (NEC)">Information and Organisation Professionals (NEC)</option>
            <option value="Advertising Specialist">Advertising Specialist</option>
            <option value="Marketing Specialist">Marketing Specialist</option>
            <option value="ICT Account Manager">ICT Account Manager</option>
            <option value="ICT Business Development Manager">ICT Business Development Manager</option>
            <option value="ICT Sales Representative">ICT Sales Representative</option>
            <option value="Public Relations Professional">Public Relations Professional</option>
            <option value="Technical Sales Representatives (NEC) Including Education Sales Representatives">Technical Sales Representatives (NEC) Including Education Sales Representatives</option>
            <option value="Fashion Designer">Fashion Designer</option>
            <option value="Industrial Designer">Industrial Designer</option>
            <option value="Jewellery Designer">Jewellery Designer</option>
            <option value="Graphic Designer">Graphic Designer</option>
            <option value="Illustrator">Illustrator</option>
            <option value="Web Designer">Web Designer</option>
            <option value="Interior Designer">Interior Designer</option>
            <option value="Urban and Regional Planner">Urban and Regional Planner</option>
            <option value="Geologist">Geologist</option>
            <option value="Primary School Teacher">Primary School Teacher</option>
            <option value="Middle School Teacher (Aus) / Intermediate School Teacher (NZ)">Middle School Teacher (Aus) / Intermediate School Teacher (NZ)</option>
            <option value="Education Adviser">Education Adviser</option>
            <option value="Art Teacher (Private Tuition)">Art Teacher (Private Tuition)</option>
            <option value="Dance Teacher (Private Tuition)">Dance Teacher (Private Tuition)</option>
            <option value="Music Teacher (Private Tuition)">Music Teacher (Private Tuition)</option>
            <option value="Private Tutors and Teachers (NEC)">Private Tutors and Teachers (NEC)</option>
            <option value="Teacher of English to Speakers of Other Languages">Teacher of English to Speakers of Other Languages</option>
            <option value="Dietitian">Dietitian</option>
            <option value="Nutritionist">Nutritionist</option>
            <option value="Occupational Health and Safety Adviser">Occupational Health and Safety Adviser</option>
            <option value="Orthoptist">Orthoptist</option>
            <option value="Hospital Pharmacist">Hospital Pharmacist</option>
            <option value="Industrial Pharmacist">Industrial Pharmacist</option>
            <option value="Retail Pharmacist">Retail Pharmacist</option>
            <option value="Health Promotion Officer">Health Promotion Officer</option>
            <option value="Health Diagnostic and Promotion Professionals (NEC)">Health Diagnostic and Promotion Professionals (NEC)</option>
            <option value="Acupuncturist">Acupuncturist</option>
            <option value="Naturopath">Naturopath</option>
            <option value="Traditional Chinese Medicine Practitioner">Traditional Chinese Medicine Practitioner</option>
            <option value="Complementary Health Therapists (NEC)">Complementary Health Therapists (NEC)</option>
            <option value="Dental Specialist">Dental Specialist</option>
            <option value="Resident Medical Officer">Resident Medical Officer</option>
            <option value="Nurse Educator">Nurse Educator</option>
            <option value="Nurse Researcher">Nurse Researcher</option>
            <option value="Nurse Manager">Nurse Manager</option>
            <option value="Web Developer">Web Developer</option>
            <option value="Software Tester">Software Tester</option>
            <option value="Database Administrator">Database Administrator</option>
            <option value="Systems Administrator">Systems Administrator</option>
            <option value="Network Administrator">Network Administrator</option>
            <option value="Network Analyst">Network Analyst</option>
            <option value="ICT Quality Assurance Engineer">ICT Quality Assurance Engineer</option>
            <option value="ICT Support Engineer">ICT Support Engineer</option>
            <option value="ICT Systems Test Engineer">ICT Systems Test Engineer</option>
            <option value="ICT Support and Test Engineers (NEC)">ICT Support and Test Engineers (NEC)</option>
            <option value="Judicial and Other Legal Professionals (NEC)">Judicial and Other Legal Professionals (NEC)</option>
            <option value="Careers Counsellor">Careers Counsellor</option>
            <option value="Drug and Alcohol Counsellor">Drug and Alcohol Counsellor</option>
            <option value="Family and Marriage Counsellor">Family and Marriage Counsellor</option>
            <option value="Rehabilitation Counsellor">Rehabilitation Counsellor</option>
            <option value="Student Counsellor">Student Counsellor</option>
            <option value="Counsellors (NEC)">Counsellors (NEC)</option>
            <option value="Psychotherapist">Psychotherapist</option>
            <option value="Interpreter">Interpreter</option>
            <option value="Social Professionals (NEC)">Social Professionals (NEC)</option>
            <option value="Recreation Officer">Recreation Officer</option>
            <option value="Welfare Worker">Welfare Worker</option>
            <option value="Anaesthetic Technician">Anaesthetic Technician</option>
            <option value="Cardiac Technician">Cardiac Technician</option>
            <option value="Medical Laboratory Technician">Medical Laboratory Technician</option>
            <option value="Pharmacy Technician">Pharmacy Technician</option>
            <option value="Medical Technicians (NEC)">Medical Technicians (NEC)</option>
            <option value="Meat Inspector">Meat Inspector</option>
            <option value="Primary Products Inspectors (NEC)">Primary Products Inspectors (NEC)</option>
            <option value="Chemistry Technician">Chemistry Technician</option>
            <option value="Earth Science Technician">Earth Science Technician</option>
            <option value="Life Science Technician">Life Science Technician</option>
            <option value="Science Technicians (NEC)">Science Technicians (NEC)</option>
            <option value="Architectural Draftsperson">Architectural Draftsperson</option>
            <option value="Building Inspector">Building Inspector</option>
            <option value="Architectural, Building and Surveying Technicians (NEC)">Architectural, Building and Surveying Technicians (NEC)</option>
            <option value="Mechanical Engineering Technician">Mechanical Engineering Technician</option>
            <option value="Metallurgical or Materials Technician">Metallurgical or Materials Technician</option>
            <option value="Mine Deputy">Mine Deputy</option>
            <option value="Hardware Technician">Hardware Technician</option>
            <option value="ICT Customer Support Officer">ICT Customer Support Officer</option>
            <option value="Web Administrator">Web Administrator</option>
            <option value="ICT Support Technicians (NEC)">ICT Support Technicians (NEC)</option>
            <option value="Farrier">Farrier</option>
            <option value="Aircraft Maintenance Engineer (Avionics)">Aircraft Maintenance Engineer (Avionics)</option>
            <option value="Aircraft Maintenance Engineer (Mechanical)">Aircraft Maintenance Engineer (Mechanical)</option>
            <option value="Aircraft Maintenance Engineer (Structures)">Aircraft Maintenance Engineer (Structures)</option>
            <option value="Metal Fitters and Machinists (NEC)">Metal Fitters and Machinists (NEC)</option>
            <option value="Precision Instrument Maker and Repairer">Precision Instrument Maker and Repairer</option>
            <option value="Toolmaker">Toolmaker</option>
            <option value="Vehicle Body Builder">Vehicle Body Builder</option>
            <option value="Vehicle Trimmer">Vehicle Trimmer</option>
            <option value="Roof Tiler">Roof Tiler</option>
            <option value="Business Machine Mechanic">Business Machine Mechanic</option>
            <option value="Cabler (Data and Telecommunications)">Cabler (Data and Telecommunications)</option>
            <option value="Telecommunications Linesworker">Telecommunications Linesworker</option>
            <option value="Baker">Baker</option>
            <option value="Pastrycook">Pastrycook</option>
            <option value="Butcher or Smallgoods Maker">Butcher or Smallgoods Maker</option>
            <option value="Cook">Cook</option>
            <option value="Dog Handler or Trainer">Dog Handler or Trainer</option>
            <option value="Animal Attendants and Trainers (NEC)">Animal Attendants and Trainers (NEC)</option>
            <option value="Veterinary Nurse">Veterinary Nurse</option>
            <option value="Florist">Florist</option>
            <option value="Gardener (General)">Gardener (General)</option>
            <option value="Arborist">Arborist</option>
            <option value="Landscape Gardener">Landscape Gardener</option>
            <option value="Greenkeeper">Greenkeeper</option>
            <option value="Hairdresser">Hairdresser</option>
            <option value="Print Finisher">Print Finisher</option>
            <option value="Printing Machinist">Printing Machinist</option>
            <option value="Dressmaker or Tailor">Dressmaker or Tailor</option>
            <option value="Upholsterer">Upholsterer</option>
            <option value="Furniture Finisher">Furniture Finisher</option>
            <option value="Wood Machinist">Wood Machinist</option>
            <option value="Wood Machinists and Other Wood Trades Workers (NEC)">Wood Machinists and Other Wood Trades Workers (NEC)</option>
            <option value="Power Generation Plant Operator">Power Generation Plant Operator</option>
            <option value="Jeweller">Jeweller</option>
            <option value="Camera Operator (Film, Television or Video)">Camera Operator (Film, Television or Video)</option>
            <option value="Makeup Artist">Makeup Artist</option>
            <option value="Sound Technician">Sound Technician</option>
            <option value="Performing Arts Technicians (NEC)">Performing Arts Technicians (NEC)</option>
            <option value="Signwriter">Signwriter</option>
            <option value="Ambulance Officer">Ambulance Officer</option>
            <option value="Intensive Care Ambulance Paramedic">Intensive Care Ambulance Paramedic</option>
            <option value="Dental Technician">Dental Technician</option>
            <option value="Diversional Therapist">Diversional Therapist</option>
            <option value="Enrolled Nurse">Enrolled Nurse</option>
            <option value="Massage Therapist">Massage Therapist</option>
            <option value="Community Worker">Community Worker</option>
            <option value="Disabilities Services Officer">Disabilities Services Officer</option>
            <option value="Family Support Worker">Family Support Worker</option>
            <option value="Residential Care Officer">Residential Care Officer</option>
            <option value="Youth Worker">Youth Worker</option>
            <option value="Diving Instructor (Open Water)">Diving Instructor (Open Water)</option>
            <option value="Gymnastics Coach or Instructor">Gymnastics Coach or Instructor</option>
            <option value="Horse Riding Coach or Instructor">Horse Riding Coach or Instructor</option>
            <option value="Snowsport Instructor">Snowsport Instructor</option>
            <option value="Swimming Coach or Instructor">Swimming Coach or Instructor</option>
            <option value="Other Sports Coach or Instructor">Other Sports Coach or Instructor</option>
            <option value="Sports Development Officer">Sports Development Officer</option>
            <option value="Sportspersons (NEC)">Sportspersons (NEC)</option>
            <option value="Contract Administrator">Contract Administrator</option>
            <option value="Program or Project Administrator">Program or Project Administrator</option>
            <option value="Insurance Loss Adjuster">Insurance Loss Adjuster</option>
            <option value="Insurance Agent">Insurance Agent</option>
            <option value="Retail Buyer">Retail Buyer</option>
            <option value="Aquaculture Farmer">Aquaculture Farmer</option>
            <option value="Cotton Grower">Cotton Grower</option>
            <option value="Fruit or Nut Grower">Fruit or Nut Grower</option>
            <option value="Grain, Oilseed or Pasture Grower (Aus) / Field Crop Grower (NZ)">Grain, Oilseed or Pasture Grower (Aus) / Field Crop Grower (NZ)</option>
            <option value="Mixed Crop Farmer">Mixed Crop Farmer</option>
            <option value="Sugar Cane Grower">Sugar Cane Grower</option>
            <option value="Crop Farmers (NEC)">Crop Farmers (NEC)</option>
            <option value="Beef Cattle Farmer">Beef Cattle Farmer</option>
            <option value="Dairy Cattle Farmer">Dairy Cattle Farmer</option>
            <option value="Deer Farmer">Deer Farmer</option>
            <option value="Goat Farmer">Goat Farmer</option>
            <option value="Horse Breeder">Horse Breeder</option>
            <option value="Mixed Livestock Farmer">Mixed Livestock Farmer</option>
            <option value="Pig Farmer">Pig Farmer</option>
            <option value="Sheep Farmer">Sheep Farmer</option>
            <option value="Livestock Farmers (NEC)">Livestock Farmers (NEC)</option>
            <option value="Mixed Crop and Livestock Farmer">Mixed Crop and Livestock Farmer</option>
            <option value="Public Relations Manager">Public Relations Manager</option>
            <option value="Policy and Planning Manager">Policy and Planning Manager</option>
            <option value="Project Builder">Project Builder</option>
            <option value="Procurement Manager">Procurement Manager</option>
            <option value="Medical Administrator">Medical Administrator</option>
            <option value="Regional Education Manager">Regional Education Manager</option>
            <option value="Sports Administrator">Sports Administrator</option>
            <option value="Caravan Park and Camping Ground Manager">Caravan Park and Camping Ground Manager</option>
            <option value="Post Office Manager">Post Office Manager</option>
            <option value="Amusement Centre Manager">Amusement Centre Manager</option>
            <option value="Fitness Centre Manager">Fitness Centre Manager</option>
            <option value="Sports Centre Manager">Sports Centre Manager</option>
            <option value="Cinema or Theatre Manager">Cinema or Theatre Manager</option>
            <option value="Financial Institution Branch Manager">Financial Institution Branch Manager</option>
            <option value="Human Resource Adviser">Human Resource Adviser</option>
            <option value="Workplace Relations Adviser">Workplace Relations Adviser</option>
            <option value="Policy Analyst">Policy Analyst</option>
            <option value="Liaison Officer">Liaison Officer</option>
            <option value="Market Research Analyst">Market Research Analyst</option>
            <option value="Aeroplane Pilot">Aeroplane Pilot</option>
            <option value="Flying Instructor">Flying Instructor</option>
            <option value="Helicopter Pilot">Helicopter Pilot</option>
            <option value="Ship's Master">Ship's Master</option>
            <option value="Multimedia Designer">Multimedia Designer</option>
            <option value="Winemaker">Winemaker</option>
            <option value="Conservation Officer">Conservation Officer</option>
            <option value="Exercise Physiologist">Exercise Physiologist</option>
            <option value="Vocational Education Teacher">Vocational Education Teacher</option>
            <option value="Environmental Health Officer">Environmental Health Officer</option>
            <option value="Dentist">Dentist</option>
            <option value="Anaesthetist">Anaesthetist</option>
            <option value="Intellectual Property Lawyer">Intellectual Property Lawyer</option>
            <option value="Translator">Translator</option>
            <option value="Community Arts Worker">Community Arts Worker</option>
            <option value="Agricultural Technician">Agricultural Technician</option>
            <option value="Operating Theatre Technician">Operating Theatre Technician</option>
            <option value="Pathology Collector">Pathology Collector</option>
            <option value="Construction Estimator">Construction Estimator</option>
            <option value="Surveying or Spatial Science Technician">Surveying or Spatial Science Technician</option>
            <option value="Mechanical Engineering Draftsperson">Mechanical Engineering Draftsperson</option>
            <option value="Safety Inspector">Safety Inspector</option>
            <option value="Maintenance Planner">Maintenance Planner</option>
            <option value="Building and Engineering Technicians (NEC)">Building and Engineering Technicians (NEC)</option>
            <option value="Vehicle Painter">Vehicle Painter</option>
            <option value="Floor Finisher">Floor Finisher</option>
            <option value="Electrical Linesworker">Electrical Linesworker</option>
            <option value="Zookeeper">Zookeeper</option>
            <option value="Nurseryperson">Nurseryperson</option>
            <option value="Gas or Petroleum Operator">Gas or Petroleum Operator</option>
            <option value="Dental Hygienist">Dental Hygienist</option>
            <option value="Dental Therapist">Dental Therapist</option>
            <option value="Emergency Service Worker">Emergency Service Worker</option>
            <option value="Driving Instructor">Driving Instructor</option>
            <option value="Funeral Workers (NEC)">Funeral Workers (NEC)</option>
            <option value="Flight Attendant">Flight Attendant</option>
            <option value="First Aid Trainer">First Aid Trainer</option>
            <option value="Jockey">Jockey</option>
            <option value="Clinical Coder">Clinical Coder</option>
            <option value="Property Manager">Property Manager</option>
            <option value="Real Estate Representative">Real Estate Representative</option>
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
        var agepoints = 0;
        var finalpoints = 0;
        var education_level = 0;
        var degree = 0;

        $("#education_level").change(function()
        {
          updatePoints();
      }); 

        $("#age_range").change(function()
        {  
          updatePoints();
      }); 

        $("#degree_from_australia").change(function()
        { 
          updatePoints();
      });  

        $("#regional_study").change(function()
        { 
          updatePoints();
      });

        $("#masters_degree").change(function()
        { 
          updatePoints();
      });

        $("#nominated_occupation_experience").change(function()
        { 
          updatePoints();
      });

        $("#nominated_experience").change(function()
        { 
          updatePoints();
      });

        $("#english_ability").change(function()
        { 
          updatePoints();
      }); 

        $("#partner_skills").change(function()
        { 
          updatePoints();
      });

        $("#naati_accreditation").change(function()
        { 
          updatePoints();
      }); 

        $("#professional_program").change(function()
        { 
          updatePoints();
      });

        $("#visa_subclass").change(function()
        { 
          updatePoints();
      }); 

        function updatePoints()
        { 
          finalpoints = 0;  
          var ageValue = $('#age_range').find(":selected").val();
          if(ageValue == '18_to_24')
          {
            agepoints = 25;
        }
        else if(ageValue == '25_to_32')
        {
            agepoints = 30;
        }
        else if(ageValue == '33_to_39')
        {
         agepoints = 25;
     }
     else if(ageValue == '40_to_44')
     {
         agepoints = 15;
     }
     else
     {
         agepoints = 0;
     }

     var VisaType = $('#visa_subclass').find(":selected").val();
     if(VisaType == 'subclass_189')
     {
        VisaPoints = 0;
    }
    else if(VisaType == 'subclass_190')
    {
        VisaPoints = 5;
    }
    else if(VisaType == 'subclass_491')
    {
     VisaPoints = 15;
 }
 else
 {
     VisaPoints = 0;
 }

 var nomValue = $('#nominated_occupation_experience').find(":selected").val();
 if(nomValue == '8_years_more')
 {
    nompoints = 15;
}
else if(nomValue == '5_years_more')
{
    nompoints = 10;
}
else if(nomValue == '3_years_more')
{
 nompoints = 05;
}
else if(nomValue == 'less_than_3_years')
{
 nompoints = 0;
}
else
{
 nompoints = 0;
}

var nomeValue = $('#nominated_experience').find(":selected").val();
if(nomeValue == '8_years_more')
{
    nomepoints = 20;
}
else if(nomeValue == '5_years_more')
{
    nomepoints = 15;
}
else if(nomeValue == '3_years_more')
{
 nomepoints = 10;
}
else if(nomeValue == '1_year_more')
{
 nomepoints = 5;
}
else
{
 nomepoints = 0;
}

var eduValue = $('#education_level').find(":selected").val();
if(eduValue == 'phd')
{
    education_level = 20;
}
else if(eduValue == 'bachelor')
{
    education_level = 15;
}
else if(eduValue == 'diploma')
{
 education_level = 10;
}
else if(eduValue == 'no_qualification')
{
 education_level = 0;
}
else
{
 education_level = 0;
}

var degreeValue = $('#degree_from_australia').find(":selected").val();
if(degreeValue == 'yes')
{
    degree = 5;
}
else if(degreeValue == 'no')
{
    degree = 0;
}
else
{
 degree = 0;
}

var englishValue = $('#english_ability').find(":selected").val();
if(englishValue == 'competent')
{
    engpoint = 0;
}
else if(englishValue == 'proficient')
{
    engpoint = 10;
}
else if(englishValue == 'superior')
{
    engpoint = 20;
}
else
{
 engpoint = 0;
}

var englishPValue = $('#partner_skills').find(":selected").val();
if(englishPValue == 'meet_all_criteria')
{
    engPpoint = 10;
}
else if(englishPValue == 'competent_english')
{
    engPpoint = 5;
}
else if(englishPValue == 'australian_citizen')
{
    engPpoint = 10;
}
else
{
 engPpoint = 0;
}

var regValue = $('#regional_study').find(":selected").val();
if(regValue == 'yes')
{
    regpoint = 5;
}
else if(regValue == 'no')
{
    regpoint = 0;
}
else
{
 regpoint = 0;
} 

var programValue = $('#professional_program').find(":selected").val();
if(programValue == 'yes')
{
    programpoint = 5;
}
else if(programValue == 'no')
{
    programpoint = 0;
}
else
{
 programpoint = 0;
}  

var naatiValue = $('#naati_accreditation').find(":selected").val();
if(naatiValue == 'yes')
{
    naatipoint = 5;
}
else if(naatiValue == 'no')
{
    naatipoint = 0;
}
else
{
 naatipoint = 0;
}  

var masterValue = $('#masters_degree').find(":selected").val();
if(masterValue == 'yes')
{
    masterpoint = 10;
}
else if(masterValue == 'no')
{
    masterpoint = 0;
}
else
{
 masterpoint = 0;
}  

finalpoints = agepoints + education_level + degree + regpoint + masterpoint + nompoints + nomepoints + engpoint + engPpoint + naatipoint + programpoint + VisaPoints;
      //alert(finalpoints);
$( "#points" ).html(finalpoints);
$("#calculation").val(finalpoints);
}

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