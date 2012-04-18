<?php
include "usersClass.php";
$user_id = $_GET["user_id"];
$macroName = $_GET["macro_id"];
$user = new user($user_id);
if($user->deleteMacro($macroName)) {
  echo "TRUE";
 }
 else {
   $user->createXMLResponse();
 }
?>
