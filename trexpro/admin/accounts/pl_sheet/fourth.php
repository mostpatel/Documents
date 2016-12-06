<?php 

        $id=$_GET['id'];
		if(validateForNull($id)&& substr($id, 0, 1) === 'C') // if the pament is done to a general account ledger
		{
			$main_customer_id=str_replace('C','',$id);
			if(is_numeric($main_customer_id))
						  { 
						  $main_file_id=getFileIdFromCustomerId($main_customer_id);
						  $main_file_no=getFileNumberByFileId($main_file_id);
						  $main_reg_no=getRegNoFromFileID($main_file_id);
						  $main_customer=getCustomerNameANDCoByFileId($main_file_id);
						  } 
		}
		else if(validateForNull($id)&& substr($id, 0, 1)=='L')
		{
			$main_ledger_id=str_replace('L','',$id);
			if(is_numeric($main_ledger_id))
			$main_ledger_name=getLedgerNameFromLedgerId($main_ledger_id);
			}		
		if(isset($_GET['from']))
		{
		$from=$_GET['from'];
		}
		else
		$from=null;
		
		if(isset($_GET['to']))
		{
		$to=$_GET['to'];
		}
		else
		$to=null;	
		
		if(isset($_GET['month']))
		{
		$month=$_GET['month'];
		}
		else
		$month=null;	
		
		if(isset($_GET['year']))
		{
		$year=$_GET['year'];
		}
		else
		$year=null;	
		
		
		if(isset($from) && validateForNull($from))
{
	    $from_mysql = str_replace('/', '-', $from);
		$from_mysql=date('Y-m-d',strtotime($from_mysql));
	}
		
		
		if(date('m',strtotime($from_mysql))==$month && date('Y',strtotime($from_mysql))==$year)
		$from=$from;
		else
		{	
		$from='01/'.$month.'/'.$year;
		}

$transaction_array=null;
$reportArray=getAllTransactionsForLedgerIdForMonth($id,$month,$year,$transaction_array,$from,$to);

		 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Transactions For <?php if(checkForNumeric($main_customer_id)) echo $main_customer['customer_name']." ".$main_file_no." ".$main_reg_no; else if(checkForNumeric($main_ledger_id)) echo $main_ledger_name; echo "[".date("F", mktime(0, 0, 0, $month, 10))." ".$year."]"; echo ' ['.$from.'-'.$to."]";  ?></h4>
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
	
	$payments=$transaction_array[0];
	$head_type=$transaction_array[1];
	
	
	if(!isset($from))
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
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Particulars</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Mode</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Type</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Debit</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">Credit</label> 
        <input class="showCB" type="checkbox" id="8" checked="checked"  /><label class="showLabel" for="8">Closing Balance</label> 
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
        <th class="heading no_sort">Closing Balance</th>
        <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        <?php
		if($payments!="error" && is_array($payments))
		{
			$openingBalance=0;
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
					 <td><?php if($head_type==1) echo number_format( $payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==0) echo number_format( $payment['amount'],2); ?>
					</td>
				   <td><?php if($head_type==1) $openingBalance=$openingBalance+$payment['amount']; else if($head_type==0) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/payment/index.php?view=details&id='.$payment['payment_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/index.php?view=loanDetails&id='.getFileIdFromLoanId($payment['auto_id']); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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
						<td><?php if($head_type==0) echo number_format( $payment['amount'],2); ?>
						</td>
						<td><?php if($head_type==1) echo number_format( $payment['amount'],2); ?>
						</td>
					   <td><?php if($head_type==0) $openingBalance=$openingBalance+$payment['amount']; else if($head_type==1) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php
						$emi_id=getEMIIDFromPaymentId($payment['auto_id']);
						$loan_id=getLoanIdFromEmiId($emi_id);
						$file_id=getFileIdFromLoanId($loan_id);
						
						 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/payment/index.php?view=details&id='.$file_id.'&lid='.$payment['auto_id'].'&state='.getEMIIDFromPaymentId($emi_id); else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/payment/penalty/index.php?view=details&id='.$file_id.'&state='.$payment['auto_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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
             <td><?php if($jv_type==0) echo number_format( $payment['amount'],2);  ?>
            </td>
             <td><?php if($jv_type==1) echo number_format( $payment['amount'],2);  ?>
            </td>
             <td><?php if($jv_type==0) $openingBalance=$openingBalance+$payment['amount']; else if($jv_type==1) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
            </td>
           
            <td class="no_print"> <a href="<?php
			 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/jv/index.php?view=details&id='.$payment['jv_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/index.php?view=loanDetails&id='.$main_file_id; else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/payment/index.php?view=details&id='.$main_file_id.'&lid='.$payment['auto_id'].'&state='.getEMIIDFromPaymentId($payment['auto_id']);  ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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
             <td><?php if($to_ledger_id==$ledger_id) echo number_format( $payment['amount'],2); ?>
            </td>
            <td><?php if($from_ledger_id==$ledger_id) echo number_format( $payment['amount'],2); ?>
            </td>
           <td><?php if($to_ledger_id==$ledger_id) $openingBalance=$openingBalance+$payment['amount']; else if($from_ledger_id==$ledger_id) $openingBalance=$openingBalance-$payment['amount']; if($openingBalance>=0) echo number_format( $openingBalance,2)." Dr"; else echo number_format( -$openingBalance,2)." Cr";  ?>
            </td>
           
            <td class="no_print"> <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/contra/index.php?view=details&id='.$payment['contra_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
        </tr>
         <?php 
		 ?>
         <?php
		 } }}}?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>