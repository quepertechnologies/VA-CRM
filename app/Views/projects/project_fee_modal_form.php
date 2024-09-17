<?php echo form_open(get_uri("projects/save_project_fees"), array("id" => "project-fee-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
        <input type="hidden" name="rows_count" value="1" />
        <input type="hidden" name="amount_title" value="<?php echo app_lang("installment_amount"); ?>" />
        <input type="hidden" name="installments_title" value="<?php echo app_lang("installments"); ?>" />
        <input type="hidden" name="claimable_title" value="<?php echo app_lang("claimable_installments"); ?>" />

        <div class="row">
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="fee_option_name" class=" col-md-4"><?php echo app_lang('fee_option_name'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_input(
                            "fee_option_name",
                            'Default Fee',
                            "class='form-control' readonly='readonly' style='cursor: not-allowed;' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='fee-option-name'",
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="country_of_residency" class=" col-md-4"><?php echo app_lang('country_of_residency'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        echo form_input(
                            "country_of_residency",
                            'All Countries',
                            "class='form-control' readonly='readonly' style='cursor: not-allowed;' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='country-of-residency'",
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="row">
                    <label for="installment-type" class=" col-md-4"><?php echo app_lang('installment_type'); ?></label>
                    <div class=" col-md-8">
                        <?php
                        $list = array(
                            '' => '-',
                            'Full Fee' => 'Full Fee',
                            'Per Year' => 'Per Year',
                            'Per Month' => 'Per Month',
                            'Per Term' => 'Per Term',
                            'Per Trimester' => 'Per Trimester',
                            'Per Semester' => 'Per Semester',
                            'Per Week' => 'Per Week',
                            'Installment' => 'Installment'
                        );

                        echo form_dropdown(
                            'installment_type',
                            $list,
                            '',
                            "class='form-control select2 validate-hidden' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='installment-type'"
                        );
                        ?>
                    </div>
                </div>
            </div>
            <!-- <h4 class="border-top pt-3"><?php echo app_lang('payment_schedule_setup'); ?></h4> -->

            <div class="form-group mt-4">
                <div class="row">
                    <label for="first_invoice_date" class=" col-md-3"><?php echo app_lang('first_invoice_date'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(
                            "first_invoice_date",
                            '',
                            "class='form-control' id='first-invoice-date' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'",
                        );
                        ?>
                        <small class="text-info"><span data-feather="info" class="icon-16"></span> Auto Schedule your Invoices by selecting an Invoice date for the first installment.</small>
                    </div>
                </div>
            </div>
        </div>
        <div class='row col-md-12 border-top py-3'>
            <div class="col-md-3"><strong><?php echo app_lang("fee_type"); ?></strong></div>
            <div class="col-md-2"><strong id='amount-title'><?php echo app_lang("installment_amount"); ?></strong></div>
            <div class="col-md-2"><strong id='installments-title'><?php echo app_lang("installments"); ?></strong></div>
            <div class="col-md-2"><strong id='claimable-title'><?php echo app_lang("claimable_installments"); ?></strong></div>
            <div class="col-md-1"><strong><?php echo app_lang("taxable"); ?></strong></div>
            <div class="col-md-1 text-right"><strong id='total-title'><?php echo app_lang("total_fee"); ?></strong></div>
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
                        "class='form-control select2 validate-hidden' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='fee-type' placeholder='" . app_lang("fee_type") . "' id='fee-type'"
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
                        'installments',
                        '1',
                        "class='form-control' min='1' max='99' onkeydown='javascript: return event.keyCode == 69 ? false : true' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='installments' placeholder='" . app_lang("installments") . "'",
                        'number'
                    );
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo form_input(
                        'claimable_installments',
                        '0',
                        "class='form-control' min='0' max='99' onkeydown='javascript: return event.keyCode == 69 ? false : true' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='claimable-installments' placeholder='" . app_lang("claimable_installments") . "'",
                        'number'
                    );
                    ?>
                </div>
                <div class="col-md-1">
                    <?php
                    $list = array(
                        0 => 'No',
                        1 => 'Yes'
                    );
                    ksort($list);

                    echo form_dropdown(
                        'taxable',
                        $list,
                        ['no'],
                        "class='form-control' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='taxable' placeholder='" . app_lang("taxable") . "'",
                    );
                    ?>
                </div>
                <div class="col-md-1 d-flex align-items-center justify-content-end"><span id='row-total'>0.00</span></div>
                <div class="col-md-1 d-none" id='remove-btn-container'>
                    <button type='button' class="btn btn-danger" id='remove-btn'>
                        <span data-feather="trash-2" class="icon-16"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class='row col-md-12 pb-3'>
            <div class="col-md-3 d-flex align-items-center">
                <p>
                    <strong><?php echo app_lang("discount") ?></strong>
                    <br>
                    <small class="d-none" id='discount-msg'></small>
                </p>
            </div>
            <div class="col-md-2">
                <?php
                echo form_input(
                    'discount',
                    '0.00',
                    "class='form-control' min='0' step='0.01' onkeypress='javascript: return validateNum(this,event);' id='discount' placeholder='" . app_lang("discount") . "'",
                );
                ?>
            </div>
            <div class="col-md-2">
                <?php
                echo form_input(
                    'discount_installments',
                    '1',
                    "class='form-control' min='1' max='99' onkeydown='javascript: return event.keyCode == 69 ? false : true' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='discount-installments' placeholder='" . app_lang("installments") . "'",
                    'number'
                );
                ?>
            </div>

            <input type="hidden" name="discount_total" id="discount-total-field" value="0.00" />
            <div class="col-md-4 d-flex align-items-center justify-content-end"><span class="text-danger" id="discount-total"><?php echo '0.00'; ?></span></div>
        </div>
        <div class='row col-md-12 border-top py-3'>
            <div class="col-md-3">
                <button id='add-new-row' type="button" class="btn btn-success"><span data-feather="plus-circle" class="icon-16"></span> <?php echo app_lang("add_fee") ?></button>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-end">
                    <strong><?php echo app_lang('net_total') ?></strong>
                </div>
                <div class="d-none align-items-center justify-content-end" id='net-total-msg-container'>
                    <small id='net-total-msg'></small>
                </div>
            </div>

            <input type="hidden" name="net_total" id="net-total-field" value="<?php echo '0.00'; ?>" />
            <div class="col-md-2 d-flex justify-content-end"><span class="text-info" id="net-total"><?php echo '0.00'; ?></span></div>
        </div>
    </div>

    <div class="modal-footer">

        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary" id='submit-button'><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>

    </div>
    <?php echo form_close(); ?>

    <script type="text/javascript">
        $(document).ready(function() {

            <?php if (is_dev_mode()) { ?>
                setDatePicker("#first-invoice-date");
            <?php } else { ?>
                var date = new Date();
                var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                setDatePicker("#first-invoice-date", {
                    startDate: today
                });
            <?php } ?>

            window.projectFeeForm = $("#project-fee-form").appForm({
                closeModalOnSuccess: false,
                onSuccess: function(result) {
                    window.projectFeeForm.closeModal();
                    location.reload();
                }
            });

            var rowSerial = 0;

            var rowNode = $('#fee-row'),
                defaultNode = rowNode.clone(); //? create a clone of the original node for safe keeping

            defaultNode.attr('id', ''); //? set the id to empty to avoid duplication

            rowNode.parent().html(''); //? remove the original node

            addNewRow();

            $('#installment-type').select2().on("change", function() {
                var selectedVal = $(this).val();
                handleInstallmentType(selectedVal);
            });

            $('#discount, #discount-installments').on('input', function(e) {
                handleZeroVal(this);
                handleTotal();
            });

            $("#project-fee-form").on('click', '#add-new-row', function() {
                addNewRow();
            });

            $(document).on('click', '#remove-btn', function(e) {
                var rowId = $(this).attr('data-row-id');
                handleRemoveRow(rowId);
            });

            $(document).on('input', '#amount', function(e) {
                handleZeroVal(this);
                handleTotal();
            });

            $(document).on('input', '#claimable-installments', function(e) {
                handleZeroVal(this);
            });

            $(document).on('input', '#installments', function(e) {
                handleZeroVal(this);
                handleClaimableInstallment(this);
                handleTotal();
            });

            $(document).on('blur', '#amount,#discount', function(e) {
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

            function handleClaimableInstallment(el) {
                var installment = $(el).val(),
                    name = $(el).attr('name');
                if (name) {
                    name = name.split('_');
                    if (name.length > 1) {
                        var key = name[name.length - 1];
                        if (key) {
                            $("input[name='claimable_installments_" + key + "']").val(installment);
                        }
                    }
                }
            }

            function addNewRow(fee) {
                rowSerial++;
                var newNode = defaultNode.clone(),
                    rowId = 'fee-row-' + rowSerial;

                newNode.attr('id', rowId);

                newNode.find('#fee-type').attr('name', 'fee_type_' + rowSerial).attr('list', 'fee_type_' + rowSerial + '_list');
                newNode.find('#fee_type_list').attr('id', 'fee_type_' + rowSerial + '_list');
                newNode.find('#amount').attr('name', 'amount_' + rowSerial);
                newNode.find('#row-total').attr('id', 'row-total-' + rowSerial);
                newNode.find('#installments').attr('name', 'installments_' + rowSerial);
                newNode.find('#claimable-installments').attr('name', 'claimable_installments_' + rowSerial);
                newNode.find('#taxable').attr('name', 'taxable_' + rowSerial);

                if (rowSerial > 1) { // show remove btn if not first row
                    newNode.find('#remove-btn-container').removeClass("d-none");
                    newNode.find('#remove-btn').attr('data-row-id', rowId);
                }

                $('#fee-row-container').append(newNode);
                $("input[name='rows_count']").val(rowSerial);
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
                    var row_amount = $("input[name='amount_" + x + "']").val(),
                        row_installment = $("input[name='installments_" + x + "']").val(),
                        row_total = Number(row_amount) * Number(row_installment);

                    $("#row-total-" + x).html(Number(row_total).toFixed(2));

                    total += row_total;
                }

                return total;
            }

            function handleMaxInstallmentCount() {
                var maxInstallment = 0;

                for (let x = 1; x <= rowSerial; x++) {
                    var row_installment = $("input[name='installments_" + x + "']").val();
                    if (+row_installment > maxInstallment) {
                        maxInstallment = row_installment;
                    }

                }

                $('#discount-installments').attr('max', maxInstallment);
                if (+$('#discount-installments').val() > maxInstallment) {
                    $('#discount-installments').val(maxInstallment);
                    $('#discount-msg').removeClass('d-none').addClass('text-info').html('Discount installments must be equal to or less than max no. of installments of fees.');
                } else {
                    $('#discount-msg').addClass('d-none').removeClass('text-info').html('');
                }
            }

            function handleDiscount() {
                handleMaxInstallmentCount();
                var discount = $('#discount').val(),
                    discount_installments = $('#discount-installments').val(),
                    discount_total = (Number(discount) * Number(discount_installments)).toFixed(2);

                $('#discount-total').html(discount_total);
                $('#discount-total-field').val(discount_total);

                return discount_total;
            }

            function handleTotal() {
                var discount_total = handleDiscount(),
                    fees_total = handleFees(),
                    net_total = (Number(fees_total) - Number(discount_total)).toFixed(2);

                $('#net-total').html(net_total);
                $('#net-total-field').val(net_total);

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

            function handleInstallmentType(type = 'Installment') {
                var amount_title = '<?php echo app_lang('per_year_amount'); ?>',
                    installment_title = '<?php echo app_lang('no_of_year'); ?>',
                    claimable_title = '<?php echo app_lang('claimable_year'); ?>';

                switch (type) {
                    case 'Per Year':

                        amount_title = '<?php echo app_lang('per_year_amount'); ?>';
                        installment_title = '<?php echo app_lang('no_of_year'); ?>';
                        claimable_title = '<?php echo app_lang('claimable_year'); ?>';

                        break;

                    case 'Per Month':

                        amount_title = '<?php echo app_lang('per_month_amount'); ?>';
                        installment_title = '<?php echo app_lang('no_of_month'); ?>';
                        claimable_title = '<?php echo app_lang('claimable_month'); ?>';

                        break;

                    case 'Per Term':

                        amount_title = '<?php echo app_lang('per_term_amount'); ?>';
                        installment_title = '<?php echo app_lang('no_of_term'); ?>';
                        claimable_title = '<?php echo app_lang('claimable_term'); ?>';

                        break;

                    case 'Per Trimester':

                        amount_title = '<?php echo app_lang('per_trimester_amount'); ?>';
                        installment_title = '<?php echo app_lang('no_of_trimester'); ?>';
                        claimable_title = '<?php echo app_lang('claimable_trimester'); ?>';

                        break;

                    case 'Per Semester':

                        amount_title = '<?php echo app_lang('per_semester_amount'); ?>';
                        installment_title = '<?php echo app_lang('no_of_semester'); ?>';
                        claimable_title = '<?php echo app_lang('claimable_semester'); ?>';

                        break;

                    case 'Per Week':

                        amount_title = '<?php echo app_lang('per_week_amount'); ?>';
                        installment_title = '<?php echo app_lang('no_of_week'); ?>';
                        claimable_title = '<?php echo app_lang('claimable_week'); ?>';

                        break;

                    default:

                        amount_title = '<?php echo app_lang('installment_amount'); ?>';
                        installment_title = '<?php echo app_lang('installments'); ?>';
                        claimable_title = '<?php echo app_lang('claimable_installments'); ?>';

                        break;
                }


                $('#amount-title').html(amount_title);
                $('#installments-title').html(installment_title);
                $('#claimable-title').html(claimable_title);

                $("input[name='amount_title']").val(amount_title);
                $("input[name='installments_title']").val(installment_title);
                $("input[name='claimable_title']").val(claimable_title);
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
            var charCode = (evt.which) ? evt.which : event.keyCode;
            var number = evt.value.split('.');
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
        };
    </script>