<?php
require_once 'cg.php';

$dbConn = mysql_connect ($dbHost, $dbUser, $dbPass) or die ('MySQL connect failed. ' . mysql_error($dbConn));
mysql_select_db($dbName,$dbConn) or die('Cannot select database. ' . mysql_error($dbConn));
$time="SET time_zone='+5:30'";
mysql_query($time,$dbConn) or die(mysql_error($dbConn));

function dbQuery($sql,$bd2=false)
{
	global $dbConn,$dbConn1;
   /*	$result = mysql_query($sql) or die('<META HTTP-EQUIV="REFRESH" 
CONTENT="1;URL='.WEB_ROOT.'">'); */
 	  
	  if($bd2)
	  $dbCon = $dbConn1;
	  else 
	  $dbCon = $dbConn;
	  
	  if(!$dbCon)
	  return false;
	  
	 $result = mysql_query($sql,$dbCon) or die(mysql_error()); 
	return $result;
}

function dbAffectedRows($bd2=false)
{
	global $dbConn,$dbConn1;
	  if($bd2)
	  $dbCon = $dbConn1;
	  else 
	  $dbCon = $dbConn;
	  
	   if(!$dbCon)
	  return false;
	return mysql_affected_rows($dbCon);
}

function dbFetchArray($result, $resultType = MYSQL_BOTH) {
	return mysql_fetch_array($result, $resultType);
}

function dbFetchAssoc($result)
{
	return mysql_fetch_assoc($result);
}

function dbFetchRow($result) 
{
	return mysql_fetch_row($result);
}

function dbFreeResult($result)
{
	return mysql_free_result($result);
}

function dbNumRows($result)
{
	return mysql_num_rows($result);
}

function dbSelect($dbName,$bd2=false)
{
	global $dbConn,$dbConn1;
	  if($bd2)
	  $dbCon = $dbConn1;
	  else 
	  $dbCon = $dbConn;
	   if(!$dbCon)
	  return false;
	return mysql_select_db($dbName,$dbCon);
}

function dbInsertId($bd2=false)
{
	global $dbConn,$dbConn1;
	  if($bd2)
	  $dbCon = $dbConn1;
	  else 
	  $dbCon = $dbConn;
	   if(!$dbCon)
	  return false;
	return mysql_insert_id($dbCon);
}

function dbResultToArray($result)
{
	$resultArray = array();
	while($row=dbFetchArray($result,MYSQL_BOTH))
	{
	  $resultArray[] = $row;	
	}
	 return $resultArray;
}
?>