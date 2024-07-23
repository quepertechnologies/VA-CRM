<div class="card bg-white">
    <h4 class="p-4"><?php echo app_lang("filters"); ?></h4>

    <div class="card-body pt0 rounded-bottom row" id="">
        <div class="form-group col-md-3">
            <label for="course_search" class=""><?php echo app_lang('search'); ?></label>
            <?php
            echo form_input(array(
                "id" => "course_search",
                "name" => "course_search",
                "value" => '',
                "class" => "form-control company_name_input_section",
                "placeholder" => app_lang('course_search_placeholder'),
                "autofocus" => true,
            ));
            ?>
        </div>
        <div class="form-group col-md-3">
            <label for="course_intake" class=""><?php echo app_lang('intake'); ?></label>
            <?php
            $currentYear = date("Y");
            $years = range($currentYear, $currentYear + 2);
            $list = [];

            $list[''] = '-';
            foreach ($years as $year) {
                $list[$year . '-Q1'] = $year . ' Q1: Jan, Feb, Mar';
                $list[$year . '-Q2'] = $year . ' Q2: Apr, May, Jun';
                $list[$year . '-Q3'] = $year . ' Q3: Jul, Aug, Sep';
                $list[$year . '-Q4'] = $year . ' Q4: Oct, Nov, Dec';
            }

            echo form_dropdown(
                'course_intake',
                $list,
                '',
                "class='form-control select2' id='course_intake'"
            );
            ?>
        </div>
        <div class="form-group col-md-3">
            <label for="course_country" class=""><?php echo app_lang('country'); ?></label>
            <?php
            $countries = isset($countries_dropdown) ? $countries_dropdown : '';
            $list = [];

            $list[''] = '-';
            foreach (json_decode($countries) as $country) {
                $list[$country->nicename] = $country->nicename;
            }

            echo form_dropdown(
                'course_country',
                $list,
                '',
                "class='form-control select2' id='course_country'"
            );
            ?>
        </div>
        <div class="form-group col-md-2">
            <label for="" class=""> </label>
            <button class="form-control btn btn-primary mt-2" type="submit" role="button">Search</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>