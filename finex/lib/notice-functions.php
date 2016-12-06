<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("customer-functions.php");
require_once("guarantor-functions.php");
require_once("common.php");
require_once("bd.php");


function listNoticesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT notice_id, notice_date, customer_name, customer_address, guarantor_name, guarantor_address, bucket, bucket_amount, file_id, created_by, last_modified_by, date_added, date_modified, notice_stage, reg_ad, received, received_date, not_received_type, notice_type FROM fin_loan_notice LEFT JOIN fin_reg_ad_not_received_types ON fin_loan_notice.not_received_type_id = fin_reg_ad_not_received_types.not_received_type_id  WHERE file_id=$file_id ORDER BY notice_date DESC";
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

function listUnreceivedNoticesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT notice_id, notice_date, customer_name, customer_address, guarantor_name, guarantor_address, file_id, notice_type, reg_ad, received, created_by,  date_added, not_received_type FROM fin_loan_notice INNER JOIN fin_reg_ad_not_received_types ON fin_loan_notice.not_received_type_id = fin_reg_ad_not_received_types.not_received_type_id WHERE file_id=$file_id AND received=2 ORDER BY notice_date DESC";
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


function getNumberOfNoticesForFileID($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT count(notice_id) FROM fin_loan_notice WHERE file_id=$file_id ";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	
}

function getLatestNoticeDateForFile($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT notice_date FROM fin_loan_notice WHERE file_id=$file_id ORDER BY notice_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	}

