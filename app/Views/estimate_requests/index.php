<div id="page-content" class="page-wrapper clearfix grid-button">

    <div class="card">
        <ul id="estimate-tabs" class="nav nav-tabs bg-white title" role="tablist" data-bs-toggle="ajax-tab">
            <li class="title-tab estimates-title-section">
                <h4 class="pl15 pt10 pr15"><?php echo app_lang("estimate_requests"); ?></h4>
            </li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri('estimate_requests/list_submitted'); ?>" data-bs-target="#estimates-submitted"><?php echo app_lang("submitted"); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri('estimate_requests/list_archived'); ?>" data-bs-target="#estimates-archived"><?php echo app_lang('archived'); ?></a></li>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("estimate_requests/request_an_estimate_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('create_estimate_request'), array("class" => "btn btn-default", "title" => app_lang('create_estimate_request'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="estimates-submitted"></div>
            <div role="tabpanel" class="tab-pane fade" id="estimates-archived"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function initEstimateTable(el, is_archived) {
        var defaultFilters = is_archived == '1' ? [{
            name: "assigned_to",
            class: "w150",
            options: <?php echo $assigned_to_dropdown; ?>
        }] : [{
            name: "assigned_to",
            class: "w150",
            options: <?php echo $assigned_to_dropdown; ?>
        }, {
            name: "status",
            class: "w150",
            options: <?php echo $statuses_dropdown; ?>
        }];
        $(el).appTable({
            source: '<?php echo_uri("estimate_requests/estimate_request_list_data") ?>' + '/' + is_archived,
            order: [
                [4, 'desc']
            ],
            filterDropdown: defaultFilters,
            columns: [{
                    title: "<?php echo app_lang('id'); ?>"
                },
                {
                    title: "<?php echo app_lang('client'); ?>"
                },
                {
                    title: "<?php echo app_lang('title'); ?>"
                },
                {
                    title: "<?php echo app_lang('assigned_to'); ?>"
                },
                {
                    visible: false,
                    searchable: false
                },
                {
                    title: '<?php echo app_lang("created_date") ?>',
                    "iDataSort": 4
                },
                {
                    title: "<?php echo app_lang('status'); ?>"
                },
                {
                    title: "<i data-feather='menu' class='icon-16'></i>",
                    "class": "text-center dropdown-option w50"
                }
            ],
            printColumns: [0, 1, 2, 3, 5, 6]
        });
    }
</script>