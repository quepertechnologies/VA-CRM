<?php echo form_open(get_uri("notes/save"), array("id" => "note-form", "class" => "general-form", "role" => "form")); ?>
<div id="notes-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="id" id='model_id' value="<?php echo $model_info->id; ?>" />
            <input type="hidden" name="project_id" id='project_id' value="<?php echo $project_id; ?>" />
            <input type="hidden" name="client_id" id='client_id' value="<?php echo $client_id; ?>" />
            <input type="hidden" name="user_id" id='user_id' value="<?php echo $user_id; ?>" />
            <input type="hidden" name="milestone_id" id='milestone_id' value="<?php echo isset($milestone_id) ? $milestone_id : 0; ?>" />
            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    $initialTitle = $model_info->title ? $model_info->title : '';
                    // if (!$model_info->id && isset($recovered_note->title) && $recovered_note->title) {
                    //     $initialTitle = $recovered_note->title;
                    // }

                    echo form_input(array(
                        "id" => "note-title",
                        "name" => "title",
                        "value" => $initialTitle,
                        "class" => "form-control notepad-title",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="notepad">
                        <?php
                        $initialContent = $model_info->description ? process_images_from_content($model_info->description, false) : '';
                        // if (!$model_info->id && isset($recovered_note->description) && $recovered_note->description) {
                        //     $initialContent = process_images_from_content($recovered_note->description, false);
                        // }

                        echo form_textarea(array(
                            "id" => "note-description",
                            "name" => "description",
                            "value" => $initialContent,
                            "class" => "form-control",
                            "placeholder" => app_lang('description') . "...",
                            "data-rich-text-editor" => true
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="notepad">
                        <?php
                        echo form_input(array(
                            "id" => "note_labels",
                            "name" => "labels",
                            "value" => $model_info->labels,
                            "class" => "form-control",
                            "placeholder" => app_lang('labels')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <?php if (is_dev_mode()) { ?>

                <div class="form-group">
                    <div class="col-md-12">
                        <?php
                        echo form_input(array(
                            "id" => "created_at",
                            "name" => "created_at",
                            "value" => $model_info->created_at,
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
                            "value" => $login_user->id,
                            "class" => "form-control",
                            "placeholder" => app_lang('added_by'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
                </div>

            <?php } ?>

            <?php if ($project_id) { ?>
                <?php if ($model_info->is_public) { ?>
                    <input type="hidden" name="is_public" value="<?php echo $model_info->is_public; ?>" />
                <?php } else { ?>
                    <div class="form-group">
                        <label for="mark_as_public" class=" col-md-12">
                            <?php
                            echo form_checkbox("is_public", "1", true, "id='mark_as_public'  class='float-start form-check-input'");
                            ?>
                            <span class="float-start ml15"> <?php echo app_lang('mark_as_public'); ?> </span>
                            <span id="mark_as_public_help_message" class="ml10 hide"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("mark_as_public_help_message"); ?></span>
                        </label>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <input type="hidden" name="is_public" value="1" />
            <?php } ?>

            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo view("includes/file_list", array("files" => $model_info->files));
                    ?>
                </div>
            </div>

            <?php echo view("includes/dropzone_preview"); ?>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-default upload-file-button float-start me-auto btn-sm round" type="button" style="color:#7988a2"><i data-feather="camera" class="icon-16"></i> <?php echo app_lang("upload_file"); ?></button>
        <div id='auto-save-uploading-icon' class="text-info"><small>Saving...</small></div>
        <div id='auto-save-uploaded-icon' title='This note is auto saved'><span class='icon-16 text-success' data-feather='cloud'></span></div>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#auto-save-uploading-icon,#auto-save-uploaded-icon').hide();
        var uploadUrl = "<?php echo get_uri("notes/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("notes/validate_notes_file"); ?>";

        var dropzone = attachDropzoneWithForm("#notes-dropzone", uploadUrl, validationUri);

        var intervalIds = new Array();
        $("#note-form").appForm({
            onSuccess: function(result) {
                if (intervalId) {
                    clearTimeout(intervalId);
                }
                if ($("#note-table").length) {
                    $("#note-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                } else {
                    location.reload();
                }
            }
        });

        var intervalCount = 0,
            autoSavedOnce = false;
        const intervalId = setTimeout(autoSaveNote, 15000);
        intervalIds.push(intervalId);

        function autoSaveNote() {
            var title = $('#note-title').val(),
                description = $('#note-description').val(),
                model_id = $('#model_id').val(),
                project_id = $('#project_id').val(),
                client_id = $('#client_id').val(),
                user_id = $('#user_id').val(),
                milestone_id = $('#milestone_id').val(),
                file_names = new Array(),
                file_sizes = new Array();

            $("input[name^='file_names']").each(function() {
                file_names.push($(this).val());
            });
            $("input[name^='file_sizes']").each(function() {
                file_sizes.push($(this).val());
            });

            if (description && description.length > 2 && !$('#note-title').val()) {
                title = '<?php echo $login_user->first_name . ' ' . $login_user->last_name ?>' + ' (Auto saved note)'
            }

            if ((title || description) && (intervalCount == 1 || (intervalCount > 1 && !autoSavedOnce) || (intervalCount > 1 && autoSavedOnce && model_id))) {
                $('#note-title').val(title);
                if ($('#auto-save-uploading-icon').css('display') == 'none') {
                    $('#auto-save-uploading-icon').fadeIn('slow');
                }

                $.ajax({
                    url: '<?php echo_uri("notes/save") ?>',
                    type: "POST",
                    dataType: 'json',
                    data: {
                        id: model_id,
                        project_id,
                        client_id,
                        user_id,
                        milestone_id,
                        title,
                        description,
                        file_names,
                        file_sizes
                    },
                    success: function(result) {
                        if (result.success) {
                            autoSavedOnce = true;
                            $("#model_id").val(result.id);

                            $("input[name^='file_names']").each(function() {
                                $(this).remove();
                            });
                            $("input[name^='file_sizes']").each(function() {
                                $(this).remove();
                            });
                        }
                        if (result) {
                            const intervalId = setTimeout(autoSaveNote, 15000);
                            intervalIds.push(intervalId);
                        }

                        if ($('#auto-save-uploaded-icon').css('display') == 'none') {
                            $('#auto-save-uploaded-icon').fadeIn('slow');
                        }
                        $('#auto-save-uploading-icon').fadeOut('slow');
                    }
                });
            } else {
                const intervalId = setTimeout(autoSaveNote, 15000);
                intervalIds.push(intervalId);
            }
            intervalCount++;
        }

        $(document).on('hide.bs.modal', '#ajaxModal', function() {
            if (intervalIds && intervalIds.length) {
                intervalIds.forEach(function(intervalId) {
                    clearTimeout(intervalId);
                });
            }

            if ($("#note-table").length) {
                $("#note-table").appTable({
                    reload: true
                });
            }
        });

        setTimeout(function() {
            $("#title").focus();
        }, 200);

        $("#note_labels").select2({
            multiple: true,
            data: <?php echo json_encode($label_suggestions); ?>
        });

        <?php if (is_dev_mode()) { ?>

            setDatePicker('#created_at');

            $("#created_by").select2({
                data: <?php echo $team_members_dropdown; ?>
            });

        <?php } ?>

        //show/hide mark as public help message
        $("#mark_as_public").click(function() {
            if ($(this).is(":checked")) {
                $("#mark_as_public_help_message").removeClass("hide");
            } else {
                $("#mark_as_public_help_message").addClass("hide");
            }
        });
    });
</script>