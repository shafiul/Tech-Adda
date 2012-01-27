
<form  class="validationForm" action="<?php echo BASE_URL . '?page=username'; ?>" method="post">
    <fieldset>
        <div class="clearfix">
            <label for="xlInput3"></label>
            <div class="input">
                <input class="required" id="title" name="username" value="<?php echo ViewHelper::getInput('username') ?>" size="25" type="text">
                <input type="submit" class="btn primary" value="Update your name" />
                <span class="help-block">Plase provide your name</span>
            </div>
        </div>
    </fieldset>
</form>