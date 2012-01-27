<?php

$pathinfo = pathinfo($_SERVER['PHP_SELF']);
$baseurl = $pathinfo['dirname'] . '/';
define('BASE_URL', $baseurl);
define('IMAGE_URL',BASE_URL."/assets/images");


define('MSG_SUCCESS', 'success');
define('MSG_ERROR', 'error');
define('MSG_WARNING', 'warning');
define('MSG_INFO', 'info');

//Database Table names
define('TABLE_COMMENTS','comments');
define('TABLE_ATTENDEES','attendees');
define('TABLE_CATEGORIES','categories');
define('TABLE_EVENTS','events');
define('TABLE_EVENTS_CATEGORY','event_category');
define('TABLE_TALKS','talks');
define('TABLE_USERS','users');


define('PAGINATION_LIMIT',2);

define('NICE_URL_ENABLED', true);


define('CONSUMER_KEY', "oxcmzZq1ozaNqEuc26jLBA");
define('CONSUMER_SECRET', "q3kpDSWcrhaSmqk7bZv8VNYwJHT7X20zQzV9v3rEI");
define('OAUTH_CALLBACK', "http://localhost".BASE_URL."?page=login&loginType=twitter");

?>
