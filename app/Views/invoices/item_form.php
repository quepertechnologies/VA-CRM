<?php echo form_open(get_uri("invoices/save_item"), array("id" => "inline-invoice-item-form", "class" => "general-form", "role" => "form")); ?>
<input type="hidden" id="item_id" name="item_id" value="" />
<input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
<input type="hidden" name="add_new_item_to_library" value="" id="inline_add_new_item_to_library" />
<input type="hidden" name="taxable" value="0" id="inline_taxable" />
<input type="hidden" name="invoice_unit_type" value="" id="inline_invoice_unit_type" />

<div class="table-responsive mt15 pl15 pr15">
    <table class="display" width="100%">
        <tbody class="row">
            <tr class="even">
                <td class="" style="padding: 12px 10px; width: 43%;">
                    <?php
                    echo form_input(array(
                        "id" => "inline_invoice_item_title",
                        "name" => "invoice_item_title",
                        "value" => "",
                        "class" => "form-control validate-hidden",
                        "placeholder" => app_lang('select_or_create_new_item'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                    <a id="inline_invoice_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
                    <?php
                    echo form_input(array(
                        "id" => "inline_invoice_item_description",
                        "name" => "invoice_item_description",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => app_lang('description')
                    ));
                    ?>
                </td>
                <td class="w15p" style="padding: 12px 10px;">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "inline_invoice_item_quantity",
                                "name" => "invoice_item_quantity",
                                "value" => "1",
                                "class" => "form-control",
                                "placeholder" => app_lang('quantity'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                                'min' => 1,
                                'max' => 99,
                                'onkeydown' => 'javascript: return event.keyCode == 69 ? false : true',
                                'type' => 'number'
                            ));
                            ?>
                        </div>
                    </div>
                </td>
                <td class="w15p" style="padding: 12px 10px;">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "inline_invoice_item_rate",
                                "name" => "invoice_item_rate",
                                "value" => "0.00",
                                "class" => "form-control",
                                "placeholder" => app_lang('rate'),
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                                'min' => '0.00',
                                'onkeydown' => 'javascript: return event.keyCode == 69 ? false : true',
                                'type' => 'number'
                            ));
                            ?>
                        </div>
                    </div>
                </td>
                <td class="text-right w15p" style="padding: 12px 10px;" id="invoice-item-total">
                    0.00
                </td>
                <td class="text-center option w100" style="padding: 12px 10px;">
                    <button type="submit" class="btn btn-primary"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add'); ?></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<!-- <div class="d-flex justify-content-end px-4">
    <button type="submit" class="btn btn-success"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang('add_item'); ?></button>
</div> -->
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#inline-invoice-item-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                $("#invoice-item-table").appTable({
                    newData: result.data,
                    dataId: result.id
                });

                $("#invoice-total-section").html(result.invoice_total_view);

                $("#inline_invoice_item_title").val("");

                $("#inline_invoice_item_description").val("");

                $("#inline_invoice_unit_type").val("");

                $("#inline_invoice_item_rate").val("0.00");

                $('#inline_invoice_item_quantity').val('1');

                $("#invoice-item-total").html("0.00");

                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        $(document).on('input', '#inline_invoice_item_quantity,#inline_invoice_item_rate', function() {
            handleZeroVal(this);
            var qty = $('#inline_invoice_item_quantity').val(),
                rate = $('#inline_invoice_item_rate').val(),
                total = qty * rate;

            $("#invoice-item-total").html(total.toFixed(2));
        });

        $(document).on('blur', '#inline_invoice_item_quantity,#inline_invoice_item_rate', function(e) {
            handleZeroValSuffix(this);
        });

        function handleZeroVal(el) {
            var val = $(el).val();
            if (!val || val == '' || val == null || (val && Number(val) == 0)) {
                $(el).val('0.00');
            } else if (val && +val < 1) {
                $(el).val(String(val).charAt(String(val).length - 1));
            } else {
                $(el).val(val);
            }
        }

        function handleZeroValSuffix(el) {
            var val = $(el).val();
            $(el).val(Number(val).toFixed(2));
        }

        applySelect2OnItemTitle();

        //re-initialize item suggestion dropdown on request
        $("#inline_invoice_item_title_dropdwon_icon").click(function() {
            applySelect2OnItemTitle();
        });

    });

    function applySelect2OnItemTitle() {
        $("#inline_invoice_item_title").select2({
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
                $("#inline_invoice_item_title").select2("destroy").val("").focus();
                $("#inline_add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#inline_add_new_item_to_library").val(""); //reset the flag to add new item in library
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

                            $("#inline_invoice_item_title").val(response.item_info.title);

                            $("#inline_invoice_item_description").val(response.item_info.description);

                            $("#inline_invoice_unit_type").val(response.item_info.unit_type);

                            $("#inline_invoice_item_rate").val(response.item_info.rate);

                            $("#invoice-item-total").html(($('#inline_invoice_item_quantity').val() * response.item_info.rate).toFixed(2));

                            if (response.item_info.taxable == 1) {
                                $("#inline_taxable").val("1");
                            } else {
                                $("#inline_taxable").val("0");
                            }
                        }
                    }
                });
            }

        });
    }
</script>