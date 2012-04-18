<?php
$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
$text = $_GET['text'];
$font = "";//$_GET['font'];
$font_size = $_GET['font_size'];
$text_color = $_GET['text_color'];
$x = $_GET['x'];
$y = $_GET['y'];
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user($user_id);
$user->watermark($text, $font, $font_size, $text_color, $x, $y, $image_id);
echo $user->getFullImage($image_id);
?>