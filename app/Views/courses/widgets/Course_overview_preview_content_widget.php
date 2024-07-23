<div id="page-content" class="page-wrapper clearfix">
    <div class="clearfix grid-button">
        <ul id="client-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#overview"><?php echo app_lang('overview'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#admission_requirements"><?php echo app_lang('admission_requirements'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#course_dates"><?php echo app_lang('course_dates'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#scholarships"><?php echo app_lang('scholarships'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#custom_tab_1"><?php echo app_lang('custom_tab_1'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#custom_tab_2"><?php echo app_lang('custom_tab_2'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#custom_tab_3"><?php echo app_lang('custom_tab_3'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#custom_tab_4"><?php echo app_lang('custom_tab_4'); ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="overview">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab1"); ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="admission_requirements">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab2"); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="course_dates">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab3"); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="scholarships">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab4"); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="custom_tab_1">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab5"); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="custom_tab_2">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab6"); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="custom_tab_3">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab7"); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="custom_tab_4">
                <?php echo view("courses/widgets/PreviewContentTabs/Tab8"); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {});
</script>