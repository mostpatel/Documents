<?php if(isset($_GET['id']))
{

$purchase_items = getInventoryItemForPurchaseOrderId($purchase_id);	// tax details inside the array
$ns_items = getNonStockItemForPurchaseOrderId($purchase_id);
}
else
exit; ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Edit Purchase Order </h4>
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
<input type="hidden" name="id" value="<?php echo $purchase_id; ?>" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Purchase Order Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime($purchase['trans_date'])); ?>" autofocus="autofocus" /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<!-- <tr>
<td width="220px">Delivery Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="delivery_date" id="delivery_date" class="datepicker2" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr> -->

<!--<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" />
                            </td>
</tr> -->

<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<select  id="by_ledger" name="to_ledger_id">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listPurchaseLedgers($purchase['oc_id']);
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id'];  ?>" <?php if($bank_cash_ledger['ledger_id']==$purchase['to_ledger_id']) { ?> selected="selected" <?php } ?> ><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" id="from_ledger" name="from_ledger_id" value="<?php  if(is_numeric($purchase['from_ledger_id'])) echo getCustomerLedgerNameFromLedgerNameLedgerId('L'.$purchase['from_ledger_id']); else if(is_numeric($purchase['from_customer_id'])) echo getCustomerLedgerNameFromLedgerNameLedgerId('C'.$purchase['from_customer_id']); ?>"/> 
                 <!--  <select id="to_ledger" name="from_ledger_id" >
                    	<option value="" ></option>
                    <?php
					$ledgers=listCustomerAndLedgersWithoutPurchaseAndSales();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="<?php echo $ledger['id']; ?>" <?php if((is_numeric($purchase['from_ledger_id']) && $ledger['id']=='L'.$purchase['from_ledger_id']) || (is_numeric($purchase['from_customer_id']) && $ledger['id']=='C'.$purchase['from_customer_id'])) { ?> selected="selected" <?php } ?>><?php echo $ledger['name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span> -->
                            </td>
</tr>
</table>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th>Godown</th>
                 <th></th>
            </tr>
            <tbody style="display:none" id="p0">
            	<tr>
                    <td><input name="item_id[]" type="text" class="inventory_item_autocomplete1" /></td>
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDisc(this);" /> %</td>
                     <td><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                     
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     <td><select id="godown" name="godown_id[]" style="width:150px;">
                       
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',1)"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php if(count($purchase_items)>0) {
				 for($i=1;$i<=count($purchase_items);$i++) { 
			$purchase_item=$purchase_items[$i-1]['purchase_item_details'];
			$item_tax_details = $purchase_items[$i-1]['tax_details'];
			 
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><input name="item_id[]" type="text" class="inventory_item_autocomplete" value="<?php echo getFullItemNameFromItemId($purchase_item['item_id'],1); ?>" /></td>
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;"  onchange="onchangeQuantity(this);" value="<?php echo $purchase_item['quantity']; ?>" /><span style="color:#f00;font-size:12px;"><?php echo getRemainingQuanityForItemForDate($purchase_item['item_id'],$purchase_item['godown_id']); ?></span></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="<?php echo $purchase_item['rate']; ?>" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="<?php echo $purchase_item['amount']; ?>"  /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="<?php echo $purchase_item['discount']; ?>" onchange="onchangeDisc(this);" /> %</td>
                     <td><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"  <?php if($model['tax_group_id']==$purchase_item['tax_group_id']){ ?> selected="selected" <?php } ?>><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="<?php echo round($purchase_item['net_amount']+$purchase_item['tax_amount'],2); ?>" /></td>
                     <td><select id="godown" name="godown_id[]" style="width:150px;">
                        
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>" <?php if($model['godown_id']==$purchase_item['godown_id']){ ?> selected="selected" <?php } ?>><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                            <td><input  <?php  if($i<count($purchase_items)) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',1)"/><input type="button" <?php  if($i>=count($purchase_items)) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } }else { ?>
             <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><input name="item_id[]" type="text" class="inventory_item_autocomplete" /></td>
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0"  /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDisc(this);" /> %</td>
                     <td><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                       
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     <td><select id="godown" name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForPurchaseFromGodwonId(this.value,this);">
                        
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',1)"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
            <?php } ?>
    	</table>
    </td>

