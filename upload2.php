<?php

define ("MAX_SIZE","5000");

 
function getExtension($str) {         
  $i = strrpos($str,".");         
  if (!$i) { return ""; }         
  $l = strlen($str) - $i;         
  $ext = substr($str,$i+1,$l);         
  return $ext; 
}
 
$errors=0;
if(isset($_POST['Submit'])) { 		
  $image=$_FILES['userfile']['name']; 	 
  if ($image)  	{ 	 		
    $filename = stripslashes($_FILES['userfile']['name']); 	 		
    $extension = getExtension($filename); 		
    $extension = strtolower($extension); 	
    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {			
      echo '<h1>Unknown extension!</h1>';
      echo $extension;
      $errors=1; 		
    } 		
    else {
      $size=filesize($_FILES['userfile']['tmp_name']);
      if ($size > MAX_SIZE*1024){	
	echo '<h1>You have exceeded the size limit!</h1>';	
	$errors=1;
      }
      $file_contents = file_get_contents($_FILES['userfile']['tmp_name']);
      $encoded_file = base64_encode($file_contents);
      $image=base64_decode($encoded_file);

      $im = new Imagick();
      $im->readimageblob($image);
      $im->thumbnailImage(200,82,true);
      $color=new ImagickPixel();
      $color->setColor("rgb(220,220,220)");
      $im->borderImage($color,1,1);
      $output = $im->getimageblob();
      $outputtype = $im->getFormat();
      header("Content-type: $outputtype");
      echo "<img src='".$output."'>";
    }
  }
 }
?>