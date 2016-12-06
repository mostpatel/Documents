<?php

$dbConn1 = mysql_connect ($dbHost1, $dbUser1, $dbPass1,true) or die ('MySQL connect failed. ' . mysql_error($dbConn1));
mysql_select_db($dbName1,$dbConn1) or die('Cannot select database. ' . mysql_error($dbConn1));




$time1="SET time_zone='+5:30'";
mysql_query($time1,$dbConn1) or die(mysql_error($dbConn1));



function dbQuery1($sql)
{
   /*	$result = mysql_query($sql) or die('<META HTTP-EQUIV="REFRESH" 
CONTENT="1;URL='.WEB_ROOT.'">'); */
 	 global $dbConn1;
	 $result = mysql_query($sql,$dbConn1) or die(mysql_error()); 
	 
	return $result;
}

function dbAffectedRows1()
{
	global $dbConn1;
	return mysql_affected_rows($dbConn1);
}

function dbFetchArray1($result, $resultType = MYSQL_BOTH) {
	return mysql_fetch_array($result, $resultType);
}

function dbFetchAssoc1($result)
{
	return mysql_fetch_assoc($result);
}

function dbFetchRow1($result) 
{
	return mysql_fetch_row($result);
}

function dbFreeResult1($result)
{
	return mysql_free_result($result);
}

function dbNumRows1($result)
{
	return mysql_num_rows($result);
}

function dbSelect1($dbName)
{
	return mysql_select_db($dbName);
}

function dbInsertId1()
{
	global $dbConn1;
	return mysql_insert_id($dbConn1);
}

function dbResultToArray1($result)
{
	$resultArray = array();
	while($row=dbFetchArray1($result,MYSQL_BOTH))
	{
	  $resultArray[] = $row;	
	}
	 return $resultArray;
}
?>