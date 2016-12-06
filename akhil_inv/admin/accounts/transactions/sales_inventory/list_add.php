<?php $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$curent_companny = getCurrentCompanyForUser($admin_id);
$oc_id = $curent_companny[0];
$default_spares_sales=getDefaultSparesSalesLedgerForOC($oc_id);
$tax_grps = listTaxGroups();
$godowns = listGodowns();
$last_Added_sales_id = getLastAddedSale();
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
else if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$challan_id=$_GET['id']; 
$sales_info = getSalesInfoForDeliveryChallanId($challan_id);
$challan = getACDeliveryChallanByACDeliveryChallanId($challan_id);
$sales_items = getInventoryItemForDeliveryChallanId($challan_id);	// tax details inside the array
$ns_items = getNonStockItemForDeliveryChallanId($challan_id);

if(is_numeric($challan['to_ledger_id']))
			{
				$debit_name = getLedgerNameFromLedgerId($challan['to_ledger_id']);
			}
			else 
			{
				$debit_name = getCustomerNameByCustomerId($challan['to_customer_id']);
			}
}

$today = date('d/m/Y',strtotime(getTodaysDate()));
$yesterday = date('d/m/Y',strtotime(getPreviousDate($today)));
$cash_ledger_id=getCashLedgerIdForOC($oc_id);
if(is_numeric($customer_id))
{	
$sales = generateSalesReport(null,null,null,null,$customer_id);
}
else
{
$sales = generateSalesReport($yesterday,$today,null,$cash_ledger_id);
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
   <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=invoice&id='.$last_Added_sales_id; ?>"><button title="View this entry" class="btn viewBtn btn-success">Print Last Invoice</button></a>
       <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$customer_id; ?>"><button title="View this entry" class="btn viewBtn btn-success">Back To Customer Profile</button></a>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> <?php echo SALES_NAME; ?> For  <?php echo $customer['customer_name']; ?> </h4>
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
<form onsubmit="return submitTransaction(1);" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="oc_id" value="<?php echo $oc_id ?>" />
<?php if(isset($challan_id) && is_numeric($challan_id)) { ?>
<input type="hidden" name="challan_id" value="<?php echo $challan_id ?>"  />
<?php } 
else
 { ?>
<input type="hidden" name="challan_id" value=""  />
<?php } ?>
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px"><?php echo SALES_NAME; ?> Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>" autofocus /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<!--<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" value="<?php echo $emi; ?>" /><span class="DateError customError">Amount Should less than <?php echo -$balance; ?> Rs. !</span>
                            </td>
</tr> -->

<?php if(TAX_CLASS==0) { ?>
<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
					<select id="by_ledger" name="from_ledger_id">
                    	<option value="-1">-- Click To Select --</option>
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
</tr>
<?php } ?>

<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<?php if(isset($customer_id) && is_numeric($customer_id)) { ?>
					<input type="hidden" value="<?php echo getCustomerLedgerNameFromLedgerNameLedgerId('C'.$customer_id); ?>" id="to_ledger" name="to_ledger_id" /> 
                    <input type="hidden" value="customer" id="http_referrer" name="http_referrer" /> 
                     <input type="hidden" value="<?php echo $customer_id; ?>" id="referrer_id" name="referrer_id" /> 
                    <?php echo $customer['customer_name']; ?>
                    <?php } 
					else if(isset($challan_id) && is_numeric($challan_id))
					{ ?>
                     <input type="hidden" value="challan" id="http_referrer" name="http_referrer" /> 
                     <input type="hidden" value="<?php echo $challan_id; ?>" id="referrer_id" name="referrer_id" /> 
                    <input type="hidden" value="<?php if(is_numeric($challan['to_customer_id'])) echo getCustomerLedgerNameFromLedgerNameLedgerId('C'.$challan['to_customer_id']); else if(is_numeric($challan['to_ledger_id'])) echo getCustomerLedgerNameFromLedgerNameLedgerId('L'.$challan['to_ledger_id']); ?>" id="to_ledger" name="to_ledger_id" /> 
                    <?php echo $debit_name; ?>
                    <?php } 
					else { ?>
                   <input type="text" id="to_ledger" name="to_ledger_id" placeholder="Start Typing For Supggestions"  />
                    <?php } ?>
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
                    <option value="<?php echo $bank_cash_ledger['invoice_type_id']; ?>"  ><?php echo $bank_cash_ledger['invoice_type']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>
</tr>

<td width="220px">Invoice No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="invoice_no" id="invoice_no" placeholder="Only Digits Allowed!" value="<?php echo $invoice_counter; ?>"  />
                            </td>
</tr> 

</table>
<?php if(SALES_STOCK==1) { ?>
<h4 class="headingAlignment">Spare parts</h4>
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
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
            <?php if(!isset($challan_id) && !is_numeric($challan_id))
			{ ?>
            <tbody style="display:none" id="p0">
            	<tr>
                    <td>
                    	<input name="item_id[]" type="text" class="inventory_item_autocomplete1"  placeholder="Select Only From  Suggestions" />
                        <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                        <br  />
                       <span style="padding-top:10px;display:block"> Desc : <input name="item_desc[]" type="text"  /></span>
                       <?php } ?></td>
                      <td><select  name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);" >
                        
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
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDisc(this);" /> %</td>
                     <td><?php if(TAX_CLASS==0) { ?><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> <?php } 
							else if(TAX_CLASS==1) 
							{ ?>
                                <select class="sales_ledger_item_wise" name="sales_ledger_id[]" onchange="changeSalesPurchaseLedger(this);" style="margin-bottom:5px;width:170px;">
                                    <option value="-1">-- Sales Ledger --</option>
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
                                <select style="margin-bottom:5px;width:170px;" name="tax_class_id[]" class="tax_class_id" onchange="changeTaxClass(this);">
                                	<option>-- Vat/Tax Class --</option>
                                </select>
                                <select class="tax_group" name="tax_group_id[]" style="width:170px;" onchange="onchangeTaxGroup(this);">
                                	<option>-- Tax --</option>
                                </select>
                            
                             <?php } ?> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php }
			
			 if(isset($sales_items) && count($sales_items)>0) { 
			 for($i=1;$i<=count($sales_items);$i++) { 
			$sales_item=$sales_items[$i-1]['sales_item_details'];
			$item_tax_details = $sales_items[$i-1]['tax_details'];
			  $trans_item_unit_details = getTransItemUnitBySalesItemId($sales_item['sales_item_id']);
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><input name="item_id[]" type="text" class="inventory_item_autocomplete" readonly value="<?php echo getFullItemNameFromItemId($sales_item['item_id'],0,$sales_item['barcode_transaction_id']);  ?>"  placeholder="Select Only From  Suggestions" />
                     <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                    <br /> <span style="padding-top:10px;display:block"> Desc : <input name="item_desc[]" readonly type="text" value="<?php echo $sales_item['item_desc']; ?>" /></span>
                    <?php } ?>
                    </td>
                      <td><select id="godown" name="godown_id[]" style="width:150px;">
                        
                      <?php 
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>" <?php if($model['godown_id']==$sales_item['godown_id']){ ?> selected="selected" <?php } ?>><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>   
                    <td><input type="text" name="quantity[]" readonly class="item_quantity" style="width:35px;"  onchange="onchangeQuantity(this);" value="<?php if(!is_numeric($trans_item_unit_details['quantity'])) echo $sales_item['quantity']; else echo $trans_item_unit_details['quantity']; ?>" /><span style="color:#f00;font-size:12px;"><?php echo getRemainingQuanityForItemForDate($sales_item['item_id'],$sales_item['godown_id']); ?></span><select style="width:50px;" name="" class="item_unit" disabled="disabled">
                    	<?php $units = getUnitsForItemId($sales_item['item_id']); foreach($units as $unit) { ?>
                        	<option value="<?php echo $unit['item_unit_id']; ?>" <?php if($unit['item_unit_id']==$trans_item_unit_details['item_unit_id']) {  ?> selected="selected" <?php } ?>><?php echo $unit['unit_name']; ?></option>
                        <?php } ?>
                    </select><input type="hidden" name="unit_id[]" value="<?php echo $trans_item_unit_details['item_unit_id']; ?>" /></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="<?php echo $sales_item['rate']; ?>" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="<?php echo $sales_item['amount']; ?>"  /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="<?php echo $sales_item['discount']; ?>" onchange="onchangeDisc(this);" /> %</td>
                     <td><?php if(TAX_CLASS==0) { ?><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> <?php } 
							else if(TAX_CLASS==1) 
							{ ?>
                                <select class="sales_ledger_item_wise" name="sales_ledger_id[]" onchange="changeSalesPurchaseLedger(this);" style="margin-bottom:5px;width:170px;">
                                    <option value="-1">-- Sales Ledger --</option>
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
                                <select style="margin-bottom:5px;width:170px;" name="tax_class_id[]" class="tax_class_id" onchange="changeTaxClass(this);">
                                	<option>-- Vat/Tax Class --</option>
                                </select>
                                <select class="tax_group" name="tax_group_id[]" style="width:170px;" onchange="onchangeTaxGroup(this);">
                                	<option>-- Tax --</option>
                                </select>
                            
                             <?php } ?> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="<?php if(is_numeric($sales_item['tax_amount'])) echo round($sales_item['net_amount']+$sales_item['tax_amount'],2); else echo round($sales_item['net_amount'],2);  ?>" /></td>
                    
                            <td><input  <?php  if($i<count($sales_items)) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php  if($i>=count($sales_items)) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } }
			if(!isset($challan_id) && !is_numeric($challan_id))
			{
			 for($i=1;$i<6;$i++) { ?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td>
                    	<input type="text" name="item_id[]" class="inventory_item_autocomplete"  placeholder="Select Only From  Suggestions" />
                          <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?><br />
                         <span style="padding-top:10px;display:block"> Desc : <input name="item_desc[]" type="text" style="width:160px;" /></span>
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
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span><select style="width:50px;display:block;" name="unit_id[]" class="item_unit">
                    	<option value="-1">-- Unit --</option>
                    </select></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0"  /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDisc(this);" /> %</td>
                     <td style="width:170px;"><?php if(TAX_CLASS==0) { ?><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> <?php } 
							else if(TAX_CLASS==1) 
							{ ?>
                                <select class="sales_ledger_item_wise" name="sales_ledger_id[]" onchange="changeSalesPurchaseLedger(this);" style="margin-bottom:5px;width:170px;">
                                    <option value="-1">-- Sales Ledger --</option>
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
                                <select style="margin-bottom:5px;width:170px;" name="tax_class_id[]" class="tax_class_id" onchange="changeTaxClass(this);">
                                	<option>-- Vat/Tax Class --</option>
                                </select>
                                <select class="tax_group" name="tax_group_id[]" style="width:170px;" onchange="onchangeTaxGroup(this);">
                                	<option>-- Tax --</option>
                                </select>
                            
                             <?php } ?> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php }} ?>
    	</table>
    </td>

