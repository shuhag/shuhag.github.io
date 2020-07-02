<?php

if (isset($_SESSION["imagehost-admin"])) {
  
  $password1 = $_SESSION["imagehost-admin"];
  if ($password != $password1) {
    session_destroy();
    die("You must login first, in order to view the admin panel");
  }

}
else
  die("You must login first, in order to view the admin panel");


?>
  