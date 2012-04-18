<?php
$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
$height = $_GET['height'];
$width = $_GET['width'];
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user($user_id);
$user->resizeImage($height, $width, $image_id);
echo $user->getFullImage($image_id);
?>