
<h4>All Events</h4>

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
                <p class="align-justify"><?php echo $event['summary'] ?></p>
                <div style="margin-bottom: 10px;">
                    <a href="<?php ViewHelper::url('?page=event&id=' . $event['event_id'] . '#comments') ?>"><?php echo $event['total_comments'] ?> comments</a> &nbsp;
                    <strong><?php echo $event['total_attending'] ?> attending</strong> &nbsp;


                    <?php
                    if (in_array($event['event_id'], $attendance)) {
                        ?>
                        <a href="#" class="btn success">You Attending</a>
                        <?php
                    } else {
                        ?>
                        <input id="attendButton<?php echo $event['event_id']; ?>" class="attendButton" type="submit" class="btn" value="I'm attending" />

                        <?php
                    }
                    ?>


                </div>
            </div>

        </div>

    <?php endforeach; ?>
    
    <?php echo $paginationHtml; ?>
    
</div>

