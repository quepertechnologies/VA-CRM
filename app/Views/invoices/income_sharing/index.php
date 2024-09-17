<div id="page-content" class="page-wrapper clearfix grid-button">
    <div class="card clearfix">
        <ul id="income-sharing-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab">
                <h4 class="pl15 pt10 pr15"><?php echo app_lang("income_sharing"); ?></h4>
            </li>
            <li><a id="unpaid-income-button" role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#unpaid-income"><?php echo app_lang("unpaid"); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("invoices/paid_income_sharing"); ?>" data-bs-target="#paid-income"><?php echo app_lang('paid'); ?></a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="unpaid-income">
                <div class="table-responsive">
                    <table id="unpaid-income-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="paid-income"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadIncomeTable = function(selector, income_type) {
        var customFilters = [{
                name: "partner_id",
                class: "w200",
                options: <?php echo $partners_dropdown; ?>
            }],
            rangeDatepicker = '';

        var optionVisibility = income_type == 'paid' ? false : true;
        var isUnpaidView = income_type == 'paid' ? true : false;

        if (income_type == 'paid') {
            customFilters.push({
                name: "paid_by",
                class: "w200",
                options: <?php echo $team_member_dropdown; ?>
            });

            rangeDatepicker = [{
                startDate: {
                    name: "start_date",
                    // value: moment().format("YYYY-MM-DD")
                },
                endDate: {
                    name: "end_date",
                    // value: moment().format("YYYY-MM-DD")
                },
                showClearButton: true
            }];
        }

        if ('<?php echo $login_user->user_type ?>' !== 'staff') {
            customFilters = [];
        }

        $(selector).appTable({
            source: '<?php echo_uri("invoices/income_list_data") ?>' + '/' + income_type,
            // dateRangeType: dateRange,
            serverSide: false,
            smartFilterIdentity: "all_" + income_type + "_invoices_list", //a to z and _ only. should be unique to avoid conflicts 
            dateRangeType: "",
            order: [
                [0, "desc"]
            ],
            filterDropdown: customFilters,
            rangeDatepicker,
            columns: [{
                    title: "<?php echo app_lang("invoice_id") ?>",
                    "class": "w10p all",
                    "iDataSort": 0
                },
                {
                    title: "<?php echo app_lang("partner") ?>",
                    "class": "",
                    "iDataSort": 1
                },
                {
                    title: "<?php echo app_lang("client") ?>",
                    "class": "w15p",
                    "iDataSort": 2
                },
                {
                    title: "<?php echo app_lang("project") ?>",
                    "class": "w15p",
                    "iDataSort": 2
                },
                {
                    title: "<?php echo app_lang("amount") ?>",
                    "class": "w10p text-right",
                    "iDataSort": 3
                },
                {
                    title: "<?php echo app_lang("tax") ?>",
                    "class": "w10p text-right",
                    "iDataSort": 4
                },
                {
                    title: "<?php echo app_lang("total_payment") ?>",
                    "class": "w10p text-right",
                    "iDataSort": 5
                },
                {
                    title: "<?php echo app_lang("paid_date") ?>",
                    "class": "w10p",
                    "iDataSort": 6,
                    visible: isUnpaidView
                },
                {
                    title: "<?php echo app_lang("paid_by") ?>",
                    "class": "w10p",
                    "iDataSort": 7,
                    visible: isUnpaidView
                },
                {
                    title: "<?php echo app_lang("status") ?>",
                    "class": "w10p",
                    "iDataSort": 8
                },
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center dropdown-option w100",
                    visible: optionVisibility
                }
            ],
            printColumns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
            xlsColumns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
            summation: [{
                    column: 4,
                    dataType: 'currency',
                    conversionRate: <?php echo $conversion_rate; ?>
                },
                {
                    column: 5,
                    dataType: 'currency',
                    conversionRate: <?php echo $conversion_rate; ?>
                },
                {
                    column: 6,
                    dataType: 'currency',
                    conversionRate: <?php echo $conversion_rate; ?>
                }
            ]
        });
    };
    $(document).ready(function() {
        loadIncomeTable("#unpaid-income-table", "");
    });
</script>