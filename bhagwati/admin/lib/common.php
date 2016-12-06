<?php
require_once('cg.php');
require_once('bd.php');

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$accounts=0;
$webRoot  = str_replace(array($docRoot, 'lib/common.php'), '', $thisFile);
$srvRoot  = str_replace('lib/common.php', '', $thisFile);

define('WEB_ROOT',"/admin/");
define('SRV_ROOT', $srvRoot);
define('BP_ROOT',"C:\\gDrive");


function dump_error_to_file($errno, $errstr) {
	$errorDetails=debug();
    file_put_contents(SRV_ROOT.'tmp/errors.txt', date('Y-m-d H:i:s')." - ".$errstr.$errorDetails, FILE_APPEND);
}
set_error_handler('dump_error_to_file');


function clean_data($input) {
	
    $input = trim(htmlentities(strip_tags($input,",")));
    $input = mysql_real_escape_string($input);
    return $input;
}
function checkForNumeric() // taken n number of inputs 
{
	$arg_list = func_get_args();
	$result=true;
	foreach($arg_list as $arg)
	{
		$innerResult=is_numeric($arg);
		$result=$result && $innerResult;
		
	}
	return $result;
	
	}
function checkForImagesInArray($imgArray)
{
	if(is_array($imgArray))
	{
		if(count($imgArray)>0)
		{
			 if($imgArray[0]!="" &&  $imgArray[0]!=null)
			 return true;
			 else 
			 return false;
			}
		}
	else
	{
		 if($imgArray!="" &&  $imgArray!=null)
			 return true;
			 else 
			 return false;
	}
}	

function validateForNull()
{
	$arg_list = func_get_args();
	$result=true;
	foreach($arg_list as $arg)
	{
		if($arg==null || $arg=="")
		$result=false;
		
	}
	return $result;
}

function checkForAlphaNumeric()
{
	$arg_list = func_get_args();
	$result=true;
	foreach($arg_list as $arg)
	{
		if($arg!=null && $arg!="")
		$result=preg_match('/^[a-z0-9]+$/i', $arg);
		if($result==false)
		return false;
	}
	return $result;
	
	
	}
	
function validateForDate($input)
{
	
   if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $input))
   {
      return true; // it matched, return true
   }
   else
   {
      return false;
   }
}

function validateMysqlDate( $date )
{
    return preg_match( '#^(?P<year>\d{2}|\d{4})([- /.])(?P<month>\d{1,2})\2(?P<day>\d{1,2})$#', $date, $matches )
           && checkdate($matches['month'],$matches['day'],$matches['year']);
}


function getTodaysDate()
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d',strtotime($resultArray[0][0]));
	
	}

function getTodaysDateTime()
{
	$sql="SELECT NOW()";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return date('Y-m-d H:i:s',strtotime($resultArray[0][0]));
	
	}	

function getPreviousDate($date)
{
	
	if(isset($date) && validateForNull($date))
			{
		    $date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}	
	return date('Y-m-d', strtotime('-1 day', strtotime($date)));		
	}	

function getNextDate($date)
{
	
	if(isset($date) && validateForNull($date))
			{
		    $date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}	
	return date('Y-m-d', strtotime('+1 day', strtotime($date)));		
	}		



?>