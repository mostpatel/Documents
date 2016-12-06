<?php
if($type=="delivery_status")
$url = 'http://smsapi.24x7sms.com/api_2.0/GetReportMsgID.aspx';
else
$url = 'http://smsapi.24x7sms.com/api_2.0/SendSMS.aspx';
	/* $type=2;
	$customer_name = "Jeet Patel";
	$reg_no = "GJ1FB5557";
	$actual_emi_date = "10/09/2015";
	$customer_mobile_no = 9824143009;
	$amount = 5500;
	$bucket=1; */
	if($type==1)
	{	
	$message = 'Dear '.$customer_name.', We have received a payment of '.$payment.' and the same has been credited to your account. Thank you.';
	
	}
	else if($type==2)
	{
	if(!isset($bucket) || !is_numeric($bucket))
	$bucket=0;	
	$message = 'Dear '. $customer_name.', Tamara 2-wheeler no '. $amount.' Rakam no Hapto '.$actual_emi_date.' tarikhe ave che, bank balance rakhvu. Balaji Fincorp. 9104524524';
	
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
	
	if($type=="delivery_status")
	{
		 $params = array('APIKEY' => 'a6caiqpW8Z4' ,'MsgID' => $msg_id, 'SenderID' => 'BALAJI');
		}
	else
	{
    /* $_GET Parameters to Send */
    $params = array('APIKEY' => '1' ,'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => 'BALAJI', 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');
	}
	/* $url .= '?' . http_build_query($params);
	
  echo $url;
    $ch = curl_init();

  
    curl_setopt($ch, CURLOPT_URL, $url);

  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   
    curl_setopt($ch, CURLOPT_HEADER, false);

  
    $data = curl_exec($ch);
	

	$dataArray = explode(':', $data);
	$messageId = $dataArray[1];
	
	echo $messageId;

   
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
  
    curl_close($ch);
   
    $returnAray = array();
    $returnAray[0] = $message;
    $returnAray[1] = $messageId;
    $returnAray[2] = $type;
   
   return $returnAray;    */

?>