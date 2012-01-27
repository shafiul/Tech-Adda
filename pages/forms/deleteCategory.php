
<form  action="<?php echo BASE_URL . '?page=adminCategories&mode=deleteCategory'; ?>" method="post">
    <fieldset>
        <input type="hidden" name="categoryId" value="<?php echo$categoryId ?>" />
        <input type="submit" class="btn danger" value="Delete" />
    </fieldset>
</form>
