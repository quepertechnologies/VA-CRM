<div class="card bg-white">
    <div class="card-header no-border">
        <i data-feather="list" class="icon-16"></i>&nbsp; <?php echo app_lang('visa_expiring_clients'); ?>
    </div>

    <div class="table-responsive" id="visa-expiring-clients-list-widget-table">
        <table id="visa-expiring-clients-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        initScrollbar('#visa-expiring-clients-list-widget-table', {
            setHeight: 330
        });

        var showOption = true,
            idColumnClass = "w70",
            titleColumnClass = "";

        if (isMobile()) {
            showOption = false;
            idColumnClass = "w25p";
            titleColumnClass = "w75p";
        }

        $("#visa-expiring-clients-table").appTable({
            source: '<?php echo_uri("clients/visa_expiring_clients_list_data") ?>',
            order: [
                [1, "asc"]
            ],
            serverSide: true,
            displayLength: 100,
            filterParams: {
                limit: 100
            },
            responsive: false, //hide responsive (+) icon
            columns: [
                // {
                //     title: '<?php echo app_lang("id") ?>',
                //     "class": 'w25p'
                // },
                {
                    title: '<?php echo app_lang("client") ?>',
                    "class": 'w50p',
                    order_by: "unique_id"
                },
                {
                    title: '<?php echo app_lang("visa") ?>',
                    "class": "",
                    order_by: "visa_expiry"
                },
                {
                    title: '<?php echo app_lang("client") ?>',
                    visible: false,
                    searchable: false
                },
                {
                    title: '<?php echo app_lang("client_id") ?>',
                    visible: false,
                    searchable: false
                },
                {
                    title: '<?php echo app_lang("visa") ?>',
                    visible: false,
                    searchable: false
                },
                {
                    title: '<?php echo app_lang("visa_expiry") ?>',
                    visible: false,
                    searchable: false,
                },
            ],
            onInitComplete: function() {
                $("#visa-expiring-clients-table_wrapper .datatable-tools").addClass("hide");
            },
            printColumns: [2, 3, 4, 5]
        });
    });
</script>