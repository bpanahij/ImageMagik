<?php
$im = new Imagick();
$command = $_POST["command"];
$im->newPseudoImage(100, 100, "pattern:checkerboard");
$im->setImageFormat('jpg');
$im->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
$im->setImageMatte(true);
header("Content-Type: image/jpg");
echo $im;
?>