<div class="mt20">
    <div class="row">
        <div class="col-md-12">
            <?php echo Course_overview_title_widget($show_own_clients_only_user_id, $allowed_client_groups); ?>
        </div>
        <div class="col-md-12">
            <?php echo Course_overview_preview_content_widget($show_own_clients_only_user_id, $allowed_client_groups); ?>
        </div>
        <div class="col-md-12">
            <?php echo view("courses/widgets/PreviewContentTabs/Footer"); ?>
        </div>
    </div>

    <div class="row">
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>