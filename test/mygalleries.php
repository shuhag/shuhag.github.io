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

<script language="JavaScript">
function deleteGallery(id) { 
  x = confirm("Are you sure that you want to delete this gallery ?"); 
  if (x) 
    window.location.href = "mygalleries.php?act=deletegallery&id=" + id ;
}
</script>



</head>


<body link=#336699 vlink=#336699 alink=#336699>
<?php include("header.php"); ?>

<center>
<div class="content-container">


<!-- ######################################################################################### -->


<?php



if ($session == true)
{    
    
    $show = true;    


        //RENAME GALLERY
        if (isset($_POST["changename"])) {
       
            $show = false;
            $id = $_POST["id"];
            $name = $_POST["name"];

            if ($name != "") {
                  if ($id != "") {
                      if(mysql_query("UPDATE `galleries` SET name='$name' WHERE (id='$id') AND (userid='$userid')")) {
                          echo "The name of the gallery was changed !";
                          echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
                      }
                      else
                          echo "Sorry ! The name of the gallery could not changed due to some reason.";
                  }
            }
            else {
                  echo "Please enter a name of the gallery first !"; 
                  echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
            }
       }




       //CHANGE IMAGE TAGS IN A GALLERY
       if (isset($_POST["changetags"])) {
       
          $show = false;
          $id = $_POST["id"];
          $tags = $_POST["tags"];

          if ($tags != "") {
              if ($id != "") {
         
                $result = mysql_query("SELECT galleryid FROM `images` WHERE id = '$id'");
                $n = mysql_num_rows($result);
                if (!$n) die();
                $row = mysql_fetch_array($result);
                $galleryid = $row['galleryid'];
             
                $result = mysql_query("SELECT id FROM `galleries` WHERE (id = '$galleryid') AND (userid = '$userid')");
                $n = mysql_num_rows($result);
                if (!$n) die();
 
                if(mysql_query("UPDATE `images` SET tags='$tags' WHERE id='$id'")) {
                   echo "The tags of the image were changed !";
                   echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
                }
                else
                   echo "Sorry ! The tags of the image could not changed due to some unknown reason.";
             }
          }
          else {
              echo "Please enter some tags first !"; 
             echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
          }
       }

         
      

       //DELETE SELECTED IMAGES
       if (isset($_POST["deleteselected"])) {
       
          $show = false;
          $galleryid = $_POST["galleryid"];
     
          if (isset($_POST["images"])) {
               $images = $_POST["images"];               

               while (list($index, $id) = each($images))  {
                  $imageid = $id;
                  $result = mysql_query("SELECT galleryid FROM `images` WHERE id = '$imageid'");
                  $n = mysql_num_rows($result);
                  if (!$n) die();
                  $row = mysql_fetch_array($result);
                  $galleryid = $row['galleryid'];
             
                  $result = mysql_query("SELECT id FROM `galleries` WHERE (id = '$galleryid') AND (userid = '$userid')");
                  $n = mysql_num_rows($result);
                  if (!$n) die();      

                  deleteImage($id);                  

               }
                     
               echo "The images were deleted successfully !";
               echo "<br><br><a href=\"mygalleries.php?act=show&id=$galleryid\">Click here</a> to go back";
          }
          else {
               echo "Please select some images to delete first !";
               echo "<br><br><a href=\"mygalleries.php?act=show&id=$galleryid\">Click here</a> to go back";
          }
      
       }
       



   //MOVE SELECTED IMAGES TO 'MY IMAGES'
   if (isset($_POST["moveselectedimages"])) {
       
     $show = false;
     $galleryid = $_POST["galleryid"]; 
     
     if (isset($_POST["images"])) {
        $images = $_POST["images"];      

        while (list($index, $id) = each($images)) {
           $imageid = $id;
            
           //CONFIRM THAT THE IMAGE IS OF THIS USER (ELSE DIE) ***********
           $result = mysql_query("SELECT galleryid FROM `images` WHERE id = '$imageid'");
           $n = mysql_num_rows($result);
           if (!$n) die();
           $row = mysql_fetch_array($result);
           $galleryid = $row['galleryid'];
             
           $result = mysql_query("SELECT id FROM `galleries` WHERE (id = '$galleryid') AND (userid = '$userid')");
           $n = mysql_num_rows($result);
           if (!$n) die();      
           //**************************************************************

           mysql_query("UPDATE `images` SET galleryid = '-1', userid = '$userid', type = 'member-public' WHERE (id = '$id')");
           
        }
        echo "The images were moved successfully !";
        echo "<br><br><a href=\"mygalleries.php?act=show&id=$galleryid\">Click here</a> to go back";
     }
     else {
        echo "Please select some images to move first !";
        echo "<br><br><a href=\"mygalleries.php?act=show&id=$galleryid\">Click here</a> to go back";
     }
      
   }


  

   //MOVE SELECTED IMAGES TO ANOTHER GALLERY
   if (isset($_POST["moveselectedgallery"])) {
       
     $show = false;
     
     if (isset($_POST["images"])) {
        $images = $_POST["images"];
        $galleryid = $_POST["galleryid"];
      
        while (list($index, $id) = each($images)) {
           $r = mysql_query("UPDATE `images` SET galleryid = '$galleryid' WHERE id='$id'");
        }
        echo "The images were moved successfully !";
        echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
     }
     else {
        echo "Please select some images to move first !";
        echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
     }
      
   }
    


   
   //CREATE A NEW GALLERY 
       if (isset($_GET["name"])) {
            
            $show = false;
            if ($_GET['name'] != "") {
               if (($_GET['type'] == "private") OR ($_GET['type'] == "public")) {
                 $type = $_GET["type"];
                 $name = $_GET["name"];
                 $id = md5($name . $type . date("d-m-y") . time()); 

                 if (mysql_query("INSERT INTO `galleries` (id, userid, name, type) VALUES('$id', '$userid', '$name', '$type')"))
                    echo "Gallery created successfully !";
                 else
                    echo "Sorry ! The gallery could not be created due to some reason.";
               }
               else echo "You have specified an invalid category.";
                  
            }
            else
                echo "Please enter a name for the gallery !";

            echo "<br><br>Go back to <a href='mygalleries.php'>'Galleries'</a>";
            echo "<meta http-equiv=\"refresh\" content=\"4; url='mygalleries.php'\" />";
      }


      //**************************************************************************************************


      if (isset($_GET["act"])) {
      
         $act = $_GET["act"]; 
         if (isset($_GET["id"]))
              $id = trim($_GET["id"]);
         else
              die();

 
          //DELETE A GALLERY
          if ($act == "deletegallery") {
             
             $result = mysql_query("SELECT id FROM `galleries` WHERE (id = '$id') AND (userid = '$userid')");
             $n = mysql_num_rows($result);
             if (!$n) die("Sorry ! The gallery could not be deleted due to some reason.");
             
             if (mysql_query("DELETE FROM `galleries` WHERE (id = '$id') AND (userid = '$userid')"))
               echo "Gallery along with all its images deleted successfully !";
             else
               echo "The gallery could not be deleted due to some reason !";

             mysql_query("DELETE FROM `images` WHERE galleryid = '$id'");

             echo "<meta http-equiv=\"refresh\" content=\"4; url='mygalleries.php'\" />";
          }


          //SHOWS IMAGES WHITHIN A GALLERY
          if ($act == "show") {
             
             $galleryid = $id;
             $result = mysql_query("SELECT name, type FROM `galleries` WHERE (id = '$galleryid') AND (userid = '$userid')");
             $t = mysql_num_rows($result);
             if (!$t) die("Sorry ! Invalid gallery specified.");
             $row = mysql_fetch_array($result);
             $name = $row['name'];
             $type = $row['type'];

             $result_set = mysql_query("SELECT * FROM `images` WHERE galleryid = '$galleryid' ORDER BY number DESC");
             $n = mysql_num_rows($result_set);

             if ($n) {

                echo "<center>";
                echo "<br><h1>Gallery '$name' images ($type)</h1><br>";
                echo "<b>Gallery link:</b> {$website}/gallery.php?id=$galleryid <br>";
                echo "This gallery currently contains '$n' images<br><br><br>";
                
                echo "<form method='POST' action='mygalleries.php'>";
                echo "<input type='submit' name='deleteselected' value='Delete Selected Images'>";
                echo "&nbsp;&nbsp; <input type='submit' name='moveselectedimages' value=\"Move selected to 'My Images'\">";
                echo "<input type='hidden' name='galleryid' value='$galleryid'>";
                
                $result_set1 = mysql_query("SELECT id, name FROM `galleries` WHERE userid = '$userid'");
                $nn = mysql_num_rows($result_set1);

                 if ($nn) {
                  
                    echo "<br><br>";
                    echo "<LABEL id='message'>Move selected images to gallery:</LABEL></td>
                            <select name='galleryid'>";

                     while ($row = mysql_fetch_array($result_set1))
                          echo "<option value={$row['id']}>{$row['name']}</option>";

                     echo "</select>&nbsp;<input type='submit' name='moveselectedgallery' value='Move'>";
                 }


                echo "<br><br><table border=2 bordercolor=\"#b1ddf6\" style=\"border-collapse: collapse; FONT-SIZE: 16px\" width=800>";
                echo "<tr height=40 bgcolor=\"#F0F8FF\">
                      <td>&nbsp;</td>
                      <td><center><b>Images</b></center></td>";
                echo "<td><center><b>Details</b></center></td>";
                echo "<td><center><b>Views</b></center></td>";
                echo "<td><center><b>Date</b></center></td>";
                echo "<td><center><b>Action</b></center></td>";
                echo "</tr>";

                while ($row = mysql_fetch_array($result_set))
                {
                  echo "<tr>";
                  $id = $row['id'];
                  echo "<td align=center> <input type='checkbox' name='images[]' value='$id'>";
                  echo "<td align=center> <a href=\"show-image.php?id=$id\"><img src=\"thumb.php?id=$id\"></a> </td>";
                  echo "<td width=180 align=center> {$row['details']} </td>";
                  echo "<td align=center> {$row['views']}</td>";
                  echo "<td align=center> {$row['date']} </td>";
                  
                  echo "<td align=center> <a href=\"mygalleries.php?id=$id&act=changetags\">Edit Tags</a>
                        &nbsp; | &nbsp; <a href=\"mygalleries.php?act=deleteimage&id=$id\">Delete</a>
                        </td>";

                  echo "</tr>";
                }

                echo "</table></form>";
                
              }
              else
                echo "<center>No images have been uploaded in this gallery yet !</center>";

              echo "<br><br><center><a href='index.php'><img src='images/upload.png' border=0></a></center><br>";
 
          }



         //DELETE IMAGE WITHIN A GALLERY
         if ($act == "deleteimage") {
        
             $imageid = $id;

             $result = mysql_query("SELECT galleryid FROM `images` WHERE id = '$imageid'");
             $n = mysql_num_rows($result);
             if (!$n) die();
             $row = mysql_fetch_array($result);
             $galleryid = $row['galleryid'];
             
             $result = mysql_query("SELECT id FROM `galleries` WHERE (id = '$galleryid') AND (userid = '$userid')");
             $n = mysql_num_rows($result);
             if (!$n) die();
             
             deleteImage($id);
             echo "Image deleted successfully !";

         }
         


          //MAKE GALLERY PRIVATE  
          if ($act == "makeprivate") {
         
              if(mysql_query("UPDATE `galleries` SET type='private' WHERE (id='$id') AND (userid='$userid')")) {
                  echo "The gallery was made private !";
                  echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
              }
              else
                  echo "Sorry ! The gallery could not be changed to private due to some reason.";
          }



          //MAKE GALLERY PUBLIC
          if ($act == "makepublic") {
        
             if(mysql_query("UPDATE `galleries` SET type='public' WHERE (id='$id') AND (userid='$userid')")) {
                  echo "The gallery was made public !";
                  echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
             }
             else
                  echo "Sorry ! The gallery could not be changed to public due to some reason.";
          }

        

          //RENAME GALLERY (form)  
          if ($act == "changename") {
          
               echo "<form method='POST' action='mygalleries.php'>";
               echo "<br>Enter a new name for the gallery:<br><input type='text' name='name' size=40 maxlength=250>";
               echo "&nbsp; &nbsp; <input type='submit' value='Change' name='changename'>";
               echo "<input type='hidden' name='id' value='$id'></form>";
               echo "<br><br><a href=\"mygalleries.php\">Click here</a> to go back";
          }



          //CHANGE IMAGE TAGS (form)
          if ($act == "changetags") {
        
             echo "<form method='POST' action='mygalleries.php'>";
             echo "<br>Enter new tags for the image:<br><input type='text' name='tags' size=40 maxlength=250>";
             echo "&nbsp; &nbsp; <input type='submit' value='Change' name='changetags'>";
             echo "<input type='hidden' name='id' value='$id'></form>";
          }


          echo "<br><br><center><a href='mygalleries.php'>Go back to My Galleries</a></center>";    

          //************************************************************************************************
          
     }
     else {  //GALERIES MAIN PAGE !
       
         if ($show == true) {

            $q = "SELECT * FROM `galleries` WHERE userid = '$userid' ORDER BY id DESC";
            $result_set = mysql_query($q);
            $n = mysql_num_rows($result_set);

            echo "<br><center><h1>My Galleries</center></h1><br>"; 

            if ($n) {

                 echo "<center>";
                 echo "<br><a href='index.php'><img src='images/upload.png' border=0></a><br><br><br>";
                 echo "<table style=\"FONT-SIZE: 16px\">";
                 
                 echo "<tr height=30>";
 
                 $x = -1;
                 while ($row = mysql_fetch_array($result_set))
                 {
                       $x++;
                       if (($x % 4) == 0) echo "</tr><tr>";
                      
                       echo "<td align=center width=400 valign=top>";
                       showGalleryImage($row['id']);
                    
                       echo "<br><br> {$row['name']} <b>({$row['type']}) </b>";
                       echo "<br><br> <a href=\"mygalleries.php?act=show&id={$row['id']}\">Manage</a> 
                              | <a href=\"mygalleries.php?act=changename&id={$row['id']}\">Rename</a>
                              | <a href='#' onclick=\"deleteGallery('{$row['id']}');\">Delete</a> 
                              <br>";
                     
                       if ($row['type'] == "public")  
                          echo "<a href=\"mygalleries.php?id={$row['id']}&act=makeprivate\">Make Private</a>";
                       else
                          echo "<a href=\"mygalleries.php?id={$row['id']}&act=makepublic\">Make Public</a>";
                      
                       echo "</td>";
                  }
                         
                  echo "</table></center><br><hr color=#b1ddf6>";
                  echo "<center><br><br><b>Note:</b> Click on 'Manage' to manage images within a gallery !<br><br>";
           }
           else
                  echo "<center><b>No gallery has been created yet !</b><center><br>";


           echo "<br><br><h2>Create Gallery</h2><br>";
           echo "<form action='mygalleries.php' method='GET' name='myForm1'>"; 
           echo "<table><tr><td width=100><LABEL id='message'>Name: </LABEL></td> <td><input type=text name='name' maxlength=250></td></tr>";
           echo "<tr><td><LABEL id='message'>Type: </LABEL></td>
                 <td>
                    <select name=type>
                            <option value='public'>Public</option>
                            <option value='private'>Private</option>
                    </select>
                 </td></tr>
                 <tr> <td> 
                    <br><br></td> <td><br> <a href=#><img src='images/create.png' border=0 onclick='myForm1.submit();'></a>
                  </td> </tr></table></form></center>";

           echo "<br><br><center><a href='account.php'>Go back to My Account</a></center>";    

           //******************************************************************************************
       }   
    }

}
else
  echo "You must sign-in first in order to view your account.<br><a href=\"login.php\">Click here</a> to login.";




//SHOWS THUMBNAIL OF THE LAST IMAGE IN THE GALLERY.
function showGalleryImage($id) {

$r = mysql_query("SELECT id FROM `images` WHERE galleryid = '$id' ORDER BY number DESC LIMIT 1");

if (!mysql_num_rows($r))
  echo "<a href='gallery.php?id=$id'><img src='gallery.gif' border=1></a>";
else {  
  $row = mysql_fetch_array($r);
  $imageid = $row['id'];
  echo "<a href='gallery.php?id=$id' target='_blank'><img src='thumb.php?id=$imageid' border=0></a>";
}

}



function deleteImage($id) {

$result = mysql_query("SELECT image, thumb FROM `images` WHERE id='$id'");
$number = mysql_num_rows($result);
if (!$number) die("Sorry ! The image you specified does not exists.");

$row = mysql_fetch_array($result);
$image = $row['image'];
$thumb = $row['thumb'];

unlink($image);
unlink($thumb);

mysql_query("DELETE FROM `images` WHERE id='$id'");

}




?>


       
<!-- ######################################################################################### -->          
<?php  include("footer.php"); ?>


</div>
</center>
</body>
</html>





