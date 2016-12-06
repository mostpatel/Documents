<?php 
if(isset($_GET['bulk'])) // if bulk = 1 show customer groups or if bulk = 2 show customer list with pank card 
$bulk = $_GET['bulk'];
else 
$bulk=0;
$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$curent_companny = getCurrentCompanyForUser($admin_id);
$oc_id = $curent_companny[0];
$challan_no = getChallanCounterForOCID($oc_id);
$last_added_challan_id = getLastACDeliveryChallan();
?>
<?php if(isset($_GET['cid']) && is_numeric($_GET['cid']))
{
$customer_id=$_GET['cid']; 
$customer = getCustomerDetailsByCustomerId($customer_id);
if($customer=="error")
{
?>
<script>
  window.history.back()
</script>	
<?php } 
}
$today = date('d/m/Y',strtotime(getTodaysDate()));
$yesterday = date('d/m/Y',strtotime(getPreviousDate($today)));
if(is_numeric($customer_id))
{	
$sales = getAllACDeliveryChallansForCustomerId($customer_id);
}
else
{	
$sales = getDeliveryChallansBetweenDates();
}

?>
<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/multi_receipt/index.php"><button class="btn btn-success"> Receipt</button></a>
	<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/payment/index.php"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/multi_jv/index.php"><button class="btn btn-success"> JV </button></a>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/contra/index.php"><button class="btn btn-success"> Contra</button></a>
    <?php if(TAX_MODE==0) { ?>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/purchase_inventory/index.php"><button class="btn btn-success"> Purchase</button></a>
    <?php } ?>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/delivery_challan/index.php"><button class="btn btn-success"> <?php echo DELIVERY_CHALLAN_NAME; ?></button></a>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php"><button class="btn btn-success"><?php echo SALES_NAME; ?></button></a>
      <?php if(TAX_MODE==0) { ?>
      <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/credit_note/index.php"><button class="btn btn-success"> Credit Note</button></a>
       <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/debit_note/index.php"><button class="btn btn-success"> Debit Note</button></a>
       <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/inventory_jv/index.php"><button class="btn btn-success"> Inventory JV</button></a>
       <?php } ?>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/ledgers/index.php"><button class="btn btn-success"> Add Ledger</button></a>
     <br><br>
     <?php if(is_numeric($last_added_challan_id)) { ?>
	 	 <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=delivery_challan&id='.$last_added_challan_id;  ?>"><button class="btn btn-success">Print Last Delivery Challan</button></a>
	 <?php } ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> <?php echo DELIVERY_CHALLAN_NAME; ?> </h4>
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
<input type="hidden" name="oc_id" value="<?php echo $oc_id; ?>" />
<?php if(is_numeric($customer_id)) { ?>
<input type="hidden" name="customer_redirect" value="<?php echo $customer_id; ?>" />
<?php } else { ?>
<input type="hidden" name="customer_redirect" value="0" />
<?php } ?>
<input type="hidden" name="bulk" value="<?php echo $bulk; ?>" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px"><?php echo DELIVERY_CHALLAN_NAME; ?> Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>" autofocus /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<!--<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" value="<?php echo $emi; ?>" /><span class="DateError customError">Amount Should less than <?php echo -$balance; ?> Rs. !</span>
                            </td>
</tr> 
<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
					<select id="by_ledger" name="from_ledger_id">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listSalesLedgers();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>" <?php if(is_numeric($default_spares_sales) && $bank_cash_ledger['ledger_id']==$default_spares_sales) { ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr> -->
