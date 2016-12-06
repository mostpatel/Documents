<?php
require_once('admin/lib/cg.php');
require_once('admin/lib/bd.php');
require_once('admin/lib/common.php');
require_once('admin/lib/inquiry-functions.php');

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "start_journey")  
{
	$name=$_POST['name'];
	$email=$_POST['email'];
	$phone=$_POST['mobile'];
	$booking_date = $_POST['booking_date'];
	$destination = $_POST['destination'];
	$inquiry=$_POST['inquiry'];
	$no_nights=$_POST['number_of_nights'];
	$no_rooms=$_POST['no_of_rooms'];
	
	
	$result=insertStartJourneyInquiry($name,$email,$phone,$booking_date,$destination,$no_nights,$inquiry,$no_rooms);
	
	
	if($result=="success")
	{
	$_SESSION['ack']['msg']="Inquiry successfully Submitted! We will contact you shortly!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: start_journey.php");
	exit;
	}
	else
	{
	$_SESSION['ack']['msg']="Inquiry not Saved! Incorrect OR Insufficient Data!";
    $_SESSION['ack']['type']=2; // 1 for insert
	header("Location: start_journey.php");
	exit;	
	}
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == "contact")  
{
	$name=$_POST['name'];
	$email=$_POST['email'];
	$phone=$_POST['mobile'];
	$inquiry=$_POST['description'];
	
	$result=insertContactInquiry($name,$email,$phone,$inquiry);
	
	if($result=="success")
	{
	$_SESSION['ack']['msg']="Inquiry successfully Submitted! We will contact you shortly!";
    $_SESSION['ack']['type']=1; // 1 for insert
	header("Location: ".$_SERVER['HTTP_REFERERc']);
	exit;
	}
	else
	{
	$_SESSION['ack']['msg']="Inquiry not Saved! Incorrect OR Insufficient Data!";
    $_SESSION['ack']['type']= 2; // 1 for insert
	header("Location: ".$_SERVER['HTTP_REFERER']);
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
	$name=$_POST['name'];
	$email=$_POST['email'];
	$phone=$_POST['mobile'];
	$booking_date = $_POST['booking_date'];
	$destination = $_POST['destination'];
	$inquiry=$_POST['inquiry'];
	$no_nights=$_POST['number_of_nights'];
	$no_rooms=$_POST['no_of_rooms'];
	$inquiry=$_POST['contact_inquiry'];

	$result=insertHotelBookingInquiry($name,$email,$phone,$booking_date,$destination,$no_nights,$inquiry,$no_rooms);
	
	
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