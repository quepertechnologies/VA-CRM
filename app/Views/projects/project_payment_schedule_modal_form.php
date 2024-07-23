<?php echo form_open(get_uri("projects/save_project_payment_schedule_setup"), array("id" => "project-payment-schedule-setup-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="installment_start_date" class=" col-md-3"><?php echo app_lang('installment_start_date'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(
                        "installment_start_date",
                        isset($modal_info->installment_start_date) ? $modal_info->installment_start_date : '',
                        "class='form-control' id='installment-start-date'",
                        'date'
                    );
                    ?>
                </div>
            </div>
        </div>

        <h4 class="border-top pt-3"><?php echo app_lang('payment_schedule_setup'); ?></h4>
        <small class="text-muted"><span data-feather="info" class="icon-16"></span> Schedule your Invoices by selecting an Invoice date for this installment.</small>

        <div class="form-group mt-4">
            <div class="row">
                <label for="first_invoice_date" class=" col-md-3"><?php echo app_lang('first_invoice_date'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(
                        "first_invoice_date",
                        isset($modal_info->first_invoice_date) ? $modal_info->first_invoice_date : '',
                        "class='form-control' id='first-invoice-date'",
                        'date'
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

        window.projectPaymentScheduleSetupForm = $("#project-payment-schedule-setup-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                // location.reload();
                window.projectPaymentScheduleSetupForm.closeModal();
            }
        });

        setTimeout(function() {
            $("#installment-start-date").focus();
        }, 200);

        setDatePicker("#installment-start-date, #first-invoice-date");
    });
</script>