<?php if($bulk==0) { ?>
<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<?php if(isset($customer_id) && is_numeric($customer_id)) { ?>
					<input type="hidden" value="<?php echo getCustomerLedgerNameFromLedgerNameLedgerId('C'.$customer_id); ?>" id="to_ledger" name="to_ledger_id" /> 
                    <?php echo $customer['customer_name']; ?>
                    <?php } else { ?>
                   <input type="text" id="to_ledger" name="to_ledger_id" placeholder="Start Typing For Supggestions" />
                    <?php } ?>
                            </td>
</tr>
<?php } else if($bulk==1) { ?>
<tr>
<td>By (Debit) Customer Group <span class="requiredField">* </span> : </td>
				<td>
					
                   <input type="text" id="to_customer_group" name="customer_group_id"  placeholder="Start Typing For Supggestions"/>
              
                            </td>
</tr>
<?php } else if($bulk==2) { ?>
</table>

<h4 class="headingAlignment">List of Customers</h4>
  
   
    <table style="margin-bottom:50px;" id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
            <th class="heading">
            <input type="checkbox" id="selectAllTR" name="selectAllTR"  />
            </th>
        	<th class="heading no_sort">No</th>
            <th class="heading">Name</th>
           
           
            <th class="heading">Pan No</th>
            <th class="heading">Tin No</th>
            <th class="heading">CST No</th>
            <th class="heading">Service Tax No</th>
            <th class="heading no_print btnCol" ></th>
          
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
         	<td><input type="checkbox" class="selectTR" name="selectTR[]" value="C<?php echo $agencyDetails['customer_id']; ?>" /></td>
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php  echo $agencyDetails['customer_name']; ?>
            </td>
            
             <td><?php echo $agencyDetails['pan_no'] ?>
            </td> 
             <td><?php echo $agencyDetails['tin_no'] ?>
            </td>
            <td><?php echo $agencyDetails['cst_no'] ?>
            </td>  
            <td><?php echo $agencyDetails['service_tax_no'] ?>
            </td>  
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$agencyDetails['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
             </td>
          
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>

<table>
<?php } ?>
<tr>
<td width="220px"><?php echo DELIVERY_CHALLAN_NAME; ?> No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="challan_no" id="invoice_no" placeholder="Only Digits Allowed!" value="<?php echo $challan_no; ?>"  />
                            </td>
</tr> 


</table>
<?php if(DELIVERY_STOCK==1) { ?>
<h4 class="headingAlignment">Spare parts</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable inventory_table" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Godown</th>
                 <th>Qty</th>
               
                 <th></th>
            </tr>
            <tbody style="display:none" id="p0">
            	<tr>
                    <td>
                    	<input name="item_id[]" type="text" style="width:75%" class="inventory_item_autocomplete1" placeholder="Start Typing / Select Only From Available Suggestions" />
                        <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                        <br  />
                       <span style="padding-top:10px;display:block"> Desc : <input name="item_desc[]" type="text"  placeholder="Use ## to seperate lines!" /></span>
                       <?php } ?></td>
                      <td><select  name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                        
                      <?php  $godowns = listGodowns();
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>   
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" /><span style="color:#f00;font-size:12px;"></span><select style="width:50px;" name="unit_id[]" class="item_unit">
                    	<option value="-1">-- Unit --</option>
                    </select></td>
                    
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php
			
			 for($i=1;$i<6;$i++) { ?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td>
                    	<input type="text" name="item_id[]" style="width:75%" class="inventory_item_autocomplete" placeholder="Start Typing / Select Only From Available Suggestions" />
                          <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?><br />
                         <span style="padding-top:10px;display:block"> Desc : <input name="item_desc[]" type="text" style="width:160px;" placeholder="Use ## to seperate lines!" /></span>
                         <?php } ?>
                         </td>
                     <td><select  name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                       
                      <?php
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span><select name="unit_id[]" style="display:block;" class="item_unit">
                    	<option value="-1">-- Unit --</option>
                    </select></td>
                    
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>
</table>
<h4 class="headingAlignment">Total : <span id="in_total"></span></h4>
<?php }
if(DELIVERY_NON_STOCK==1)
{
 ?>
<h4 class="headingAlignment"><?php  if(EDMS_MODE==1) { ?>Labour / <?php } ?> Service</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable ns_inventory_table" id="nonStockSaleTable">
    		<tr>
            	<th>Service Name / Code</th>
                 <th></th>
            </tr>
            <tbody style="display:none" id="ns0">
            	<tr>
                    <td><input type="text" name="ns_item_id[]"  class="inventory_ns_item_autocomplete1" placeholder="Select Only From Avl Suggestions" placeholder="Start Typing / Select Only From Available Suggestions" />
                      <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                    <br />
                         <span style="padding-top:10px;display:block"> Desc : <input name="ns_item_desc[]" type="text" style="width:160px;" placeholder="Use ## to seperate lines!" /></span>
                         <?php }  else if(defined('INVOICE_TAX_YEARS') && INVOICE_TAX_YEARS==1) {  ?>
                         Year : <select name="ns_item_desc[]">
                        <?php $product_Desc=listProductDesc(); foreach($product_Desc as $productdesc) { ?>
                         <option value="<?php echo $productdesc['product_desc']; ?>"><?php echo $productdesc['product_desc'] ?></option>
                       
                          <?php } ?>
                         </select>
                         <?php  } ?>
                    </td>
                     
                     
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="ns<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="ns_item_id[]" class="inventory_ns_item_autocomplete" placeholder="Start Typing / Select Only From Available Suggestions" />
                      <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                       <br />
                         <span style="padding-top:10px;display:block"> Desc : <input name="ns_item_desc[]" type="text" style="width:160px;" placeholder="Use ## to seperate lines!" /></span>
                         <?php } else if(defined('INVOICE_TAX_YEARS') && INVOICE_TAX_YEARS==1) {  ?>
                         Year : <select name="ns_item_desc[]">
                        <?php $product_Desc=listProductDesc(); foreach($product_Desc as $productdesc) { ?>
                         <option value="<?php echo $productdesc['product_desc']; ?>"><?php echo $productdesc['product_desc'] ?></option>
                       
                          <?php } ?>
                         </select>
                         <?php  } ?>
                    </td>
                    
                    
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>

</table>
<?php } ?>

