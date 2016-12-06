<?php $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$curent_companny = getCurrentCompanyForUser($admin_id);
$oc_id = $curent_companny[0];

$to = $_SESSION['cInventoryJVReport']['to'];
$from = $_SESSION['cInventoryJVReport']['from'];
$jv_type = $_SESSION['cInventoryJVReport']['jv_type'];

$cash_ledger_id=getCashLedgerIdForOC($oc_id);
if(isset($_SESSION['cInventoryJVReport']))
$jvs = generateInventoryJVReport($from,$to,NULL,NULL,$jv_type);

?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Inventory Transaction Reports</h4>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="oc_id" value="<?php echo $oc_id ?>" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="150px">From Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="from" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php if(isset($from))  echo $from; ?>"  autofocus/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td width="150px">From Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="to" id="to" class="datepicker2" placeholder="click to select date!" value="<?php if(isset($from))  echo $to; ?>"  /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td>Type</td>
<td>
	<select name="jv_type" id="jv_type">
    	<?php $jv_types=listInventoryJVTypes();
		foreach($jv_types as $jv_type)
		{ ?>
    	<option value="<?php echo $jv_type['jv_type_id']; ?>" <?php if($jv_type==$jv_type['jv_type_id']) { ?> selected="selected" <?php } ?>><?php echo $jv_type['jv_type']; ?></option>
        <?php } ?>
    </select>
</td>
</tr>

</table>

<table>
<tr>
<td width="160px"></td>
<td>
<input id="disableSubmit" type="submit" value="Generate"  class="btn btn-warning">
 <?php { ?>
<a href="<?php if(!defined("INVENTORY_MODE") && INVENTORY_MODE!=1) echo WEB_ROOT."admin/accounts/"; else echo WEB_ROOT."admin/reports/";  ?>"><input type="button" class="btn btn-success" value="Back"/></a>
<?php } ?>
</td>
</tr>

</table>

</form>
<?php 

if(is_array($jvs))
		{
?>
<hr class="firstTableFinishing" />

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
<?php } ?>
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