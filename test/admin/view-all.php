<?php

session_start();
include("loadsettings.inc.php");
include("checkpass.inc.php");

?>


<html>


<body link=brown vlink=brown> 


<br>
<font face=arial color="brown"><u><h2><center>
.:: View All Images ::.
</center></h2></u></font>


<font face=verdana size=2 color=brown>
<br><br>

<?php
//**********************************************************************************************

$link = mysql_connect($server, $user, $pass);
if(!mysql_select_db($database)) die(mysql_error());


$total = 0;

$q = "SELECT id FROM `images` ORDER BY number DESC";
if(!($result_set = mysql_query($q))) die(mysql_error());
$number = mysql_num_rows($result_set);
$total = $number;

if ($number) {
  while ($row = mysql_fetch_row($result_set)) 
     $id[] = $row[0];
}




if ($total) {
  
  $max_show = 20;
         
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


  echo "<b>There are <b> '$total' </b> images uploaded yet in all categories.</b><br><br>";
  echo "<br><center><table border=0 bordercolor=purple><tr>";

  $x = -1;

  for ($i=$from1; $i < $from2; $i++) {
    
    $id1 = $id[$i];    
    $x++;
    
    if (($x % 4) == 0) echo "</tr><tr>";

    echo "<td  valign=top height=200>";
    echo "<a href='../show-image.php?id={$id1}' target='_blank'>";
    echo "<img src='../thumb.php?id={$id1}'>";
    echo "</a></td>";

  }

  echo "</tr></table>";





  //SHOW THE NEXT AND PREVIOUS LINKS IN A TABLE (IF REQUIRED)
  echo "<br><br><table width='100%'><tr>";
  echo "<td align='right' width='50%'>&nbsp;";

  if ($from1 > 0)
  {
     $previous = $page - 1;
     echo "<a href='view-all.php?page=$previous'><< Previous Page</a>";
  } echo "</td>";    
    

  echo "<td align=left width=50%>&nbsp;&nbsp;&nbsp;";
  if ($from2 < $total)
  {
     $next = $page + 1;
     echo "<a href='view-all.php?page=$next'>Next Page >></a>";
  } echo "</td></tr></table>";



}
else
  echo "No images have been uploaded yet !";


?>

<br><br>

</font>

</body>
</html>