<?php if(defined('INVOICE_ADD_INFO') && INVOICE_ADD_INFO==1) {  ?>
<h4 class="headingAlignment">Additional Info</h4>
<table width="100%">
<tr >
<td>Delivery Note : </td>
				<td>
					<input type="text" id="delivery_note" name="delivery_note" /> 
                  
                            </td>

<td>Terms of Payement : </td>
				<td>
					<input type="text" id="terms_of_payment" name="terms_of_payment" /> 
                  
                            </td>
</tr>

<tr >
<td>Supplier's Ref : </td>
				<td>
					<input type="text" id="supplier_ref_no" name="supplier_ref_no" /> 
                  
                            </td>

<td>Other Reference(s) : </td>
				<td>
					<input type="text" id="other_reference" name="other_reference" /> 
                  
                            </td>
</tr>

<tr >
<td>Buyer's Order No : </td>
				<td>
					<input type="text" id="buyers_order_no" name="buyers_order_no" /> 
                  
                            </td>

<td>Dated : </td>
				<td>
					<input type="text" id="order_date" name="order_date" placeholder="dd/mm/yyyy" /> 
                  
                            </td>
</tr>

<tr >
<td>Despatch Document No : </td>
				<td>
					<input type="text" id="despatch_doc_no" name="despatch_doc_no" /> 
                  
                            </td>

<td>Dated : </td>
				<td>
					<input type="text" id="despatch_dated" name="despatch_dated" placeholder="dd/mm/yyyy" /> 
                  
                            </td>
</tr>

<tr >
<td>Despatched through : </td>
				<td>
					<input type="text" id="despatched_through" name="despatched_through" /> 
                  
                            </td>

<td>Destination : </td>
				<td>
					<input type="text" id="destination" name="destination" /> 
                  
                            </td>
</tr>

<tr >
<td>Terms Of Delivery : </td>
				<td>
					<input type="text" id="terms_of_delivery" name="terms_of_delivery" /> 
                  
                            </td>
<td>Consignee Address : </td>
				<td>
					<textarea  id="consignee_address" name="consignee_address" ></textarea> 
                  
                            </td>                            
</tr>

</table>
<?php } ?>
<table>


<td class="firstColumnStyling" width="240px">
Remarks (ctrl + g to change english/gujarati) : 
</td>

<td>
<textarea name="remarks" id="transliterateTextarea"></textarea>
</td>
</tr>

</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add <?php echo DELIVERY_CHALLAN_NAME; ?>"  class="btn btn-warning">
 <?php if(isset($customer_id) && is_numeric($customer_id)) { ?>
 <a href="<?php echo  WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back to customer"/></a>
 <?php }else{ ?>
<a href="<?php echo  WEB_ROOT."admin/accounts/"; ?>"><input type="button" class="btn btn-success" value="Back to accounts"/></a>
<?php } ?>
</td>
</tr>

</table>

</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of <?php echo DELIVERY_CHALLAN_NAME; ?> </h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	 <th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading"><?php echo DELIVERY_CHALLAN_NAME; ?> No</th>
              <th class="heading">Debit</th>
              <th class="heading"><?php echo SALES_NAME; ?></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($sales as $receipt)
		{
			if(is_numeric($receipt['to_ledger_id']))
			{
				$debit_name = getLedgerNameFromLedgerId($receipt['to_ledger_id']);
			}
			else 
			{
				$debit_name = getCustomerNameByCustomerId($receipt['to_customer_id']);
			}
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
            <td><?php echo $receipt['challan_no']; ?>
            </td>
            <td><?php echo $debit_name; ?>
            </td>
          	 
             <td class="no_print">
          
             <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=delivery_challan&id='.$receipt['delivery_challan_id'];  ?>"><button title="View this entry" class="btn viewBtn <?php echo "btn-success"; ?>"><?php ?>Print Delivery Challan</button></a>
              <a href="<?php if(is_numeric($receipt['sales_id'])) echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$receipt['sales_id']; else echo  WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?id='.$receipt['delivery_challan_id']; ?>"><button title="View this entry" class="btn viewBtn <?php if(is_numeric($receipt['sales_id'])) echo "btn-success"; else echo "btn-warning"; ?>"><?php if(is_numeric($receipt['sales_id'])) echo "View"; else echo "Create"; ?> Invoice</button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=details&id='.$receipt['delivery_challan_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=edit&id='.$receipt['delivery_challan_id']; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?action=delete&lid='.$receipt['delivery_challan_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
            
          
  
        </tr>
         <?php }?>
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
	
	$( "#to_customer_group" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
		
                $.getJSON ('<?php echo WEB_ROOT; ?>json/customerGroup.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
		 	
			$( "#to_customer_group" ).val(ui.item.label);
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
	getRateQuantityAndTaxForSalesFromItemId(ui.item.id,target_el); 
	
	getUnitsFromItemId(ui.item.id,target_el);
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

function changeInvoiceNo()
{
    var e = document.getElementById('retail_tax');
	
	var inv_type = e.options[e.selectedIndex].value;
	
	var full_ledger_name = document.getElementById('to_ledger').value;
	
	if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {	
	
    var res=eval(xmlhttp.responseText);
	invoice_no=res[0];	
    document.getElementById('invoice_no').value=invoice_no;		
	
    }
  }
 
xmlhttp.open("GET","getInvoiceNo.php?id="+full_ledger_name+"&state="+inv_type,true);
xmlhttp.send();

	
}

</script>