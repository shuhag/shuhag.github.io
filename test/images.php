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

<meta name="description" content="<? echo $description; ?>" />
<meta name="keywords" content="<? echo $keywords; ?>" />

</head>


<body link=#336699 vlink=#336699 alink=#336699>
<?php include("header.php"); ?>

<center>
<div class="content-container">
   
<!-- ################################################################################################## -->

<br><img src="images/images.gif" align=left hspace=10 height=25>
&nbsp; &nbsp;<a href="images.php" style="FONT-SIZE: 18px">Newest</a>&nbsp; &nbsp; |
&nbsp; &nbsp;<a href="popular.php" style="FONT-SIZE: 18px">Most Popular</a>&nbsp; &nbsp; |
&nbsp; &nbsp;<a href="search.php" style="FONT-SIZE: 18px">Search</a>&nbsp; &nbsp;
<br><br><br>
 
<?php

$max_show = 20;
$images_in_row = 5;
$total = 0;


   $q = "SELECT * FROM `images` WHERE (type = 'member-public') OR (type = 'gallery') OR (type = 'public') ORDER BY number DESC";
          if(!($result_set = mysql_query($q))) die(mysql_error());
          $number = mysql_num_rows($result_set);

          if ($number) {
            while ($row = mysql_fetch_array($result_set))
            {
               if ($row['type'] == "gallery") {
                 $galleryid = $row['galleryid'];
                 $result = mysql_query("SELECT type FROM `galleries` WHERE id = '$galleryid'");
                 $row1 = mysql_fetch_row($result);
                 $a = $row1[0];

                 if ($a == "public") {
                    $arr[] = $row['id'];
                    $total++;
                 }
               }
               else {
                 $arr[] = $row[0];
                 $total++;
               }
            }
          }




if ($total) {



 if (isset($_GET["page"]))
      $page = $_GET["page"];   
 else
      $page = 1;

 

$from2 = $page *  $max_show;
if ($from2 > $total)
{
    $diff = $total % $max_show;
    $from2 = $total;
    $from1 = $from2 - $diff;
}
else
    $from1 = $from2 - $max_show;



 echo "<center><h1>Newest Images</h2></center><br>";
 echo "</p>
        <table width=100% style=\"border-collapse: collapse\">
        <tr>";
 
 $x = -1;
 for ($i=$from1; $i < $from2; $i++)
 {
   $id = $arr[$i];

   $x++;
   if (($x % $images_in_row) == 0) echo "</tr><tr>";

   echo "<td align=center height=200>";
   echo "<a href=\"show-image.php?id=$id\">";
   echo "<img src='thumb.php?id=$id' style=\"opacity: 1;filter:alpha(opacity=100)\"
                   onmouseover=\"this.style.opacity=0.4;this.filters.alpha.opacity=40\"
                   onmouseout=\"this.style.opacity=1;this.filters.alpha.opacity=100\" />";
   echo "</a></td>";
 }
 
 echo "</tr></table>";



 //SHOW THE NEXT AND PREVIOUS LINKS IN A TABLE (IF REQUIRED)
 echo "<br><br><table width='100%'><tr>";
 echo "<td align='right' width='50%'>&nbsp;";

 if ($from1 > 0)
 {
      $previous = $page - 1;
       echo "<a href='images.php?page=$previous'><< Previous Page</a>";
 } echo "</td>";    
    

 echo "<td align=left width=50%>&nbsp;&nbsp;&nbsp;";
 if ($from2 < $total)
 {
      $next = $page + 1;
      echo "<a href='images.php?page=$next'>Next Page >></a>";
 } echo "</td></tr></table>";


}
else
  echo "No public images have been uploaded yet !";




?>


<!-- ################################################################################################## -->


<?php  include("footer.php"); ?>


</div>
</center>
</body>
</html>
