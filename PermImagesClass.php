<?php
require_once('db_params.php');
class userImages
{
  private $DATABASE = DBNAME;
  private $DBUSER = DBUSER;
  private $DBPASS = DBPASS;
  private $HOST = HOST;
  protected $images;
  protected $user_id;
  protected $errors;
  
  function __construct($user_id = NULL) {
    $this->images = array();
    $this->user_id = NULL;
    $this->errors = array();
    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
    mysql_query("USE ".$this->DATABASE, $connection);
    $query = "CREATE TABLE IF NOT EXISTS images(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
                user_id INT NOT NULL,
		folder_id INT NOT NULL,
                ext VARCHAR(5) NOT NULL,
                height INT NOT NULL,
                width INT NOT NULL,
                size INT NOT NULL)
		ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";
    mysql_query($query, $connection);
    if($user_id) {
      $this->user_id = $user_id;
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "SELECT id, folder_id, ext, height, width, size FROM images WHERE user_id='".$user_id."';";
      $result = mysql_query($query, $connection);
      while($row = mysql_fetch_assoc($result)) {
	$this->images[$row["id"]] = array($row["folder_id"], $row["ext"], $row["height"], $row["width"], $row["size"]);
      }
      return $this;
      
    }
    else {
      $this->errors[] = "constructor: user_id not provided";
      return $this;
    }
  }

  function newImage($folder_id = NULL, $ext = NULL, $height = NULL, $width = NULL, $size = NULL) {
     if($this->user_id) {
       $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
       mysql_query("USE ".$this->DATABASE, $connection);
       $query = "INSERT INTO images (user_id, folder_id, ext, height, width, size) 
                              VALUES('".$this->user_id."',
                                     '".$folder_id."',
                                     '".$ext."',
                                     '".$height."',
                                     '".$width."',
                                     '".$size."')";
       mysql_query($query, $connection);
       $query = "SELECT id FROM images WHERE id=LAST_INSERT_ID()";
       $result = mysql_query($query, $connection);
       if($row = mysql_fetch_assoc($result)) {
	 $this->images[$row["id"]] = array($folder_id, $ext, $height, $width, $size);
	 return $this->getImageLocation($row["id"]);
       }
       else {
	 $this->errors[] = "Could not find new image in images table";
	 return FALSE;
       }
     }
     else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }

  function copyImage($image_id) {
    if($this->user_id) {
      if($image_id) {
	$connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	mysql_query("USE ".$this->DATABASE, $connection);
	$query = "SELECT user_id, folder_id, ext, height, width, size FROM images WHERE id='".$image_id."'";
	$result = mysql_query($query, $connection);
	if($row = mysql_fetch_assoc($result)) {
	  $query = "INSERT INTO images(user_id, folder_id, ext, height, width, size) 
                    VALUES('".$row["user_id"]."','".$row["folder_id"]."','".$row["ext"]."',
                           '".$row["height"]."','".$row["width"]."','".$row["size"]."')";
	  mysql_query($query, $connection);
	  $query2 = "SELECT id, folder_id, ext, height, width, size FROM images WHERE id=LAST_INSERT_ID()";
	  $result2 = mysql_query($query2, $connection);
	  if($row2 = mysql_fetch_assoc($result2)) {
	    $this->images[$row2["id"]] = array($row2["folder_id"], $row2["ext"], $row2["height"], $row2["width"], $row2["size"]);
	    if(copy($this->getImageLocation($image_id), $this->getImageLocation($row2["id"]))) {
	      return $row2["id"];
	    }
	    else {
	      $this->errors[] = "image could not be physically copied";
	      return FALSE;
	    }
	  }
	}
      }
      else {
	$this->errors[] = "image_id not provided";
	return FALSE;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }

  function moveImage($image_id, $folder_id) {
    if($this->user_id) {
      if($folder_id) {
	if($image_id) {
	  $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	  mysql_query("USE ".$this->DATABASE, $connection);
	  $query = "UPDATE images SET folder_id='".$folder_id."' WHERE id='".$image_id."'";
	  mysql_query($query, $connection);
	  $oldLocation = $this->getImageLocation($image_id);
	  $this->images[$image_id][0] = $folder_id;
	  if(!rename($oldLocation, getImageLocation($image_id))) {
	    $this->errors[] = "image could not be physically moved";
	    return FALSE;
	  }
	}
	else {
	  $this->errors[] = "image_id not provided";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = "folder_id not provided";
	return FALSE;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }

  function changeImage($image_id = NULL, $ext = NULL, $height = NULL, $width = NULL, $size = NULL) {
    if($this->user_id) {
      if($image_id) {
	$connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	mysql_query("USE ".$this->DATABASE, $connection);
	$query = "UPDATE images SET ext='".$ext."', height='".$height."', width='".$width."', size='".$size."' WHERE id='".$image_id."'";
	mysql_query($query, $connection);
	$this->images[$image_id][1] = $ext;
	$this->images[$image_id][2] = $height;
	$this->images[$image_id][3] = $width;
	$this->images[$image_id][4] = $size;
      }
      else {
	$this->errors[] = "image_id not provided";
	return FALSE;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }

  function deleteImage($image_id) {
    if($this->user_id) {
      if($image_id) {
	$connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	mysql_query("USE ".$this->DATABASE, $connection);
	$query = "DELETE FROM images WHERE id='".$image_id."'";
	$result = mysql_query($query, $connection);
	if(!unlink($this->getImageLocation($image_id))) {
	  $this->errors[] = "image could not be physically deleted";
	  return FALSE;
	}
	unset($this->images[$image_id]);
	return TRUE;
      }
      else {
	$this->errors[] = "image_id not provided";
	return FALSE;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }

  function getImageLocation($image_id) {
    $imagePath = "photos/".$this->user_id."/";
    if($this->images[$image_id][0]) $imagePath .= $this->images[$image_id][0]."/";
    $imagePath .= $image_id.".".$this->images[$image_id][1];
    return $imagePath;
  }

  function getFullImage($image_id) {
    $fullImage = new Imagick();
    $fullImage->readImage($this->getImageLocation($image_id));
    return $fullImage->getImageBlob();
  }

  function getThumbnailImage($image_id) {
    $thumbnail = new Imagick();
    $thumbnail->readImage($this->getImageLocation($image_id));
    $thumbnail->scaleImage(200, 170);
    return $thumbnail->getImageBlob();
  }

  function createXMLNode() {
    $doc = new domDocument();
    $doc->preserveWhiteSpace=false;
    $root = $doc->createElement("userImages");
    $imagesNode = $doc->createElement("images");
    arsort($this->images);
    foreach($this->images as $image_id => $image) {
      $imageNode = $doc->createElement("image");
      $imageNode->appendChild($doc->createTextNode($image_id));
      $imagesNode->appendChild($imageNode);
    }
    $root->appendChild($imagesNode);
    
    $errorsNode = $doc->createElement("errors");
    foreach($this->errors as $number => $error) {
      $errorNode = $doc->createElement("error");
      $errorNode->appendChild($doc->createTextNode($error));
      $errorsNode->appendChild($errorNode);
    }
    $root->appendChild($errorsNode);
    $doc->appendChild($root);
    return $root;
  }

  //These are all of the imagemagick functions
  //New functions are easily added by using the getImageSource and changeImage functions
  //---------------------------------------------------------------------------
  function resize($height = NULL, $width = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->adaptiveResizeImage(intval($width), intval($height));
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function roundCorners($x = NULL, $y = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->roundCorners(intval($x), intval($y));
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function watermark($text = '', $font_size = '12', $text_color = 'black', $x = 0, $y = 0, $image_id = NULL){
    $imagePath = $this->getImageLocation($image_id);  
    $font = 'path/to/your/yourfont.ttf';   
    $watermark  = array();  
    $image = new Imagick($imagePath);  
    $image->setImageFormat("jpg");  
    $draw = new ImagickDraw();  
    $draw->setGravity(Imagick::GRAVITY_CENTER);  
    //$draw->setFont($font);  
    $draw->setFontSize($font_size);  
    $textColor = new ImagickPixel($text_color);  
    $draw->setFillColor($textColor);  
    $im = new Imagick();  
    $properties = $im->queryFontMetrics($draw,$text);  
    $watermark['w'] = intval($properties["textWidth"] + 5);  
    $watermark['h'] = intval($properties["textHeight"] + 5);  
    $im->newImage($watermark['w'],$watermark['h'],new ImagickPixel("transparent"));  
    $im->setImageFormat("jpg");  
    $im->annotateImage($draw, 0, 0, 0, $text);  
    $watermark = $im->clone();  
    $watermark->setImageBackgroundColor($textColor);  
    $watermark->shadowImage(80, 2, 2, 2);  
    $watermark->compositeImage($im, Imagick::COMPOSITE_OVER, 0, 0);  
    $image->compositeImage($watermark, Imagick::COMPOSITE_OVER, $x, $y);
    $geometry = $image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $image->getImageSize());
    $image->writeImage($imagePath);
  }

  function shave($x = NULL, $y = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->shaveImage(intval($x), intval($y));
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function percentSize($percent = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $geometry = $new_image->getImageGeometry();
    $x = $geometry['width'] * $percent/100.0;
    $y = $geometry['height'] * $percent/100.0;
    $new_image->adaptiveResizeImage(intval($x), intval($y));
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function setCompression($percent = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->setImageFormat('jpeg');
    $new_image->setCompression(Imagick::COMPRESSION_JPEG);  
    $new_image->setCompressionQuality(intval($percent));
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, 'jpg', $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function flip($image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->flipImage();
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function flop($image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->flopImage();
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function rotate($degrees = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->rotateImage(new ImagickPixel(), $degrees);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function border($x = NULL, $y = NULL, $color = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    //$color = new ImagickPixel();
    //$color->setColor("rgb(220,220,220)");
    $new_image->borderImage($color, $x, $y);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function frame($height = NULL, $width = NULL, $inner_bevel = NULL, $outer_bevel = NULL, $color = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    //$color = new ImagickPixel();
    //$color->setColor("rgb(220,220,220)");
    $new_image->frameImage($color, $width, $height, $inner_bevel, $outer_bevel);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function modulate($brightness = NULL, $saturation = NULL, $hue = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->modulateImage($brightness, $saturation, $hue);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function sharpen($sigma = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->sharpenImage(0, $sigma);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function emboss($sigma = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->embossImage(0, $sigma);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function shade($gray = NULL, $azimuth = NULL, $elevation = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->shadeImage($gray, $azimuth, $elevation);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function oilPaint($radius = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->oilPaintImage($radius);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function charcoal($radius = NULL, $sigma = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->charcoalImage($radius, $sigma);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

  function negate($gray = FALSE, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->negateImage($gray);
    $geometry = $new_image->getImageGeometry();
    $this->changeImage($image_id, $this->images[$image_id][1], $geometry['height'], $geometry['width'], $new_image->getImageSize());
    $new_image->writeImage($imagePath);
  }

}

?>
