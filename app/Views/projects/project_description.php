<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <?php echo isset($status_label) ? $status_label : '<h4 class="float-start">' . ucfirst($project_info->title) . '</h4>'  ?>
        <div class="">
            <!-- <button class="btn btn-danger btn-sm"><i data-feather="x" class="icon-16"></i>Discontinue</button> -->

            <?php
            echo modal_anchor(get_uri("contracts/send_project_contract_modal_form/" . $project_info->client_id), "<i data-feather='send' class='icon-16'></i> " . app_lang('send_contract'), array("class" => "btn btn-primary", "title" => app_lang('send_contract'), "data-post-project_id" => $project_id));
            ?>

            <?php
            $options = array('class' => 'btn btn-info text-white', 'title' => "Back to Previous Stage", "data-reload-on-success" => true, "data-show-response" => true);
            if (isset($current_milestone) && (int)$current_milestone->sort == 1) {
                $options['class'] = 'btn btn-info text-white disabled';
            }
            if ($project_info && $project_info->status_id == 2 && !str_contains(get_array_value($options, 'class'), 'disabled')) {
                $options['class'] .= " disabled";
            }
            echo ajax_anchor(get_uri("projects/previous_milestone/" . $project_id), '<i data-feather="chevron-left" class="icon-16"></i>Back to Previous Stage', $options);
            ?>

            <?php
            $options = array('class' => 'btn btn-info text-white', 'title' => 'Proceed to Next Stage', "data-reload-on-success" => true, "data-show-response" => true);
            $show_complete_btn = false;
            if (isset($current_milestone) && (int)$current_milestone->sort == $total_milestones) {
                $options['class'] = 'btn btn-success text-white';
                $options['title'] = 'Mark as Completed';
                $show_complete_btn = true;
            }
            if ($project_info && $project_info->status_id == 2 && !str_contains(get_array_value($options, 'class'), 'disabled')) {
                $options['class'] .= " disabled";
            }
            if ($show_complete_btn) {
                echo ajax_anchor(get_uri("projects/mark_as_completed/$project_info->id/2"), "<i data-feather='check-circle' class='icon-16'></i> " . app_lang('mark_project_as') . " " . "Completed", $options);
            } else {
                echo ajax_anchor(get_uri("projects/next_milestone/" . $project_id), '<i data-feather="chevron-right" class="icon-16"></i>Proceed to Next Stage', $options);
            }
            ?>
        </div>
    </div>
    <div class="card-body">
        <?php echo $project_info->description ? nl2br(link_it(process_images_from_content($project_info->description))) : ""; ?>
        <div class="col-sm-12 row mt-4">
            <div class="col-sm-2">
                <p class="text-secondary">Branch</p>
                <p><?php echo isset($branch) && $branch ? $branch : "All Branches"; ?></p>
            </div>
            <div class="col-sm-2">
                <p class="text-secondary">Application ID</p>
                <p><?php echo "#" . $project_info->id; ?></p>
            </div>
            <!-- <div class="col-sm-2">
                <p class="text-secondary">Partner's Client ID</p>
                <p><?php echo "#" . $project_info->client_id; ?></p>
            </div> -->
            <!-- <div class="col-sm-4 mt-5">
                <p class="text-secondary">Started At</p>
                <p><?php echo format_to_date($project_info->created_date); ?></p>
            </div> -->
        </div>
        <div class="col-sm-12 row mt-4">
            <div class="col-sm-2">
                <p class="text-secondary">Workflow</p>
                <p style="font-weight: bold;"><?php echo isset($workflow) ? $workflow->description : 'N/A'; ?></p>
            </div>
            <div class="col-sm-2">
                <p class="text-secondary">Current Stage</p>
                <p class="text-success" style="font-weight: bold;"><?php echo isset($current_milestone) ? $current_milestone->title : 'N/A'; ?></p>
            </div>
            <div class="col-sm-2">
                <p class="text-secondary">Document Check List</p>
                <p style="font-weight: bold;"><?php echo isset($doc_check_list) ? $doc_check_list->description : 'N/A'; ?></p>
            </div>
        </div>
    </div>
</div>