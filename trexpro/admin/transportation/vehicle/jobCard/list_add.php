<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$vehicle_id = $_GET['id'];
$vehicle = getVehicleById($vehicle_id);	
$customer_id = $vehicle['customer_id'];
$customer = getCustomerDetailsByCustomerId($customer_id);
$oc_id =$_SESSION['edmsAdminSession']['oc_id'];
$invoice_counter = getInvoiceCounterForOCID($oc_id);
$job_card_counter = getJobCounterForOCID($oc_id);
$tax_grps = listTaxGroups();
 $godowns = listGodowns();
 $default_spares_sales=getDefaultSparesSalesLedgerForOC($oc_id);
}
else
exit;
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Create a Job Card For <?php echo $customer['customer_name']; echo " ( ".$vehicle['vehicle_reg_no']." - ".getModelNameById($vehicle['model_id'])." )"; ?>  </h4>
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
<form onsubmit="" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden"  name="to_ledger_id" value="C<?php echo $customer_id; ?>" /> 
<input type="hidden"  name="customer_id" value="<?php echo $customer_id; ?>" /> 
<input type="hidden"  name="vehicle_id" value="<?php echo $vehicle_id; ?>" /> 
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Job Card Date and Time<span class="requiredField">* </span> : </td>
				<td>
                <div id="datetimepicker1" class="input-append date">
<input type="text" name="jb_date_time" id="date_of_sale" class="datetimepicker1" data-format="dd/MM/yyyy hh:mm:ss" placeholder="click to select date!" value="<?php echo date('d/m/Y H:i:s',strtotime(getTodaysDateTime())); ?>"/>
 <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>
				
                            </td>
</tr>
<tr>
<td width="220px">Job Card No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="job_card_no" id="job_card_no" placeholder="Only Digits!" value="<?php echo $job_card_counter; ?>" />
                            </td>
</tr> 

<tr>
<td>Sales Account<span class="requiredField">* </span> : </td>
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
</tr>


<tr>
<td>Service Type<span class="requiredField">* </span> : </td>
				<td>
					<select id="service_type" name="service_type_id" onchange="toggleServiceNoSelect(this.value);">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listServiceTypes();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['service_type_id']; ?>"><?php echo $bank_cash_ledger['service_type']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<tr style="display:none" id="srevice_no_tr">
