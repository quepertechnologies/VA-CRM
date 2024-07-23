<div class="card">
    <div class="bg-primary p30 rounded-top">
        <br />
    </div>
    <div class="clearfix text-center mb-1">
        <div class="mt-50 chart-circle">
            <canvas id="project-progress-chart"></canvas>
        </div>
    </div>

    <ul class="list-group list-group-flush">
        <li class="list-group-item border-top d-flex justify-content-between">
            <strong><?php echo app_lang("project_details") ?></strong>
            <div class="d-flex align-items-center justify-content-end">
                <?php echo modal_anchor(get_uri('projects/project_deadline_modal_form'), '<i data-feather="clock" class="icon-16"></i> ' . (is_dev_mode() ? "Set Project Dates" : app_lang("set_deadline")), array("class" => "btn btn-default btn-sm", "title" => app_lang("set_project_deadline"), "data-post-project_id" => $project_info->id)); ?>
            </div>
        </li>
        <li class="list-group-item border-top d-flex justify-content-between">
            <p><?php echo app_lang("start_date"); ?><?php echo !is_date_exists($project_info->start_date) ? "<br><small class='text-info'><i data-feather='info' class='icon-16'></i> Start date will be set automatically once you start the timer</small>" : "" ?></p>
            <strong><?php echo is_date_exists($project_info->start_date) ? format_to_date($project_info->start_date, false) : "-"; ?></strong>
        </li>
        <li class="list-group-item border-top d-flex justify-content-between">
            <p><?php echo app_lang("deadline"); ?></p>
            <strong><?php echo is_date_exists($project_info->deadline) ? format_to_date($project_info->deadline, false) : "-"; ?></strong>
        </li>
        <?php if ($login_user->user_type === "staff" && $project_info->project_type === "client_project") { ?>
            <li class="list-group-item border-top d-flex justify-content-between">
                <p><?php echo app_lang("client"); ?></p>
                <strong>
                    <?php
                    $name = '';
                    if (isset($project_info->company_name)) {
                        $name = $project_info->company_name;
                    } elseif (isset($project_info->first_name) && isset($project_info->last_name)) {
                        $name = $project_info->first_name . ' ' . $project_info->last_name;
                    }
                    echo anchor(get_uri("clients/view/" . $project_info->client_id), $name);
                    ?>
                </strong>
            </li>
        <?php } else { ?>
            <li class="list-group-item border-top">
                <?php echo app_lang("status"); ?>: <?php echo $project_info->title_language_key ? app_lang($project_info->title_language_key) : $project_info->status_title; ?>
            </li>
        <?php } ?>

        <li class="list-group-item border-top d-flex justify-content-between">
            <strong><?php echo app_lang("project_fees") ?></strong>
            <div class="d-flex align-items-center justify-content-end">
                <?php echo isset($project_fees->partners) ? modal_anchor(get_uri('projects/project_partners_revenue_overview'), '<i data-feather="list" class="icon-16"></i> ' . app_lang("overview"), array("class" => "btn btn-default btn-sm mx-2", "title" => app_lang("overview"), "data-post-project_id" => $project_info->id)) : ''; ?>
                <?php if ($login_user->user_type === "staff") {
                    echo modal_anchor(get_uri('projects/project_fees_modal_form'), '<i data-feather="edit" class="icon-16"></i> ' . app_lang("edit"), array("class" => "btn btn-default btn-sm", "title" => app_lang("project_fees"), "data-post-project_id" => $project_info->id));
                } ?>
            </div>
        </li>

        <li class="d-flex justify-content-between" style="padding: 0px 15px;">
            <p><?php echo app_lang("total_fee") ?></p>
            <strong><?php echo isset($project_fees) && $project_fees->total_fee ? to_currency($project_fees->total_fee) : to_currency(0) ?></strong>
        </li>

        <li class="d-flex justify-content-between" style="padding: 0px 15px;">
            <p class="text-danger"><?php echo app_lang("discount") ?></p>
            <strong class="text-danger"><?php echo isset($project_fees) && $project_fees->discount_total ? to_currency($project_fees->discount_total) : to_currency(0) ?></strong>
        </li>

        <li class="d-flex justify-content-between" style="padding: 0px 15px;">
            <p class="text-info"><?php echo app_lang("net_fee") ?></p>
            <strong class="text-info"><?php echo isset($project_fees) && $project_fees->net_total ? to_currency($project_fees->net_total) : to_currency(0) ?></strong>
        </li>

        <li class="list-group-item border-top d-flex justify-content-between">
            <strong><?php echo app_lang("sales_forecast") ?></strong>
            <?php echo isset($project_fees) ? modal_anchor(get_uri('projects/project_sales_forecast_modal_form'), '<i data-feather="edit" class="icon-16"></i> ' . app_lang("edit"), array("class" => "btn btn-default btn-sm", "title" => app_lang("sales_forecast"), "data-post-project_id" => $project_info->id)) : ''; ?>
        </li>

        <li class="d-flex justify-content-between" style="padding: 0px 15px;">
            <p><?php echo app_lang("institute_revenue") ?> <span class="badge" style="background-color: #E67E22;"><?php echo app_lang('payable'); ?></span></p>
            <strong><?php echo isset($project_fees) && $project_fees->institute_revenue ? to_currency($project_fees->institute_revenue) : to_currency(0) ?></strong>
        </li>

        <li class="d-flex justify-content-between" style="padding: 0px 15px;">
            <p><?php echo app_lang("referral_revenue") ?> <span class="badge" style="background-color: #E67E22;"><?php echo app_lang('payable'); ?></span></p>
            <strong><?php echo isset($project_fees) && $project_fees->referral_revenue ? to_currency($project_fees->referral_revenue) : to_currency(0) ?></strong>
        </li>

        <!-- <li class="d-flex justify-content-between" style="padding: 0px 15px;">
                <p><?php echo app_lang("total_revenue") ?></p>
                <strong><?php echo isset($project_fees) && $project_fees->total_revenue ? to_currency($project_fees->total_revenue) : to_currency(0) ?></strong>
            </li> -->

        <li class="d-flex justify-content-between border-top pt-2" style="padding: 0px 15px;">
            <p class="text-danger"><?php echo app_lang("discount") ?></p>
            <strong class="text-danger"><?php echo isset($project_fees) && $project_fees->revenue_discount ? to_currency($project_fees->revenue_discount) : to_currency(0) ?></strong>
        </li>

        <li class="d-flex justify-content-between" style="padding: 0px 15px;">
            <p class="text-info"><?php echo app_lang("net_revenue") ?></p>
            <strong class="text-info"><?php echo isset($project_fees) && $project_fees->net_revenue ? to_currency($project_fees->net_revenue) : to_currency(0) ?></strong>
        </li>

    </ul>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        var project_progress = <?php echo $project_progress; ?>;
        var projectProgressChart = document.getElementById("project-progress-chart");

        var textDirection = "<?php echo app_lang("text_direction"); ?>";
        var textAlign = "";
        if (textDirection === "rtl") {
            var textAlign = "center";
        }

        new Chart(projectProgressChart, {
            type: 'doughnut',
            data: {
                datasets: [{
                    label: 'Complete',
                    percent: project_progress,
                    backgroundColor: ['#6690F4'],
                    borderWidth: 0
                }]
            },
            plugins: [{
                    beforeInit: (chart) => {
                        const dataset = chart.data.datasets[0];
                        chart.data.labels = [dataset.label];
                        dataset.data = [dataset.percent, 100 - dataset.percent];
                    }
                },
                {
                    beforeDraw: (chart) => {
                        var width = chart.chart.width,
                            height = chart.chart.height,
                            ctx = chart.chart.ctx;
                        ctx.restore();
                        ctx.font = 1.5 + "em sans-serif";
                        ctx.fillStyle = "#9b9b9b";
                        ctx.textBaseline = "middle";
                        ctx.textAlign = textAlign;
                        var text = chart.data.datasets[0].percent + "%",
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;
                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            ],
            options: {
                maintainAspectRatio: false,
                cutoutPercentage: 90,
                rotation: Math.PI / 2,
                legend: {
                    display: false
                },
                tooltips: {
                    filter: tooltipItem => tooltipItem.index === 0,
                    callbacks: {
                        afterLabel: function(tooltipItem, data) {
                            var dataset = data['datasets'][0];
                            var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][Object.keys(dataset["_meta"])[0]]['total']) * 100);
                            return '(' + percent + '%)';
                        }
                    }
                }
            }
        });
    });
</script>