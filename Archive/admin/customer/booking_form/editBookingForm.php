<?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
}

$enquiry_form_id=$_GET['id'];

$booking_form_id = getBookingFormByEnquiryId($enquiry_form_id);

if(checkForNumeric($booking_form_id))
{
$booking_form_details = getBookingFormById($booking_form_id);

}
else
{
$booking_form_details = NULL;
}


$enquiryDetails=getEnquiryById($enquiry_form_id);

$customer_id = $enquiryDetails['customer_id'];
$customerDetails=getCustomerById($customer_id);

$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);



if(checkForNumeric($booking_form_id))
$membersDetails = getMemberDetailsByCustomerIdForABookingId($customer_id, $booking_form_id);
else
$membersDetails = getMembersByCustomerId($customer_id);



$contactNumbers=getCustomerContactNo($customer_id);

$products=getSubCatFromEnquiryId($enquiry_form_id);

$sub_cat_id = $products[0]['sub_cat_id'];

$all_attribute_type_array = getAttributesFromSubCatId($sub_cat_id);

?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Booking Details</h4>
<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php if(isset($type)  && $type>0 && $type<4) { ?> <strong>Success!</strong> <?php } else if(isset($type) && $type>3) { ?> <strong>Warning!</strong> <?php } ?> <?php echo $msg; ?>
</div>
<?php
		
		
		}
	if(isset($type) && $type>0)
		$_SESSION['ack']['type']=0;
	if($msg!="")
		$_SESSION['ack']['msg']=="";
}

?>
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editBookingForm'; ?>" name="myForm" method="post" >

<table class="insertTableStyling no_print noBorder">

 <input name="customer_id" type="hidden" value="<?php echo $customer_id; ?>" />
 <input name="enquiry_form_id" type="hidden" value="<?php echo $enquiry_form_id; ?>" />
 <?php
 if(checkForNumeric($booking_form_id))
 {
 ?>
 <input name="booking_form_id" type="hidden" value="<?php echo $booking_form_id; ?>" />
 <?php
 }
 ?>
<tr>

<td width="220px" class="firstColumnStyling">
Date<span class="requiredField">* </span> : 
</td>

<td> 
<input type="text" name="booking_date" id="booking_date" placeholder="booking_date" 
value="<?php 
if(validateForNull($booking_form_details) && $booking_form_details['booking_date'] != "1970-01-01 00:00:00")
{
	echo date('d/m/Y', strtotime($booking_form_details['booking_date']));
}
else
echo date('d/m/Y', strtotime(getTodaysDate())); 
?>" 
class="datepicker1 datepick" />

</td>
</tr>

<tr>
<td>Tour Name<span class="requiredField">* </span> : </td>
				<td>
                
					<input type="text" name="tourName" id="tourName" 
                    value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['tour_name'];
					}
					else
					echo $products[0]['sub_cat_name']; 
					?>"/>

                            </td>
</tr>


<tr>
<td>Tour Code <span class="requiredField">* </span> : </td>
				<td>
                
					<input type="text" name="tourCode" id="tourCode" style="text-transform: uppercase;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['tour_code'];
					}
					
					?>" /> 

                            </td>
</tr>

<tr>
<td class="firstColumnStyling" width="125px">
Departure Date <span class="requiredField">* </span>: 
</td>

<td>
<input type="text" id="datepicker_tourDate" size="12" autocomplete="off"  name="purchase_date" class="datepicker2 datepick" placeholder="Click to Select!" value="<?php

 $purchaseDate = $enquiryDetails['purchase_date'];
 if(strtotime($purchaseDate) != strtotime("1970-01-01"))
 {
 $purchaseDate = date('d/m/Y',strtotime($purchaseDate));
 echo $purchaseDate;
 }
 ?>" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>
<td> Days <span class="requiredField">* </span> :</td>
				<td>
					<select  name="days">
        <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
        <option value="2" <?php if( validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
        <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
        <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
        <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
        <option value="6" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 6) { ?> selected="selected" <?php } ?>> 6 </option>
        <option value="7" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 7) { ?> selected="selected" <?php } ?>> 7 </option>
        <option value="8" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 8) { ?> selected="selected" <?php } ?>> 8 </option>
        <option value="9" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 9) { ?> selected="selected" <?php } ?>> 9 </option>
     <option value="10" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 10) { ?> selected="selected" <?php } ?>> 10 </option>
     <option value="11" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 11) { ?> selected="selected" <?php } ?>> 11</option>
     <option value="12" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 12) { ?> selected="selected" <?php } ?>> 12 </option>
     <option value="13" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 13) { ?> selected="selected" <?php } ?>> 13</option>
     <option value="14" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 14) { ?> selected="selected" <?php } ?>> 14 </option>
     <option value="15" <?php if(validateForNull($booking_form_details) && $booking_form_details['tour_days'] == 15) { ?> selected="selected" <?php } ?>> 15 </option>
                     </select> 
               </td>
</tr>

