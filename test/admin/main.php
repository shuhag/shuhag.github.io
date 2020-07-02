<?php

session_start();
include("loadsettings.inc.php");
include("checkpass.inc.php");



if (isset($_GET['act']))
{
 $act = trim($_GET['act']);
 if ($act != "")
 {
  if ($act == "signout")
  {
    session_destroy();
    die("<br><font face=verdana size=4 color=red>You are signed out successfully !<br><br><a href=index.php>Click here to go to the main page</a></font>");
  } 
 }
}


?>

<html>

<body link=brown vlink=brown>


<br>
<font face="arial" color="brown"><u><h2><center>
.:: Welcome to <? echo $webtitle; ?> Admin Panel ::.
</center></h2></u></font>


<br>


<font color=brown face=verdana size=2>
<br>


<h4><font color=purple face=verdana>Statistics:</font></h4>

<?php

$r = mysql_query("SELECT id FROM `images`");
$totalimages = mysql_num_rows($r);

$r = mysql_query("SELECT id FROM `images` WHERE type = 'public'");
$totalanonymous = mysql_num_rows($r);

$r = mysql_query("SELECT id FROM `images` WHERE approved = 'false'");
$totalunapproved = mysql_num_rows($r);

$r = mysql_query("SELECT id FROM `members`");
$totalmembers = mysql_num_rows($r);

$r = mysql_query("SELECT id FROM `galleries`");
$totalgalleries = mysql_num_rows($r);

$r = mysql_query("SELECT id FROM `report`");
$totalabused = mysql_num_rows($r);


echo "Total Images Uploaded: <b> $totalimages </b><br>
      Anonymous Images: <b> $totalanonymous </b><br>
      Unapproved Images: <b> $totalunapproved </b><br><br>
                    
      Total Members Registered: <b> $totalmembers </b><br>
      Total Galleries Created: <b> $totalgalleries </b><br><br>

      Abused Images: <b> $totalabused</b><br>"; 


?>



<br><br><br><br><hr color=purple>



</font>

</body>
</html>


