<div class="general-form">

    <div class="modal-body clearfix">
        <div class="container-fluid">
            <div class="form-group">
                <div class="row">
                    <label for="location_id" class="<?php echo $label_column; ?>">Select Office Location</label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_dropdown("location_id", $locations_dropdown, '', 'class="form-control select2" id="location_id" data-rule-required="true" data-msg-required="' . app_lang("field_required") . '"');
                        ?>
                        <small class="text-danger" id="location_msg"></small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="qr_label" class="<?php echo $label_column; ?>">QR Code Label</label>
                    <div class="<?php echo $field_column; ?>">
                        <?php
                        echo form_input(
                            'qr_label',
                            'Self Check-In',
                            "class='form-control' id='qr_label' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'"
                        );
                        ?>
                        <small class="text-danger" id="qr_label_msg"></small>
                    </div>
                </div>
            </div>
            <div id='qr-container' class='align-items-center justify-content-center d-flex'></div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="button" class="btn btn-default" disabled='disabled' id='qr-print-btn'><span data-feather="printer" class="icon-16"></span> <?php echo app_lang('print'); ?></button>
        <button type="button" class="btn btn-default" disabled='disabled' id='qr-download-btn'><span data-feather="download" class="icon-16"></span> <?php echo app_lang('download'); ?></button>
        <button type="button" class="btn btn-primary" id='gen-qr-btn'><span data-feather="refresh-ccw" class="icon-16"></span> <?php echo app_lang('generate'); ?></button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#location_id").select2();

        $('#gen-qr-btn').click(function() {
            if (isValid()) {
                var location_id = $('#location_id').val(),
                    qr_label = $('#qr_label').val();

                $('#qr-print-btn').attr('disabled', 'disabled');
                $('#qr-container').html("");
                appLoader.show({
                    container: "#qr-container",
                    css: "left:0;"
                });

                $.ajax({
                    url: "<?php echo get_uri('check_in/generate_public_qr_code') ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        location_id,
                        qr_label
                    },
                    success: function(result) {
                        appLoader.hide();

                        if (result.success) {
                            $('#qr-print-btn').removeAttr('disabled');
                            $('#qr-download-btn').attr('data-image', result.qr_uri).removeAttr('disabled');
                            $('#qr-container').html("<img src='" + result.qr_uri + "' style='border: 1px dashed black; border-radius: 10px;'>");
                        }
                    },
                    error: function() {
                        appLoader.hide();
                        $('#qr-print-btn').attr('disabled', 'disabled');
                        $('#qr-container').html("");
                    }
                });
            }
        });

        $('#qr-print-btn').click(function() {
            var divToPrint = document.getElementById('DivIdToPrint');

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            // newWin.document.write('<html><body onload="window.print()">' + $('#qr-container').html() + '</body></html>');
            newWin.document.write('<html style="height: 100%;"><head><meta name="viewport" content="width=device-width, minimum-scale=0.1"><title>Visa Alliance | Print QR Code</title></head><body onload="window.print()" style="margin: auto; height: 100%; background-color: rgb(14, 14, 14);">' + $('#qr-container').html() + '</body></html>');

            newWin.document.close();

            setTimeout(function() {
                newWin.close();
            }, 10);
        });

        $('#qr-download-btn').click(function() {
            downloadURI($(this).attr('data-image'));
        });

        function downloadURI(uri, name = "VA-self-check-in-qr-code.png") {
            var link = document.createElement("a");
            link.download = name;
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            delete link;
        }

        $('#location_id').change(function() {
            $('#location_msg').html('');
        });

        $('#qr_label').keyup(function() {
            $('#qr_label_msg').html('');
        });

        function isValid() {
            var location_id = $('#location_id').val(),
                qr_label = $('#qr_label').val(),
                isValid = true;

            if (!location_id) {
                $('#location_msg').html('This field is required');
                isValid = false;
            }

            if (!qr_label) {
                $('#qr_label_msg').html('This field is required');
                isValid = false;
            }

            return isValid;
        }
    });
</script>