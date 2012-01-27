
<h4>Events on <?php echo $category['title'] ?></h4>

<div class="events">

    <?php foreach ($activeEvents as $event): ?>

        <div class="row event">

            <div class="span2">
                <?php if (!empty($event['logo'])): ?>
                    <img width="90" height="90" src="<?php echo $event['logo'] ?>" />
                <?php else: ?>
                    <img src="http://placehold.it/90x90" />
                <?php endif; ?>
            </div>

            <div class="span8">
                <h3><a href="<?php ViewHelper::url('?page=event&id=' . $event['event_id']) ?>"><?php echo $event['title'] ?></a></h3>
                <p class="align-justify"><?php echo nl2br($event['summary']); ?></p>
                <p>
                    <a href="<?php ViewHelper::url('?page=event&id=' . $event['event_id'] . '#comments') ?>"><?php echo $event['total_comments'] ?> comments</a> &nbsp;
                    <strong><?php echo $event['total_attending'] ?> attending</strong> &nbsp;
                    <a href="#" class="btn small">I'm attending</a>
                </p>
            </div>

        </div>

    <?php endforeach; ?>
    
    <?php
        if(empty($activeEvents))
            echo 'No events found!';
    ?>

</div>

