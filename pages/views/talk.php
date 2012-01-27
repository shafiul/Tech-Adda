<h2><?php echo $talk['title'] ?></h2>
<div class="meta">
    by <strong><?php echo $talk['speaker'] ?></strong> <br />
    Talk at <a href="<?php ViewHelper::url('?page=event&id=' . $event['event_id']) ?>"><?php echo $event['title'] ?></a>
</div>

<p class="align-justify"><?php echo nl2br($talk['summary']) ?></p>

<h3>Comments</h3>

<div class="comments">
    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <div class="meta"><strong><?php echo empty($comment['name']) ? $comment['email'] : $comment['name'] ?></strong> on <em><?php echo ViewHelper::formatDate($comment['create_date']) ?></em> said:</div>
            <?php echo nl2br($comment['body']) ?>
        </div>
    <?php endforeach; ?>
</div>


<?php
   // echo "<pre>";   
    echo $paginationHtml; 
    //echo "</pre>";   
?>


<div class="post-comment">

    <?php
    ViewHelper::includeForm('addComment', array(
        'mode' => 'talk',
        'modeId' => $talk['talk_id'])
    );
    ?>

</div>
