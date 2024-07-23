<table style="width: 100%; color: #444; border-spacing: 0">
    <thead style="font-size: 14px;">
        <tr style="font-weight: bold; background-color: #f4f4f4; text-align: left">
            <th style="width: 20%; border: 1px solid #ddd; padding: 10px; border-right: none"><?php echo app_lang("project"); ?></th>
            <th style="width: 40%; border: 1px solid #ddd; padding: 10px; border-right: none"><?php echo app_lang("payment_schedule"); ?></th>
            <th style="width: 40%; border: 1px solid #ddd; padding: 10px;"><?php echo app_lang("invoice_date"); ?></th>
            <th style="width: 40%; border: 1px solid #ddd; padding: 10px;"><?php echo app_lang("due_date"); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($schedules as $schedule) {
            $project_id = get_array_value($schedule, "project_id");
            if (!$project_id) {
                $project_id = $schedule->id;
            }
            $installment_name = get_array_value($schedule, "installment_name");
            if (!$installment_name) {
                $installment_name = $schedule->installment_name;
            }
            $invoice_date = get_array_value($schedule, "invoice_date");
            if (!$invoice_date) {
                $invoice_date = $schedule->invoice_date;
            }
            $due_date = get_array_value($schedule, "due_date");
            if (!$due_date) {
                $due_date = $schedule->due_date;
            }
        ?>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px 10px; border-top: none; border-right: none"><?php echo anchor(get_uri("projects/view/$project_id"), app_lang("project") . " " . $project_id); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px 10px; border-top: none; border-right: none"><?php echo format_to_date($installment_name, false); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px 10px; border-top: none"><?php echo format_to_date($due_date, false); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>