</tr>
</table>
<h4 class="headingAlignment">Total : <span id="in_total"></span></h4>
<?php } ?>
<?php if(SALES_NON_STOCK==1) { ?>
<h4 class="headingAlignment"> <?php if(EDMS_MODE==1) { ?>Labour / <?php } ?>Service</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable ns_inventory_table" id="nonStockSaleTable">
    		<tr>
            	<th>Service Name / Code</th>
                <th>Rate</th>
                <th>Disc.</th>
                <th>Tax</th>
                <th>Nett Amt.</th>
                <th></th>
            </tr>
            <?php if((!isset($challan_id) && !is_numeric($challan_id)) || DELIVERY_NON_STOCK==0)
			 { ?>
            <tbody style="display:none" id="ns0">
            	<tr>
                    <td><input type="text" name="ns_item_id[]" class="inventory_ns_item_autocomplete1"  placeholder="Select Only From  Suggestions" />
                      <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                    <br />
                         <span style="padding-top:10px;display:block"> Desc : <input name="ns_item_desc[]" type="text" style="width:160px;" /></span>
                         <?php } else if(defined('INVOICE_TAX_YEARS') && INVOICE_TAX_YEARS==1) {  ?>
                         Year : <select name="ns_item_desc[]">
						<?php $product_Desc=listProductDesc(); foreach($product_Desc as $productdesc) { ?>
                         <option value="<?php echo $productdesc['product_desc']; ?>"><?php echo $productdesc['product_desc'] ?></option>
                       
                          <?php } ?>
                         </select>
                         <?php  } ?>
                         
                    </td>
                     
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" /></td>
                     
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><?php if(TAX_CLASS==0) { ?><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> <?php } 
							else if(TAX_CLASS==1) 
							{ ?>
                                <select class="sales_ledger_item_wise" name="ns_sales_ledger_id[]" onchange="changeSalesPurchaseLedger(this);" style="margin-bottom:5px;width:170px;">
                                    <option value="-1">-- Sales Ledger --</option>
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
                                <select style="margin-bottom:5px;width:170px;" name="ns_tax_class_id[]" class="tax_class_id" onchange="changeTaxClass(this);">
                                	<option>-- Vat/Tax Class --</option>
                                </select>
                                <select class="tax_group" name="ns_tax_group_id[]" style="width:170px;" onchange="onchangeTaxGroupNS(this);">
                                	<option>-- Tax --</option>
                                </select>
                            
                             <?php } ?> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
              <?php } if(DELIVERY_NON_STOCK==1 && isset($ns_items) && count($ns_items)>0) { for($i=1;$i<=count($ns_items);$i++) { 
			$sales_item=$ns_items[$i-1]['sales_item_details'];
			$item_tax_details = $ns_items[$i-1]['tax_details'];
			
			?>
            <tbody id="ns<?php echo $i; ?>">
            	<tr >
                    <td>
                   
                    <input type="text"  name="ns_item_id[]"  class="inventory_ns_item_autocomplete" value="<?php echo getFullItemNameFromItemId($sales_item['item_id']); ?>"  readonly="readonly"  placeholder="Select Only From Suggestions" />
                     <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                     <br />
                         <span style="padding-top:10px;display:block"> Desc : <input name="ns_item_desc[]" type="text" style="width:160px;" value="<?php echo $sales_item['item_desc']; ?>" /></span>
                         <?php   }  else if(defined('INVOICE_TAX_YEARS') && INVOICE_TAX_YEARS==1) {  ?>
                         Year : <select name="ns_item_desc[]" disabled="disabled">
                         <?php $product_Desc=listProductDesc(); foreach($product_Desc as $productdesc) { ?>
                         <option value="<?php echo $productdesc['product_desc']; ?>" <?php if($sales_item['item_desc']==$productdesc['product_desc']) { ?><?php } ?>><?php echo $productdesc['product_desc'] ?></option>
                       
                          <?php } ?>
                       
                         </select>
                         <input name="ns_item_desc[]" type="hidden"  value="<?php echo $sales_item['item_desc']; ?>" />
                  
                         <?php  } ?>
                         </td>
                    
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="<?php echo $sales_item['amount']; ?>" onchange="onchangeRateNS(this);" /></td>
                    
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="<?php echo $sales_item['discount']; ?>" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><?php if(TAX_CLASS==0) { ?><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> <?php } 
							else if(TAX_CLASS==1) 
							{ ?>
                                <select class="sales_ledger_item_wise" name="ns_sales_ledger_id[]" onchange="changeSalesPurchaseLedger(this);" style="margin-bottom:5px;width:170px;">
                                    <option value="-1">-- Sales Ledger --</option>
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
                                <select style="margin-bottom:5px;width:170px;" name="ns_tax_class_id[]" class="tax_class_id" onchange="changeTaxClass(this);">
                                	<option>-- Vat/Tax Class --</option>
                                </select>
                                <select class="tax_group" name="ns_tax_group_id[]" style="width:170px;" onchange="onchangeTaxGroupNS(this);">
                                	<option>-- Tax --</option>
                                </select>
                            
                             <?php } ?> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="<?php if(is_numeric($sales_item['tax_amount'])) echo round($sales_item['net_amount']+$sales_item['tax_amount'],2); else echo round($sales_item['net_amount'],2);  ?>" /></td>
                    
                            <td></td>
            	</tr>
            </tbody>
            <?php } } 
			if((!isset($challan_id) && !is_numeric($challan_id)) || DELIVERY_NON_STOCK==0)
			{
             for($i=1;$i<6;$i++) { ?>
            <tbody id="ns<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="ns_item_id[]" class="inventory_ns_item_autocomplete"  placeholder="Select Only From  Suggestions" />
                      <?php if(defined('INVOICE_ITEM_DESC') && INVOICE_ITEM_DESC==1) { ?>
                       <br />
                         <span style="padding-top:10px;display:block"> Desc : <input name="ns_item_desc[]" type="text" style="width:160px;" /></span>
                         <?php  } else if(defined('INVOICE_TAX_YEARS') && INVOICE_TAX_YEARS==1) {  ?>
                         Year : <select name="ns_item_desc[]">
                       <?php $product_Desc=listProductDesc(); foreach($product_Desc as $productdesc) { ?>
                         <option value="<?php echo $productdesc['product_desc']; ?>"><?php echo $productdesc['product_desc'] ?></option>
                       
                          <?php } ?>
                         </select>
                         <?php  } ?>
                    </td>
                    
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" /></td>
                    
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><?php if(TAX_CLASS==0) { ?><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> <?php } 
							else if(TAX_CLASS==1) 
							{ ?>
                                <select class="sales_ledger_item_wise" name="ns_sales_ledger_id[]" onchange="changeSalesPurchaseLedger(this);" style="margin-bottom:5px;width:170px;">
                                    <option value="-1">-- Sales Ledger --</option>
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
                                <select style="margin-bottom:5px;width:170px;" name="ns_tax_class_id[]" class="tax_class_id" onchange="changeTaxClass(this);">
                                	<option>-- Vat/Tax Class --</option>
                                </select>
                                <select class="tax_group" name="ns_tax_group_id[]" style="width:170px;" onchange="onchangeTaxGroupNS(this);">
                                	<option>-- Tax --</option>
                                </select>
                            
                             <?php } ?> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php }} ?>
    	</table>
    </td>

