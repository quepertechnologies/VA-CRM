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
<div class="form-group">
    <div class="row">
        <label for="client_id" class="<?php echo $label_column; ?> client_id_section"><?php echo app_lang('client_id'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "unique_id",
                "name" => "unique_id",
                "value" => isset($model_info->unique_id) && !empty($model_info->unique_id) ? $model_info->unique_id : uniqid('VA' . date('-y-')),
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
        <label for="first_name" class="<?php echo $label_column; ?>"><?php echo app_lang('first_name'); ?></label>
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
        <label for="last_name" class="<?php echo $label_column; ?>"><?php echo app_lang('last_name'); ?></label>
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
        <label for="preferred_name" class="<?php echo $label_column; ?>"><?php echo app_lang('preferred_name'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "preferred_name",
                "name" => "preferred_name",
                "value" => isset($model_info->preferred_name) ? $model_info->preferred_name : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => app_lang('preferred_name'),
                "autofocus" => true,
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
                "value" => isset($model_info->date_of_birth) ? $model_info->date_of_birth : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => app_lang('date_of_birth'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label for="country_of_birth" class="<?php echo $label_column; ?>"><?php echo app_lang('country_of_birth'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "country_of_birth",
                "name" => "country",
                "value" => isset($model_info->country) ? $model_info->country : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => app_lang('country_of_birth'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label for="country_of_citizenship" class="<?php echo $label_column; ?>">Country of Citizenship</label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "country_of_citizenship",
                "name" => "country_of_citizenship",
                "value" => isset($model_info->country_of_citizenship) ? $model_info->country_of_citizenship : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => 'Country of Citizenship',
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label for="country_of_residence" class="<?php echo $label_column; ?>">Country of Residence</label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "country_of_residence",
                "name" => "country_of_residence",
                "value" => isset($model_info->country_of_residence) ? $model_info->country_of_residence : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => 'Country of Residence',
                "autofocus" => true,
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
        <label for="zip" class="<?php echo $label_column; ?>"><?php echo app_lang('post_code'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "zip",
                "name" => "zip",
                "value" => $model_info->zip,
                "class" => "form-control",
                "placeholder" => app_lang('post_code')
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="address" class="<?php echo $label_column; ?>"><?php echo app_lang('address_line_1'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_textarea(array(
                "id" => "address",
                "name" => "address",
                "value" => $model_info->address ? $model_info->address : "",
                "class" => "form-control",
                "placeholder" => app_lang('address_line_1')
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="address" class="<?php echo $label_column; ?>"><?php echo app_lang('address_line_2'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_textarea(array(
                "id" => "address_2",
                "name" => "address_2",
                "value" => isset($model_info->address_2) ? $model_info->address_2 : "",
                "class" => "form-control",
                "placeholder" => app_lang('address_line_2')
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="email" class="<?php echo $label_column; ?>"><?php echo app_lang('email'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "value" => isset($model_info->email) ? $model_info->email : '',
                "class" => "form-control",
                "placeholder" => app_lang('email'),
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>
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
        <label for="phone" class="<?php echo $label_column; ?> phone_section"><?php echo app_lang('phone'); ?></label>
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
                "placeholder" => app_lang('phone'),
                "autofocus" => true,
                "type" => 'number',
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="landline" class="<?php echo $label_column; ?>"><?php echo app_lang('landline'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "landline",
                "name" => "landline",
                "value" => isset($model_info->landline) ? $model_info->landline : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => app_lang('landline'),
                "autofocus" => true,
                "type" => 'number',
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="gender" class="<?php echo $label_column; ?>"><?php echo app_lang('gender'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "gender",
                "name" => "gender",
                "value" => isset($model_info->gender) ? $model_info->gender : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => app_lang('gender'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="marriage_status" class="<?php echo $label_column; ?>"><?php echo app_lang('marriage_status'); ?></label>
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
                "class='select2' placeholder='" . app_lang('marital_status') . "'"
            );
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="source" class="<?php echo $label_column; ?> source_section"><?php echo app_lang('source'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            $list = array(
                'Search Engine (e.g., Google, Bing)',
                'Facebook',
                'Instagram',
                'X (Twitter)',
                'LinkedIn',
                'Pinterest',
                'Snapchat',
                'TikTok',
                'YouTube',
                'Reddit',
                'WhatsApp',
                'Quora',
                'WeChat',
                'Telegram',
                'Referral from a Friend or Family Member',
                'Online Advertisement',
                'Printed Advertisement (Magazine, Newspaper, Brochure)',
                'Event or Conference',
                'Educational Institution',
                'Employment Agency',
                'News Article or Blog'
            );
            asort($list);
            echo form_datalist(
                'source',
                isset($model_info->source) ? $model_info->source : '',
                $list,
                "class='form-control company_name_input_section' placeholder='" . app_lang('source') . "'"
            );
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
<div class="form-group">
    <div class="row">
        <label for="areas_of_interest" class="<?php echo $label_column; ?>"><?php echo app_lang('areas_of_interest'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "areas_of_interest",
                "name" => "areas_of_interest",
                "value" => isset($model_info->areas_of_interest) ? $model_info->areas_of_interest : '',
                "class" => "form-control company_name_input_section",
                "placeholder" => app_lang('areas_of_interest'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
</div>

<div <?php if (!isset($is_overview)) {
            echo 'role="tabpanel" class="tab-pane"';
        } ?> id="internal-tab">
    <h3 class="mb-5"><?php echo app_lang('internal'); ?></h3>
    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <div class="row">
                <label for="consultancy_type" class="<?php echo $label_column; ?>"><?php echo app_lang('consultancy_type'); ?>
                    <span class="help" data-container="body" data-bs-toggle="tooltip" title="Change the consultancy type of the client"><i data-feather="help-circle" class="icon-16"></i></span>
                </label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_input(array(
                        "id" => "consultancy_type",
                        "name" => "consultancy_type",
                        "value" => isset($model_info->account_type) ? $model_info->account_type : '',
                        "class" => "form-control",
                        "placeholder" => app_lang('consultancy_type'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="form-group">
        <div class="row">
            <label for="assignee" class="<?php echo $label_column; ?>"><?php echo app_lang('assignee'); ?>
                <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_client') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
            </label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "created_by",
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
                    "id" => "created_by_manager",
                    "name" => "assignee_manager",
                    "value" => isset($model_info->assignee_manager) ? $model_info->assignee_manager : '',
                    "class" => "form-control company_name_input_section",
                    "placeholder" => app_lang('assignee_manager'),

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
                $list = array(
                    'Search Engine (e.g., Google, Bing)',
                    'Facebook',
                    'Instagram',
                    'X (Twitter)',
                    'LinkedIn',
                    'Pinterest',
                    'Snapchat',
                    'TikTok',
                    'YouTube',
                    'Reddit',
                    'WhatsApp',
                    'Quora',
                    'WeChat',
                    'Telegram',
                    'Referral from a Friend or Family Member',
                    'Online Advertisement',
                    'Printed Advertisement (Magazine, Newspaper, Brochure)',
                    'Event or Conference',
                    'Educational Institution',
                    'Employment Agency',
                    'News Article or Blog'
                );
                asort($list);
                echo form_datalist(
                    'source',
                    isset($model_info->source) ? $model_info->source : '',
                    $list,
                    "class='form-control company_name_input_section' placeholder='" . app_lang('source') . "'"
                );
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
                    "id" => "client_labels",
                    "name" => "tag_name",
                    "value" => isset($model_info->tag_name) ? $model_info->tag_name : '',
                    "class" => "form-control company_name_input_section",
                    "placeholder" => app_lang('tag_name'),

                ));
                ?>
            </div>
        </div>
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
            <label for="vevo_check_no" class=""><?php echo app_lang('no'); ?></label>
        </div>
    </div>
</div> -->

<script type="text/javascript">
    $(document).ready(function() {

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