<?php
require_once('admin/lib/cg.php');
require_once('admin/lib/bd.php');
require_once('admin/lib/common.php');

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "sendInquiry")  
{
	$name=$_POST['name'];
	$email=$_POST['email'];
	$subject=$_POST['subject'];
	$inquiry = $_POST['message'];
	
	
	if(validateForNull($name,$email,$subject,$inquiry))
	{
	$message="Name: ".$name."\n Email: ".$email."\n Subject: ".$subject."\n Details: ".$inquiry;
	$headers="From:website@mahavirinterface.com";
	$to_mail = "mostpatel@gmail.com";
	mail($to_mail,"General Inquiry",$message,$headers);	
	$result = "success";
	}
	
	if($result=="success")
	{
		
	$_SESSION['ack']['msg']="Inquiry successfully Submitted! We will contact you shortly!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: contact_us.php?return=success");
	exit;
	}
	else
	{
	$_SESSION['ack']['msg']="Inquiry not Saved! Incorrect OR Insufficient Data!";
    $_SESSION['ack']['type']=2; // 1 for insert
	header("Location: contact_us.php?return=error");
	exit;	
	}
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "contact")  
{
	$name=$_POST['contact_firstname'];
	$email=$_POST['contact_emailaddress1'];
	$phone=$_POST['contact_mobile'];
	$inquiry=$_POST['contact_inquiry'];
	
	$result=insertContactInquiry($name,$email,$phone,$inquiry);
	
	if($result=="success")
	{
	$_SESSION['ack']['msg']="Inquiry successfully Submitted! We will contact you shortly!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: contact.php");
	exit;
	}
	else
	{
	$_SESSION['ack']['msg']="Inquiry not Saved! Incorrect OR Insufficient Data!";
    $_SESSION['ack']['type']=2; // 1 for insert
	header("Location: contact.php");
	exit;	
	}
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "air_tickets")  
{
	$name=$_POST['contact_firstname'];
	$email=$_POST['contact_emailaddress1'];
	$phone=$_POST['contact_mobile'];
	$booking_date = $_POST['contact_booking_date'];
	$destination = $_POST['contact_destination'];
	$location_type=$_POST['contact_flight_type'];
	$return_type=$_POST['contact_return_type'];
	$inquiry=$_POST['contact_inquiry'];
	$return_date = $_POST['contact_return_date'];
	$to_destination = $_POST['contact_to_destination'];
	$adult=$_POST['contact_adult'];
	$child=$_POST['contact_child'];
	
	$result=insertAirBookingInquiry($name,$email,$phone,$booking_date,$location_type,$destination,$to_destination,$inquiry,$return_type,$return_date,$adult,$child);
	
	
	
	
	if($result=="success")
	{
	$_SESSION['ack']['msg']="Inquiry successfully Submitted! We will contact you shortly!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: air_tickets.php");
	exit;
	}
	else
	{
	$_SESSION['ack']['msg']="Inquiry not Saved! Incorrect OR Insufficient Data!";
    $_SESSION['ack']['type']=2; // 1 for insert
	header("Location: air_tickets.php");
	exit;
	
	}
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "hotel")  
{
	$name=$_POST['contact_firstname'];
	$email=$_POST['contact_emailaddress1'];
	$phone=$_POST['contact_mobile'];
	$booking_date = $_POST['contact_booking_date'];
	$destination = $_POST['contact_destination'];
	$hotel_type=$_POST['contact_hotel_type'];
	$no_nights=$_POST['contact_number_of_nights'];
	$inquiry=$_POST['contact_inquiry'];
	$location_type=$_POST['contact_location_type'];
	$adult=$_POST['contact_adult'];
	$child=$_POST['contact_child'];
	
	
	
	$inquiry=$_POST['contact_inquiry'];
	
	$result=insertHotelBookingInquiry($name,$email,$phone,$booking_date,$location_type,$hotel_type,$destination,$no_nights,$inquiry,$adult,$child);
	
	
	if($result=="success")
	{
	$_SESSION['ack']['msg']="Inquiry successfully Submitted! We will contact you shortly!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: hotels.php");
	exit;
	}
	else
	{
	$_SESSION['ack']['msg']="Inquiry not Saved! Incorrect OR Insufficient Data!";
    $_SESSION['ack']['type']=2; // 1 for insert
	header("Location: hotels.php");
	exit;	
	}
	
	
	header("Location: hotels.php");
	exit;
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "visa")  
{
	$name=$_POST['contact_firstname'];
	$email=$_POST['contact_emailaddress1'];
	$phone=$_POST['contact_mobile'];
	$destination = $_POST['contact_destination'];
	$visa_type=$_POST['contact_visa_type'];
	$inquiry=$_POST['contact_inquiry'];
	
	$result=insertVisaInquiry($name,$email,$phone,$visa_type,$destination,$inquiry);
	
	if($result=="success")
	{
	$_SESSION['ack']['msg']="Inquiry successfully Submitted! We will contact you shortly!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: visa.php");
	exit;
	}
	else
	{
	$_SESSION['ack']['msg']="Inquiry not Saved! Incorrect OR Insufficient Data!";
    $_SESSION['ack']['type']=2; // 1 for insert
	header("Location: visa.php");
	exit;	
	}
	
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "feedback")  
{
	$name=$_POST['contact_firstname'];
	$email=$_POST['contact_emailaddress'];
	$phone=$_POST['contact_mobile'];
	$destination = $_POST['name_of_tour_you_travelled_on'];
	$booking_date = $_POST['contact_tour_date'];
	
	foreach($_POST as $key=>$value)
	{
	$key = str_replace('contact','',$key);
	$key = str_replace('_',' ',$key);
	$message =$message.$key." : ".$value." \n";
	}

	$headers="From:info@bhagwatiholidays.com";
	
	$to_mail = "info@bhagwatiholidays.com";
	$to_mail = "bhagwatitours@gmail.com";
	mail($to_mail,"FEEDBACK",$message,$headers);

	$_SESSION['ack']['msg']="Feedback successfully Submitted!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: feedback.php");
	exit;
	
}


 ?>