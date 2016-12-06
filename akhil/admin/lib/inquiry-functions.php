<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once('inquiry-location-type-functions.php');
require_once('inquiry-package-type-functions.php');
require_once('inquiry-hotel-type-functions.php');
require_once('inquiry-visa-type-functions.php');

function insertContactInquiry($name,$email,$phone,$inquiry="NA")
{
	
	if(validateForNull($name) && (validateForNull($email) || (checkForNumeric($phone) && strlen($phone)==10)))
	{
	$name=clean_data($name);
	$email=clean_data($email);
	$phone=clean_data($phone);
	$inquiry=clean_data($inquiry);	
	
	if(validateForNull($phone) && !checkForNumeric($phone) && strlen($phone)!=10)
	$phone=0;
		
	$sql="INSERT INTO trl_contact_form (name, mobile_no, email, message, date_added) VALUES ('$name',$phone, '$email', '$inquiry', NOW())";	
	dbQuery($sql);
	$message="Name: ".$name."\n Email: ".$email."\n Phone: ".$phone."\n Details: ".$inquiry;
	$headers="From:inquiry@akhilbharattours.com";
	
	$to_mail = "mostpatel@gmail.com";
	
	mail($to_mail,"General Inquiry",$message,$headers);	
	return "success";	
	}
	return "error";
	
}

function insertStartJourneyInquiry($name,$email,$phone,$booking_date="01/01/1970",$destination="NA",$no_nights=1,$inquiry="NA",$no_of_rooms=1)
{
	
	
	 
	 if(!validateForNull($booking_date))
	 $booking_date="01/01/1970";
	 
	 
	 if(!validateForNull($destination))
	 $destination="NA";
	
	 
	 
	 if(!validateForNull($inquiry))
	 $inquiry="NA"; 
	 
	
	
	if(validateForNull($name) && checkForNumeric($no_of_rooms,$no_nights) && (validateForNull($email) || (checkForNumeric($phone) && strlen($phone)==10)))
	{
	
		if(isset($booking_date) && validateForNull($booking_date))
			{
		    $booking_date = str_replace('/', '-', $booking_date);
			$booking_date=date('Y-m-d',strtotime($booking_date));
			}	
		
		
	$name=clean_data($name);
	$email=clean_data($email);
	$phone=clean_data($phone);
	$inquiry=clean_data($inquiry);	
	$destination=clean_data($destination);	
	
	$no_nights=clean_data($no_nights);	
	
	$booking_date=clean_data($booking_date);	
	$no_of_rooms = clean_data($no_of_rooms);
	
	
	if(validateForNull($phone) && !checkForNumeric($phone) && strlen($phone)!=10)
	$phone=0;
		
	$sql="INSERT INTO trl_start_journey (name, mobile_no, email, message,  booking_date, destination, no_of_nights, date_added, no_of_rooms) VALUES ('$name',$phone, '$email', '$inquiry', '$booking_date' , '$destination' , $no_nights, NOW(), $no_of_rooms )";	
	
	dbQuery($sql);
	
	
	
	$message="Name: ".$name."\n Email: ".$email."\n Phone: ".$phone."\n Booking Date: ".$booking_date."\n No Of Nights: ".$no_nights."\n Destination: ".$destination."\n No of Rooms: ".$no_of_rooms."\n Details: ".$inquiry;
	$headers="From:packages@akhilbharattours.com";
	

	$to_mail = "mostpatel@gmail.com";
	
	mail($to_mail,"Package Inquiry",$message,$headers);	
	return "success";	
	}
	return "error";
	
}

