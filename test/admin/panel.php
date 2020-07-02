<?php

session_start();
include("loadsettings.inc.php");
include("checkpass.inc.php");

?>

<HTML>
	<HEAD>
		<TITLE><? echo $webtitle; ?> - Administration Panel</TITLE> 
	</HEAD>

	<frameset cols="280,*" frameborder="1" framespacing="0" border="1" name=all>
		<frame src="navigation.php" name="A" MARGINHEIGHT="0" MARGINWIDTH="0">
		<frame src="main.php" name="B">
	</frameset>
</HTML>


