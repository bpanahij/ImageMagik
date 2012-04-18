<?php
$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
$macroName = $_GET['macroName'];
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user($user_id);
$user->executeMacro($macroName, $image_id);
echo $user->getThumbnailImage($image_id);
?>