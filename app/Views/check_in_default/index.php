<div id="page-content" class="page-wrapper clearfix">
    <div class="clearfix grid-button">
        <ul id="client-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#waiting-list"><?php echo app_lang('waiting'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/attending_list/"); ?>" data-bs-target="#attending-list"><?php echo app_lang('attending'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/completed_list/"); ?>" data-bs-target="#completed-list"><?php echo app_lang('completed'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/all_list/"); ?>" data-bs-target="#all-list"><?php echo app_lang('all'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/archived_list/"); ?>" data-bs-target="#archived-list"><?php echo app_lang('archived'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php
                    echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-outline-light", "title" => app_lang('manage_labels'), "data-post-type" => "client"));
                    echo modal_anchor(get_uri("check_in/import_clients_modal_form"), "<i data-feather='upload' class='icon-16'></i> " . app_lang('import_clients'), array("class" => "btn btn-default", "title" => app_lang('import_clients')));
                    echo modal_anchor(get_uri("check_in/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_checkin'), array("class" => "btn btn-default", "title" => app_lang('checkin')));
                    ?>
                </div>
            </div>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="waiting-list"><?php echo view("check_in/waiting_list") ?></div>
            <div role="tabpanel" class="tab-pane fade" id="attending-list"></div>
            <div role="tabpanel" class="tab-pane fade" id="completed-list"></div>
            <div role="tabpanel" class="tab-pane fade" id="all-list"></div>
            <div role="tabpanel" class="tab-pane fade" id="archived-list"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {});
</script>