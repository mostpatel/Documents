<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listRasidBooks(){
	
	try
	{
		$sql="SELECT *
		      FROM fin_rasid_book 
			  LEFT JOIN fin_our_company ON fin_rasid_book.oc_id = fin_our_company.our_company_id
			  LEFT JOIN fin_agency ON fin_rasid_book.oc_id = fin_agency.agency_id
			  ORDER BY given_date";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfRasidBooks()
{
	$sql="SELECT count(rasid_book_id)
		      FROM fin_rasid_book
			  ORDER BY vehicle_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertRasidBook($book_no,$rasid_no_from,$rasid_no_to,$agency_id,$given_to,$given_date){
	
	try
	{
		$book_no=clean_data($book_no);
		$original_agency_id=$agency_id;
	$our_company_id=NULL;
	$type=substr($agency_id,0,2);
		$agency_id=substr($agency_id,2);
		
if($type=="ag")
{
$agency_id=$agency_id;
$our_company_id="NULL";
}
else if($type=="oc")
{
$our_company_id=$agency_id;
$agency_id="NULL";	
}
if(isset($given_date) && validateForNull($given_date))
{
	    $given_date = str_replace('/', '-', $given_date);
		$given_date=date('Y-m-d',strtotime($given_date));
	}
		if(validateForNull($book_no,$given_date,$given_to) && checkForNumeric($rasid_no_from,$rasid_no_to))
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_rasid_book
		      (`book_no`, `rasid_no_from`, `radid_no_to`, `oc_id`, `agency_id`, `given_to`, `given_date`, `date_added`, `date_modified`, `created_by`, `last_updated_by`)
			  VALUES
			  ('$book_no',$rasid_no_from,$rasid_no_to,$our_company_id,$agency_id,'$given_to','$given_date',NOW(),NOW(),$admin_id,$admin_id)";
			  
		dbQuery($sql);	  
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



function deleteRasidBook($id){
	
	try
	{
		if(checkForNumeric($id) )
		{
		$sql="DELETE FROM fin_rasid_book
		      WHERE rasid_book_id=$id";
		dbQuery($sql);
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

function updateRasidBook($id,$from){
	
	try
	{
	
if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
		
		if(validateForNull($from) )
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
			
		$sql="UPDATE `fin_rasid_book` SET `date_modified`=NOW(),`last_updated_by`=$admin_id, received_date = '$from' WHERE  rasid_book_id=$id";
		
		dbQuery($sql);	  
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

function getRasidBookById($id){
	
	try
	{
		$sql="SELECT *
		      FROM fin_rasid_book
			  LEFT JOIN fin_our_company ON fin_rasid_book.oc_id = fin_our_company.our_company_id
			  LEFT JOIN fin_agency ON fin_rasid_book.oc_id = fin_agency.agency_id
			  WHERE rasid_book_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
?>