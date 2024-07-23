<?php echo form_open(get_uri("projects/save_project_fees"), array("id" => "project-fee-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-4">
                <p>
                    <strong>
                        <?php echo app_lang('fee_option_name'); ?>
                    </strong>
                    <br />
                    <?php echo isset($modal_info->fee_option_name) ? $modal_info->fee_option_name : 'Default Fee'; ?>
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <strong>
                        <?php echo app_lang('country_of_residency'); ?>
                    </strong>
                    <br />
                    <?php echo isset($modal_info->country_of_residency) ? $modal_info->country_of_residency : 'All Countries'; ?>
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <strong>
                        <?php echo app_lang('installment_type'); ?>
                    </strong>
                    <br />
                    <?php echo isset($modal_info->installment_type) ? $modal_info->installment_type : 'N/A'; ?>
                </p>
            </div>
            <!-- <div class="col-md-4">
                <p>
                    <strong>
                        <?php echo app_lang('installment_start_date'); ?>
                    </strong>
                    <br />
                    <?php echo isset($modal_info->installment_start_date) ? format_to_date($modal_info->installment_start_date, false) : 'N/A'; ?>
                </p>
            </div> -->
            <div class="col-md-4">
                <p>
                    <strong>
                        <?php echo app_lang('first_invoice_date'); ?>
                    </strong>
                    <br />
                    <?php echo isset($modal_info->first_invoice_date) ? format_to_date($modal_info->first_invoice_date, false) : 'N/A'; ?>
                </p>
            </div>
        </div>
        <div class='row col-md-12 border-top py-3'>
            <div class="col-md-5"><strong><?php echo app_lang("fee_type"); ?></strong></div>
            <div class="col-md-3"><strong id='amount-title'><?php echo isset($modal_info->amount_title) ? $modal_info->amount_title : app_lang("installment_amount"); ?></strong></div>
            <div class="col-md-2 text-right"><strong id='installments-title'><?php echo isset($modal_info->installments_title) ? $modal_info->installments_title : app_lang("installments"); ?></strong></div>
            <div class="col-md-2 text-right"><strong id='total-title'><?php echo app_lang("total"); ?></strong></div>
            <!-- <div class="col-md-2"><strong id='claimable-title'><?php echo isset($modal_info->claimable_title) ? $modal_info->claimable_title : app_lang("claimable_installments"); ?></strong></div> -->
        </div>
        <div class="border-top pb-3">
            <?php
            if ($modal_info->fees && count($modal_info->fees)) {
                foreach ($modal_info->fees as $fee) {
                    $fee = json_decode(json_encode($fee), true); //? convert array to associative array
            ?>
                    <div class='row col-md-12 pt-3'>
                        <div class="col-md-5">
                            <?php
                            echo $fee['fee_type'];
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            echo to_currency($fee['amount']);
                            ?>
                        </div>
                        <div class="col-md-2 text-right">
                            <?php
                            echo $fee['installments'];
                            ?>x
                        </div>
                        <div class="col-md-2 text-right">
                            <?php
                            echo to_currency($fee['row_total']);
                            ?>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <div class='row col-md-12 pb-3'>
            <div class="col-md-5 d-flex align-items-center">
                <strong><?php echo app_lang("discount") ?></strong>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <?php
                echo to_currency(isset($modal_info->discount) ? $modal_info->discount : 0);
                ?>
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-end">
                <?php
                echo isset($modal_info->discount_installments) ? $modal_info->discount_installments : 1;
                ?>x
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-end"><span class="text-danger" id="discount-total"><?php echo isset($modal_info->discount_total) ?  to_currency((float)$modal_info->discount_total) : '0.00'; ?></span></div>
        </div>
        <div class='row col-md-12 border-top py-3'>
            <div class="col-md-10">
                <div class="d-flex align-items-center justify-content-end">
                    <strong><?php echo app_lang('net_total') ?></strong>
                </div>
            </div>

            <div class="col-md-2 d-flex align-items-center justify-content-end"><span class="text-info" id="net-total"><?php echo isset($modal_info->net_total) ? to_currency((float)$modal_info->net_total) : '0.00'; ?></span></div>
        </div>

        <div class='row col-md-12 border-top py-3'>
            <div class="col-md-5"><strong><?php echo app_lang("partner"); ?></strong></div>
            <div class="col-md-3"><strong><?php echo app_lang("fee_type"); ?></strong></div>
            <!-- <div class="col-md-3 text-right"><strong><?php echo isset($modal_info->amount_title) ? $modal_info->amount_title : app_lang("installment_amount"); ?></strong></div> -->
            <div class="col-md-2 text-right"><strong><?php echo isset($modal_info->claimable_title) ? $modal_info->claimable_title : app_lang("claimable_installments"); ?></strong></div>
            <div class="col-md-2 text-right"><strong><?php echo app_lang("total"); ?></strong></div>
        </div>
        <div class="border-top pb-3">
            <?php
            if ($modal_info->partners && count($modal_info->partners)) {
                foreach ($modal_info->partners as $partner) {
                    $partner = json_decode(json_encode($partner), true); //? convert array to associative array

                    $fee_types = '';
                    $amounts = '';
                    $claimable_installments = '';

                    if (get_array_value($partner, 'revenues') && count($partner['revenues'])) {
                        for ($x = 0; $x < count($partner['revenues']); $x++) {
                            $revenue = $partner['revenues'][$x];

                            if ($x > 0) {
                                $fee_types .= '<br>';
                                $amounts .= '<br>';
                                $claimable_installments .= '<br>';
                            }

                            $fee_types .= '<small>• ' . $revenue['fee_type'] . '</small>';
                            $amounts .= '<small>' . to_currency($revenue['amount']) . '</small>';
                            $claimable_installments .= '<small>' . $revenue['claimable_installments'] . 'x</small>';
                        }
                    }
            ?>
                    <div class='row col-md-12 pt-3'>
                        <div class="col-md-5">
                            <?php
                            echo get_client_contact_profile_link($partner['partner_id'], $partner['full_name'], array(), array('account_type' => 3, 'caption' => 'Commission: ' . $partner['commission'] . '%'));
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            echo $fee_types
                            ?>
                        </div>
                        <div class="col-md-2 text-right">
                            <?php
                            echo $claimable_installments;
                            ?>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-end">
                            <?php
                            echo to_currency($partner['revenue']);
                            ?>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <div class='row col-md-12 border-top pt-3'>
            <div class="col-md-10">
                <div class="d-flex align-items-center justify-content-end">
                    <strong><?php echo app_lang('partner_revenue') ?></strong>
                </div>
            </div>

            <div class="col-md-2 d-flex align-items-center justify-content-end"><span><?php echo isset($modal_info->total_revenue) ? to_currency((float)$modal_info->total_revenue) : '0.00'; ?></span></div>
        </div>
        <div class='row col-md-12'>
            <div class="col-md-10">
                <div class="d-flex align-items-center justify-content-end">
                    <strong><?php echo app_lang('discount') ?></strong>
                </div>
            </div>

            <div class="col-md-2 d-flex align-items-center justify-content-end"><span class="text-danger"><?php echo isset($modal_info->revenue_discount) ? to_currency((float)$modal_info->revenue_discount) : '0.00'; ?></span></div>
        </div>
        <div class='row col-md-12 pb-3'>
            <div class="col-md-10">
                <div class="d-flex align-items-center justify-content-end">
                    <strong><?php echo app_lang('net_revenue') ?></strong>
                </div>
            </div>

            <div class="col-md-2 d-flex align-items-center justify-content-end"><span class="text-info"><?php echo isset($modal_info->net_revenue) ? to_currency((float)$modal_info->net_revenue) : '0.00'; ?></span></div>
        </div>
    </div>

    <div class="modal-footer">

        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>

    </div>
    <?php echo form_close(); ?>