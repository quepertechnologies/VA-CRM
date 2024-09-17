<?php echo form_open(get_uri("invoices/save_income_payment"), array("id" => "income-payment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="invoice_item_title" class=" col-md-3"><?php echo app_lang('payment_method'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "payment_methods",
                        "name" => "payment_method_id",
                        "value" => '',
                        "class" => "form-control validate-hidden",
                        "placeholder" => app_lang('payment_method'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="payment_date" class="col-md-3"><?php echo app_lang('payment_date'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "payment_date",
                        "name" => "payment_date",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => app_lang('payment_date'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 text-right">
                <p>Amount</p>
            </div>
            <div class="col-md-3 text-right">
                <b><?php echo to_currency($model_info->amount); ?></b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 text-right">
                <p>Tax</p>
            </div>
            <div class="col-md-3 text-right">
                <b><?php echo to_currency($model_info->tax); ?></b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 text-right">
                <p>Total payment</p>
            </div>
            <div class="col-md-3 text-right">
                <b><?php echo to_currency($model_info->amount + $model_info->tax); ?></b>
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
        $("#income-payment-form").appForm({
            onSuccess: function(result) {
                if (result.success) {
                    $("#unpaid-income-table").appTable({
                        reload: true
                    });
                    $("#paid-income-table").appTable({
                        reload: true
                    });

                    appAlert.success(result.message);
                }
            }
        });

        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

        $('#payment_date').val(moment().format("YYYY-MM-DD"));

        // setDatePicker('#payment_date', {
        //     startDate: today
        // });
        setDatePicker('#payment_date');

        $('#payment_methods').select2({
            data: <?php echo json_encode($payment_methods_dropdown); ?>
        });
    });
</script>