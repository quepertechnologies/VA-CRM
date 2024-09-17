<style type="text/css">
    .client-info-section .form-group {
        margin: 25px 0px 0px 0px;
    }
</style>

<div id="page-content" class="page-wrapper clearfix">
    <div id="public-check-in-container">
        <?php
        echo form_open(get_uri("public_forms/save_public_check_in"), array("id" => "public-check-in-form", "class" => "general-form", "role" => "form"));
        ?>
        <input type="hidden" name="status" value="Waiting">
        <input type="hidden" name="account_type" value="1">
        <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
        <input type="hidden" name="assignee" value="<?php echo $assignee; ?>">

        <div id="public-check-in-preview" class="card  p15 no-border clearfix post-dropzone" style="max-width: 1000px; margin: auto;">

            <h3 id="public-check-in-title" class=" pl10 pr10">Check In</h3>

            <div class="pl10 pr10"><?php echo $form_description; ?></div>
            <div class=" pt10 mt15">
                <!-- CLIENT FIELDS -->
                <div class="client-info-section row">
                    <div class="form-group col-md-12">
                        <label for="student-onshore"><?php echo app_lang('client_onshore_question'); ?></label>
                        <div>
                            <?php
                            $list = array(
                                '' => '-',
                                '0' => 'No',
                                '1' => "Yes"
                            );
                            echo form_dropdown(
                                'student_onshore',
                                $list,
                                '',
                                "class='form-control validate-hidden' id='student-onshore' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="first-name-field">
                        <label for="first_name"><?php echo app_lang('first_name'); ?></label>
                        <div>
                            <?php
                            echo form_input(array(
                                "id" => "first_name",
                                "name" => "first_name",
                                'value' => '',
                                "class" => "form-control",
                                'placeholder' => app_lang('first_name'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="last-name-field">
                        <label for="last_name"><?php echo app_lang('last_name'); ?></label>
                        <div>
                            <?php
                            echo form_input(array(
                                "id" => "last_name",
                                "name" => "last_name",
                                'value' => '',
                                "class" => "form-control",
                                'placeholder' => app_lang('last_name'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="email-field">
                        <label for="email"><?php echo app_lang('email'); ?></label>
                        <div>
                            <?php
                            echo form_input(array(
                                "id" => "email",
                                "name" => "email",
                                'value' => '',
                                "class" => "form-control",
                                'placeholder' => app_lang('email'),
                                'type' => 'email',
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                            <p class="text-danger d-none" id="email-alert-cont"></p>
                        </div>
                    </div>
                    <div class="form-group col-md-6"></div>
                    <div class="form-group col-md-6">
                        <label for="phone" class="<?php echo $label_column; ?> phone_section"><?php echo app_lang('contact_number'); ?></label>
                        <div>
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
                                    '',
                                    "class='form-control select2' id='select-phone-code'"
                                );
                                ?>
                            </div>
                            <?php
                            echo form_input(array(
                                "id" => "phone",
                                "name" => "phone",
                                "value" => '',
                                "class" => "form-control company_name_input_section",
                                "placeholder" => app_lang('contact_number'),
                                "type" => 'number',
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="visit_purpose"><?php echo app_lang('visit_purpose'); ?></label>
                        <div>
                            <?php
                            echo form_textarea(array(
                                "id" => "visit_purpose",
                                "name" => "note",
                                'value' => '',
                                "class" => "form-control company_name_input_section",
                                "placeholder" => app_lang('visit_purpose'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="visa_type"><?php echo app_lang('visa_type'); ?>
                            <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
                        </label>
                        <div>
                            <?php
                            echo form_input(array(
                                "id" => "visa_type",
                                "name" => "visa_type",
                                "value" => '',
                                "class" => "form-control validate-hidden",
                                "placeholder" => app_lang('visa_type'),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="visa_2_field">
                        <label for="visa_2">Visa Applied For</label>
                        <div class=" <?php echo $field_column; ?>">
                            <?php
                            echo form_input(array(
                                "id" => "visa_2",
                                "name" => "visa_2",
                                "value" => '',
                                "class" => "form-control",
                                "placeholder" => 'Visa Applied For',
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="visa_expiry" class="<?php echo $label_column; ?> visa_expiry"><?php echo app_lang('visa_expiry'); ?></label>
                        <div>
                            <?php
                            echo form_input(array(
                                "id" => "visa_expiry",
                                "name" => "visa_expiry",
                                "value" => '',
                                "class" => "form-control company_name_input_section",
                                "placeholder" => app_lang('visa_expiry'),
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p15">
                <button type="submit" class="btn btn-primary"><i data-feather="send" class="icon-16"></i> <?php echo app_lang('request_an_estimate'); ?></button>
            </div>
        </div>

        <?php
        echo form_close();
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {


        $("#public-check-in-form").appForm({
            isModal: false,
            onSubmit: function() {
                appLoader.show();
                $("#public-check-in-form").find('[type="submit"]').attr('disabled', 'disabled');
            },
            onSuccess: function(result) {
                appLoader.hide();
                $("#public-check-in-container").html("");
                appAlert.success(result.message, {
                    container: "#public-check-in-container",
                    animate: false
                });
                $('.scrollable-page').scrollTop(0); //scroll to top
            }
        });

        $("#visa_2_field").hide();

        setDatePicker("#visa_expiry");

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

        $('#account_type').select2().on('change', function() {
            value = $(this).val();
            if (value == 4) {
                $('#company_field').show();
                $('#heading').show();
            } else {
                $('#company_field').hide();
                $('#heading').hide();
            }
        });

        $(".select2").select2();

    });
</script>