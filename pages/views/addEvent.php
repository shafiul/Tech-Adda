
<h2>Submit an event!</h2>

<p class="align-justify">Submit your event here to be included on Tech Adda. The site is aimed at events with sessions, where organisers are looking to use this as a tool to gather feedback.</p>

<div class="post-comment">
    <?php
    ViewHelper::includeForm('event', array(
        'action' => 'addEvent',
        'mode' => 'addEvent',
        'categories' => $categories
    ));
    ?>
</div>

