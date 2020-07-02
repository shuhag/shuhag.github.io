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


<script language="JavaScript">

function showWindow(id) {
   window.open("show-fullsize.php?id=" + id,"FullsizeImage","menubar=no,width=800,height=600,scrollbars=yes,status=yes,resizable=yes");
}

</script>

</head>


<body link=#336699 vlink=#336699 alink=#336699>
<?php include("header.php"); ?>

<center>
<div class="content-container">
   

<!-- ######################################################################################### --> 

<?php


if (isset($_GET["id"])) {

$id = $_GET["id"];
if ($id == "") die("Please specify an image id!");

$result = mysql_query("SELECT * FROM `images` WHERE id='$id'");
$number = mysql_num_rows($result);
$row = mysql_fetch_array($result);
if (!$number) die("Sorry ! The image you specified does not exists, or maybe it has been deleted due to violation of our <a href='terms.php'>TOS</a> !");

$type = $row['type'];


//IF IMAGE TYPE IS PRIVATE THEN SHOW THE PASSWORD FORM
if ($type == "member-private") {

  if ($row['userid'] != $userid) {
      echo "This image is private. Please enter the password in order to view it<br><br>";
      echo "<form action='show-image.php' method='POST'>
           <LABEL id='title'>Password:</LABEL> <input type='password' name='password' maxlength='30'>";
      echo "<input type='hidden' name='id' value='$id'> <input type='hidden' name='image' value='member'> ";
      echo "&nbsp; &nbsp; <input type='submit' value='View'></form>";
  }
  else show();

}


//IF THE IMAGE TYPE IS MEMBER PUBLIC THEN SHOW IT!
if ($type == "member-public") {
  show();
}


//IF THE IMAGE TYPE IS ANONYMOUS PUBLIC THEN SHOW IT!
if ($type == "public") {
  show();
}


//IF THE IMAGE IS OF A GALLERY THEN:
if ($type == "gallery") {

 $q = "SELECT galleryid FROM `images` WHERE id = '$id'";
 if(!($result_set = mysql_query($q))) die(mysql_error());
 $row = mysql_fetch_row($result_set);
 $galleryid = $row[0];

 
 //CHECK IF THE GALLERY IN WHICH THE IMAGE IS PRESENT IS PUBLIC OR PRIVATE
 $result_set = mysql_query("SELECT * FROM `galleries` WHERE id = '$galleryid'");
 $row = mysql_fetch_array($result_set);
 $type1 = $row['type'];
 $imguserid = $row['userid'];


 //IF PRIVATE THEN SHOW THE PASSWORD FORM 
 if ($type1 == "private") {

    if ($imguserid == $userid) {
       show();
    } else { 
     
         if (isset($_SESSION['gallery' . $galleryid]))
              show();
         else {
              echo "This image is private. Please enter the password in order to view it<br><br>";
              echo "<form action='show-image.php' method='POST'>Password: <input type='password' name='password' maxlength='30'>";
              echo "<input type='hidden' name='id' value='$id'>  <input type='hidden' name='image' value='gallery'>";
              echo "&nbsp; &nbsp; <input type='submit' value='View'></form>";
         }
    }
    
}
 else
   show();

}





//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$


}
else {

    //IF PASSWORD IS SUBMITTED
    if (isset($_POST["password"]))  {

       $id = $_POST["id"];
       $image = $_POST["image"];

       //GET THE ASSOCIATIVE USER ID  
       $imguserid = getUserId($id);

       //EXTRACT HIS USER PASS
       $r = mysql_query("SELECT userpass FROM `members` WHERE id = '$imguserid'");
       $row1 = mysql_fetch_row($r);
       $userpass = $row1[0];

       //THEN CHECK IT.. IF CORRECT THEN SHOW
       if ($_POST["password"] != $userpass)
          echo "Sorry ! You have specified an invalid password.";
       else
          show();
   }

}




