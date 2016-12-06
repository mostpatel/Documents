<?php   $sales_jv=listSalesJvs(); ?>
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

$delivery_challan_id=$_GET['id'];
$delivery_challan = getDeliveryChallanById($delivery_challan_id);

if(is_array($delivery_challan) && $delivery_challan!="error")
{
	$customer=getCustomerDetailsByCustomerId($delivery_challan['customer_id']);
	$vehicle=getVehicleById($delivery_challan['vehicle_id']);
	$vehicle_model = getVehicleModelById($vehicle['model_id']);
	$invoice = getVehicleInvoiceByVehicleId($vehicle['vehicle_id']);
	
	$loan_jv = getLoanJVForVehicleId($vehicle['vehicle_id']);
	
	if(!$invoice)
	{
	$_SESSION['ack']['msg']="Invalid Invoice!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	exit;
	}
	
	$sales = getSaleById($invoice['sales_id']);
	$tax_group = getTaxForVehicleSaleId($invoice['sales_id']);
	$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);
	$exchange_vehicle_id = getExchangeVehicleIdForVehicleInvoiceId($invoice['vehicle_invoice_id']);
	if(is_numeric($exchange_vehicle_id))
	{
	$exchange_vehicle = getVehicleById($exchange_vehicle_id);
	$exchange_vehicle_model = getVehicleModelById($exchange_vehicle['model_id']);
	$exchange_purchase_id = getPurchaseIdFromVehicleId($exchange_vehicle_id);
	$purchase_vehicle=getVehiclePurchaseById($exchange_vehicle_id);
$exchange_purchase = $purchase_vehicle[0];

	$by_account_id=$exchange_purchase['to_ledger_id'];
	$tax_group = getTaxGroupForVehicleId($exchange_vehicle['vehicle_id']);
	$tax_group_id = $tax_group['tax_group_id'];
	$tax_grp_in_out= $tax_group['in_out'];
	$tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
	$tax_percent = $tax_percent /100;
	
	if($tax_grp_in_out==2)
	$basic_price = $exchange_vehicle['basic_price'] / (1+$tax_percent);
	else
	$basic_price  = $exchange_vehicle['basic_price'];
	}
}
else
{
	$_SESSION['ack']['msg']="Invalid Invoice!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	exit;
}

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Make Invoice For Vehicle</h4>
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
<form id="addLocForm"  action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" >

<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<input name="delivery_challan_id" value="<?php echo $delivery_challan_id; ?>" type="hidden" />
<input name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>" type="hidden" />
<input name="to_ledger" value="<?php echo 'C'.$customer['customer_id']; ?>" type="hidden" />
<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td width="250px">Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="sale_date" class="datepicker1" name="sale_date" value="<?php echo date('d/m/Y',strtotime($invoice['invoice_date'])); ?>" />
                            </td>
</tr>

<tr>
<td>Invoice No<span class="requiredField">* </span> : </td>
				<td>
					<span><?php $prefix=getPrefixFromOCId($_SESSION['edmsAdminSession']['oc_id']); echo $prefix; ?></span>
                  <input type="text" id="invoice_no"  name="invoice_no" value="<?php echo str_replace($prefix,'',$invoice['invoice_no']);?>" />
                            </td>
</tr>

<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
					<select  id="by_ledger" name="from_ledger">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listSalesLedgers();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>" <?php if($bank_cash_ledger['ledger_id']==$sales['from_ledger_id']) { ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<tr>
<td>Invoice Type<span class="requiredField">* </span> : </td>
				<td>
					<select id="retail_tax" name="retail_tax" onchange="changeInvoiceNo();">
                    	<option value="-1">-- Click To Select --</option>
                    <?php
					$bank_cash_ledgers=listAllInvoiceTypes($oc_id);
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['invoice_type_id']; ?>"  <?php if($bank_cash_ledger['invoice_type_id']==$invoice['retail_tax']) { ?> selected="selected" <?php } ?> ><?php echo $bank_cash_ledger['invoice_type']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>
</tr>


