<a href="<?php echo get_uri("partners/"); ?>" class="white-link">
    <div class="card  dashboard-icon-widget">
        <div class="card-body">
            <div class="widget-icon bg-danger">
                <i data-feather="users" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $total; ?></h1>
                <span class="bg-transparent-white"><?php echo isset($title) ? $title : app_lang("total_clients"); ?></span>
            </div>
        </div>
    </div>
</a>