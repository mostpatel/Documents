<?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
}

$booking_id=$_GET['id'];

$bookingFormDetails = getBookingFormById($booking_id);
$enquiry_form_id = $bookingFormDetails['enquiry_id'];

            $customer_id  = $bookingFormDetails['customer_id']; 
			
			$customerDetails = getCustomerById($customer_id);
			$contactNumbers=getCustomerContactNo($customer_id);
			
			$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);
			
			
			//$memberDetails = getMembersByCustomerId($customer_id);
			
			$booking_members = getMembersByCustomerIdForABookingId($customer_id, $booking_id);
			
			
			$proof_primary_id_for_customer = getCustomerProofIdByBookingIdAndCustomerId($booking_id, $customer_id);
			$customer_proof_details  = getPrrofDetailsByPrimaryProofId($proof_primary_id_for_customer);
			
			
            
?>

<div class="insideCoreContent adminContentWrapper wrapper">

<div class="buttons no_print">

<a href="<?php echo WEB_ROOT; ?>admin/customer/booking_form?id=<?php echo $enquiry_form_id; ?>">
<input type="button" value="+Edit Form" class="btn btn-warning" />
</a>

<a href="<?php echo WEB_ROOT; ?>admin/customer/booking_form/index.php?view=page2&id=<?php echo $booking_id ?>">
<input type="button" value="+Next Page" class="btn btn-success" />
</a>

</div> <!-- End of buttons -->


