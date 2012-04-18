<?php
include "usersClass.php";
$user_id = $_GET["user_id"];
$user = new user($user_id);
if($user->endMacro()) {
  echo TRUE;
 }
 else {
   echo FALSE;
 }
?>
