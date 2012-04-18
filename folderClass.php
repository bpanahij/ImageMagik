<?php
require_once('db_params.php');
class folders
{
  private $DATABASE = DBNAME;
  private $DBUSER = DBUSER;
  private $DBPASS = DBPASS;
  private $HOST = HOST;
  protected $user_id;
  protected $folders;
  protected $errors;
 
  function __construct($user_id = NULL)
  {
    $this->user_id = NULL;
    $this->folders = array();
    $this->errors = array();

    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
    mysql_query("USE ".$this->DATABASE, $connection);
    $query = "CREATE TABLE IF NOT EXISTS folders(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		folder VARCHAR(20),
		user_id INT NOT NULL)
		ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";
    mysql_query($query, $connection);
    if($user_id) {
      if(!is_dir("user_folders/".$user_id)) {
	$errors[] = $user_id." user does not have a folder";
	mkdir("user_folders/".$user_id, 0755, true);
      }
      $this->user_id = $user_id;
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "SELECT id, folder FROM folders WHERE user_id='".$user_id."'";
      $result = mysql_query($query, $connection);
      while($row = mysql_fetch_assoc($result)) {	
	if(is_dir("user_folders/".$this->user_id."/".$row["folder"])) {
	  $this->folders[$row["id"]] = $row["folder"];
	}
	else {
	  $this->errors[] = $this->user_id."/".$row["folder"]."constructor: folder does not exist but is in table";
	  return $this;
	}
      }
      return $this;
    }
    else {
      $this->errors[] = "constructor: user_id not provided";
      return $this;
    }
  }

  function createFolder($folderName = NULL)
  {
    if($this->user_id) {
      if($folderName) {
	if(!file_exists("user_folders/".$this->user_id."/".$folderName)) {
	  if(mkdir("user_folders/".$this->user_id."/".$folderName, 0700, true)) {
	    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	    mysql_query("USE ".$this->DATABASE, $connection);
	    $query = "INSERT INTO folders(user_id, folder) VALUES ('".$this->user_id."', '".$folderName."' )";
	    $result = mysql_query($query, $connection);
	    $query = "SELECT id, folder FROM folders WHERE user_id='".$this->user_id."' AND folder='".$folderName."'";
	    $result2 = mysql_query($query, $connection);
	    if($row = mysql_fetch_assoc($result2)) {
	      $this->folders[$row["id"]] = $row["folder"];
	      return TRUE;
	    }
	    else {
	      $this->errors[] = $this->user_id."/".$folderName." failed to put folder in table";
	      return FALSE;
	    }
	  }
	  else {
	    $this->errors[] = $this->user_id."/".$folderName." folder creation failed";
	    return FALSE;
	  }
	}
	else {
	  $this->errors[] = $this->user_id."/".$folderName." already exists, folder not created";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = $this->user_id." foldername not provided, folder not created";
	return FALSE;
      }
    }
    else {
      $this->errors[] = $folderName." username not set, folder not created";
      return FALSE;
    }
  }

  function deleteFolder($folder_id = NULL, $folderName = NULL) {
    if($this->user_id) {
      if($folder_id) {
	if(file_exists("user_folders/".$this->user_id."/".$folderName)) {
	  if(rmdir("user_folders/".$this->user_id."/".$folderName)) {
	    unset($this->folders[$folder_id]);
	    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	    mysql_query("USE ".$this->DATABASE, $connection);
	    $query = "DELETE FROM folders WHERE user_id='".$this->user_id."' AND id='".$folder_id."' AND folder='".$folderName."'";
	    if($result = mysql_query($query, $connection)) {
	      return TRUE;
	    }
	    else {
	      $this->errors[] = $this->user_id."/".$folderName." deleted but table not updated";
	      return FALSE;
	    }
	  }
	  else {
	    $this->errors[] = $this->user_id."/".$folderName." could not be deleted from user directory";
	    return FALSE;
	  }
	}
	else {
	  $this->errors[] = $this->user_id."/".$folderName." does not exist, could not delete folder";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = $this->user_id."/".$folderName." folder_id not provided, could not delete folder";
	return FALSE;
      }
    }
    else {
      $this->errors[] = $this->user_id."/".$folderName." user_id not set, could not delete folder";
      return FALSE;
    }
  }

  function modifyFolderName($folder_id = NULL, $newFolderName = NULL) {
    if($this->user_id) {
      if($folder_id) {
	if(file_exists("user_folders/".$this->user_id."/".$this->folders[$folder_id])) {
	  if(rename($this->folders[$folder_id], $newFolderName)) {
	    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	    mysql_query("USE ".$this->DATABASE, $connection);
	    $query = "UPDATE folders SET folder='".$newFolderName."' WHERE user_id='".$this->user_id."' AND id='".$folder_id."'";
	    if($result = mysql_query($query, $connection)) {
	      return TRUE;
	    }
	    else {
	      $this->errors[] = $this->user_id."/".$this->folders[$folder_id]." table was not updated to rename folder: ".$newFolderName;
	      return FALSE;
	    }
	  }
	  else {
	    $this->errors[] = $this->user_id."/".$this->folders[$folder_id]." could not be renamed to: ".$newFolderName;
	    return FALSE;
	  }
	}
	else {
	  $this->errors[] = $this->user_id."/".$this->folders[$folder_id]." does not exist, could not rename folder";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = $this->user_id."/NEW Folder: ".$newFolderName." old folder_id not provided, could not rename folder";
	return FALSE;
      }
    }
    else {
      $this->errors[] = $this->user_id."/".$folderName." user_id not set, could not rename folder";
      return FALSE;
    }
  }
    
  function createXMLNode() {
    $doc = new domDocument();
    $doc->preserveWhiteSpace=false;
    $root = $doc->createElement("userFolders");
    $foldersNode = $doc->createElement("folders");

    foreach($this->folders as $id => $folder) {
      $folderNode = $doc->createElement("folder");
      $folderNode->setAttribute("folder_id", $id);
      $folderNode->appendChild($doc->createTextNode($folder));
      $foldersNode->appendChild($folderNode);
    }
    $root->appendChild($foldersNode);
    
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
