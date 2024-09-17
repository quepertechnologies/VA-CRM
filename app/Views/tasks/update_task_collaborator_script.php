<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '[data-act=update-task-collaborator-info]', function(e) {
            var $instance = $(this),
                type = $(this).attr('data-act-type'),
                source = "",
                select2Option = {},
                showbuttons = false,
                placement = "bottom",
                editableType = "select2",
                datepicker = {};

            if (type === "collaborators") {
                e.stopPropagation();
                e.preventDefault();

                showbuttons = true;
                source = <?php echo json_encode($collaborators_dropdown); ?>;
                select2Option = {
                    data: source,
                    multiple: true
                };
            }

            if (type === "assigned_to") {
                e.stopPropagation();
                e.preventDefault();

                showbuttons = false;
                source = <?php echo json_encode($assign_to_dropdown); ?>;
                select2Option = {
                    data: source,
                    multiple: false
                };
            }

            $(this).appModifier({
                actionType: editableType,
                value: $(this).attr('data-value'),
                actionUrl: '<?php echo_uri("tasks/update_task_info") ?>/' + $(this).attr('data-id') + '/' + $(this).attr('data-act-type'),
                showbuttons: showbuttons,
                datepicker: datepicker,
                select2Option: select2Option,
                placement: placement,
                onSuccess: function(response, newValue) {
                    if (response.success) {
                        if (type === "assigned_to" && response.assigned_to_avatar) {
                            $("#task-assigned-to-avatar").attr("src", response.assigned_to_avatar);

                            if (response.assigned_to_id === "0") {
                                setTimeout(function() {
                                    $instance.html("<span class='text-off'><?php echo app_lang("add") . " " . app_lang("assignee"); ?><span>");
                                }, 50);
                            }
                        }

                        if (type === "collaborators" && response.collaborators) {
                            setTimeout(function() {
                                $instance.html(response.collaborators);
                            }, 50);
                        }

                        $("#task-table").appTable({
                            newData: response.data,
                            dataId: response.id
                        });

                        appLoader.hide();

                        window.reloadKanban = true;

                        //reload gantt
                        if (typeof window.reloadGantt === "function") {
                            window.reloadGantt(true);
                        }
                    }
                }
            });

            return false;
        });
    });
</script>