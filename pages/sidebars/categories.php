
<div class="widget">

    <h4>Categories</h4>

    <ul>
        <?php foreach ($categories as $category): ?>
            <li><a href="<?php ViewHelper::url('?page=cat&id=' . $category['category_id']) ?>"><?php echo $category['title'] ?></a></li>
        <?php endforeach; ?>
    </ul>

</div>
