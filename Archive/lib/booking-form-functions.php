<?php 
require_once("cg.php");
require_once("customer-functions.php");
require_once("member-functions.php");
require_once("customer-extra-details-functions.php");
require_once("rel-booking-form-functions.php");
require_once("common.php");
require_once("bd.php");


	

function insertBookingForm($booking_date, $tourName, $tourCode, $purchase_date, $days, $prefix_id, $customer_name, $residence_address, $customerContact, $email, $business_address, $ltc, $double_bed, $extra_matress, $full_tickets, $extra_person, $half_with_seat, $half_without_seat, $infant, $dob_customer, $customer_nationality, $gender, $proof_type, $proof_id, $member_id, $member_name, $dob_member, $member_nationality, $member_gender, $member_proof_type, $member_proof_id, $member_phone, $food_instructions, $hotel_instructions, $extra_hotel_instructions, $reccoBy, $departure_mode, $departure_date, $departure_no, $departure_company_type, $middle_mode, $middle_date, $middle_no, $middle_company_type, $return_mode, $return_date, $return_no, $return_company_type, $total_amount, $total_receiptNo, $total_date, $total_tax_percentage, $total_taxAmount, $total_finalAmount, $extra_vehicle_amount, $extra_vehicle_receiptNo, $extra_vehicle_date, $extra_vehicle_tax_percentage, $extra_vehicle_taxAmount, $extra_vehicle_finalAmount, $extra_hotel_amount, $extra_hotel_receiptNo, $extra_hotel_date, $extra_hotel_tax_percentage, $extra_hotel_taxAmount, $extra_hotel_finalAmount, $extra_air_amount, $extra_air_receiptNo, $extra_air_date, $extra_air_tax_percentage, $extra_air_taxAmount, $extra_air_finalAmount, $rr_amount, $rr_receiptNo, $grand_taxAmount, $grand_finalAmount, $booked_by, $rr_cnf, $checked_by,$computerEntry, $customer_id, $enquiry_form_id)
{
	
	    
		$name=clean_data($name);
		$email=clean_data($email);
		$dob_customer=clean_data($dob_customer);
		$residence_address=clean_data($residence_address);
		
		$business_address = clean_data($business_address);
		$reccoBy=clean_data($reccoBy);
		$food_instructions=clean_data($food_instructions);
		$hotel_instructions=clean_data($hotel_instructions);
		$extra_hotel_instructions=clean_data($extra_hotel_instructions);
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$tourCode = strtoupper($tourCode);
		
		if(!validateForNull($email))
		$email="NA";
		
	    $customer_name = ucwords(strtolower($customer_name));
	    $dob_customer=str_replace('/','-',$dob_customer);
	    $dob_customer=date('Y-m-d',strtotime($dob_customer));
		
		if(!validateForNull($departure_date))
		$departure_date="1970-01-01 00:00:00";
		
		if(!validateForNull($middle_date))
		$middle_date="1970-01-01 00:00:00";
		
		if(!validateForNull($return_date))
		$return_date="1970-01-01 00:00:00";
		
		if(!validateForNull($departure_date))
		$departure_date="1970-01-01 00:00:00";
		
		if(!validateForNull($total_date))
		$total_date="1970-01-01 00:00:00";
		
		if(!validateForNull($extra_vehicle_date))
		$extra_vehicle_date="1970-01-01 00:00:00";
		
		if(!validateForNull($extra_hotel_date))
		$extra_hotel_date="1970-01-01 00:00:00";
		
		if(!validateForNull($extra_air_date))
		$extra_air_date="1970-01-01 00:00:00";
		
		if(!validateForNull($email))
		$email="NA";
		
		if(!validateForNull($business_address))
		$business_address= '';
		
		if(!validateForNull($food_instructions))
		$food_instructions='';
		
		if(!validateForNull($hotel_instructions))
		$hotel_instructions='';
		
		if(!validateForNull($extra_hotel_instructions))
		$extra_hotel_instructions='';
		
		if(!validateForNull($reccoBy))
		$reccoBy='';
		
		if(!validateForNull($total_receiptNo))
		$total_receiptNo='';
		
		if(!validateForNull($extra_vehicle_receiptNo))
		$extra_vehicle_receiptNo='';
		
		if(!validateForNull($extra_hotel_receiptNo))
		$extra_hotel_receiptNo='';
		
		if(!validateForNull($extra_air_receiptNo))
		$extra_air_receiptNo='';
		
		if(!validateForNull($departure_no))
		$departure_no='';
		
		if(!validateForNull($middle_no))
		$middle_no='';
		
		if(!validateForNull($return_no))
		$return_no='';
		
		if($departure_mode == -1)
		$departure_mode="NULL";
		
		if($departure_company_type == -1)
		$departure_company_type = "NULL";
		
		if($middle_mode == -1)
		$middle_mode="NULL";
		
		if($middle_company_type == -1)
		$middle_company_type="NULL";
		
		if($return_mode == -1)
		$return_mode="NULL";
		
		if($return_company_type == -1)
		$return_company_type="NULL";
		
		if(validateForNull($tourName))
		{
			$booking_date=str_replace('/','-',$booking_date);
	        $booking_date=date('Y-m-d',strtotime($booking_date));
			
			$purchase_date=str_replace('/','-',$purchase_date);
	        $purchase_date=date('Y-m-d',strtotime($purchase_date));
			
			$departure_date=str_replace('/','-',$departure_date);
	        $departure_date=date('Y-m-d',strtotime($departure_date));
			
			$middle_date=str_replace('/','-',$middle_date);
	        $middle_date=date('Y-m-d',strtotime($middle_date));
			
			$return_date=str_replace('/','-',$return_date);
	        $return_date=date('Y-m-d',strtotime($return_date));
			
			$total_date=str_replace('/','-',$total_date);
	        $total_date=date('Y-m-d',strtotime($total_date));
			
			$extra_vehicle_date=str_replace('/','-',$extra_vehicle_date);
	        $extra_vehicle_date=date('Y-m-d',strtotime($extra_vehicle_date));
			
			$extra_hotel_date=str_replace('/','-',$extra_hotel_date);
	        $extra_hotel_date=date('Y-m-d',strtotime($extra_hotel_date));
			
			$extra_air_date=str_replace('/','-',$extra_air_date);
	        $extra_air_date=date('Y-m-d',strtotime($extra_air_date));
			
			
			
			
			$sql="INSERT INTO ems_ab_booking_form 
			      (booking_date, tour_name, tour_code, tour_departure_date, tour_days, ltc_required, double_bed, extra_matress, full_tickets, extra_person, half_ticket_with_seat, half_ticket_without_seat, infant, instructions_food, instructions_hotel,
instructions_extra_hotel, recommended_by, departure_transport_mode, departure_transport_mode_date, departure_transport_mode_no,
departure_transport_mode_company, middle_transport_mode, middle_transport_mode_date, middle_transport_mode_no, middle_transport_mode_company, return_transport_mode, return_transport_mode_date, return_transport_mode_no,
return_transport_mode_company, total_amount_raw, total_amount_receipt, total_amount_date, total_amount_tax_percentage,
 total_amount_tax_amount, total_amount_after_tax, extra_vehicle_amount_raw, extra_vehicle_amount_receipt,
extra_vehicle_amount_date, extra_vehicle_amount_tax_percentage, extra_vehicle_amount_tax_amount, extra_vehicle_amount_after_tax,
extra_hotel_amount_raw, extra_hotel_amount_receipt, extra_hotel_amount_date, extra_hotel_amount_tax_percentage, 
 extra_hotel_amount_percentage_amount, extra_hotel_amount_after_tax, extra_air_amount_raw, extra_air_amount_receipt, 
 extra_air_amount_date, extra_air_amount_tax_percentage, extra_air_amount_tax_amount, extra_air_amount_after_tax, 
 discount_amount, discount_receipt_no, total_tax_amount, total_after_discount, booked_by, rr_cnf, checked_by, computer_entry, customer_id, enquiry_id, date_added, date_edited, modified_by)
				  
				  VALUES
				  
				  ('$booking_date', '$tourName', '$tourCode', '$purchase_date', $days, '$ltc', $double_bed, $extra_matress, $full_tickets, $extra_person, $half_with_seat, $half_without_seat, $infant, '$food_instructions', '$hotel_instructions',
'$extra_hotel_instructions', '$reccoBy', '$departure_mode', '$departure_date', '$departure_no', '$departure_company_type', '$middle_mode', '$middle_date', '$middle_no', '$middle_company_type', '$return_mode', '$return_date', '$return_no', '$return_company_type', $total_amount, '$total_receiptNo', '$total_date', $total_tax_percentage, $total_taxAmount, $total_finalAmount, $extra_vehicle_amount, 
'$extra_vehicle_receiptNo', '$extra_vehicle_date', $extra_vehicle_tax_percentage, $extra_vehicle_taxAmount, $extra_vehicle_finalAmount, 
$extra_hotel_amount, '$extra_hotel_receiptNo', '$extra_hotel_date', $extra_hotel_tax_percentage, $extra_hotel_taxAmount, $extra_hotel_finalAmount, $extra_air_amount, '$extra_air_receiptNo', '$extra_air_date', $extra_air_tax_percentage, $extra_air_taxAmount, $extra_air_finalAmount, $rr_amount, '$rr_receiptNo', $grand_taxAmount, $grand_finalAmount, $booked_by, $rr_cnf, $checked_by, '$computerEntry', $customer_id, $enquiry_form_id, NOW(), NOW(), $admin_id)";

  
       
		
		
		
		$result=dbQuery($sql);
		
		$booking_id = dbInsertId();
		
		
		updateCustomer($customer_id, $customer_name, $email, $customerContact, $prefix_id);
		
		$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);
		
		if(is_array($extraCustomerDetails) && $extraCustomerDetails['extra_details_id']>0)
		{
			
				$data_from_id = $extraCustomerDetails['data_from_id'];
				
				if(!validateForNull($data_from_id))
				{
					$data_from_id = "NULL";
				}
			
				$city_id = $extraCustomerDetails['city_id'];
				if(!validateForNull($city_id))
				{
					$city_id = "NULL";
				}
				
				
				$profession_id = $extraCustomerDetails['profession_id'];
				
				if(!validateForNull($profession_id))
				{
					$profession_id = "NULL";
				}
		
		        
				
				
		updateExtraCustomerDetails($customer_id, $dob_customer, $residence_address, $business_address, $profession_id, $data_from_id, $customer_nationality, $city_id,-1);
		
			
		}
		else
		{
		
		insertCustomerExtraDetails($dob_customer, $residence_address, $business_address, -1, -1, $customer_nationality,-1, -1,$customer_id);
		
		}
		
		if($proof_type>0 && $customer_id>0)
		{
			
			$proof_details_array=getOnlyCustomerProofDetailsByCustomerIdAndProofTypeId($customer_id,$proof_type);
		
			if($proof_details_array && $proof_details_array['human_proof_type_id']>0)
			{
				
				updateCustomerProofNumberByCustomerIdAndProofTypeId($customer_id, $proof_type, $proof_id);
			}
			else
			{
				
				addCustomerProof($customer_id,$customer_name,array($proof_type),array($proof_id),NULL,NULL,NULL);
			
				$proof_details_array=getOnlyCustomerProofDetailsByCustomerIdAndProofTypeId($customer_id,$proof_type);
				
			}
			$customer_proof_id = $proof_details_array['customer_proof_id'];
			
			insertRelBookingForm($customer_id,$mem_id,$customer_proof_id,$booking_id);
		}
		
		
		for($i=0;$i<count($member_id);$i++)
		{
			
			$mem_name = $member_name[$i];
			$mem_dob= $dob_member[$i];
			$mem_ph = $member_phone[$i];
			
			$mem_name=clean_data($mem_name);
		    $mem_dob=clean_data($mem_dob);
			
			$mem_name = ucwords(strtolower($mem_name));
			
			$mem_name = ucwords(strtolower($mem_name));
	        $mem_dob=str_replace('/','-',$mem_dob);
	        $mem_dob=date('Y-m-d',strtotime($mem_dob));
			$proof_type = $member_proof_type[$i];
			$proof_id = $member_proof_id[$i];
			if(validateForNull($mem_name, $mem_dob))
			{
			$mem_id = $member_id[$i];
			
					if($mem_id<0)
					{	
					$mem_id=insertMember($mem_name,"NA",array($mem_ph), -1, $mem_dob, $member_nationality[$i], $member_gender[$i], $customer_id);
					}
					else if($mem_id>0)
					{
			
						 $memberDetailsById = getMemberById($mem_id);
						
						 $email = $memberDetailsById['member_email'];
								 if(!validateForNull($email))
								{
								  $email = "NULL";
								}
								 
								 
								 if(!is_array($mem_ph) && !validateForNull($mem_ph[0]))
								{
									
								  $mem_ph = "NULL";
								}
								 $relation_id = $memberDetailsById['relation_id'];
								
								 if(!validateForNull($relation_id))
								{
									
								  $relation_id = "NULL";
								}
					
						 
								updateMember($mem_id, $mem_name, $email, array($mem_ph), $relation_id, $mem_dob, $gender, $mem_id, $customer_id);
			 
			 
					}
			}
			
			if($proof_type>0 && $mem_id>0)
			{
				$proof_details_array=getOnlyMemberProofDetailsByCustomerAndMemberIdAndProofTypeId($mem_id,$proof_type);
				if($proof_details_array && $proof_details_array['human_proof_type_id']>0)
				{
					updateMemberProofNumberByCustomerAndMemberIdAndProofTypeId($mem_id, $proof_type, $proof_id);
				}
				else
				{
					addCustomerProof($customer_id,$customer_name,array($proof_type),array($proof_id),NULL,NULL,$mem_id);
					$proof_details_array=getOnlyMemberProofDetailsByCustomerAndMemberIdAndProofTypeId($mem_id,$proof_type);
				}
				$customer_proof_id = $proof_details_array['customer_proof_id'];
				
				
		}
		else
		$customer_proof_id="NULL";
		
		insertRelBookingForm($customer_id, $mem_id, $customer_proof_id, $booking_id);
		
	}
	
	return $booking_id;	
}
		else
		{
			return "error";
		}	
	

}

