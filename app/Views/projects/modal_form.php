<?php echo form_open(get_uri("projects/save"), array("id" => "project-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" name="estimate_id" value="<?php echo $model_info->estimate_id; ?>" />
        <input type="hidden" name="order_id" value="<?php echo $model_info->order_id; ?>" />
        <input type="hidden" id="title_id" name="title_id" value="<?php echo $model_info->title_id; ?>" />
        <input type="hidden" id="title_text" name="title_text" value="<?php echo $model_info->title; ?>" />
        <input type="hidden" name="add_new_title_to_library" value="" id="add_new_title_to_library" />

        <div class="form-group">
            <div class="row">
                <label for="workflow_id" class=" col-md-3"><?php echo app_lang('attach_workflow'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("workflow_id", $workflows_dropdown, array($model_info->workflow_id ? $model_info->workflow_id : ""), "class='select2 validate-hidden' data-rule-required='true',data-msg-required='" . app_lang('field_required') . "' id='workflow-id-dropdown'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="doc_check_list_id" class=" col-md-3"><?php echo app_lang('attach_document_check_list'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("doc_check_list_id", $doc_check_list_dropdown, array($model_info->doc_check_list_id ? $model_info->doc_check_list_id : ""), "class='select2 validate-hidden' data-rule-required='true',data-msg-required='" . app_lang('field_required') . "' id='doc-check-list-id-dropdown'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="partner_ids" class=" col-md-3"><?php echo app_lang('select_partners'); ?></label>
                <div class=" col-md-9">
                    <?php
                    $partner_ids = array();
                    if ($project_partners) {
                        foreach ($project_partners as $partner) {
                            $partner_ids[] = $partner->partner_id;
                        }
                    }
                    if (is_dev_mode()) {
                        echo form_multiselect("partner_ids", $partners_dropdown, $partner_ids, "class='select2' id='partner-ids-dropdown'");
                    } else {
                        echo form_multiselect("partner_ids", $partners_dropdown, $partner_ids, "class='select2 validate-hidden' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "' id='partner-ids-dropdown'");
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="partner_client_id" class=" col-md-3"><?php echo app_lang('partner_client_id') . " (Optional)"; ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "partner_client_id",
                        "name" => "partner_client_id",
                        "value" => $model_info->partner_client_id ? $model_info->partner_client_id : '',
                        "class" => "form-control",
                        "placeholder" => app_lang('partner_client_id'),
                        "autofocus" => true,
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php if (isset($subagent) && $subagent_full_name) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="subagent_id" class=" col-md-3"><?php echo app_lang('subagent'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input('subagent_name', $subagent_full_name, "class='form-control' readonly='readonly' disabled='disabled'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="referral_id" class=" col-md-3"><?php echo app_lang('referral'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown('referral_id', $referrals_dropdown,  $referral_info ? $referral_info->partner_id : '', "class='form-control select2'");
                    ?>
                    <small class="text-info">Default commission rate 10% will be deducted from your income for the selected referral.</small>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="title" class=" col-md-3"><?php echo app_lang('title'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => $model_info->title_id ? $model_info->title_id : '',
                        "class" => "form-control",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php if ($client_id || $login_user->user_type == "client") { ?>
            <input type="hidden" name="project_type" value="client_project" />
        <?php } else { ?>
            <div class="form-group">
                <div class="row">
                    <label for="project_type" class=" col-md-3"><?php echo app_lang('project_type'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("project_type", array(
                            "client_project" => app_lang("client_project"),
                            "internal_project" => app_lang("internal_project"),
                        ), array($model_info->project_type ? $model_info->project_type : "client_project"), "class='select2 validate-hidden' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "' id='project-type-dropdown'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($client_id) { ?>
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
        <?php } else if ($login_user->user_type == "client" || $hide_clients_dropdown) { ?>
            <input type="hidden" name="client_id" value="<?php echo $model_info->client_id; ?>" />
        <?php } else { ?>
            <div class="form-group <?php echo $model_info->project_type === "internal_project" ? 'hide' : ''; ?>" id="clients-dropdown">
                <div class="row">
                    <label for="client_id" class=" col-md-3"><?php echo app_lang('client'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="description" class=" col-md-3"><?php echo app_lang('description'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "description",
                        "name" => "description",
                        "value" => process_images_from_content($model_info->description, false),
                        "class" => "form-control",
                        "placeholder" => app_lang('description'),
                        "style" => "height:150px;",
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>
        <!-- <div class="form-group">
            <div class="row">
                <label for="start_date" class=" col-md-3"><?php echo app_lang('start_date'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "start_date",
                        "name" => "start_date",
                        "value" => is_date_exists($model_info->start_date) ? $model_info->start_date : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('start_date'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="deadline" class=" col-md-3"><?php echo app_lang('deadline'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "deadline",
                        "name" => "deadline",
                        "value" => is_date_exists($model_info->deadline) ? $model_info->deadline : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('deadline'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="price" class=" col-md-3"><?php echo app_lang('price'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "price",
                        "name" => "price",
                        "value" => $model_info->price ? to_decimal_format($model_info->price) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('price')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="project_labels" class=" col-md-3"><?php echo app_lang('labels'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "project_labels",
                        "name" => "labels",
                        "value" => $model_info->labels,
                        "class" => "form-control",
                        "placeholder" => app_lang('labels')
                    ));
                    ?>
                </div>
            </div>
        </div> -->

        <?php if ($model_info->id) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="status_id" class=" col-md-3"><?php echo app_lang('status'); ?></label>
                    <div class="col-md-9">
                        <?php
                        foreach ($statuses as $status) {
                            $project_status[$status->id] = $status->key_name ? app_lang($status->key_name) : $status->title;
                        }

                        echo form_dropdown("status_id", $project_status, array($model_info->status_id), "class='select2'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>


        <?php if (is_dev_mode()) { ?>

            <div class="form-group">
                <div class="row">
                    <label for="start_date" class=" col-md-3"><?php echo app_lang('start_date'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "start_date",
                            "name" => "start_date",
                            "value" => is_date_exists($model_info->start_date) ? $model_info->start_date : "",
                            "class" => "form-control",
                            "placeholder" => app_lang('start_date'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="deadline" class=" col-md-3"><?php echo app_lang('deadline'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "deadline",
                            "name" => "deadline",
                            "value" => is_date_exists($model_info->deadline) ? $model_info->deadline : "",
                            "class" => "form-control",
                            "placeholder" => app_lang('deadline'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "created_date",
                        "name" => "created_date",
                        "value" => $model_info->created_date,
                        "class" => "form-control",
                        "placeholder" => app_lang('added_date'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "created_by",
                        "name" => "created_by",
                        "value" => '',
                        "class" => "form-control",
                        "placeholder" => app_lang('added_by'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>

        <?php } ?>

        <div class="hide" id='partners-commission-container'>
            <h3 class="mb-5"><?php echo app_lang('partners'); ?></h3>
            <div class="row" id="commission-container"></div>
        </div>

        <?php if (isset($subagent) && $subagent_full_name) { ?>
            <input type="hidden" name='subagent_id' value="<?php echo $subagent->id; ?>">
            <input type="hidden" name='subagent_default_commission' value="<?php echo $subagent->com_percentage; ?>">
            <div class="form-group">
                <div class="row">
                    <label for="subagent-commission" class=" col-md-3"><?php echo app_lang('subagent_commission'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "subagent-commission",
                            "name" => "subagent_commission",
                            "value" => isset($subagent_commission) && $subagent_commission ? $subagent_commission : '',
                            "class" => "form-control",
                            "placeholder" => app_lang('subagent_commission'),
                            'type' => 'number'
                        ));
                        ?>
                        <small><?php echo "Define commission percentage for the subagent <b>" . $subagent_full_name . '</b>. Leave the above field empty to have the default <b>' . ($subagent->com_percentage ? $subagent->com_percentage : 0) . '%</b> commission applied.'; ?></small>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?>

    </div>
</div>

<div class="modal-footer">
    <div id="link-of-add-project-member-modal" class="hide">
        <?php echo modal_anchor(get_uri("projects/project_member_modal_form"), "", array()); ?>
    </div>

    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <?php if (!$model_info->id && $login_user->user_type != "client" && $can_edit_projects) { ?>
        <button type="button" id="save-and-continue-button" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_continue'); ?></button>
    <?php } ?>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        window.projectForm = $("#project-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function(result) {
                if (typeof RELOAD_PROJECT_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_PROJECT_VIEW_AFTER_UPDATE) {
                    location.reload();

                    window.projectForm.closeModal();
                } else if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    RELOAD_VIEW_AFTER_UPDATE = false;
                    window.location = "<?php echo site_url('projects/view'); ?>/" + result.id;

                    window.projectForm.closeModal();
                } else if (window.showAddNewModal) {
                    var $addProjectMemberLink = $("#link-of-add-project-member-modal").find("a");

                    $addProjectMemberLink.attr("data-action-url", "<?php echo get_uri("projects/project_member_modal_form"); ?>");
                    $addProjectMemberLink.attr("data-title", "<?php echo app_lang("add_new_project_member"); ?>");
                    $addProjectMemberLink.attr("data-post-project_id", result.id);
                    $addProjectMemberLink.attr("data-post-view_type", "from_project_modal");

                    $addProjectMemberLink.trigger("click");

                    $("#project-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                } else {
                    $("#project-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });

                    window.projectForm.closeModal();
                }
            }
        });

        setTimeout(function() {
            $("#title").focus();
        }, 200);
        $("#project-form .select2").select2();

        // setDatePicker("#start_date, #deadline");

        <?php if (is_dev_mode()) { ?>

            setDatePicker('#created_date, #start_date, #deadline');

            $("#created_by").select2({
                data: <?php echo $team_members_dropdown; ?>
            });

        <?php } ?>

        $("#project_labels").select2({
            multiple: true,
            data: <?php echo json_encode($label_suggestions); ?>
        });

        //save and open add new project member modal
        window.showAddNewModal = false;

        $("#save-and-continue-button").click(function() {
            window.showAddNewModal = true;
            $(this).trigger("submit");
        });

        function validateClientDropdown() {
            if ($("#project-type-dropdown").val() === "internal_project") {
                $("#clients-dropdown").addClass("hide");
                $("#clients-dropdown").find(".select2").removeClass("validate-hidden");
                $("#clients-dropdown").find(".select2").removeAttr("data-rule-required");
            } else {
                $("#clients-dropdown").removeClass("hide");
                $("#clients-dropdown").find(".select2").addClass("validate-hidden");
                $("#clients-dropdown").find(".select2").attr("data-rule-required", true);
            }
        }

        $("#project-type-dropdown").select2().on("change", function() {
            validateClientDropdown();
        });

        setTimeout(function() {
            validateClientDropdown();
        });

        handlePartnerFields($('#partner-ids-dropdown'));
        $('#partner-ids-dropdown').on('change', function() {
            handlePartnerFields(this);
        });

        function handlePartnerFields(selector) {
            let selected_partners = <?php echo json_encode($project_partners); ?>;

            const modal_info = <?php echo json_encode($model_info); ?>;

            if (modal_info && modal_info.id && selected_partners && selected_partners.length) {
                let items = '';
                for (let i = 0; i < selected_partners.length; i++) {
                    const partner = selected_partners[i];
                    items += `<div class="form-group">
                                <div class="row">
                                    <label for="${partner.id}_com_percentage" class=" col-md-3">${partner.full_name}'s commission (%)</label>
                                    <div class=" col-md-9">
                                    <input type='number' id='${partner.id}' name="com_percentage_${partner.id}" value='${partner.commission}' class='form-control' placeholder='<?php echo app_lang('commission_percentage'); ?>' data-rule-required="1" data-msg-required="<?php echo app_lang("field_required"); ?>" >
                                    </div>
                                </div>
                              </div>`;
                }

                $('#commission-container').html(items);
                if (selected_partners.length && $('#partners-commission-container').hasClass("hide")) {
                    $('#partners-commission-container').removeClass('hide');
                }
            } else {
                selected_partners = $(selector).select2('data');
                let items = '';
                for (let i = 0; i < selected_partners.length; i++) {
                    const partner = selected_partners[i];
                    items += `<div class="form-group">
                                <div class="row">
                                    <label for="${partner.id}_com_percentage" class=" col-md-3">${partner.text}'s commission (%)</label>
                                    <div class=" col-md-9">
                                    <input type='number' id='${partner.id}' name="com_percentage_${partner.id}" value='' class='form-control' placeholder='<?php echo app_lang('commission_percentage'); ?>' data-rule-required="1" data-msg-required="<?php echo app_lang("field_required"); ?>" >
                                    </div>
                                </div>
                              </div>`;
                }

                $('#commission-container').html(items);
                if (selected_partners.length && $('#partners-commission-container').hasClass("hide")) {
                    $('#partners-commission-container').removeClass('hide');
                }
                selected_partners = $(selector).select2('data');

            }
        }

        applySelect2OnItemTitle();

        //re-initialize item suggestion dropdown on request
        $("#inline_invoice_item_title_dropdwon_icon").click(function() {
            applySelect2OnItemTitle();
        });

    });

    function applySelect2OnItemTitle() {
        $("#title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("projects/get_project_title_suggestion"); ?>",
                type: 'POST',
                dataType: 'json',
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function(data, page) {
                    return {
                        results: data
                    };
                }
            }
        }).change(function(e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#title").select2("destroy").val("").focus();
                $("#add_new_title_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_title_to_library").val("");
                $("#title_id").val(e.val);
                $("#title_text").val(e.added.text);
            }

        });

        $('#title').select2('data', {
            id: '<?php echo $model_info->title_id; ?>',
            text: '<?php echo $model_info->title; ?>'
        })
    }
</script>