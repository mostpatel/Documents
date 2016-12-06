<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("common.php");
require_once("bd.php");


function listSaleNoticesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT sale_notice_id, sale_notice_date, customer_name, customer_address, guarantor_name, guarantor_address, total_emis_paid, total_amount_paid, file_id, remarks, created_by, last_modified_by, date_added, date_modified FROM fin_sale_notice WHERE file_id=$file_id ORDER BY sale_notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfSaleNoticesForFileID($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT count(sale_notice_id) FROM fin_sale_notice WHERE file_id=$file_id ";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	
}

function getLatestSaleNoticeDateForFile($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT sale_notice_date FROM fin_sale_notice WHERE file_id=$file_id ORDER BY sale_notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	}

function insertSaleNotice($file_id,$sale_notice_date,$customer_name,$customer_address,$guarantor_name, $guarantor_address ,$total_emis_paid=null,$total_amount_paid=null,$remarks=null){
	
	try
	{
		 $loan_id=getLoanIdFromFileId($file_id);
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 $customer_name=clean_data($customer_name);
		 $customer_address=clean_data($customer_address);
$guarantor_name_name=clean_data($guarantor_name);
		 $guarantor_address=clean_data($guarantor_address);
		 $sale_notice_date=clean_data($sale_notice_date);
		 
		 if(!validateForNull($total_emis_paid))
		 $total_emis_paid=getTotalEmiPaidForLoan($loan_id); 
		 
		  if(!validateForNull($total_amount_paid))
	     $total_amount_paid=getTotalPaymentForLoan($loan_id);
		 
		 if(!validateForNull($remarks))
		 $remarks="";
		 $total_emis_paid=clean_data($total_emis_paid);
		 $total_amount_paid=clean_data($total_amount_paid);
		 
		 if(checkForNumeric($file_id) && validateForNull($customer_name,$customer_address,$sale_notice_date))
		 {
		$customer_address='<pre>'.$customer_address.'</pre>';

$guarantor_address='<pre>'.$guarantor_address.'</pre>';
		$sale_notice_date = str_replace('/', '-', $sale_notice_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$sale_notice_date = date('Y-m-d',strtotime($sale_notice_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_sale_notice(sale_notice_date,  customer_name, customer_address, guarantor_name, guarantor_address, total_emis_paid, total_amount_paid, file_id, remarks, created_by, last_modified_by, date_added, date_modified) VALUES ('$sale_notice_date', '$customer_name', '$customer_address', '$guarantor_name', '$guarantor_address', '$total_emis_paid', '$total_amount_paid', $file_id, '$remarks', $admin_id, $admin_id, NOW(), NOW())";
	
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteSaleNotice($id){
	
	try
	{
		if(checkForNumeric($id))
		{
			$sql="DELETE FROM fin_sale_notice WHERE sale_notice_id=$id";
			dbQuery($sql);
			return "success";
			}
		return "error";	
	}
	catch(Exception $e)
	{
	}
	
}	

function updateSaleNotice($sale_notice_id,$file_id,$sale_notice_date,$customer_name,$customer_address,$total_emis_paid,$total_amount_paid){
	
	try
	{
	}
	catch(Exception $e)
	{
	}
	
}	

function getSaleNoticeById($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
			$sql="SELECT sale_notice_id, sale_notice_date, customer_name, customer_address, guarantor_name, guarantor_address, total_emis_paid, total_amount_paid, file_id, remarks, created_by, last_modified_by, date_added, date_modified FROM fin_sale_notice WHERE sale_notice_id=$id";
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
?>