function updateBookingForm($booking_date, $tourName, $tourCode, $purchase_date, $days, $prefix_id, $customer_name, $residence_address, $customerContact, $email, $business_address, $ltc, $double_bed, $extra_matress, $full_tickets, $extra_person, $half_with_seat, $half_without_seat, $infant, $dob_customer, $customer_nationality, $gender, $proof_type, $proof_id, $member_id, $member_name, $dob_member, $member_nationality, $member_gender, $member_proof_type, $member_proof_id, $member_phone, $food_instructions, $hotel_instructions, $extra_hotel_instructions, $reccoBy, $departure_mode, $departure_date, $departure_no, $departure_company_type, $middle_mode, $middle_date, $middle_no, $middle_company_type, $return_mode, $return_date, $return_no, $return_company_type, $total_amount, $total_receiptNo, $total_date, $total_tax_percentage, $total_taxAmount, $total_finalAmount, $extra_vehicle_amount, $extra_vehicle_receiptNo, $extra_vehicle_date, $extra_vehicle_tax_percentage, $extra_vehicle_taxAmount, $extra_vehicle_finalAmount, $extra_hotel_amount, $extra_hotel_receiptNo, $extra_hotel_date, $extra_hotel_tax_percentage, $extra_hotel_taxAmount, $extra_hotel_finalAmount, $extra_air_amount, $extra_air_receiptNo, $extra_air_date, $extra_air_tax_percentage, $extra_air_taxAmount, $extra_air_finalAmount, $rr_amount, $rr_receiptNo, $grand_taxAmount, $grand_finalAmount, $booked_by, $rr_cnf, $checked_by,$computerEntry, $customer_id, $enquiry_form_id, $booking_id)
{
	
	    
		$name=clean_data($name);
		$email=clean_data($email);
		$dob_customer=clean_data($dob_customer);
		$residence_address=clean_data($residence_address);
		$business_address = clean_data($business_address);
		$reccoBy=clean_data($reccoBy);
		$food_instructions=clean_data($food_instructions);
		$hotel_instructions=clean_data($hotel_instructions);
		$extra_hotel_instructions=clean_data($extra_hotel_instructions);
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$tourCode = strtoupper($tourCode);
		
		
		
	    $customer_name = ucwords(strtolower($customer_name));
	    $dob_customer=str_replace('/','-',$dob_customer);
	    $dob_customer=date('Y-m-d',strtotime($dob_customer));
		
		if(!validateForNull($departure_date))
		$departure_date="1970-01-01 00:00:00";
		
		if(!validateForNull($middle_date))
		$middle_date="1970-01-01 00:00:00";
		
		if(!validateForNull($return_date))
		$return_date="1970-01-01 00:00:00";
		
		if(!validateForNull($departure_date))
		$departure_date="1970-01-01 00:00:00";
		
		if(!validateForNull($total_date))
		$total_date="1970-01-01 00:00:00";
		
		if(!validateForNull($extra_vehicle_date))
		$extra_vehicle_date="1970-01-01 00:00:00";
		
		if(!validateForNull($extra_hotel_date))
		$extra_hotel_date="1970-01-01 00:00:00";
		
		if(!validateForNull($extra_air_date))
		$extra_air_date="1970-01-01 00:00:00";
		
		if(!validateForNull($email))
		$email="NA";
		
		if(!validateForNull($business_address))
		$business_address= '';
		
		if(!validateForNull($food_instructions))
		$food_instructions='';
		
		if(!validateForNull($hotel_instructions))
		$hotel_instructions='';
		
		if(!validateForNull($extra_hotel_instructions))
		$extra_hotel_instructions='';
		
		if(!validateForNull($reccoBy))
		$reccoBy='';
		
		if(!validateForNull($total_receiptNo))
		$total_receiptNo='';
		
		if(!validateForNull($extra_vehicle_receiptNo))
		$extra_vehicle_receiptNo='';
		
		if(!validateForNull($extra_hotel_receiptNo))
		$extra_hotel_receiptNo='';
		
		if(!validateForNull($extra_air_receiptNo))
		$extra_air_receiptNo='';
		
		if(!validateForNull($departure_no))
		$departure_no='';
		
		if(!validateForNull($middle_no))
		$middle_no='';
		
		if(!validateForNull($return_no))
		$return_no='';
		
		if($departure_mode == -1)
		$departure_mode="NULL";
		
		if($departure_company_type == -1)
		$departure_company_type = "NULL";
		
		if($middle_mode == -1)
		$middle_mode="NULL";
		
		if($middle_company_type == -1)
		$middle_company_type="NULL";
		
		if($return_mode == -1)
		$return_mode="NULL";
		
		if($return_company_type == -1)
		$return_company_type="NULL";
		
		if(validateForNull($tourName))
		{
			$booking_date=str_replace('/','-',$booking_date);
	        $booking_date=date('Y-m-d',strtotime($booking_date));
			
			
			$purchase_date=str_replace('/','-',$purchase_date);
	        $purchase_date=date('Y-m-d',strtotime($purchase_date));
			
			$departure_date=str_replace('/','-',$departure_date);
	        $departure_date=date('Y-m-d',strtotime($departure_date));
			
			$middle_date=str_replace('/','-',$middle_date);
	        $middle_date=date('Y-m-d',strtotime($middle_date));
			
			$return_date=str_replace('/','-',$return_date);
	        $return_date=date('Y-m-d',strtotime($return_date));
			
			$total_date=str_replace('/','-',$total_date);
	        $total_date=date('Y-m-d',strtotime($total_date));
			
			$extra_vehicle_date=str_replace('/','-',$extra_vehicle_date);
	        $extra_vehicle_date=date('Y-m-d',strtotime($extra_vehicle_date));
			
			$extra_hotel_date=str_replace('/','-',$extra_hotel_date);
	        $extra_hotel_date=date('Y-m-d',strtotime($extra_hotel_date));
			
			$extra_air_date=str_replace('/','-',$extra_air_date);
	        $extra_air_date=date('Y-m-d',strtotime($extra_air_date));
			
			
			
			
			$sql="UPDATE ems_ab_booking_form
			 
			      SET booking_date = '$booking_date', tour_name = '$tourName', tour_code = '$tourCode', tour_departure_date = '$purchase_date', tour_days = $days, ltc_required = '$ltc', double_bed = $double_bed, extra_matress = $extra_matress, full_tickets = $full_tickets, extra_person = $extra_person, half_ticket_with_seat = $half_with_seat, half_ticket_without_seat = $half_without_seat, infant = $infant, instructions_food = '$food_instructions', instructions_hotel = '$hotel_instructions', instructions_extra_hotel = '$extra_hotel_instructions', recommended_by = '$reccoBy', departure_transport_mode = '$departure_mode', departure_transport_mode_date = '$departure_date', departure_transport_mode_no = '$departure_no', departure_transport_mode_company = '$departure_company_type', middle_transport_mode = '$middle_mode', middle_transport_mode_date = '$middle_date', middle_transport_mode_no = '$middle_no', middle_transport_mode_company = '$middle_company_type', return_transport_mode = '$return_mode', return_transport_mode_date = '$return_date', return_transport_mode_no = '$return_no', return_transport_mode_company = '$return_company_type', total_amount_raw = $total_amount, total_amount_receipt = '$total_receiptNo', total_amount_date = '$total_date', total_amount_tax_percentage = $total_tax_percentage, total_amount_tax_amount = $total_taxAmount, total_amount_after_tax = $total_finalAmount, extra_vehicle_amount_raw = $extra_vehicle_amount, extra_vehicle_amount_receipt = '$extra_vehicle_receiptNo', extra_vehicle_amount_date = '$extra_vehicle_date', extra_vehicle_amount_tax_percentage = $extra_vehicle_tax_percentage, extra_vehicle_amount_tax_amount = $extra_vehicle_taxAmount, extra_vehicle_amount_after_tax = $extra_vehicle_finalAmount,
extra_hotel_amount_raw = $extra_hotel_amount, extra_hotel_amount_receipt = '$extra_hotel_receiptNo', extra_hotel_amount_date = '$extra_hotel_date', extra_hotel_amount_tax_percentage = $extra_hotel_tax_percentage,extra_hotel_amount_percentage_amount = $extra_hotel_taxAmount, extra_hotel_amount_after_tax = $extra_hotel_finalAmount, extra_air_amount_raw = $extra_air_amount, extra_air_amount_receipt =  '$extra_air_receiptNo', extra_air_amount_date = '$extra_air_date', extra_air_amount_tax_percentage = $extra_air_tax_percentage,extra_air_amount_tax_amount =  $extra_air_taxAmount,extra_air_amount_after_tax = $extra_air_finalAmount, discount_amount = $rr_amount, discount_receipt_no = '$rr_receiptNo',total_tax_amount = $grand_taxAmount, total_after_discount =  $grand_finalAmount, booked_by =   $booked_by, rr_cnf = $rr_cnf, checked_by = $checked_by, computer_entry = '$computerEntry', customer_id =  $customer_id, enquiry_id = $enquiry_form_id, date_added =   NOW(), date_edited = NOW(), modified_by =  $admin_id
				  
				  WHERE booking_id = $booking_id";
		
				  
        
		
		$result=dbQuery($sql);
		
		
		updateCustomer($customer_id, $customer_name, $email, $customerContact, $prefix_id);
		
		$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);
		
		if(is_array($extraCustomerDetails) && $extraCustomerDetails['extra_details_id']>0)
		{
			
				$data_from_id = $extraCustomerDetails['data_from_id'];
				
				if(!validateForNull($data_from_id))
				{
					$data_from_id = "NULL";
				}
			
				$city_id = $extraCustomerDetails['city_id'];
				if(!validateForNull($city_id))
				{
					$city_id = "NULL";
				}
				
				
				$profession_id = $extraCustomerDetails['profession_id'];
				
				if(!validateForNull($profession_id))
				{
					$profession_id = "NULL";
				}
		
		        
				
				
		updateExtraCustomerDetails($customer_id, $dob_customer, $residence_address, $business_address, $profession_id, $data_from_id, $customer_nationality, $city_id);
		
			
		}
		else
		{
		
		insertCustomerExtraDetails($dob_customer, $residence_address, $business_address, -1, -1, $customer_nationality,-1, $customer_id);
		
		}
		
		if($proof_type>0 && $customer_id>0)
		{
			
			$proof_details_array=getOnlyCustomerProofDetailsByCustomerIdAndProofTypeId($customer_id,$proof_type);
		
			if($proof_details_array && $proof_details_array['human_proof_type_id']>0)
			{
				
				updateCustomerProofNumberByCustomerIdAndProofTypeId($customer_id, $proof_type, $proof_id);
			}
			else
			{
				
				addCustomerProof($customer_id,$customer_name,array($proof_type),array($proof_id),NULL,NULL,NULL);
			
				$proof_details_array=getOnlyCustomerProofDetailsByCustomerIdAndProofTypeId($customer_id,$proof_type);
				
			}
			$customer_proof_id = $proof_details_array['customer_proof_id'];
			
			insertRelBookingForm($customer_id,$mem_id,$customer_proof_id,$booking_id);
		}
		
		deleteRelBookingForm($booking_id);
		for($i=0;$i<count($member_id);$i++)
		{
			
			$mem_name = $member_name[$i];
			$mem_dob= $dob_member[$i];
			$mem_ph = $member_phone[$i];
			
			$mem_name=clean_data($mem_name);
		    $mem_dob=clean_data($mem_dob);
			
			$mem_name = ucwords(strtolower($mem_name));
			
			$mem_name = ucwords(strtolower($mem_name));
	        $mem_dob=str_replace('/','-',$mem_dob);
	        $mem_dob=date('Y-m-d',strtotime($mem_dob));
			$proof_type = $member_proof_type[$i];
			$proof_id = $member_proof_id[$i];
			if(validateForNull($mem_name, $mem_dob))
			{
			$mem_id = $member_id[$i];
			
					if($mem_id<0)
					{	
					$mem_id=insertMember($mem_name,"NA",array($mem_ph), -1, $mem_dob, $member_nationality[$i], $member_gender[$i], $customer_id);
					}
					else if($mem_id>0)
					{
			
						 $memberDetailsById = getMemberById($mem_id);
						
						 $email = $memberDetailsById['member_email'];
								 if(!validateForNull($email))
								{
								  $email = "NULL";
								}
								 
								 
								 if(!is_array($mem_ph) && !validateForNull($mem_ph[0]))
								{
									
								  $mem_ph = "NULL";
								}
								 $relation_id = $memberDetailsById['relation_id'];
								
								 if(!validateForNull($relation_id))
								{
									
								  $relation_id = "NULL";
								}
					
						 
								updateMember($mem_id, $mem_name, $email, array($mem_ph), $relation_id, $mem_dob, $gender, $mem_id, $customer_id);
			 
			 
					}
			}
			
			if($proof_type>0 && $mem_id>0)
		{
			$proof_details_array=getOnlyMemberProofDetailsByCustomerAndMemberIdAndProofTypeId($mem_id,$proof_type);
			if($proof_details_array && $proof_details_array['human_proof_type_id']>0)
			{
				updateMemberProofNumberByCustomerAndMemberIdAndProofTypeId($mem_id, $proof_type, $proof_id);
			}
			else
			{
				addCustomerProof($customer_id,$customer_name,array($proof_type),array($proof_id),NULL,NULL,$mem_id);
				$proof_details_array=getOnlyMemberProofDetailsByCustomerAndMemberIdAndProofTypeId($mem_id,$proof_type);
			}
			$customer_proof_id = $proof_details_array['customer_proof_id'];
			
		}
		
		else
		$customer_proof_id="NULL";
		
		
		insertRelBookingForm($customer_id, $mem_id, $customer_proof_id, $booking_id);

	}
	
	return $booking_id;	
}
		else
		{
			return "error";
		}	
	

}





