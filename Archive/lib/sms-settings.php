<?php
$url =  SMS_URL.'/SendSMS.aspx';
	
	if($type==1)
	{
	
	$message = 'Dear '. $customer_name.', Thanks for your Valuable inquiry. You have just interacted with '.$admin_name.'. For any other information, contact on '. $admin_number. ' or at '.$admin_email.'. For more packages, visit us at www.AkhilBharatTours.com';
	
	}
	
	
	
	else if($type==2)
	{
		
	$message = 'Dear '. $customer_name.', Hope you were satisfied with the information given by '.$admin_name.'. For any further clarification, contact on '. $admin_number. ' or at '.$admin_email.'. For more packages, visit us at www.AkhilBharatTours.com';
	
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
	
	
	/* $_GET Parameters to Send */
    $params = array('APIKEY' => APIKEY, 'MobileNo' => '91'.$customer_mobile_no, 'SenderID' => SENDERID, 'Message' => $message , 'ServiceName' => 'TEMPLATE_BASED');

?>