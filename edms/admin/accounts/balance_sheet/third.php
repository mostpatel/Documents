<div class="insideCoreContent adminContentWrapper wrapper">
<?php
if(!isset($_GET['type']))
{
	    $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$period = getPeriodForUser($admin_id);
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));
	    $id=$_GET['id'];
		if(validateForNull($id)&& substr($id, 0, 1) == 'C') // if the pament is done to a general account ledger
		{
			$main_customer_id=str_replace('C','',$id);
			if(is_numeric($main_customer_id))
						  { 
						  $main_customer=getCustomerDetailsByCustomerId($main_customer_id);
						  } 
			}
		else if(validateForNull($id)&& substr($id, 0, 1)=='L')
		{
			$main_ledger_id=str_replace('L','',$id);
			if(is_numeric($main_ledger_id))
			$main_ledger_name=getLedgerNameFromLedgerId($main_ledger_id);
			}		
		
$reportArray=getAllTransactionsForLedgerIdMonthWise($id,$transaction_array,$from,$to);


 ?>
<h4 class="headingAlignment ">Transactions For <?php if(checkForNumeric($main_customer_id)) echo $main_customer['customer_name']." ".$main_file_no." ".$main_reg_no; else if(checkForNumeric($main_ledger_id)) echo $main_ledger_name; echo ' ['.$from.'-'.$to."]";  ?></h4>
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
<hr class="firstTableFinishing" />
<div class="no_print">
 <?php if(isset($reportArray))
{
	
	$transaction_array=$reportArray;
	if(substr($id, 0, 1) === 'L')
		{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$head_type=getLedgerHeadType($ledger_id);
		}
		else if(substr($id, 0, 1) === 'C')
		{
		$head_type=1;
		}	
	
		
	if($head_type==0)
	{
	$ledger_id=str_replace('L','',$id);
	$ledger_id=intval($ledger_id);
	$ledger_type=0;
	}
	else if($head_type==1)
	{
		if(substr($id, 0, 1) === 'L')
		{
			$ledger_id=str_replace('L','',$id);
			$ledger_id=intval($ledger_id);
			$ledger_type=0;
		}
		else if(substr($id, 0, 1) === 'C')
		{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);	
		$ledger_type=1;
		}	
	}
	$openingBalance=getOpeningBalanceForLedgerForDate($id,$from);
?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Month</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Debit</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Credit</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Closing Balance</label> 
    </div>
    <table id="accountContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading ">No</th>
        <th class="heading date default_sort">Month</th>
        <th class="heading no_sort">Debit</th>
        <th class="heading no_sort">Credit</th>
        <th class="heading no_sort">Closing Balance</th>
        <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
      	  <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php $i=0; echo ++$i; ?></td>
            <td><?php echo "Opening Balance";  ?></td>
            <td><?php if($openingBalance>=0) echo number_format($openingBalance,2); ?></td>
            <td><?php if($openingBalance<0) echo number_format(-$openingBalance,2);  ?></td>
            <td><?php if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr"; ?></td>
           <td class="no_print"></td>
            </tr>
        <?php
		if($transaction_array!="error" && is_array($transaction_array))
		{
		foreach($transaction_array as $key => $value)
		{
			
			$debit_amount=$value[0];
			$credit_amount=$value[1];
			$payment=$value[2];
			$month_id=$value[3];
			$year=$value[4];
			
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
             <td class="date"> <a href="<?php echo 'index.php?view=fourth&id='.$id.'&month='.$month_id.'&year='.$year.'&from='.$from.'&to='.$to ?>"> <?php  echo $key; ?></a>
            </td>
             <td><?php if($debit_amount>0) echo number_format($debit_amount,2); ?>
            </td>
            <td><?php if($credit_amount>0) echo number_format($credit_amount,2); ?>
            </td>
           <td><?php
		   
		     $openingBalance=$openingBalance+$payment;  if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
            </td>
            <td class="no_print"> <a href="<?php echo 'index.php?view=fourth&id='.$id.'&month='.$month_id.'&year='.$year.'&from='.$from.'&to='.$to ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }}}
		 ?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php } else {
	    $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$period = getPeriodForUser($admin_id);
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));
	    $id=$_GET['id'];
		$reportArray=getTransactionForItemIDForPeriod($id,$from,$to);
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
         <th class="heading no_print"></th>
        	 <th class="heading">No</th>
             <th class="heading date">Date</th>
             <th class="heading">Item Name</th>
             <th class="heading">Debit Qty</th>
             <th class="heading">Credit Qty</th>
             <th class="heading">Rate</th>
             <th class="heading">Dr</th>
             <th class="heading">Cr</th>
             <th class="heading">Type</th>
             <th class="heading no_print"></th>
        </tr>
    </thead>
    <tbody>
      
       <?php
	$total =0;
		$tax =0;
		
		foreach($reportArray as $job_card)
		{
				
				
		 ?>
          <tr class="resultRow">
          <td class="no_print"></td>
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['trans_date'])); ?>
            </td>
            <td><?php echo $_SESSION['cStockTransactionReport']['item_name']; ?>
            </td> 
            <td><?php if($job_card['quantity']>0) echo $job_card['quantity']; ?>
            </td>
             <td><?php if($job_card['quantity']<0) echo -$job_card['quantity'];  ?>
            </td>
             <td><?php echo $job_card['rate']; ?>
            </td>
             <td><?php  if($job_card['net_amount']>=0) echo $job_card['net_amount']; ?>
            </td>
             <td><?php  if($job_card['net_amount']<0) echo -$job_card['net_amount'];  ?>
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
      <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  <?php } ?>        
</div>
<div class="clearfix"></div>
