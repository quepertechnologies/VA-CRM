<!-- don't load the panel for 2nd time -->
<?php
if ($offset) {
    echo milestone_activity_logs_widget($activity_logs_params, $milestones);
} else {
?>
    <div class="card">
        <div class="card-body">
            <?php echo milestone_activity_logs_widget($activity_logs_params, $milestones, $remaining_check_list_count); ?>
        </div>
    </div>
<?php
} ?>