<?php
include "foldersClass.php";
include "macrosClass.php";
include "PermImagesClass.php";
require_once('db_params.php');
class user
{
  private $DATABASE = DBNAME;
  private $DBUSER = DBUSER;
  private $DBPASS = DBPASS;
  private $HOST = HOST;
  protected $user_id;
  protected $username;
  protected $password;
  protected $email;
  protected $signUpDate;
  protected $folders;
  protected $macros;
  protected $images;
  protected $errors;

  function __construct($user_id = NULL) 
  {
    $this->user_id = $user_id;
    $this->username = NULL;
    $this->password = NULL;
    $this->email = NULL;
    $this->signUpDate = NULL;
    $this->folders = NULL;
    $this->macros = NULL;
    $this->images = NULL;
    $this->errors = array();
    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
    mysql_query("USE ".$this->DATABASE, $connection);
    $query = "CREATE TABLE IF NOT EXISTS users(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		username VARCHAR(20),
		password VARCHAR(20),
                email VARCHAR(100),
		signUpDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on UPDATE CURRENT_TIMESTAMP)
		ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";
    mysql_query($query, $connection);
    if($user_id) {
      $query = "SELECT username, password, email, signUpDate FROM users WHERE id='".$user_id."';";
      $result = mysql_query($query, $connection);
      if($row = mysql_fetch_assoc($result)) {
	$this->username = $row["username"];
	$this->password = $row["password"];
	$this->email = $row["email"];
	$this->signUpDate = $row["signUpDate"];
	$this->folders = new folders($this->user_id);
	if(!$this->macros = new macros($this->user_id)) {
	  $this->errors[] = "macros not found";
	  return FALSE;
	}
	$this->images = new userImages($this->user_id);
	mysql_free_result($result);
	return $this;
      }
      else {
	mysql_free_result($result);
	$this->errors[] = "user not found";
	return FALSE;
      }
    }
    else {
      $this->errors[] = "no user_id supplied";
      return FALSE;
    }
  }

  function createUser($username, $password) {
    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
    mysql_query("USE ".$this->DATABASE, $connection);
    $query = "SELECT username FROM users WHERE username='".$username."';";
    $result = mysql_query($query, $connection);
    if($row = mysql_fetch_row($result)) {
      mysql_free_result($result);
      $this->errors[] = "user already exists";
      return FALSE;
    }
    $query = "INSERT INTO users(username, password, email) VALUES('".$username."','".$password."','');";
    mysql_query($query, $connection);
    $query = "SELECT id, signUpDate FROM users WHERE username='".$username."';";
    $result = mysql_query($query, $connection);
    if($row = mysql_fetch_assoc($result)) {
      $this->user_id = $row["id"];
      mkdir("photos/".$this->user_id);
      $this->username = $username;
      $this->password = $password;
      $this->email = "";
      $this->signUpDate = $row["signUpDate"];
      $this->folders = new folders($this->user_id);
      $this->macros = new macros($this->user_id);
      $this->images = new userImages($this->user_id);
      mysql_free_result($result);
      return TRUE;
    }
    else {
      mysql_free_result($result);
      $this->errors[] = "attempted to register user but not in table";
      return FALSE;
    }
  }

  function loginUser($username, $password) {
    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
    mysql_query("USE ".$this->DATABASE, $connection);
    $query = "SELECT id, username, password, email, signUpDate FROM users WHERE username='".$username."';";
    $result = mysql_query($query, $connection);
    if($row = mysql_fetch_assoc($result)) {
      if($password == $row["password"]) {
	$this->username = $username;
	$this->password = $password;
	$this->user_id = $row["id"];
	$this->email = $row["email"];
	$this->signUpDate = $row["signUpDate"];
	$this->folders =  new folders($this->user_id);
	$this->macros = new  macros($this->user_id);
	$this->images = new userImages($this->user_id);
	mysql_free_result($result);
	return TRUE;
      }
      else {
	mysql_free_result($result);
	$this->errors[] = "user not found";
	return FALSE;
      }
    }
  }

  function changeEmail($email) {
    if($this->user_id) {
      $pattern = '/.+@.+\..+/';
      if(preg_match($pattern, $email)) {
	$connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	mysql_query("USE ".$this->DATABASE, $connection);
	$query = "UPDATE users SET email='".$email."' WHERE id='".$this->user_id."'";
	$result = mysql_query($query);
	if($result) {
	  $this->email = $email;
	  return TRUE;
	}
	else {
	  $this->errors[]= "email not set";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = "not a valid email";
	return FALSE;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }

  function createNewBlankImage() {
    return $this->images->createNewBlankImage();
  }

  function createSubFolder($folderName = NULL) {
    $this->folders->createFolder($folderName);
  }

  function deleteSubFolder($folder_id = NULL, $folderName = NULL) {
    $this->folders->deleteFolder($folder_id, $folderName);
  }

  function startMacro($macroName = NULL) {
    return $this->macros->startMacro($macroName);
  }

  function endMacro($macroName = NULL) {
    return $this->macros->endMacro();
  }

  function deleteMacro($macroName = NULL) {
    return $this->macros->deleteMacro($macroName);
  }

  function createMacroStep($macroName = NULL, $macroStep = NULL) {
    $this->macros->createMacroStep($macroName, $macroStep);
  }
  
  function renameMacro($macroName = NULL, $newMacroName = NULL) {
    $this->macros->renameMacro($macroName, $newMacroName);
  }

  function executeMacro($macroName = NULL, $image_id = NULL) {
    if($this->user_id) {
      if($macroName) {
	if($image_id) {
	  $doc = new domDocument();
	  $doc->preserveWhiteSpace=false;
	  $macrosNode = $doc->importNode($this->macros->createXMLNode(), TRUE);
	  $doc->appendChild($macrosNode);
	  $activeMacro = NULL;
	  $tempMacroList = $doc->getElementsByTagName('macro');
	  foreach($tempMacroList as $macro) {
	    if($macro->getAttribute('macro_name') == $macroName) {
	      $activeMacro = $macro;
	    }
	  }
	  $savedMacroList = $doc->getElementsByTagName('savedMacro');
	  foreach($savedMacroList as $macro) {
	    if($macro->getAttribute('macro_name') == $macroName) {
	      $activeMacro = $macro;
	    }
	  }
	  if($activeMacro == NULL) {
	    $this->errors[] = "could not find macro";
	    return FALSE;
	  }
	  $stepList = $activeMacro->getElementsByTagName('step');
	  foreach($stepList as $step) {
	    $command = $step->firstChild->nodeValue;
	    $commandParts = split(":", $command);
	    $method = $commandParts[0];
	    if(count($commandParts) == 1) $this->$method($image_id);
	    if(count($commandParts) == 2) $this->$method($commandParts[1], $image_id);
	    if(count($commandParts) == 3) $this->$method($commandParts[1], $commandParts[2], $image_id);
	    if(count($commandParts) == 4) $this->$method($commandParts[1], $commandParts[2], $commandParts[3], $image_id);
	    if(count($commandParts) == 5) $this->$method($commandParts[1], $commandParts[2], $commandParts[3], $commandParts[4], $image_id);
	    if(count($commandParts) == 6) $this->$method($commandParts[1], $commandParts[2], $commandParts[3], $commandParts[4], $commandParts[5], $image_id);
	    if(count($commandParts) == 7) $this->$method($commandParts[1], $commandParts[2], $commandParts[3], $commandParts[4], $commandParts[5], $commandParts[6], $image_id);
	  }
	}
      }
    }
  }

  function resize($height = NULL, $width = NULL, $image_id = NULL) {
    $this->images->resize($height, $width, $image_id);
    $this->macros->createMacroStep("resize:{$height}:{$width}");
  }

  function roundCorners($x = NULL, $y = NULL, $image_id = NULL) {
    $this->images->roundCorners($x, $y, $image_id);
    $this->macros->createMacroStep("roundCorners:{$x}:{$y}");
  }

  function watermark($text = '', $font_size = '12', $text_color = 'black', $x = 0, $y = 0, $image_id = NULL) {
    $this->images->watermark($text, $font_size, $text_color, $x, $y, $image_id);
    $this->macros->createMacroStep("watermark:{$text}:{$font_size}:{$text_color}:{$x}:{$y}");
  }

  function shave($x = NULL, $y = NULL, $image_id = NULL) {
    $this->images->shave($x, $y, $image_id);
    $this->macros->createMacroStep("shave:{$x}:{$y}");
  }

  function percentSize($percent = NULL, $image_id = NULL) {
    $this->images->percentSize($percent, $image_id);
    $this->macros->createMacroStep("percentSize:{$percent}");
  }

  function setCompression($percent = NULL, $image_id = NULL) {
    $this->images->setCompression($percent, $image_id);
    $this->macros->createMacroStep("setCompression:{$percent}");
  }

  function flip($image_id = NULL) {
    $this->images->flip($image_id);
    $this->macros->createMacroStep("flip");
  }

  function flop($image_id = NULL) {
    $this->images->flop($image_id);
    $this->macros->createMacroStep("flop");
  }

  function rotate($degrees = NULL, $image_id = NULL) {
    $this->images->rotate($degrees, $image_id);
    $this->macros->createMacroStep("rotate:{$degrees}");
  }

  function border($x = NULL, $y = NULL, $color = NULL, $image_id = NULL) {
    $this->images->border($x, $y, $color, $image_id);
    $this->macros->createMacroStep("border:{$x}:{$y}:{$color}");
  }

  function frame($height = NULL, $width = NULL, $inner_bevel = NULL, $outer_bevel = NULL, $color = NULL, $image_id = NULL) {
    $this->images->frame($height, $width, $inner_bevel, $outer_bevel, $color, $image_id);
    $this->macros->createMacroStep("frame:{$height}:{$width}:{$inner_bevel}:{$outer_bevel}:{$color}");
  }

  function modulate($brightness = NULL, $saturation = NULL, $hue = NULL, $image_id = NULL) {
    $this->images->modulate($brightness, $saturation, $hue, $image_id);
    $this->macros->createMacroStep("modulate:{$brightness}:{$saturation}:{$hue}");
  }

  function sharpen($sigma = NULL, $image_id = NULL) {
    $this->images->sharpen($sigma, $image_id);
    $this->macros->createMacroStep("sharpen:{$sigma}");
  }

  function emboss($sigma = NULL, $image_id = NULL) {
    $this->images->emboss($sigma, $image_id);
    $this->macros->createMacroStep("emboss:{$sigma}");
  }

  function shade($gray = NULL, $azimuth = NULL, $elevation = NULL, $image_id = NULL) {
    $this->images->shade($gray, $azimuth, $elevation, $image_id);
    $this->macros->createMacroStep("shade:{$gray}:{$azimuth}:{$elevation}");
  }

  function oilPaint($radius = NULL, $image_id = NULL) {
    $this->images->oilPaint($radius, $image_id);
    $this->macros->createMacroStep("oilPaint:{$radius}");
  }

  function charcoal($radius = NULL, $sigma = NULL, $image_id = NULL) {
    $this->images->charcoal($radius, $sigma, $image_id);
    $this->macros->createMacroStep("charcoal:{$radius}:{$sigma}");
  }

  function negate($gray = FALSE, $image_id = NULL) {
    $this->images->negate($gray, $image_id);
    $this->macros->createMacroStep("negate:{$gray}");
  }

  function copyImage($image_id = NULL) {
    $this->macros->createMacroStep("copyImage");
    return $this->images->copyImage($image_id);
  }

  function newImage($folder_id = NULL, $ext = NULL, $height = NULL, $width = NULL, $size = NULL) {
    return $this->images->newImage($folder_id, $ext, $height, $width, $size);
  }

  function getImageLocation($image_id = NULL) {
    return $this->images->getImageLocation($image_id);
  }

  function getThumbnailImage($image_id = NULL) {
    return $this->images->getThumbnailImage($image_id);
  }

  function getFullImage($image_id = NULL) {
    return $this->images->getFullImage($image_id);
  }

  function deleteImage($image_id = NULL) {
    $this->images->deleteImage($image_id);
  }

  function createXMLResponse() {
    $doc = new domDocument();
    $doc->preserveWhiteSpace=false;

    $userNode = $doc->createElement("user");
    $useridNode = $doc->createElement("user_id");
    $useridNode->appendChild($doc->createTextNode($this->user_id));
    $usernameNode = $doc->createElement("username");
    $usernameNode->appendChild($doc->createTextNode($this->username));
    //$passwordNode = $doc->createElement("password");
    //$passwordNode->appendChild($doc->createTextNode($this->password));
    $emailNode = $doc->createElement("email");
    $emailNode->appendChild($doc->createTextNode($this->email));
    $signupdateNode = $doc->createElement("signupdate");
    $signupdateNode->appendChild($doc->createTextNode($this->signUpDate));

    $userNode->appendChild($useridNode);
    $userNode->appendChild($usernameNode);
    //$userNode->appendChild($passwordNode);
    $userNode->appendChild($emailNode);
    $userNode->appendChild($signupdateNode);

    $foldersNode = $doc->importNode($this->folders->createXMLNode(), TRUE);
    $macrosNode = $doc->importNode($this->macros->createXMLNode(), TRUE);
    $imagesNode = $doc->importNode($this->images->createXMLNode(), TRUE);

    $userNode->appendChild($foldersNode);
    $userNode->appendChild($macrosNode);
    $userNode->appendChild($imagesNode);
    
    foreach($this->errors as $error) {
      $errorNode = $doc->createElement("error");
      $errorNode->appendChild($doc->createTextNode($error));
      $userNode->appendChild($errorNode);
    }
    $doc->appendChild($userNode);
    echo $doc->saveXML();
  } 
}
    
?>
