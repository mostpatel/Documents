<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("customer-functions.php");

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


function sendDirectorSMS($customer_id_array)
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
				  
		  $url = 'http://smsapi.24x7sms.com/api_1.0/SendSMS.aspx';
	
	        $suffix = "Director (Vijay Buildcon)";
			$message = 'Dear '.$customer_name.', Thank you so much for visiting my Project Siddhi Vinayak Elegance 4 & 5 BHK Bungalow. If you have any kind of query, let me inform anytime. Once again thank you for your support. - Lilesh Chaudhary : 09898570011 '.$suffix;
			
			
			/* $_GET Parameters to Send */
			$params = array('EmailID' => 'lileshbhai',  'Password' => '26871370', 'MobileNo' => '91'.$no[0], 'SenderID' => 'SIDDHI', 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');
		
			
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
   
			}
		}
		
	}
	
}
	catch(Exception $e)
	{
	}
	
	return "success";
}

function sendanSMSTemp($mobile_no,$message)
{
	
	
	$url =  SMS_URL.'/SendSMS.aspx';

    /* $_GET Parameters to Send */
    
	$params = array('APIKEY' => APIKEY, 'MobileNo' => '91'.$mobile_no, 'SenderID' => SENDERID, 'Message' => $message , 'ServiceName' => 'PROMOTIONAL_HIGH');

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
	
}


function sendNewLeadSMS($customer_name, $customer_mobile_no, $admin_name, $admin_number, $admin_email, $type, $sub_cat_id)
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



function getSMSStatusDetails($message_id)
{
	
	
	
	$url = SMS_URL.'/GetReportMsgID.aspx';

    /* $_GET Parameters to Send */
    $params = array('APIKEY' => APIKEY, 'MsgID' => $message_id, 'SenderID' => SENDERID);

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
    
      
	
	// Returns WAITING FOR REPORT as the status. So DELIVERY DATE-TIME and status can not be fetched immediately. Hmm. What to do?
    
	
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
	
	$url = SMS_URL.'/BalanceCheck.aspx';

    /* $_GET Parameters to Send */
	
	if($type==1)
	{
    $params = array('APIKEY' => APIKEY, 'ServiceName' => 'PROMOTIONAL_HIGH');
	}
	
	else if($type==2)
	{
	$params = array('APIKEY' => APIKEY, 'ServiceName' => 'TEMPLATE_BASED');	
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

function sendSiteAddress($customer_name, $address, $customer_mobile_no)
{
	
	
	
	$address = "Nr. Fun Republic, S.G Road, ISKCON Flyover, Ramdevnagar, Ahmedabad-15. Phone:079-40223333";
	
	$url = SMS_URL.'/SendSMS.aspx';
	
	$message = 'Dear '.$customer_name.', in response to your enquiry, here is the address of our site: '.$address;
	
	/* $_GET Parameters to Send */
    $params = array('APIKEY' => APIKEY, 'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => SENDERID, 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');

	
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
   
    return "success";
}


function sendCustomSMSFromDBWrapper($custom_sms_id, $customer_id)
{
	
	$message = getSMSMessageById($custom_sms_id);
	
	
	$customer = getCustomerById($customer_id);
				
				
				$prefix_id = $customer['prefix_id'];
				$prefixDetails = getPrefixById($prefix_id);
				$customer_prefix = $prefixDetails['prefix'];
				$contact_nos = getCustomerContactNo($customer_id);
	            
				foreach($contact_nos as $contact_no)
				{
				
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
					{
				 sendCustomSMSFromDB($customer_prefix." ".$customer['customer_name'], $message, $contact_no[0]);
				    }
				}
	
	
	
}


function sendCustomSMSFromDB($customer_name, $message, $customer_mobile_no)
{
	
	//$url = SMS_URL.'/SendSMS.aspx';
	
	$url = 'http://46.4.112.58/api_1.0/SendSMS.aspx';
	
	$message = 'Dear '.$customer_name.', '.$message;
	
	echo $message;
	
	/* $_GET Parameters to Send */
	
   // $params = array('APIKEY' => APIKEY, 'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => SENDERID, 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');
	
	 $params = array('EmailID' => 'akhilbharatems@gmail.com',  'Password' => '6478', 'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => 'ABTTPL', 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');

	
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
   
    return "success";
}

?>