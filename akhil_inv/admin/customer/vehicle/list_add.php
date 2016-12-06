<?php if(!isset($_GET['id']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}
$customer_id=$_GET['id'];
$customer = getCustomerDetailsByCustomerId($customer_id);
if($customer=="error")
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Add Vehicle Details</h4>
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
<form id="addLocForm" onsubmit="return submitOurVehicle();" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurVehicle()">

<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<table id="insertVehicleTable" class="insertTableStyling no_print">



<tr>
<td>Vehicle Model<span class="requiredField">* </span> : </td>
				<td>
					<select id="vehicle_model" name="model_id"  class="selectpicker show-tick" data-live-search="true" >
                        <option value="-1" >--Please Select Model--</option>
                        <?php
                            $companies = listVehicleCompanies();
                            foreach($companies as $super)
                              {
                             ?>
                             <optgroup label="<?php echo $super['company_name'] ?>">
                             <?php $models = getModelsFromCompanyID($super['vehicle_company_id']);
							 foreach($models as $model)
							 {
							  ?>
                             <option value="<?php echo $model['model_id'] ?>"><?php echo $model['model_name'] ?></option					>
                             <?php } ?>
                             							</optgroup>	
                             <?php } ?>
                              
                     
                            </select> 
                            </td>
</tr>


 
<tr>
<td class="firstColumnStyling">
Registration Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="vehicle_reg_no" id="vehicle_reg_no" placeholder="Only Letters and Digits!" onblur="checkAvailibilty(this,'agerror','ajax/regNo.php','')"/><span id="agerror" class="availError">Registration Number already taken!</span>	

</td>
</tr>



<tr>
       <td>Vehicle Condition<span class="requiredField">* </span> :</td>
           
           
        <td>
              <table>
               <tr><td><input type="radio"   name="condition"  value="1" checked="checked"></td><td>New</td></tr>
            <tr><td><input type="radio"  name="condition"  value="0" ></td><td>Used</td>
               </tr> 
            </table>
        </td>
 </tr>
 
  <tr>
       <td>Vehicle Sold<span class="requiredField">* </span> :</td>
           
           
        <td>
              <table>
               <tr><td><input type="radio"   name="is_sold_customer"  value="0" <?php if($vehicle['is_sold_customer']==0) { ?> checked="checked" <?php } ?>></td><td>No</td></tr>
            <tr><td><input type="radio"  name="is_sold_customer"  value="1" <?php if($vehicle['is_sold_customer']==1) { ?> checked="checked" <?php } ?> ></td><td>Yes</td>
               </tr> 
            </table>
        </td>
 </tr>
 
 <tr>
 
 <tr>
<td>Financer / Dealer / Broker : </td>
				<td>
					<select id="dealer" name="ledger_id"  class="selectpicker show-tick" data-live-search="true" >
                        <option value="-1" >--Please Select Model--</option>
                        <?php
                            $companies = listFinancersDealersBrokers();
                            foreach($companies as $super)
                              {
                             ?>
                             <option value="<?php echo $super['ledger_id'] ?>"><?php echo $super['ledger_name'] ?></option					>
                             <?php } ?>
                              
                     
                            </select> 
                            </td>
</tr>

<tr>
<td> Opening Balance For Vehicle : </td>
<td> <input type="text" name="opening_balance" /> <select name="opening_balance_cd" class="credit_debit"><option value="0"  selected="selected" >Debit</option><option value="1"  >Credit</option> </select> </tr>
</tr>

<tr>
<td> Vehicle Purchase Ledger : </td>
<td> <?php $ledgers = listNonAccountingLedgers(); ?> <select id="purchase_ledger" name="extra_ledger_id"  class="selectpicker show-tick" data-live-search="true" >
                        <option value="-1" >--Please Select Purchase Ledger--</option>
                        <?php
                           
                            foreach($ledgers as $super)
                              {
                             ?>
                             <option value="<?php echo $super['ledger_id'] ?>"><?php echo $super['ledger_name'] ?></option					>
                             <?php } ?>
                              
                     
                            </select>  </td>
</tr>

<tr>
<td> Opening Balance For Purchase Ledger : </td>
<td> <input type="text" name="opening_balance_extra" /> <select name="opening_balance_cd_extra" class="credit_debit"><option value="0"  selected="selected" >Debit</option><option value="1" >Credit</option> </select> </tr>
</tr>

<td>Vehicle Model Year<span class="requiredField">* </span> : </td>
				<td>
					<select id="model" name="model_year">
                       
                       <?php
					   for($i=date('Y');$i>=1990;$i--)
					   {
						 ?>
                          <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                         <?php  
						   }
					    ?>
                     </select> 
                            </td>
</tr>


<tr>

<td class="firstColumnStyling">
Registration Date : 
</td>

<td>
 <input type="text" size="12"  placeholder="Click to select Date!"  name="vehicle_reg_date" class="datepicker1 date" /><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Engine Number : 
</td>

<td>
<input type="text" id="vehicle_engine_no" name="vehicle_engine_no"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"/><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number : 
</td>

<td>
<input type="text" id="vehicle_chasis_no" name="vehicle_chasis_no" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')"/><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Fitness Exp Date : 
</td>

<td>
<input type="text" placeholder="Click to select Date!" name="fitness_exp_date" class="datepicker2 date" /><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Permit Exp Date : 
</td>

<td>
<input type="text"  placeholder="Click to select Date!" name="permit_exp_date" class="datepicker3 date" /><span class="ValidationErrors contactNoError">Please select a date!</span>
</td>
</tr>


<!-- for regenreation purpose Please donot delete -->

<tr id="vehicleProofTypeTr">
<td>Proof type : </td>
				<td id="vehicleProofTypeTd">
					<select id="proof" name="vehicleProofId[]" class="vehicleProofId" onblur="checkProofId(this.value,this)">
                        <option value="-1" >--Please Select Proof Type--</option>
                        <?php
                            $types = listVehicleProofTypes();
                            foreach($types as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['vehicle_document_type_id'] ?>"><?php echo $super['vehicle_document_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select> <span class="ValidationErrors contactNoError">Please select a Proof Type!</span> 
                            </td>
</tr>




<tr id="vehicleProofNoTr">
<td> Proof Number : </td>
<td id="vehicleProofNoTd"><input type="text" class="vehicleProofNo" name="vehicleProofNo[]" placeholder="Only Letters and Digits!" onblur="checkProofNo(this.value,this)" /><span class="ValidationErrors contactNoError">Please enter Proof No (only numbers and letters)! OR Choose Proof Image!</span></td>
</tr>


<tr id="vehicleProofImgTr">
<td>
Proof Image : 
</td>
<td>
<input type="file" name="" class="customerFile"  /><br /> - OR - <br /><input type="button" name="scanProof" class="btn scanBtn" value="scan" /><input type="button" value="+" class="btn btn-primary addscanbtnGuarantor"/>
</td>
</tr> 

<!-- end of used for regeneration -->
</table>

<table style="margin-top:0px;margin-bottom:10px;">
<tr>
<td width="250px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Proof" id="addVehicleProofBtn"/></td>
</tr>     
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Vehicle Details"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $customer_id; ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>