function insertHotelBookingInquiry($name,$email,$phone,$booking_date="01/01/1970",$destination="NA",$no_nights=1,$inquiry="NA",$no_of_rooms)
{
	
	

	 if(!validateForNull($inquiry))
	 $inquiry="NA"; 
	 

	
	if(validateForNull($name,$booking_date,$destination) && checkForNumeric($no_nights,$no_of_rooms) && (validateForNull($email) || (checkForNumeric($phone) && strlen($phone)==10)))
	{
	
		if(isset($booking_date) && validateForNull($booking_date))
			{
		    $booking_date = str_replace('/', '-', $booking_date);
			$booking_date=date('Y-m-d',strtotime($booking_date));
			}	
		
		
	$name=clean_data($name);
	$email=clean_data($email);
	$phone=clean_data($phone);
	$inquiry=clean_data($inquiry);	
	$destination=clean_data($destination);	
	
	$no_nights=clean_data($no_nights);	
	
	$booking_date=clean_data($booking_date);
	$no_of_rooms = clean_data($no_of_rooms);
	
	if(validateForNull($phone) && !checkForNumeric($phone) && strlen($phone)!=10)
	$phone=0;
		
	$sql="INSERT INTO trl_hotel_booking (name, mobile_no, email, message,  booking_date, destination, no_of_nights, date_added , no_of_rooms) VALUES ('$name',$phone, '$email', '$inquiry', '$booking_date' , '$destination' , $no_nights, NOW(), $no_of_rooms )";	
	dbQuery($sql);
	
	
	
	$message="Name: ".$name."\n Email: ".$email."\n Phone: ".$phone."\n Booking Date: ".$booking_date."\n Destination: ".$destination."\n No of Rooms: ".$no_of_rooms."\n No Of Nights: ".$no_nights."\n Details: ".$inquiry;
	$headers="From:hotels@akhilbharattours.com";
	
	
	$to_mail = "mostpatel@gmail.com";

	mail($to_mail,"Hotel Booking Inquiry",$message,$headers);
		
	return "success";	
	}
	return "error";
	
}


function insertAirBookingInquiry($name,$email,$phone,$booking_date="01/01/1970",$loc_type=1,$destination="NA",$to_destination="NA",$inquiry="NA",$return_type=1,$return_date="01/01/1970",$adult,$child)
{
	
	 if(!validateForNull($booking_date))
	 $booking_date="01/01/1970";
	
	 if(!validateForNull($return_date))
	 $return_date="01/01/1970";
	 
	 if(!validateForNull($destination))
	 $destination="NA";
	
	 if(!validateForNull($to_destination))
	 $to_destination="NA"; 
	 
	 if(!validateForNull($inquiry))
	 $inquiry="NA"; 
	 
	 if($return_type==1)
	 $return_date="01/01/1970";
	
	if(validateForNull($name) && checkForNumeric($loc_type,$adult,$child) && (validateForNull($email) || (checkForNumeric($phone) && strlen($phone)==10)))
	{
	
		if(isset($booking_date) && validateForNull($booking_date))
			{
		    $booking_date = str_replace('/', '-', $booking_date);
			$booking_date=date('Y-m-d',strtotime($booking_date));
			}	
			
		if(isset($return_date) && validateForNull($return_date))
			{
		    $return_date = str_replace('/', '-', $return_date);
			$return_date=date('Y-m-d',strtotime($return_date));
			}		
		
		
	$name=clean_data($name);
	$email=clean_data($email);
	$phone=clean_data($phone);
	$inquiry=clean_data($inquiry);	
	$destination=clean_data($destination);	
	$loc_type=clean_data($loc_type);	
	$return_type=clean_data($return_type);	
	$booking_date=clean_data($booking_date);	
	$return_date=clean_data($return_date);		
	$adult = clean_data($adult);
	$child = clean_data($child);	
	
	if(validateForNull($phone) && !checkForNumeric($phone) && strlen($phone)!=10)
	$phone=0;
		
	$sql="INSERT INTO trl_air_booking (name, mobile_no, email, message,  booking_date, destination, to_destination, return_date, loc_type_id,  return_type, date_added, adult, child) VALUES ('$name',$phone, '$email', '$inquiry', '$booking_date' , '$destination' , '$to_destination' ,'$return_date', $loc_type, $return_type, NOW(),$adult,$child )";	
	dbQuery($sql);
	
	
	if($return_type==1)
	$return = "ONE WAY";
	else
	$return = "RETURN";

	
	$message="Name: ".$name."\n Email: ".$email."\n Phone: ".$phone."\n Departure Date: ".$booking_date."\n From: ".$destination."\n To: ".$to_destination."\n Adult: ".$adult."\n Child: ".$child;
	
	if($return_type==2)
	{
	$message=$message."\n Return Date: ".$return_date;
	}
	$message=$message."\n Flight Type: ".getInquiryLocTypeById($loc_type)."\n Ticket Type: ".$return."\n Details: ".$inquiry;
	
	
	$headers="From:info@bhagwatiholidays.com";
	
	if($loc_type==1)
	$to_mail = "bhagwati.ticket@gmail.com";
	else
	$to_mail = "bhagwati.ticket@gmail.com";
	

	mail($to_mail,"Air Tickets Inquiry",$message,$headers);	
	return "success";	
	}
	return "error";
	
}
function insertVisaInquiry($name,$email,$phone,$visa_type=1,$destination="NA",$inquiry="NA")
{
	
	
	 
		 
	 
	 if(!validateForNull($destination))
	 $destination="NA";
	
	  
	 if(!validateForNull($inquiry))
	 $inquiry="NA"; 
	 
	
	
	if(validateForNull($name) && checkForNumeric($visa_type) && (validateForNull($email) || (checkForNumeric($phone) && strlen($phone)==10)))
	{
	
		
		
		
	$name=clean_data($name);
	$email=clean_data($email);
	$phone=clean_data($phone);
	$inquiry=clean_data($inquiry);	
	$destination=clean_data($destination);	
	$visa_type=clean_data($visa_type);	

	
	if(validateForNull($phone) && !checkForNumeric($phone) && strlen($phone)!=10)
	$phone=0;
		
	$sql="INSERT INTO trl_visa_inquiry (name, mobile_no, email, destination, visa_type_id, message, date_added) VALUES ('$name',$phone, '$email', '$destination', $visa_type,'$inquiry', NOW() )";	
	
	dbQuery($sql);
	
	
	
	$message="Name: ".$name."\n Email: ".$email."\n Phone: ".$phone."\n Destination: ".$destination."\n Visa Type: ".getInquiryVisaTypeById($visa_type)."\n Details: ".$inquiry;
	$headers="From:info@bhagwatiholidays.com";
	
	$to_mail = "bhagwati.holiday@gmail.com";
	
	
	mail($to_mail,"Visa Inquiry",$message,$headers);	
	return "success";	
	}
	return "error";
	
}

