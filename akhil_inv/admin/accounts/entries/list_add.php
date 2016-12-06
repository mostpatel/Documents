<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
$cash_ledger_id=getCashLedgerIdForOC($oc_id);
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Transaction For Ledger</h4>
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

<tr >
<td width="260px;">From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['ledgerEntriess']['from'])) echo $_SESSION['ledgerEntriess']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['ledgerEntriess']['to'])) echo $_SESSION['ledgerEntriess']['to']; ?>"/>	
                 </td>
</tr>



<tr>
<td>Ledger<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="to_ledger" name="ledger_id" >
                    	<option value="" selected="selected"></option>
                    <?php
					$ledgers=listCustomerAndLedgersWithBankCash();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="<?php echo $ledger['id']; ?>" <?php if( $ledger['id']==$_SESSION['ledgerEntriess']['ledger_id']) { ?>  selected="selected"<?php } ?>><?php echo $ledger['name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Transactions : </td>
				<td>
					<select name="transaction_type[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="1" <?php if(in_array(1,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?> >Payments</option>
                         <option value="2" <?php if(in_array(2,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Receipts</option>
                         <option value="3" <?php if(in_array(3,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Journal Entries</option>
                         <option value="4" <?php if(in_array(4,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Contra Entries</option>
                         <option value="5" <?php if(in_array(5,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Purchase Entries</option>
                         <option value="6" <?php if(in_array(6,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Sales Entries</option>
                         <option value="7" <?php if(in_array(7,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Debit Note Entries</option>
                         <option value="8" <?php if(in_array(8,$_SESSION['ledgerEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Credit Note Entries</option>
                    </select>
                            </td>
</tr>

<tr>

<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>
                <a href="<?php echo WEB_ROOT."admin/accounts/" ?>"><input type="button" class="btn btn-success" value="Back"/></a>	
                </td>
</tr>


</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['ledgerEntriess']['entries_array']))
{
	$id=$_SESSION['ledgerEntriess']['ledger_id'];
	$transaction_array=$_SESSION['ledgerEntriess']['entries_array'];
	
	$l_id=str_replace('L','',$id);
	
	$head_type=getLedgerHeadType($l_id);
	
	if(isset($head_type) && $head_type==2)
	$transaction_array = $transaction_array[0];
	if(!$head_type && !is_numeric($head_type))
	$head_type=1;

	if(isset($_SESSION['ledgerEntriess']['from']) && validateForNull($_SESSION['ledgerEntriess']['from']))
	{	
	$from=$_SESSION['ledgerEntriess']['from'];
	}
	else
	{
	$from=getBooksStartingDateForLedgerCustomer($id);
	}
	
	
	
	if($head_type==0)
	{
	
	$ledger_id=str_replace('L','',$id);
	$ledger_id=intval($ledger_id);
	$ledger_type=0;
	}
	else
	{
	
		if(substr($id, 0, 1) == 'L')
		{
			$ledger_id=str_replace('L','',$id);
			$ledger_id=intval($ledger_id);
			$ledger_type=0;
		}
		else if(substr($id, 0, 1) == 'C')
		{
		$ledger_id=str_replace('C','',$id);
		$ledger_id=intval($ledger_id);	
		$main_customer_id=$ledger_id;
		$ledger_type=1;
			if(is_numeric($main_customer_id))
						  { 
						 	$main_customer = getCustomerDetailsByCustomerId($main_customer_id);
						  } 
		
		}	
	}

	$openingBalance=getOpeningBalanceForLedgerForDate($id,$from);
	
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Particulars</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Mode</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Type</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Amount</label> 
    </div>
    <table id="accountContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading no_sort">No</th>
        <th class="heading date">Date</th>
        <th class="heading no_sort">Particulars</th>
        <th class="heading no_sort">For</th>
        <th class="heading no_sort">Type</th>
        <th class="heading no_sort">Debit</th>
        <th class="heading no_sort">Credit</th>
        <th class="heading no_sort">Remarks</th>
        <th class="heading no_sort">Closing Balance</th>
        <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
      	  <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $from;  ?></td>
            <td><?php echo "Opening Balance";  ?></td>
            <td><?php  ?></td>
             <td></td>
            <td><?php if($openingBalance>=0) echo number_format($openingBalance,2); ?></td>
            <td><?php if($openingBalance<0) echo number_format(-$openingBalance,2);  ?></td>
            <td></td>
            <td><?php if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr"; ?></td>
           <td></td>
            </tr>
            
        <?php
		foreach($transaction_array as $payment)
		{
			
			if($payment['type']==1)
			{
				$from_ledger_id=$payment['debit_ledger_id'];
				$from_customer_id=$payment['debit_customer_id'];
				
				$extra_payment_details = getPaymentDetailsForPaymentId($payment['id']);
				
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
					 
						if($head_type==0)
						  {
							 
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  
						  $customer = getCustomerDetailsByCustomerId($from_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($from_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['credit_ledger_id']); ?>
					</td>
					 <td><?php switch($payment['auto_rasid_type']){
							 case 0: echo "Normal";
							 		break;
							 case 2: echo "Financer";
							 		break;	
							case 4: echo "Vehicle";
							 		break;	
							case 5: echo "Purchase";
							 		break;				
							case $payment['auto_rasid_type']>100: echo getReceiptTypeNameById($payment['auto_rasid_type']);
							 		break;	
															
							 } ?>
						</td>
					 <td width="160px"><?php echo "Payment"; ?>
					</td>
					 <td><?php if($head_type==1) echo number_format($payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==0) echo number_format($payment['amount'],2); ?>
					</td>
                    <td><span style="text-transform:uppercase;"><?php if($extra_payment_details) { ?>	<?php  if($extra_payment_details) echo "Bank : ".$extra_payment_details['bank_name']."<br>"; ?><?php  if($extra_payment_details) echo "Chq No : ".$extra_payment_details['chq_no']; ?><?php } ?><span><br><?php echo $payment['remarks']; ?></td>
				   <td><?php  if($head_type==1) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==0) $openingBalance=$openingBalance-round($payment['amount'],2);  if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if(($payment['auto_rasid_type']==0 || $payment['auto_rasid_type']>100) && is_numeric($from_customer_id)) { echo WEB_ROOT.'admin/customer/payment_for_customer/index.php?view=details&id='.$payment['id']; }
					 else if($payment['auto_rasid_type']==0 && is_numeric($from_ledger_id)) 
					 echo WEB_ROOT.'admin/accounts/transactions/payment/index.php?view=details&id='.$payment['id']; 
					 else if($payment['auto_rasid_type']==2) 
					 echo WEB_ROOT.'admin/financer/index.php?view=list&id='.$from_ledger_id;
 					 else if($payment['auto_rasid_type']==4) 
					 echo WEB_ROOT.'admin/customer/vehicle_payment/index.php?view=details&id='.$payment['id'];
					 else echo WEB_ROOT.'admin/accounts/transactions/payment/index.php?view=details&id='.$payment['id']; 				 ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		else if($payment['type']==2)
				{	
					$to_ledger_id=$payment['credit_ledger_id'];
					$to_customer_id=$payment['credit_customer_id'];
					$extra_payment_details = getReceiptDetailsForReceiptId($payment['id']);
					 ?>
					 <tr class="resultRow">
						<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==0)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;
						  }
						  } 
						  else echo getLedgerNameFromLedgerId($payment['debit_ledger_id']); ?>
						</td>
						 <td><?php switch($payment['auto_rasid_type']){
							 case 0: echo "Normal";
							 		break;
							 case 2: echo "Financer";
							 		break;	
							case 3: echo "Job Card";
							 		break;	
							case 4: echo "Vehicle";
							 		break;	
							case 5: echo "Sales";
							 		break;		
							case $payment['auto_rasid_type']>100: echo getReceiptTypeNameById($payment['auto_rasid_type']);
							 		break;								
							 } ?>
						</td>
						 <td width="160px"><?php echo "Receipt"; ?>
						</td>
						<td><?php if($head_type==0) echo number_format($payment['amount'],2); ?>
						</td>
						<td><?php if($head_type==1) echo number_format($payment['amount'],2); ?>
						</td>
                         <td><span style="text-transform:uppercase;"><?php if($extra_payment_details) { ?>	<?php  if($extra_payment_details) echo "Bank : ".$extra_payment_details['bank_name']."<br>"; ?><?php  if($extra_payment_details) echo "Chq No : ".$extra_payment_details['chq_no']; ?><?php } ?><span><br><?php echo $payment['remarks']; ?></td>
					   <td><?php if($head_type==0) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==1) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php
						   
						 if(($payment['auto_rasid_type']==0 || $payment['auto_rasid_type']>100) && is_numeric($from_customer_id)) { echo WEB_ROOT.'admin/customer/receipt/index.php?view=details&id='.$payment['id']; }else if(($payment['auto_rasid_type']==0 || $payment['auto_rasid_type']>100)) { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['id']; } else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/financer_receipt/index.php?view=details&id='.$payment['id'].'&state='.$emi_id; else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/customer/vehicle_receipt/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==5) echo WEB_ROOT."admin/accounts/transactions/receipt/index.php?view=details&id=".$payment['id']; else  { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['id']; } ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
         <?php } 
		else if($payment['type']==3)
				{	
		
		$to_ledger_id=$payment['debit_ledger_id'];
		$to_customer_id=$payment['debit_customer_id'];
		$from_ledger_id=$payment['credit_ledger_id'];
		$from_customer_id=$payment['credit_customer_id'];
		
		
		
		if($ledger_type==0 && 'L'.$to_ledger_id==$id)
		$jv_type=0; // debit jv
		else if($ledger_type==0 && 'L'.$from_ledger_id==$id)
		$jv_type=1;  //credit jv
		else if($ledger_type==1 && 'C'.$to_customer_id==$id)
		$jv_type=0;
		else if($ledger_type==1 && 'C'.$from_customer_id==$id)
		$jv_type=1;
		
		
		
		
		?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            
             <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
            </td>
            
            <td><?php if($jv_type==0) // if it is a accounts ledger and a debit jv
			 		{
						$others_ledger_id = $payment['other_ledger_id'];
						if($others_ledger_id[0]=="L")
						$from_ledger_id=str_replace("L","",$others_ledger_id);
						else
						$from_customer_id=str_replace("C","",$others_ledger_id);
						 if(is_numeric($from_ledger_id))  echo getLedgerNameFromLedgerId($from_ledger_id);
					  else if(is_numeric($from_customer_id)){  $customer = getCustomerDetailsByCustomerId($from_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($from_customer_id);
						   echo $customer['customer_name']." "; if($reg_no) echo $reg_no; }
					}
					else if($jv_type==1) // if it is a accounts ledger and a credit jv
			 		{ 
					$others_ledger_id = $payment['other_ledger_id'];
						if($others_ledger_id[0]=="L")
						$to_ledger_id=str_replace("L","",$others_ledger_id);
						else
						$to_customer_id=str_replace("C","",$others_ledger_id);
					if(is_numeric($to_ledger_id))  echo getLedgerNameFromLedgerId($to_ledger_id);
					  else if(is_numeric($to_customer_id)) {  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no; }
					}
					 ?>
            </td>
             <td><?php switch($payment['auto_rasid_type']){
							 case 0: echo "Normal";
							 		break;
							 case 1: echo "Financer";
							 		break;		
							 case 2: echo "Purchase JV";
							 		break;	
							case 3: echo "Outside Labour";
							 		break;	
							case 4: echo "Sales JV";
							 		break;	
							case 5: echo "Loan JV";
							 		break;
							case 6: echo "Payment For Customer";
							 		break;
							case 7: echo "Kasar JV";
							 		break;		
													
													
							 } ?>
						</td>
             <td width="160px"><?php echo "Journal" ?>
            </td>
             <td><?php if($jv_type==0) echo number_format($payment['amount'],2);  ?>
            </td>
             <td><?php if($jv_type==1) echo number_format($payment['amount'],2);  ?>
            </td>
             <td><?php echo $payment['remarks']; ?></td>
             <td><?php if($jv_type==0) $openingBalance=$openingBalance+round($payment['amount'],2); else if($jv_type==1) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
            </td>
           
             <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/accounts/transactions/jv/index.php?view=details&id=".$payment['id'];  ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
           
            
          
  
        </tr>
         <?php } 
		else if($payment['type']==4)
				{
					
		$to_ledger_id=$payment['debit_ledger_id'];
		$from_ledger_id=$payment['credit_ledger_id'];
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            
             <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
            </td>
              <td><?php if($head_type==0){
				  		if(is_numeric($to_ledger_id))  echo getLedgerNameFromLedgerId($to_ledger_id);
					  	else  echo getLedgerNameFromLedgerId($payment['from_ledger_id']); } ?>
            </td>
             <td><?php if($payment['auto_rasid_type']==0) echo "Manual"; else echo "Auto"; ?>
            </td>
             <td width="160px"><?php echo "Contra"; ?>
            </td>
             <td><?php if($to_ledger_id==$ledger_id) echo number_format($payment['amount'],2); ?>
            </td>
            <td><?php if($from_ledger_id==$ledger_id) echo number_format($payment['amount'],2); ?>
            </td>
             <td><?php echo $payment['remarks']; ?></td>
           <td><?php if($to_ledger_id==$ledger_id) $openingBalance=$openingBalance+round($payment['amount'],2);  if($from_ledger_id==$ledger_id) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
            </td>
           
           <td class="no_print"> <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/contra/index.php?view=details&id='.$payment['id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
          
  
        </tr>
         <?php 
		 ?>
         <?php
		 }
		 else if($payment['type']==5)
			{
				$from_ledger_id=$payment['credit_ledger_id'];
				$from_customer_id=$payment['credit_customer_id'];
				
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
						if($head_type==3)
						  {
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($from_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($from_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['debit_ledger_id']); ?>
					</td>
					<td><?php switch($payment['auto_rasid_type']){
							 case 0: echo "Normal";
							 		break;
							 case 1: echo "Vehicle";
							 		break;		
							 case 2: echo "Item";
							 		break;	
							case 3: echo "Job Card";
							 		break;	
							case 4: echo "Sales JV";
							 		break;	
							case 5: echo "Loan JV";
							 		break;
							case 6: echo "Payment For Customer";
							 		break;
							case 7: echo "Kasar JV";
							 		break;		
													
													
							 } ?>
						</td>
					 <td width="160px"><?php echo "Purchase"; ?>
					</td>
					 <td><?php if($head_type==3) echo number_format($payment['amount'],2); ?>
					</td>
					<td><?php if($head_type!=3) echo number_format($payment['amount'],2); ?>
					</td>
                     <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==3) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type!=3) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$payment['id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/purchase/vehicle/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$payment['id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		  else if(isset($payment['purchase_tax_id']) && is_numeric($payment['purchase_tax_id']))
			{
				$from_ledger_id=$payment['from_ledger_id'];
				$from_customer_id=$payment['from_customer_id'];
				
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
						if($head_type==3 || $head_type==2)
						  {
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($from_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($from_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['debit_ledger_id']); ?>
					</td>
					<td><?php switch($payment['auto_rasid_type']){
							 case 0: echo "Normal";
							 		break;
							 case 1: echo "Vehicle";
							 		break;		
							 case 2: echo "Item";
							 		break;	
							case 3: echo "Job Card";
							 		break;	
							case 4: echo "Sales JV";
							 		break;	
							case 5: echo "Loan JV";
							 		break;
							case 6: echo "Payment For Customer";
							 		break;
							case 7: echo "Kasar JV";
							 		break;		
													
													
							 } ?>
						</td>
					 <td width="160px"><?php echo "Tax (Purchase)"; ?>
					</td>
					 <td><?php if($head_type==3) echo number_format($payment['amount'],2); ?>
					</td>
					<td><?php if($head_type!=3) echo number_format($payment['amount'],2); ?>
					</td>
                     <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==3) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type!=3) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$payment['id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/purchase/vehicle/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$payment['id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		 else if($payment['type']==6) // sales
				{	
					$to_ledger_id=$payment['debit_ledger_id'];
					$to_customer_id=$payment['debit_customer_id'];
					
					 ?>
					 <tr class="resultRow">
						<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==4)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['credit_ledger_id']); ?>
						</td>
						<td><?php switch($payment['auto_rasid_type']){
							 case 0: echo "Normal";
							 		break;
							 case 1: echo "Vehicle";
							 		break;		
							 case 2: echo "Item";
							 		break;	
							case 3: echo "Job Card";
							 		break;	
							case 4: echo "Sales JV";
							 		break;	
							case 5: echo "Loan JV";
							 		break;
							case 6: echo "Payment For Customer";
							 		break;
							case 7: echo "Kasar JV";
							 		break;		
													
													
							 } ?>
						</td>
						 <td width="160px"><?php echo "Sales"; ?>
						</td>
						<td><?php if($head_type!=4) echo number_format($payment['amount'],2); ?>
						</td>
						<td><?php if($head_type==4) echo number_format($payment['amount'],2); ?>
						</td>
                         <td><?php echo $payment['remarks']; ?></td>
					   <td><?php if($head_type!=4) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==4) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==2 && is_numeric($to_ledger_id) && $to_ledger_id==$cash_ledger_id) { 
						
						echo WEB_ROOT.'admin/accounts/transactions/cash_sale/index.php?view=details&id='.$payment['id']; } 
					else if($payment['auto_rasid_type']==0){
						 echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$payment['id'];
						}	else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$payment['id'];else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$payment['auto_id']  ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
         <?php }
		else if(isset($payment['sales_tax_id']) && is_numeric($payment['sales_tax_id']))
			{
					$to_ledger_id=$payment['to_ledger_id'];
					$to_customer_id=$payment['to_customer_id'];
					
					 ?>
					 <tr class="resultRow">
						<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==4 || $head_type==2)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['credit_ledger_id']); ?>
						</td>
						<td><?php switch($payment['auto_rasid_type']){
							 case 0: echo "Normal";
							 		break;
							 case 1: echo "Vehicle";
							 		break;		
							 case 2: echo "Item";
							 		break;	
							case 3: echo "Job Card";
							 		break;	
							case 4: echo "Sales JV";
							 		break;	
							case 5: echo "Loan JV";
							 		break;
							case 6: echo "Payment For Customer";
							 		break;
							case 7: echo "Kasar JV";
							 		break;		
													
													
							 } ?>
						</td>
						 <td width="160px"><?php echo "Tax (Sales)"; ?>
						</td>
						<td><?php if($head_type!=4) echo number_format($payment['amount'],2); ?>
						</td>
						<td><?php if($head_type==4) echo number_format($payment['amount'],2); ?>
						</td>
                         <td><?php echo $payment['remarks']; ?></td>
					   <td><?php if($head_type!=4) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==4) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance)." Dr"; else echo number_format(-$openingBalance)." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==2 && is_numeric($to_ledger_id) && $to_ledger_id==$cash_ledger_id) { 
						
						echo WEB_ROOT.'admin/accounts/transactions/cash_sale/index.php?view=details&id='.$payment['id']; } 
					else if($payment['auto_rasid_type']==0){
						 echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$payment['id'];
						}	else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$payment['id'];else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$payment['auto_id']  ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
         <?php }
		  else if($payment['type']==8)
			{
				$to_ledger_id=$payment['credit_ledger_id'];
				$to_customer_id=$payment['credit_customer_id'];
				
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date']));  ?>
					</td>
					
					  <td><?php 
						if($head_type==3)
						  {
						  if(is_numeric($to_customer_id))  
						  echo getLedgerNameFromLedgerId($to_customer_id);
						  else if(is_numeric($to_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['debit_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==0) echo "Manual"; else echo "Auto"; ?>
					</td>
					 <td width="160px"><?php echo "Credit Note"; ?>
					</td>
					 <td><?php if($head_type==4) echo number_format($payment['amount'],2); ?>
					</td>
					<td><?php if($head_type!=4) echo number_format($payment['amount'],2); ?>
					</td>
                     <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==4) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type!=4) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance)." Dr"; else echo number_format(-$openingBalance)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/credit_note/index.php?view=details&id='.$payment['id']; } else  echo WEB_ROOT.'admin/accounts/transactions/credit_note/index.php?view=details&id='.$payment['id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		 else if(isset($payment['credit_note_tax_id']) && is_numeric($payment['credit_note_tax_id']))
			{
				
				$to_ledger_id=$payment['to_ledger_id'];
				$to_customer_id=$payment['to_customer_id'];
				
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date']));  ?>
					</td>
					
					  <td><?php 
						if($head_type==3 || $head_type==2)
						  {
							  
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['debit_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==0) echo "Manual"; else echo "Auto"; ?>
					</td>
					 <td width="160px"><?php echo "Tax (Credit Note)"; ?>
					</td>
					 <td><?php if($head_type==4) echo number_format($payment['amount'],2); ?>
					</td>
					<td><?php if($head_type!=4) echo number_format($payment['amount'],2); ?>
					</td>
                     <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==4) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type!=4) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/credit_note/index.php?view=details&id='.$payment['id']; } else  echo WEB_ROOT.'admin/accounts/transactions/credit_note/index.php?view=details&id='.$payment['id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		 else if($payment['type']==7) // Debit_note
				{	
					$to_ledger_id=$payment['debit_ledger_id'];
					$to_customer_id=$payment['debit_customer_id'];
					
					 ?>
					 <tr class="resultRow">
						<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==4)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['credit_ledger_id']); ?>
						</td>
						 <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
						</td>
						 <td width="160px"><?php echo "Debit Note"; ?>
						</td>
						<td><?php if($head_type!=3) echo number_format($payment['amount'],2); ?>
						</td>
						<td><?php if($head_type==3) echo number_format($payment['amount'],2); ?>
						</td>
                         <td><?php echo $payment['remarks']; ?></td>
					   <td><?php if($head_type!=3) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==3) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php
						   
						 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/accounts/transactions/debit_note/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/payment/penalty/index.php?view=details&id='.$file_id.'&state='.$payment['auto_id']; else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id; else { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
         <?php }
		  else if(isset($payment['debit_note_tax_id']) && is_numeric($payment['debit_note_tax_id']))
			{
					$to_ledger_id=$payment['from_ledger_id'];
					$to_customer_id=$payment['from_customer_id'];
					
					 ?>
					 <tr class="resultRow">
						<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==4 || $head_type==2)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						  $customer = getCustomerDetailsByCustomerId($to_customer_id);
						  $reg_no=getVehicleRegNoStringForCustomer($to_customer_id);
						 
						  echo $customer['customer_name']." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['credit_ledger_id']); ?>
						</td>
						 <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
						</td>
						 <td width="160px"><?php echo "Debit Note"; ?>
						</td>
						<td><?php if($head_type!=3) echo number_format($payment['amount'],2); ?>
						</td>
						<td><?php if($head_type==3) echo number_format($payment['amount'],2); ?>
						</td>
                         <td><?php echo $payment['remarks']; ?></td>
					   <td><?php if($head_type!=3) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==3) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo number_format($openingBalance,2)." Dr"; else echo number_format(-$openingBalance,2)." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php
						   
						 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/accounts/transactions/debit_note/index.php?view=details&id='.$payment['id']; else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/payment/penalty/index.php?view=details&id='.$file_id.'&state='.$payment['auto_id']; else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
         <?php }  } }?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>
<script>

  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.data( "ui-autocomplete" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
$( "#to_ledger" ).combobox();
 
</script>