</tr>

</table>
<?php } ?>
<h4 class="headingAlignment"><?php  if(EDMS_MODE==1) { ?>Labour / <?php } ?> Service Total : <span id="ns_total"></span></h4>
<h4 class="headingAlignment">Grand Total : <span id="grand_total"></span></h4>
<div id="form_details">
<h4 class="headingAlignment"><span id="form_type"></span> Form Details</h4>
<table width="100%">
<tr >
<td>Form No: </td>
				<td>
					<input type="text" id="form_no" name="form_no"  /> 
                  
                            </td>

<td>Form Date : </td>
				<td>
					<input type="text" id="form_date" name="form_date" class="datepicker3" /> 
                  
                            </td>
</tr>
</table>
</div>
<?php if(defined('INVOICE_ADD_INFO') && INVOICE_ADD_INFO==1) {  ?>
<h4 class="headingAlignment">Additional Info</h4>
<table width="100%">
<tr >
<td>Delivery Note : </td>
				<td>
					<input type="text" id="delivery_note" name="delivery_note"  value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['delivery_note']; ?>"/> 
                  
                            </td>

<td>Terms of Payement : </td>
				<td>
					<input type="text" id="terms_of_payment" name="terms_of_payment" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['terms_of_payment']; ?>" /> 
                  
                            </td>
</tr>

<tr >
<td>Supplier's Ref : </td>
				<td>
					<input type="text" id="supplier_ref_no" name="supplier_ref_no" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['supplier_ref_no']; ?>" /> 
                  
                            </td>

<td>Other Reference(s) : </td>
				<td>
					<input type="text" id="other_reference" name="other_reference" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['other_reference']; ?>" /> 
                  
                            </td>
</tr>

<tr >
<td>Buyer's Order No : </td>
				<td>
					<input type="text" id="buyers_order_no" name="buyers_order_no" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['buyers_order_no']; ?>" /> 
                  
                            </td>

<td>Dated : </td>
				<td>
					<input type="text" id="order_date" name="order_date" value="<?php if(isset($sales_info['delivery_note'])){ if($sales_info['order_date']!="1970-01-01") echo date('d/m/Y',strtotime($sales_info['order_date'])); } ?>" placeholder="dd/mm/yyyy" /> 
                  
                            </td>
</tr>

<tr >
<td>Despatch Document No : </td>
				<td>
					<input type="text" id="despatch_doc_no" name="despatch_doc_no" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['despatch_doc_no']; ?>" /> 
                  
                            </td>

<td>Dated : </td>
				<td>
					<input type="text" id="despatch_dated" name="despatch_dated" value="<?php if(isset($sales_info['delivery_note'])){ if($sales_info['despatch_dated']!="1970-01-01") echo date('d/m/Y',strtotime($sales_info['despatch_dated'])); } ?>" placeholder="dd/mm/yyyy" /> 
                  
                            </td>
</tr>

<tr >
<td>Despatched through : </td>
				<td>
					<input type="text" id="despatched_through" name="despatched_through" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['despatched_through']; ?>" /> 
                  
                            </td>

<td>Destination : </td>
				<td>
					<input type="text" id="destination" name="destination" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['destination']; ?>" /> 
                  
                            </td>
</tr>

<tr >
<td>Terms Of Delivery : </td>
				<td >
					<input type="text" id="terms_of_delivery" name="terms_of_delivery" value="<?php if(isset($sales_info['delivery_note'])) echo $sales_info['terms_of_delivery']; ?>" /> 
                  
                            </td>
                            <td>Consignee Address : </td>
				<td>
					<textarea  id="consignee_address" name="consignee_address" ><?php if(isset($sales_info['delivery_note'])) echo $sales_info['consignee_address']; ?></textarea> 
                  
                            </td>   
</tr>

</table>
<?php } ?>
<table>
<tr>
<td width="250px;">Sales Type : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="ref_type" name="ref_type" onchange="changeRefFeild(this.value)" >
                    	<option value="0" selected="selected">NEW</option>
                        <option value="2" >Against Advance Receipt</option>
                    </select>
                            </td>
