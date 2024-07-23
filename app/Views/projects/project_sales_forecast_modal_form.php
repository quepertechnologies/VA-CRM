<?php echo form_open(get_uri("projects/save_project_sales_forecast"), array("id" => "project-sales-forecast-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <?php if (is_dev_mode()) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="institute-revenue" class=" col-md-3"><?php echo app_lang('institute_revenue'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(
                            "institute_revenue",
                            isset($modal_info->institute_revenue) ? $modal_info->institute_revenue : '0.00',
                            "class='form-control' min='0' step='0.01' onkeydown='javascript: return event.keyCode == 69 ? false : true' data-rule-required='true' data-msg-required='" . app_lang('discount_must_be_greater_than_or_equal_to_zero') . "' id='institute-revenue' placeholder='Revenue'",
                            'number'
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="referral-revenue" class=" col-md-3"><?php echo app_lang('referral_revenue'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(
                            "referral_revenue",
                            isset($modal_info->referral_revenue) ? $modal_info->referral_revenue : '0.00',
                            "class='form-control' min='0' step='0.01' onkeydown='javascript: return event.keyCode == 69 ? false : true' data-rule-required='true' data-msg-required='" . app_lang('discount_must_be_greater_than_or_equal_to_zero') . "' id='referral-revenue' placeholder='Revenue'",
                            'number'
                        );
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <div class="row">
                <label for="revenue-discount" class=" col-md-3"><?php echo app_lang('discount'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(
                        "revenue_discount",
                        isset($modal_info->revenue_discount) ? $modal_info->revenue_discount : '0.00',
                        "class='form-control' min='0' step='0.01' onkeydown='javascript: return event.keyCode == 69 ? false : true' data-rule-required='true' data-msg-required='" . app_lang('discount_must_be_greater_than_or_equal_to_zero') . "' id='revenue-discount' placeholder='Discount'",
                        'number'
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">

    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        window.projectSalesForecastForm = $("#project-sales-forecast-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                location.reload();
                window.projectSalesForecastForm.closeModal();
            }
        });

        setTimeout(function() {
            $("#revenue-discount").focus();
        }, 200);
    });
</script>