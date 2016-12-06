<?php
require_once("cg.php");
require_once("our-company-function.php");
require_once("bd.php");



$show_amount=0;  //0 = off, 1 = on
$product_name_global_variable = "product";



resetEnquiryIdCounterAtTheBegininng();

function resetEnquiryIdCounterAtTheBegininng()
{
	$todaysDate = getTodaysDate();
	$lastChangeDate = getEnquiryIdResetDateOC(5);
     
    if(strtotime($todaysDate)>strtotime($lastChangeDate))
    {
     resetEnquiryIdCounterForOC(5);
    }

}

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
	

function getTodaysDateTime()
{
	    $sql="select NOW()";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];
		
}

function getTodaysDate()
{
	$dateAndTime = getTodaysDateTime();
	$date=date('Y-m-d',strtotime($dateAndTime));
	return $date;
}





function getDateDifferneceBetweenDates($date1,$date2) // yyyy-mm-dd
{
	$datetime1 = strtotime($date1);
	$datetime2 = strtotime($date2);
	
	$datetime = $datetime2 - $datetime1;
	
	echo $datetime1 ." ";
	echo $datetime2. " ";
	echo $datetime." ";
	
	
	$diff = $datetime / 3600 / 24;
	
	
	return $diff;
	
}


function getYearsBetweenDates($date1, $date2) // yyyy-mm-dd
{
	$datetime1 = strtotime($date1);
	$datetime2 = strtotime($date2);
	
	$datetime = $datetime2 - $datetime1;
	
	$diff = $datetime / 3600 / 24 / 365;
	$diff = floor($diff);
	
	return $diff;
	
}



function getMonthArrayBetweenTwoDates($date1,$date2) //yyyy-mm-dd
{
	$date_diff=getDateDifferneceBetweenDates($date1,$date2);
	
	
	
	if($date_diff>=365)
	return array(1,2,3,4,5,6,7,8,9,10,11,12);
	else
	{
		
		$from_month = date('n',strtotime($date1));
		$to_month = date('n',strtotime($date2));
		
		
		
		
		if($to_month<$from_month)
		$to_month_12=$to_month+12;
		else
		$to_month_12=$to_month;
		
		
		
		$month_array = array();
		
		for($i=$from_month,$j=$from_month;$i<=$to_month_12;$i++,$j++)
		{
			
			if($j>12)
			$j=1;
			
			$month_array[]=$j;
		}
		return $month_array;
	}
	
}

function getDateAfterDaysFromTodaysDate($days)
{
	
	$dateAndTime = getTodaysDateTime();
	$date=date('Y-m-d',strtotime($dateAndTime.' + '.$days.' days'));
	return $date;
}

function getDateBeforeDaysFromTodaysDate($days)
{
	
	$dateAndTime = getTodaysDateTime();
	$date=date('Y-m-d',strtotime($dateAndTime.' - '.$days.' days'));
	return $date;
}
?>