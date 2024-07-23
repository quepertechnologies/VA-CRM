<div class="form-group">
    <div class="col-sm-12">
        <div id="file-upload-dropzone" class="dropzone mb15">

        </div>
        <div id="file-upload-dropzone-scrollbar">
            <div id="uploaded-file-previews">
                <div id="file-upload-row" class="box">
                    <div class="preview box-content pr15" style="width:100px;">
                        <img data-dz-thumbnail class="upload-thumbnail-sm" />
                        <div class="progress upload-progress-sm active mt5" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="box-content">
                        <p class="name" data-dz-name></p>
                        <p class="clearfix">
                            <span class="size float-start" data-dz-size></span>
                            <span data-dz-remove class="btn btn-default btn-sm border-circle float-end mr10 fs-14 margin-top-5">
                                <i data-feather="x" class="icon-16"></i>
                            </span>
                        </p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                        <input class="file-count-field" type="hidden" name="files[]" value="" />

                        <?php

                        $list = array();
                        $account_types = array(1, 2, 3, 4);
                        if (
                            (
                                isset($account_type) &&
                                (
                                    in_array((int)$account_type, $account_types)
                                )
                            )
                        ) {
                            $list = array(
                                // student
                                "Passport",
                                "Visa application form",
                                "Letter of acceptance from the educational institution",
                                "Proof of financial ability (bank statements, scholarship letters, etc.)",
                                "Proof of English proficiency (IELTS, TOEFL scores)",
                                "Health insurance",
                                "Proof of accommodation",
                                "Passport-sized photographs",
                                "Academic transcripts and certificates",
                                "Statement of Purpose (SOP)",
                                "Visa application fee payment receipt",
                                "Proof of payment for the first semester or year of tuition fees.",
                                "Visa application cover letter.",
                                "Additional documents required by the specific country's immigration department.",
                                "Vaccination records as per the destination country's requirements.",
                                "Resume or Curriculum Vitae (CV).",
                                "Letters of recommendation.",
                                "Passport Photograph",
                                "Profile Image (Avatar)",
                                // agent
                                "License or registration certificate as a migration agent",
                                "Professional indemnity insurance",
                                "Evidence of continuing professional development (CPD)",
                                "Client agreements and contracts",
                                "Visa application forms and supporting document(s) for each client",
                                "Documentation related to client representation",
                                "Fee payment record(s)",
                                "Any correspondence with immigration authorities",
                                "Case management record(s)",
                                "Certificates or records of attendance for workshops, seminars, or training programs related to migration laws and procedures.",
                                "Copies of emails, letters, or any other communication with clients.",
                                "Evidence of memberships in professional organizations related to migration.",
                                "Regularly updated knowledge of changes in immigration policies and laws.",
                                "Profile Image (Avatar)",
                                "Educational Certificate(s)",
                                "Professional Certification(s)",
                                "Proof of Financial Capability",
                                "Health Examination Report",
                                "Police Clearance Certificate",
                                "Proof of accommodation",
                                "Travel Itinerary",
                                "Insurance Coverage",
                                "Language Proficiency Test Result(s)",
                                "Any Other Country-Specific Requirement(s)",
                                // organization
                                "Business registration document(s)",
                                "Australian Business Number (ABN) or equivalent document",
                                "Financial statement(s)",
                                "Balance Sheet",
                                "Job descriptions for positions to be filled",
                                "Job letter for Visa",
                                "Sample Employment contract(s)",
                                "Evidence of the need for foreign workers",
                                "Sponsorship approval letter(s)",
                                "Nomination approval letter(s)",
                                "Records of compliance with sponsorship obligations",
                                "Training benchmarks evidence",
                                "Business financial statements for the past few years",
                                "Proof of financial ability",
                                "Tax returns and compliance record(s)",
                                "Evidence of the organization's commitment to training local employees",
                                "Copy/Copies of advertisements for job positions to demonstrate attempts to hire locally before sponsoring foreign workers",
                                "Company Logo",
                                "Company Symbol",
                                "Company Stamp",
                                "Signature",
                                "Passport-sized Photograph(s) of employees",
                                "Approval from Local Authorities",
                                "Educational and Professional Certificate(s)",
                                "Proof of accommodation",
                                "Health Insurance Coverage",
                                "Tax Documents",
                                "Visa Fee Payment Receipt",
                                "Local Sponsorship Letter",
                                "Medical Examination Report",
                                "Any Additional Country-Specific Requirements",
                                "Relocation Support",
                                "Background Checks",
                                "Reference Checks",
                                "Labor Market Impact Assessment (LMIA)",
                                "Employee Handbook or Policies",
                                // partner
                                "Passport",
                                "Visa application form",
                                "Marriage certificate or evidence of the relationship",
                                "Proof of genuine and ongoing relationship (photos, joint financial documents, communication records)",
                                "Statutory declarations from friends and family supporting the relationship",
                                "Police clearance certificates",
                                "Health examination document(s)",
                                "Proof of financial ability",
                                "Passport-sized photograph(s)",
                                "Visa application fee payment receipt",
                                "Joint bank account statements.",
                                "Evidence of shared assets or responsibilities.",
                                "Correspondence history (emails, letters, chat logs).",
                                "Statutory declarations from friends, family, or acquaintances supporting the genuineness of the relationship.",
                                "Evidence of joint travels and vacations.",
                                "Photograph(s) together at various events and occasions.",
                                "Call logs, chat logs, or other communication records to demonstrate regular contact.",
                                // project files
                                "Visa Refusal Letter",
                                "Experience Letter",
                                "Academic Certificates",
                                "Citizenship",
                                "ROI Lodged Snip-shot and send it to client",
                                "EOI Lodged Evidence and the EOI Points Breakdown",
                                "Payment",
                                "Draft Application For Review",
                                "15 Work Photos and 5/10 Videos 2 mins long",
                                "Skill Assessment Lodged Snip-shot as Proof",
                                "Check for invoice and ask for Due Money",
                                "Skill Assessment Outcome",
                                "Signed Cost Agreement",
                                "Signed 956 Form",
                                "Bridging Visa Grant or Visa Grant",
                                "HAP ID (from immi portal)",
                                "Payment Receipt",
                                "Tax Invoice",
                                "Signed Offer Letter",
                                "Health Insurance (OSHC)",
                                "Travel History with Dates",
                                "First Arrival Date",
                                "Travel Stamps on the Passport",
                                "GTE Letter",
                                "Financial Document",
                                "Overseas Police Clearance",
                                "Resume / CV",
                                "Birth Certificate",
                                "Proof of Relationship",
                                "Naati Certificate",
                                "Professional Year Program Certificate",
                                "Skill Assessment",
                                "Payslip (at least 3-4)",
                                "RPL of address (Utility Bill / Photo ID / License / Bank Statement)",
                                "USI Number",
                                "Bank Statement",
                                "Marriage Certificate",
                                "Health Insurance OVHC (I will help to organize this)",
                                "English Language Test Scores (IELTS / PTE / TOEFL)",
                                "Overseas Academic Certificate",
                                "Visa Grant Letter",
                                "Australian Federal Police Certificate",
                                "Offer Letter",
                                "CoE",
                                "Salary Statement",
                                "Work Reference Letter",
                                "Transcripts",
                                "Australian Academic Certificates (Completion Letter)",
                                "Passport",
                                "National Identity Card (Citizenship)",
                            );

                            $list = array_unique($list);
                            asort($list);
                            $list['Other Document'] = 'Other Document';

                            $_list = array();
                            foreach ($list as $item) {
                                $_list[] = array('id' => $item, 'text' => $item);
                            }
                            $list = $_list;
                        } elseif (isset($doc_check_list) && count($doc_check_list)) {
                            $list = $doc_check_list;

                            asort($list);

                            $_list = array();
                            foreach ($list as $id => $text) {
                                $_list[] = array('id' => $id, 'text' => $text);
                            }
                            $_list[] = array('id' => 0, 'text' => 'Other Document');
                            $list = $_list;
                        }

                        if (!isset($hide_description)) {

                            if (count($list)) {

                                $extra = "class='form-control description-field' placeholder='" . (isset($description_placeholder) ? $description_placeholder : app_lang("description")) . "' data-rule-required='" . (isset($description_required) ? true : "false") . "' data-msg-required='" . app_lang("field_required") . "'";
                                echo form_input(
                                    'description_field',
                                    '',
                                    $extra
                                );
                            } else {
                                $extra = "class='form-control description-field' placeholder='" . (isset($description_placeholder) ? $description_placeholder : app_lang("description")) . "' data-rule-required='" . (isset($description_required) ? true : "false") . "' data-msg-required='" . app_lang("field_required") . "'";
                                echo form_datalist(
                                    'description_field',
                                    '',
                                    $list,
                                    $extra
                                );
                            }
                            // echo form_input(array(
                            //     "class" => "form-control description-field",
                            //     "placeholder" => isset($description_placeholder) ? $description_placeholder : app_lang("description"),
                            //     "data-rule-required" => isset($description_required) ? true : "false",
                            //     "data-msg-required" => app_lang("field_required"),
                            // ));
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        fileSerial = 0;

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#file-upload-row");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var dropzoneId = "#file-upload-dropzone";

        var disableButtonType = '[type="submit"]';
        <?php if (isset($disable_button_type)) { ?>
            disableButtonType = '[type="button"]';
        <?php } ?>

        var maxFiles = 1000;
        <?php if (isset($max_files)) { ?>
            maxFiles = <?php echo $max_files; ?>;
        <?php } ?>

        var projectFilesDropzone = new Dropzone(dropzoneId, {
            url: "<?php echo $upload_url; ?>",
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            maxFilesize: 3000,
            previewTemplate: previewTemplate,
            dictDefaultMessage: '<?php echo app_lang("file_upload_instruction"); ?>',
            autoQueue: true,
            previewsContainer: "#uploaded-file-previews",
            clickable: true,
            maxFiles: maxFiles,
            timeout: 20000000, //20000 seconds
            sending: function(file, xhr, formData) {
                formData.append(AppHelper.csrfTokenName, AppHelper.csrfHash);
            },
            accept: function(file, done) {

                if (file.name.length > 200) {
                    done("Filename is too long.");
                    $(file.previewTemplate).find(".description-field").remove();
                }

                //validate the file?
                $.ajax({
                    url: "<?php echo $validation_url; ?>",
                    data: {
                        file_name: file.name,
                        file_size: file.size
                    },
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            fileSerial++;
                            $(file.previewTemplate).find(".description-field").attr("name", "description_" + fileSerial);
                            $(file.previewTemplate).append('<input type="hidden" name="file_name_' + fileSerial + '" value="' + file.name + '" />\n\
                                <input type="hidden" name="file_size_' + fileSerial + '" value="' + file.size + '" />');
                            $(file.previewTemplate).find(".file-count-field").val(fileSerial);

                            <?php if (
                                (
                                    isset($account_type) &&
                                    (
                                        in_array((int)$account_type, $account_types)
                                    )
                                ) ||
                                isset($doc_check_list) && count($doc_check_list)
                            ) { ?>
                                $('[name=description_' + fileSerial + ']').select2({
                                    data: <?php echo json_encode($list); ?>
                                });
                            <?php } elseif (isset($doc_check_list_item) && isset($doc_check_list_id)) { ?>
                                $('[name=description_' + fileSerial + ']').attr('readonly', true).select2({
                                    data: <?php
                                            $list = array(array('id' => $doc_check_list_id, 'text' => $doc_check_list_item));
                                            echo json_encode($list);
                                            ?>
                                });
                                $('[name=description_' + fileSerial + ']').select2('val', <?php echo $doc_check_list_id; ?>);
                            <?php }  ?>

                            done();
                        } else {
                            $(file.previewTemplate).find("input").remove();
                            done(response.message);
                        }
                    }
                });
            },
            processing: function() {
                $(dropzoneId).closest('form').find(disableButtonType).prop("disabled", true);

                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            },
            queuecomplete: function() {
                $(dropzoneId).closest('form').find(disableButtonType).prop("disabled", false);
            },
            fallback: function() {
                //add custom fallback;
                $("body").addClass("dropzone-disabled");
                $(dropzoneId).closest('form').find(disableButtonType).removeAttr('disabled');

                $("#file-upload-dropzone").hide();
                $(dropzoneId).closest('form').find(".modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default float-start'><i data-feather='plus-circle' class='icon-16'></i> " + "<?php echo app_lang("add_more"); ?>" + "</button>");

                $(dropzoneId).closest('form').find(".modal-footer").on("click", "#add-more-file-button", function() {

                    var descriptionDom = "<div class='mb5 pb5'><input class='form-control description-field'  name='description[]'  type='text' style='cursor: auto;' placeholder='<?php echo app_lang("description") ?>' /></div>";
                    <?php if (isset($hide_description)) { ?>
                        descriptionDom = "";
                    <?php } ?>

                    var newFileRow = "<div class='file-row pb10 pt10 b-b mb10'>" +
                        "<div class='pb10 clearfix '><button type='button' class='btn btn-xs btn-danger float-start mr10 remove-file'><i data-feather='x' class='icon-16'></i></button> <input class='float-start' type='file' name='manualFiles[]' /></div>" +
                        descriptionDom +
                        "</div>";
                    $("#uploaded-file-previews").prepend(newFileRow);
                });
                $("#add-more-file-button").trigger("click");
                $("#uploaded-file-previews").on("click", ".remove-file", function() {
                    $(this).closest(".file-row").remove();
                });
            },
            success: function(file) {
                setTimeout(function() {
                    $(file.previewElement).find(".progress-bar-striped").removeClass("progress-bar-striped progress-bar-animated");
                }, 1000);
            }
        });

        document.querySelector(".start-upload").onclick = function() {
            projectFilesDropzone.enqueueFiles(projectFilesDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector(".cancel-upload").onclick = function() {
            projectFilesDropzone.removeAllFiles(true);
        };
        initScrollbar("#file-upload-dropzone-scrollbar", {
            setHeight: 280
        });

    });
</script>