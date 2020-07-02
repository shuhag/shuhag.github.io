<?php

session_start();
include("loadsettings.inc.php");
include("checkpass.inc.php");

?>


<html>

<body link=brown vlink=brown>


<br>
<font face="arial" color="brown"><u><h2><center>
.:: Approve / Delete Abused Images ::.
</center></h2></u></font>


<font face=verdana size=2 color=brown>
<br><br>


<?php


$link = mysql_connect($server, $user, $pass);
if(!mysql_select_db($database)) die(mysql_error());



if (isset($_GET["act"])) {

  if ($_GET["act"] == "")  die();
  if (!isset($_GET["id"])) die();
 
  $act = $_GET["act"];
  $id = $_GET["id"];
   

  if ($act == "approve") {
    
    $q = "DELETE FROM `report` WHERE imageid = '$id'";
    mysql_query($q);

    echo "Image approved !";
  }



  if ($act == "delete") {
    deleteImage($id);
    $q = "DELETE FROM `report` WHERE imageid = '$id'";
    mysql_query($q);
    
    echo "Image deleted !";
  }




  echo "<br><br><a href='abuse.php'>Go back</a> <br>";
  echo "<meta http-equiv=\"refresh\" content=\"2; url='abuse.php'\" />";

  die();
}

//*****************************************************************************************************


$total = 0;


$q = "SELECT imageid FROM `report` ORDER BY id DESC";
if(!($result_set = mysql_query($q))) die(mysql_error());
$number = mysql_num_rows($result_set);


if ($number) {
  while ($row = mysql_fetch_array($result_set)) {
     $r = mysql_query("SELECT ip FROM `images` WHERE id = '{$row['imageid']}'");
     $n = mysql_num_rows($r);
     if ($n) {
         $imageid[] = $row['imageid'];
         $row1 = mysql_fetch_row($r);
         $ip[] = $row1[0];
         $total++;
     }
  
  }
}




if ($total) {
  
  $max_show = 6;
         
  if (isset($_GET["page"]))
    $page = $_GET["page"];   
  else
    $page = 1;


  
 $from2 = $page * $max_show;
 if ($from2 > $total)
 {
     $diff = $total % $max_show;
     $from2 = $total;
     $from1 = $from2 - $diff;
 }     
 else
     $from1 = $from2 - $max_show;


  echo "<b>There are '$total' reported abuse images.</b><br><br><br>";
  

  echo "<center><table border=1 bordercolor=purple width=500><tr align=center>";
  echo "<td><b>Image</b></td>
        <td><b>IP Address</b></td>
        <td><b>Action</b></td></tr>";  
  

  for ($i=$from1; $i < $from2; $i++) {
    
    $imageid1 = $imageid[$i];    
    $ip1 = $ip[$i];
 
    echo "<tr align=center>";
    echo "<td><a href='../show-image.php?id={$imageid1}' target='_blank'>";
    echo "<img src='../thumb.php?id={$imageid1}' border=0>";
    echo "</a></td>";
   
    echo "<td> $ip1 </td>";
   
    echo "<td> <a href='abuse.php?act=approve&id={$imageid1}'>Approve</a>
          ||    <a href='abuse.php?act=delete&id={$imageid1}'>Delete</a>";
    echo "</tr>";
  
  }

  echo "</table>";





  //SHOW THE NEXT AND PREVIOUS LINKS IN A TABLE (IF REQUIRED)
  echo "<br><br><table width='100%'><tr>";
  echo "<td align='right' width='50%'>&nbsp;";

  if ($from1 > 0)
  {
     $previous = $page - 1;
     echo "<a href='abuse.php?page=$previous'><< Previous Page</a>";
  } echo "</td>";    
    

  echo "<td align=left width=50%>&nbsp;&nbsp;&nbsp;";
  if ($from2 < $total)
  {
     $next = $page + 1;
     echo "<a href='abuse.php?page=$next'>Next Page >></a>";
  } echo "</td></tr></table>";



}
else
  echo "<b>No reported abused images were found !</b>";


echo "<br><br><b>You can block the person uploading any abused image by going to <a href='blockip.php'>'Block IP'</a> page.</b>";



function deleteImage($id) {

 $result = mysql_query("SELECT image, thumb FROM `images` WHERE id='$id'");
 $number = mysql_num_rows($result);
 if (!$number) die("Sorry ! The image you specified does not exists.");

 $row = mysql_fetch_array($result);
 $image = "../" . $row['image'];
 $thumb = "../" . $row['thumb'];

 unlink($image);
 unlink($thumb);

 mysql_query("DELETE FROM `images` WHERE id='$id'");

}



?>




<br><br>

</font>

</body>
</html>

