<div id="page-content" class="page-wrapper clearfix">
    <div class="card grid-button">
        <div class="page-title clearfix projects-page">
            <h1><?php echo app_lang('projects'); ?></h1>
            <div class="title-button-group">
                <?php
                if ($can_create_projects) {
                    if ($can_edit_projects) {
                        echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-default", "title" => app_lang('manage_labels'), "data-post-type" => "project"));
                    }

                    echo modal_anchor(get_uri("projects/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_project'), array("class" => "btn btn-default", "title" => app_lang('add_project')));
                }
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="project-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var optionVisibility = false;
        if ("<?php echo ($can_edit_projects || $can_delete_projects); ?>") {
            optionVisibility = true;
        }


        var ignoreSavedFilter = false;
        <?php if (isset($selected_status_id) && $selected_status_id) { ?>
            ignoreSavedFilter = true;
        <?php } ?>


        $("#project-table").appTable({
            source: '<?php echo_uri("projects/list_data") ?>',
            serverSide: true,
            order: [
                [0, "desc"]
            ],
            responsive: false, //hide responsive (+) icon
            smartFilterIdentity: "all_projects_list", //a to z and _ only. should be unique to avoid conflicts 
            ignoreSavedFilter: ignoreSavedFilter,
            multiSelect: [{
                name: "status_id",
                class: "w200",
                text: "<?php echo app_lang('status'); ?>",
                options: <?php echo view("project_status/project_status_dropdown", array("project_statuses" => $project_statuses, "selected_status_id" => $selected_status_id, "selected_status_key" => is_dev_mode() ? array("open", 'completed', 'hold', 'canceled') : array("open", 'completed', 'hold'))); ?>
            }],
            filterDropdown: [{
                    name: "project_label",
                    class: "w200",
                    options: <?php echo $project_labels_dropdown; ?>
                },
                {
                    name: "workflow_id",
                    class: "w200",
                    options: <?php echo $workflows_dropdown; ?>,
                    dependent: ["current_milestone_title"]
                },
                {
                    name: "current_milestone_title",
                    class: "w200",
                    options: [{
                        id: "",
                        text: "- <?php echo app_lang('milestone'); ?> -"
                    }],
                    dependency: ["workflow_id"],
                    dataSource: '<?php echo_uri("projects/get_milestones_for_filter") ?>'
                }, //milestone is dependent on workflow
                {
                    name: "partner_id",
                    class: "w200",
                    options: <?php echo $partners_dropdown; ?>
                },
                <?php echo $custom_field_filters; ?>
            ],
            rangeDatepicker: [{
                startDate: {
                    name: "start_date_from",
                    // value: moment().startOf('month').format("YYYY-MM-DD")
                },
                endDate: {
                    name: "start_date_to",
                    // value: moment().endOf('month').format('YYYY-MM-DD')
                },
                showClearButton: true,
                label: "<?php echo app_lang('start_date'); ?>",
                ranges: ['this_month', 'last_month', 'this_year', 'last_year', 'next_7_days', 'next_month']
            }],
            singleDatepicker: [{
                name: "deadline",
                class: "w200",
                defaultText: "<?php echo app_lang('deadline') ?>",
                options: [{
                        value: "expired",
                        text: "<?php echo app_lang('expired') ?>"
                    },
                    {
                        value: moment().format("YYYY-MM-DD"),
                        text: "<?php echo app_lang('today') ?>"
                    },
                    {
                        value: moment().add(1, 'days').format("YYYY-MM-DD"),
                        text: "<?php echo app_lang('tomorrow') ?>"
                    },
                    {
                        value: moment().add(7, 'days').format("YYYY-MM-DD"),
                        text: "<?php echo sprintf(app_lang('in_number_of_days'), 7); ?>"
                    },
                    {
                        value: moment().add(15, 'days').format("YYYY-MM-DD"),
                        text: "<?php echo sprintf(app_lang('in_number_of_days'), 15); ?>"
                    }
                ]
            }],
            columns: [{
                    title: '<?php echo app_lang("id") ?>',
                    "class": "all w10p",
                    visible: true,
                    order_by: "id",
                    order_dir: "DESC"
                },
                {
                    title: '<?php echo app_lang("title") ?>',
                    "class": "all",
                    visible: true
                },
                // {
                //     title: '<?php echo app_lang("deadline") ?>',
                //     "class": "w10p",
                //     "iDataSort": 2,
                //     visible: true
                // },
                {
                    title: '<?php echo app_lang("client") ?>',
                    "class": "w10p",
                    visible: true
                },
                // {
                //     title: '<?php echo app_lang("phone") ?>',
                //     "class": "w10p",
                //     visible: true
                // },
                {
                    title: '<?php echo app_lang("assignee").'s' ?>',
                    "class": "w10p",
                    visible: true
                },
                // {
                //     title: '<?php echo app_lang("application_owner") ?>',
                //     "class": "w10p",
                //     visible: true
                // },
                {
                    title: '<?php echo app_lang("workflow") ?>',
                    "class": "w10p",
                    visible: true
                },
                {
                    title: '<?php echo app_lang("current_stage") ?>',
                    "class": "w10p",
                    visible: true
                },
                {
                    title: '<?php echo app_lang("partner") ?>',
                    "class": "w10p",
                    visible: true
                },
                {
                    title: '<?php echo app_lang("progress") ?>',
                    "class": "w10p",
                    visible: true
                },
                // <?php echo $custom_field_headers; ?>,
                {
                    title: '<i data-feather="menu" class="icon-16"></i>',
                    "class": "text-center option w100",
                    visible: optionVisibility
                }
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], '<?php echo $custom_field_headers; ?>')
        });
    });
</script>