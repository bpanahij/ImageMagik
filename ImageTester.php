<?php
header("Content-Type: image/jpeg");
include "usersClass.php";
$user = new user(1);
$user->getImageSource(2);
?>