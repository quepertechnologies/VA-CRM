<?php echo form_open(get_uri("projects/save_schedule"), array("id" => "project-schedule-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
        <input type="hidden" name="client_id" value="<?php echo isset($client_id) ? $client_id : 0; ?>" />
        <input type="hidden" name="schedule_id" value="<?php echo isset($schedule_id) ? $schedule_id : 0; ?>" />
        <input type="hidden" name="rows_count" value="<?php echo isset($modal_info->rows_count) ? $modal_info->rows_count : 1; ?>" />

        <div class="row">
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="client_name" class=" col-md-4"><?php echo app_lang('client_name'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_input(
                            "client_name",
                            isset($client_name) ? $client_name : '-',
                            "class='form-control' readonly='readonly' style='cursor: not-allowed;' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'",
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="project_title" class=" col-md-4"><?php echo app_lang('project_title'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_input(
                            "project_title",
                            isset($project_title) ? $project_title : '-',
                            "class='form-control' readonly='readonly' style='cursor: not-allowed;' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'",
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="installment_name" class=" col-md-4"><?php echo app_lang('installment_name'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_input(
                            "installment_name",
                            isset($modal_info->installment_name) ? $modal_info->installment_name : '',
                            "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' placeholder='" . app_lang('installment_name') . "'",
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="invoice_date" class=" col-md-4"><?php echo app_lang('invoice_date'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_input(
                            "invoice_date",
                            isset($modal_info->invoice_date) ? $modal_info->invoice_date : '',
                            "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='invoice-date'",
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="due_date" class=" col-md-4"><?php echo app_lang('due_date'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_input(
                            "due_date",
                            isset($modal_info->due_date) ? $modal_info->due_date : '',
                            "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='due-date'",
                        );
                        ?>
                    </div>
                </div>
            </div>
            <!-- <div class="form-group col-md-6">
                <div class="row">
                    <label for="due_date" class=" col-md-4"><?php echo app_lang("invoice_is_claimable") ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_checkbox(
                            'is_claimable',
                            '1',
                            isset($modal_info->is_claimable) && $modal_info->is_claimable  ? true : false,
                            "class='form-check-input' id='is_claimable'",
                        );
                        ?>
                    </div>
                </div>
            </div> -->
            <?php if (is_dev_mode()) { ?>
                <div class="form-group col-md-6">
                    <div class="row">
                        <label for="status" class=" col-md-4"><?php echo app_lang("status") ?></label>
                        <div class=" col-md-8">
                            <?php
                            $list = array(
                                '0' => 'Scheduled',
                                '1' => 'Invoiced',
                                '2' => 'Discontinued',
                                '3' => 'Invoice Removed',
                                '4' => 'Pending',
                                '5' => 'Expired',
                            );

                            echo form_dropdown(
                                'status',
                                $list,
                                isset($modal_info->status) ? $modal_info->status : '',
                                "class='form-control select2' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='status'",
                            );
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class='row col-md-12 border-top py-3'>
            <div class="col-md-3"><strong><?php echo app_lang("fee_type"); ?></strong></div>
            <div class="col-md-2"><strong><?php echo app_lang("fee_amount"); ?></strong></div>
            <div class="col-md-2"><strong><?php echo app_lang("commission") . ' (%)'; ?></strong></div>
            <div class="col-md-2"><strong><?php echo app_lang("is_claimable"); ?></strong></div>
            <div class="col-md-2"><strong><?php echo app_lang("is_taxable"); ?></strong></div>
        </div>
        <div class="border-top pb-3" id='fee-row-container'>
            <div class='row col-md-12 pt-3' id='fee-row'>
                <div class="col-md-3">
                    <?php
                    $list = array(
                        'Accommodation Fee',
                        'Administration Fee',
                        'Airline Ticket',
                        'Airport Transfer Fee',
                        'Application Fee',
                        'Bond',
                        'Exam Fee',
                        'Date Change Fee',
                        'Extension Fee',
                        'Extra Fee',
                        'FCE Exam Fee',
                        'Health Cover',
                        'I20 Fee',
                        'Installment Fee',
                        'Key Deposit Fee',
                        'Late Payment Fee',
                        'Material Deposit',
                        'Material Fee',
                        'Medial Exam Fee',
                        'Placement Fee',
                        'Security Deposit Fee',
                        'Service Fee',
                        'Swipe Card Fee',
                        'Training Fee',
                        'Transaction Fee',
                        'Translation Fee',
                        'Travel Insurance',
                        'Tuition Fee',
                        'Visa Counseling Fee',
                        'Visa Fee',
                        'Visa Process Fee',
                        'RMA Fee',
                        'Registered Migration Agent Fee',
                        'Enrollment Fee',
                        'Expedited Processing Fee',
                        'Priority Service Fee',
                        'Document Authentication Fee',
                        'Biometric Data Collection Fee',
                        'Legalization Fee',
                        'Document Courier Fee',
                        'Visa Interview Fee',
                        'Document Translation Fee',
                        'Premium Appointment Fee',
                        'Passport Return Delivery Fee',
                        'Document Verification Fee',
                        'Visa Consultation Fee',
                        'Document Handling Fee',
                        'Priority Visa Fee',
                        'Visa Application Center Fee',
                        'Visa Processing Service Fee',
                        'Visa Expedite Fee',
                        'Visa Assessment Fee',
                        'Document Notarization Fee',
                        'Visa Support Letter Fee',
                        'Document Copying Fee',
                        'Photo Printing Fee',
                        'Document Storage Fee',
                        'Legal Consultation Fee',
                        'Application Review Fee',
                        'Document Amendment Fee',
                        'Document Expedite Fee',
                        'Translation Verification Fee',
                        'Passport Renewal Fee',
                        'Notary Public Fee',
                        'Certification Fee',
                        'Postal Delivery Fee',
                        'Document Retrieval Fee',
                        'Document Scanning Fee',
                        'Online Application Fee',
                        'Form Filling Assistance Fee',
                        'Document Packaging Fee',
                        'Document Authentication Fee',
                        'Emergency Service Fee',
                        'Document Tracking Fee',
                        'Customer Service Fee',
                        'Document Mailing Fee',
                        'Courier Service Fee',
                        'Document Return Fee',
                        'Online Account Activation Fee',
                        'Document Submission Fee',
                        'Document Verification Fee',
                        'Document Translation Fee',
                        'Document Courier Fee',
                        'Document Legalization Fee',
                        'Document Witnessing Fee',
                        'Document Notarization Fee',
                        'Document Review Fee',
                        'Document Processing Fee',
                        'Document Transfer Fee',
                        'Document Handling Fee',
                        'Document Printing Fee',
                        'Document Dispatch Fee',
                        'Document Collection Fee',
                        'Document Evaluation Fee',
                        'Document Storage Fee',
                        'Document Upload Fee',
                        'Document Retrieval Fee',
                        'Document Archiving Fee',
                        'Document Destruction Fee',
                        'Document Consultation Fee',
                        'Document Examination Fee',
                        'Document Conversion Fee',
                        'Document Security Fee',
                        'Document Imaging Fee',
                        'Document Duplication Fee',
                        'Document Disposal Fee',
                        'Document Retention Fee',
                        'Document Indexing Fee',
                        'Document Tracking Fee',
                        'Document Binding Fee',
                        'Document Shredding Fee',
                        'Document Handling Fee',
                        'Document Review Fee',
                        'Document Dispatch Fee',
                        'Document Collection Fee',
                        'Document Examination Fee',
                        'Document Retrieval Fee',
                        'Document Verification Fee',
                        'Document Certification Fee',
                        'Document Notarization Fee',
                        'Document Authentication Fee',
                        'Document Translation Fee',
                        'Document Scanning Fee',
                        'Document Copying Fee',
                        'Document Courier Fee',
                        'Document Mailing Fee',
                        'Document Processing Fee',
                        'Document Submission Fee',
                        'Document Transfer Fee',
                        'Document Packaging Fee',
                        'Document Storage Fee',
                        'Document Return Fee',
                        'Document Archiving Fee',
                        'Document Destruction Fee',
                        'Document Consultation Fee',
                        'Document Review Fee',
                        'Document Examination Fee',
                        'Document Handling Fee',
                        'Document Dispatch Fee',
                        'Document Collection Fee',
                        'Document Retrieval Fee',
                        'Document Verification Fee',
                        'Document Certification Fee',
                        'Document Notarization Fee',
                        'Document Authentication Fee',
                        'Document Translation Fee',
                        'Document Scanning Fee',
                        'Document Copying Fee',
                        'Document Courier Fee',
                        'Document Mailing Fee',
                        'Document Processing Fee',
                        'Visa Bond',
                        'Immigration Bond',
                        'Performance Bond',
                        'Surety Bond',
                        'Contract Bond',
                        'Bid Bond',
                        'Payment Bond',
                        'Maintenance Bond',
                        'Advance Payment Bond',
                        'Supply Bond',
                        'Customs Bond',
                        'Judicial Bond',
                        'Bail Bond',
                        'Probate Bond',
                        'Fiduciary Bond',
                        'Notary Bond',
                        'License or Permit Bond',
                        'Public Official Bond',
                        'Lost Instrument Bond',
                        'Release of Lien Bond',
                        'Reclamation Bond',
                        'Environmental Bond',
                        'Construction Bond',
                        'Subdivision Bond',
                        'Court Bond',
                        'Wage and Welfare Bond',
                        'Lost Title Bond',
                        'Title Bond',
                        'Estate Bond',
                        'Appeal Bond',
                        'Cost Bond',
                        'Indemnity Bond',
                        'Lien Bond',
                        'Non-Resident Cost Bond',
                        'Workers Compensation Bond',
                        'Employee Dishonesty Bond',
                        'Financial Guaranty Bond',
                        'Reinsurance Bond',
                        'Performance and Payment Bond',
                        'Utility Bond',
                        'Lost Securities Bond',
                        'Merchandise Dealer Bond',
                        'Replevin Bond',
                        'Sales Tax Bond',
                        'Tax Bond',
                        'Warranty Bond',
                        'Construction Lien Bond',
                        'Court-Appointed Guardian Bond',
                        'Plumbing Contractor Bond',
                        'Electrical Contractor Bond',
                        'Car Dealer Bond',
                        'Motor Vehicle Dealer Bond',
                        'Fishing License Bond',
                        'Farm Labor Contractor Bond',
                        'Alcohol Beverage Tax Bond',
                        'Alcohol Tax Bond',
                        'Alcoholic Beverage Control Bond',
                        'Auto Dealer Bond',
                        'Automobile Dealer Bond',
                        'Broker Bond',
                        'Broker-Dealer Bond',
                        'Business Service Bond',
                        'Car Wash Bond',
                        'Cigarette Tax Bond',
                        'Contractor License Bond',
                        'Currency Exchange Bond',
                        'DMEPOS Bond',
                        'Debt Collector Bond',
                        'Employee Retirement Income Security Act Bond',
                        'ERISA Bond',
                        'Energy Bond',
                        'Excise Tax Bond',
                        'Export-Import Bond',
                        'Federal Bond',
                        'Fidelity Bond',
                        'Financial Institution Bond',
                        'Freight Broker Bond',
                        'Fuel Tax Bond',
                        'Game Dealer Bond',
                        'Garage Bond',
                        'Gasoline Tax Bond',
                        'Grain Dealer Bond',
                        'Health Club Bond',
                        'Hunting License Bond',
                        'Import-Export Bond',
                        'Injunction Bond',
                        'Insurance Broker Bond',
                        'Insurance Producer Bond',
                        'Janitorial Bond',
                        'Lease Bond',
                        'Liquor Tax Bond',
                        'Lost Instrument Surety Bond',
                        'Lost Title Surety Bond',
                        'Mortgage Broker Bond',
                        'Mortgage Lender Bond',
                        'Nursing Home Bond',
                        'Over Axle and Over Gross Weight Tolerance Permit Bond',
                        'Pest Control Bond',
                        'Private Investigator Bond',
                        'Process Server Bond',
                        'Promoter Bond',
                        'Public Adjuster Bond',
                        'Public Official Blanket Bond',
                        'Receiver Bond',
                        'Referee Bond',
                        'Replevin Surety Bond',
                        'Sales Tax Surety Bond',
                        'Service Contract Bond',
                        'Sewer Bond',
                        'Sidewalk Bond',
                        'Site Improvement Bond',
                        'Solicitor Bond',
                        'Special Warranty Bond',
                        'Street Opening Bond',
                        'Surplus Lines Broker Bond',
                        'Tax Preparer Bond',
                        'Telephone Solicitor Bond',
                        'Temporary Restraining Order Bond',
                        'Timber Buyer Bond',
                        'Title Agency Bond',
                        'Title Insurance Agent Bond',
                        'Title Insurance Producer Bond',
                        'Tobacco Tax Bond',
                        'Toll Bond',
                        'Union Bond',
                        'Utility Deposit Bond',
                        'Veteran Affairs Bond',
                        'Voter Registration Bond',
                        'Warranty Deed Bond',
                        'Water Supply Bond',
                        'Weight Distance Tax Bond',
                        'Wholesale Distributor Bond'
                    );

                    $list = array_unique($list);

                    asort($list);

                    echo form_datalist(
                        'fee_type',
                        '',
                        $list,
                        "class='form-control validate-hidden' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='fee-type' placeholder='" . app_lang("fee_type") . "' id='fee-type'"
                    );
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo form_input(
                        'amount',
                        '',
                        "class='form-control' min='0' step='0.01' onkeypress='javascript: return validateNum(this,event);' id='amount' placeholder='0.00'",
                    );
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo form_input(
                        'commission',
                        isset($institute) && $institute ? $institute->commission : '0.00',
                        "class='form-control' min='0' max='100' step='0.01' onkeypress='javascript: return validateNum(this,event);' id='commission' placeholder='0.00 %'",
                    );
                    ?>
                </div>
                <div class=" col-md-2">
                    <?php
                    $list = array(
                        0 => "No",
                        1 => 'Yes'
                    );

                    echo form_dropdown(
                        'is_claimable',
                        $list,
                        '',
                        "class='form-control' id='is-claimable'",
                    );
                    ?>
                </div>
                <div class=" col-md-2">
                    <?php
                    $list = array(
                        0 => "No",
                        1 => 'Yes'
                    );

                    echo form_dropdown(
                        'is_taxable',
                        $list,
                        '',
                        "class='form-control' id='is-taxable'",
                    );
                    ?>
                </div>
                <div class="col-md-1 d-none" id='remove-btn-container'>
                    <button type='button' class="btn btn-danger" id='remove-btn'>
                        <span data-feather="trash-2" class="icon-16"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class='row col-md-12 pb-3'>
            <div class="col-md-3 d-flex align-items-center">
                <strong><?php echo app_lang("discount") ?></strong>
            </div>
            <div class="col-md-8">
                <?php
                echo form_input(
                    'discount',
                    isset($modal_info->discount) ? (float)$modal_info->discount : '0.00',
                    "class='form-control' min='0' step='0.01' onkeypress='javascript: return validateNum(this,event);' id='discount' placeholder='" . app_lang("discount") . "'",
                );
                ?>
            </div>
        </div>
        <div class='row col-md-12 border-top py-3'>
            <div class="col-md-7">
                <button id='add-new-row' type="button" class="btn btn-success"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang("add_fee") ?></button>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center justify-content-end">
                    <strong><?php echo app_lang('net_total') ?></strong>
                </div>
                <div class="d-none align-items-center justify-content-end" id='net-total-msg-container'>
                    <small id='net-total-msg'></small>
                </div>
            </div>
            <div class="col-md-2 d-flex justify-content-start"><span class="text-info" id="net-total"><?php echo isset($modal_info->net_fee) ? number_format((float)$modal_info->net_fee, 2, '.', '') : '0.00'; ?></span></div>
        </div>
    </div>

    <div class="modal-footer">

        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary" id='submit-button'><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>

    </div>
    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#status').select2();
            <?php if (is_dev_mode()) { ?>
                setDatePicker("#invoice-date, #due-date");
            <?php } else { ?>
                var date = new Date();
                var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                setDatePicker("#invoice-date, #due-date", {
                    startDate: today
                });
            <?php } ?>

            window.projectScheduleForm = $("#project-schedule-form").appForm({
                closeModalOnSuccess: false,
                onSuccess: function(result) {
                    window.projectScheduleForm.closeModal();
                    // $("#project-payment-schedule-list-table").appTable({
                    //     reload: true
                    // });
                    location.reload();
                }
            });

            var rowSerial = +'<?php echo isset($modal_info->rows_count) ? $modal_info->rows_count : 0 ?>';

            var rowNode = $('#fee-row'),
                defaultNode = rowNode.clone(); //? create a clone of the original node for safe keeping

            defaultNode.attr('id', ''); //? set the id to empty to avoid duplication

            rowNode.parent().html(''); //? remove the original node

            const fees = <?php echo isset($modal_info->fees) ? json_encode($modal_info->fees) : json_encode(array()); ?>;

            if (fees && fees.length) {
                fees.forEach((fee, i) => addNewRow(fee, i + 1));
            } else {
                addNewRow(); //? add an empty row
            }

            $("#project-schedule-form").on('click', '#add-new-row', function() {
                addNewRow();
            });

            $(document).on('click', '#remove-btn', function(e) {
                var rowId = $(this).attr('data-row-id');
                handleRemoveRow(rowId);
            });

            $(document).on('input', '#amount,#discount', function(e) {
                handleZeroVal(this);
                handleTotal();
            });

            $(document).on('input', '#commission', function(e) {
                handleZeroVal(this, 100);
            });

            $(document).on('blur', '#amount,#discount,#commission', function(e) {
                handleZeroValSuffix(this);
            });

            // $(document).on('input', '#commission', function(e) {
            //     handleMaxVal(this, 100);
            // });

            function handleZeroVal(el, maxVal = null) {
                var val = $(el).val();
                if (!val || val == '' || val == null || (val && Number(val) == 0)) {
                    $(el).val('0.00');
                } else if (val && +val < 1) {
                    $(el).val(String(val).charAt(String(val).length - 1));
                } else {
                    if (maxVal) {
                        if (Number(val) > maxVal) {
                            $(el).val(Number(maxVal));
                        } else {
                            $(el).val(val);
                        }
                    } else {
                        $(el).val(val);
                    }
                }
            }

            // function handleMaxVal(el, maxVal = 100) {
            //     var val = $(el).val();
            //     $(el).val(Number(val) > maxVal ? Number(maxVal) : Number(val));
            // }

            function handleZeroValSuffix(el) {
                var val = $(el).val();
                $(el).val(Number(val).toFixed(2));
            }

            function addNewRow(fee, key) {
                if (fee, key) {
                    var newNode = defaultNode.clone(),
                        rowId = 'fee-row-' + key;

                    newNode.attr('id', rowId);

                    newNode.find('#fee-type').attr('name', 'fee_type_' + key).attr('list', 'fee_type_' + key + '_list').val(fee.fee_type);
                    newNode.find('#fee_type_list').attr('id', 'fee_type_' + key + '_list');
                    newNode.find('#amount').attr('name', 'amount_' + key).val(fee.amount);
                    newNode.find('#commission').attr('name', 'commission_' + key).val(fee.commission ? fee.commission : 0);
                    newNode.find('#is-claimable').attr('name', 'is_claimable_' + key).val(fee.is_claimable ? fee.is_claimable : 0);
                    newNode.find('#is-taxable').attr('name', 'is_taxable_' + key).val(fee.is_taxable ? fee.is_taxable : 0);

                    if (key > 1) { // show remove btn if not first row
                        newNode.find('#remove-btn-container').removeClass("d-none");
                        newNode.find('#remove-btn').attr('data-row-id', rowId);
                    }

                    $('#fee-row-container').append(newNode);
                } else {
                    rowSerial++;
                    var newNode = defaultNode.clone(),
                        rowId = 'fee-row-' + rowSerial;

                    newNode.attr('id', rowId);

                    newNode.find('#fee-type').attr('name', 'fee_type_' + rowSerial).attr('list', 'fee_type_' + rowSerial + '_list');
                    newNode.find('#fee_type_list').attr('id', 'fee_type_' + rowSerial + '_list');
                    newNode.find('#amount').attr('name', 'amount_' + rowSerial);
                    newNode.find('#commission').attr('name', 'commission_' + rowSerial);
                    newNode.find('#is-claimable').attr('name', 'is_claimable_' + rowSerial);
                    newNode.find('#is-taxable').attr('name', 'is_taxable_' + rowSerial);

                    if (rowSerial > 1) { // show remove btn if not first row
                        newNode.find('#remove-btn-container').removeClass("d-none");
                        newNode.find('#remove-btn').attr('data-row-id', rowId);
                    }

                    $('#fee-row-container').append(newNode);
                    $("input[name='rows_count']").val(rowSerial);
                }
            }

            function handleRemoveRow(rowId = '') {
                if (rowId) {
                    rowSerial--;
                    $('#' + rowId).remove();
                    $("input[name='rows_count']").val(rowSerial);
                    handleTotal();
                }
            }

            function handleFees() {
                var total = 0;

                for (let x = 1; x <= rowSerial; x++) {
                    var row_amount = $("input[name='amount_" + x + "']").val();
                    total += Number(row_amount);
                }

                return total;
            }

            function handleDiscount() {
                var discount = $('#discount').val();

                $('#discount-total').html(Number(discount).toFixed(2));
                $('#discount-total-field').val(discount);

                return Number(discount).toFixed(2);
            }

            function handleTotal() {
                var discount_total = handleDiscount(),
                    fees_total = handleFees(),
                    net_total = (Number(fees_total) - Number(discount_total)).toFixed(2);

                $('#net-total').html(net_total);

                if (Number(net_total) < 0) {
                    $('#net-total-msg-container').removeClass('d-none').addClass('d-flex');
                    $('#net-total-msg').addClass('text-danger').html('Net Total cannot be negative');
                    $('#submit-button').attr('disabled', 'disabled');
                } else {
                    $('#net-total-msg-container').removeClass('d-flex').addClass('d-none');
                    $('net-total-msg').removeClass('text-danger').html('');
                    $('#submit-button').removeAttr('disabled');
                }
            }
        });

        function validateNum(el, evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode != 45 && charCode != 8 && (charCode != 46) && (charCode < 48 || charCode > 57))
                return false;
            if (charCode == 46) {
                if ((el.value) && (el.value.indexOf('.') >= 0))
                    return false;
                else
                    return true;
            }
            return true;
        };
    </script>