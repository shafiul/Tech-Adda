
<h2>Update event!</h2>

<div class="post-comment">
    <?php
    ViewHelper::includeForm('event', array(
        'action' => 'updateEvent',
        'mode' => 'updateEvent',
        'eventId' => $eventId
    ));
    ?>
</div>

