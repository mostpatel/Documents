<?php $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$curent_companny = getCurrentCompanyForUser($admin_id);
$oc_id = $curent_companny[0];

$today = date('d/m/Y',strtotime(getTodaysDate()));
$yesterday = date('d/m/Y',strtotime(getPreviousDate($today)));

$cash_ledger_id=getCashLedgerIdForOC($oc_id);

$jvs = generateInventoryJVReport($yesterday);

?>
<?php if(defined('INVENTORY_MODE') && INVENTORY_MODE!=1){ ?>
<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/multi_receipt/index.php"><button class="btn btn-success"> Receipt</button></a>
	<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/payment/index.php"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/multi_jv/index.php"><button class="btn btn-success"> JV </button></a>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/contra/index.php"><button class="btn btn-success"> Contra</button></a>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/purchase_inventory/index.php"><button class="btn btn-success"> Purchase</button></a>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/delivery_challan/index.php"><button class="btn btn-success"> Delivery Challan</button></a>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php"><button class="btn btn-success"> Sales</button></a>
      <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/credit_note/index.php"><button class="btn btn-success"> Credit Note</button></a>
       <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/debit_note/index.php"><button class="btn btn-success"> Debit Note</button></a>
       <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/inventory_jv/index.php"><button class="btn btn-success"> Inventory JV</button></a>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/ledgers/index.php"><button class="btn btn-success"> Add Ledger</button></a>
     <br />
     <br />
     <?php } ?>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/debit_inventory_jv/index.php"><button class="btn btn-success">Inwards Inventory JV</button></a>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Outwards Inventory JV </h4>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="oc_id" value="<?php echo $oc_id ?>" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="150px">Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>" autofocus/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>
<?php if(defined('INVENTORY_MODE') && INVENTORY_MODE!=1){ ?>
<tr>
<td>Ledger / Customer<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" id="to_ledger" name="to_ledger_id" /> 
                   
                            </td>
</tr>
<?php } ?>
<tr>
<td>Type</td>
<td>
	<select name="jv_type" id="jv_type">
    	<?php $jv_types=listInventoryJVTypes();
		foreach($jv_types as $jv_type)
		{ ?>
    	<option value="<?php echo $jv_type['jv_type_id']; ?>"><?php echo $jv_type['jv_type']; ?></option>
        <?php } ?>
    </select>
</td>
</tr>

</table>
<h4 class="headingAlignment">Outwards</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable inventory_table" id="productPurchaseTable">
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
                    <td>
                    	<input name="item_id[]" type="text" class="inventory_item_autocomplete1" /></td>
                      <td><select  name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                        
                      <?php  $godowns = listGodowns();
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>   
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span><select style="width:50px;" name="unit_id[]" class="item_unit">
                    	<option value="-1">-- Unit --</option>
                    </select></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php
			
			 for($i=1;$i<6;$i++) { ?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td>
                    	<input type="text" name="item_id[]" class="inventory_item_autocomplete" /></td>
                     <td><select  name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                       
                      <?php
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span><select style="width:50px;" name="unit_id[]" class="item_unit">
                    	<option value="-1">-- Unit --</option>
                    </select></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0"  /></td>
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>
</table>
<h4 class="headingAlignment">Outward (Credit) Total : <span id="in_total"></span></h4>
<table>
<tr>

<td class="firstColumnStyling" width="150px;">
Remarks  : 
</td>

<td>
<textarea name="remarks" id=""></textarea>
</td>
</tr>
 
</table>

<table>
<tr>
<td width="160px"></td>
<td>
<input id="disableSubmit" type="submit" value="Add JV"  class="btn btn-warning">
 <?php if(isset($customer_id) && is_numeric($customer_id)) { ?>
 <a href="<?php echo  WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
 <?php }else{ ?>
<a href="<?php if(!defined("INVENTORY_MODE") && INVENTORY_MODE!=1) echo WEB_ROOT."admin/accounts/"; else echo WEB_ROOT."admin/reports/";  ?>"><input type="button" class="btn btn-success" value="Back"/></a>
<?php } ?>
</td>
</tr>

</table>

</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Outward Inventory Transaction</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading">Refernce</th>
            <th class="heading">Product / Item</th>
            <th class="heading">Type</th>
              <th class="heading">Remarks</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($jvs as $receipt)
		{
		$sales_id=$receipt['inventory_jv_id'];
		$sale=getInventoryJVById($sales_id);
			if($sale['inventory_jv_mode']==2)
			{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($sale['trans_date'])); ?>
            </td>
             <td>
          <?php   if(is_numeric($sale['ledger_id'])) echo getLedgerNameFromLedgerId($sale['ledger_id']); else if(is_numeric($sale['customer_id'])) echo getCustomerNameByCustomerId($sale['customer_id']); ?>
            </td>
            <td><?php echo $receipt['items_string']; ?></td>
            <td><?php echo $receipt['jv_type']; ?></td>
            <td><?php echo $sale['remarks']; ?>
            </td>
         
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/inventory_jv/index.php?view=details&id='.$receipt['inventory_jv_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/inventory_jv/index.php?view=edit&id='.$receipt['inventory_jv_id']; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/inventory_jv/index.php?action=delete&lid='.$receipt['inventory_jv_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }}?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 

</div>
<div class="clearfix"></div>
<script>
document.product_count=6;
document.barcode_type=0;
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
	getUnitsFromItemId(ui.item.id,target_el);
			 },
	change: function() {
          
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