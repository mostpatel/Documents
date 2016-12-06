<?php 

        $id=$_GET['id'];
		$item = getInventoryItemById($id);
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

$reportArray=getAllTransactionsForItemIdForMonth($id,$month,$year,$transaction_array,$from,$to);

		 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Transactions For <?php if(checkForNumeric($id)) echo $item['item_name']." | ".$item['alias']." ".$item['item_code']." | ".$item['manufacturer'];  echo "[".date("F", mktime(0, 0, 0, $month, 10))." ".$year."]"; echo ' ['.$from.'-'.$to."]";  ?></h4>
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
	
	$transactions=$transaction_array;
	
	
	

	
	if(!isset($from))
	{
	$account_settings=getAccountsSettingsForOC($_SESSION['edmsAdminSession']['oc_id']);
	$from=$account_settings['ac_starting_date'];
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

	$openingBalance=getOpeningBalanceForItemForDate($id,$from);
	$first_day_next_month = getFirstDayOfNextMonth($month,$year);
	$closingBalance=getOpeningBalanceForItemForDate($id,$first_day_next_month);
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Ledger</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Inwards</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Outwards</label>
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Closing Balance</label> 
       
    </div>
    <table id="accountContentTable" class="adminContentTable no_print inventoryTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading no_sort">No</th>
        <th class="heading date">Date</th>
        <th class="heading date">Ledger</th>
       <th class="heading no_sort">Inwards</th>
        <th class="heading no_sort">Outwards</th>
        <th class="heading no_sort">Closing Balance</th>
        <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
      	  <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td></td>
            <td></td>
            <td></td>
            <td style="padding:0;margin:0;"><table width="100%" >
				  	<tr>
                    	<td width="25%">Qty</td>
                        <td width="25%">Rate</td>
                        <td width="50%">Amount</td>
                    </tr>          
            	</table></td>
            <td style="padding:0;margin:0;"><table width="100%">
				     <tr>
                    	<td width="25%">Qty</td>
                        <td width="25%">Rate</td>
                        <td width="50%">Amount</td>
                    </tr>           
            	</table></td>
            <td style="padding:0;margin:0;"><table width="100%">
				      <tr>
                    	<td width="25%">Qty</td>
                        <td width="25%">Rate</td>
                        <td width="50%">Amount</td>
                    </tr>          
            	</table></td>
           <td class="no_print"></td>
            </tr>
            
      	  <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php $i=0; echo ++$i; ?></td>
            <td><?php echo "Opening Balance";  ?></td>
            <td></td>
            <td></td>
            <td></td>
             <td style="padding:0;margin:0;" ><table width="100%">
				      <tr>
                    	<td width="25%"><?php echo $openingBalance[1]; ?></td>
                        <td width="25%"><?php echo $openingBalance[2]; ?></td>
                        <td width="50%"><?php echo $openingBalance[0]; ?></td>
                    </tr>          
            	</table></td>
           <td class="no_print"></td>
            </tr>
        <?php
		if($transactions && is_array($transactions))
		{
			foreach($transactions as $transaction)
			{
				
				if(array_key_exists('purchase_item_id',$transaction))
				{
				$from_ledger_id=$transaction['from_ledger_id'];
				$from_customer_id=$transaction['from_customer_id'];
				
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($transaction['trans_date'])); ?>
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
						  else echo getLedgerNameFromLedgerId($transaction['to_ledger_id']); ?>
					</td>
					 <td style="padding:0;margin:0;">
                     	<table width="100%">
                            <tr>
                                <td width="25%"><?php echo $transaction['quantity']; ?></td>
                                <td width="25%"><?php echo $transaction['net_amount']/$transaction['quantity']; ?></td>
                                <td width="50%"><?php echo $transaction['net_amount'];  ?></td>
                            </tr>          
            			</table>
                	</td>
                   
					 <td>
					 </td>
                      <td>
					 </td>
					
					
                  
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/payment/index.php?view=details&id='.$payment['payment_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/index.php?view=loanDetails&id='.getFileIdFromLoanId($payment['auto_id']); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
				</tr>
         <?php  }
		else if(array_key_exists('sales_item_id',$transaction))
				{	
					$to_ledger_id=$transaction['to_ledger_id'];
					$to_customer_id=$transaction['to_customer_id'];
					
					 ?>
					 <tr class="resultRow">
						<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($transaction['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==0){
									if(is_numeric($to_ledger_id))  echo getLedgerNameFromLedgerId($to_ledger_id);
									else if(is_numeric($to_customer_id)) 
									{ 
									
									 $customer=getCustomerDetailsByCustomerId($to_customer_id);
						  
						  
						  echo $customer[1];			
									}
							  } else  echo getLedgerNameFromLedgerId($transaction['from_ledger_id']); ?>
						</td>
						 <td>
						</td>
						 <td style="padding:0;margin:0;">
                     	<table width="100%">
                            <tr>
                                <td width="25%"><?php echo $transaction['quantity']; ?></td>
                                <td width="25%"><?php echo $transaction['net_amount']/$transaction['quantity']; ?></td>
                                <td width="50%"><?php echo $transaction['net_amount'];  ?></td>
                            </tr>          
            			</table>
                	</td>
					 <td>
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
						
						 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/payment/index.php?view=details&id='.$file_id.'&lid='.$payment['auto_id'].'&state='.$emi_id; else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/payment/penalty/index.php?view=details&id='.$file_id.'&state='.$payment['auto_id']; else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
        
         <?php
		 } }}}?>
           <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php  echo ++$i; ?></td>
            <td><?php echo "Closing Balance";  ?></td>
            <td></td>
            <td></td>
            <td></td>
             <td style="padding:0;margin:0;" ><table width="100%">
				      <tr>
                    	<td width="25%"><?php echo $closingBalance[1]; ?></td>
                        <td width="25%"><?php echo $closingBalance[2]; ?></td>
                        <td width="50%"><?php echo $closingBalance[0]; ?></td>
                    </tr>          
            	</table></td>
           <td class="no_print"></td>
            </tr>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>