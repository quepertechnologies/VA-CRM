<?php echo form_open(get_uri("partners/save_file"), array("id" => "file-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
        <?php

        $option = array(
            "upload_url" => get_uri("partners/upload_file"),
            "validation_url" => get_uri("partners/validate_file"),
        );

        if (isset($doc_check_list_item) && isset($doc_check_list_id)) {
            $option['doc_check_list_item'] = $doc_check_list_item;
            $option['doc_check_list_id'] = $doc_check_list_id;
        } elseif (isset($doc_check_list)) {
            $option['doc_check_list'] = $doc_check_list;
        } elseif ($client_info->account_type) {
            $option['account_type'] = $client_info->account_type;
        }

        echo view("includes/multi_file_uploader", $option);
        ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-upload" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" disabled="disabled" class="btn btn-primary start-upload"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#file-form").appForm({
            onSuccess: function(result) {
                location.reload();
                // $("#client-file-table").appTable({
                //     reload: true
                // });
            }
        });

    });
</script>