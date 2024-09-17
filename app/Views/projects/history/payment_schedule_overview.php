<div class="col-md-12 d-flex flex-wrap justify-content-between mt-4">
    <div class="col-md-3">
        <div class="card mr-4">
            <div class="card-body">
                <span>
                    <?php echo app_lang('scheduled') ?>
                    <br>
                    <h3><strong><?php echo to_currency($project_fees->scheduled_amount); ?></strong></h3>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mx-4">
            <div class="card-body">
                <span>
                    <?php echo app_lang('invoiced') ?>
                    <br>
                    <h3><strong><?php echo to_currency($project_fees->invoiced_amount); ?></strong></h3>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card ml-4">
            <div class="card-body">
                <span>
                    <?php echo app_lang('pending') ?>
                    <br>
                    <h3><strong><?php echo to_currency($project_fees->pending_amount); ?></strong></h3>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="project-payment-schedule-list-table" class="display" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#project-payment-schedule-list-table").appTable({
            source: '<?php echo_uri("projects/project_payment_schedule_list_data/" . $project_id) ?>',
            order: [],
            serverSide: true,
            smartFilterIdentity: "project_payment_schedule_list", //a to z and _ only. should be unique to avoid conflicts
            columns: [{
                    visible: false,
                    searchable: false
                }, {
                    title: '<?php echo app_lang("installment") ?>',
                    order_by: 'sort'
                },
                {
                    title: '<?php echo app_lang("fee_type") ?>',
                },
                {
                    title: '<?php echo app_lang("fee") ?>',
                },
                {
                    title: '<?php echo app_lang("total_fee") ?>',
                },
                {
                    title: '<?php echo app_lang("discounts") ?>',
                },
                {
                    title: '<?php echo app_lang("invoicing") ?>',
                    order_by: 'invoice_date',
                    // type: "date"
                },
                {
                    title: '<?php echo app_lang("status") ?>',
                    order_by: "status"
                },
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center w100",
                    searchable: false,
                    sortable: false
                }
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>