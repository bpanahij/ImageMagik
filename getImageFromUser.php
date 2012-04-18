<?php
$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user($user_id);
$thumbnail = new Imagick();
$thumbnail->readImageBlob($user->getImageSource($image_id));
$thumbnail->scaleImage(200, 170);
echo $thumbnail->getImageBlob();
?>