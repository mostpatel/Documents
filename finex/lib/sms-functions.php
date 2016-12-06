<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("customer-functions.php");
require_once("loan-functions.php");
require_once("sms-record-functions.php");

function sendFixSMS($sms, $customer_id_array)
{
try
{
	foreach($customer_id_array as $customer_id)
	{
		   $contact_no = getCustomerContactNo($customer_id);
		   $customerDetails = getCustomerById($customer_id);
		   $customer_name = $customerDetails['customer_name'];
		   $sms_send = "Dear". " ". $customer_name. ",". " ". $sms;
		
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				
				if(checkForNumeric($no[0]) && (preg_match('/^\d{10}$/', $no[0])))
				{
					
				  sendanSMSTemp($no[0], $sms_send);
				}
			}
		}
		
	}
	exit;
}
	catch(Exception $e)
	{
	}
}


function sendFeedbackSMS($customer_id_array)
{
	
	            $admin_id=$_SESSION['EMSadminSession']['admin_id'];
	            $adminDetails = getAdminUserByID($admin_id);
			    $admin_name = $adminDetails['admin_name'];
				$admin_email = $adminDetails['admin_email'];
				$admin_number = $adminDetails['admin_phone'];
try
{
	foreach($customer_id_array as $customer_id)
	
	
	{
             
 		   $contact_no = getCustomerContactNo($customer_id);
		   $customerDetails = getCustomerById($customer_id);
		   $customer_name = $customerDetails['customer_name'];
		   
	
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				
				if(checkForNumeric($no[0]) && (preg_match('/^\d{10}$/', $no[0])))
				{
				  sendNewLeadSMS($customer_name, $no[0], $admin_name, $admin_number, $admin_email, $type=5);
				}
			}
		}
		
	}
	
}
	catch(Exception $e)
	{
	}
}

function sendanSMSTemp($mobile_no,$message)
{
	
	$url = 'http://smsapi.24x7sms.com/api_1.0/SendSMS.aspx';

    /* $_GET Parameters to Send */
    
	$params = array('EmailID' => 'akhilbharatems@gmail.com',  'Password' => '6478', 'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => 'ALERTS', 'Message' => $message , 'ServiceName' => 'PROMOTIONAL_HIGH');

    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);

    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
	
	
	$dataArray = explode(':', $data);
	$messageId = $dataArray[0];
	$mobile_no = $dataArray[1];
    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
    /* Close cURL Resource */
    curl_close($ch);
	
}


function sendNewLeadSMS($customer_name, $customer_mobile_no, $admin_name, $admin_number, $admin_email, $type)
{
	
	
		
	
	require('sms-settings.php');
	
    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);
	
    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
	
	
	$dataArray = explode(':', $data);
	$messageId = $dataArray[1];
	
	

    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
    /* Close cURL Resource */
    curl_close($ch);
   
    $returnAray = array();
    $returnAray[0] = $message;
    $returnAray[1] = $messageId;
    $returnAray[2] = $type;
   
   return $returnAray;
	
}

function sendSMSForEmiPaymentId($emi_payment_id)
{
		$emi_id = getEMIIDFromPaymentId($emi_payment_id);
		$loan_id = getLoanIdFromEmiId($emi_id);
		$customer_id=getCustomerIdFromLoanId($loan_id);
					$customer = getCustomerDetailsByCustomerId($customer_id);
					$rasid_no=getRasidNoForPayment($emi_payment_id);	
					$payment_amount=getTotalAmountForRasidNo($rasid_no,$loan_id,$emi_payment_id);
					$customer_contact_nos = $customer['contact_no'];
					foreach($customer_contact_nos as $contact_no) 
					sendPaymentReceivedSMS($customer['customer_name'],$contact_no[0],$payment_amount,1,$emi_payment_id);
	
}
function sendFinalizeJobCardSMS($customer_name, $customer_mobile_no, $vehicle, $bill_amount, $type)
{
	
	
	if($customer_mobile_no!="9999999999")	
	{
	require('sms-settings.php');
	
    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);
	
    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
	echo $data;
	if(!in_array($data,array('INVALID username or password','INVALID PARAMETERS','INVALID SenderID','Invalid API Key','INSUFFICIENT_CREDIT')))
	{
	$dataArray = explode(':', $data);
	$messageId = $dataArray[1];
	$mobile_no = $dataArray[2];
	$type = 1;
	}
	else 
	return false;
    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
    /* Close cURL Resource */
    curl_close($ch);
   
    $returnAray = array();
    $returnAray[0] = $message;
    $returnAray[1] = $messageId;
    $returnAray[2] = $type;
   
   return $returnAray;
	}
	else return false;
}



