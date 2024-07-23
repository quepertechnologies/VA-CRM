<?php if ($page_view) { ?>
    <div id="page-content" class="page-wrapper clearfix">
    <?php } ?>
    <div class="clearfix grid-button mt-3">
        <ul id="file-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="nav-item title-tab">
                <h4 class="pl15 pt10 pr15"><?php echo app_lang("files"); ?></h4>
            </li>

            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#check-list"><?php echo app_lang('checklist'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#uploaded-files-list"><?php echo app_lang('uploaded_documents'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php
                    if (
                        (int)$client_info->doc_check_list_id == 0
                    ) {
                        echo modal_anchor(get_uri("partners/checklist_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_checklist'), array("class" => "btn btn-default", "title" => app_lang('add_checklist'), "data-post-client_id" => $client_id));
                    } elseif (
                        (
                            $login_user->user_type == "staff" ||
                            (
                                $login_user->user_type == "client" &&
                                get_setting("client_can_add_files")
                            )
                        )
                    ) {
                        echo modal_anchor(get_uri("partners/file_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_files'), array("class" => "btn btn-default", "title" => app_lang('add_files'), "data-post-client_id" => $client_id, 'data-post-full_check_list' => '1'));
                    }
                    ?>
                </div>
            </div>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="check-list">
                <?php echo view("partners/files/check_list"); ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="uploaded-files-list">
                <?php echo view("partners/files/uploaded_files_list"); ?>
            </div>
        </div>
    </div>
    <?php if ($page_view) { ?>
    </div>
<?php } ?>