<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />

<div class="form-group">
         <div class="row">
            <label for="account_type" class="<?php echo $label_column; ?>">Consultation Type</label>
            <div class="<?php echo $field_column; ?>">
                <?php                
                echo form_dropdown("account_type", $account_types_filter_dropdown, $model_info->account_type, 'class="form-control select2" id="account_type" data-rule-required="true" data-msg-required="' . app_lang("field_required") . '"');
                ?>
            </div>
        </div>
</div>
<div class="form-group" id="company_field" <?php if($model_info->account_type != 4) { echo 'style="display:none;"'; } ?>>
<div class="row">
        <label for="company_name" class="<?php echo $label_column; ?>"><?php echo app_lang('company_name'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "company_name",
                "name" => "company_name",
                "value" => $model_info->company_name,
                "class" => "form-control",
                "placeholder" => app_lang('company_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ));
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
<span  id="heading" <?php if($model_info->account_type != 4) { echo 'style="display:none;"'; } ?>><h3 class="mb-5"><?php echo app_lang('contact_person'); ?></h3></span>
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
                        $phone_codes['+' . $country->phonecode] = $country->nicename . ' +' . $country->phonecode;
                    }

                    $phone_codes[''] = '-';
                    asort($phone_codes);
                    echo form_dropdown(
                        'phone_code',
                        $phone_codes,
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
                    "placeholder" => app_lang('phone'),
                    "autofocus" => true,
                    "type" => 'number',
                ));
                ?>
            </div>
        </div>
    </div>
<h3 class="mb-5"><?php echo app_lang('internal'); ?></h3>    
<div class="form-group">
    <div class="row">
        <label for="lead_status_id" class="<?php echo $label_column; ?>"><?php echo app_lang('status'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            foreach ($statuses as $status) {
                $lead_status[$status->id] = $status->title;
            }

            echo form_dropdown("lead_status_id", $lead_status, array($model_info->lead_status_id), "class='select2'");
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="owner_id" class="<?php echo $label_column; ?>"><?php echo app_lang('owner'); ?>
            <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo app_lang('the_person_who_will_manage_this_lead') ?>"><i data-feather="help-circle" class="icon-16"></i></span>
        </label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "owner_id",
                "name" => "owner_id",
                "readonly" => "true",
                "value" => $model_info->owner_id ? $model_info->owner_id : $login_user->id,
                "class" => "form-control",
                "placeholder" => app_lang('owner')
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="lead_source_id" class="<?php echo $label_column; ?>"><?php echo app_lang('source'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            $lead_source = array();

            foreach ($sources as $source) {
                $lead_source[$source->id] = $source->title;
            }

            echo form_dropdown("lead_source_id", $lead_source, array($model_info->lead_source_id), "class='select2'");
            ?>
        </div>
    </div>
</div>
<!-- <div class="form-group">
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
</div> -->
<!-- <div class="form-group">
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
</div> -->
<!-- <div class="form-group">
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
</div> -->
<!-- <div class="form-group">
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
</div> -->
<!-- <div class="form-group">
    <div class="row">
        <label for="country" class="<?php echo $label_column; ?>"><?php echo app_lang('country'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "country",
                "name" => "country",
                "value" => $model_info->country,
                "class" => "form-control",
                "placeholder" => app_lang('country')
            ));
            ?>
        </div>
    </div>
</div> -->
<!-- <div class="form-group">
    <div class="row">
        <label for="phone" class="<?php echo $label_column; ?>"><?php echo app_lang('phone'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "phone",
                "name" => "phone",
                "value" => $model_info->phone,
                "class" => "form-control",
                "placeholder" => app_lang('phone')
            ));
            ?>
        </div>
    </div>
</div> -->
<!-- <div class="form-group">
    <div class="row">
        <label for="website" class="<?php echo $label_column; ?>"><?php echo app_lang('website'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "website",
                "name" => "website",
                "value" => $model_info->website,
                "class" => "form-control",
                "placeholder" => app_lang('website')
            ));
            ?>
        </div>
    </div>
</div> -->
<!-- <div class="form-group">
    <div class="row">
        <label for="vat_number" class="<?php echo $label_column; ?>"><?php echo app_lang('vat_number'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "vat_number",
                "name" => "vat_number",
                "value" => $model_info->vat_number,
                "class" => "form-control",
                "placeholder" => app_lang('vat_number')
            ));
            ?>
        </div>
    </div>
</div> -->
<!-- <div class="form-group">
    <div class="row">
        <label for="gst_number" class="<?php echo $label_column; ?>"><?php echo app_lang('gst_number'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "gst_number",
                "name" => "gst_number",
                "value" => $model_info->gst_number,
                "class" => "form-control",
                "placeholder" => app_lang('gst_number')
            ));
            ?>
        </div>
    </div>
</div> -->

<?php if ($login_user->is_admin && get_setting("module_invoice")) { ?>
<!--     <div class="form-group">
        <div class="row">
            <label for="currency" class="<?php echo $label_column; ?>"><?php echo app_lang('currency'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "currency",
                    "name" => "currency",
                    "value" => $model_info->currency,
                    "class" => "form-control",
                    "placeholder" => app_lang('keep_it_blank_to_use_default') . " (" . get_setting("default_currency") . ")"
                ));
                ?>
            </div>
        </div> 
    </div> --> 
<!--     <div class="form-group">
        <div class="row">
            <label for="currency_symbol" class="<?php echo $label_column; ?>"><?php echo app_lang('currency_symbol'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php
                echo form_input(array(
                    "id" => "currency_symbol",
                    "name" => "currency_symbol",
                    "value" => $model_info->currency_symbol,
                    "class" => "form-control",
                    "placeholder" => app_lang('keep_it_blank_to_use_default') . " (" . get_setting("currency_symbol") . ")"
                ));
                ?>
            </div>
        </div>
    </div> -->
<?php } ?>

<div class="form-group">
    <div class="row">
        <label for="lead_labels" class="<?php echo $label_column; ?>"><?php echo app_lang('labels'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "lead_labels",
                "name" => "labels",
                "value" => $model_info->labels,
                "class" => "form-control",
                "placeholder" => app_lang('labels')
            ));
            ?>
        </div>
    </div>
</div>

<?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => $label_column, "field_column" => $field_column)); ?> 

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $(".select2").select2();

<?php if (isset($currency_dropdown)) { ?>
            if ($('#currency').length) {
                $('#currency').select2({data: <?php echo json_encode($currency_dropdown); ?>});
            }
<?php } ?>

        $('#owner_id').select2({data: <?php echo json_encode($owners_dropdown); ?>});

        $("#lead_labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});

        $('#account_type').on('change', function() {
              value  = this.value;
                if(value == 4)
                  {
                    $('#company_field').show();
                    $('#heading').show();
                  }
                  else
                  {
                    $('#company_field').hide();
                    $('#heading').hide();
                  }
            });

    });


</script>