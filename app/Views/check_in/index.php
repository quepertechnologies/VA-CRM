<div id="page-content" class="page-wrapper clearfix grid-button">

    <div class="card">
        <ul id="attendance-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab">
                <h4 class="pl15 pt10 pr15"><?php echo app_lang("office_check_ins"); ?></h4>
            </li>

            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/all/"); ?>" data-bs-target="#checkin-all"><?php echo app_lang('all'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/waiting/"); ?>" data-bs-target="#checkin-waiting"><?php echo app_lang("waiting"); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/attending/"); ?>" data-bs-target="#checkin-attending"><?php echo app_lang('attending'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/completed/"); ?>" data-bs-target="#checkin-completed"><?php echo app_lang('completed'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("check_in/archived/"); ?>" data-bs-target="#checkin-archived"><?php echo app_lang('archived'); ?></a></li>

            <div class="tab-title clearfix no-border time-card-page-title">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("check_in/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_checkin'), array("class" => "btn btn-default", "title" => app_lang('add_checkin'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <!-- <div role="tabpanel" class="tab-pane fade" id="checkin-all">
                <div class="table-responsive">
                    <table id="checkin-all-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div> -->
            <div role="tabpanel" class="tab-pane fade" id="checkin-all"></div>
            <div role="tabpanel" class="tab-pane fade" id="checkin-waiting"></div>
            <div role="tabpanel" class="tab-pane fade" id="checkin-attending"></div>
            <div role="tabpanel" class="tab-pane fade" id="checkin-completed"></div>
            <div role="tabpanel" class="tab-pane fade" id="checkin-archived"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        // $("#checkin-all-table").appTable({
        //     source: '<?php echo_uri("check_in/list_data/All"); ?>',
        //     order: [
        //         [2, "desc"]
        //     ],
        //     // dateRangeType: "daily",
        //     columns: [{
        //             title: "<?php echo app_lang("date"); ?>",
        //             "class": "w15p",
        //             iDataSort: 1
        //         },
        //         {
        //             title: "<?php echo app_lang("time"); ?>",
        //             "class": "w15p"
        //         },
        //         {
        //             title: "<?php echo app_lang("contact_name"); ?>",
        //             "class": "w20p"
        //         },
        //         {
        //             title: "<?php echo app_lang("branch"); ?>",
        //             "class": ""
        //         },
        //         {
        //             title: "<?php echo app_lang("status"); ?>",
        //             "class": ""
        //         },
        //         {
        //             title: '<i data-feather="message-circle" class="icon-16"></i>',
        //             "class": "text-center w50"
        //         },
        //         {
        //             title: '<i data-feather="menu" class="icon-16"></i>',
        //             "class": "text-center option w100"
        //         }
        //     ],
        //     printColumns: [0, 2, 3, 5, 6, 7],
        //     xlsColumns: [0, 2, 3, 5, 6, 7],
        //     summation: [{
        //         column: 7,
        //         dataType: 'time'
        //     }]
        // });

    });
</script>