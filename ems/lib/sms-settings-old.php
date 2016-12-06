<?php
$url = 'http://smsapi.24x7sms.com/api_1.0/SendSMS.aspx';
	
	if($type==1)
	{
		
	$message = 'Dear '. $customer_name.', Thanks for your Valuable inquiry. You have just interacted with '.$admin_name.'. For any other information, contact on '. $admin_number. ' or at '.$admin_email.'. For more information, visit us at www.VijayBuildcon.com';
	
	}
	
	else if($type==2)
	{
	$message = 'Dear '. $customer_name.', Hope you were satisfied with the information given by '.$admin_name.'. For any further clarification, contact on '. $admin_number. '. For more info, visit us at www.VijayBuildcon.com.';
	}
	
	else if($type==3)
	{
	$message = 'Dear '. $customer_name.', Welcome to Vijay Buildcon. Thank you for booking with us and choosing us. We assure you to provide our best services. For any further clarification, contact on '. $admin_number. ' or at '.$admin_email.'.';
	
	}
	
	else if($type==4)
	{
	$message = 'Dear '. $customer_name.", We deeply regret that your booking could not be finalised with us. It will be our pleasure to serve you in future. For more info visit www.VijayBuildcon.com";
	}
	
	else if($type==5)
	{
	$message = 'Dear '. $customer_name.", Hope you have enjoyed your Holidays and you were satisfied with our services. We welcome your valuable feedback at feedback@akhilbharat.in.";
	
	}
	
	else if($type==6)
	{
	$message = 'Dear '.$customer_name.', thanks for visiting our Project Site and we hope that you had a great time visiting it. For any further info, contact '.$admin_name.' on '.$admin_number.'. For more info, visit us at www.VijayBuildcon.com.';
	}
	
	/* $_GET Parameters to Send */
    $params = array('EmailID' => 'lileshbhai',  'Password' => '26871370', 'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => 'SIDDHI', 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');

?>