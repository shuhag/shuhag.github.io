<?php

  //#####################################################################################
  
  //This script processes the uploaded images, puts a watermark on it, insert records 
  // into the database and also shows code for each image. 

  //#####################################################################################


  session_start();
  $session = false;
 
  include("db-info.php");
  $link = mysql_connect($server, $user, $pass);
  if(!mysql_select_db($database)) die(mysql_error());
  
  include("loadsettings.inc.php");

  $type = "public";

   if (isset($_SESSION["imagehost-user"]))
   { 
      $session = true;
      $username = $_SESSION["imagehost-user"];
      $password = $_SESSION["imagehost-pass"];
       
    
      $q = "SELECT id FROM `members` WHERE (username = '$username') and (password = '$password')";
      if(!($result_set = mysql_query($q))) die(mysql_error());
      $number = mysql_num_rows($result_set);
      
      if (!$number) {
         session_destroy();
         $session = false;
      }else {
         $row = mysql_fetch_row($result_set); 
         $loggedId = $row[0];
         
         if (isset($_POST["tags1"])) {

            $opt = $_POST['opt'];
            if ($opt == "gallery") {
               $galleryid = $_POST["galleryid"]; 
               $result = mysql_query("SELECT type FROM `galleries` WHERE id = '$galleryid'");
               $n = mysql_num_rows($result);
               if (!$n) die();
               $row = mysql_fetch_array($result); 
               $type = $row['type'];
            }
            else {
               if (isset($_POST["private"]))
                  $type = "private";
               else 
                  $type = "public";
            }

         }
      } 

      
   }
   else
      $session = false;

//*************************************************************************************************

?>


<html>

<head>

<title><? echo $webtitle; ?> - Free Image Hosting</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>


<body link=#336699 vlink=#336699 alink=#336699>
<?php include("header.php"); ?>

<center>
<div class="content-container">


<!-- ############################################################################################### -->
 
<?php


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



$max = 5;
$total = 0;