//THIS FUNCTION DISPLAYS THE IMAGE
function show() {

  global $id; global $session; global $userid;
  
  include("loadsettings.inc.php");


  $q = "SELECT * FROM `images` WHERE id = '$id'";
  if(!($result_set = mysql_query($q))) die(mysql_error());
  $row = mysql_fetch_array($result_set);

  $thumb = $row['thumb'];
  $image = $row['image'];

  //UPDATE VIEWS COUNT AND LAST ACCESS DATE
  $views = $row['views'] + 1;
  $access = date("y-m-d");
  $r = mysql_query("UPDATE `images` SET views = '$views', access = '$access' WHERE id = '$id' ");


  $imguserid = getUserId($id);

  $own = false;
  if ($imguserid != -1) {
       
       if ($userid == $imguserid) 
          $own = true;

       $r = mysql_query("SELECT username FROM `members` WHERE id = '$imguserid'");
       $row1 = mysql_fetch_row($r);
       $username = $row1[0];
  }
  else $username = "Anonymous";
   


  echo "<center>";
  echo "<br><LABEL id='title'>Views:</LABEL> $views";
  echo "<br><LABEL id='title'>Date Added:</LABEL> {$row['date']}";
 

  //***********************************************************************************
 
     //Check if image size is bigger than 800 X 800 then make it small to atleast 800 but proportionally
     $img = imagecreatefromunknown($image);
                                  
     $mainWidth = imagesx($img);
     $mainHeight = imagesy($img);
              
     if (($mainWidth > 800) || ($mainHeight > 800)) 
     { 
          $a = ($mainWidth >= $mainHeight) ? $mainWidth : $mainHeight; 
          $div = $a / 800;
          $mainWidth = intval($mainWidth / $div);
          $mainHeight = intval($mainHeight / $div);
 
          echo "<br><br><a href='$image' title='Click here to see fullsize original image' target='_blank'>
                  <img src='$image' border=1 width='$mainWidth' height='$mainHeight'>
                  </a>";
     }
     else {
         echo "<br><br><img src='$image' border='1'>";
     }

  //*********************************************************************************** 



  echo "<br><br><LABEL id='title'>Details:</LABEL> {$row['details']}";
  echo "<br><LABEL id='title'>Uploaded By:</LABEL> $username";
  echo "<br><br><LABEL id='title'>Tags:</LABEL><br>{$row['tags']}";
  
  echo "<br><br><br><br>";

  echo "<div class='emailBox'>
        <form method='POST' action='email.php'>
        <LABEL id='title'>Send this image to friend via email:</LABEL><br> &nbsp; <input type='text' size='20' name='email'>&nbsp; &nbsp;
        <input type='submit' value='Send' name='emailImage'>
        <input type='hidden' name='id' value='$id'>
        <br>Separate multiple emails by commas (,)
        </form></div>";

  echo "<br><br><a href='report.php?id=$id'><img src='images/abuse.png' border=0></a>";
 
  if ($session == true) 
     echo "&nbsp;  <a href='addfavourite.php?id=$id'><img src='images/favourites.png' border=0></a>";


  echo "<br><br><br><hr color='#42679c'><br>";

  //*****************************************************************************************************


if ($imguserid != -1) {  

  $r = mysql_query("SELECT * FROM `comments` WHERE imageid = '$id' ORDER BY id DESC");
  $n = mysql_num_rows($r);  


  echo "<div style='FLOAT: left; WIDTH: 500px; TEXT-ALIGN: left; BORDER-RIGHT: #42679c 2px solid; 
                    PADDING-LEFT: 20px; PADDING-RIGHT: 20px; HEIGHT: 300px; MARGIN-RIGHT: 20px'>";
  echo "<h2><u>Comments</u></h2>";

  if ($n) {
 
     $ccount = -1;
     while ($row1 = mysql_fetch_array($r)) {
       $comment = str_replace("\n", "<br>", $row1['comment']);
     
       $ccount++;
       if ($ccount == 5) 
         echo "<br><a href='#' style='FONT-SIZE: 16px'
                 onclick=\"getElementById('allcomments').style.display='block'; this.style.display='none'\">
                 View All Comments</a>
               <div style='display: none' id='allcomments'>";

       echo "<div class='commentbox'> $comment ";
       if ($own == true) 
           echo "<br><a href='deletecomment.php?id={$row1['id']}'>Delete</a>";
       echo "</div>";
     }
      if ($ccount > 4) echo "</div>";  
 
  }
  else
      echo "<div class='commentbox'>There are no comments for this image !</div>";

    
  if ($session == true) {
 
    echo "<form method='POST' action='postcomment.php'>
          <br><h2>Post Comment</h2>
          <textarea cols=40 rows=4 name='comment'></textarea><br>
          <input type='hidden' name='id' value='$id'>
          <input type='submit' value='Post Comment' name='postcomment'>
          </form> <b>Max-chars: 200</b>
         ";    
    }
   echo "</div>";

}


  //********************************************************************************************************
  
  echo "<div style='TEXT-ALIGN: left'>
        <h2><u>Codes:</u> </h2>";
  echo "<table style='border-collapse: collapse'><tr><td>";

  echo "<LABEL id='title'>HTML:</LABEL><br><input type='text' size=60 onclick=\"this.select();\" value=\"<a href='{$website}/show-image.php?id=$id'> <img src='{$website}/{$thumb}' alt='Image Hosting' border='0'> </a>\">";
  echo "<br><br>";
    
 
  echo "<LABEL id='title'>BB Code:</LABEL><br><input type='text' size=60 onclick=\"this.select();\" value=\"[URL={$website}/show-image.php?id={$id}] [IMG]{$website}/{$thumb}[/IMG][/URL]\">";
  echo "<br><br>";


  echo "<LABEL id='title'>Direct Image Link (HTML):</LABEL><br><input type='text' size=60 onclick=\"this.select();\" value=\"<a href='{$website}'> <img src='{$website}/{$image}'> </a>\">";
  echo "<br><br>";


  echo "<LABEL id='title'>Direct Image Link (BB Code):</LABEL><br><input type='text' size=60 onclick=\"this.select();\" value=\"[URL={$website}] [IMG]{$website}/{$image}[/IMG][/URL]\">";
  echo "<br><br>";
 
  echo "<LABEL id='title'>URL:</LABEL><br><input type='text' size=60 onclick=\"this.select();\" value=\"{$website}/show-image.php?id=$id\">";
  
  echo "</td></tr></table></div>";
  


  //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$

  echo "<p style='CLEAR: both'>";
  echo "<br><br><hr color='#42679c'><br><br>";
  include("random.inc.php");  


}