function deleteBookingFormByEnquiryId($id){
	
	try
	{
		if(1==1)
		{
		$sql="DELETE FROM ems_ab_booking_form
		      WHERE enquiry_id=$id";
		dbQuery($sql);
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

		
function getBookingFormById($booking_id)
{
	
	try
	{
		if(checkForNumeric($booking_id))
		{
		$sql="SELECT booking_date, tour_name, tour_code, tour_departure_date, tour_days, ltc_required, double_bed, extra_matress, full_tickets, extra_person, half_ticket_with_seat, half_ticket_without_seat, infant, instructions_food, instructions_hotel,
instructions_extra_hotel, recommended_by, departure_transport_mode, departure_transport_mode_date, departure_transport_mode_no,
departure_transport_mode_company, middle_transport_mode, middle_transport_mode_date, middle_transport_mode_no, middle_transport_mode_company, return_transport_mode, return_transport_mode_date, return_transport_mode_no,
return_transport_mode_company, total_amount_raw, total_amount_receipt, total_amount_date, total_amount_tax_percentage,
 total_amount_tax_amount, total_amount_after_tax, extra_vehicle_amount_raw, extra_vehicle_amount_receipt,
extra_vehicle_amount_date, extra_vehicle_amount_tax_percentage, extra_vehicle_amount_tax_amount, extra_vehicle_amount_after_tax,
extra_hotel_amount_raw, extra_hotel_amount_receipt, extra_hotel_amount_date, extra_hotel_amount_tax_percentage, 
 extra_hotel_amount_percentage_amount, extra_hotel_amount_after_tax, extra_air_amount_raw, extra_air_amount_receipt, 
 extra_air_amount_date, extra_air_amount_tax_percentage, extra_air_amount_tax_amount, extra_air_amount_after_tax, 
 discount_amount, discount_receipt_no, total_tax_amount, total_after_discount, booked_by, rr_cnf, checked_by, computer_entry, customer_id, enquiry_id, date_added, date_edited, modified_by
 
			  FROM ems_ab_booking_form
			  
			  WHERE booking_id=$booking_id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}



function getBookingFormByEnquiryId($enquiry_id)
{
	
	try
	{
		if(checkForNumeric($enquiry_id))
		{
		$sql="SELECT booking_id
 
			  FROM ems_ab_booking_form
			  
			  WHERE enquiry_id=$enquiry_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}



?>