<?php

include("../db-info.php");
$link = mysql_connect($server, $user, $pass);
if(!mysql_select_db($database)) die(mysql_error());

$result = mysql_query("SELECT * FROM `settings`");
$r = mysql_fetch_array($result);


$password = $r['password'];
$website = $r['website'];
$webtitle = $r['title'];
$description = $r['description'];
$keywords = $r['keywords'];
$maxsizeguest = $r['maxsizeguest'];
$maxsizemember = $r['maxsizemember'];
$watermark = $r['maxsizemember'];


?>


