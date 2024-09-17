<?php echo form_open(get_uri("invoices/save_item"), array("id" => "invoice-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" id="item_id" name="item_id" value="" />
        <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
        <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
        <input type="hidden" name="invoice_item_quantity" value="1" />
        <input type="hidden" name="invoice_unit_type" value="" />
        <div class="form-group">
            <div class="row">
                <label for="invoice_item_title" class=" col-md-3"><?php echo app_lang('item'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "invoice_item_title",
                        "name" => "invoice_item_title",
                        "value" => $model_info->title,
                        "class" => "form-control validate-hidden",
                        "placeholder" => app_lang('select_or_create_new_item'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                    <a id="invoice_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
                    <small class="text-danger" id="invoice_item_title_error"></small>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="invoice_item_description" class="col-md-3"><?php echo app_lang('description'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "invoice_item_description",
                        "name" => "invoice_item_description",
                        "value" => $model_info->description ? process_images_from_content($model_info->description, false) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('description'),
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>
        <!-- <div class="form-group">
            <div class="row">
                <label for="invoice_item_quantity" class=" col-md-3"><?php echo app_lang('quantity'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "invoice_item_quantity",
                        "name" => "invoice_item_quantity",
                        "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('quantity'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div> -->
        <!-- <div class="form-group">
            <div class="row">
                <label for="invoice_unit_type" class=" col-md-3"><?php echo app_lang('unit_type'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "invoice_unit_type",
                        "name" => "invoice_unit_type",
                        "value" => $model_info->unit_type,
                        "class" => "form-control",
                        "placeholder" => app_lang('unit_type') . ' (Ex: hours, pc, etc.)'
                    ));
                    ?>
                </div>
            </div>
        </div> -->
        <div class="form-group">
            <div class="row">
                <label for="type-dropdown" class=" col-md-3"><?php echo app_lang('type'); ?></label>
                <div class="col-md-9">
                    <?php
                    $list = array(
                        "" => "-",
                        "payable" => 'Payable',
                        'income' => "Income"
                    );
                    ksort($list);

                    echo form_dropdown(
                        'income_type',
                        $list,
                        isset($model_info->income_type) && $model_info->income_type ? $model_info->income_type : "",
                        "class='form-control validate-hidden' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='type-dropdown'"
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="invoice_item_rate" class=" col-md-3"><?php echo app_lang('fee'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "invoice_item_rate",
                        "name" => "invoice_item_rate",
                        "value" => $model_info->rate ? $model_info->rate : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('fee'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "type" => 'number'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <!-- <div class="form-group <?php echo $model_info->income_type == 'income' ? "" : "d-none" ?>" id="invoice_item_commission_container">
            <div class="row">
                <label for="invoice_item_commission" class=" col-md-3"><?php echo app_lang('commission'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "invoice_item_commission",
                        "name" => "commission",
                        "value" => isset($model_info->commission) && $model_info->commission ? $model_info->commission : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('commission')
                    ));
                    ?>
                    <a id="invoice_item_commission_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
                </div>
            </div>
        </div> -->
        <div class="form-group">
            <div class="row">
                <label for="taxable" class=" col-md-3 col-xs-5 col-sm-4"><?php echo app_lang('taxable'); ?></label>
                <div class=" col-md-9 col-xs-7 col-sm-8">
                    <?php
                    echo form_checkbox("taxable", "1", $model_info->taxable ? true : false, "id='taxable' class='form-check-input'");
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
        $("#invoice-item-form").appForm({
            onSuccess: function(result) {
                // $("#invoice-item-table").appTable({
                //     newData: result.data,
                //     dataId: result.id
                // });
                // $("#invoice-total-section").html(result.invoice_total_view);
                // if (typeof updateInvoiceStatusBar == 'function') {
                //     updateInvoiceStatusBar(result.invoice_id);
                // }

                location.reload();
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_title_dropdwon_icon").click(function() {
            applySelect2OnItemTitle();
        });

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_title").on('keyup', function() {
            if ($("#add_new_item_to_library").val() == '1') {
                if (String($(this).val()).length > 50) {
                    $(this).val(String($(this).val()).substr(0, 50));
                    $("#invoice_item_title_error").html("The item title cannot be greater than 50 characters");
                } else {
                    $("#invoice_item_title_error").html("");
                }
            }
        });

        applySelect2OnCommission();

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_commission_dropdwon_icon").click(function() {
            applySelect2OnCommission();
        });

    });

    function applySelect2OnItemTitle() {
        $("#invoice_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("invoices/get_invoice_item_suggestion"); ?>",
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
        }).change(function(e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#invoice_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_invoice_item_info_suggestion"); ?>",
                    data: {
                        item_id: e.val
                    },
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function(response) {
                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
                            $("#item_id").val(response.item_info.id);
                            $("#invoice_item_title").val(response.item_info.title);

                            $("#invoice_item_description").summernote('code', response.item_info.description);

                            $("#invoice_unit_type").val(response.item_info.unit_type);

                            $("#invoice_item_rate").val(response.item_info.rate);

                            if (response.item_info.taxable == 1) {
                                $("#taxable").prop("checked", true);
                            } else {
                                $("#taxable").prop("checked", false);
                            }
                        }
                    }
                });
            }

        });
    }

    $('#type-dropdown').select2();
    // .change(function(e) {
    //     if (e.val == 'income') {
    //         $('#invoice_item_commission_container').removeClass('d-none');
    //     } else {
    //         $('#invoice_item_commission_container').addClass('d-none');
    //         $('#invoice_item_commission').select2("val", "");
    //     }
    // });

    function applySelect2OnCommission() {
        $("#invoice_item_commission").select2({
            data: <?php echo json_encode($partners_commission_dropdown) ?>
        }).change(function(e) {
            if (e.val === "+") {
                $("#invoice_item_commission").select2("destroy").val("").focus();
            }

        });
    }
</script>