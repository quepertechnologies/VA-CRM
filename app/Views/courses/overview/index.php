<div class="mt20">
    <div class="row">
        <div class="col-md-12">
            <?php echo course_overview_filter_widget($show_own_clients_only_user_id, $allowed_client_groups); ?>
        </div>
        <div class="col-md-12">
            <?php echo course_overview_search_results_widget($show_own_clients_only_user_id, $allowed_client_groups); ?>
        </div>
        <div class="col-md-12">
            <?php echo course_overview_course_widget($show_own_clients_only_user_id, $allowed_client_groups); ?>
        </div>
    </div>

    <div class="row">
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>