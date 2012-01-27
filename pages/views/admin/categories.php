<h2>Add new category</h2>
<?php ViewHelper::includeForm('addCategory'); ?>


<h2>Category List</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($categories as $category) {
            ?>
            <tr>
                <td><?php echo $category['category_id'] ?></td>
                <td><?php echo $category['title'] ?></td>
                <td><?php echo ViewHelper::includeForm('deleteCategory', array('categoryId' => $category['category_id'])) ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>