<tr>
<td width="220px">Basic Price<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="basic_price" placeholder="Only Digits!" value="<?php echo $sales['amount']; ?>" />
                            </td>
</tr>

<tr>
<td>Tax Group<span class="requiredField">* </span> : </td>
				<td>
					<select id="color" name="tax_group_id">
                        <option value="-1" >--Please Select Tax Group--</option>
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>"  <?php if($model['tax_group_id']==$tax_group[0]['tax_group_id']) { ?> selected="selected" <?php } ?>><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<?php if(is_array($sales_jv) && count($sales_jv)>0) { 
foreach($sales_jv as $purchase_jv)
{
$sales_inserted_jv=getSalesJvForVehicleIdAndLedgerId($vehicle['vehicle_id'],$purchase_jv['ledger_id']);	
?>	
<tr>
	<td><?php echo getLedgerNameFromLedgerId($purchase_jv['ledger_id']); ?> :</td>
    <td><input type="text" name="sales_jvs[<?php echo $purchase_jv['purchase_sales_jv_id']; ?>]" placeholder="Only Digits!" value="<?php if($sales_jv) echo $sales_inserted_jv[0]['amount']; ?>" /></td>
</tr>
<?php	
}
?>

<?php } ?>
<tr>
<td>Loan Jv Amount :</td>
<td><input type="text" name="loan_amount" placeholder="Only Digits!" value="<?php if($loan_jv) echo $loan_jv['amount']; ?>" /></td>
</tr>

<tr>
<td class="firstColumnStyling">
Loan Jv (Debit)<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="ledger_id" class="ledger_id" id="ledger_id" value="<?php if($loan_jv) echo $loan_jv['to_ledger_id']; ?>" />
<input type="text" name="ledger_name" id="txtName" value="<?php if($loan_jv)  echo getLedgerNameFromLedgerId($loan_jv['to_ledger_id'])." | [".$loan_jv['to_ledger_id']."]"; ?>"/>
</td>
</tr>

<tr>
       <td>Under Exchange<span class="requiredField">* </span> :</td>
           
           
        <td>
              <table>
               <tr><td><input type="radio"   name="exchange" id="no"  value="0" <?php if($invoice['under_exchange']==0) { ?> checked="checked" <?php } ?> onchange="toggleExchangeTable(this.value);"></td><td><label for="no">No</label></td></tr>
            <tr><td><input type="radio"  name="exchange"  value="1" id="yes" <?php if($invoice['under_exchange']==1) { ?> checked="checked" <?php } ?> onchange="toggleExchangeTable(this.value);" ></td><td><label for="yes">Yes</label></td>
               </tr> 
            </table>
        </td>
 </tr>
</table>

<table class="insertTableStyling no_print" id="exchange_table" <?php if(!is_numeric($exchange_vehicle_id)) { ?> style="display:none;"<?php } ?>>
<tr>
<td width="250px">By (Debit)<span class="requiredField">* </span> : </td>
				<td>
                
					<select  id="by_ledger" name="to_ledger_id">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listPurchaseLedgers();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>" <?php if($bank_cash_ledger['ledger_id']==$by_account_id){ ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                    <input type="hidden" id="to_ledger" name="from_ledger_id" value="<?php echo "C".$customer['customer_id']; ?>" />
                            </td>
</tr>

<tr>
<td>Vehicle Model<span class="requiredField">* </span> : </td>
				<td>
                <input type="hidden" name="vehicle_id[]" value="<?php echo $exchange_vehicle_id; ?>" /> 
					<select id="vehicle_model" name="model_id[]">
                        <option value="-1" >--Please Select Model--</option>
                     			<?php $models = listVehicleModels();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['model_id'] ?>" <?php if($model['model_id']==$exchange_vehicle['model_id']){ ?> selected="selected" <?php } ?>><?php echo $model['model_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>


 <tr>