function insertFeedbackInquiry($name,$email,$phone,$visa_type="general",$inquiry="NA")
{
	
	
	 if(!validateForNull($inquiry))
	 $inquiry="NA"; 
	 
	
	
	if(validateForNull($name) && checkForNumeric($visa_type) && (validateForNull($email) || (checkForNumeric($phone) && strlen($phone)==10)))
	{
	
		
	$message="Name: ".$name."\n Email: ".$email."\n Phone: ".$phone."\n Feedback Type: ".$visa_type."\n Details: ".$inquiry;
	
	$headers="From:info@bhagwatiholidays.com";
	

	
	
	mail("jasanisanket24@gmail.com","Visa Inquiry",$message,$headers);	
	return "success";	
	}
	return "error";
	
}

function listContactInquiries($from=NULL,$to=NULL)
{
		if(isset($from) && validateForNull($from))
			{
		    $from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
			}	
			
		if(isset($to) && validateForNull($to))
			{
		    $to = str_replace('/', '-', $to);
			$to=date('Y-m-d',strtotime($to));
			}		
	
	$sql="SELECT name, mobile_no, email, message, date_added FROM trl_contact_form WHERE 1 ";
	if(validateForNull($from) && validateMysqlDate($from))
	$sql=$sql." AND date_added>= '$from' ";
	if(validateForNull($to) && validateMysqlDate($to))
	$sql=$sql." AND date_added<= '$to 23:59:59' ";
	
	$result = dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;	
	}
	return false;
}

function listStartJourneyInquiries($from=NULL,$to=NULL,$location_type=-1)
{
		if(isset($from) && validateForNull($from))
			{
		    $from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
			}	
			
		if(isset($to) && validateForNull($to))
			{
		    $to = str_replace('/', '-', $to);
			$to=date('Y-m-d',strtotime($to));
			}		
	
	$sql="SELECT name, mobile_no, email, message, date_added, IF(booking_date!='1970-01-01',booking_date,'NA') as booking_date, destination,  loc_type, no_of_nights, package_type, date_added, adult, child FROM trl_start_journey, trl_inquiry_pck_type, trl_inquiry_loc_type WHERE trl_start_journey.loc_type_id = trl_inquiry_loc_type.loc_type_id AND trl_inquiry_pck_type.pck_type_id = trl_start_journey.pck_type_id ";
	if(validateForNull($from) && validateMysqlDate($from))
	$sql=$sql." AND date_added>= '$from' ";
	if(validateForNull($to) && validateMysqlDate($to))
	$sql=$sql." AND date_added<= '$to 23:59:59' ";
	if(validateForNull($location_type) && checkForNumeric($location_type) && $location_type>0)
	$sql=$sql." AND trl_start_journey.loc_type_id = $location_type ";
	
	$result = dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;	
	}
	return false;
}

