<?php
header("Content-Type: image/jpeg");
include "usersClass.php";

$user_id = $_GET['user_id'];
$image_id = $_GET['image_id'];
$function = $_GET['function'];
$params = $_GET['param'];
$user = new user($user_id);

if(count($params) == 0) $user->$function($image_id);
if(count($params) == 1) $user->$function($params[0], $image_id);
if(count($params) == 2) $user->$function($params[0], $params[1], $image_id);
if(count($params) == 3) $user->$function($params[0], $params[1], $params[2], $image_id);
if(count($params) == 4) $user->$function($params[0], $params[1], $params[2], $params[3], $image_id);
if(count($params) == 5) $user->$function($params[0], $params[1], $params[2], $params[3], $params[4], $image_id);
if(count($params) == 6) $user->$function($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $image_id);
echo $user->getFullImage($image_id);
?>