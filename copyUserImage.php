<?php
$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user($user_id);
$new_image_id = $user->copyImage($image_id);
echo $user->getThumbnailImage($new_image_id);
?>