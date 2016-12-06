<?php 

		 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=list"><button class="btn btn-success">View All Customers</button></a> </div>
<h4 class="headingAlignment no_print">Add Education Details</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">
<input type="hidden" name="agency_id" value="oc3"  />
<table id="insertCustomerTable" class="insertTableStyling no_print">

<tr>
<td width="230px" class="firstColumnStyling">
Medium Of Instruction<span class="requiredField">* </span> : 
</td>

<td>
<table>
<tr>
</tr>
</table>
<input type="radio" name="customer_no" id="customer_no" class="person_name" placeholder="Only Letters and numbers"  autofocus="autofocus" />
</td>
</tr>

<tr>

<td width="230px" class="firstColumnStyling">
Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" class="person_name" placeholder="Only Letters" onblur="checkForDuplicateCustomerName(this.value);" autofocus />
</td>
</tr>

<tr>
<td>
Address<span class="requiredField">* </span> : 
</td>

<td>
<textarea name="customer_address" id="customer_address" class="address" cols="5" rows="6"></textarea>
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
                             
                             <option value="<?php echo $super['city_id'] ?>"><?php echo $super['city_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Area<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="customer_area" class="city_area" id="city_area1" placeholder="Only Letters" />
                            </td>
</tr>

<tr>
<td>Pincode : </td>
<td><input type="text" name="customer_pincode" id="customer_pincode" class="pincode" placeholder="6 Digits!"/></td>
</tr>



<tr id="addcontactTrCustomer">
                <td>
                Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="customerContact[]" placeholder="more than 6 Digits!" onblur="checkForDuplicateContactNo(this.value);" /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
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
               
       
       
<!-- end for regenreation purpose -->

<tr>

<td width="230px" class="firstColumnStyling">
Email : 
</td>

<td>
<input type="text" name="email" id="txtEmail" class="email" placeholder="Only Valid Email Address"  />
</td>
</tr>

<tr>
<td> Opening Balance  : </td>
<td> <input type="text" name="opening_balance" /> <select name="opening_balance_cd" class="credit_debit"><option value="0"  selected="selected" >Debit</option><option value="1"  selected="selected" >Credit</option> </select> </tr>
</tr>


<tr>
<td>PAN No : </td>
<td><input type="text" name="pan_no" id="pan_no" placeholder="PAN Number!"/></td>
</tr>


<tr>
<td>TIN No : </td>
<td><input type="text" name="tin_no" id="tin_no"  placeholder="TIN Number!"/></td>
</tr>

<tr>
<td>CST No : </td>
<td><input type="text" name="cst_no" id="cst_no" placeholder="CST Number!"/></td>
</tr>


<tr>
<td>Service Tax No : </td>
<td><input type="text" name="service_tax_no" id="service_tax_no"  placeholder="Service Tax Number!"/></td>
</tr>

<tr>
<td>Notes : </td>
<td><textarea  name="notes" id="notes"  placeholder="Any Remarks!"></textarea></td>
</tr>

<tr>
<td> Customer Group : </td>
				<td>
					<select id="bs3Select" name="customer_group_id[]" data-live-search="true" class="city_area selectpicker" multiple="multiple" >
                       
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


<tr>
<td> <?php echo BROKER_NAME; ?> Group : </td>
				<td>
					<select id="bs3Select" name="broker_group_id[]" data-live-search="true" class="city_area selectpicker" multiple="multiple" >
                       
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
<input type="file" name="" class="customerFile"  /><input type="button" value="+" class="btn btn-primary addscanbtnCustomer"/>
</td>
</tr> 

<!-- end of used for regeneration -->
</table>

<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="260px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Proof" id="addCustomerProofBtn"/></td>
</tr>     
</table>
<table>
<tr>
<td width="260px"></td>
<td>
<input type="submit" value="Add Customer" id="disableSubmit" class="btn btn-warning">
</td>
</tr>
</table>
</form>

<hr class="firstTableFinishing" />

<!-- <h4 class="headingAlignment">List of Parties</h4>
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Address</th>
            <th class="heading">Contact No</th>
            <th class="heading">Opening Balance</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$parties=listCustomer();
		$no=0;
		if($parties!=false)
		{ 
		foreach($parties as $agencyDetails)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php  echo $agencyDetails['customer_name']; ?>
            </td>
            <td><?php echo $agencyDetails['customer_address'] ?>
            </td> 
             <td><?php $contact_nos=$agencyDetails['contact_no']; foreach($contact_nos as $contact_no) echo $contact_no[0]."<br>"; ?>
            </td>
            <td><?php echo $agencyDetails['opening_balance']; if($agencyDetails['opening_cd']==0) echo " Dr"; else echo " Cr"; ?>
            </td> 
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$agencyDetails['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
             </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&id='.$agencyDetails['customer_id']; ?>"><button title="Edit this entry" class="btn editBtn splEditBtn "><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteCustomer&lid='.$agencyDetails['customer_id']; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table>  -->

</div>
<div class="clearfix"></div>
<script>
 $( "#city_area1" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/city_area.php',
                { term: request.term, city_id:$('#customer_city_id').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#city_area1" ).val(ui.item.label);
			return false;
		}
    });
</script>	