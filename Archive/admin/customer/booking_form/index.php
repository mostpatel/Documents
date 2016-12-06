<?php
require_once "../../../lib/cg.php";
require_once "../../../lib/bd.php";
require_once "../../../lib/common.php";
require_once "../../../lib/city-functions.php";
require_once "../../../lib/sub-category-functions.php";
require_once "../../../lib/category-functions.php";
require_once "../../../lib/super-category-functions.php";
require_once "../../../lib/customer-type-functions.php";
require_once "../../../lib/adminuser-functions.php";
require_once "../../../lib/lead-functions.php";
require_once "../../../lib/enquiry-functions.php";
require_once "../../../lib/customer-functions.php";
require_once "../../../lib/report-functions.php";
require_once "../../../lib/rel-subcat-enquiry-functions.php";
require_once "../../../lib/decline-reasons-functions.php";
require_once "../../../lib/prefix-functions.php";
require_once "../../../lib/quantity-functions.php";
require_once "../../../lib/rel-attribute-functions.php";
require_once "../../../lib/customer-extra-details-functions.php";
require_once "../../../lib/member-functions.php";
require_once "../../../lib/booking-form-functions.php";



if(isset($_SESSION['EMSadminSession']['admin_rights']))
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if(isset($_GET['view']))
{
	if($_GET['view']=='add')
	{
		$showTitle = false;
		$content="editBookingForm.php";
	}
	else if($_GET['view']=='details')
	{
		$content="details.php";
		}
	else if($_GET['view']=='edit')
	{
		$content="edit.php";
	}
	
	else if($_GET['view']=='bookingForm')
	{
		$showTitle = false;
		$content="list_add.php";
	}
	
	else if($_GET['view']=='page2')
	{
		$showTitle = false;
		$content="page2.php";
	}
		
	else
	{
		$showTitle = false;
		$content="editBookingForm.php";
	}	
}
else
{
	$showTitle = false;
		$content="editBookingForm.php";
}		
if(isset($_GET['action']))
{
	if($_GET['action']=='editBookingForm')
	{
		if(isset($_SESSION['EMSadminSession']['admin_rights']) && (in_array(2,$admin_rights) || in_array(7,					$admin_rights)))
			{
				
		   
		/*   echo "booking_date : "." ".$_POST['booking_date']. " ". "<br>", 
			"tourName : "." ".$_POST['tourName']. " ". "<br>", 
			"tourCode : "." ".$_POST['tourCode']. " ". "<br>", 
			"purchase_date : "." ".$_POST['purchase_date']. " ". "<br>", 
			"days : "." ".$_POST['days']. " ". "<br>",
			
			"prefix_id : "." ".$_POST['prefix_id']. " ". "<br>", 
			"customer_name : "." ".$_POST['customer_name']. " ". "<br>", 
			"residence_address : "." ".$_POST['residence_address']. " ". "<br>", 
			"customerContact : "." ".$_POST['customerContact']. " ". "<br>", 
			"email : "." ".$_POST['email']. " ". "<br>", 
			"business_address : "." ".$_POST['business_address']. " ". "<br>", 
			
			"ltc : "." ".$_POST['ltc']. " ". "<br>", 
			"double_bed : "." ".$_POST['double_bed']. " ". "<br>", 
			"extra_matress : "." ".$_POST['extra_matress']. " ". "<br>",
			 
			"full_tickets : "." ".$_POST['full_tickets']. " ". "<br>", 
			"extra_person : "." ".$_POST['extra_person']. " ". "<br>", 
			"half_with_seat : "." ".$_POST['half_with_seat']. " ". "<br>", 
			"half_without_seat : "." ".$_POST['half_without_seat']. " ". "<br>", 
			"infant : "." ".$_POST['infant']. " ". "<br>",
			 
			"dob_customer : "." ".$_POST['dob_customer']. " ". "<br>",
			"customer_nationality : "." ".$_POST['customer_nationality']. " ". "<br>", 
			"gender : "." ".$_POST['gender']. " ". "<br>", 
			"human_proof_type_id : "." ".$_POST['human_proof_type_id']. " ". "<br>", 
			"proof_id : "." ".$_POST['proof_id']. " ". "<br>", */
			
			/*echo "member_id : "." ".$_POST['member_id']. " ". "<br>", 
			"member_name : "." ".$_POST['member_name']. " ". "<br>", 
			"dob_member : "." ".$_POST['dob_member_array']. " ". "<br>", 
			"member_nationality : "." ".$_POST['member_nationality_array']. " ". "<br>", 
			"member_gender : "." ".$_POST['member_gender']. " ". "<br>", 
			"memberProof : "." ".$_POST['memberProofArray']. " ". "<br>", 
			"member_proof_id : "." ".$_POST['member_proof_id']. " ". "<br>"; */
			
		   
			
			/* "food_instructions : "." ".$_POST['food_instructions']. " ". "<br>",
			"hotel_instructions : "." ".$_POST['hotel_instructions']. " ". "<br>",
			"extra_hotel_instructions : "." ".$_POST['extra_hotel_instructions']. " ". "<br>",
			"reccoBy : "." ".$_POST['reccoBy']. " ". "<br>",
			
			"departure_mode : "." ".$_POST['departure_mode']. " ". "<br>",
			"departure_date : "." ".$_POST['departure_date']. " ". "<br>",
			"departure_no : "." ".$_POST['departure_no']. " ". "<br>",
			"departure_company_type : "." ".$_POST['departure_company_type']. " ". "<br>",
			
			"middle_mode : "." ".$_POST['middle_mode']. " ". "<br>",
			"middle_date : "." ".$_POST['middle_date']. " ". "<br>",
			"middle_no : "." ".$_POST['middle_no']. " ". "<br>",
			"middle_company_type : "." ".$_POST['middle_company_type']. " ". "<br>",
			
			"return_mode : "." ".$_POST['return_mode']. " ". "<br>",
			"return_date : "." ".$_POST['return_date']. " ". "<br>",
			"return_no : "." ".$_POST['return_no']. " ". "<br>",
			"return_company_type : "." ".$_POST['return_company_type']. " ". "<br>",
			
			"total_amount : "." ".$_POST['total_amount']. " ". "<br>",
			"total_receiptNo : "." ".$_POST['total_receiptNo']. " ". "<br>",
			"total_date : "." ".$_POST['total_date']. " ". "<br>",
			"total_tax_percentage : "." ".$_POST['total_tax_percentage']. " ". "<br>",
			"total_taxAmount : "." ".$_POST['total_taxAmount']. " ". "<br>",
			"total_finalAmount : "." ".$_POST['total_finalAmount']. " ". "<br>",
			
			"extra_vehicle_amount : "." ".$_POST['extra_vehicle_amount']. " ". "<br>",
			"extra_vehicle_receiptNo : "." ".$_POST['extra_vehicle_receiptNo']. " ". "<br>",
			"extra_vehicle_date : "." ".$_POST['extra_vehicle_date']. " ". "<br>",
			"extra_vehicle_tax_percentage : "." ".$_POST['extra_vehicle_tax_percentage']. " ". "<br>",
			"extra_vehicle_taxAmount : "." ".$_POST['extra_vehicle_taxAmount']. " ". "<br>",
			"extra_vehicle_finalAmount : "." ".$_POST['extra_vehicle_finalAmount']. " ". "<br>",
			
			"extra_hotel_amount : "." ".$_POST['extra_hotel_amount']. " ". "<br>",
			"extra_hotel_receiptNo : "." ".$_POST['extra_hotel_receiptNo']. " ". "<br>",
			"extra_hotel_date : "." ".$_POST['extra_hotel_date']. " ". "<br>",
			"extra_hotel_tax_percentage : "." ".$_POST['extra_hotel_tax_percentage']. " ". "<br>",
			"extra_hotel_taxAmount : "." ".$_POST['extra_hotel_taxAmount']. " ". "<br>",
			"extra_hotel_finalAmount : "." ".$_POST['extra_hotel_finalAmount']. " ". "<br>",
			
			"extra_air_amount : "." ".$_POST['extra_air_amount']. " ". "<br>",
			"extra_air_receiptNo : "." ".$_POST['extra_air_receiptNo']. " ". "<br>",
			"extra_air_date : "." ".$_POST['extra_air_date']. " ". "<br>",
			"extra_air_tax_percentage : "." ".$_POST['extra_air_tax_percentage']. " ". "<br>",
			"extra_air_taxAmount : "." ".$_POST['extra_air_taxAmount']. " ". "<br>",
			"extra_air_finalAmount : "." ".$_POST['extra_air_finalAmount']. " ". "<br>",
			
			"rr_amount : "." ".$_POST['rr_amount']. " ". "<br>",
			"rr_receiptNo : "." ".$_POST['rr_receiptNo']. " ". "<br>",
			
			"grand_taxAmount : "." ".$_POST['grand_taxAmount']. " ". "<br>",
			"grand_finalAmount : "." ".$_POST['grand_finalAmount']. " ". "<br>",
			
			"booked_by : "." ".$_POST['booked_by']. " ". "<br>",
			"rr_cnf : "." ".$_POST['rr_cnf']. " ". "<br>",
			"checked_by : "." ".$_POST['checked_by']. " ". "<br>",
			"computerEntry : "." ".$_POST['computerEntry']. " ". "<br>";
			
			print_r($_POST['customerContact']);
			print_r($_POST['member_id']); 
			print_r($_POST['member_name']);
			print_r($_POST['dob_member_array']);
			echo "1st DOB = ".$_POST['dob_member_array'][0]. " ". "<br>";
			print_r($_POST['member_nationality_array']);
			echo "1st member Nationality = ".$_POST['member_nationality_array'][0]. " ". "<br>";
			print_r($_POST['member_gender']);
			echo "1st member Gender = ".$_POST['member_gender'][0]. " ". "<br>";
			print_r($_POST['memberProofArray']);
			echo "1st member Proof = ".$_POST['memberProofArray'][0]. " ". "<br>";
			print_r($_POST['member_proof_id']);
			echo "1st member Proof Id = ".$_POST['member_proof_id'][0]. " ". "<br>";
			 
			*/
			
		if(validateForNull($_POST['booking_form_id']))
		{
			
			$result = updateBookingForm($_POST['booking_date'],
			$_POST['tourName'], 
			$_POST['tourCode'], 
			$_POST['purchase_date'], 
			$_POST['days'],
			
			$_POST['prefix_id'], 
			$_POST['customer_name'], 
			$_POST['residence_address'], 
			$_POST['customerContact'], 
			$_POST['email'], 
			$_POST['business_address'], 
			
			$_POST['ltc'], 
			$_POST['double_bed'], 
			$_POST['extra_matress'],
			 
			$_POST['full_tickets'], 
			$_POST['extra_person'], 
			$_POST['half_with_seat'], 
			$_POST['half_without_seat'], 
			$_POST['infant'],
			 
			$_POST['dob_customer'],
			$_POST['customer_nationality'], 
			$_POST['gender'], 
			$_POST['human_proof_type_id'], 
			$_POST['proof_id'],
			
			$_POST['member_id'], 
			$_POST['member_name'], 
			$_POST['dob_member_array'], 
			$_POST['member_nationality_array'], 
			$_POST['member_gender'], 
			$_POST['memberProofArray'], 
			$_POST['member_proof_id'],
			$_POST['member_phone'], 
			
			$_POST['food_instructions'],
			$_POST['hotel_instructions'],
			$_POST['extra_hotel_instructions'],
			$_POST['reccoBy'],
			
			$_POST['departure_mode'],
			$_POST['departure_date'],
			$_POST['departure_no'],
			$_POST['departure_company_type'],
			
			$_POST['middle_mode'],
			$_POST['middle_date'],
			$_POST['middle_no'],
			$_POST['middle_company_type'],
			
			$_POST['return_mode'],
			$_POST['return_date'],
			$_POST['return_no'],
			$_POST['return_company_type'],
			
			$_POST['total_amount'],
			$_POST['total_receiptNo'],
			$_POST['total_date'],
			$_POST['total_tax_percentage'],
			$_POST['total_taxAmount'],
			$_POST['total_finalAmount'],
			
			$_POST['extra_vehicle_amount'],
			$_POST['extra_vehicle_receiptNo'],
			$_POST['extra_vehicle_date'],
			$_POST['extra_vehicle_tax_percentage'],
			$_POST['extra_vehicle_taxAmount'],
			$_POST['extra_vehicle_finalAmount'],
			
			$_POST['extra_hotel_amount'],
			$_POST['extra_hotel_receiptNo'],
			$_POST['extra_hotel_date'],
			$_POST['extra_hotel_tax_percentage'],
			$_POST['extra_hotel_taxAmount'],
			$_POST['extra_hotel_finalAmount'],
			
			$_POST['extra_air_amount'],
			$_POST['extra_air_receiptNo'],
			$_POST['extra_air_date'],
			$_POST['extra_air_tax_percentage'],
			$_POST['extra_air_taxAmount'],
			$_POST['extra_air_finalAmount'],
			
			$_POST['rr_amount'],
			$_POST['rr_receiptNo'],
			
			$_POST['grand_taxAmount'],
			$_POST['grand_finalAmount'],
			
			$_POST['booked_by'],
			$_POST['rr_cnf'],
			
			$_POST['checked_by'],
			$_POST['computerEntry'], 
			
			$_POST['customer_id'], 
			$_POST['enquiry_form_id'],
			$_POST['booking_form_id']);
			
		}
		
		else
		{
			$result = insertBookingForm($_POST['booking_date'],
			$_POST['tourName'], 
			$_POST['tourCode'], 
			$_POST['purchase_date'], 
			$_POST['days'],
			
			$_POST['prefix_id'], 
			$_POST['customer_name'], 
			$_POST['residence_address'], 
			$_POST['customerContact'], 
			$_POST['email'], 
			$_POST['business_address'], 
			
			$_POST['ltc'], 
			$_POST['double_bed'], 
			$_POST['extra_matress'],
			 
			$_POST['full_tickets'], 
			$_POST['extra_person'], 
			$_POST['half_with_seat'], 
			$_POST['half_without_seat'], 
			$_POST['infant'],
			 
			$_POST['dob_customer'],
			$_POST['customer_nationality'], 
			$_POST['gender'], 
			$_POST['human_proof_type_id'], 
			$_POST['proof_id'],
			
			$_POST['member_id'], 
			$_POST['member_name'], 
			$_POST['dob_member_array'], 
			$_POST['member_nationality_array'], 
			$_POST['member_gender'], 
			$_POST['memberProofArray'], 
			$_POST['member_proof_id'],
			$_POST['member_phone'], 
			
			$_POST['food_instructions'],
			$_POST['hotel_instructions'],
			$_POST['extra_hotel_instructions'],
			$_POST['reccoBy'],
			
			$_POST['departure_mode'],
			$_POST['departure_date'],
			$_POST['departure_no'],
			$_POST['departure_company_type'],
			
			$_POST['middle_mode'],
			$_POST['middle_date'],
			$_POST['middle_no'],
			$_POST['middle_company_type'],
			
			$_POST['return_mode'],
			$_POST['return_date'],
			$_POST['return_no'],
			$_POST['return_company_type'],
			
			$_POST['total_amount'],
			$_POST['total_receiptNo'],
			$_POST['total_date'],
			$_POST['total_tax_percentage'],
			$_POST['total_taxAmount'],
			$_POST['total_finalAmount'],
			
			$_POST['extra_vehicle_amount'],
			$_POST['extra_vehicle_receiptNo'],
			$_POST['extra_vehicle_date'],
			$_POST['extra_vehicle_tax_percentage'],
			$_POST['extra_vehicle_taxAmount'],
			$_POST['extra_vehicle_finalAmount'],
			
			$_POST['extra_hotel_amount'],
			$_POST['extra_hotel_receiptNo'],
			$_POST['extra_hotel_date'],
			$_POST['extra_hotel_tax_percentage'],
			$_POST['extra_hotel_taxAmount'],
			$_POST['extra_hotel_finalAmount'],
			
			$_POST['extra_air_amount'],
			$_POST['extra_air_receiptNo'],
			$_POST['extra_air_date'],
			$_POST['extra_air_tax_percentage'],
			$_POST['extra_air_taxAmount'],
			$_POST['extra_air_finalAmount'],
			
			$_POST['rr_amount'],
			$_POST['rr_receiptNo'],
			
			$_POST['grand_taxAmount'],
			$_POST['grand_finalAmount'],
			
			$_POST['booked_by'],
			$_POST['rr_cnf'],
			
			$_POST['checked_by'],
			$_POST['computerEntry'], 
			
			$_POST['customer_id'], 
			$_POST['enquiry_form_id']);
			
		}
			
			
			
				if(is_numeric($result))
				{
				$_SESSION['ack']['msg']="Booking Form submitted successfully!";
				$_SESSION['ack']['type']=1; // 1 for insert
				header("Location: ".WEB_ROOT."admin/customer/booking_form/index.php?view=bookingForm&id=".$result);
				exit;
				}
				else
				{
					
				$_SESSION['ack']['msg']="Invalid Input OR Duplicate Entry!";
				$_SESSION['ack']['type']=4; // 4 for error
				header("Location: ".$_SERVER['PHP_SELF']);
				exit;
				}
				
				
			}
			else
			{	
					$_SESSION['ack']['msg']="Authentication Failed! Not enough access rights!";
					$_SESSION['ack']['type']=5; // 5 for access
					header("Location: ".$_SERVER['PHP_SELF']);
					exit;
			}
		}
		
}

?>

<?php

$selectedLink="newCustomer";
$jsArray=array("jquery.validate.js","customerDatePicker.js", "bootstrap-select.js", "attributeDropDownForReports.js", "createDropDown.js", "generateContactNoCustomer.js","generateProductPurchase.js",  "validators/bookingForm.js");
$cssArray=array("bootstrap-select.css", "bp.css", "jquery-ui.css", "abttpl_booking_form.css");
$pathLinks=array("Home","Registration Form","Manage Locations");
require_once "../../../inc/template.php";
 ?>