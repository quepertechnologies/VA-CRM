<?php echo form_open(get_uri("invoices/save_bonus"), array("id" => "bonus-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="invoice_id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <div class="row">
                <label for="bonus" class="col-md-3"><?php echo app_lang('bonus'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "bonus",
                        "name" => "bonus_amount",
                        "value" => $model_info->bonus_amount ? $model_info->bonus_amount : "",
                        "class" => "form-control",
                        "autofocus" => "true",
                        "placeholder" => app_lang('bonus'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
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
        $("#bonus-form").appForm({
            onSuccess: function(result) {
                if (result.success && result.invoice_total_view) {
                    $("#invoice-total-section").html(result.invoice_total_view);
                } else {
                    appAlert.error(result.message);
                }
            }
        });
    });
</script>