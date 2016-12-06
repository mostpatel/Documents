<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("customer-functions.php");
require_once("account-sales-functions.php");

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


function sendUpcomingServiceSMS($customer_name, $customer_mobile_no, $vehicle, $type)
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
	if(strpos($messageId,"Error"))
	return false;
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

function sendOutstandingToCustomers($customer_id_array)
{
	
	if(is_array($customer_id_array) && is_numeric($customer_id_array[0]))
	{
		foreach($customer_id_array as $customer_id)
		{
		
			$customer = getCustomerDetailsByCustomerId($customer_id);
			$customer_contact_no = $customer['contact_no'];
			$current_balance = getOpeningBalanceForLedgerForDate('C'.$customer['customer_id'],getNextDate(						getLatestTransactionDateForLedgerId('C'.$customer['customer_id'])));
			if($current_balance>0)
			{
				foreach($customer_contact_no as $contact_no)
				{
					sendOutstandingToCustomer($customer['customer_name'],$contact_no[0],$current_balance);
				}
			}
		}
	}
	
}

function sendOutstandingForInvoiceToCustomers($sales_id_array)
{
	
	if(is_array($sales_id_array) && is_numeric($sales_id_array[0]))
	{
		foreach($sales_id_array as $sales_id)
		{
			$sale=getSaleById($sales_id);
			$invoice_no = $sale['invoice_no'];
			$invoice_date = date('d/m/Y',strtotime($sale['trans_date']));
		    $receipt_amount = getReceiptAmountForSalesId($sales_id);
			$tax_amount = getTotalTaxForSalesId($sales_id);
			$remaining_amount = $sale['amount'] - $receipt_amount;
			if(is_numeric($tax_amount))
			$remaining_amount = $remaining_amount+$tax_amount;
			
			$amount= $remaining_amount;
			$customer_id = $sale['to_customer_id'];
			if(is_numeric($customer_id))
			{
				$customer = getCustomerDetailsByCustomerId($customer_id);
				$customer_contact_no = $customer['contact_no'];
				
				if($amount>0)
				{
					foreach($customer_contact_no as $contact_no)
					{
						sendOutstandingInvoiceAmountToCustomer($customer['customer_name'],$contact_no[0],$amount,$invoice_no,$invoice_date);
					}
				}
			}
		}
	}
	
}

function sendOutstandingToCustomer($customer_name,$customer_mobile_no,$amount)
{
	
	$type=6;
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
	if(strpos($messageId,"Error"))
	return false;
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


function sendOutstandingInvoiceAmountToCustomer($customer_name,$customer_mobile_no,$amount,$invoice_no,$invoice_date)
{
	
	$type=7;
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
	if(strpos($messageId,"Error"))
	return false;
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
?>