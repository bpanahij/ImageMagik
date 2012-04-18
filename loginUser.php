<?php
include "usersClass.php";
$user_id = $_GET["user_id"];
if(!$user_id) {
  $user = new user();
  $username = $_GET["username"];
  $password = $_GET["password"];
  if(!$user->loginUser($username, $password)){
    $user->createUser($username, $password);
  }
}
 else {
   $user = new user($user_id);
 }
$user->createXMLResponse();
?>