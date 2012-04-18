<?php
require_once('db_params.php');
class macros
{
  private $DATABASE = DBNAME;
  private $DBUSER = DBUSER;
  private $DBPASS = DBPASS;
  private $HOST = HOST;
  protected $user_id;
  protected $macros;
  protected $errors;
 
  function __construct($user_id = NULL)
  {
    $this->user_id = NULL;
    $this->thisMacro = "";
    $this->savedMacros = array();
    $this->macros = array();
    $this->errors = array();

    $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
    mysql_query("USE ".$this->DATABASE, $connection);
    $query = "CREATE TABLE IF NOT EXISTS macros(
		id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(id),
		macro VARCHAR(40),
		step VARCHAR(100),
		user_id INT NOT NULL,
                perm VARCHAR(10))
		ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";
    mysql_query($query, $connection);

    if($user_id) {
      $this->user_id = $user_id;
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "SELECT DISTINCT macro FROM macros WHERE user_id='".$this->user_id."' AND perm='FALSE'";
      $result = mysql_query($query, $connection);
      while($row = mysql_fetch_assoc($result)) {
	$query2 = "SELECT id, step FROM macros WHERE user_id='".$this->user_id."' AND macro='".$row["macro"]."' ORDER BY id;";
	$result2 = mysql_query($query2, $connection);
	$steps = array();
	while($row2 = mysql_fetch_assoc($result2)) {
	  $steps[$row2["id"]] = $row2["step"];
	}
	$this->macros[$row["macro"]] = $steps;
      }

      $query3 = "SELECT DISTINCT macro FROM macros WHERE user_id='".$this->user_id."' AND perm='TRUE'";
      $result3 = mysql_query($query3, $connection);
      while($row3 = mysql_fetch_assoc($result3)) {
	$query4 = "SELECT id, step FROM macros WHERE user_id='".$this->user_id."' AND macro='".$row3["macro"]."' ORDER BY id;";
	$result4 = mysql_query($query4, $connection);
	$steps = array();
	while($row4 = mysql_fetch_assoc($result4)) {
	  $steps[$row4["id"]] = $row4["step"];
	}
	$this->savedMacros[$row3["macro"]] = $steps;
      }

      return $this;
    }
    else {
      $this->errors[] = $this->user_id." user_id not provided";
      return FALSE;
    }
  }

  function startMacro($macroName = NULL) {
    if($this->user_id) {
      $this->endMacro();
      if(!$macroName) $macroName = date("m.d.y H:i:s");
      $this->thisMacro = $macroName;
      $this->macros[$macroName] = array();
      $this->createMacroStep("EDITING MACRO");
      return TRUE;
    }
    else {
      $this->errors[] = $macroName." user_id not set";
      return FALSE;
    }
  }

  function endMacro() {
    if($this->user_id) {
      $this->getCurrentMacro();
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "DELETE FROM macros WHERE step = 'EDITING MACRO'";
      mysql_query($query, $connection);
      $this->thisMacro = NULL;
      return TRUE;
    }
    else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }
    
  function getCurrentMacro() {
    if($this->user_id) {
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "SELECT macro FROM macros WHERE step = 'EDITING MACRO'";
      if($result = mysql_query($query, $connection)) {
	if($row = mysql_fetch_assoc($result)) {
	  $this->thisMacro = $row["macro"];
	  return TRUE;
	}
	else {
	  $this->errors[] = "no macro currently active";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = "macro query result failed";
	return FALSE;
      }
    }
    else {
      $this->errors[] = "user_id not set";
      return FALSE;
    }
  }

  function createMacroStep($stepCommand = NULL) {
    if($this->user_id) {
      $this->getCurrentMacro();
      if($this->thisMacro) {
	if($stepCommand) {
	  $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	  mysql_query("USE ".$this->DATABASE, $connection);
	  $query = "INSERT INTO macros(user_id, macro, step, perm) VALUES('".$this->user_id."','".$this->thisMacro."','".$stepCommand."','FALSE')";
	  $result = mysql_query($query, $connection);
	  $query2 = "SELECT id FROM macros WHERE id=LAST_INSERT_ID()";
	  if($result2 = mysql_query($query2, $connection)) {
	    if($row = mysql_fetch_assoc($result2)) {
	      $this->macros[$this->thisMacro][$row["id"]] = $stepCommand;
	    }
	  }
	  else {
	    $this->errors[] = $this->user_id." ".$this->thisMacro." ".$stepCommand." not added to hash array";
	    return FALSE;
	  }
	}
	else {
	  $this->errors[] = $this->user_id." ".$this->thisMacro." $stepCommand not specified";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = $this->user_id." ".$stepCommand." thisMacro not set";
	return FALSE;
      }
    }
    else {
      $this->errors[] = $this->thisMacro." ".$stepCommand." user_id not provided";
      return FALSE;
    }
  }

  function renameMacro($macroName = NULL, $newMacroName = NULL) {
    if($this->user_id) {
      if($macroName) {
	if($newMacroName) {
	  $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	  mysql_query("USE ".$this->DATABASE, $connection);
	  $query = "UPDATE macros SET macro='".$newMacroName."', perm='TRUE' WHERE user_id='".$this->user_id."' AND macro='".$macroName."'";
	  if($result = mysql_query($query, $connection)) {
	    if($this->macros[$macroName]) {
	      $this->savedMacros[$newMacroName] = $this->macros[$macroName];
	      unset($this->macros[$macroName]);
	    }
	    else if($this->savedMacros[$macroName]){
	      $this->savedMacros[$newMacroName] = $this->savedMacros[$macroName];
	      unset($this->savedMacros[$macroName]);
	    }
	    else {
	      $this->errors[] = $this->user_id." ".$macroName." macro does not exist";
	      return FALSE;
	    }
	    return TRUE;
	  }
	}  
	else {
	  $this->errors[] = $this->user_id." newMacroName not set";
	  return FALSE;
	}
      }
      else {
	  $this->errors[] = $this->user_id." macroMacro not set";
	  return FALSE;
      }
    }
    else {
      $this->errors[] = $macroName." user_id not provided";
      return FALSE;
    }
  }
    
  function deleteMacro($macroName = NULL) {
    if($this->user_id) {
      if($macroName) {
	$connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
	mysql_query("USE ".$this->DATABASE, $connection);
	$query = "DELETE FROM macros WHERE user_id='".$this->user_id."' AND macro='".$macroName."'";
	if($result = mysql_query($query, $connection)) {
	  unset($this->macros[$macroName]);
	  unset($this->savedMacros[$macroName]);
	  return TRUE;
	}
	else {
	  $this->errors[] = $this->user_id." ".$macroName." macro table not updated";
	  return FALSE;
	}
      }
      else {
	$this->errors[] = $this->user_id." thisMacro not set";
	return FALSE;
      }
    }
    else {
      $this->errors[] = $macroName." user_id not provided";
      return FALSE;
    }
  }

  function deleteTemporaryMacros() {
    if($this->user_id) {
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "DELETE FROM macros WHERE user_id='".$this->user_id."' AND perm='FALSE'";
      if($result = mysql_query($query, $connection)) {
	$this->savedMacros = array();
	return TRUE;
      }
      else {
	$this->errors[] = $this->user_id." ".$macroName." macro table not updated";
	return FALSE;
      }
    }
    else {
      $this->errors[] = $macroName." user_id not provided";
      return FALSE;
    }
  }
  
  function createXMLNode() {
    $doc = new domDocument();
    $doc->preserveWhiteSpace=false;
    $root = $doc->createElement("userMacros");
    $macrosNode = $doc->createElement("macros");
    krsort($this->macros, SORT_STRING);
    foreach($this->macros as $macro => $steps) {
      $macroNode = $doc->createElement("macro");
      $macroNode->setAttribute("macro_name",$macro);
      foreach($steps as $step_id => $command) {
	$stepNode = $doc->createElement("step");
	$stepNode->setAttribute("step_id", $step_id);
	$stepNode->appendChild($doc->createTextNode($command));
	$macroNode->appendChild($stepNode);
      }
      $macrosNode->appendChild($macroNode);
    }
    $root->appendChild($macrosNode);

    krsort($this->savedMacros, SORT_STRING);
    foreach($this->savedMacros as $macro => $steps) {
      $savedMacroNode = $doc->createElement("savedMacro");
      $savedMacroNode->setAttribute("macro_name",$macro);
      foreach($steps as $step_id => $command) {
	$stepNode = $doc->createElement("step");
	$stepNode->setAttribute("step_id", $step_id);
	$stepNode->appendChild($doc->createTextNode($command));
	$savedMacroNode->appendChild($stepNode);
      }
      $macrosNode->appendChild($savedMacroNode);
    }
    $root->appendChild($macrosNode);
 
    $errorsNode = $doc->createElement("errors");
    foreach($this->errors as $error) {
      $errorNode = $doc->createElement("error");
      $errorNode->appendChild($doc->createTextNode($error));
      $errorsNode->appendChild($errorNode);
    }
    $root->appendChild($errorsNode);
    $doc->appendChild($root);
    return $root;
  }
  
}