function listHotelBookingInquiries($from=NULL,$to=NULL,$location_type=-1)
{
		if(isset($from) && validateForNull($from))
			{
		    $from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
			}	
			
		if(isset($to) && validateForNull($to))
			{
		    $to = str_replace('/', '-', $to);
			$to=date('Y-m-d',strtotime($to));
			}		
	
	$sql="SELECT name, mobile_no, email, message, date_added, IF(booking_date!='1970-01-01',booking_date,'NA') as booking_date, destination,  loc_type, no_of_nights, trl_inquiry_hotel_type.hotel_type, date_added, adult, child FROM trl_hotel_booking, trl_inquiry_hotel_type, trl_inquiry_loc_type WHERE trl_hotel_booking.loc_type_id = trl_inquiry_loc_type.loc_type_id AND trl_inquiry_hotel_type.hotel_type_id = trl_hotel_booking.hotel_type ";
	if(validateForNull($from) && validateMysqlDate($from))
	$sql=$sql." AND date_added>= '$from' ";
	if(validateForNull($to) && validateMysqlDate($to))
	$sql=$sql." AND date_added<= '$to 23:59:59' ";
	if(validateForNull($location_type) && checkForNumeric($location_type) && $location_type>0)
	$sql=$sql." AND trl_hotel_booking.loc_type_id = $location_type ";
	
	$result = dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;	
	}
	return false;
}

function listVisaInquiries($from=NULL,$to=NULL,$location_type=-1)
{
		if(isset($from) && validateForNull($from))
			{
		    $from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
			}	
			
		if(isset($to) && validateForNull($to))
			{
		    $to = str_replace('/', '-', $to);
			$to=date('Y-m-d',strtotime($to));
			}		
	
	$sql="SELECT name, mobile_no, email, message, date_added, destination,  visa_type FROM trl_visa_inquiry, trl_inquiry_visa_type WHERE trl_visa_inquiry.visa_type_id = trl_inquiry_visa_type.visa_type_id";
	if(validateForNull($from) && validateMysqlDate($from))
	$sql=$sql." AND date_added>= '$from' ";
	if(validateForNull($to) && validateMysqlDate($to))
	$sql=$sql." AND date_added<= '$to 23:59:59' ";
	if(validateForNull($location_type) && checkForNumeric($location_type) && $location_type>0)
	$sql=$sql." AND trl_visa_inquiry.visa_type_id = $location_type ";
	
	$result = dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;	
	}
	return false;
}

function listAirBookingInquiries($from=NULL,$to=NULL,$location_type=-1)
{
		if(isset($from) && validateForNull($from))
			{
		    $from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
			}	
			
		if(isset($to) && validateForNull($to))
			{
		    $to = str_replace('/', '-', $to);
			$to=date('Y-m-d',strtotime($to));
			}		
	
	$sql="SELECT name, mobile_no, email, message, date_added, IF(booking_date!='1970-01-01',booking_date,'NA') as booking_date,IF(return_type=2,IF(return_date!='1970-01-01',return_date,'NA'),'NA') as return_date, IF(return_type=2,'Return','One Way') as return_type, destination, to_destination, loc_type,  date_added, adult, child FROM trl_air_booking, trl_inquiry_loc_type WHERE trl_air_booking.loc_type_id = trl_inquiry_loc_type.loc_type_id  ";
	if(validateForNull($from) && validateMysqlDate($from))
	$sql=$sql." AND date_added>= '$from' ";
	if(validateForNull($to) && validateMysqlDate($to))
	$sql=$sql." AND date_added<= '$to 23:59:59' ";
	if(validateForNull($location_type) && checkForNumeric($location_type) && $location_type>0)
	$sql=$sql." AND trl_air_booking.loc_type_id = $location_type ";
	
	$result = dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;	
	}
	return false;
}
?>