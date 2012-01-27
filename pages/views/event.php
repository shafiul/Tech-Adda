

<div class="row single-event">

    <div class="span2" style="padding: 10px 0 10px 10px;">
        <?php if (!empty($event['logo'])): ?>
            <img width="90" height="90" src="<?php echo $event['logo'] ?>" />
        <?php else: ?>
            <img src="http://placehold.it/90x90" />
        <?php endif; ?>
    </div>

    <div class="span7">
        <h2><?php echo $event['title'] ?></h2>
        <div class="meta">
            <?php echo ViewHelper::formatDate($event['start_date']) ?> - <?php echo ViewHelper::formatDate($event['end_date']) ?> <br />
            <?php echo $event['location'] ?><br /><br />
            <?php
            if ($isAttending) {
                ?>
                <a href="#" class="btn success">You Attending</a>
                <?php
            } else {
                ?>
                <input id="attendButton<?php echo $event['event_id']; ?>" class="attendButton" type="submit" class="btn" value="I'm attending" />

                <?php
            }
            ?>
            &nbsp; <strong><?php echo $event['total_attending'] ?> people</strong> attending so far!
        </div>
    </div>

</div>

<p class="align-justify"><?php echo nl2br($event['summary']) ?></p>
<p><strong>Event Link:</strong> <br /><a href="<?php echo $event['href'] ?>"><?php echo $event['href'] ?></a></p>
=======
<div class="span2" style="padding: 10px 0 10px 10px;">
    <?php if (!empty($event['logo'])): ?>
        <img width="90" height="90" src="<?php echo $event['logo'] ?>" />
    <?php else: ?>
        <img src="http://placehold.it/90x90" />
    <?php endif; ?>
</div>

<h3>Talks</h3>
<ul>
    <?php
    $talkCount = count($talks);
    if ($talkCount > 0) {
        foreach ($talks as $talk):
            ?>
            <li><a href="<?php ViewHelper::url('?page=talk&id=' . $talk['talk_id']) ?>"><?php echo $talk['title'] ?></a></li>
            <?php
        endforeach;
    }else {
        echo 'No talk found';
    }
    ?>

</ul>
<a class="btn info" href="<?php ViewHelper::url('?page=addTalk&id=' . $event['event_id']); ?>"> Add <?php echo ($talkCount > 0 ? 'another' : 'a') ?> talk</a>

<br/>
<br/>
<br/>

<h3>Comments</h3>
<div class="comments">
    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <div class="meta"><strong><?php echo empty($comment['name']) ? $comment['email'] : $comment['name'] ?></strong> on <em><?php echo ViewHelper::formatDate($comment['create_date']) ?></em> said:</div>
            <?php echo nl2br($comment['body']) ?>
        </div>
    <?php endforeach; ?>
</div>

<?php echo $paginationHtml; ?>


<div class="post-comment">

    <?php
    ViewHelper::includeForm('addComment', array(
        'mode' => 'event',
        'modeId' => $event['event_id'])
    );
    ?>

</div>
