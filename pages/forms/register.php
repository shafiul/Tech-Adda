<div >
    <form class="validationForm" action="<?php echo BASE_URL . '?page=register'; ?>" method="post">
        <fieldset>
            <legend>Register here</legend>
            <div class="clearfix">
                <label for="xlInput3">Username:</label>
                <div class="input">
                    <input class="xlarge required" id="username" name="username" value="<?php echo ViewHelper::getInput('username') ?>" size="30" type="text">
                </div>
            </div>
            <div class="clearfix">
                <label for="xlInput3">Email:</label>
                <div class="input">
                    <input class="xlarge required email" id="email" name="email" value="<?php echo ViewHelper::getInput('email') ?>" size="30" type="text">
                </div>
            </div>
            <div class="actions">
                <input type="submit" value="Register" class="btn primary">
            </div>
        </fieldset>
    </form>
</div>
