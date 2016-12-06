<?php
if($type=="delivery_status")
$url = 'http://smsapi.24x7sms.com/api_2.0/GetReportMsgID.aspx';
else
$url = 'http://smsapi.24x7sms.com/api_2.0/SendSMS.aspx';
/*	$type=1;
	$customer_name = "Jeet Patel";
	$vehicle = "GJ1FB5557";
	$bill_amount = 10000;
	$customer_mobile_no = 9824143009; */
	if($type==1)
	{	
	$message = 'Dear '. $customer_name.', your vehicle '.$vehicle.' is ready after service. Your bill amount is '. $bill_amount." RS.";
	}
	else if($type>100)
	{
	$message =  $customer_name.', tamari '.$vehicle.' ni service no time thai gayo che, to service karava mate aje j mulakat lo, Brahmani Auto, 9879561230.';
	}
	else if($type==3)
	{
	$message = 'Dear '. $customer_name.', Welcome to Asarvawala Akhil Bharat Tours & Travels Pvt. Ltd. Thank you for booking with us and choosing us as your Memorable holidays provider. We assure you to provide our best services all the time. For any further clarification, contact on '. $admin_number. ' or at '.$admin_email.'.';
	}
	else if($type==4)
	{
	$message = 'Dear '. $customer_name.", We deeply regret that your tour could not be finalised with us. It will be our pleasure to serve you in future. For more packages visit www.AkhilBharatTours.com";
	}
	else if($type==5)
	{
	$message = 'Dear '. $customer_name.", Hope you have enjoyed your Holidays and you were satisfied with our services. We welcome your valuable feedback at feedback@akhilbharat.in.";
	}
	else if($type==6)
	{
	$message = 'Dear '. $customer_name.", Your outstanding amount with Brahmani Auto is ".$amount." Rs. Please Pay the amount as soon as possible. Ignore if paid.";
	}
	else if($type==7)
	{
	$message = 'Dear '. $customer_name.", unpaid amount of your Invoice No.  ".$invoice_no."  Dated ".$invoice_date." with Brahmani Auto is ".$amount." Rs. Please Pay the amount as soon as possible. Ignore if paid.";
	}
	
	if($type=="delivery_status")
	{
		 $params = array('APIKEY' => 'o7Kew7Dvd5v' ,'MsgID' => $msg_id, 'SenderID' => 'BRMANI');
		}
	else
	{
    /* $_GET Parameters to Send */
    if(SEND_SMS==1)
	$params = array('APIKEY' => 'o7Kew7Dvd5v' ,'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => 'BRMANI', 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');
	else
	$params = array('APIKEY' => '' ,'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => 'BRMANI', 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');
	
	}
/*	$url .= '?' . http_build_query($params);
	
  
    $ch = curl_init();

  
    curl_setopt($ch, CURLOPT_URL, $url);

  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   
    curl_setopt($ch, CURLOPT_HEADER, false);

  
    $data = curl_exec($ch);
	
	
	$dataArray = explode(':', $data);
	$messageId = $dataArray[1];
	
	

   
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
  
    curl_close($ch);
   
    $returnAray = array();
    $returnAray[0] = $message;
    $returnAray[1] = $messageId;
    $returnAray[2] = $type;
   
   return $returnAray;  */

?>