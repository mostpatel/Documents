<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Transactions For Ledger</h4>
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
 <?php if(isset($_SESSION['ledgerEntriesMonth']['entries_array']))
{
	$id=$_SESSION['ledgerEntriesMonth']['ledger_id'];
	$transaction_array=$_SESSION['ledgerEntriesMonth']['entries_array'];
	
	$payments=$transaction_array[0];
	$head_type=$transaction_array[1];
	
	
	if(isset($_SESSION['ledgerEntriesMonth']['from']) && validateForNull($_SESSION['ledgerEntriesMonth']['from']))
	{	
	$from=$_SESSION['ledgerEntriesMonth']['from'];
	}
	else
	{
	$from=getBooksStartingDateForLedgerCustomer($id);
	}
	
	
	
	if($head_type==0)
	{
	$contras=$transaction_array[2];
	$ledger_id=str_replace('L','',$id);
	$ledger_id=intval($ledger_id);
	$ledger_type=0;
	}
	else if($head_type==1)
	{
	$jvs=$transaction_array[2];	
		if(substr($id, 0, 1) === 'L')
		{
			$ledger_id=str_replace('L','',$id);
			$ledger_id=intval($ledger_id);
			$ledger_type=0;
		}
		else if(substr($id, 0, 1) === 'C')
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
        <th class="heading no_sort">Mode</th>
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
            <td><?php if($openingBalance>=0) echo $openingBalance; ?></td>
            <td><?php if($openingBalance<0) echo -$openingBalance;  ?></td>
            <td></td>
            <td><?php if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr"; ?></td>
           <td></td>
            </tr>
        <?php
		if($payments!="error" && is_array($payments))
		{
			foreach($payments as $payment)
			{
				
				if(array_key_exists('payment_id',$payment))
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
						if($head_type==0)
						  {
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  $file_id=getFileIdFromCustomerId($from_customer_id);
						  $file_no=getFileNumberByFileId($file_id);
						  $reg_no=getRegNoFromFileID($file_id);
						  $customer=getCustomerNameANDCoByFileId($from_customer_id);
						  echo $customer[1]." "; echo $file_no." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['to_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
					</td>
					 <td width="160px"><?php echo "Payment"; ?>
					</td>
					 <td><?php if($head_type==1) echo $payment['amount']; ?>
					</td>
					<td><?php if($head_type==0) echo $payment['amount']; ?>
					</td>
                    <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==1) $openingBalance=$openingBalance+$payment['amount']; else if($head_type==0) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/payment/index.php?view=details&id='.$payment['payment_id']; } ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		else if(array_key_exists('receipt_id',$payment))
				{	
					$to_ledger_id=$payment['to_ledger_id'];
					$to_customer_id=$payment['to_customer_id'];
					 ?>
					 <tr class="resultRow">
						<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==0){
									if(is_numeric($to_ledger_id))  echo getLedgerNameFromLedgerId($to_ledger_id);
									else if(is_numeric($to_customer_id)) 
									{ 
									$file_id=getFileIdFromCustomerId($to_customer_id); 
									$file_no=getFileNumberByFileId($file_id); 
									$reg_no=getRegNoFromFileID($file_id); 
									$customer=getCustomerNameANDCoByFileId($file_id); 
									echo $customer[1]; echo " ".$file_no." "; if($reg_no) echo $reg_no;				
									}
							  } else  echo getLedgerNameFromLedgerId($payment['from_ledger_id']); ?>
						</td>
						 <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
						</td>
						 <td width="160px"><?php echo "Receipt"; ?>
						</td>
						<td><?php if($head_type==0) echo $payment['amount']; ?>
						</td>
						<td><?php if($head_type==1) echo $payment['amount']; ?>
						</td>
                          <td><?php echo $payment['remarks']; ?></td>
					   <td><?php if($head_type==0) $openingBalance=$openingBalance+$payment['amount']; else if($head_type==1) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php
						   if($payment['auto_rasid_type']==2)
						{
						$emi_id=getEMIIDFromPaymentId($payment['auto_id']);
						$loan_id=getLoanIdFromEmiId($emi_id);
						$file_id=getFileIdFromLoanId($loan_id);
						}
						else if($payment['auto_rasid_type']==3)
						{
							$file_id=getFileIdFromPenaltyId($payment['auto_id']);
						}
						else if($payment['auto_rasid_type']==4)
						{
							$file_id=$payment['auto_id'];
						}
						 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/payment/index.php?view=details&id='.$file_id.'&lid='.$payment['auto_id'].'&state='.$emi_id; else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/payment/penalty/index.php?view=details&id='.$file_id.'&state='.$payment['auto_id']; else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id; else { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
         <?php } 
		else if(array_key_exists('jv_id',$payment))
				{
		
		$to_ledger_id=$payment['to_ledger_id'];
		$to_customer_id=$payment['to_customer_id'];
		$from_ledger_id=$payment['from_ledger_id'];
		$from_customer_id=$payment['from_customer_id'];
		
		
		
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
			 		{ if(is_numeric($from_ledger_id))  echo getLedgerNameFromLedgerId($from_ledger_id);
					  else if(is_numeric($from_customer_id)){ $file_id=getFileIdFromCustomerId($from_customer_id); $file_no=getFileNumberByFileId($file_id); $reg_no=getRegNoFromFileID($file_id); $customer=getCustomerNameANDCoByFileId($from_customer_id); echo $customer[1]." "; echo $file_no." "; if($reg_no) echo $reg_no; }
					}
					else if($jv_type==1) // if it is a accounts ledger and a credit jv
			 		{ if(is_numeric($to_ledger_id))  echo getLedgerNameFromLedgerId($to_ledger_id);
					  else if(is_numeric($to_customer_id)) { $file_id=getFileIdFromCustomerId($to_customer_id); $file_no=getFileNumberByFileId($file_id); $reg_no=getRegNoFromFileID($file_id); $customer=getCustomerNameANDCoByFileId($file_id); echo $customer[1]; echo " ".$file_no." "; if($reg_no) echo $reg_no; }
					}
					 ?>
            </td>
             <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
            </td>
             <td width="160px"><?php echo "Journal" ?>
            </td>
             <td><?php if($jv_type==0) echo $payment['amount'];  ?>
            </td>
             <td><?php if($jv_type==1) echo $payment['amount'];  ?>
            </td>
              <td><?php echo $payment['remarks']; ?></td>
             <td><?php if($jv_type==0) $openingBalance=$openingBalance+$payment['amount']; else if($jv_type==1) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
            </td>
           
             <td class="no_print"> <a href="<?php 
			 
			  if($payment['auto_rasid_type']==2)
						{
						$emi_id=getEMIIDFromPaymentId($payment['auto_id']);
						$loan_id=getLoanIdFromEmiId($emi_id);
						$file_id=getFileIdFromLoanId($loan_id);
						}
						else if($payment['auto_rasid_type']==3)
						{
							$file_id=getFileIdFromPenaltyId($payment['auto_id']);
						}
						else if($payment['auto_rasid_type']==4)
						{
							$file_id=$payment['auto_id'];
						}
			 
			 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/jv/index.php?view=details&id='.$payment['jv_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/index.php?view=loanDetails&id='.$file_id; else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/payment/index.php?view=details&id='.$file_id.'&lid='.$payment['auto_id'].'&state='.getEMIIDFromPaymentId($payment['auto_id']);else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id;  else  { echo WEB_ROOT.'admin/accounts/transactions/jv/index.php?view=details&id='.$payment['jv_id']; }  ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
           
            
          
  
        </tr>
         <?php } 
		else if(array_key_exists('contra_id',$payment))
				{
		$to_ledger_id=$payment['to_ledger_id'];
		$from_ledger_id=$payment['from_ledger_id'];
		
		
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
             <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
            </td>
             <td width="160px"><?php echo "Contra"; ?>
            </td>
             <td><?php if($to_ledger_id==$ledger_id) echo $payment['amount']; ?>
            </td>
            <td><?php if($from_ledger_id==$ledger_id) echo $payment['amount']; ?>
            </td>
              <td><?php echo $payment['remarks']; ?></td>
           <td><?php if($to_ledger_id==$ledger_id) $openingBalance=$openingBalance+$payment['amount']; else if($from_ledger_id==$ledger_id) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
            </td>
           
           <td class="no_print"> <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/contra/index.php?view=details&id='.$payment['contra_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
          
  
        </tr>
         <?php 
		 ?>
         <?php
		 }
		  else if(array_key_exists('purchase_id',$payment))
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
						if($head_type==3)
						  {
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  $customer=getCustomerDetailsByCustomerId($from_customer_id);
						  echo $customer[1];}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['to_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==1)echo "VEHICLE"; else echo "ITEM"; ?>
					</td>
					 <td width="160px"><?php echo "Purchase"; ?>
					</td>
					 <td><?php if($head_type==3) echo number_format( $payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==1 || $head_type==0) echo number_format( $payment['amount'],2); ?>
					</td>
                      <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==1 || $head_type==0) $openingBalance=$openingBalance-$payment['amount']; else if($head_type==3) $openingBalance=$openingBalance+$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$payment['purchase_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/purchase/vehicle/index.php?view=details&id='.($payment['purchase_id']); else { echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$payment['purchase_id']; } ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		  else if(array_key_exists('sales_tax_id',$payment))
				{
				$from_ledger_id=$payment['to_ledger_id'];
				$from_customer_id=$payment['to_customer_id'];
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
						if($head_type==4 || $head_type==2)
						  {
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  $customer=getCustomerDetailsByCustomerId($from_customer_id);
						  echo $customer[1];}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['from_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==1)echo "VEHICLE"; else echo "ITEM"; ?>
					</td>
					 <td width="160px"><?php echo "Tax (Sales)"; ?>
					</td>
					 <td><?php if($head_type==4 || $head_type==2) echo number_format( $payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==1 || $head_type==0) echo number_format( $payment['amount'],2); ?>
					</td>
                      <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==1 || $head_type==0) $openingBalance=$openingBalance-$payment['amount']; else if($head_type==4 || $head_type==2) $openingBalance=$openingBalance+$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$payment['purchase_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/purchase/vehicle/index.php?view=details&id='.($payment['purchase_id']); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		 else if(array_key_exists('sales_id',$payment))
				{
				
				$to_ledger_id=$payment['to_ledger_id'];
				$to_customer_id=$payment['to_customer_id'];
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
						if($head_type==0)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						
						  $customer=getCustomerDetailsByCustomerId($to_customer_id);
						  echo $customer[1];}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['from_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==1) echo "VEHICLE"; else echo "ITEM"; ?>
					</td>
					 <td width="160px"><?php echo "Sales"; ?>
					</td>
					 <td><?php if($head_type==1 || $head_type==0) echo number_format( $payment['net_amount'],2); ?>
					</td>
					<td><?php if($head_type==4) echo number_format( $payment['net_amount'],2); ?>
					</td>
                      <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==4) $openingBalance=$openingBalance-$payment['net_amount']; else if($head_type==1 || $head_type==0) $openingBalance=$openingBalance+$payment['net_amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$payment['sales_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.(getDeliveryChallanIdFromSalesId($payment['sales_id'])); else echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$payment['sales_id'];; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		  else if(array_key_exists('debit_note_tax_id',$payment))
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
						  $customer=getCustomerDetailsByCustomerId($from_customer_id);
						  echo $customer[1];}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['to_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==1) echo "VEHICLE"; else if($payment['auto_rasid_type']==2)  echo "ITEM"; ?>
					</td>
					 <td width="160px"><?php echo "Tax (Debit Note)"; ?>
					</td>
					 <td><?php if($head_type==3 || $head_type==2) echo number_format( $payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==1 || $head_type==0) echo number_format( $payment['amount'],2); ?>
					</td>
                      <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==1 || $head_type==0) $openingBalance=$openingBalance-$payment['amount']; else if($head_type==3 || $head_type==2) $openingBalance=$openingBalance+$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==2) { echo WEB_ROOT.'admin/accounts/transactions/debit_note/index.php?view=details&id='.$payment['debit_note_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/purchase/vehicle/index.php?view=details&id='.($payment['purchase_id']); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		 else if(array_key_exists('debit_note_id',$payment))
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
						if($head_type==0)
						  {
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  $customer=getCustomerDetailsByCustomerId($from_customer_id);
						  echo $customer[1];}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['to_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==1) echo "VEHICLE"; else if($payment['auto_rasid_type']==2)  echo "ITEM"; ?>
					</td>
					 <td width="160px"><?php echo "Debit Note"; ?>
					</td>
					 <td><?php if($head_type==3) echo number_format( $payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==1 || $head_type==0) echo number_format( $payment['amount'],2); ?>
					</td>
				   <td><?php if($head_type==1 || $head_type==0) $openingBalance=$openingBalance-$payment['amount']; else if($head_type==3) $openingBalance=$openingBalance+$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
                      <td><?php echo $payment['remarks']; ?></td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==2) { echo WEB_ROOT.'admin/accounts/transactions/debit_note/index.php?view=details&id='.$payment['debit_note_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/purchase/vehicle/index.php?view=details&id='.($payment['purchase_id']); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		 else if(array_key_exists('credit_note_tax_id',$payment))
				{
				$to_ledger_id=$payment['to_ledger_id'];
				$to_customer_id=$payment['to_customer_id'];
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
						if($head_type==4 || $head_type==2)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						
						  $customer=getCustomerDetailsByCustomerId($to_customer_id);
						  echo $customer[1];}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['from_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==1) echo "VEHICLE"; else if($payment['auto_rasid_type']==2) echo "ITEM"; ?>
					</td>
					 <td width="160px"><?php echo "Credit Note"; ?>
					</td>
					 <td><?php if($head_type==1 || $head_type==0) echo number_format( $payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==4 || $head_type==2) echo number_format( $payment['amount'],2); ?>
					</td>
                      <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==4 || $head_type==2) $openingBalance=$openingBalance-$payment['amount']; else if($head_type==1 || $head_type==0) $openingBalance=$openingBalance+$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==2) { echo WEB_ROOT.'admin/accounts/transactions/credit_note/index.php?view=details&id='.$payment['credit_note_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.(getDeliveryChallanIdFromSalesId($payment['sales_id'])); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		  else if(array_key_exists('credit_note_id',$payment))
				{
				$to_ledger_id=$payment['to_ledger_id'];
				$to_customer_id=$payment['to_customer_id'];
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
						if($head_type==0)
						  {
						  if(is_numeric($to_ledger_id))  
						  echo getLedgerNameFromLedgerId($to_ledger_id);
						  else if(is_numeric($to_customer_id))
						  { 
						
						  $customer=getCustomerDetailsByCustomerId($to_customer_id);
						  echo $customer[1];}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['from_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==1) echo "VEHICLE"; else if($payment['auto_rasid_type']==2) echo "ITEM"; ?>
					</td>
					 <td width="160px"><?php echo "Credit Note"; ?>
					</td>
					 <td><?php if($head_type==1 || $head_type==0) echo number_format( $payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==4) echo number_format( $payment['amount'],2); ?>
					</td>
                      <td><?php echo $payment['remarks']; ?></td>
				   <td><?php if($head_type==4) $openingBalance=$openingBalance-$payment['amount']; else if($head_type==1 || $head_type==0) $openingBalance=$openingBalance+$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==2) { echo WEB_ROOT.'admin/accounts/transactions/credit_note/index.php?view=details&id='.$payment['credit_note_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.(getDeliveryChallanIdFromSalesId($payment['sales_id'])); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		  }}}?>
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