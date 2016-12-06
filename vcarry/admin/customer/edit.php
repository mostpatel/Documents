<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

/*if(!isset($_GET['access']) && $_GET['access']="approved")
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}*/
$customer_id=$_GET['id'];

if(is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
	$contact_person_details = getContactPersonDetailsForCustomerId($customer_id);
	
	$customer_id=$customer['customer_id'];
	
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editCustomer'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">
<input name="lid" value="<?php echo $customer_id ?>" type="hidden">

<table id="insertCustomerTable" class="insertTableStyling no_print">
<?php if(CUSTOMER_NO==1) { ?>
<tr>
<td width="230px" class="firstColumnStyling">
Customer No<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_no" id="customer_no" class="person_name" placeholder="Only Letters and numbers"  autofocus="autofocus" value="<?php echo $customer['customer_no']; ?>" />
</td>
</tr>
<?php } ?>

<tr>
<td width="230px" class="firstColumnStyling">
Date of joining<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="doj" id="doj" class="dob" placeholder="dd/mm/yyyy" value="<?php echo date('d/m/Y',strtotime($customer['date_of_joining'])); ?>		"  readonly />
</td>
</tr>

    <tr>
<td>Prefix<span class="requiredField">* </span> : </td>
<td><select name="prefix" id="prefix" >
	<?php $prefix=listPrefix();
	foreach($prefix as $p)
	{
	 ?>
     <option value="<?php echo $p['prefix_id']; ?>" <?php if($p['prefix_id']==$customer['prefix_id']) { ?> selected <?php } ?>><?php echo $p['prefix']; ?></option>
     <?php } ?>
</select></td>
</tr>

<tr>

<td width="220px" class="firstColumnStyling">
 Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" class="person_name" placeholder="Only Letters" value="<?php echo $customer['customer_name']; ?>"/>
</td>
</tr>

<tr>
<td>
Address Line 1<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_address" id="customer_address" class="address" value="<?php $address=str_replace(array('<pre>','</pre>'),"",$customer['customer_address']);  echo $address; ?>" />
</td>
</tr>

<tr>
<td>
Address Line 2: 
</td>

<td>
<input type="text" name="customer_address2" id="customer_address2" class="address" value="<?php $address=str_replace(array('<pre>','</pre>'),"",$customer['customer_address2']);  echo $address; ?>" />
</td>
</tr>


<tr>
<td>City<span class="requiredField">* </span> : </td>
				<td>
					<select id="customer_city_id" name="customer_city_id" class="city" onchange="createDropDownAreaCustomer(this.value)">
                        <option value="-1" >--Please Select--</option>
                        <?php
                            $cities = listCitiesAlpha();
                            foreach($cities as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['city_id'] ?>" <?php if($super['city_id']==$customer['city_id']) { ?> selected <?php } ?>><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area<span class="requiredField">* </span> : </td>
				<td>
					<select name="customer_area" class="city_area" id="city_area1"  >
                    	<option value="-1">--Please Select City--</option>
                        <?php $areas = listAreasAlphaFromCity($customer['city_id']);
						  foreach($areas as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['area_id'] ?>" <?php if($super['area_id']==$customer['area_id']) { ?> selected <?php } ?>><?php echo $super['area_name'] ?></option					>
                             <?php } ?>
						
                    </select>
                            </td>
</tr>

<tr>
<td>Pincode : </td>
<td><input type="text" name="customer_pincode" id="customer_pincode" class="pincode" placeholder="6 Digits!" value="<?php if($customer['customer_pincode']!=0) echo $customer['customer_pincode']; ?>"/></td>
</tr>




<?php  $contactNumbers = $customer['contact_no'];
$lj=0;
foreach($contactNumbers as $contact)
{
 ?>
  <tr>
            <td>
            Contact No<?php if($lj==0) { ?><span class="requiredField">* </span> <?php } ?> : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" <?php if($lj==0) { ?> id="customerContact" <?php } ?> name="customerContact[]" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> placeholder="10 Digits!" value="<?php echo $contact[0]; ?>" /><span></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
<?php
$lj++;
 } ?> 

   <tr>
<td>Prefix<span class="requiredField">* </span> : </td>
<td><select name="contact_prefix" id="contact_prefix" >
	<?php $prefix=listPrefix();
	foreach($prefix as $p)
	{
	 ?>
     <option value="<?php echo $p['prefix_id']; ?>" <?php if($p['prefix_id']==$contact_person_details['prefix_id']) { ?> selected <?php } ?>><?php echo $p['prefix']; ?></option>
     <?php } ?>
</select></td>
</tr>
<tr id="">
                <td>
                Contact Person<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="" id="contactPerson" name="cp_name"  value="<?php if(validateForNull($contact_person_details['cp_name'])) echo $contact_person_details['cp_name'];?>" /> 
                </td>
            </tr>            


<tr>

<tr id="">
                <td>
             Contact Person Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="guarantorContact" name="cp_con_no" placeholder="10 Digits!" onblur="checkForDuplicateContactNo(this.value);" value="<?php if(validateForNull($contact_person_details['cp_contact_no_1'])) echo $contact_person_details['cp_contact_no_1']; ?>" /> 
                </td>
            </tr>
<tr>
<td width="230px" class="firstColumnStyling">
Email : 
</td>

<td>
<input type="text" name="email" id="txtEmail" class="email" placeholder="Only Valid Email Address" value="<?php if(validateForNull($contact_person_details['cp_email']) && $contact_person_details['cp_email']!="NA") echo $contact_person_details['cp_email'];  ?>" />
</td>
</tr>

<tr>
<td width="230px" class="firstColumnStyling">
Contact Person DOB : 
</td>

<td>
<input type="text" name="cp_dob" id="contact_person_dob" class="dob datepicker1" placeholder="dd/mm/yyyy" value="<?php if(validateForNull($contact_person_details['cp_dob']) && $contact_person_details['cp_dob']!="1900-01-01") echo date('d/m/Y',strtotime($contact_person_details['cp_dob']));   ?>"  />
</td>
</tr>

<tr>
<td width="230px" class="firstColumnStyling">
Contact Person Anniversary : 
</td>

<td>
<input type="text" name="cp_anniversary" id="contact_person_anniversary" class="dob datepicker2" placeholder="dd/mm/yyyy" value="<?php if(validateForNull($contact_person_details['cp_anniversary'])  && $contact_person_details['cp_anniversary']!="1900-01-01") echo date('d/m/Y',strtotime($contact_person_details['cp_anniversary']));   ?>" />
</td>
</tr>

<tr>
<td>Through:
</td>
<td>
<select name="through" onChange="changeGroup(this.value)">
	<option value="1" <?php if($customer['customer_through']==1) { ?> selected <?php } ?>>Agent</option>
    <option value="2" <?php if($customer['customer_through']==2) { ?> selected <?php } ?>>Customer</option>
</select>
</td>
</tr>


<tr id="customer_group" <?php if($customer['customer_through']==1) { ?> style="display:none;" <?php } ?>>
<td> Customer Group : </td>
				<td>
					<select id="bs3Select" name="customer_group_id[]" data-live-search="true" class="city_area selectpicker" >
                       
                        <?php
                            $listFileGroups = listCustomerGroups();
                            foreach($listFileGroups as $customerGroup)
							
                              {
								 
                             ?>
                             
                             <option value="<?php echo $customerGroup['group_id'] ?>" <?php if(in_array($customerGroup['group_id'], $selected_customer_group_name_array)) { ?> selected="selected" <?php } ?>> <?php echo $customerGroup['group_name'] ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr  id="broker_group" <?php if($customer['customer_through']==2) { ?> style="display:none;" <?php } ?>>
<td> <?php echo BROKER_NAME; ?> Group : </td>
				<td>
					<select id="bs3Select" name="broker_group_id[]" data-live-search="true" class="city_area selectpicker"  >
                       
                        <?php
                            $listFileGroups = listBrokers();
                            foreach($listFileGroups as $customerGroup)
							
                              {
								 
                             ?>
                             
                             <option value="<?php echo $customerGroup['ledger_id'] ?>" <?php if(in_array($customerGroup['ledger_id'], $selected_customer_group_name_array)) { ?> selected="selected" <?php } ?>> <?php echo $customerGroup['ledger_name'] ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                            </td>
</tr>


 <tr>
<td> Opening Balance  : </td>
<td> <input type="text" name="opening_balance" value="<?php echo $customer['opening_balance']; ?>"/> <select name="opening_balance_cd" class="credit_debit"><option value="0" <?php if(isset($customer['opening_cd']) && $customer['opening_cd']==0) { ?> selected="selected" <?php } ?>>Debit</option><option value="1" <?php if(isset($customer['opening_cd']) && $customer['opening_cd']==1) { ?> selected="selected" <?php } ?>>Credit</option> </select> </tr>
</tr>

<!--


<tr>
<td>PAN No : </td>
<td><input type="text" name="pan_no" id="pan_no" placeholder="PAN Number!" value="<?php echo $customer['pan_no']; ?>"/></td>
</tr>


<tr>
<td>TIN No : </td>
<td><input type="text" name="tin_no" id="tin_no"  placeholder="TIN Number!" value="<?php echo $customer['tin_no']; ?>"/></td>
</tr>

<tr>
<td>CST No : </td>
<td><input type="text" name="cst_no" id="cst_no" placeholder="CST Number!" value="<?php echo $customer['cst_no']; ?>" /></td>
</tr>


<tr>
<td>Service Tax No : </td>
<td><input type="text" name="service_tax_no" id="service_tax_no" value="<?php echo $customer['service_tax_no']; ?>" placeholder="Service Tax Number!"/></td>
</tr>

-->
<tr>
<td>Notes : </td>
<td><textarea  name="notes" id="notes"  placeholder="Any Remarks!" ><?php echo $customer['notes']; ?></textarea></td>
</tr>

<!-- for regenreation purpose Please donot delete -->

<tr id="customerProofTypeTr">
<td>Proof type : </td>
				<td id="customerProofTypeTd">
					<select id="proof" name="customerProofId[]" class="customerProofId" onblur="checkProofId(this.value,this)">
                        <option value="-1" >--Please Select Proof Type--</option>
                        <?php
                            $types = listProofTypes();
                            foreach($types as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['human_proof_type_id'] ?>"><?php echo $super['proof_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select><span class="ValidationErrors contactNoError">Please select a Proof Type!</span> 
                            </td>
</tr>




<tr id="customerProofNoTr">
<td> Proof Number : </td>
<td id="customerProofNoTd"><input type="text" name="customerProofNo[]" autocomplete="off" class="customerProofNo" placeholder="Only Letters and Digits!" onblur="checkProofNo(this.value,this)"/><span class="ValidationErrors contactNoError">Please enter Proof No (only numbers and letters)!</span></td>
</tr>


<tr id="customerProofImgTr">
<td>
Proof Image : 
<br />(.jpg,.jpeg,.png,.gif,.pdf)
</td>
<td>
<input type="file" name="" class="customerFile"  /><br /> - OR - <br /><input type="button" name="scanProof" class="btn scanBtn" value="scan" /><input type="button" value="+" class="btn btn-primary addscanbtnCustomer"/>
</td>
</tr> 

<!-- end of used for regeneration -->
</table>

<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="250px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Proof" id="addCustomerProofBtn"/></td>
</tr> 

    
</table>

<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="250px;"> </td>
<td><input id="disableSubmit" type="submit" class="btn btn-warning" value="Edit"/>
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$customer_id ?>"><input type="button" value="Back" class="btn btn-success" /></a>
</td>
</tr> 

    
</table>



</form>

</div>
<div class="clearfix"></div>
<script>
 function changeGroup(val)
	{
		if(val==1)
		{
		$('#customer_group').hide();
		$('#broker_group').show();
		}
		else
		{
		$('#customer_group').show();
		$('#broker_group').hide();
			
		}
		
	}
</script>