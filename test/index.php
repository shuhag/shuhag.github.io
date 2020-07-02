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

<meta name="description" content="<? echo $description; ?>" />
<meta name="keywords" content="<? echo $keywords; ?>" />


<title><? echo $webtitle; ?> - Free Image Hosting</title>
<link rel="stylesheet" href="style.css" type="text/css" />

<script language="JavaScript">
var x = 1;

function addMore() {
    x = x + 1;
    if (x > 5) 
       alert("Sorry ! You cannot upload more than 5 images at a time.");
    else 
       document.getElementById("upload" + x).style.display = "block";
}
 </script>

</head>


<body link=#336699 vlink=#336699 alink=#336699>
<?php include("header.php"); ?>

<center>
<div class="content-container">
   

<form method=\"GET\" action='images.php'>
 <table width=100% bgcolor='#edf7fd' style=\"BORDER: #b1ddf6 2px solid;\">
 <tr height=40><td align=left width=400>
   &nbsp; &nbsp; <LABEL id='message'>Search: </LABEL>&nbsp;<input type='text' name='query' size=30 maxlength='100'>
   &nbsp; &nbsp; <input type='submit' value='Search'>
 </td>
 
 <td>
    <LABEL id='title'>Search By: </LABEL>&nbsp; &nbsp;
    <input type='radio' name='opt' value='tags' CHECKED> &nbsp; Tags
    <input type='radio' name='opt' value='gallery'> &nbsp; Gallery 
 </td>
 
 <td>
    <a href="search.php">Advanced Search</a>
 </td>
 

</tr></table>
</form>


 <div style="display: none" id="loading" align=center>
    <img src='images/loading.gif' border=0><br><br>
 </div>

   <div class="content-box">
     <table> <tr>
      
     <td width=350 valign=top>
      <h1>Features:</h1> 
      <ul>
        <li>Upload multiple pictures at one time
        <li>Create public and private galleries
        <li>Post comments on the images
        <li>Add any image to your favourites
        <li>Our dedicated servers host your images 
        <li>Link your photos in websites, email, blogs 
        <li>Register an account to manage your files 
        <li>Add tags to each of your photos 
        <li>Upload private images with password
        <li>Share your images with friends and family
       </ul>
     </td>
         

     <td>


<div class="left">
          
<h1>Upload Image</h1>

<form method="POST" action="process.php" enctype="multipart/form-data" name="myForm">
<table style="border-collapse: collapse" width=300>

<tr>
 <td>
   <table id="upload1">
     <tr>
     <td><LABEL id="title">Image:</LABEL></td> <td> <input type="file" name="image1" size=40></td>
   </tr>
   <tr>
     <td><LABEL id="title">Tags:</LABEL></td> <td><input type="text" name="tags1" size=40 maxlength="200"></td>
   </tr>
   </table> 

   <table  style="BORDER: #999 1px dotted; MARGIN-TOP: 5px; MARGIN-BOTTOM: 5px; display: none" id="upload2">
   <tr>
     <td><LABEL id="title">Image:</LABEL></td> <td><input type="file" name="image2" size=40></td>
   </tr>
   <tr>
     <td><LABEL id="title">Tags:</LABEL></td> <td><input type="text" name="tags2" size=40 maxlength="200"></td>
   </tr>
  </table> 
  
   <table id="upload3" style="display: none">
   <tr>
     <td><LABEL id="title">Image:</LABEL></td> <td><input type="file" name="image3" size=40></td>
   </tr>
   <tr>
     <td><LABEL id="title">Tags:</LABEL></td> <td><input type="text" name="tags3" size=40 maxlength="200"></td>
   </tr>
  </table> 

   <table style="BORDER: #999 1px dotted; MARGIN-TOP: 5px; MARGIN-BOTTOM: 5px; display: none" id="upload4">
   <tr>
     <td><LABEL id="title">Image:</LABEL></td> <td height="18"><input type="file" name="image4" size=40></td>
   </tr>
   <tr>
     <td><LABEL id="title">Tags:</LABEL></td> <td height="22"><input type="text" name="tags4" size=40 maxlength="200"></td>
   </tr>
  </table> 
   
   <table id="upload5" style="display: none">
   <tr>
     <td><LABEL id="title">Image:</LABEL></td> <td><input type="file" name="image5" size=40></td>
   </tr>
   <tr>
     <td><LABEL id="title">Tags:</LABEL></td> <td><input type="text" name="tags5" size=40 maxlength="200"></td>
   </tr>
  </table>

 <!-- ############################################################################################ -->

 <?php
 //**************************************************************** 
   if ($session == true)
   {  
        echo "<br><table> <tr height=30><td><h2>Options: </h2></td></tr>";
        $result_set = mysql_query("SELECT id, name FROM `galleries` WHERE userid = '$userid'");
        $number = mysql_num_rows($result_set);

        if ($number)
        {
             echo "<tr><td>
                   <input type='radio' name='opt' value='gallery'>
                   <font size=2>add to gallery:</font></td>";
             echo "<td><select name='galleryid'>";

             while ($row = mysql_fetch_array($result_set))
                echo "<option value={$row['id']}>{$row['name']}</option>";

             echo "</select></td></tr>";
       }

       echo "<tr><td><input type='radio' name='opt' value='single' CHECKED> <font size=2> single image </font></td>
             <td> <font size=2>(keep private <input type='checkbox' name='private' value='ON'>)</font></td></tr>";

       echo "</table>";
   }
 //*****************************************************************
?>


  <table><tr>
   <td>
    <br><a href="#" onclick="addMore()">Upload More</a>
  </td></tr>

  <tr><td>
   <LABEL id="text">Separate tags by commas. (,) </LABEL>
  </td></tr>

  <tr><td>
    
  </td></tr>

  <tr><td>
    <br><a href=#><img src="images/upload.png" border=0 
                  onclick="myForm.submit(); getElementById('loading').style.display='block';"></a>
  </td></tr>
 
  </table>  


</td>
</tr>
</table>
</form>
<!-- </div> -->



      
     </td>
     </tr></table>
   </div>

    
   <br>
   <h2>Random Images:</h2>
   <? include("random.inc.php"); ?>   

    

   <div class="about">
      <? echo $webtitle; ?>™ is an image hosting and sharing service that lets you to create galleries; easily upload, link,
      and share your images on MySpace®, eBay®, Orkut Scrapbooks, blogs, and message boards.
      <br><br><br><img src="images/share.png">
   </div>


   <?php  include("footer.php"); ?>


</div>
</center>


</body>


</html>