// Returns the userid of image.. if its a gallery image it retrieves the userid from the galleries table
function getUserId($id) {

 $userid = "";
 $result = mysql_query("SELECT * FROM `images` WHERE id='$id'");
 $row = mysql_fetch_array($result);
 $type = $row['type'];

 if ($type == "gallery") {

      $q = "SELECT galleryid FROM `images` WHERE id = '$id'";
      if(!($result_set = mysql_query($q))) die(mysql_error());
      $row = mysql_fetch_row($result_set);
      $galleryid = $row[0];

      $result_set = mysql_query("SELECT userid FROM `galleries` WHERE id = '$galleryid'");
      $row = mysql_fetch_row($result_set);
      $userid = $row[0];
 }
 else {
 
      $q = "SELECT userid FROM `images` WHERE id = '$id'";
      if(!($result_set = mysql_query($q))) die(mysql_error());
      $row = mysql_fetch_row($result_set);
      $userid = $row[0];
 }


 return $userid;

}





function findExtension ($filename)
{
   $filename = strtolower($filename) ;
   $exts = split("[/\\.]", $filename) ;
   $n = count($exts)-1;
   $exts = $exts[$n];
   return $exts;
}


function imagecreatefromunknown($path) {

   $ext = findExtension($path);
    
   switch ($ext) {
      case "jpg":
        $img = imagecreatefromjpeg($path);
        break;
      case "gif":
        $img = imagecreatefromgif($path);
        break;
      case "png":
        $img = imagecreatefrompng($path);
        break;
  }

  return $img;
}





?>

<!-- ######################################################################################### -->

<?php  include("footer.php"); ?>


</div>
</center>
</body>
</html>