<div class="mainClass">
  
  <div class="ABHeader">
    
     <div class="leftLogo">
        <img src="../../../images/AB.jpg" />
     </div>   <!-- End of leftLogo -->
     
     <div class="rightLogo">
        <img src="../../../images/heading.jpg"  height="200"/>
     </div>    <!-- End of rightLogo -->
     
     <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
     
     <div class="address">
     2nd Floor, "Surya Complex", Near Swastik Char Rasta, C.G. Road, Navrangpura, Ahmedabad-380009.
     </div>  <!-- End of address -->
     
     <div class="phoneNo">
     <b> Booking :</b> 079-26564488, 26568833. <b> Phone :</b> (079) 26420579, 26426272, 26569979. <b> Fax :</b> (079) 26563778
     </div>  <!-- End of phoneNo -->
     
     <div class="emailWeb">
     <b> Email :</b> akhilbharat1950@gmail.com. <b> Web : </b> akhilbharattours.com
     </div>   <!-- End of emailWeb -->
     
     
  </div>  <!-- End of ABHeader -->
  
  <hr style="margin-top:10px; margin-bottom:10px" />
  <div class="numbering">
     
     <div class="numberingUpper">
        
         <div class="contractForm">
         TOUR CONTRACT FORM & DETAILS OF PASSENGERS
         </div>  <!-- End of contractForm -->
         
         <div class="billNo">
         Bill No : 		
         </div>  <!-- End of billNo -->
         
         <div class="clearDiv">
         </div>  <!-- End of clearDiv -->
         
     </div>  <!-- End of numberingUpper -->
     
     <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
     
     <hr style="margin-top:10px; margin-bottom:10px" />
     
     <div class="numberingLower">
      
         <div class="booking">
         Booking : 
         </div>  <!-- End of booking -->
         
         <div class="tourCode">
         Tour Code : <?php echo  $bookingFormDetails['tour_code']; ?>
         </div>  <!-- End of tourCode -->
         
         <div class="date">
         Date : <?php echo  date('d/m/Y',strtotime($bookingFormDetails['booking_date'])); ?>
         </div>  <!-- End of date -->
         
         <div class="clearDiv">
         </div>  <!-- End of clearDiv -->
       
     </div>  <!-- End of numberingLower -->
     
     <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
     
     
         
  </div>   <!-- End of numbering -->
  
  <div class="ticketInfoTable">
  
  <table>
  
  <tr>
  <td width="20%"> Full Tickets</td>
  <td width="20%"> Extra Person above 12 years </td>
  <td width="20%"> Half Ticket with Seat 3 to 11 years </td>
  <td width="20%"> Half Ticket without Seat 3 to 11 years </td>
  <td width="20%"> Infant below 3 years </td>
  
  </tr>
  
  
  <tr>
  
  <td width="20%"> <?php echo  $bookingFormDetails['full_tickets']; ?></td>
  <td width="20%"> <?php echo  $bookingFormDetails['extra_person']; ?> </td>
  <td width="20%"> <?php echo  $bookingFormDetails['half_ticket_with_seat']; ?> </td>
  <td width="20%"> <?php echo  $bookingFormDetails['half_ticket_without_seat']; ?> </td>
  <td width="20%"> <?php echo  $bookingFormDetails['infant']; ?> </td>
  
  </tr>
  
  </table>
  
  </div>  <!-- End of ticketInfoTable -->
  
  
  <div class="A">
  
      <div class="tourName">
      (A) Name of the Tour : <?php echo  $bookingFormDetails['tour_name']; ?>
      </div>  <!-- End of tourName -->
      
      <div class="departureDate">
      Departure Date : <?php echo  date('d/m/Y',strtotime($bookingFormDetails['tour_departure_date'])); ?>
      </div>  <!-- End of departureDate -->
      
      <div class="days">
      Days  : <?php echo  $bookingFormDetails['tour_days']; ?>
      </div>  <!-- End of days -->
      
     <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
  
  </div>   <!-- End of A -->
  
   <div class="clearDiv">
   </div>  <!-- End of clearDiv -->
  
  <div class="B">
  (B) Name of the Passenger : <?php echo $customerDetails['customer_name']; ?>
  </div>   <!-- End of B -->
  
  <div class="C">
  
  <div class="resAddress">
  (C) Residence Address : <?php
  
  $cust_address =  $extraCustomerDetails['customer_address'];
  $cust_address = str_replace("\\r\\n"," ", $cust_address);
  echo $cust_address; 
  ?>
  </div>
  
  </div>   <!-- End of C -->
  
   
  
  <div class="D">
  
      <div class="OneByTwo">
      (D) Email : <?php echo $customerDetails['customer_email'];  ?>	
      </div>  <!-- End of OneByTwo -->
      
      <div class="OneByTwo">
      Contact No : <?php
                            
							
                            for($z=0; $z<count($contactNumbers); $z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." ". "|"." ";				
                              } ?>
      </div>  <!-- End of OneByTwo -->
      
  </div>   <!-- End of D -->
  
   <div class="clearDiv">
   </div>  <!-- End of clearDiv -->
  
  <div class="E">
  (E) Business/Service Address :  <?php 
							 
							   
							   $address2 =  $extraCustomerDetails['secondary_address'];
  							   $address2 = str_replace("\\r\\n"," ", $address2);
  							    
							 
							 if($address2==NULL)
								   echo "";
								   else
								   {
								   echo $address2;
								   } 
							 
							 
							 ?>	
  </div>   <!-- End of E -->
   
  <div class="F">
  (F) Akhil Bharat's LTC cerificate Required? : 
    <?php 
    $ltc = $bookingFormDetails['ltc_required'];
	if($ltc=="not_required")
	{
	 echo "No";	
	} 
	else if($ltc=="required")
	{
	 echo "Yes";	
	} 
	?>
  </div>   <!-- End of F -->
  
  <div class="G">
  
      <div class="oneThird paddingBottomClass">
      (G) Room Required :- Double Bed :  <?php echo $bookingFormDetails['double_bed'];  ?>
      </div>  <!-- End of oneThird -->
      
      <div class="oneThird paddingBottomClass">
      Extra Matress : <?php echo $bookingFormDetails['extra_matress'];  ?>
      </div>  <!-- End of oneThird -->
      
      <div class="oneThird paddingBottomClass">
     (Except Himalaya Chardham Tour) 
      </div>  <!-- End of oneThird -->
      
  </div>   <!-- End of E -->
  
  <div class="clearDiv">
   </div>  <!-- End of clearDiv -->
  
  <div class="notebeforeTable">
  (For Four persons in one room, only one extra Mattress will be provided in Double Bed Room)
  </div>
  
  
  <div class="passengerInfoTable">
  
  <table>
  
  <tr>
  <td width="2%"> No.</td>
  <td width="30%"> Name </td>
  <td width="10%"> Birth Date </td>
  <td width="3%"> Age </td>
  <td width="12%"> Nationality </td>
  <td width="2%"> Gender </td>
  <td width="14%"> Proof Type </td>
  <td width="15%"> Proof ID </td>
  <td width="12%"> Phone No. </td>
 </tr>
  
  
   <tr>
  
  <td width="2%"> <?php $i=1; echo $i; ?> </td>
  <td width="30%"> <?php echo $customerDetails['customer_name']; ?> </td>
  
  <td width="10%"> <?php  if(date('d/m/Y',strtotime($extraCustomerDetails['customer_dob'])) != "01/01/1970")
  echo date('d/m/Y',strtotime($extraCustomerDetails['customer_dob']));  ?> </td>
  
  <td width="3%">  <?php if(date('d/m/Y',strtotime($extraCustomerDetails['customer_dob'])) != "01/01/1970")
  echo getYearsBetweenDates($extraCustomerDetails['customer_dob'], $bookingFormDetails['tour_departure_date']); ?> </td>
  
  <td width="12%"> <?php   
                                   $nationality_id = $extraCustomerDetails['customer_nationality']; 
							 
							 if($nationality_id==1)
							 {
								   echo "Indian";
							 }
							 else if($nationality_id==0)
								   {
								   
								   echo "Other";
								   } 
								   ?> </td>
  <td width="2%"> 
  <?php
   $prefix_id = $customerDetails['prefix_id'];
							  
							  if($prefix_id !=0)
					{
							  
							  $prefixDetails = getPrefixById($prefix_id);
							  
							  $customer_prefix = $prefixDetails['prefix'];
							  
							  if($customer_prefix == "Mr.")
							  {
								echo "M";  
							  }
							  
							  else if($customer_prefix == "Ms.")
							  {
								echo "F";  
							  }
					}
  ?>
  </td>
  <td width="14%"> <?php echo $customer_proof_details['proof_type'] ?> </td>
  <td width="15%"> <?php echo $customer_proof_details['customer_proof_no'] ?> </td>
  <td width="12%"> <?php echo $contactNumbers[0]['customer_contact_no']; ?> </td>
  </tr>
  
  <?php
  foreach($booking_members as $mem_id)
  {
	  $mem_id = $mem_id[0];
	  
	  $memberDetail = getMemberById($mem_id);
	  
	  $proof_primary_id_for_member = getMemberProofIdByBookingIdAndMemberId($booking_id, $mem_id);
	  $member_proof_details  = getPrrofDetailsByPrimaryProofId($proof_primary_id_for_member);
	  $memContactNumbers = getMemberContactNo($mem_id);
  ?> 
  <tr>
  
  <td width="2%"> <?php echo ++$i ?></td>
  <td width="30%"> <?php echo $memberDetail['member_name']; ?> </td>
  <td width="10%"> <?php 
  if(date('d/m/Y', strtotime($memberDetail['member_dob'])) != "01/01/1970")
  echo date('d/m/Y', strtotime($memberDetail['member_dob'])); ?> </td>
  <td width="3%"> <?php 
  if(date('d/m/Y', strtotime($memberDetail['member_dob'])) != "01/01/1970")
  echo getYearsBetweenDates($memberDetail['member_dob'], $bookingFormDetails['tour_departure_date']); ?> </td>
  <td width="12%"> <?php   
                             $nationality_id = $memberDetail['member_nationality']; 
							 
							 if($nationality_id==1)
							 {
								   echo "Indian";
							 }
							 else if($nationality_id==0)
								   {
								   
								   echo "Other";
								   } 
								   ?>  </td>
  <td width="2%"> <?php
   $gender_id = $memberDetail['gender'];
							  
							  if($gender_id != -1)
					{
							  
							  
							  
							  if($gender_id == 0)
							  {
								echo "M";  
							  }
							  
							  else if($gender_id == 1)
							  {
								echo "F";  
							  }
					}
  ?> </td>
  <td width="14%"> <?php echo $member_proof_details['proof_type'] ?> </td>
  <td width="15%"> <?php echo $member_proof_details['customer_proof_no'] ?> </td>
  <td width="12%"> <?php
  if(validateForNull($memContactNumbers[0]['customer_contact_no']))
  echo $memContactNumbers[0]['customer_contact_no']; ?> </td>
  
  </tr>
  <?php
  }
  ?>
  
  <?php
  
 $i++;
 
 for($i; $i<=6; $i++)
  {
  ?>
  
  <tr>
  
  <td width="2%"> <?php echo $i; ?></td>
  <td width="30%"></td>
  <td width="10%">  </td>
  <td width="3%">  </td>
  <td width="12%">  </td>
  <td width="2%">  </td>
  <td width="14%">  </td>
  <td width="15%"> </td>
  <td width="12%">  </td>
  
  </tr>
  
  <?php
  }
  ?>
  
  </table>
  
  </div>  <!-- End of ticketInfoTable -->
  
  
  <div class="afterTableContent">
  
    <div class="leftDivContent">
      
       <div class="H">
       (H) Instructions : (1) For Food : <?php echo $bookingFormDetails['instructions_food'];  ?>
       </div>  <!-- End of H -->
       
       <div class="forHotel">
       (2) For Hotel : <?php echo $bookingFormDetails['instructions_hotel'];  ?>
       </div>  <!-- End of 2 -->
       
       <div class="extraHotel">
       (3) Extra Hotel Booking : <?php echo $bookingFormDetails['instructions_extra_hotel'];  ?>
       </div>  <!-- End of 3 -->
       
       <div class="recco">
       Recommended By : <?php echo $bookingFormDetails['recommended_by'];  ?>
       </div>  <!-- End of recco-->
       
       
    </div>   <!-- End of leftDivContent -->
    
    <div class="rightDivContent">
    
     <div class="stamp">
     Agent rubber Stamp
     </div>  <!-- End of stamp -->
       
    </div>   <!-- End of rightDivContent -->
    
    <div class="clearDiv">
    </div>  <!-- End of clearDiv -->
    
  
  </div>  <!-- End of afterTableContent -->
  
  <div class="clearDiv">
  </div>  <!-- End of clearDiv -->
  
  <hr />
  
  <div class="officeUse">
     
     <div class="officeUseTitle">
     FOR OFFICE USE 
     </div>  <!-- End of officeUseTitle -->
     
     <div class="officeUseContent">
        
         <div class="departure">
         
             <div class="oneFourthDiv">
             જતાં : 
			 <?php 
			 
			 $dep_transport_mode = $bookingFormDetails['departure_transport_mode']; 
			 if($dep_transport_mode == "railway")
			 {
			   echo "Railway";	 
			 } 
			 
			 else if($dep_transport_mode == "air")
			 {
			   echo "Air";	 
			 } 
			 ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
             Date : <?php 
			       if(date('d/m/Y',strtotime($bookingFormDetails['departure_transport_mode_date'])) != "01/01/1970")
			 echo  date('d/m/Y',strtotime($bookingFormDetails['departure_transport_mode_date'])); ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
            Train No : <?php echo  $bookingFormDetails['departure_transport_mode_no']; ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
             <?php 
			 if($bookingFormDetails['departure_transport_mode_company'] != "NULL")
			 echo  $bookingFormDetails['departure_transport_mode_company']; ?>
             </div> <!-- End of oneFifth -->
             
             
         </div>  <!-- End of departure -->
         
         <div class="middle">
         
         
             <div class="oneFourthDiv">
             વચ્ચે : <?php 
			 
			 $mid_transport_mode = $bookingFormDetails['middle_transport_mode']; 
			 if($mid_transport_mode == "railway")
			 {
			   echo "Railway";	 
			 } 
			 
			 else if($mid_transport_mode == "air")
			 {
			   echo "Air";	 
			 } 
			 ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
             Date : <?php 
			    if(date('d/m/Y',strtotime($bookingFormDetails['middle_transport_mode_date'])) != "01/01/1970")
			 echo  date('d/m/Y',strtotime($bookingFormDetails['middle_transport_mode_date'])); ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
            Train No : <?php echo  $bookingFormDetails['middle_transport_mode_no']; ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
             
             <?php 
			 if($bookingFormDetails['middle_transport_mode_company'] != "NULL")
			 echo  $bookingFormDetails['middle_transport_mode_company']; ?>
             </div> <!-- End of oneFifth -->
             
             
             
         </div> <!-- End of middle -->
         
         <div class="return">
        
         
             <div class="oneFourthDiv">
              વળતાં : <?php 
			 
			 $ret_transport_mode = $bookingFormDetails['return_transport_mode']; 
			 if($ret_transport_mode == "railway")
			 {
			   echo "Railway";	 
			 } 
			 
			 else if($ret_transport_mode == "air")
			 {
			   echo "Air";	 
			 } 
			 ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
             Date : <?php 
			      if(date('d/m/Y',strtotime($bookingFormDetails['return_transport_mode_date'])) != "01/01/1970")
			 echo  date('d/m/Y',strtotime($bookingFormDetails['return_transport_mode_date'])); ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
            Train No : <?php echo  $bookingFormDetails['return_transport_mode_no']; ?>
             </div> <!-- End of oneFifth -->
             
             <div class="oneFourthDiv">
             <?php 
			 if($bookingFormDetails['return_transport_mode_company'] != "NULL")
			 echo  $bookingFormDetails['return_transport_mode_company']; ?>
             </div> <!-- End of oneFifth -->
             
             
         
         
         </div> <!-- End of return -->
         
         <div class="officeUseA">
         
               <div class="oneThird">
               (A) Seat no.
               </div>  <!-- End of oneThird -->
               
               <div class="oneThird">
               (B) Time Table sent on Date
               </div>  <!-- End of oneThird -->
               
               <div class="oneThird">
               by Post/Angadia/Personally
               </div>  <!-- End of oneThird -->
           
         </div> <!-- End of departure -->
         
         <div class="officeUseC">
              
              <div class="oneThird">
               (C) Advance LTC Given on
               </div>  <!-- End of oneThird -->
               
               <div class="oneThird">
               (D) LTC sent on :
               </div>  <!-- End of oneThird -->
               
               <div class="oneThird">
               by Post/Angadia/Personally
               </div>  <!-- End of oneThird -->
               
         </div> <!-- End of departure -->
         
         
     </div>  <!-- End of officeUseContent -->
      
  </div>  <!-- End of officeUse -->
  
  <hr />
  
  <div class="totalAmount">
  
    <div class="totalAmountTitle">
     TOTAL AMOUNT 
     </div>  <!-- End of officeUseTitle -->
     
     <div class="totalAmountContent">
        
         <div class="row">
         
             <div class="oneFifthBigger">
             Tour Amount receivable Rs. : 
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
              <?php echo  $bookingFormDetails['total_amount_raw']; ?>
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
             R.No. : <?php echo  $bookingFormDetails['total_amount_receipt']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
            Date : <?php 
			if(date('d/m/Y',strtotime($bookingFormDetails['total_amount_date'])) != "01/01/1970")
			echo  date('d/m/Y',strtotime($bookingFormDetails['total_amount_date'])); ?>
             </div> <!-- End of oneFifthSmaller -->
             
             
             
             <div class="oneFifthSmaller">
             Rs. : 
             </div> <!-- End of oneFifthSmaller -->
             
         </div>  <!-- End of row -->
         
         <div class="row">
         
             <div class="oneFifthBigger">
             Extra Vehicle Fare Rs. : 
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
            <?php echo  $bookingFormDetails['extra_vehicle_amount_raw']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
             R.No. : <?php echo  $bookingFormDetails['extra_vehicle_amount_receipt']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
            Date : <?php 
			if(date('d/m/Y',strtotime($bookingFormDetails['extra_vehicle_amount_date'])) != "01/01/1970")
			echo  date('d/m/Y',strtotime($bookingFormDetails['extra_vehicle_amount_date'])); ?>
             </div> <!-- End of oneFifthSmaller -->
             
             
             
             <div class="oneFifthSmaller">
             Rs. : 
             </div> <!-- End of oneFifthSmaller -->
             
         </div>  <!-- End of row -->
         
         
         <div class="row">
         
             <div class="oneFifthBigger">
             Extra Hotel Fare Rs. : 
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
            <?php echo  $bookingFormDetails['extra_hotel_amount_raw']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
             R.No. : <?php echo  $bookingFormDetails['extra_hotel_amount_receipt']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
            Date : <?php 
			 if(date('d/m/Y',strtotime($bookingFormDetails['extra_hotel_amount_date'])) != "01/01/1970")
			echo  date('d/m/Y',strtotime($bookingFormDetails['extra_hotel_amount_date'])); ?>
             </div> <!-- End of oneFifthSmaller -->
             
             
             
             <div class="oneFifthSmaller">
             Rs. : 
             </div> <!-- End of oneFifthSmaller -->
             
         </div>  <!-- End of row -->
         
         <div class="row">
         
             <div class="oneFifthBigger">
             Extra Air Fare Rs. : 
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
            <?php echo  $bookingFormDetails['extra_air_amount_raw']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
             R.No. : <?php echo  $bookingFormDetails['extra_air_amount_receipt']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
            Date : <?php 
			if(date('d/m/Y',strtotime($bookingFormDetails['extra_air_amount_date'])) != "01/01/1970")
			echo  date('d/m/Y',strtotime($bookingFormDetails['extra_air_amount_date'])); ?>
             </div> <!-- End of oneFifthSmaller -->
             
             
             
             <div class="oneFifthSmaller">
             Rs. : 
             </div> <!-- End of oneFifthSmaller -->
             
         </div>  <!-- End of row -->
         
         <div class="row">
         
             <div class="oneFifthBigger">
             RR Rs. : 
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
            <?php echo  $bookingFormDetails['discount_amount']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
             R.No. : <?php echo  $bookingFormDetails['discount_receipt_no']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneFifthSmaller">
            -
             </div> <!-- End of oneFifthSmaller -->
             
             
             
             <div class="oneFifthSmaller">
             Rs. : 
             </div> <!-- End of oneFifthSmaller -->
             
         </div>  <!-- End of row -->
         
         <div class="row">
         
             <div class="oneFifthBigger">
             Total Service Tax : 
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
            <?php echo  $bookingFormDetails['total_tax_amount']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneThirdSmaller">
           Booked By : <?php   $booked_by_id = $bookingFormDetails['booked_by']; echo getAdminUserNameByID($booked_by_id); ?>
             </div> <!-- End of oneThirdSmaller -->
             
             <div class="oneThirdSmaller">
            Computer Entry : <?php echo  $bookingFormDetails['computer_entry']; ?>
             </div> <!-- End of oneThirdSmaller -->
             
             
             
         </div>  <!-- End of row -->
         
         <div class="row">
         
             <div class="oneFifthBigger">
             Total Amount : 
             </div> <!-- End of oneFifthBigger -->
             
             <div class="oneFifthSmaller">
           <?php echo  $bookingFormDetails['total_after_discount']; ?>
             </div> <!-- End of oneFifthSmaller -->
             
             <div class="oneThirdSmaller">
            RR CNF : <?php   $rr_cnf_id = $bookingFormDetails['rr_cnf']; echo getAdminUserNameByID($rr_cnf_id); ?>
             </div> <!-- End of oneThirdSmaller -->
             
             <div class="oneThirdSmaller">
       Checked By : <?php $checked_by_id =   $bookingFormDetails['checked_by']; echo getAdminUserNameByID($checked_by_id); ?>
             </div> <!-- End of oneThirdSmaller -->
             
             
             
         </div>  <!-- End of row -->
         
         
         
     </div>  <!-- End of totalAmountContent -->
  
  </div>  <!-- End of totalAmount -->

  
</div> <!-- End of mainClass -->

      
</div>
<div class="clearfix"></div>