<td>Vehicle Model Year<span class="requiredField">* </span> : </td>
				<td>
					<select id="model" name="model_year[]">
                        <option value="-1" >--Please Select Model Year--</option>
                       <?php
					   for($i=date('Y');$i>=1990;$i--)
					   {
						 ?>
                          <option value="<?php echo $i; ?>" <?php if($i==$exchange_vehicle['vehicle_model']){ ?> selected="selected" <?php } ?> ><?php echo $i; ?></option>
                         <?php  
						   }
					    ?>
                     </select> 
                            </td>
</tr>

<tr>
<td>Vehicle Color<span class="requiredField">* </span> : </td>
				<td>
					<select id="color" name="vehicle_color_id[]">
                        <option value="-1" >--Please Select Vehicle Color--</option>
                      <?php $models = listVehicleColors();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['vehicle_color_id'] ?>" <?php if($model['vehicle_color_id']==$exchange_vehicle['vehicle_color_id']){ ?> selected="selected" <?php } ?>><?php echo $model['vehicle_color']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<tr>
<td>Godown<span class="requiredField">* </span> : </td>
				<td>
					<select id="godown" name="godown_id[]">
                        <option value="-1" >--Please Select Godown--</option>
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>" <?php if($model['godown_id']==$exchange_vehicle['godown_id']){ ?> selected="selected" <?php } ?>><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<tr>
       <td>Vehicle Condition<span class="requiredField">* </span> :</td>
           
           
       
        <td>
					<select id="condition" name="condition[]"  onchange="toggleRegNumber(this,this.value)">
                        <option value="0" >OLD</option>
                            </select> 
                            </td>
 </tr>
 
<tr>
<td class="firstColumnStyling">
Reg Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_reg_no" name="vehicle_reg_no[]"  placeholder="Reg No Eg: GJ1AB1234 !" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"  value="<?php echo $exchange_vehicle['vehicle_reg_no']; ?>"/><span id="agerror" class="availError">Reg Number already taken!</span>	
</td>
</tr>
 
 
<tr>
<td class="firstColumnStyling">
Engine Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_engine_no" name="vehicle_engine_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')" value="<?php echo $exchange_vehicle['vehicle_engine_no']; ?>"/><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_chasis_no" name="vehicle_chasis_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')" value="<?php echo $exchange_vehicle['vehicle_chasis_no']; ?>"/>
</td>
</tr>

<tr>
<td>Service Book Number<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="service_book"  name="service_book_no[]" placeholder="Only Digits!" value="<?php echo $exchange_vehicle['service_book']; ?>" />
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Cylinder Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_cylinder_no" name="cng_cylinder_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')" value="<?php echo $exchange_vehicle['cng_cylinder_no']; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_kit_no" name="cng_kit_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')" value="<?php echo $exchange_vehicle['cng_kit_no']; ?>" /><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td width="220px">Basic Price<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="basic_price[]" id="basic_price" value="<?php echo $basic_price; ?>" placeholder="Only Digits!" />
                            </td>
</tr>

<tr>
<td>Tax Group<span class="requiredField">* </span> : </td>
				<td>
					<select id="tax_group" name="tax_group_id[]">
                        <option value="-1" >--Please Select Tax--</option>
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" <?php if($model['tax_group_id']==$tax_group_id){ ?> selected="selected" <?php } ?> ><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

</table>
<table class="insertTableStyling no_print" >

<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit Vehicle Invoice"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $customer['customer_id']; ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>
<script>
function toggleExchangeTable(exchange_val)
{
	if(exchange_val==0)
	$('#exchange_table').hide();
	else if(exchange_val==1)
	$('#exchange_table').show();
}
$( "#txtName" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/ledgersOnly.php',
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
	response: function(e, ui) {
		
		
    if (ui.content.length == 0) {
        $(this).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
},change: function(e, ui) {
    if (!ui.item) {
        $(this).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
},
    open: function(event, ui) {  select_var=false; target_el=event.target },
    select: function(event, ui) { select_var=true; $(event.target).val(ui.item.label);
	
	$(this).prevAll(".ledger_id").val(ui.item.id);  
	if (!ui.item) {
        $(this).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
			 }
}).blur(function(){
    if(!select_var)
    {
		$(target_el).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
 });
</script>