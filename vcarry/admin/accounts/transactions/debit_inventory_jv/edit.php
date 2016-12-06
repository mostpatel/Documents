<?php if(isset($_GET['id']))
{
$debit_items = getDebitInventoryItemForJvId($sales_id);	// tax details inside the array
$credit_items = getCreditInventoryItemForJvId($sales_id);
$godowns = listGodowns();
}
else
exit;
?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Edit Inwards Inventory JV</h4>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="id" value="<?php echo $sales_id; ?>" />
<table id="insertTableStyling" class="insertTableStyling no_print">

<tr>
<td width="220px">Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime($sale['trans_date'])); ?>" autofocus /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td>Ledger / Customer <span class="requiredField">* </span> : </td>
				<td>
					<input type="text" id="to_ledger" name="to_ledger_id" placeholder="Start Typing For Suggestions" value="<?php  if(is_numeric($sale['ledger_id'])) echo getCustomerLedgerNameFromLedgerNameLedgerId('L'.$sale['ledger_id']); else if(is_numeric($sale['customer_id'])) echo getCustomerLedgerNameFromLedgerNameLedgerId('C'.$sale['customer_id']); ?>" /> 
                   
                            </td>
</tr>

<tr>
<td>Type <span class="requiredField">* </span> :</td>
<td>
	<select name="jv_type" id="jv_type">
    	<?php $jv_types=listInventoryJVTypes();
		foreach($jv_types as $jv_type)
		{ ?>
    	<option value="<?php echo $jv_type['jv_type_id']; ?>" <?php if($sale['jv_type_id']==$jv_type['jv_type_id']) { ?> selected="selected" <?php  } ?>><?php echo $jv_type['jv_type']; ?></option>
        <?php } ?>
    </select>
</td>
</tr>

</table>
<h4 class="headingAlignment">Debit Spare parts (Produced)</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Godown</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th></th>
            </tr>
            <tbody style="display:none" id="p0">
            	<tr>
                    <td><input type="text" name="ns_item_id[]" class="inventory_item_autocomplete1" /></td>
                     <td><select  name="ns_godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                        
                      <?php 
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>   
                     <td><input type="text" name="ns_quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span><select style="width:50px;"  class="item_unit"  name="ns_unit_id[]">
                    	
                        	<option value="-1" >-- Unit --</option>
                        
                    </select></td>
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     
                    
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
             <?php if(count($debit_items)>0) { for($i=1;$i<=count($debit_items);$i++) { 
			$sales_item=$debit_items[$i-1];
				$trans_item_unit_details = getTransItemUnitByInventoryJvItemId($sales_item['inventory_item_jv_id']);
			?>
            <tbody id="ns<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="ns_item_id[]" class="inventory_item_autocomplete" value="<?php echo getFullItemNameFromItemId($sales_item['item_id'],0,$sales_item['barcode_transaction_id']); ?>" <?php $in_use=0; if(is_numeric($sales_item['barcode_transaction_id'])) { if(!CheckIfLatestTransactionForBarcode($sales_item['barcode'],$sales_id,5)) { $in_use=1; ?> readonly title="Delete Further Transaction to Edit!" <?php  }  } ?> /></td>
                    
                      <td><select id="godown" name="ns_godown_id[]" style="width:150px;">
                        
                      <?php 
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>" <?php if($model['godown_id']==$sales_item['godown_id']){ ?> selected="selected" <?php } ?>><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>   
                    <td><input type="text" name="ns_quantity[]" class="item_quantity" style="width:35px;"  onchange="onchangeQuantity(this);" value="<?php if(!is_numeric($trans_item_unit_details['quantity'])) echo $sales_item['quantity']; else echo $trans_item_unit_details['quantity']; ?>" <?php if($in_use==1) { ?> readonly title="Delete Further Transaction to Edit!" <?php } ?> /><span style="color:#f00;font-size:12px;"><?php echo getRemainingQuanityForItemForDate($sales_item['item_id'],$sales_item['godown_id']); ?></span><select style="width:50px;"  class="item_unit"  name="ns_unit_id[]">
                    	<?php $units = getUnitsForItemId($sales_item['item_id']); foreach($units as $unit) { ?>
                        	<option value="<?php echo $unit['item_unit_id']; ?>" <?php if($unit['item_unit_id']==$trans_item_unit_details['item_unit_id']) {  ?> selected="selected" <?php } ?>><?php echo $unit['unit_name']; ?></option>
                        <?php } ?>
                    </select></td>
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:55px;" value="<?php if(!is_numeric($trans_item_unit_details['rate'])) echo $sales_item['rate']; else echo $trans_item_unit_details['rate']; ?>" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="<?php echo $sales_item['amount']; ?>"  /></td>
                    
                            <td><input  <?php if($i<count($debit_items)) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php if($i>=count($debit_items)) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } } else { ?>
              <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="p<?php echo $i; ?>">
            	 <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="ns_item_id[]" class="inventory_item_autocomplete" /></td>
                    <td><select  name="ns_godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                        
                      <?php  $godowns = listGodowns();
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td> 
                           <td><input type="text" name="ns_quantity[]" class="item_quantity" style="width:25px;" value="1" onchange="onchangeQuantity(this);" /> <span style="color:#f00;font-size:12px;"></span></td>
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            </tbody>
            <?php } ?>
            <?php } ?>
    	</table>
    </td>

</tr>

</table>
<h4 class="headingAlignment">Debit Total : <span id="ns_total"></span></h4>

<table>

<tr>

<td class="firstColumnStyling">
Remarks  : 
</td>

<td>
<textarea name="remarks" id="" placeholder="Your Remarks. Go Here!"><?php echo $sale['remarks']; ?></textarea>
</td>
</tr>
 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit"  class="btn btn-warning">
<a href="<?php echo  WEB_ROOT."admin/accounts/transactions/inventory_jv"; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
document.product_count=6;
 document.barcode_type=1;
 document.disablePeriodModal = 1;
 $( "#to_ledger" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/CustomersAndLedgersWithoutPurchaseAndSales.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#to_ledger" ).val(ui.item.label);
			return false;
		}
    });
 $( ".inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
		var trans_date = request.term + " | "+ $('#payment_date').val()+" | "+ document.barcode_type;
                $.getJSON ('<?php echo WEB_ROOT; ?>json/inventory_item.php',
                { term: trans_date }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForSalesFromItemId(ui.item.id,target_el);  
			 }
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
 
  $( ".inventory_ns_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/inventory_item.php',
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForSalesFromItemId(ui.item.id,target_el);  
			 }
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });	

function onchangeQuantity(quantity_el) {
    
	var quantity = $(quantity_el).val();
	var rate = $(quantity_el).parent().next().children('input').val();

	var amount_el = $(quantity_el).parent().next().next().children('input');
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	amount_el.val(amount);
	
		
	}
	
}

function onchangeRate(rate_el) {
    
	var rate = $(rate_el).val();
	var quantity = $(rate_el).parent().prev().children('input').val();
	
	
	
	var amount_el = $(rate_el).parent().next().children('input');
	
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	
	amount_el.val(amount);
	
	
	}
}

</script>