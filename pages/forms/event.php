<form  enctype="multipart/form-data"  class=" form-stacked validationForm"  action="<?php echo BASE_URL . '?page=' . $action . '&mode=' . $mode; ?>" method="post">
    <fieldset>
        <legend></legend>
        <div class="clearfix">
            <label for="xlInput3">Event Title:</label>
            <div class="input">
                <input class="span7 required" id="title" name="title" value="<?php echo ViewHelper::getInput('title'); ?>" size="30" type="text">
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">Event Description:</label>
            <div class="input">
                <textarea class="span7 required" id="summary" name="summary" rows="7" cols="50"><?php echo ViewHelper::getInput('summary') ?></textarea>
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">Category:</label>
            <div class="input"> 
                <select name="category_ids[]" class="span7 required" multiple="true">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id'] ?>"><?php echo $category['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">Venue:</label>
            <div class="input">
                <input class="span7  required" id="location" name="location" value="<?php echo ViewHelper::getInput('location') ?>" size="30" type="text">
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">URL:</label>
            <div class="input">
                <input class="span7  required url" id="href" name="href" value="<?php echo ViewHelper::getInput('href') ?>" size="30" type="text">
            </div>
        </div>



        <div class="clearfix">
            <label for="xlInput3">Date:</label>
            <div class="inline-inputs">
                <input class="small datepicker  required date" id="start_date" name="start_date" value="<?php echo ViewHelper::getInput('start_date') ?>" type="text"> to
                <input class="small datepicker  required date" id="end_date" name="end_date" value="<?php echo ViewHelper::getInput('end_date') ?>" type="text">
                <span class="help-block">Please enter date in this format: mm/dd/yyyy.</span>
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">Logo:</label>
            <div class="input">
                <input class="span7 input-file" id="logo" name="logo" size="30" type="file">
                <span class="help-block">The logo should be of dimension 90x90.</span>
            </div>
        </div>

        <?php
        if ($mode == 'updateEvent') {
            ?>
            <input type="hidden" name="eventId" value="<?php echo ViewHelper::getInput("eventId"); ?>"/>
            <?php
        }
        ?>

        <input type="submit" class="btn primary" value="Submit" />

    </fieldset>
</form>
