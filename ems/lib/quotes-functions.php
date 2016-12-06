<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


changeTheQuote();
	
function totalQuotes()
{
	
	try
	{
		$sql="SELECT quote_id, quote
			  FROM ems_quotes";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return dbNumRows($result);
		
		  
	}
	catch(Exception $e)
	{
	}
	
}	



function insertAQuote($quote){
	try
	{
		$quote=clean_data($quote);
		$quote = ucwords(strtolower($quote));
		if(validateForNull($quote))
		{
			$sql="INSERT INTO 
				ems_quotes (quote)
				VALUES ('$quote')";
		$result=dbQuery($sql);
		return "success";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}



	



function updateQuote($id,$quote)
{
	
	try
	{
		
	}
	catch(Exception $e)
	{
	}
	
}	


function getQuoteById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT quote_id, quote
			  FROM ems_quotes
			  WHERE quote_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function getCurrentQuoteDate()
{
	
	try
	{
		
		$sql="SELECT quote_date
			  FROM ems_quotes_settings";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		
	}
	catch(Exception $e)
	{
	}
	
}


function getCurrentQuoteCounter()
{
	
	try
	{
		
		$sql="SELECT quote_counter
			  FROM ems_quotes_settings";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		
	}
	catch(Exception $e)
	{
	}
	
}

function increaseTheQuoteCounter()
{
	$current_quote_counter = getCurrentQuoteCounter();
	$total_quote_counter = totalQuotes();
	
	
	
	if($current_quote_counter > $total_quote_counter)
	{
		
		
		$sql="UPDATE ems_quotes_settings
			  SET quote_counter=1";
		echo $sql;
		dbQuery($sql);
	}
	else
	{
		$current_quote_counter = $current_quote_counter+1;
		$sql="UPDATE ems_quotes_settings
			  SET quote_counter=$current_quote_counter";
		dbQuery($sql);
	}
	
}

function changeTheQuoteDate()
{
	$todaysDate = getTodaysDate();
	
	{
		$sql="UPDATE ems_quotes_settings
			  SET quote_date='$todaysDate'";
		dbQuery($sql);
	}
	
	
}



function changeTheQuote()
{
	$todaysDate = getTodaysDate();
	$lastChangeDate = getCurrentQuoteDate();
     
    if(strtotime($todaysDate)>strtotime($lastChangeDate))
    {
     increaseTheQuoteCounter();
	 changeTheQuoteDate();
    }

}

function getQuoteByCurrentQuoteId($current_quote_id)
{
	
	try
	{
		if(checkForNumeric($current_quote_id))
		{
		$sql="SELECT quote
			  FROM ems_quotes
			  WHERE quote_id=$current_quote_id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}



?>