<?php echo form_open(get_uri("check_in/save"), array("id" => "attendance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="account_type" class="<?php echo $label_column; ?>">Consultation Type</label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_dropdown("account_type", $account_types_filter_dropdown, $client_info->account_type, 'class="form-control select2" id="account_type" data-rule-required="true" data-msg-required="' . app_lang("field_required") . '"');
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="student-onshore" class="<?php echo $label_column; ?>"><?php echo app_lang('client_onshore'); ?></label>
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
                        $model_info->student_onshore,
                        "class='form-control validate-hidden' id='student-onshore' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"
                    );
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
        <div class="form-group" id="location-id-field">
            <div class="row">
                <label for="checkin_for" class="<?php echo $label_column; ?>"><?php echo app_lang('checkin_for'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    $list = array(
                        '' => '-',
                        'existing' => 'Existing Client',
                        'new' => 'New Client'
                    );
                    echo form_dropdown(
                        'checkin_for',
                        $list,
                        $model_info->checkin_for,
                        'class="form-control select2 validate-hidden" id="checkin_for" data-rule-required="true" data-msg-required="' . app_lang("field_required") . '"',
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group d-none" id="select-client-field">
            <div class="row">
                <label for="client_id" class="<?php echo $label_column; ?>"><?php echo app_lang('select_client'); ?></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "client_id",
                        "name" => "client_id",
                        'value' => $model_info->client_id ? $model_info->client_id : '',
                        "class" => "form-control validate-hidden",
                        'placeholder' => app_lang('client'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="d-none" id="client-info-container">
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
                            "data-msg-required" => app_lang("field_required")
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
                        <p class="text-danger d-none" id="email-alert-cont"></p>
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
                                isset($client_info->phone_code) && $client_info->phone_code ? $client_info->phone_code : '',
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

            <div class="form-group">
                <div class="row">
                    <label for="date_of_birth" class="<?php echo $label_column; ?>"><?php echo app_lang('date_of_birth'); ?></label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(array(
                            "id" => "date_of_birth",
                            "name" => "date_of_birth",
                            "class" => "form-control",
                            'value' => $client_info->date_of_birth ? $client_info->date_of_birth : '',
                            'placeholder' => app_lang('date_of_birth'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="form-group">
            <div class="row">
                <label for="visa_type" class="<?php echo $label_column; ?>"><?php echo app_lang('visa_type'); ?>
                    <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
                </label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "visa_type",
                        "name" => "visa_type",
                        "value" => isset($model_info->visa_type) ? $model_info->visa_type : '',
                        "class" => "form-control validate-hidden",
                        "placeholder" => app_lang('visa_type'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group" id="visa_2_field">
            <div class="row">
                <label for="visa_2" class="<?php echo $label_column; ?>">Visa Applied For</label>
                <div class=" <?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "visa_2",
                        "name" => "visa_2",
                        "value" => isset($model_info->visa_2) ? $model_info->visa_2 : '',
                        "class" => "form-control",
                        "placeholder" => 'Visa Applied For',
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="visa_expiry" class="<?php echo $label_column; ?> visa_expiry"><?php echo app_lang('visa_expiry'); ?></label>
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

        $("#visa_2_field").hide();

        setDatePicker("#visa_expiry,#date_of_birth");

        handleCheckInFor("#checkin_for");

        $('#checkin_for').select2().on('change', function() {
            handleCheckInFor(this);
        });

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

    });

    $(".select2").select2();

    var originalEmail = '<?php echo $model_info->email; ?>',
        isEditing = '<?php echo $model_info->email; ?>' ? true : false;

    $("#email").on('keyup', function() {
        if ($(':button[type="submit"]').is(":disabled")) {
            // $(':button[type="submit"]').prop("disabled", false);
            $("#email-alert-cont").addClass("d-none").html("");
        }
    });

    $("#email").on('blur', function() {
        if (!isEditing || (isEditing && originalEmail && $(this).val() !== originalEmail)) {
            $(':button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: "<?php echo get_uri("clients/validate_email"); ?>",
                data: {
                    email: $(this).val()
                },
                cache: false,
                type: 'POST',
                dataType: "json",
                success: function(response) {
                    if (response && response.success) {
                        $("#email-alert-cont").removeClass("d-none").html(response.message);
                    } else if (response) {
                        $(':button[type="submit"]').prop("disabled", false);
                        $("#email-alert-cont").addClass("d-none");
                    }
                }
            });
        }
    });

    function handleCheckInFor(el) {
        var value = $(el).val();

        var clientIdSelect = $('#client_id'),
            selectedClientData = <?php echo isset($selected_client) ? json_encode(array('success' => true, 'data' => $selected_client)) : json_encode(array('success' => false)); ?>;

        if (value == 'new') {
            $('#client-info-container').removeClass('d-none');
            $('#select-client-field').addClass('d-none');
            clientIdSelect.select2("destroy").removeClass('validate-hidden').removeAttr("data-rule-required").removeAttr("data-msg-required");
        } else if (value == 'existing') {
            $('#client-info-container').addClass('d-none');
            $('#select-client-field').removeClass('d-none');

            clientIdSelect.addClass('validate-hidden')
                .attr("data-rule-required", true)
                .attr("data-msg-required", "<?php echo app_lang('field_required') ?>")
                .select2({
                    showSearchBox: true,
                    ajax: {
                        url: "<?php echo get_uri("check_in/get_client_suggestion"); ?>",
                        type: 'POST',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function(term, page) {
                            return {
                                q: term // search term
                            };
                        },
                        results: function(data, page) {
                            return {
                                results: data
                            };
                        }
                    }
                });

            if (selectedClientData.success) {
                clientIdSelect.select2('data', selectedClientData.data);
            }
        } else {
            $('#client-info-container').addClass('d-none');
            $('#select-client-field').addClass('d-none');
        }
    }

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