<?php
$task_title = array();
$task_data = array();
$task_status_color = array();
foreach ($task_statuses as $status) {
    $task_title[] = $status->key_name ? app_lang($status->key_name) : $status->title;
    $task_data[] = $status->total;
    $task_status_color[] = $status->color;
}
?>

<div class="card">
    <div class="card-body">
        <?php if (count($task_title)) { ?>
            <canvas id="task-status-chart" width="512"></canvas>
        <?php } else { ?>
            <div style="width: 100%; height: 25vh; display: flex;" class="text-center">
                <div style="margin: auto;">
                    <i data-feather="pie-chart" class="icon-24"></i>
                    <h3>Tasks Overview</h3>
                    <p>No Task created yet</p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    //for task status chart
    var labels = <?php echo json_encode($task_title) ?>;
    var taskData = <?php echo json_encode($task_data) ?>;
    var taskStatuscolor = <?php echo json_encode($task_status_color) ?>;
    var taskStatusChart = document.getElementById("task-status-chart");

    if (taskStatusChart) {
        new Chart(taskStatusChart, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: taskData,
                    backgroundColor: taskStatuscolor,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                    callbacks: {
                        afterLabel: function(tooltipItem, data) {
                            var dataset = data['datasets'][0];
                            var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][Object.keys(dataset["_meta"])[0]]['total']) * 100);
                            return '(' + percent + '%)';
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: "#898fa9"
                    }
                },
                animation: {
                    animateScale: true
                }
            }
        });
    }
</script>