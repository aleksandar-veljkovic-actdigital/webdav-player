<?php 
session_start();
echo session_id ().'<hr />';
echo 'hello from Target Service';
echo '<br />';
$headRq = apache_request_headers();
//var_dump($headRq);

