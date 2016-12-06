<div class="jvp"><?php if(isset($_SESSION['cStockTransactionReport']['agency_id']) && $_SESSION['cStockTransactionReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cStockTransactionReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Stock Transaction Reports</h4>
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
<td>Product / Item</td>
<td><input name="item_name" type="text" class="inventory_item_autocomplete" value="<?php if(isset($_SESSION['cStockTransactionReport']['item_name'])) echo $_SESSION['cStockTransactionReport']['item_name']; ?>" /></td>
</tr>

<tr>
<td>From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker1" value="<?php if(isset($_SESSION['cStockTransactionReport']['from'])) echo $_SESSION['cStockTransactionReport']['from']; ?>"/>	
                 </td>
</tr>


<tr>
<td>To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cStockTransactionReport']['to'])) echo $_SESSION['cStockTransactionReport']['to']; ?>"/>	
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
 

	
 <?php if(isset($_SESSION['cStockTransactionReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cStockTransactionReport']['emi_array'];
		
	$from_ledger = $_SESSION['cStockTransactionReport']['ledger_id'];
	
	
		
	 ?>   
     <div class="no_print"> 
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
   
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Item Name</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Quantity</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">closing Rate</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Closing Balance</label> 
   
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
         <th class="heading no_print"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
        	 <th class="heading">No</th>
             <th class="heading date">Date</th>
             <th class="heading">Item Name</th>
             <th class="heading">Quantity</th>
             <th class="heading">Rate</th>
             <th class="heading">Amount</th>
             <th class="heading">Type</th>
             <th class="heading no_print"></th>
        </tr>
    </thead>
    <tbody>
      
       <?php
	$total =0;
		$tax =0;
		
		foreach($emi_array as $job_card)
		{
				
				
		 ?>
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['trans_date'])); ?>
            </td>
            <td><?php echo $_SESSION['cStockTransactionReport']['item_name']; ?>
            </td> 
            <td><?php if($job_card['quantity']<0) echo -$job_card['quantity']." CR"; else echo $job_card['quantity']." DR"; ?>
            </td>
             <td><?php echo $job_card['rate']; ?>
            </td>
             <td><?php  if($job_card['net_amount']<0) echo -$job_card['net_amount']; else echo $job_card['net_amount'] ?>
            </td>
             <td><?php switch($job_card['type']) {
				 			case 1 : echo "Purchase";
									break;
							case 2 : echo "Sales";
									break;	
							case 3 : echo "Credit Note";
									break;	
							case 4 : echo "Delivery Challan";
									break;	
							case 5 : echo "Inwards JV";
									break;
							case 6 : echo "Debit Note";
									break;										
				 			case 7 : echo "Opening Transaction";
									break;
							case 8 : echo "Outwards JV";
									break;				
				 } ?>
            </td>
             <td class="no_print"> <a href="<?php
			 switch($job_card['type']) {
				 			case 1 : echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$job_card['trans_id'];
									break;
							case 2 : echo  WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$job_card['trans_id'];
									break;	
							case 3 : echo WEB_ROOT.'admin/accounts/transactions/credit_note/index.php?view=details&id='.$job_card['trans_id'];
									break;	
							case 4 : echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=details&id='.$job_card['trans_id'];
									break;	
							case 5 : echo WEB_ROOT.'admin/accounts/transactions/inventory_jv/index.php?view=details&id='.$job_card['trans_id'];
									break;
							case 6 : echo WEB_ROOT.'admin/accounts/transactions/debit_note/index.php?view=details&id='.$job_card['trans_id'];
									break;										
				 			case 7 : echo "Opening Transaction";
									break;
							case 8 : echo WEB_ROOT.'admin/accounts/transactions/inventory_jv/index.php?view=details&id='.$job_card['trans_id'];
									break;				
				 } 
			   ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
         
           
           
            
            
        </tr>
        <?php  } ?>
         
            </tbody>
    </table>
   
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cStockTransactionReport']['from']) && $_SESSION['cStockTransactionReport']['from']!="") echo $_SESSION['cStockTransactionReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cStockTransactionReport']['to']) && $_SESSION['cStockTransactionReport']['to']!="") echo $_SESSION['cStockTransactionReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  <span class="Total">Amount : <?php echo $total; ?></span>
   
<?php  } ?>      
</div>
<div class="clearfix"></div>
<script type="text/javascript">
 $( ".inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
		var trans_date = request.term + " | 1 | 2";
	
                $.getJSON ('<?php echo WEB_ROOT; ?>json/inventory_item.php',
                { term: trans_date }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
        }
 });
</script>