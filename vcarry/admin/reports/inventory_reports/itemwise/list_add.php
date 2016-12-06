<div class="jvp"><?php if(isset($_SESSION['cSalesItemReport']['agency_id']) && $_SESSION['cSalesItemReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cSalesItemReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Sales Reports Item Wise</h4>
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
<td>Item<span class="requiredField">* </span> : </td>
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
            <th class="heading numeric">Qty</th>
            <th class="heading numeric">Rate</th>
             <th class="heading numeric">Net Amount</th>
            <th class="heading">Sales Account</th>
            <th class="heading">Customer</th>
             
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
			$total = $total + $emi['amount'];
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><div style="page-break-inside:avoid;"><?php echo ++$i; ?></div></td>
           
             
            <td><?php   echo date('d/m/Y',strtotime($emi['trans_date'])); ?></td>
            <td><?php echo $emi['quantity']; ?></td>
              <td><?php echo $emi['rate']; ?></td>
            <td><?php echo $emi['net_amount']; ?></td>
             
             <td><?php  echo getLedgerNameFromLedgerId($emi['from_ledger_id']);  ?>
            </td>
            <td><?php  if(is_numeric($emi['to_ledger_id'])) echo getLedgerNameFromLedgerId($emi['to_ledger_id']); else echo $emi['customer_name']; ?>
            </td>
             
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
    <span class="Total">Total Amount : <span id="total_amount">0</span></span>
<?php  ?>      
</div>
<div class="clearfix"></div>

<script>

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
</script>