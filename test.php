<?php

include "userClass.php";
$user = new user("");
$user->loginUser("brian","panahi");
$user->changeEmail("bpj@gmail.com");
$user->folders->createFolder("myimages");

?>