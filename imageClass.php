<?php

class userImages
{
  protected $images;
  protected $user_id;
  protected $errors;
  
  function __construct($user_id = NULL) {
    if($user_id) {
      $this->user_id = $user_id;
      $this->images = array();
      $user_dir = getcwd()."/user_folders/".$this->user_id;
      if(is_dir($user_dir)) {
	$contents = scandir($user_dir);
	foreach($contents as $file) {
	  if(($file != "..") && ($file != ".")) {
	    if(is_dir($user_dir."/".$file)) {
	      $subContents = scandir($user_dir."/".$file);
	      foreach($subContents as $file2) {
		if(($file2 != "..") && ($file2 != ".")) {
		  $this->images[$file2] = $user_dir."/".$file;
		}
	      }
	    }
	    else {
	      $this->images[$file] = $user_dir;
	    }
	  }
	}
      }
      else {
	$this->errors[] = "constructor: user folder does not exist";
	return $this;
      }
    }
    else {
      $this->errors[] = "constructor: user_id not provided";
      return $this;
    }
  }

  function getUploadForm() {
    if($user_id) {
      if(is_dir("user_folders/".$user_id)) {
	
      }
      else {
	$this->errors[] = "user folder does not exist";
	return $this;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return $this;
    }
  }    

  function getImages() {
    if($user_id) {
      if(is_dir("user_folders/".$user_id)) {
	
      }
      else {
	$this->errors[] = "user folder does not exist";
	return $this;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return $this;
    }
  }

  function createXMLNode() {
    $doc = new domDocument();
    $doc->preserveWhiteSpace=false;
    $root = $doc->createElement("userImages");
    $imagesNode = $doc->createElement("images");

    foreach($this->images as $image => $folder) {
      $imageNode = $doc->createElement("image");
      $imageNode->setAttribute("path", $folder);
      $imageNode->appendChild($doc->createTextNode($image));
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
}

?>