if (isset($_POST["tags1"])) {

   $date = date("d-m-y");
   $lastaccess = date("y-m-d");
   $ip= $_SERVER['REMOTE_ADDR'];

   //CHECK IF THE IP OF THE PERSON IS BLOCKED OR NOT
   $result = mysql_query("SELECT id FROM `blockedip` WHERE ip = '$ip'");
   $number = mysql_num_rows($result);
   if ($number) die("Sorry ! Your ip is blocked from uploading any image. <br><br><a href='index.php'>Go back to homepage</a>");


   for ($i=1; $i < ($max+1); $i++)
   {  
      if (trim($_FILES["image" . $i]["name"]) != "")  {
           
           $total = $total + 1;
           if ( (trim($_POST["tags" . $i]) != "") ) {
         
                $tags = htmlspecialchars(trim($_POST["tags" . $i]));
                
                $name = "image" . $i;

                //CHECK IF VALID IMAGE TYPE
                if (( ($_FILES[$name]["type"] == "image/gif")
                   || ($_FILES[$name]["type"] == "image/jpeg")
                   || ($_FILES[$name]["type"] == "image/pjpeg")
                   || ($_FILES[$name]["type"] == "image/x-png")
                   || ($_FILES[$name]["type"] == "image/bmp")
                   || ($_FILES[$name]["type"] == "image/png")))
                {

                $size = intval(($_FILES[$name]["size"] / 1024) / 1024);
                 
                 if ($session == true) 
                    $limit = $maxsizemember;
                 else
                    $limit = $maxsizeguest;  
 
                   if ($size > $limit)
                       die ("Sorry ! The size of the image exceeds the $limit Mb limit.");


                   if ($_FILES[$name]["error"] > 0)  {
                       die("Error: " . $_FILES[$name]["error"]);
                   }
                   else {
                       $n = $_FILES[$name]["name"];
                       $rndName = md5($n . date("d-m-y") . time()) . "." . findExtension($n);
                       $uploadPath = "pictures/" . $rndName;
                       $tempPath = $_FILES[$name]["tmp_name"];
                       move_uploaded_file($tempPath, $uploadPath);
                   }

                }
                else
                   die("Sorry ! \"{$_FILES[$name]["name"]}\" is an invalid image.");


                $imagePath = $uploadPath;
      
                //********************************************************************************************************

                $img = imagecreatefromunknown($imagePath);
                                  
                $mainWidth = imagesx($img);
                $mainHeight = imagesy($img);
              
                if (($mainWidth > 150) && ($mainWidth < 2000) && ($mainHeight < 1600)) 
                { 
                     
                 $a = ($mainWidth >= $mainHeight) ? $mainWidth : $mainHeight; 
    
                 $div = $a / 150;
                 $thumbWidth = intval($mainWidth / $div);
                 $thumbHeight = intval($mainHeight / $div);


                 $myThumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
                 imagecopyresampled($myThumb, $img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $mainWidth, $mainHeight);
                 $thumbPath = "thumbnails/" . basename($imagePath);
                 imagejpeg($myThumb, $thumbPath);
                 
                 

                 //********************************************************************************************************
                 
                 if (($type == "public") && ($watermark == "true")) {
                     $imgMark = imagecreatefromgif("watermark.gif");

                     $dX = $mainWidth - imagesx($imgMark);
                     $dY = $mainHeight - imagesy($imgMark);
                     imagecopymerge($img, $imgMark, $dX, $dY, 0, 0, imagesx($imgMark), imagesy($imgMark), 40); 
                 
                     $ext = findExtension($imagePath);
    
                     switch ($ext) {
                       case "jpg":
                          imagejpeg($img, $imagePath);  break;
                       case "png":
                          imagepng($img, $imagePath);   break;
                     }
                 }
                 
                 //******************************************************************************************************
    
                 $details = intval(filesize($imagePath) / 1024) . " kb (" . $mainWidth . " x " . $mainHeight . ")" ; 
                 $id = md5($thumbPath . date("d-m-y") . time());                  

                 //#########################################################################################################

                 if ($session == false) 
                    $q = "INSERT INTO `images`(id, image, thumb, tags, details, date, access, type, ip)
                          VALUES('$id', '$imagePath', '$thumbPath', '$tags', '$details', '$date', '$lastaccess', 'public', '$ip')";
                 else 
                 {
                    if ($opt == "gallery") 
                        $q = "INSERT INTO `images`(id, galleryid, image, thumb, tags, details, date, access, type, ip) 
                             VALUES('$id', '$galleryid', '$imagePath', '$thumbPath', '$tags', '$details', '$date', '$lastaccess', 'gallery', '$ip')";
                    else 
                        $q = "INSERT INTO `images`(id, userid, image, thumb, tags, details, date, access, type, ip)
                             VALUES('$id', '$loggedId', '$imagePath', '$thumbPath', '$tags', '$details', '$date', '$lastaccess', 'member-{$type}', '$ip')";
                 }  
                 
                 if(!($result_set = mysql_query($q))) die(mysql_error());  
                     
                 echo "<center><a href=\"show-image.php?id=$id\"><img src='thumb.php?id=$id'></a></center><br>";
                 echo "Image \"{$_FILES["image" . $i]["name"]}\" uploaded successfully. <br><br>";
                 

                 echo "<LABEL id='title'>HTML:</LABEL><br><input type='text' size=92 onclick=\"this.select();\" value=\"<a href='{$website}/show-image.php?id=$id'> <img src='{$website}/{$thumbPath}' alt='Image Hosting' border='0'> </a>\">";
                 echo "<br><br>";
    
 
                 echo "<LABEL id='title'>BB Code:</LABEL><br><input type='text' size=92 onclick=\"this.select();\" value=\"[URL={$website}/show-image.php?id={$id}] [IMG]{$website}/{$thumbPath}[/IMG][/URL]\">";
                 echo "<br><br>";


                 echo "<LABEL id='title'>Direct Image Link (HTML):</LABEL><br><input type='text' size=92 onclick=\"this.select();\" value=\"<a href='{$website}'> <img src='{$website}/{$imagePath}'> </a>\">";
                 echo "<br><br>";


                 echo "<LABEL id='title'>Direct Image Link (BB Code):</LABEL><br><input type='text' size=92 onclick=\"this.select();\" value=\"[URL={$website}] [IMG]{$website}/{$imagePath}[/IMG][/URL]\">";
                 echo "<br><br>";
 
                 echo "<LABEL id='title'>URL:</LABEL><br><input type='text' size=92 onclick=\"this.select();\" value=\"{$website}/show-image.php?id=$id\">";
  
                 echo "<br><br><hr color='#233c9b'><br>";                      
 

                 }
                 else
                    echo "Sorry ! Image \"{$_FILES["image" . $i]["name"]}\" is either too small or too large.<br><hr color='#b1ddf6'>";   

             }
             else
                    echo "You have not entered any tags for the image \"{$_FILES["image" . $i]["name"]}\" <br><hr color='#b1ddf6'>";  
       }
   }

}

if ($total == 0)
  echo "Sorry ! You must upload atleast one image.";


?>

 <!-- ############################################################################################### -->


<?php  include("footer.php"); ?>

</div>
</center>
</body>
</html>


  
