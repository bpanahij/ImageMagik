<?php
include "usersClass.php";
$user_id = $_GET["user_id"];
$macroName = $_GET["macro_id"];
$newMacroName = $_GET["newMacroName"];
//$newMacroName = str_replace(" ","_",$newMacroName);
$user = new user($user_id);
if($user->renameMacro($macroName, $newMacroName)) {
  echo "TRUE";
 }
 else {
   $user->createXMLResponse();
 }
?>
