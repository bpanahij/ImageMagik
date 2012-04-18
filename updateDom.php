<?php
include "usersClass.php";
$user_id = $_GET["user_id"];
if($user = new user($user_id)) {
  $user->createXMLResponse();
 }
 else echo "user object not returned";
?>