</tr>

<tr  id="pay_ref_new">
<td>Sales Ref : </td>
				<td>
					<input type="text" id="pay_ref_new" name="ref" /> 
                  
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_against">
<td>Sales Ref : </td>
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
<textarea name="remarks" id="transliterateTextarea"></textarea>
</td>
</tr>

<tr>
<td width="220px">Due Date : </td>
				<td>
					<input type="text" name="delivery_date" id="delivery_date" class="datepicker2" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>


 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add <?php echo SALES_NAME ?>"  class="btn btn-warning">
 <?php if(isset($customer_id) && is_numeric($customer_id)) { ?>
 <a href="<?php echo  WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
 <?php }else{ ?>
<a href="<?php echo  WEB_ROOT."admin/accounts/"; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
<?php } ?>
</td>
</tr>

</table>

</form>

<hr class="firstTableFinishing" />
<?php if(!is_numeric($customer_id)) { ?>
<h4 class="headingAlignment">List of <?php echo SALES_NAME; ?> for period (<?php echo $yesterday." - ".$today; ?>)</h4>
<?php } else { ?>
<h4 class="headingAlignment">List of <?php echo SALES_NAME; ?> for  <?php echo $customer['customer_name']; ?></h4>
<?php } ?>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading">Amount</th>
             <th class="heading">Debit</th>
              <th class="heading">Credit</th>
              <th class="heading">Received</th>
               <th class="heading">Balance</th>
              <th class="heading">Invoice</th>
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
			$sales_id=$receipt['sales_id'];
			$sales=getSaleById($sales_id);
			$receipt_amount = getReceiptAmountForSalesId($sales_id);
			
			$tax_amount = getTotalTaxForSalesId($sales_id);
			$total_amount = $receipt['amount'] + $tax_amount;
			$remaining_amount = $total_amount - $receipt_amount;
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
            <td><?php echo $total_amount." Rs"; ?>
            </td>
            <td><?php if(is_numeric($receipt['from_ledger_id']))echo $receipt['from_ledger_name']; else echo $receipt['from_customer_name']; ?>
            </td>
          	 <td><?php if(is_numeric($receipt['to_ledger_id'])) echo $receipt['to_ledger_name']; else echo $receipt['customer_name']; ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=allReceipts&id='.$sales_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Receipt</span></button></a>
            </td>
             <td align="center" ><?php echo number_format($remaining_amount)." Rs"; ?>  <br /> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?sales_id='.$receipt['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-warning">Add Receipt</button></a>
            </td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=invoice&id='.$receipt['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-success">Invoice</button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$receipt['sales_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=edit&id='.$receipt['sales_id']; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?action=delete&lid='.$receipt['sales_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
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
	
	var tax_select =  $(quantity_el).parent().next().next().next().next().children('select')[<?php if(TAX_CLASS==0) echo 0; else echo 2; ?>];

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
	var tax_select =  $(rate_el).parent().next().next().next().children('select')[<?php if(TAX_CLASS==0) echo 0; else echo 2; ?>];
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
	var tax_select =  $(disc_el).parent().next().children('select')[<?php if(TAX_CLASS==0) echo 0; else echo 2; ?>];
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
	
	var tax_select =  $(rate_el).parent().next().next().children('select')[<?php if(TAX_CLASS==0) echo 0; else echo 2; ?>];
	
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
	
	var tax_select =  $(disc_el).parent().next().children('select')[<?php if(TAX_CLASS==0) echo 0; else echo 2; ?>];
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
	
	var full_ledger_name = encodeURIComponent(document.getElementById('to_ledger').value);
	
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
 var url = "getInvoiceNo.php?id="+full_ledger_name+"&state="+inv_type;

xmlhttp.open("GET",url,true);
xmlhttp.send();

	
}

</script>