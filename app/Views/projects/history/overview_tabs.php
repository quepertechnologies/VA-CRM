<div class="">
    <ul id="project-activity-overview-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
        <!-- <li class="nav-item title-tab">
            <h4 class="pl15 pt10 pr15"><?php echo app_lang("activity"); ?></h4>
        </li> -->
        <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#project-activities"><?php echo app_lang('activities'); ?></a></li>
        <li class="nav-item"><a class="nav-link" role="presentation" href="javascript:;" data-bs-target="#project-payment-schedule"><?php echo app_lang("payment_schedule"); ?></a></li>

        <?php if ($login_user->user_type === "staff") { ?>
            <div class="tab-title clearfix no-border d-none" id='payment-schedule-btn'>
                <div class="title-button-group">
                    <!-- <div>
                        <?php echo modal_anchor(get_uri('projects/preview_schedule_modal_form'), '<i data-feather="eye" class="icon-16"></i> ' . app_lang("preview"), array("class" => "btn btn-primary btn-sm", "title" => app_lang("preview_schedule"), "data-post-project_id" => $project_info->id)); ?>
                        <?php echo modal_anchor(get_uri('projects/email_schedule_modal_form'), '<i data-feather="send" class="icon-16"></i> ' . app_lang("email"), array("class" => "btn btn-primary btn-sm", "title" => app_lang("email_schedule"), "data-post-project_id" => $project_info->id)); ?>
                    </div> -->
                    <?php echo modal_anchor(get_uri('projects/schedule_modal_form'), '<i data-feather="plus-circle" class="icon-16"></i> ' . app_lang("add_schedule"), array("class" => "btn btn-primary", "title" => app_lang("add_payment_schedule"), "data-post-project_id" => $project_info->id, 'data-modal-lg' => true)); ?>
                </div>
            </div>
        <?php } ?>

    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="project-activities"><?php echo view('projects/history/index') ?></div>
        <div role="tabpanel" class="tab-pane fade" id="project-payment-schedule"><?php echo view('projects/history/payment_schedule_overview') ?></div>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function() {
        //change the add button attributes on changing tab panel
        var btnContainer = $("#payment-schedule-btn");
        $("#project-activity-overview-tabs li").click(function() {
            var activeField = $(this).find("a").attr("data-bs-target");
            if (activeField === "#project-activities") {
                btnContainer.addClass('d-none');
            } else if (activeField === "#project-payment-schedule") {
                btnContainer.removeClass('d-none');
            }
        });
    });
</script>