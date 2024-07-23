<div class="card bg-white">

    <div class="card-body rounded-bottom row p-4" id="">
        <div class='mb-3 row align-items-center'>
            <div class="col-md-9">
                <h5 class=""><?php echo app_lang("search_results"); ?></h5>
                <small>Showing <strong>1557 results based on your student data</strong></small>
            </div>
            <div class="form-group col-md-3">
                <label for="course_sort_by" class=""><?php echo app_lang('sort_by'); ?></label>
                <?php
                $list = array(
                    'Relevance' => 'Relevance',
                    'Cost Low to Hight' => 'Cost Low to Hight',
                    'Cost High to Low' => 'Cost High to Low',
                    'The Ranking' => 'The Ranking',
                    'QS Ranking' => 'QS Ranking',
                    'Intake Earliest to Latest' => 'Intake Earliest to Latest',
                    'Turnaround Time Low to High' => 'Turnaround Time Low to High',
                );

                echo form_dropdown(
                    'course_sort_by',
                    $list,
                    '',
                    "class='form-control select2' id='course_sort_by'"
                );
                ?>
            </div>
        </div>
        <button class="btn btn-primary" type="button" role="button">Clear All Filters</button>
    </div>
</div>