<form  class="validationForm" action="<?php echo BASE_URL . '?page=adminCategories&mode=addCategory'; ?>" method="post">
    <fieldset>
        <div class="clearfix">
            <label for="xlInput3"></label>
            <div class="input">
                <input class="required" id="title" name="title" value="<?php echo ViewHelper::getInput('title') ?>" size="30" type="text">
                <input type="submit" class="btn primary" value="Add Category" />
                <span class="help-block">Enter new category name</span>
            </div>
        </div>
    </fieldset>
</form>
