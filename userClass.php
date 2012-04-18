<?php
require_once('db_params.php');
include "folderClass.php";
include "macrosClass.php";
include "imageClass.php";

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
    $this->folders = array();
    $this->macros = array();
    $this->images = array();
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
      $query = "SELECT username, password, email, signUpDate FROM users WHERE user_id='".$user_id."';";
      $result = mysql_query($query, $connection);
      if($row = mysql_fetch_assoc($result)) {
	$this->username = $row["username"];
	$this->password = $row["password"];
	$this->email = $row["email"];
	$this->signUpDate = $row["signUpDate"];
	$this->folders = new folders($this->user_id);
	$this->macros =  new macros($this->user_id);
	$this->images = new userImages($this->user_id);
	mysql_free_result($result);
	return $this;
      }
      else {
	mysql_free_result($result);
	$this->errors[] = "user not found";
	return NULL;
      }
    }
    else {
      $this->errors[] = "no user_id supplied";
      return NULL;
    }
  }

  function createUser($username, $password) {
    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
    mysql_query("USE ".$this->DATABASE, $connection);
    $query = "SELECT username FROM users WHERE username='".$username."';";
    $result = mysql_query($query, $connection);
    if($row = mysql_fetch_assoc($result)) {
      mysql_free_result($result);
      $this->errors[] = "user already exists";
      return FALSE;
    }
    $query = "INSERT INTO users(username, password, email) VALUES('".$username."','".$password."','');";
    mysql_query($query);
    $query = "SELECT id, signUpDate FROM users WHERE username='".$username."';";
    $result = mysql_query($query, $connection);
    if($row = mysql_fetch_assoc($result)) {
      $this->user_id = $row["id"];
      $this->username = $username;
      $this->password = $password;
      $this->email = "";
      $this->signUpDate = $row["signUpDate"];
      $this->folders = new folders($this->user_id);
      $this->macros = new macros($this->user_id);
      $this->images = new userImages($this->user_id);
      mysql_free_result($result);
      if(!is_dir("user_folders/".$this->user_id)) {
	if(!mkdir("user_folders/".$this->user_id, 0700, true)) {
	  $this->errors[] = "user folder not created";
	  return FALSE;
	}
	else return TRUE;
      }
      else {
	$this->errors[] = "user folder already exists";
	return FALSE;
      }
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

  function createSubFolder($folderName = NULL) {
    $this->folders->createFolder($folderName);
  }

  function deleteSubFolder($folder_id = NULL, $folderName = NULL) {
    $this->folders->deleteFolder($folder_id, $folderName);
  }

  function createMacroStep($macroName = NULL, $macroStep = NULL) {
    $this->macros->createMacroStep($macroName, $macroStep);
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
