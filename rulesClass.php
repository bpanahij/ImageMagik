<?php
require_once('db_params.php');
class rules
{
  private $DATABASE = DBNAME;
  private $DBUSER = DBUSER;
  private $DBPASS = DBPASS;
  private $HOST = HOST;
  protected $user_id;
  protected $rules;
  protected $errors;
 
  function __construct($user_id = NULL)
  {
    $this->user_id = NULL;
    $this->rules = new array();
    $this->errors = new array();
    if($user_id) {
      $this->user_id = $user_id;
      $connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPASS);
      mysql_query("USE ".$this->DATABASE, $connection);
      $query = "SELECT DISTINCT rule FROM folders WHERE user_id='".$user_id."'";
      $result = mysql_query($query, $connection);
      while($row = mysql_fetch_assoc($result)) {
	$query2 = "SELECT id, step FROM folders WHERE user_id='".$user_id."' AND rule='".$row[0]."'";
	$result2 = mysql_query($query2, $connection);
	$steps = new array();
	while($row2 = mysql_fetch_assoc($result2)) {
	  $step = new array($row2[0], $row2[1]);
	  $steps[] = $step;
	}
	$this->rules[] = new array($row[0], $steps);
      }
      return TRUE;
    }
    else {
      $errors[] = $this->user_id." user_id not provided";
      return FALSE;
    }
  }

  function addRule($ruleName, $steps) {
    
  }