<tr>
<td> Customer Prefix : </td>
				<td>
					<select  name="prefix_id">
                        
                        <?php
                            $prefixes = listPrefix();
							
                            foreach($prefixes as $prefix)
                              {
                             ?>
                             
                           <option value="<?php echo $prefix['prefix_id'] ?>" <?php if($prefix['prefix_id'] == $customerDetails['prefix_id']) { ?> selected="selected" <?php } ?>><?php echo $prefix['prefix'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Customer Name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $customerDetails['customer_id'] ?>" />
<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_from_id ?>" />

<input type="text" name="customer_name" id="customer_name" value="<?php echo $customerDetails['customer_name']; ?>"/>
</td>
</tr>

<tr>
<td>
Address <span class="requiredField">* </span> : 
</td>

<td>
<textarea id="residence_address" class="residence_address" name="residence_address"  cols="5" rows="6">
<?php
if(is_array($extraCustomerDetails) && $extraCustomerDetails['extra_details_id']>0)
{
echo $extraCustomerDetails['customer_address'];
}
?>
</textarea>
</td>
</tr>

<?php  
$lj=0;
foreach($contactNumbers as $contact)
{
 ?>
  <tr>
            <td>
            Contact No<?php if($lj==0) { ?><span class="requiredField">* </span> <?php } ?> : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" <?php if($lj==0) { ?> id="customerContact" <?php } ?> name="customerContact[]" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> placeholder="more than 6 Digits!" value="<?php echo $contact[0]; ?>" /><span></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
<?php
$lj++;
 } ?> 


 <tr id="addcontactTrCustomer">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" <?php if($lj<1) { ?> id="customerContact" <?php } ?> name="customerContact[]" placeholder="more than 6 Digits!" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
            Contact No : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="customerContact[]" onblur="checkContactNo(this.value,this)" placeholder="more than 6 Digits!" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
       
       
<tr>
<td class="firstColumnStyling">
Email :
</td>

<td>

<input type="text" name="email" id="email" value="<?php echo $customerDetails['customer_email']; ?>"/>
</td>
</tr>


<tr>
<td>
Business Address : 
</td>

<td>
<textarea id="business_address" class="business_address" name="business_address"  cols="5" rows="6">
<?php
if(is_array($extraCustomerDetails) && $extraCustomerDetails['extra_details_id']>0)
{
echo $extraCustomerDetails['secondary_address'];
}
?>
</textarea>
</td>
</tr>

<tr>
<td>
LTC Certificate required? : <span class="requiredField">* </span> 
</td>

<td>
   
   <table>
    
    <tr>
    <td> <input type="radio" name="ltc" value="not_required" 
	<?php if(validateForNull($booking_form_details) && $booking_form_details['ltc_required'] == 'not_required')
     {
     ?>
    checked="checked"
    <?php
	}
	else
	{ 
?>checked="checked"

<?php
	}
?>> </td>
    <td> Not required </td>
    </tr>
    
    <tr>
    <td> <input type="radio" name="ltc" value="required" 
    <?php if(validateForNull($booking_form_details) && $booking_form_details['ltc_required'] == 'required')
     {
     ?>
    checked="checked"
    <?php
	}
	?>
    > </td>
    <td> Required </td>
    </tr>
    
    </table>
    
</td>
</tr>


<tr>
<td> Double Bed :<span class="requiredField">* </span>  </td>
				<td>
					<select  name="double_bed">
                    <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['double_bed'] == 0) { ?> selected="selected" <?php } ?>> 0 </option>
                        <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['double_bed'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
                        <option value="2" <?php if(validateForNull($booking_form_details) && $booking_form_details['double_bed'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
                        <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['double_bed'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
                        <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['double_bed'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
                        <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['double_bed'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
                        
                     </select> 
                            </td>
</tr>

<tr>
<td> Extra Matress : <span class="requiredField">* </span> </td>
				<td>
					<select  name="extra_matress">
                    <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_matress'] == 0) { ?> selected="selected" <?php } ?>> 0 </option>
                    <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_matress'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
                    <option value="2" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_matress'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
                    <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_matress'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
                    <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_matress'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
                    <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_matress'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
                    
                        
                     </select> 
                            </td>
</tr>

</table>



<table width="100%" style="margin-top:20px;margin-bottom:20px;" class="noBorder">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable inventory_table" id="productPurchaseTable">
    		<tr>
            	
                 <th class='heading' style="width:20%;">Full Tickets</th>
                 <th class='heading' style="width:20%;">Extra Person <br />(above 12 years)</th>
                 <th class='heading' style="width:20$;">Half Ticket with Seat <br />(3 to 11 years)</th>
                 <th class='heading' style="width:20%;">Half Ticket without Seat <br />(3 to 11 years)</th>
                 <th class='heading' style="width:20%;">Infant below 3 years</th>
                 
                 
            </tr>
            
           
            <tbody>
            	<tr>
                
                  <td>
                         <select  name="full_tickets" style="width:120px;">
                         
                    <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['full_tickets'] == 0) { ?> selected="selected" <?php } ?>> 0 </option>
                    <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['full_tickets'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
                    <option value="2" <?php if(validateForNull($booking_form_details) && $booking_form_details['full_tickets'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
                    <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['full_tickets'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
                    <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['full_tickets'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
                    <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['full_tickets'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
                    <option value="6" <?php if(validateForNull($booking_form_details) && $booking_form_details['full_tickets'] == 6) { ?> selected="selected" <?php } ?>> 6 </option>
                    
                          </select> 
                            
                    </td>
                    
                    <td>
                         <select  name="extra_person" style="width:120px;">
                    <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_person'] == 0) { ?> selected="selected" <?php } ?>> 0 </option>
                    <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_person'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
                    <option value="2" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_person'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
                    <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_person'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
                    <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_person'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
                    <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_person'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
                    <option value="6" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_person'] == 6) { ?> selected="selected" <?php } ?>> 6 </option>
                          </select> 
                            
                    </td>
                    
                    <td>
                         <select  name="half_with_seat" style="width:120px;">
                    <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_with_seat'] == 0) { ?> selected="selected" <?php } ?>> 0 </option>
                    <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_with_seat'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
                    <option value="2" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_with_seat'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
                    <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_with_seat'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
                    <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_with_seat'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
                    <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_with_seat'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
                    <option value="6" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_with_seat'] == 6) { ?> selected="selected" <?php } ?>> 6 </option>
                          </select> 
                            
                    </td>
                    
                    <td>
                         <select  name="half_without_seat" style="width:120px;">
                    <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_without_seat'] == 0) { ?> selected="selected" <?php } ?>> 0 </option>
                    <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_without_seat'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
                    <option value="2" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_without_seat'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
                    <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_without_seat'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
                    <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_without_seat'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
                    <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_without_seat'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
                    <option value="6" <?php if(validateForNull($booking_form_details) && $booking_form_details['half_ticket_without_seat'] == 6) { ?> selected="selected" <?php } ?>> 6 </option>
                          </select> 
                            
                    </td>
                    
                    <td>
                         <select  name="infant" style="width:120px;">
                    <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['infant'] == 0) { ?> selected="selected" <?php } ?>> 0 </option>
                    <option value="1" <?php if(validateForNull($booking_form_details) && $booking_form_details['infant'] == 1) { ?> selected="selected" <?php } ?>> 1 </option>
                    <option value="2" <?php if(validateForNull($booking_form_details) && $booking_form_details['infant'] == 2) { ?> selected="selected" <?php } ?>> 2 </option>
                    <option value="3" <?php if(validateForNull($booking_form_details) && $booking_form_details['infant'] == 3) { ?> selected="selected" <?php } ?>> 3 </option>
                    <option value="4" <?php if(validateForNull($booking_form_details) && $booking_form_details['infant'] == 4) { ?> selected="selected" <?php } ?>> 4 </option>
                    <option value="5" <?php if(validateForNull($booking_form_details) && $booking_form_details['infant'] == 5) { ?> selected="selected" <?php } ?>> 5 </option>
                    <option value="6" <?php if(validateForNull($booking_form_details) && $booking_form_details['infant'] == 6) { ?> selected="selected" <?php } ?>> 6 </option>
                          </select> 
                            
                    </td>
                  
                    
                            
            	</tr>
            </tbody>
  </table>
</td>
</tr>
</table>

<h4 class="headingAlignment">Passenger Details</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;" class="noBorder">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable inventory_table" id="productPurchaseTable">
    		<tr>
            	<th class='heading'>No.</th>
                 <th class='heading'>Name</th>
                 <th class='heading'>DOB</th>
                 <th class='heading'>Age</th>
                 <th class='heading'>Nationality</th>
                 <th class='heading'>Gender</th>
                 <th class='heading'>Proof Type</th>
                 <th class='heading'>Proof ID</th>
                 <th class='heading'>Phone</th>
                 
            </tr>
            
           
            <tbody>
            	<tr >
                
                
                  <td>
                    	<?php $i=1; echo $i; ?>
                    </td>
                
                    <td>
                    	<input name="customer_name" type="text" style="width:200px;"  
                        value="<?php 
						
						
						
						echo $customerDetails['customer_name']; 
						
						?>" />
                    </td>
                    
                    <td> 
                     <input type="text" name="dob_customer"  placeholder="DOB" class="datepicker1 datepick" style="width:75px;" 
                     value="<?php
					 
					 if(is_array($extraCustomerDetails))
				{  
					 
					 if($extraCustomerDetails['customer_dob'] != "1970-01-01 00:00:00")
					 {
						echo date('d/m/Y', strtotime($extraCustomerDetails['customer_dob']));
					 }
				}
				
					 ?>" onchange="countAge(this)"/>
                     
                    </td>
                    
                    <td> 
                     <input type="text" name="age" style="width:35px;" value="
					 <?php 
					 
			getYearsBetweenDates($extraCustomerDetails['customer_dob'], $enquiryDetails['purchase_date']); 
				
			          ?>
                      "/>
                     
                    </td>
                    
                    <td>
                         <select  name="customer_nationality" style="width:80px;">
                             <option value="1" 
							 <?php if(is_array($extraCustomerDetails) && $extraCustomerDetails['customer_nationality']==1)
							{ ?> selected="selected" <?php } ?>
							>Indian</option>
                              <option value="0"
                              <?php if(is_array($extraCustomerDetails) && $extraCustomerDetails['customer_nationality']==0)
							{ ?> selected="selected" <?php } ?>
                              >Other</option>
                          </select> 
                            
                      </td>
                    
                    <td>
                         <select  name="gender" style="width:50px;">
                             <option value="0" <?php if($customerDetails['prefix_id'] == 1) { ?> selected="selected" <?php } ?>>M</option>
                             <option value="1" <?php if($customerDetails['prefix_id'] == 3 || $customerDetails['prefix_id'] == 4) { ?> selected="selected" <?php } ?>>F</option>
                              
                          </select> 
                            
                    </td>
                    
                    <?php
					
			if(!checkForNumeric($booking_form_id))
			{
			$cust_proof_details = getCustomerProofByCustomerId($customerDetails['customer_id']);
			$customer_proof_type_id = $cust_proof_details[0]['human_proof_type_id'];
			$customer_proof_type_no = $cust_proof_details[0]['customer_proof_no'];
			}
			
			else
			{
			$proof_ref_id = getCustomerProofIdByBookingIdAndCustomerId($booking_form_id, $customerDetails['customer_id']);
			$customer_proof_details = getProofTypeAndProofNoByProofId($proof_ref_id);
			$customer_proof_type_id = $customer_proof_details['human_proof_type_id'];
			$customer_proof_type_no = $customer_proof_details['customer_proof_no'];
			
			}
			
			
			
                    ?>
                    
                    <td id="customerProofTypeTd">
					<select id="proof" name="human_proof_type_id" class="customerProofId" style="width:150px">
                        <option value="-1" >--Please Select--</option>
                        <?php
						
			
			
                            $types = listProofTypes();
                            foreach($types as $super)
                              {
                             ?>
                             
                    <option value="<?php echo $super['human_proof_type_id'] ?>" <?php if($super['human_proof_type_id'] ==$customer_proof_type_id) { ?> selected="selected" <?php } ?>><?php echo $super['proof_type'] ?></option>
                    
                    
                             <?php } ?>
                              
                         
                            </select>
                            </td>
                    
                    <td> 
 <input type="text" name="proof_id" id="proof_id" style="width:120px;" value="<?php if($customer_proof_type_no != 'e') echo $customer_proof_type_no;?>"/>
                    </td>
                         
                     <td> 
                     <input type="text" name="member_phone" id="member_phone" style="width:90px;" value="<?php echo $contactNumbers[0]['customer_contact_no']; ?>"/>
                    </td>
                    
                            
            	</tr>
            </tbody>
            
            
            <?php
			
				
				foreach($membersDetails as $membersDetail)
				{
					$member_name = $membersDetail['member_name'];
					$member_gender = $membersDetail['gender'];
					$member_dob = $membersDetail['member_dob'];
					$refined_member_dob = date('d/m/Y', strtotime($member_dob));
					$memberContactNoArray = getMemberContactNo($membersDetail['member_id']);
					
			if(!checkForNumeric($booking_form_id))
			{
			$mem_proof_details = getOnlyMemberProofDetailsByCustomerAndMemberId($customerDetails['customer_id'], $membersDetail['member_id']);
			
			$member_proof_type_id = $mem_proof_details[0]['human_proof_type_id'];
			$member_proof_type_no = $mem_proof_details[0]['customer_proof_no'];
			}
			else
			{		
					
			$proof_ref_id = getMemberProofIdByBookingIdAndMemberId($booking_form_id, $membersDetail['member_id']);
			$member_proof_details = getProofTypeAndProofNoByProofId($proof_ref_id);
			$member_proof_type_id = $member_proof_details['human_proof_type_id'];
			$member_proof_type_no = $member_proof_details['customer_proof_no'];
			}
					
			?>
            
             <tbody>
            	<tr>
                
                
                  <td>
                    	<?php echo ++$i; ?>
                        <input name="member_id[]" type="hidden" value="<?php echo $membersDetail['member_id']; ?>" />
                    </td>
                
                    <td>
                    	<input name="member_name[]" type="text" style="width:200px;" value="<?php echo $member_name ?>" />
                    </td>
                    
                    <td> 
                     <input type="text" name="dob_member_array[]" placeholder="DOB" class="datepicker2 datepick dob_member" style="width:75px;" value="<?php 
					 if($refined_member_dob != "01/01/1970")
					 {
					 echo $refined_member_dob;
					 }
					 ?>"  onchange="countAge(this)"/>
                     
                    </td>
                    
                    <td> 
                     <input type="text" name="age_member[]" class="age_member" style="width:35px;"value="
					 <?php 
					 
				
			getYearsBetweenDates($member_dob, $enquiryDetails['purchase_date']); 
				
			          ?>
                      "/>
                    </td>
                    
                    <td>
                         <select  name="member_nationality_array[]" style="width:80px;">
                              <option value="1">Indian</option>
                              <option value="0">Other</option>
                          </select> 
                            
                      </td>
                    
                    <td>
                         <select  name="member_gender[]" style="width:50px;">
                         
                             <option value="0" <?php if($member_gender=='0') {?> selected="selected" <?php }?>>M</option>   
                             <option value="1" <?php if($member_gender=='1') {?> selected="selected" <?php }?>>F</option>
                              
                          </select> 
                            
                    </td>
                    
                    
                   
                   
                    
                    <td id="customerProofTypeTd">
					<select id="proof" name="memberProofArray[]" class="customerProofId" onblur="checkProofId(this.value,this)" style="width:150px">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $types = listProofTypes();
                            foreach($types as $super)
                              {
                             ?>
                             
       <option value="<?php echo $super['human_proof_type_id'] ?>" <?php if($super['human_proof_type_id'] ==$member_proof_type_id) { ?> selected="selected" <?php } ?>><?php echo $super['proof_type'] ?></option>
       
                             
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
                    
                    <td> 
                     <input type="text" name="member_proof_id[]" id="proof_id" style="width:120px;" value="<?php if(validateForNull($member_proof_type_no)) echo $member_proof_type_no;?>"/>
                    </td>
                         
                     <td> 
                     <input type="text" name="member_phone[]" id="member_phone" style="width:90px;" value="<?php echo $memberContactNoArray[0]['member_contact_no']; ?>"/>
                    </td>
                    
                            
            	</tr>
            </tbody>
            
            
            
            <?php
			}
			?>
            
            
            
            <?php
			
				$already_member_count = count($membersDetails);
				$remaining_member_count = 6 - $already_member_count;
				
				for($j=$already_member_count; $j<=$remaining_member_count; $j++)
				{
					
			?>
            
             <tbody>
            	<tr>
                
                
                  <td>
                    	<?php echo ++$i; ?>
                        <input name="member_id[]" type="hidden" value="-1" />
                        
                    </td>
                
                    <td>
                    	<input name="member_name[]" type="text" style="width:200px;" />
                    </td>
                    
                    <td> 
                     <input type="text" name="dob_member_array[]"  placeholder="DOB" class="datepicker2 datepick dob_member" style="width:75px;" onchange="countAge(this)" />
                     
                    </td>
                    
                    <td> 
                     <input type="text" name="age_member[]" class ="age_member" style="width:35px;"/>
                    </td>
                    
                   <td>
                         <select  name="member_nationality_array[]" style="width:80px;">
                             <option value="1">Indian</option>
                              <option value="0">Other</option>
                          </select> 
                            
                   </td>
                   
                    
                    <td>
                         <select  name="member_gender[]" style="width:50px;">
                             <option value="0">M</option>   
                             <option value="1">F</option>
                              
                          </select> 
                            
                    </td>
                    
                    <td id="customerProofTypeTd">
					<select id="proof" name="memberProofArray[]" class="customerProofId" onblur="checkProofId(this.value,this)" style="width:150px">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $types = listProofTypes();
                            foreach($types as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['human_proof_type_id'] ?>"><?php echo $super['proof_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
                    
                    <td> 
                     <input type="text" name="member_proof_id[]" id="proof_id" style="width:120px;"/>
                    </td>
                         
                    <td> 
                     <input type="text" name="member_phone[]" id="member_phone" style="width:90px;"/>
                    </td> 
                    
                            
            	</tr>
            </tbody>
            
            
            
            <?php
			}
			?>
            
    	</table>
    </td>

</tr>
</table>


<table class="noBorder" >



<tr>
<td width="220px" class="firstColumnStyling">
Instructions For Food  </span> : 
</td>

<td>
<textarea id="food_instructions" class="food_instructions" name="food_instructions"  cols="8" rows="4">
<?php
if(validateForNull($booking_form_details))
	{
	echo $booking_form_details['instructions_food'];
	}
?>
</textarea>
</td>
</tr>

<tr>
<td>
Instructions For Hotel  </span> : 
</td>

<td>
<textarea id="hotel_instructions" class="hotel_instructions" name="hotel_instructions"  cols="8" rows="4">
<?php
if(validateForNull($booking_form_details))
	{
	echo $booking_form_details['instructions_hotel'];
	}
?>
</textarea>
</td>
</tr>

<tr>
<td>
Instructions For Extra Hotel Booking </span> : 
</td>

<td>
<textarea id="extra_hotel_instructions" class="extra_hotel_instructions" name="extra_hotel_instructions"  cols="8" rows="4">
<?php
if(validateForNull($booking_form_details))
	{
	echo $booking_form_details['instructions_extra_hotel'];
	}
?>
</textarea>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Recommended By :
</td>

<td>
<input type="text" name="reccoBy" id="txtName" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['recommended_by'];
					}
					
					?>" /> 
</td>
</tr>

</table>


<h4 class="headingAlignment"> For Office Use </h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;" class="noBorder">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable inventory_table" id="productPurchaseTable">
    		<tr>
                <th class='heading'></th>
            	<th class='heading'>Mode of transport</th>
                 <th class='heading'>Date</th>
                 <th class='heading'>Train/Flight No</th>
                 <th class='heading'></th>
                 
                 
            </tr>
            <tbody>
            	<tr>
                
                    <td>
                    	Departure :
                    </td>
                    
                    <td>
                         <select  name="departure_mode" style="width:160px;">
                         <option value="-1">-- Please Select -- </option>
                             <option value="railway" <?php if(validateForNull($booking_form_details) && $booking_form_details['departure_transport_mode'] == 'railway') { ?> selected="selected" <?php } ?>>Railway</option>
                              <option value="air" <?php if(validateForNull($booking_form_details) && $booking_form_details['departure_transport_mode'] == 'air') { ?> selected="selected" <?php } ?>>Air</option>
                          </select> 
                            
                      </td>
                    
                    <td> 
                     <input type="text"  size="12" autocomplete="off"  name="departure_date" class="datepicker1 datepick" placeholder="Click to Select!" value="<?php 
					if(validateForNull($booking_form_details) && $booking_form_details['departure_transport_mode_date'] != "1970-01-01 00:00:00")
					{
					
						echo date('d/m/Y', strtotime($booking_form_details['departure_transport_mode_date']));
					}
					
					?>" /> 
                    </td>
                    
                    
                    <td>
                    	<input name="departure_no" type="text" style="width:160px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						
						echo $booking_form_details['departure_transport_mode_no'];
					}
					
					?>" /> 
                    </td>
                    
                    
                    <td>
                         <select  name="departure_company_type" style="width:180px;">
                         
                         <option value="-1">-- Please Select -- </option>
                             <option value="ABPC" <?php if(validateForNull($booking_form_details) && $booking_form_details['departure_transport_mode_company'] == 'ABPC') { ?> selected="selected" <?php } ?>>ABPC</option>
                              <option value="Self" <?php if(validateForNull($booking_form_details) && $booking_form_details['departure_transport_mode_company'] == 'Self') { ?> selected="selected" <?php } ?>>Self</option>
                          </select> 
                            
                    </td>
                    
                   
                </tr>
            </tbody>
            
            <tbody>
            	<tr>
                
                    <td>
                    	Middle :
                    </td>
                    
                    <td>
                         <select  name="middle_mode" style="width:160px;">
                         <option value="-1">-- Please Select -- </option>
                             
                             <option value="railway" <?php if(validateForNull($booking_form_details) && $booking_form_details['middle_transport_mode'] == 'railway') { ?> selected="selected" <?php } ?>>Railway</option>
                              <option value="air" <?php if(validateForNull($booking_form_details) && $booking_form_details['middle_transport_mode'] == 'air') { ?> selected="selected" <?php } ?>>Air</option>
                          </select> 
                            
                      </td>
                    
                    <td> 
                     <input type="text" size="12" autocomplete="off"  name="middle_date" class="datepicker2 datepick" placeholder="Click to Select!" value="<?php 
					if(validateForNull($booking_form_details) && $booking_form_details['middle_transport_mode_date'] != "1970-01-01 00:00:00")
					{
						echo date('d/m/Y', strtotime($booking_form_details['middle_transport_mode_date']));
					}
					
					?>" /> 
                    </td>
                    
                    
                     <td>
                    	<input name="middle_no" type="text" style="width:160px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['middle_transport_mode_no'];
					}
					
					?>" /> 
                    </td>
                    
                    
                    
                    <td>
                         <select  name="middle_company_type" style="width:180px;">
                         <option value="-1">-- Please Select -- </option>
                             <option value="ABPC" <?php if(validateForNull($booking_form_details) && $booking_form_details['middle_transport_mode_company'] == 'ABPC') { ?> selected="selected" <?php } ?>>ABPC</option>
                              <option value="Self" <?php if(validateForNull($booking_form_details) && $booking_form_details['middle_transport_mode_company'] == 'Self') { ?> selected="selected" <?php } ?>>Self</option>
                          </select> 
                            
                    </td>
                    
                   
                </tr>
            </tbody>
            
            <tbody>
            	<tr>
                
                    <td>
                    	Return :
                    </td>
                    
                    <td>
                         <select  name="return_mode" style="width:160px;">
                         <option value="-1">-- Please Select -- </option>
                             <option value="railway" <?php if(validateForNull($booking_form_details) && $booking_form_details['return_transport_mode'] == 'railway') { ?> selected="selected" <?php } ?>>Railway</option>
                              <option value="air" <?php if(validateForNull($booking_form_details) && $booking_form_details['return_transport_mode'] == 'air') { ?> selected="selected" <?php } ?>>Air</option>
                          </select> 
                            
                      </td>
                    
                    <td> 
                     <input type="text" size="12" autocomplete="off"  name="return_date" class="datepicker2 datepick" placeholder="Click to Select!" value="<?php 
					if(validateForNull($booking_form_details) && $booking_form_details['return_transport_mode_date'] != "1970-01-01 00:00:00")
					{ 
						echo date('d/m/Y', strtotime($booking_form_details['return_transport_mode_date']));
					}
					
					?>" /> 
                    </td>
                    
                     <td>
                    	<input name="return_no" type="text" style="width:160px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['return_transport_mode_no'];
					}
					
					?>" /> 
                    </td>
                    
                    
                    
                    
                    <td>
                         <select  name="return_company_type" style="width:180px;">
                          <option value="-1">-- Please Select -- </option>
                              <option value="ABPC" c>ABPC</option>
                              <option value="Self" <?php if(validateForNull($booking_form_details) && $booking_form_details['return_transport_mode_company'] == 'Self') { ?> selected="selected" <?php } ?>>Self</option>
                          </select> 
                            
                    </td>
                    
                   
                </tr>
            </tbody>
            
    	</table>
    </td>

</tr>
</table>





<h4 class="headingAlignment"> Total Amount </h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;" class="noBorder">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable inventory_table" id="productPurchaseTable">
    		<tr>
                <th class="heading"></th>
                <th class="heading">Amount</th>
            	<th class='heading'>Receipt No.</th>
                 <th class='heading'>Date</th>
                 <th class='heading'>Tax(%)</th>
                 <th class='heading'>Tax Amount</th>
                 <th class='heading'>Final Amount</th>
                 
            </tr>
            <tbody>
            	<tr>
                
                    <td>
                    	Total Amount Receivable  :
                    </td>
                    
                    
                    <td>
                
					<input type="text" name="total_amount" id="total_amount" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['total_amount_raw'];
					}
					else
					echo 0;
					?>" style="width:100px;" onchange="countDiscountOnTotalAmount()"/>

                      </td>

				    <td>
                
					<input type="text" name="total_receiptNo" id="total_receiptNo" style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['total_amount_receipt'];
					}
					
					?>"/>

                      </td>

                    
                    
                    <td> 
                     <input type="text" size="12" autocomplete="off"  name="total_date" class="datepicker2 datepick" placeholder="Click to Select!"  style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details) && $booking_form_details['total_amount_date'] != "1970-01-01 00:00:00")
					{
						
						echo date('d/m/Y', strtotime($booking_form_details['total_amount_date']));
					}
					
					?>"/>
                    </td>
                    
                    
                    <td>
                         <select  name="total_tax_percentage" style="width:120px;" onchange="countDiscountOnTotalAmount()" id="total_tax_percentage">
                              <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['total_amount_tax_percentage'] == 0) { ?> selected="selected" <?php } ?>>None</option>
                              <option value="3.5" <?php if(validateForNull($booking_form_details) && $booking_form_details['total_amount_tax_percentage'] == 3.5) { ?> selected="selected" <?php } ?>>3.5%</option>
                              
                          </select> 
                            
                    </td>
                    
                    
                    <td>
                
					<input type="text" name="total_taxAmount" id="total_taxAmount" style="width:100px;" onchange="countTaxAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['total_amount_tax_amount'];
					}
					else
					echo 0;
					
					?>"/>

                      </td>
                      
                      <td>
                
					<input type="text" name="total_finalAmount" id="total_finalAmount" style="width:100px;" onchange="countDiscountOnTotalAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['total_amount_after_tax'];
					}
					else
					echo 0;
					
					?>"/>

                      </td>
                   
                    
                   
                </tr>
            </tbody>
            
            <tbody>
            	<tr>
                
                    <td>
                    	Extra vehicle Fare  :
                    </td>
                    
                    <td>
                
					<input type="text" name="extra_vehicle_amount" id="extra_vehicle_amount" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_vehicle_amount_raw'];
					}
					else
					echo 0;
					?>" style="width:100px;" onchange="countDiscountOnExtraVehicleFare()"/>

                      </td>

				    <td>
                
					<input type="text" name="extra_vehicle_receiptNo" id="extra_vehicle_receiptNo"  style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_vehicle_amount_receipt'];
					}
					?>" />

                      </td>

                    
                    
                    <td> 
                     <input type="text" size="12" autocomplete="off"  name="extra_vehicle_date" class="datepicker2 datepick" placeholder="Click to Select!"  style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details) && $booking_form_details['extra_vehicle_amount_date'] != "1970-01-01 00:00:00")
					{
						
						echo date('d/m/Y', strtotime($booking_form_details['extra_vehicle_amount_date']));
					}
					?>" />
                    </td>
                    
                    
                    <td>
                         <select  name="extra_vehicle_tax_percentage" style="width:120px;" id="extra_vehicle_tax_percentage" onchange="countDiscountOnExtraVehicleFare()">
                              <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_vehicle_amount_tax_percentage'] == 0) { ?> selected="selected" <?php } ?>>None</option>
                              <option value="5.6" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_vehicle_amount_tax_percentage'] == 5.6) { ?> selected="selected" <?php } ?>>5.6%</option>
                              
                          </select> 
                            
                    </td>
                    
                    
                    <td>
                
					<input type="text" name="extra_vehicle_taxAmount" id="extra_vehicle_taxAmount"  style="width:100px;" onchange="countTaxAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_vehicle_amount_tax_amount'];
					}
					else
					echo 0;
					?>"/>

                      </td>
                      
                      <td>
                
					<input type="text" name="extra_vehicle_finalAmount" id="extra_vehicle_finalAmount" style="width:100px;" onchange="countDiscountOnTotalAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_vehicle_amount_after_tax'];
					}
					else
					echo 0;
					?>"/>

                      </td>
                   
                    
                    
                   
                </tr>
            </tbody>
            
            
            <tbody>
            	<tr>
                
                    <td>
                    
                    	Extra Hotel Fare  :
                    </td>
                    
                    <td>
                
					<input type="text" name="extra_hotel_amount" id="extra_hotel_amount" style="width:100px;" onchange="countDiscountOnExtraHotelFare()" style="width:100px;" onchange="countDiscountOnTotalAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_hotel_amount_raw'];
					}
					else
					echo 0;
					?>"/>

                      </td>

				   <td>
                
					<input type="text" name="extra_hotel_receiptNo" id="extra_hotel_receiptNo" style="width:100px;"value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_hotel_amount_receipt'];
					}
					
					?>"/>

                      </td>

                    
                    
                    <td> 
                     <input type="text"  size="12" autocomplete="off"  name="extra_hotel_date" class="datepicker2 datepick" placeholder="Click to Select!"  style="width:100px;"value="<?php 
					if(validateForNull($booking_form_details) && $booking_form_details['extra_hotel_amount_date'] != "1970-01-01 00:00:00")
					{
						
						echo date('d/m/Y', strtotime($booking_form_details['extra_hotel_amount_date']));
					}
					
					?>"/>
                    </td>
                    
                    
                    <td>
                         <select  name="extra_hotel_tax_percentage" style="width:120px;" id="extra_hotel_tax_percentage" onchange="countDiscountOnExtraHotelFare()">
                              <option value="0" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_hotel_amount_tax_percentage'] == 0) { ?> selected="selected" <?php } ?>>None</option>
                              <option value="1.4" <?php if(validateForNull($booking_form_details) && $booking_form_details['extra_hotel_amount_tax_percentage'] == 1.4) { ?> selected="selected" <?php } ?>>1.4%</option>
                              
                          </select> 
                            
                    </td>
                    
                    
                    <td>
                
					<input type="text" name="extra_hotel_taxAmount" id="extra_hotel_taxAmount" style="width:100px;" onchange="countTaxAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_hotel_amount_percentage_amount'];
					}
					else
					echo 0;
					?>"/>

                      </td>
                      
                      <td>
                
					<input type="text" name="extra_hotel_finalAmount" id="extra_hotel_finalAmount" style="width:100px;" onchange="countDiscountOnTotalAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_hotel_amount_after_tax'];
					}
					else
					echo 0;
					?>"/>

                      </td>
                   
               </tr>
            </tbody>
            
            
            <tbody>
            	<tr>
                
                    <td>
                    	Extra Air Fare  :
                    </td>
                    
                    <td>
                
					<input type="text" name="extra_air_amount" id="extra_air_amount" style="width:100px;" onchange="countDiscountOnExtraAirFare()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_air_amount_raw'];
					}
					else
					echo 0;
					?>"/>

                      </td>

				    <td>
                
					<input type="text" name="extra_air_receiptNo" id="extra_air_receiptNo" style="width:100px;" onchange="countDiscountOnExtraAirFare()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_air_amount_receipt'];
					}
					
					?>"/>


                      </td>

                    
                    
                    <td> 
                     <input type="text" size="12" autocomplete="off"  name="extra_air_date" class="datepicker2 datepick" placeholder="Click to Select!"  style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details) && $booking_form_details['extra_air_amount_date'] != "1970-01-01 00:00:00")
					{
						
						echo date('d/m/Y', strtotime($booking_form_details['extra_air_amount_date']));
					}
					
					?>"/>
                    </td>
                    
                    
                    <td>
                         <select  name="extra_air_tax_percentage" style="width:120px;" id="extra_air_tax_percentage" onchange="countDiscountOnExtraAirFare()">
                              <option value="0">None</option>
                              
                          </select> 
                            
                    </td>
                    
                    
                    <td>
                
					<input type="text" name="extra_air_taxAmount" id="extra_air_taxAmount"  style="width:100px;" onchange="countTaxAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_air_amount_tax_amount'];
					}
					else
					echo 0;
					
					?>"/>

                      </td>
                      
                      <td>
                
					<input type="text" name="extra_air_finalAmount" id="extra_air_finalAmount" style="width:100px;" onchange="countDiscountOnTotalAmount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['extra_air_amount_after_tax'];
					}
					else
					echo 0;
					
					?>"/>

                      </td>
                   
                    
                   
                </tr>
            </tbody>
            
            
            
            <tbody>
            	<tr>
                
                    <td>
                    	RR   :
                    </td>
                    
                    <td>
                
					<input type="text" name="rr_amount" id="rr_amount" style="width:100px;" onchange="countFinalAmontAfterDiscount()" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['discount_amount'];
					}
					else
					echo 0;
					?>"/>

                      </td>
                    

				    <td>
                
					<input type="text" name="rr_receiptNo" id="rr_receiptNo" style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['discount_receipt_no'];
					}
					
					?>"/>

                      </td>

                    
                    
                    <td> 
                     <input type="text" size="12" autocomplete="off"  name="rr_date" class="datepicker2 datepick" placeholder="Click to Select!"  style="width:100px;"/>

                    </td>
                    
                    
                    <td>
                         
                          
                    </td>
                    
                    
                    <td>
                
					

                      </td>
                      
                      <td>
                
					<input type="text" name="finalAmount" id="finalAmount" style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['discount_amount'];
					}
					else
					echo 0;
					?>"/>

                      </td>
                   
                    
                   
                </tr>
            </tbody>
            
            
            
            
            
            <tbody>
            	<tr>
                
                    <td>
                    	GRAND TOTAL  :
                    </td>
                    
                    <td>
                    </td>
                    
                    <td>
                    </td>
                    
                    <td>
                    </td>
                    
                    <td>
                    </td>

                    
                    
                    <td>
                
					<input type="text" name="grand_taxAmount" id="grand_taxAmount" style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['total_tax_amount'];
					}
					else
					echo 0;
					?>"/>

                      </td>
                      
                      <td>
                
					<input type="text" name="grand_finalAmount" id="grand_finalAmount"  style="width:100px;" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['total_after_discount'];
					}
					else
					echo 0;
					?>"/>


                      </td>
                   
                    
                   
                </tr>
            </tbody>
            
            
            
            
            
    	</table>
    </td>

