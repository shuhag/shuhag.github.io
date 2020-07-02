<?php
 session_start();
 
 include("db-info.php");
 $link = mysql_connect($server, $user, $pass);
 if(!mysql_select_db($database)) die(mysql_error());

 include("session.inc.php");
 include("loadsettings.inc.php");
?>

<html>

<head>

<title><? echo $webtitle; ?> - Free Image Hosting</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>

<?php

if (isset($_POST["postcomment"])) {

 $imageid = $_POST['id'];

 $path = "show-image.php?id=" . $imageid;
 $comment = htmlspecialchars(trim($_POST['comment']));
 
 if (!get_magic_quotes_gpc())
   $comment = addslashes($comment);  

 if ($comment == "") {
    echo "<script language=\"JavaScript\">
           alert('Please enter a comment to post first!');
           window.location.href='$path';
          </script>";
    die();
 }

 if (strlen($comment) > 200) {
    echo "<script language=\"JavaScript\">
           alert('Sorry!  The comment is too long to post');
           window.location.href='$path';
          </script>";
    die();
 }

 

 if ($session == false) die();

 $link = mysql_connect($server, $user, $pass);
 if(!mysql_select_db($database)) die(mysql_error());

 $comment = "<b>Posted By: $username</b><br><br>$comment";

 mysql_query("INSERT INTO `comments` (imageid, comment) VALUES('$imageid', '$comment')");

 echo "<meta http-equiv=\"refresh\" content=\"0; url='$path'\" />";

} 

?>


</body>
</html>
