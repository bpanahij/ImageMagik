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
                size INT NOT NULL,
                image LONGBLOB)
		ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";
    mysql_query($query, $connection);
    if($user_id) {
      $this->user_id = $user_id;
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "SELECT id, folder_id, image FROM images WHERE user_id='".$user_id."'";
      $result = mysql_query($query, $connection);
      while($row = mysql_fetch_assoc($result)) {
	$this->images[$row["id"]] = array($row["folder_id"], $row["image"]);
      }
      return $this;
      
    }
    else {
      $this->errors[] = "constructor: user_id not provided";
      return $this;
    }
  }

  function newPermImage($folder_id = NULL, $ext = NULL, $height = NULL, $width = NULL, $size = NULL) {
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
	 $this->images[$row["id"]] = array(-1, $user_id."/".$folder_id."/".$row["id"].".".$ext);
	 return $row["id"];
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

  function newTempImage($imageContents = NULL) {
    if($this->user_id) {
      if($folder_id) {
	if($imageContents) {
	  $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	  mysql_query("USE ".$this->DATABASE, $connection);
	  $query = "UPDATE images SET image ='".base64_encode($imageContents)."'";
	  mysql_query($query, $connection);
	  $query = "SELECT id FROM images WHERE id=LAST_INSERT_ID()";
	  $result = mysql_query($query, $connection);
	  if($row = mysql_fetch_assoc($result)) {
	    $this->images[$row["id"]] = array($folder_id, $imageContents);
	  }
	  else {
	    $this->errors[] = "new image not found in table: images";
	    return FALSE;
	  }
	}
	else {
	  $this->errors[] = "image content not provided";
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
	
  function changeImage($image = NULL, $image_id = NULL) {
    if($this->user_id) {
      if($image_id) {
	if($image) {
	  $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	  mysql_query("USE ".$this->DATABASE, $connection);
	  $query = "UPDATE images SET image='".base64_encode($image)."' WHERE id='".$image_id."'";
	  mysql_query($query, $connection);
	  $this->images[$image_id][1] = base64_encode($image);
	}
	else {
	  $this->errors[] = "image not provided";
	  return FALSE;
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

  function copyImage($image_id) {
    if($this->user_id) {
      if($image_id) {
	$connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	mysql_query("USE ".$this->DATABASE, $connection);
	$query = "SELECT user_id, folder_id, image FROM images WHERE id='".$image_id."'";
	$result = mysql_query($query, $connection);
	if($row = mysql_fetch_assoc($result)) {
	  $query = "INSERT INTO images(user_id, folder_id, image) VALUES('".$row["user_id"]."','".$row["folder_id"]."','".$row["image"]."')";
	  mysql_query($query, $connection);
	  $query2 = "SELECT id FROM images WHERE id=LAST_INSERT_ID()";
	  $result2 = mysql_query($query2, $connection);
	  if($row2 = mysql_fetch_assoc($result2)) {
	    $this->images[$row2["id"]] = array($row["folder_id"], $row["image"]);
	    return $row2["id"];
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
	  $query = "UPDATE images SET folder_id='".$folder_id." WHERE id='".$image_id."')";
	  mysql_query($query, $connection);
	  $this->images[$image_id][0] = $folder_id;
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

  function getUploadForm() {
    if($this->user_id) {
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

  function getImageSource($image_id = NULL) {
    if($this->user_id) {
      if($image_id) {
	return base64_decode($this->images[$image_id][1]);
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

  function createXMLNode() {
    $doc = new domDocument();
    $doc->preserveWhiteSpace=false;
    $root = $doc->createElement("userImages");
    $imagesNode = $doc->createElement("images");

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
  function resizeImage($height = NULL, $width = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->adaptiveResizeImage(intval($width), intval($height));
    $new_image->writeImage($imagePath);
  }

  function roundCorners($x = NULL, $y = NULL, $image_id = NULL) {
    $imagePath = $this->getImageLocation($image_id);
    $new_image = new Imagick();
    $new_image->readImage($imagePath);
    $new_image->roundCorners(intval($x), intval($y));
    $new_image->writeImage($imagePath);
  }

}

?>
