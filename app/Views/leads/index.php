<div id="page-content" class="page-wrapper clearfix grid-button">

    <div class="card">
        <ul id="lead-tabs" class="nav nav-tabs bg-white title" role="tablist" data-bs-toggle="ajax-tab">
            <li class="title-tab leads-title-section">
                <h4 class="pl15 pt10 pr15"><?php echo app_lang("leads"); ?></h4>
            </li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri('leads/all_leads'); ?>" data-bs-target="#leads-leads"><?php echo app_lang("leads"); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri('leads/list_prospects'); ?>" data-bs-target="#leads-prospects"><?php echo app_lang('lead_prospects'); ?></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri('leads/cold_leads'); ?>" data-bs-target="#leads-cold"><?php echo app_lang('cold_leads'); ?></a></li>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-outline-light", "title" => app_lang('manage_labels'), "data-post-type" => "client")); ?>
                    <?php echo modal_anchor(get_uri("leads/import_modal_form"), "<i data-feather='upload' class='icon-16'></i> " . app_lang('import_leads'), array("class" => "btn btn-default", "title" => app_lang('import_leads'))); ?>
                    <?php echo modal_anchor(get_uri("leads/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_lead'), array("class" => "btn btn-default", "title" => app_lang('add_lead'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="leads-leads"></div>
            <div role="tabpanel" class="tab-pane fade" id="leads-prospects"></div>
            <div role="tabpanel" class="tab-pane fade" id="leads-cold"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
      if(window.location.hash) {
        setTimeout(function () {
        var tab = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        if (tab == "list_prospects") {
                $("[data-bs-target='#leads-prospects']").trigger("click");

                window.selectedClientQuickFilter = window.location.hash.substring(1);
            } else if (tab == "cold_leads") {
                $("[data-bs-target='#cold_leads']").trigger("click");

                window.selectedContactQuickFilter = window.location.hash.substring(1);
            }
        }, 300);
     }
    });
</script>


