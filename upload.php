<?php
include "usersClass.php";
define ("MAX_SIZE","5000");

function getExtension($str) {         
  $i = strrpos($str,".");         
  if (!$i) { return ""; }         
  $l = strlen($str) - $i;         
  $ext = substr($str,$i+1,$l);         
  return $ext; 
}
$success = "DODO";
$user_id = $_POST['user_id'];
$errors=0;
if(isset($_POST['Submit'])) { 		
  $image = $_FILES['userfile']['name']; 	 
  if($image) { 	 		
    $filename = stripslashes($_FILES['userfile']['name']); 	 		
    $extension = getExtension($filename); 		
    $extension = strtolower($extension); 	
    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
      echo '<h1>Unknown extension!</h1>';
      echo $extension; 		
    } 		
    else {
      $size=filesize($_FILES['userfile']['tmp_name']);
      if ($size > MAX_SIZE*1024){	
	echo '<h1>You have exceeded the size limit!</h1>';	
      }
      $realImage = new Imagick();
      $realImage->readImage($_FILES['userfile']['tmp_name']);
      $height = $realImage->getImageHeight();
      $width = $realImage->getImageWidth();
      $user = new user($user_id);
      $filename = $user->newImage($folder_id, $extension, $height, $width, $size);
      $success = move_uploaded_file($_FILES['userfile']['tmp_name'], $filename);
      if($success) $success = "YAY";
      else $success = "POO";
    }
  }
 }
?>
<script language="javascript" type="text/javascript"></script> 