</tr>
</table>
<h4 class="headingAlignment">Labour / Service</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="nonStockSaleTable">
    		<tr>
            	<th>Item Name / Code</th>
                 
               
                 <th>Rate</th>
                 
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
            <tbody style="display:none" id="ns0">
            	<tr>
                    <td><input type="text" name="ns_item_id[]" class="inventory_ns_item_autocomplete1" /></td>
                     
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" value="0" /></td>
                     
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" value="0"  /> %</td>
                     <td><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"  ><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php',1)"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
             <?php if(count($ns_items)>0) { for($i=1;$i<=count($ns_items);$i++) { 
			$sales_item=$ns_items[$i-1]['purchase_item_details'];
			$item_tax_details = $ns_items[$i-1]['tax_details'];
			
			?>
            <tbody id="ns<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="ns_item_id[]" class="inventory_ns_item_autocomplete" value="<?php echo getFullItemNameFromItemId($sales_item['item_id']); ?>" /></td>
                    
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="<?php echo $sales_item['amount']; ?>" onchange="onchangeRateNS(this);" /></td>
                    
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="<?php echo $sales_item['discount']; ?>" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                     
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>" <?php if($model['tax_group_id']==$sales_item['tax_group_id']){ ?> selected="selected" <?php } ?>><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="<?php if(is_numeric($sales_item['tax_amount'])) echo round($sales_item['net_amount']+$sales_item['tax_amount'],2); else echo round($sales_item['net_amount'],2);  ?>" /></td>
                    
                            <td><input  <?php if($i<count($ns_items)) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php',1)"/><input type="button" <?php if($i>=count($ns_items)) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } }else { ?>
            <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="ns<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="ns_item_id[]" class="inventory_ns_item_autocomplete"  /></td>
                    
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" /></td>
                    
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                      
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php',1)"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
            <?php } ?>
    	</table>
    </td>

</tr>

</table>
<table>
<tr>
<td width="250px;">Purchase Order Type<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="ref_type" name="ref_type" onchange="changeRefFeild(this.value)" >
                    	<option value="0" selected="selected">NEW</option>
                        <option value="2" >Against Advance Payment</option>
                    </select>
                            </td>
</tr>

<tr  id="pay_ref_new">
<td>Purchase Order Ref<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" id="pay_ref_new" name="ref" /> 
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_against">
<td>Payment Ref<span class="requiredField">* </span> : </td>
				<td>
					<select type="text" id="pay_ref_aganist" name="ref" >
                    </select> 
                </td>
</tr>



<tr>

<td class="firstColumnStyling">
Remarks (ctrl + g to change english/gujarati) : 
</td>

<td>
<textarea name="remarks" id="transliterateTextarea"><?php echo $purchase['remarks']; ?></textarea>
</td>
</tr>

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit Purchase Order"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/accounts/" ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
document.product_count=6;
document.barcode_type=2;
 $( "#from_ledger" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/CustomersAndLedgersWithoutPurchaseAndSales.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#from_ledger" ).val(ui.item.label);
			return false;
		}
    });	 
$( ".inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
		var trans_date = request.term + " | "+ $('#payment_date').val()+" | "+document.barcode_type;
                $.getJSON ('<?php echo WEB_ROOT; ?>json/inventory_item.php',
                { term: trans_date }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForPurchaseFromItemId(ui.item.id,target_el);  
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
                $.getJSON ('<?php echo WEB_ROOT; ?>json/inventory_ns_item.php',
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
	getRateQuantityAndTaxForPurchaseFromItemId(ui.item.id,target_el);  
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
	var disc = $(quantity_el).parent().next().next().next().children('input').val();
	var tax_select =  $(quantity_el).parent().next().next().next().next().children('select')[0];
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	var amount_el = $(quantity_el).parent().next().next().children('input');
	var net_amount_el = $(quantity_el).parent().next().next().next().next().next().children('input');
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
			
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
	
}

function onchangeRate(rate_el) {
    
	var rate = $(rate_el).val();
	var quantity = $(rate_el).parent().prev().children('input').val();
	var disc = $(rate_el).parent().next().next().children('input').val();
	var tax_select =  $(rate_el).parent().next().next().next().children('select')[0];
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	
	var amount_el = $(rate_el).parent().next().children('input');
	var net_amount_el = $(rate_el).parent().next().next().next().next().children('input');
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}
function onchangeDisc(disc_el) {
    
	var disc = $(disc_el).val();
	var quantity = $(disc_el).parent().prev().prev().prev().children('input').val();
	var rate = $(disc_el).parent().prev().prev().children('input').val();
	var tax_select =  $(disc_el).parent().next().children('select')[0];
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	var amount_el = $(disc_el).parent().prev().children('input');
	var net_amount_el = $(disc_el).parent().next().next().children('input');
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}

function onchangeTaxGroup(tax_el) {
    
	var tax_select = tax_el;
	
	
	var quantity = $(tax_el).parent().prev().prev().prev().prev().children('input').val();
	var rate = $(tax_el).parent().prev().prev().prev().children('input').val();
	var disc = $(tax_el).parent().prev().children('input').val();
	
	
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	
	
	var amount_el = $(tax_el).parent().prev().prev().children('input');
	var net_amount_el = $(tax_el).parent().next().children('input');
	
	
	
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}


function onchangeRateNS(rate_el) {
    
	var rate = $(rate_el).val();

	var disc = $(rate_el).parent().next().children('input').val();
	
	var tax_select =  $(rate_el).parent().next().next().children('select')[0];
	
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	
	var net_amount_el = $(rate_el).parent().next().next().next().children('input');
	if(!isNaN(rate))
	{
	
	var amount = parseFloat(rate);
	
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		
		var net_amount = amount + parseFloat((amount*(disc_percent/100)));
		
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}
function onchangeDiscNS(disc_el) {
    
	var disc = $(disc_el).val();
	var rate = $(disc_el).parent().prev().children('input').val();
	
	var tax_select =  $(disc_el).parent().next().children('select')[0];
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	
	var net_amount_el = $(disc_el).parent().next().next().children('input');
	rate = parseFloat(rate);
	if( !isNaN(rate))
	{
	
	var amount = parseFloat(rate);
	
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}

function onchangeTaxGroupNS(tax_el) {
    
	var tax_select = tax_el;
	
	var rate = $(tax_el).parent().prev().prev().children('input').val();
	var disc = $(tax_el).parent().prev().children('input').val();
	
	
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	
	
	
	var net_amount_el = $(tax_el).parent().next().children('input');
	
	
	
	if( !isNaN(rate))
	{
	
	var amount = parseFloat(rate);
	
	
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}
</script>