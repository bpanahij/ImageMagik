<?php
$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
$roundX = $_GET['roundX'];
$roundY = $_GET['roundY'];
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user($user_id);
$user->roundCorners($roundX, $roundY, $image_id);
echo $user->getFullImage($image_id);
?>