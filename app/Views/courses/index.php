<div id="page-content" class="page-wrapper clearfix">
    <div class="clearfix grid-button">
        <h3><?php echo app_lang('course_search'); ?></h3>
        <?php echo view("courses/overview/index"); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            var tab = "<?php echo $tab; ?>";
            if (tab === "clients_list" || tab === "clients_list-has_open_projects") {
                $("[data-bs-target='#clients_list']").trigger("click");

                window.selectedClientQuickFilter = window.location.hash.substring(1);
            } else if (tab === "contacts") {
                $("[data-bs-target='#contacts']").trigger("click");

                window.selectedContactQuickFilter = window.location.hash.substring(1);
            }
        }, 210);
    });
</script>