</tr>
</table>





<table class="noBorder">



<tr>

<td class="firstColumnStyling" width="220">
Booked By :
</td>

<td>
<?php
$admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
?>
<input type="hidden" name="booked_by" value="<?php if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['booked_by'];
					} 
					else
					echo $admin_session_id ?>" />
<?php
if(validateForNull($booking_form_details))
					{
						echo getAdminUserNameByID($booking_form_details['booked_by']);
					} 
					else
echo getAdminUserNameByID($admin_session_id);
?>
</td>

</tr>

<tr>


<tr>

<td class="firstColumnStyling" width="220">
RR CNF :
</td>


				<td>
					<select id="admin_id" name="rr_cnf">
                       
                        <?php
                            $users = listAdminUsers();
                            foreach($users as $user)
							
                              {
								 $userId = $user['admin_id'];
								 $teamIdData = getTeamIdByAdminId($userId);
								 $teamId = $teamIdData['team_id'];
								 $teamNamedata = getTeamNameByTeamId($teamId);
								 $team_name = $teamNamedata['team_name']; 
                             ?>
                             
                             <option value="<?php echo $user['admin_id'] ?>" <?php if($user['admin_id'] == $current_holder) { ?> selected="selected" <?php } ?>> <?php echo $user['admin_name']. " "."(".$team_name.")" ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                       
</td>

</tr>


<tr>

<td class="firstColumnStyling" width="220">
Checked By :
</td>


				<td>
					<select id="admin_id" name="checked_by">
                       
                        <?php
                            $users = listAdminUsers();
                            foreach($users as $user)
							
                              {
								 $userId = $user['admin_id'];
								 $teamIdData = getTeamIdByAdminId($userId);
								 $teamId = $teamIdData['team_id'];
								 $teamNamedata = getTeamNameByTeamId($teamId);
								 $team_name = $teamNamedata['team_name']; 
                             ?>
                             
                             <option value="<?php echo $user['admin_id'] ?>" <?php if($user['admin_id'] == $current_holder) { ?> selected="selected" <?php } ?>> <?php echo $user['admin_name']. " "."(".$team_name.")" ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                       
</td>

</tr>


<tr>

<td class="firstColumnStyling" width="220">
Computer Entry :
</td>

<td>
<input type="text" name="computerEntry" id="computerEntry" value="<?php 
					if(validateForNull($booking_form_details))
					{
						echo $booking_form_details['computer_entry'];
					}
					
					?>" /> 

</td>

</tr>

<tr>
<td class="firstColumnStyling" width="220"></td>
<td>
<input type="submit" value="Submit" class="btn btn-warning">

<a href="<?php  echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id;  ?>">
<input type="button" value="back" class="btn btn-success" />
</a>

</td>
</tr>



</table>
</form>


</div>
<div class="clearfix"></div>

<script>

function countAge(el)
{
	var dob_member = el.value;
	dob_member = dob_member.trim();
	
	
   var dat = dob_member.substring(0,2);
  	 var mont = dob_member.substring(3,5);
	var yea =  dob_member.substring(6);
	mont = mont - 1;
	var dob_mem = new Date(yea,mont,dat);
 
	
    var ageDifMs = <?php echo strtotime($enquiryDetails['purchase_date'])."000"; ?> - dob_mem.getTime();
	
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    age = Math.abs(ageDate.getUTCFullYear() - 1970);
	
	var next_td_children=$(el).parent().next().children('input');
	
	next_td_children.val(age);
}

</script>


<script>

function countDiscountOnTotalAmount()
{
var total_amount = document.getElementById("total_amount").value;
var total_tax_percentage = document.getElementById("total_tax_percentage").value;


total_amount = parseFloat(total_amount);
total_tax_percentage = parseFloat(total_tax_percentage);
var mul = total_amount*total_tax_percentage;

var total_taxAmount = mul/100;

document.getElementById('total_taxAmount').value = total_taxAmount;
document.getElementById('total_finalAmount').value = total_amount + total_taxAmount;

countTaxAmount();
countFinalAmontAfterDiscount();
countFinalAmount();

}

function countDiscountOnExtraVehicleFare()
{
var extra_vehicle_amount = document.getElementById("extra_vehicle_amount").value;
var extra_vehicle_tax_percentage = document.getElementById("extra_vehicle_tax_percentage").value;


extra_vehicle_amount = parseFloat(extra_vehicle_amount);
extra_vehicle_tax_percentage = parseFloat(extra_vehicle_tax_percentage);
var mul = extra_vehicle_amount*extra_vehicle_tax_percentage;

var extra_vehicle_taxAmount = mul/100;

document.getElementById('extra_vehicle_taxAmount').value = extra_vehicle_taxAmount;
document.getElementById('extra_vehicle_finalAmount').value = extra_vehicle_amount + extra_vehicle_taxAmount;

countTaxAmount();
countFinalAmontAfterDiscount();
countFinalAmount();

}

function countDiscountOnExtraHotelFare()
{
var extra_hotel_amount = document.getElementById("extra_hotel_amount").value;
var extra_hotel_tax_percentage = document.getElementById("extra_hotel_tax_percentage").value;


extra_hotel_amount = parseFloat(extra_hotel_amount);
extra_hotel_tax_percentage = parseFloat(extra_hotel_tax_percentage);

var mul = extra_hotel_amount*extra_hotel_tax_percentage;

var extra_hotel_taxAmount = mul/100;

document.getElementById('extra_hotel_taxAmount').value = extra_hotel_taxAmount;
document.getElementById('extra_hotel_finalAmount').value = extra_hotel_amount + extra_hotel_taxAmount;

countTaxAmount();
countFinalAmontAfterDiscount();
countFinalAmount();

}

function countDiscountOnExtraAirFare()
{
var extra_air_amount = document.getElementById("extra_air_amount").value;
var extra_air_tax_percentage = document.getElementById("extra_air_tax_percentage").value;


extra_air_amount = parseFloat(extra_air_amount);
extra_air_tax_percentage = parseFloat(extra_air_tax_percentage);

var mul = extra_air_amount*extra_air_tax_percentage;

var extra_air_taxAmount = mul/100;

document.getElementById('extra_air_taxAmount').value = extra_air_taxAmount;
document.getElementById('extra_air_finalAmount').value = extra_air_amount + extra_air_taxAmount;


countTaxAmount();
countFinalAmontAfterDiscount();
countFinalAmount();

	
}


function countFinalAmontAfterDiscount()
{
	
var extra_air_amount =0;
var extra_hotel_amount=0;
var extra_vehicle_amount=0;
var total_final_amount=0;
var discount_amount=0;
	
var extra_air_amount = document.getElementById('extra_air_finalAmount').value;
if(extra_air_amount == '')
{
	extra_air_amount = 0;
}
var extra_hotel_amount = document.getElementById('extra_hotel_finalAmount').value;
if(extra_hotel_amount == '')
{
	extra_hotel_amount = 0;
}
var extra_vehicle_amount = document.getElementById('extra_vehicle_finalAmount').value;
if(extra_vehicle_amount == '')
{
	extra_vehicle_amount = 0;
}
var total_final_amount = document.getElementById('total_finalAmount').value;
if(total_final_amount == '')
{
	total_final_amount = 0;
}

var discount_amount = document.getElementById('rr_amount').value;
if(discount_amount == '')
{
	discount_amount = 0;
}


extra_air_amount = parseFloat(extra_air_amount);
extra_hotel_amount = parseFloat(extra_hotel_amount);
extra_vehicle_amount = parseFloat(extra_vehicle_amount);
total_final_amount = parseFloat(total_final_amount);
discount_amount = parseFloat(discount_amount);



var add = total_final_amount + extra_hotel_amount + extra_air_amount + extra_vehicle_amount;

var grand_final_amount = add - discount_amount;

document.getElementById('finalAmount').value = grand_final_amount;
document.getElementById('grand_finalAmount').value = grand_final_amount;

countFinalAmount();
}


function countFinalAmount()
{
	
var extra_air_amount =0;
var extra_hotel_amount=0;
var extra_vehicle_amount=0;
var total_final_amount=0;

var extra_air_amount = document.getElementById('extra_air_finalAmount').value;
if(extra_air_amount == '')
{
	extra_air_amount = 0;
}
var extra_hotel_amount = document.getElementById('extra_hotel_finalAmount').value;
if(extra_hotel_amount == '')
{
	extra_hotel_amount = 0;
}
var extra_vehicle_amount = document.getElementById('extra_vehicle_finalAmount').value;
if(extra_vehicle_amount == '')
{
	extra_vehicle_amount = 0;
}
var total_final_amount = document.getElementById('total_finalAmount').value;
if(total_final_amount == '')
{
	total_final_amount = 0;
}

extra_air_amount = parseFloat(extra_air_amount);
extra_hotel_amount = parseFloat(extra_hotel_amount);
extra_vehicle_amount = parseFloat(extra_vehicle_amount);
total_final_amount = parseFloat(total_final_amount);

var add = total_final_amount + extra_hotel_amount + extra_air_amount + extra_vehicle_amount;

document.getElementById('grand_finalAmount').value = grand_final_amount;
	
}

function countTaxAmount()
{
	
var add = 0;
var total_taxAmount = 0;
var extra_vehicle_taxAmount = 0;
var extra_hotel_taxAmount = 0;
var extra_air_taxAmount = 0;
	
var total_taxAmount = document.getElementById('total_taxAmount').value;
if(total_taxAmount == '')
{
	total_taxAmount = 0;
}
var extra_vehicle_taxAmount = document.getElementById('extra_vehicle_taxAmount').value;
if(extra_vehicle_taxAmount == '')
{
	extra_vehicle_taxAmount = 0;
}
var extra_hotel_taxAmount = document.getElementById('extra_hotel_taxAmount').value;
if(extra_hotel_taxAmount == '')
{
	extra_hotel_taxAmount = 0;
}
var extra_air_taxAmount = document.getElementById('extra_air_taxAmount').value;
if(extra_air_taxAmount == '')
{
	extra_air_taxAmount = 0;
}

total_taxAmount = parseFloat(total_taxAmount);
extra_vehicle_taxAmount = parseFloat(extra_vehicle_taxAmount);
extra_hotel_taxAmount = parseFloat(extra_hotel_taxAmount);
extra_air_taxAmount = parseFloat(extra_air_taxAmount);

var add = total_taxAmount + extra_vehicle_taxAmount + extra_hotel_taxAmount + extra_air_taxAmount;

document.getElementById('grand_taxAmount').value = add;
	
}

</script>