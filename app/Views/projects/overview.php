<div class="clearfix default-bg">

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 col-sm-12">
                <?php echo view("projects/project_description"); ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <?php if (get_setting('module_project_timesheet')) { ?>
                    <div class="col-md-12 col-sm-12">
                        <?php echo view("projects/widgets/total_hours_worked_widget"); ?>
                    </div>
                <?php } ?>
                <div class="col-md-12 col-sm-12">
                    <?php echo view("projects/history/overview_tabs"); ?>
                </div>

                <div class="col-md-12 col-sm-12 project-custom-fields">
                    <?php echo view('projects/custom_fields_list', array("custom_fields_list" => $custom_fields_list)); ?>
                </div>

                <?php if ($project_info->estimate_id) { ?>
                    <div class="col-md-12 col-sm-12">
                        <?php echo view("projects/estimates/index"); ?>
                    </div>
                <?php } ?>

                <?php if ($project_info->order_id) { ?>
                    <div class="col-md-12 col-sm-12">
                        <?php echo view("projects/orders/index"); ?>
                    </div>
                <?php } ?>

            </div>
        </div>
        <div class="col-md-4">
            <div class="col-md-12 col-sm-12">
                <?php echo view("projects/project_progress_chart_info"); ?>
            </div>
            <div class="col-md-12 col-sm-12">
                <?php echo view("projects/project_task_pie_chart"); ?>
            </div>
            <?php if ($can_access_clients && $project_info->project_type === "client_project") { ?>
                <div class="col-md-12 col-sm-12">
                    <?php echo view("projects/client_contacts/index"); ?>
                </div>
            <?php } ?>
            <?php if ($can_add_remove_project_members) { ?>
                <div class="col-md-12 col-sm-12">
                    <?php echo view("projects/partners/index"); ?>
                </div>
            <?php } ?>
            <?php if ($can_add_remove_project_members) { ?>
                <div class="col-md-12 col-sm-12">
                    <?php echo view("projects/project_members/index"); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>