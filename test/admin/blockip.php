<?php

session_start();
include("loadsettings.inc.php");
include("checkpass.inc.php");

?>

<html>

<body link=brown vlink=brown>


<br>
<font face="arial" color="brown"><u><h2><center>
.:: Block IP ::.
</center></h2></u></font>


<br>


<font color=brown face=verdana size=2>
<br>


<?php 

if (isset($_GET["delete"])) {

 if ($_GET["delete"] == "") die();
 $id = $_GET["delete"];
 
 mysql_query("DELETE FROM `blockedip` WHERE id = '$id'");
 echo "<b>IP removed from the block list</b>";
}



if (isset($_POST["blockip"])) {

  if ($_POST["ip"] == "") die();
  $ip = $_POST["ip"];

  mysql_query("INSERT INTO `blockedip` (ip) VALUES ('$ip')");
  echo "<b>IP added to the block list</b>";
}
  





echo "<form method='POST' action='blockip.php'>
      IP to block: &nbsp; <input type='text' maxlength='20' name='ip'> &nbsp; &nbsp;
      <input type='submit' name='blockip' value='Block'></form><br><br>";



$result = mysql_query("SELECT * FROM `blockedip`");
$number = mysql_num_rows($result);


if ($number) {
   
   echo "<h4>Blocked IP's: </h4>";
   echo "<ul>";
   
   while (($r = mysql_fetch_array($result))) {
     $id = $r['id'];
     $ip = $r['ip'];
    
      echo "<li> $ip &nbsp; &nbsp; <a href='blockip.php?delete=$id'>Remove</a>"; 
   }   
   echo "</ul>";
}
else
   echo "<b>You have not blocked any ip yet !</b>";


?>  
 



</font>
</body>
</html>