function sendPaymentReceivedSMS($customer_name, $customer_mobile_no, $payment, $type,$emi_payment_id)
{
	
	
	if($customer_mobile_no!="9999999999")	
	{
	require('sms-settings.php');
	
    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);
	
    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
	
	if(!in_array($data,array('INVALID username or password','INVALID PARAMETERS','INVALID SenderID','Invalid API Key','INSUFFICIENT_CREDIT')))
	{
	$dataArray = explode(':', $data);
	$messageId = $dataArray[1];
	$mobile_no = $dataArray[2];
	$type = 1;
	insertSMSRecord($messageId,1,$customer_mobile_no,$message,'1970-01-01 00:00:00',$emi_payment_id);
	}
	else 
	return false;
    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
    /* Close cURL Resource */
    curl_close($ch);
   
    $returnAray = array();
    $returnAray[0] = $message;
    $returnAray[1] = $messageId;
    $returnAray[2] = $type;
    
   return $returnAray;
	}
	else return false;
}

function sendPaymentPendingSMS($customer_name, $customer_mobile_no, $type,$loan_emi_id,$actual_emi_date,$amount,$reg_no,$bucket=false)
{

	
	if($customer_mobile_no!="9999999999")	
	{
	require('sms-settings.php');
	
    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);
	
    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);


    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
	
	if(!in_array($data,array('INVALID username or password','INVALID PARAMETERS','INVALID SenderID','Invalid API Key','INSUFFICIENT_CREDIT')))
	{
	$dataArray = explode(':', $data);
	$messageId = $dataArray[1];
	$mobile_no = $dataArray[2];
	$type = 1;
	if(validateForNull($messageId))
	insertSMSRecord($messageId,2,$customer_mobile_no,$message,'1970-01-01 00:00:00',$loan_emi_id);
	else return false;
	}
	else 
	return false;
    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
    /* Close cURL Resource */
    curl_close($ch);
   
    $returnAray = array();
    $returnAray[0] = $message;
    $returnAray[1] = $messageId;
    $returnAray[2] = $type;
    
   return $returnAray;
	}
	else return false;
}





function getSMSStatusDetails($message_id)
{
	
	
	
	$url = 'http://smsapi.24x7sms.com/api_1.0/GetReportMsgID.aspx';

    /* $_GET Parameters to Send */
    $params = array('EmailID' => 'akhilbharatems@gmail.com',  'Password' => '6478', 'MsgID' => $message_id, 'SenderID' => 'ABTTPL');

    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);
	
	
	

    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
	
	echo $data;
	
	$dataArray = explode(':', $data);
	
	$delivery_date_time = $dataArray[3];
	$stutus = $dataArray[4];
	

    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
    /* Close cURL Resource */
    curl_close($ch);
	
	$returnAray = array();
	
    $returnAray[0] = $delivery_date_time;
    $returnAray[1] = $stutus;
    
    print_r($returnAray);   
	
	// Returns WAITING FOR REPORT as the status. So DELIVERY DATE-TIME and status can not be fetched immediately. Hmm. What to do?
    exit;
	
    return $returnAray;
	
}

