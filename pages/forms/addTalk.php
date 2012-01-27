<form  class="validationForm" action="<?php echo BASE_URL . '?page=addTalk'; ?>" method="post">
    <fieldset>
        <legend>Add a talk</legend>
        <div class="clearfix">
            <label for="xlInput3">To Event: </label>
            <div class="input">
                <label class="span6 " style="text-align: left;"> <?php echo empty($event['title']) ? "Empty Title" : $event['title']; ?></label>
            </div>
        </div>
        <input type="hidden" name="eventId" value="<?php echo $event['event_id']; ?>"/>

        <div class="clearfix">
            <label for="xlInput3">Talk Title:</label>
            <div class="input">
                <input class="span6 required" id="title" name="title" value="<?php echo ViewHelper::getInput('title') ?>" size="30" type="text">
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">Talk Description:</label>
            <div class="input">
                <textarea class="span6 required" id="summary" name="summary" rows="7" cols="30"><?php echo ViewHelper::getInput('summary') ?></textarea>
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">Speaker :</label>
            <div class="input">
                <input class="span6 required" id="speaker" name="speaker" value="<?php echo ViewHelper::getInput('speaker') ?>" size="30"></textarea>
            </div>
        </div>

        <div class="clearfix">
            <label for="xlInput3">Slide link:</label>
            <div class="input">
                <input class="span6 url" id="slideLink" name="slideLink" value="<?php echo ViewHelper::getInput('slideLink') ?>" size="30" type="text">
            </div>
        </div>


        <div class="actions">
            <input type="submit" class="btn primary" value="Add Talk" />
        </div>
    </fieldset>
</form>
