<?php
//$activeEvents = App::getRepository('Event')->getActiveEvents();
//$categories = App::getRepository('Category')->getAllCategories();
//var_dump($attendance);
//exit();
?>

<div class="alert-message block-message success">
    <h4>Welcome to Tech Adda!</h4>
    <p>This is the site where event attendees can leave feedback on a tech event and its sessions. Do you have an opinion? Then <a href="<?php ViewHelper::url('login') ?>">log in</a> and share it!</p>
</div>

<h4>Upcoming Events</h4>

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
                <p>
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

                </p>
            </div>

        </div>

    <?php endforeach; ?>
    
     <?php echo $paginationHtml; ?>

</div>