<td>Service No<span class="requiredField">* </span> : </td>
				<td>
					<select id="service_no" name="free_service_no">
                    	<option value="0">-- Please Select --</option>
                    <?php
					
					for($i=1;$i<15;$i++)
					{
					?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<tr>
<td width="220px">Date of Sale : </td>
				<td>
					<input type="text" name="date_of_sale"  class="datepicker1" placeholder="click to select date!" /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td width="220px">Kms Covered : </td>
				<td>
					<input type="text" name="kms_covered" id="kms_covered" placeholder="Only Digits!"  />
                            </td>
</tr> 






<tr>
<td width="220px">Estimated Cost : </td>
				<td>
					<input type="text" name="estimated_cost" id="estimated_cost" placeholder="Only Digits!"  />
                            </td>
</tr> 
<tr>
<td width="220px">Delivery Promise<span class="requiredField">* </span> : </td>
				<td>
                <div id="datetimepicker4" class="input-append date">
<input type="text" name="delivery_promise" id="delivery_promise" class="datetimepicker1" data-format="dd/MM/yyyy hh:mm:ss" placeholder="click to select date!" value="<?php echo date('d/m/Y H:i:s',strtotime(getTodaysDateTime())); ?>" />
 <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>
				
                            </td>
</tr>
<tr id="addcontactTrCustomer">
                <td>
                Customer Compalints : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="custmer_complaints[]" placeholder="Customer Complaints!" /> <span class="addContactSpan"><input type="button" title="add more customer Complaints" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
            Customer Compalints  : 
            </td>
            
            <td id="addcontactTd">
             <input type="text" class="contact" id="customerContact" name="custmer_complaints[]" placeholder="Customer Complaints!" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
               
       
       
<!-- end for regenreation purpose -->

<tr id="addcontactTrCustomer1">
                <td>
                Actual Work Done : 
                </td>
                
                <td id="addcontactTd1">
                <input type="text" class="contact" id="customerContact1" name="work_done[]" placeholder="Actual Work Done!" /> <span class="addContactSpan"><input type="button" title="add more Work Done" value="+" class="btn btn-success addContactbtnCustomer1"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer1">
            <td>
           Actual Work Done : 
            </td>
            
            <td id="addcontactTd1">
             <input type="text" class="contact" id="customerContact1" name="work_done[]" placeholder="Actual Work Done!" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
               
       
       
<!-- end for regenreation purpose -->

<tr id="addcontactTrCustomer2">
                <td>
                Remarks : 
                </td>
                
                <td id="addcontactTd2">
                <input type="text" class="contact" id="customerContact2" name="remarks[]" placeholder="Remarks!" /> <span class="addContactSpan"><input type="button" title="add more remarks" value="+" class="btn btn-success addContactbtnCustomer2"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer2">
            <td>
            Remarks : 
            </td>
            
            <td id="addcontactTd2">
             <input type="text" class="contact" id="customerContact2" name="remarks[]" placeholder="Remarks!" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
               
       
       
<!-- end for regenreation purpose -->

</table>
<h4 class="headingAlignment">Service Check</h4>
<table class="insertTableStyling no_print">
<?php

$service_checks = listServiceChecksOrderByType();
foreach($service_checks as $service_check)
{
?>
<tr>
	<td><?php echo $service_check['service_check']; ?> :</td>
    <td>
    	<?php 
		$service_check_values=listServiceCheckValuesForServiceCheck($service_check['service_check_id']);
		if($service_check['check_type']==0) {
			
			foreach($service_check_values as $sr_value)
			{
			 ?>
    	<input  type="radio" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="service_check<?php echo $sr_value['service_check_value_id']; ?>" value="<?php echo $sr_value['service_check_value_id']; ?>"  /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="service_check<?php echo $sr_value['service_check_value_id'];?>"><?php echo $sr_value['service_check_value']; ?></label>
	
    	<?php } ?>
        <input type="radio" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="none<?php echo $service_check['service_check_id'];  ?>"  value="-1" /><label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="none<?php echo $service_check['service_check_id']; ?>" > None </label>
		<?php 
		} 
		else if($service_check['check_type']==1) { 
		
        foreach($service_check_values as $sr_value)
			{
			 ?>
    	<input  type="checkbox" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="service_check<?php echo $sr_value['service_check_value_id']; ?>" value="<?php echo $sr_value['service_check_value_id']; ?>"  /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="service_check<?php echo $sr_value['service_check_value_id'];?>"><?php echo $sr_value['service_check_value']; ?></label>
	
    	<?php } ?>
        
        <?php } 
		if($service_check['check_type']==2) { 
		 foreach($service_check_values as $sr_value)
			{
			 ?>
    	<input  type="radio" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="service_check<?php echo $sr_value['service_check_value_id']; ?>" value="<?php echo $sr_value['service_check_value_id']; ?>"  /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="service_check<?php echo $sr_value['service_check_value_id'];?>"><?php echo $sr_value['service_check_value']; ?></label>
	
    	<?php } } ?>
    </td>
</tr>
<?php	
	
}
  ?>
  
<tr>
<td width="220px">Bay In<span class="requiredField">* </span> : </td>
				<td>
                <div id="datetimepicker2" class="input-append date">
<input type="text" name="bay_in" id="bay_in" class="datetimepicker1" data-format="dd/MM/yyyy hh:mm:ss" placeholder="click to select date!" value="<?php echo date('d/m/Y H:i:s',strtotime(getTodaysDateTime())); ?>"/>
 <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>
				
                            </td>
</tr>

<tr>
<td>Technician<span class="requiredField">* </span> : </td>
				<td>
					<select id="technician" name="technician_id" >
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listTechnicians();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['technician_id']; ?>"><?php echo $bank_cash_ledger['technician_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

  
					
</table>
<h4 class="headingAlignment">Spare Parts</h4>
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
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
            <tbody style="display:none" id="p0">
            	<tr>
                    <td><input name="item_id[]" type="text" class="inventory_item_autocomplete1" /></td>
                      <td><select  name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                        
                      <?php 
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>   
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDisc(this);" /> %</td>
                     <td><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        <!-- <option value="-1" >--Select Tax--</option> -->
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><input name="item_id[]" type="text" class="inventory_item_autocomplete" /></td>
                     <td><select  name="godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                       
                      <?php 
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
                    <td><input type="text" name="quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span></td>
                     <td><input type="text" name="rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0"  /></td>
                     <td><input type="text" name="disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDisc(this);" /> %</td>
                     <td><select class="tax_group" name="tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        <!-- <option value="-1" >--Select Tax--</option> -->
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>

<table>
<h4 class="headingAlignment">Spare parts under warranty</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="warProductPurchaseTable">
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
            <tbody style="display:none" id="pwar0">
            	<tr>
                    <td><input name="war_item_id[]" type="text" class="inventory_item_autocomplete1" /></td>
                      <td><select  name="war_godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                        
                      <?php 
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>   
                    <td><input type="text" name="war_quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span></td>
                     <td><input type="text" name="war_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                     <td><input type="text" name="war_disc[]" class="item_disc" style="width:25px;" value="100" readonly onchange="onchangeDisc(this);" /> %</td>
                     <td><select class="tax_group" name="war_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        <!-- <option value="-1" >--Select Tax--</option> -->
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addWarProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="pwar<?php echo $i; ?>">
            	<tr >
                    <td><input name="war_item_id[]" type="text" class="inventory_item_autocomplete" /></td>
                     <td><select  name="war_godown_id[]" style="width:150px;" onchange="getRateQuantityAndTaxForSalesFromGodwonId(this.value,this);">
                       
                      <?php 
									foreach($godowns as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> </td>    
                    <td><input type="text" name="war_quantity[]" class="item_quantity" style="width:35px;" value="1" onchange="onchangeQuantity(this);" /><span style="color:#f00;font-size:12px;"></span></td>
                     <td><input type="text" name="war_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRate(this);" /></td>
                     <td><input type="text" class="item_amount uneditable-input" style="width:75px;" disabled="disabled" value="0"  /></td>
                     <td><input type="text" name="war_disc[]" class="item_disc" style="width:25px;" value="100" readonly onchange="onchangeDisc(this);" /> %</td>
                     <td><select class="tax_group" name="war_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroup(this);">
                        <!-- <option value="-1" >--Select Tax--</option> -->
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addWarProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>

<table>
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
                     
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" /></td>
                     
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        <!-- <option value="-1" >--Select Tax--</option> -->
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="ns<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="ns_item_id[]" class="inventory_ns_item_autocomplete" /></td>
                    
                     <td><input type="text" name="ns_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" /></td>
                    
                     <td><input type="text" name="ns_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><select class="tax_group" name="ns_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        <!-- <option value="-1" >--Select Tax--</option> -->
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addNSProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>

</table>
<h4 class="headingAlignment">Out Side Job</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="outSideJobTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Rate</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th>Our Rate</th>
                 <th>Job Provider</th>
                 <th></th>
            </tr>
            <tbody style="display:none" id="oj0">
            	<tr>
                    <td><input type="text" name="oj_item_id[]" class="inventory_ns_item_autocomplete1" /></td>
                     
                     <td><input type="text" name="oj_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" /></td>
                    
                     <td><input type="text" name="oj_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><select class="tax_group" name="oj_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        <!-- <option value="-1" >--Select Tax--</option> -->
                      <?php 
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                       <td><input type="text" name="oj_our_rate[]" class="item_rate" style="width:35px;" value="0"  /></td>
                        <td><select class="tax_group" name="oj_provider_id[]" style="width:150px;" >
                        <option value="-1" >--Job Provider--</option>
                      <?php $models = listOutsideLabourProviders();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['legder_id'] ?>" ><?php echo $model['ledger_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                    
                            <td><input type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addOJProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" style="display:none;" value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php for($i=1;$i<6;$i++) { ?>
            <tbody id="oj<?php echo $i; ?>">
            	<tr >
                    <td><input type="text" name="oj_item_id[]" class="inventory_ns_item_autocomplete" /></td>
                     <td><input type="text" name="oj_rate[]" class="item_rate" style="width:35px;" value="0" onchange="onchangeRateNS(this);" /></td>
                     
                     <td><input type="text" name="oj_disc[]" class="item_disc" style="width:25px;" value="0" onchange="onchangeDiscNS(this);" /> %</td>
                     <td><select class="tax_group" name="oj_tax_group_id[]" style="width:150px;" onchange="onchangeTaxGroupNS(this);">
                        <!--  <option value="-1" >--Select Tax--</option> --> 
                      <?php
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                     <td><input type="text" class="item_net_amount uneditable-input" style="width:75px;" disabled="disabled" value="0" /></td>
                    
                     <td><input type="text" name="oj_our_rate[]" class="item_rate" style="width:35px;" value="0"  /></td>
                        <td><select class="tax_group" name="oj_provider_id[]" style="width:150px;" >
                        <option value="-1" >--Job Provider--</option>
                      <?php $models = listOutsideLabourProviders();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['ledger_id'] ?>" ><?php echo $model['ledger_name']; ?></option>
                                 <?php } ?>
                            </select> </td>
                            <td><input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addOJProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_ns_item.php')"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>

<table>

<!-- <tr>
<td width="220px">Bay Out : </td>
				<td>
                <div id="datetimepicker3" class="input-append date">
<input type="text" name="bay_out" id="bay_out" class="datetimepicker1" data-format="dd/MM/yyyy hh:mm:ss" placeholder="click to select date!" />
 <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>
				
                            </td>
</tr>

<tr>
<td width="220px">Actual Delivery : </td>
				<td>
                <div id="datetimepicker5" class="input-append date">
<input type="text" name="actual_delivery" id="actual_delivery" class="datetimepicker1" data-format="dd/MM/yyyy hh:mm:ss" placeholder="click to select date!" />
 <span class="add-on">
      <i data-time-icon="icon-time" data-date-icon="icon-calendar">
      </i>
    </span>
  </div>
				
                            </td>
</tr> -->


<tr>

<td width="240px;" class="firstColumnStyling">
Remarks / Any Advice To Customers: 
</td>

<td>
<textarea name="remarks_gen" id="remarks"></textarea>
</td>
</tr>




 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input name="submit" type="submit" value="Save"  class="btn btn-warning">
<input id="disableSubmit" type="submit" value="Add Job Card"  class="btn btn-warning">
<a href="<?php echo  WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
document.product_count=6;
  $( ".inventory_item_autocomplete" ).autocomplete({
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

function toggleServiceNoSelect(service_type_id)
{
	if(service_type_id==5 || service_type_id==2)
	{
	$('#srevice_no_tr').show();	
	document.getElementById('service_no').options[0].selected=true;	
	}
	else
	{
	$('#srevice_no_tr').hide();	
	document.getElementById('service_no').options[0].selected=true;	
	}
	
}
</script>
