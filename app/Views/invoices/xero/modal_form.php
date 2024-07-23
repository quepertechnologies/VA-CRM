<?php if (isset($xero_session_expired) && $xero_session_expired) { ?>
    <?php echo view('invoices/xero/session_expired'); ?>
<?php } elseif (isset($xero_invoice_available) && $xero_invoice_available) { ?>
    <?php echo view('invoices/xero/invoice_available'); ?>
<?php } else { ?>
    <?php echo form_open(get_uri("xero_api/create_invoice"), array("id" => "xero-form", "class" => "general-form", "role" => "form")); ?>
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
            <div class="form-group">
                <div class="row">
                    <label for="select_account" class="col-md-3"><?php echo app_lang('select_account'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "select_account",
                            "name" => "xero_account_id",
                            "value" => "",
                            "class" => "form-control",
                            "autofocus" => "true",
                            "placeholder" => app_lang('select_account'),
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
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#xero-form").appForm({
            onSuccess: function(result) {
                if (result.success) {
                    // $("#invoice-total-section").html(result.invoice_total_view);
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        $("#select_account").select2({
            data: <?php echo json_encode($xero_accounts_dropdown); ?>,
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                return data.html;
            },
            templateSelection: function(data) {
                return data.text;
            }
        })
    });
</script>