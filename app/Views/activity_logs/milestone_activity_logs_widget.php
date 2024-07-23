<div class="accordion accordion-flush" id="accordionFlushExample">
    <?php
    foreach (array_reverse($milestones) as $milestone) {
        $title = $milestone->title;
        $logs = array();

        foreach ($activity_logs as $key => $activity) {
            if ($activity->log_for3 == 'milestone' && $activity->log_for_id3 == $milestone->id) {
                $logs[] = $activity;
            }
        }
        $unique_key = uniqid('-va-');
    ?>
        <div class="accordion-item">
            <div class="accordion-header" id="flush-heading<?php echo $unique_key; ?>">
                <div class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $unique_key; ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $unique_key; ?>">
                    <?php
                    if ($milestone->is_current == 1) {
                        echo '<i data-feather="play-circle" class="icon-16 float-left text-info"></i>';
                    } elseif ($milestone->is_current == 2) {
                        echo '<i data-feather="check-circle" class="icon-16 float-left text-success"></i>';
                    } else {
                        echo '<i data-feather="circle" class="icon-16 float-left"></i>';
                    }
                    ?>
                    <h6 style="margin-left: 8px;">
                        <?php echo ucfirst($title); ?>
                        <?php // echo $milestone->is_doc_check_list ? '<strong>(' . $remaining_check_list_count . ' Documents Remaining)</strong>' : ''; 
                        ?>
                        <?php if ($milestone->due_date) {
                            $now = time(); // or your date as well
                            $due_date = strtotime($milestone->due_date);
                            $date_diff = $due_date - $now;

                            $remaining_days = round($date_diff / (60 * 60 * 24));

                            if ($remaining_days == 0 || $remaining_days < 0) {
                                echo '<br><small class="text-danger">Deadline reached ' . ($remaining_days == 0 ? "Today" : abs($remaining_days) . ' days ago on ' . format_to_date($milestone->due_date)) . '</small>';
                            } elseif ($remaining_days > 0 && $remaining_days < 10) {
                                echo '<br><small class="text-warning">Deadline in ' . abs($remaining_days) . ' days on ' . format_to_date($milestone->due_date) . '</small>';
                            } elseif ($remaining_days > 0 && $remaining_days < 20) {
                                echo '<br><small class="text-info">Deadline in ' . abs($remaining_days) . ' days on ' . format_to_date($milestone->due_date) . '</small>';
                            } else {
                                echo '<br><small class="text-secondary">Deadline in ' . abs($remaining_days) . ' days on ' . format_to_date($milestone->due_date) . '</small>';
                            }
                        } ?></h6>
                    <?php
                    if (is_dev_mode()) {
                        echo ' ' . ajax_anchor(get_uri('projects/save_active_milestone_dev'), '<i data-feather="clock" class="icon-16"></i> Set as Active', array('class' => "btn btn-default btn-sm", 'title' => '', "data-post-project_id" => $project_id, "data-post-milestone_id" => $milestone->id, 'data-reload-on-success' => true));
                    }
                    ?>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <?php echo is_dev_mode() ? modal_anchor(get_uri('projects/activities_modal_form'), '<i data-feather="plus-circle" class="icon-16"></i> ' . "Add Activity", array("class" => "btn btn-default btn-sm", "title" => '', "data-post-project_id" => $project_id, "data-post-milestone_id" => $milestone->id)) : '' ?>
                    <?php echo modal_anchor(get_uri('projects/deadline_modal_form'), '<i data-feather="clock" class="icon-16"></i> ' . app_lang("set_deadline"), array("class" => "btn btn-default btn-sm", "title" => ucfirst($title), "data-post-project_id" => $project_id, "data-post-milestone_id" => $milestone->id)); ?>
                    <?php echo modal_anchor(get_uri('projects/email_modal_form'), '<i data-feather="send" class="icon-16"></i> ' . app_lang("send_email"), array("class" => "btn btn-default btn-sm", "title" => app_lang("send_email"), "data-post-project_id" => $project_id, "data-post-milestone_id" => $milestone->id)); ?>
                    <?php // echo modal_anchor(get_uri("projects/file_modal_form"), "<i data-feather='file-plus' class='icon-16'></i> " . app_lang('add_files'), array("class" => "btn btn-default btn-sm", "title" => app_lang('add_files'), "data-post-full_check_list" => '1', "data-post-project_id" => $project_id, "data-post-milestone_id" => $milestone->id)); 
                    ?>
                    <?php echo modal_anchor(get_uri("notes/modal_form"), "<i data-feather='book-open' class='icon-16'></i> " . app_lang('add_note'), array("class" => "btn btn-default btn-sm", "title" => app_lang('add_note'), "data-post-project_id" => $project_id, "data-post-milestone_id" => $milestone->id)); ?>
                    <?php echo modal_anchor(get_uri("events/modal_form"), "<i data-feather='calendar' class='icon-16'></i> " . app_lang('add_event'), array("class" => "btn btn-default btn-sm", "id" => "add_event_hidden", "title" => app_lang('add_event'), "data-post-client_id" => $project_info->client_id, "data-post-project_id" => $project_id, "data-post-milestone_id" => $milestone->id)); ?>
                </div>
            </div>
            <div id="flush-collapse<?php echo $unique_key; ?>" class="accordion-collapse collapse <?php echo $milestone->is_current == 1 ? "show" : ""; ?>" aria-labelledby="flush-heading<?php echo $unique_key; ?>" data-bs-parent="#accordionFlushExample">
                <?php
                foreach ($logs as $log) {
                    $changes_array = get_change_logs_array($log->changes, $log->log_type, $log->action);

                    if ($log->action !== "updated" || (count($changes_array) && $log->changes !== "" && ($log->action === "updated" || $log->action === "bitbucket_notification_received" || $log->action === "github_notification_received"))) {
                ?>
                        <div class="accordion-body d-flex border-bottom mb-3">
                            <div class="flex-shrink-0 me-2 mt-3">
                                <span class="avatar avatar-xs">
                                    <?php if ($log->created_by_user) { ?>
                                        <img src="<?php echo get_avatar($log->created_by_avatar); ?>" alt="..." />
                                    <?php } else if ($log->action === "bitbucket_notification_received") { ?>
                                        <img src="<?php echo get_avatar("bitbucket"); ?>" alt="..." />
                                    <?php } else if ($log->action === "github_notification_received") { ?>
                                        <img src="<?php echo get_avatar("github"); ?>" alt="..." />
                                    <?php } else { ?>
                                        <img src="<?php echo get_avatar("system_bot"); ?>" alt="..." />
                                    <?php } ?>
                                </span>
                            </div>
                            <div class="p-2 w-100">
                                <div class="card-title">
                                    <?php
                                    if ($log->created_by_user) {
                                        if ($log->user_type === "staff") {
                                            echo get_team_member_profile_link($log->created_by, $log->created_by_user, array("class" => "dark strong"));
                                        } else {
                                            echo get_client_contact_profile_link($log->created_by, $log->created_by_user, array("class" => "dark strong"));
                                        }
                                    } else if ($log->action === "bitbucket_notification_received") {
                                        echo "<strong>Bitbucket</strong>";
                                    } else if ($log->action === "github_notification_received") {
                                        echo "<strong>GitHub</strong>";
                                    } else {
                                        echo "<strong>" . get_setting("app_title") . "</strong>";
                                    }
                                    ?>
                                    <small><span class="text-off"><?php echo format_to_relative_time($log->created_at); ?></span></small>
                                </div>

                                <p>
                                    <?php
                                    $label_class = 'default';
                                    if ($log->action === "created") {
                                        $label_class = "success";
                                        $log->action = "added";
                                    } else if ($log->action === "updated") {
                                        $label_class = "warning";
                                    } else if ($log->action === "deleted") {
                                        $label_class = "danger";
                                    } else if ($log->action === 'send') {
                                        $label_class = "info";
                                    } else if ($log->action === 'signed') {
                                        $label_class = "success";
                                    } else if ($log->action === 'declined') {
                                        $label_class = "warning";
                                    }

                                    $log_caption = app_lang($log->action);

                                    if ($log->action === "bitbucket_notification_received" || $log->action === "github_notification_received") {
                                        $log_caption = app_lang("code_reference");
                                        $label_class = "info";
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $label_class; ?>"><?php echo $log_caption; ?></span>
                                    <span class="text-break"><?php
                                                                if ($log->log_type === "project_file") {
                                                                    echo app_lang($log->log_type) . ": " . remove_file_prefix(convert_mentions($log->log_type_title));
                                                                } else if ($log->action != "bitbucket_notification_received" && $log->action != "github_notification_received") {
                                                                    $log_type = $log->log_type;
                                                                    if ($log_type === 'milestone') {
                                                                        $log_type = 'stage';
                                                                    }
                                                                    if ($log->log_type === "task") {
                                                                        echo app_lang($log_type) . ": " . modal_anchor(get_uri("tasks/view"), " #" . $log->log_type_id . " - " . convert_mentions(convert_comment_link(process_images_from_content($log->log_type_title))), array("title" => app_lang('task_info') . " #$log->log_type_id", "class" => "dark", "id" => "task-modal-view-link", "data-post-id" => $log->log_type_id, "data-modal-lg" => "1"));
                                                                    } else {
                                                                        echo app_lang($log_type) . ": " . convert_mentions(convert_comment_link(process_images_from_content($log->log_type_title)));
                                                                    }
                                                                }
                                                                ?></span>
                                    <?php
                                    if (count($changes_array)) {
                                        if ($log->action === "bitbucket_notification_received" || $log->action === "github_notification_received") {
                                            echo get_array_value($changes_array, 0);
                                            unset($changes_array[0]);
                                        }

                                        echo "<ul>";
                                        foreach ($changes_array as $change) {
                                            echo process_images_from_content($change);
                                        }
                                        echo "</ul>";
                                    }
                                    ?>
                                </p>


                                <?php if ($log->log_for2 && $log->log_for2 != "customer_feedback") { ?>
                                    <p> <?php
                                        if ($log->log_for2 === "task") {
                                            echo app_lang($log->log_for2) . ": " . modal_anchor(get_uri("tasks/view"), " #" . $log->log_for_id2, array("title" => app_lang('task_info') . " #$log->log_for_id2", "class" => "dark", "data-post-id" => $log->log_for_id2, "data-modal-lg" => "1"));
                                        } else {
                                            echo app_lang($log->log_for2) . ": #" . $log->log_for_id2;
                                        }
                                        ?>
                                    </p>
                                <?php } ?>

                                <?php if ($log->action === "bitbucket_notification_received" || $log->action === "github_notification_received") { ?>
                                    <p> <?php echo app_lang($log->log_type) . ": " . modal_anchor(get_uri("tasks/view"), " #" . $log->log_type_id . " - " . convert_mentions($log->log_type_title), array("title" => app_lang('task_info') . " #$log->log_type_id", "class" => "dark", "data-post-id" => $log->log_type_id, "data-modal-lg" => "1")); ?></p>
                                <?php } ?>

                                <?php if (isset($log->log_for_title)) { ?>
                                    <p> <?php echo app_lang($log->log_for) . ": " . anchor("projects/view/" . $log->log_for_id, $log->log_for_title, array("class" => "dark")); ?></p>
                                <?php } ?>

                                <?php
                                if (is_dev_mode()) {
                                    echo ' ' . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => "Delete Activity", "class" => "delete", "data-id" => $log->id, "data-action-url" => get_uri("projects/delete_milestone_activity"), "data-action" => "delete-confirmation", 'data-reload-on-success' => true));
                                }
                                ?>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    <?php

    }
    ?>
</div>