function insertSMSStatusDetails($enquiry_form_id, $message, $status, $message_type, $api_message_id, $msg_delivered_date_time)
{
	try
	{
		
		
		if(validateForNull($status, $enquiry_form_id))
		{
			$sql="INSERT INTO 
				ems_sms_sent_report (enquiry_form_id, message, message_type, api_message_id, msg_delivered_date_time, status)
				VALUES ($enquiry_form_id, '$message', '$status', $message_type, '$api_message_id', '$msg_delivered_date_time')";
				
		
			
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



function checkCredits($type)
{
	
	$url = 'http://smsapi.24x7sms.com/api_1.0/BalanceCheck.aspx';

    /* $_GET Parameters to Send */
	
	if($type==1)
	{
    $params = array('EmailID' => 'akhilbharatems@gmail.com',  'Password' => '6478', 'ServiceName' => 'PROMOTIONAL_HIGH');
	}
	
	else if($type==2)
	{
	$params = array('EmailID' => 'akhilbharatems@gmail.com',  'Password' => '6478', 'ServiceName' => 'TEMPLATE_BASED');	
	}
	
    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);

    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);
	

    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
    /* Close cURL Resource */
    curl_close($ch);
	
	$dataArray = explode(':', $data);
	$credits = $dataArray[1];
	
	return $credits;
}

function generateListOfLoanEmiIdsForSMS($days_before_today,$company_id=NULL,$agnecy_id=NULL)
{

	if(checkForNumeric($days_before_today))
	{
		
		$sms_date=getTodaysDateTimeAfterDays($days_before_today);
		$sms_date=date('Y-m-d',strtotime($sms_date));
		$from_sms_date = date('Y-m-d',strtotime($sms_date.'-4days'));
		$sql="SELECT fin_loan_emi.loan_emi_id,emi_amount,actual_emi_date,fin_loan_emi.loan_id,fin_customer.customer_id,(SELECT SUM(payment_amount) FROM fin_loan_emi_payment WHERE fin_loan_emi_payment.loan_emi_id=fin_loan_emi.loan_emi_id GROUP BY fin_loan_emi_payment.loan_emi_id) as received_amount, vehicle_reg_no FROM fin_loan_emi,fin_loan,fin_customer,fin_file LEFT JOIN fin_vehicle ON fin_vehicle.file_id = fin_file.file_id WHERE fin_file.file_id = fin_customer.file_id AND fin_loan.file_id=fin_file.file_id  AND fin_loan_emi.loan_id = fin_loan.loan_id AND file_status IN (1,5) AND sms_sent=0 AND actual_emi_date>='$from_sms_date' AND actual_emi_date<='$sms_date' ";
		if(validateForNull($company_id) && validateForNull($agnecy_id))
		$sql=$sql." AND (oc_id IN ($company_id)  OR agency_id IN ($agnecy_id)) ";
		else if(validateForNull($company_id))
		$sql=$sql." AND oc_id IN ($company_id) ";
		else if(validateForNull($agnecy_id))
		$sql=$sql." AND agency_id IN ($agnecy_id) ";
		$sql=$sql." AND fin_file.file_id NOT IN (SELECT file_id FROM fin_vehicle_seize) HAVING received_amount IS NULL OR (emi_amount-received_amount)>0 ";
		
		$result = dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray = dbResultToArray($result);
			
			foreach($resultArray as $re)
			{
				$customer_id = $re['customer_id'];
	
				$customer = getCustomerDetailsByCustomerId($customer_id);
				$file_id = $customer['file_id'];
				$bucket = getBucketForLoan(getLoanIdFromFileId($file_id));
				$vehicle_reg_no = $re['vehicle_reg_no'];
				if(!validateForNull($vehicle_reg_no))
				$vehicle_reg_no = 'ni loan';
				foreach($customer['contact_no'] as $contact)
				{
				$return_array=sendPaymentPendingSMS($customer['customer_name'],$contact[0],2,$re['loan_emi_id'],date('d/m/Y',strtotime($re['actual_emi_date'])),$re['emi_amount'],$vehicle_reg_no,$bucket);
				
				if($return_array && validateForNull($return_array[1]))
				{
					$sql="UPDATE fin_loan_emi SET sms_sent=1 WHERE loan_emi_id = ".$re['loan_emi_id'];
					dbQuery($sql);
				}
				}
			}
			
		}
	}
}
?>