function insertNotice($file_id,$notice_date,$customer_name,$customer_address,$guarantor_name, $guarantor_address ,$bucket=NULL,$bucket_amount=NULL,$note="",$notice_stage=0,$reg_ad="",$received=0,$received_date="01/01/1970",$bulk_notice_id="NULL",$advocate_id=NULL,$notice_type=0){
	
	try
	{
		
		if(!validateForNull($bulk_notice_id) || !checkForNumeric($bulk_notice_id))
		$bulk_notice_id="NULL";
		
		if(!validateForNull($advocate_id) || !checkForNumeric($advocate_id))
		$advocate_id="NULL";
		
		if(!checkForNumeric($advocate_id) && $notice_stage==2)
		return "error";
		
		 $loan_id=getLoanIdFromFileId($file_id);
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 $customer_name=clean_data($customer_name);
		 $customer_address=clean_data($customer_address);
         $guarantor_name_name=clean_data($guarantor_name);
		 $guarantor_address=clean_data($guarantor_address);
		 $notice_date=clean_data($notice_date);
		 $note = clean_data($note);
		 if(!validateForNull($bucket))
		 $bucket=getBucketForLoan($loan_id); 
		 
		  if(!validateForNull($bucket_amount))
	     $bucket_amount=getTotalBucketAmountForLoan($loan_id);
		 
		 $bucket=clean_data($bucket);
		 $bucket_amount=clean_data($bucket_amount);
		 
		 if(!validateForNull($note))
		 $note="";
		 
		 if(!validateForNull($reg_ad))
		 $reg_ad = "";
		 if(!checkForNumeric($received))
		 $received=0;
		
		if(!checkForNumeric($notice_type))
		 $notice_type=0;
		 if(checkForNumeric($file_id) && validateForNull($customer_name,$customer_address,$notice_date))
		 {
		$customer_address='<pre>'.$customer_address.'</pre>';
		$guarantor_address='<pre>'.$guarantor_address.'</pre>';
		$notice_date = str_replace('/', '-', $notice_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$notice_date = date('Y-m-d',strtotime($notice_date)); // converts date to Y-m-d format
		
		$received_date = str_replace('/', '-', $received_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$received_date = date('Y-m-d',strtotime($received_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_loan_notice(notice_date,  customer_name, customer_address, guarantor_name, guarantor_address, bucket, bucket_amount, file_id, note, created_by, last_modified_by, date_added, date_modified, notice_stage, reg_ad, received, received_date,bulk_notice_id,advocate_id,notice_type) VALUES ('$notice_date', '$customer_name', '$customer_address', '$guarantor_name', '$guarantor_address', '$bucket', '$bucket_amount', $file_id , '$note', $admin_id, $admin_id, NOW(), NOW(), $notice_stage, '$reg_ad', $received, '$received_date',$bulk_notice_id,$advocate_id,$notice_type)";
	
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteNotice($id){
	
	try
	{
		if(checkForNumeric($id))
		{
			$sql="DELETE FROM fin_loan_notice WHERE notice_id=$id";
			dbQuery($sql);
			return "success";
			}
		return "error";	
	}
	catch(Exception $e)
	{
	}
	
}	

function updateNotice($notice_id,$reg_ad="",$received=0,$not_received_type_id=NULL,$received_date="01/01/1970"){
	
	try
	{
		
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$reg_ad = clean_data($reg_ad);
		
		 if(!validateForNull($not_received_type_id) || $received!=2)
		 $not_received_type_id="NULL";
		 
		 if(!validateForNull($received_date))
		 $received_date=getTodaysDate();
		 
		$received_date = str_replace('/', '-', $received_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$received_date = date('Y-m-d',strtotime($received_date)); // converts date to Y-m-d format
		 if(checkForNumeric($notice_id,$received) && validateForNull($reg_ad))
		 {
		$sql="UPDATE fin_loan_notice SET reg_ad = '$reg_ad', received = $received, not_received_type_id = $not_received_type_id, received_date = '$received_date', last_modified_by=$admin_id,  date_modified = NOW() WHERE notice_id = $notice_id";
		
		dbQuery($sql);
		return "success";	
		 }
	}
	catch(Exception $e)
	{
	}
	
}	

function getNoticeById($id){
	
	try
	{
	
		if(checkForNumeric($id))
		{
			$sql="SELECT notice_id, notice_date, customer_name, customer_address, guarantor_name, guarantor_address, bucket, bucket_amount, file_id, note, created_by, last_modified_by, date_added, date_modified, notice_stage, reg_ad, received, received_date, not_received_type, fin_loan_notice.not_received_type_id, advocate_id FROM fin_loan_notice LEFT JOIN fin_reg_ad_not_received_types ON fin_loan_notice.not_received_type_id = fin_reg_ad_not_received_types.not_received_type_id  WHERE notice_id=$id";
			
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

function getLatestNoticeDateForFileId($file_id,$type=0)
{
	if(!checkForNumeric($type))
	$type=0;
	
	if(checkForNumeric($file_id,$type))
	{
		$sql="SELECT MAX(notice_date) FROM fin_loan_notice WHERE file_id = $file_id AND notice_stage=$type GROUP BY file_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return false;
		
	}
	
}

function insertBulkNotices($file_id_array,$notice_stage=0,$advocate_id = NULL)
{
	
	if(is_array($file_id_array) && count($file_id_array)>0)
	{
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 if(checkForNumeric($notice_stage,$admin_id))
		 {
			 if($notice_stage==2 && !checkForNumeric($advocate_id))
			 return "error";
			 
			 
				$sql="INSERT INTO fin_bulk_notice (notice_stage,date_added,created_by) VALUES ($notice_stage,NOW(),$admin_id)";
				$result = dbQuery($sql);
				$bulk_notice_id = dbInsertId();
				foreach($file_id_array as $file_id)
				{
					
					$customer_id = getCustomerIdByFileId($file_id);
					
					$customer = getCustomerDetailsByCustomerId($customer_id);
					$guarantor = getGuarantorDetailsByCustomerId($customer_id);
					$loan_id=getLoanIdFromFileId($file_id);
					$bucket_details = getBucketDetailsForLoan($loan_id);
					$bucket=getBucketForLoan($loan_id);
					$bucket_amount=0;
					
					if(validateForNull($customer['secondary_customer_name']) && $customer['secondary_customer_name']!="NA")
					{
					$customer_name = $customer['secondary_customer_name'];
					}
					else
					$customer_name = $customer['customer_name'];
					
					if(validateForNull($customer['secondary_customer_address']) && $customer['secondary_customer_address']!="NA")
					$customer_address = $customer['secondary_customer_address'];
					else
					$customer_address = $customer['customer_address'];
					
					if(checkForNumeric($customer['customer_pincode']) && strlen($customer['customer_pincode']))
					$customer_address  = $customer_address." - ".$customer['customer_pincode'];
					
					if(validateForNull($guarantor['secondary_guarantor_name']) && $guarantor['secondary_guarantor_name']!="NA")
					$guarantor_name = $guarantor['secondary_guarantor_name'];
					else
					$guarantor_name = $guarantor['guarantor_name'];
					
					if(validateForNull($guarantor['secondary_guarantor_address']) && $guarantor['secondary_guarantor_address']!="NA")
					$guarantor_address = $guarantor['secondary_guarantor_address'];
					else
					$guarantor_address = $guarantor['guarantor_address'];
					
					if(checkForNumeric($guarantor['guarantor_pincode']) && strlen($guarantor['guarantor_pincode']))
					$guarantor_address  = $guarantor_address." - ".$guarantor['guarantor_pincode'];
					
					if(!validateForNull($guarantor_name))
					$guarantor_name="";
					
					if(!validateForNull($guarantor_address))
					$guarantor_address="";
					
					$bucket_amount = getTotalBucketAmountForLoan($loan_id);
					$note="";
					$reg_ad="";
					
					insertNotice($file_id,date('d/m/Y',strtotime(getTodaysDate())),$customer_name,$customer_address,$guarantor_name,$guarantor_address,$bucket,$bucket_amount,$note,$notice_stage,$reg_ad,0,"01/01/1970",$bulk_notice_id,$advocate_id,0);
					
					if(validateForNull($guarantor_name) && $guarantor!="error" && $notice_stage>0)
					{
							insertNotice($file_id,date('d/m/Y',strtotime(getTodaysDate())),$customer_name,$customer_address,$guarantor_name,$guarantor_address,$bucket,$bucket_amount,$note,$notice_stage,$reg_ad,0,"01/01/1970",$bulk_notice_id,$advocate_id,1);
						
					}
				
				
				}
		 }
	}
	return $bulk_notice_id;
}

function getNoticesForBulkNoticeId($bulk_notice_id)
{
	if(checkForNumeric($bulk_notice_id))
	{
		$sql="SELECT notice_id FROM fin_loan_notice WHERE bulk_notice_id = $bulk_notice_id AND notice_type=0";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$returnArray = array();
			foreach($resultArray as $re)
			{
				$returnArray[] = $re[0];
			}
			return $returnArray;
		}
	}
	return false;
	
}
?>