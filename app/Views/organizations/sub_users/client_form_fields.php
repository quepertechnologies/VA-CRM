<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />
<input type="hidden" name="type" value="person" />
<input type="hidden" name="password" id="password" value="">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo isset($parent_id) ? $parent_id : "0"; ?>">

<div class="mt15">
    <h3 class="mb-5"><?php echo app_lang('profile_info'); ?></h3>
    <div class="form-group">
        <div class="row">
            <label for="consultation_type" class="<?php echo $label_column; ?>"><?php echo app_lang('consultation_type'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                $list =
                    array(
                        '' => '-',
                        '1' => 'Student',
                        '2' => 'Migration Client',
                    );

                ksort($list);
                echo form_dropdown(
                    'account_type',
                    $list,
                    isset($model_info->account_type) ? $model_info->account_type : '',
                    "class='form-control select2 validate-hidden' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='consultation_type' placeholder='" . app_lang('select_partners') . "'"
                );
                ?>
            </div>
        </div>
    </div>
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
                    'readonly' => true,
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

                    "type" => 'email',
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
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
                    "placeholder" => app_lang('contact_number'),

                    "type" => 'number',
                ));
                ?>
            </div>
        </div>
    </div>

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
                asort($list);
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
    <h3 class="mb-5"><?php echo app_lang('internal'); ?></h3>
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

<script type="text/javascript">
    $(document).ready(function() {

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