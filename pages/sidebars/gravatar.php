
<div class="widget">

    <h4>Who's Attending</h4>
    
    <br />

    <?php
    
//        var_dump($gravatarURLs);
//        exit();
    
        foreach($gravatarURLs as $urls){
            echo '<img src="' . $urls . '" /> <br /><br />';
        }
    
    ?>

</div>
