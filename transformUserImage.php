<?php
$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user($user_id);
$new_image_id = $user->copyImage($image_id);
$user->resizeImage($new_image_id);
echo $user->getImageSource($new_image_id);
?>