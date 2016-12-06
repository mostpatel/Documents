<div class="jvp"><?php if(isset($_SESSION['cSalesItemReport']['agency_id']) && $_SESSION['cSalesItemReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cSalesItemReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Sales Reports Service Wise</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">

<tr>
<td>Service<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   	<input type="text" name="item_id[]" class="inventory_item_autocomplete" value="<?php if(isset($_SESSION['cSalesItemReport']['item_id'])) echo $_SESSION['cSalesItemReport']['item_id'][0];  ?>" />
                            </td>
</tr>

<tr>
<td> From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSalesItemReport']['from'])) echo $_SESSION['cSalesItemReport']['from']; ?>"/>	
                 </td>
</tr>

<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSalesItemReport']['to'])) echo $_SESSION['cSalesItemReport']['to']; ?>"/>	
                 </td>
</tr>



<?php $technicians = listTechnicians();

 ?>

<tr>
<td> Technician : </td>
				<td>
				 <select name="technician_id" id="technician_id" >	
                 	<?php foreach($technicians as $technician) { ?>
                    	<option value="<?php echo $technician['technician_id']; ?>" <?php if(is_numeric($_SESSION['cSalesItemReport']['technician_id'])) { if($technician['technician_id']==$_SESSION['cSalesItemReport']['technician_id']){ ?> selected="selected" <?php } } ?>>
                        	<?php echo $technician['technician_name'];  ?>
                        </option>
                    <?php } ?>
                 </select>
                 </td>
</tr>

<tr>
<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>

</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['cSalesItemReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cSalesItemReport']['emi_array'];
	
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
 <div id="deleteSelectedDiv"><button id="deleteSelected" class="btn viewBtn">delete selected rows</button></div>      
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Sales Account</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Customer</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Amount</label> 
     <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Remarks</label> 
       
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
         <th class="heading no_print"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
       
        	<th class="heading">No</th>
            <th class="heading date">Date</th>
            <th class="heading">Reg No</th>
             <?php if(TAX_MODE==1) { ?>
             <th class="heading">Service</th>
             <?php } ?>
            <th class="heading">Customer</th>
            
             <th class="heading numeric">Net Amount</th>
             <th class="heading numeric">Job Card No</th>
              <th class="heading numeric">Invoice No</th>
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
        <?php
	
		$total=0;
		$total_emi_amount=0;
		$customer_id_array = array();
		foreach($emi_array as $emi)
		{
			$total = $total + $emi['net_amount'];
			if(TAX_MODE==1)
				$non_stock_items = getNonStockItemForSaleId($emi['sales_id']);
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><div style="page-break-inside:avoid;"><?php echo ++$i; ?></div></td>
           
             
            <td><?php   echo date('d/m/Y',strtotime($emi['trans_date'])); ?></td>
             <td><?php $vehicle = getVehicleById($emi['vehicle_id']); echo $vehicle['vehicle_reg_no'];  ?>
            </td>
            <?php if(TAX_MODE==1) { ?>
            <td><?php 	for($j=0; $j<count($non_stock_items); $j++)
			{
	
			$inventory_item = $non_stock_items[$j]['sales_item_details'];	
			echo getItemNameFromItemId($inventory_item['item_id'])." X ".round(($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)),2)."Rs <br>";
			}
			?></td>
            <?php } ?>
            <td><?php  if(is_numeric($emi['to_ledger_id'])) echo getLedgerNameFromLedgerId($emi['to_ledger_id']); else echo $emi['customer_name']; ?>
            </td>
           
            <td><?php echo $emi['net_amount']; ?></td>
             <td><?php echo $emi['job_card_no'] ?></td>
            <td><?php echo $emi['invoice_no'] ?></td>
             <td><?php echo $emi['remarks'] ?></td>
             
          
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$emi['sales_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
   
        </tr>
     
         <?php } }?>
         
         
            </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cSalesItemReport']['from']) && $_SESSION['cSalesItemReport']['from']!="") echo $_SESSION['cSalesItemReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cSalesItemReport']['to']) && $_SESSION['cSalesItemReport']['to']!="") echo $_SESSION['cSalesItemReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
    <span class="Total">Total Amount : <span id="total_amount"><?php echo $total; ?></span></span>
<?php  ?>      
</div>
<div class="clearfix"></div>

<script>

  $( ".inventory_item_autocomplete" ).autocomplete({
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
			 }
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
</script>