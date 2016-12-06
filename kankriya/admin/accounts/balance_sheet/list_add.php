<?php
        $admin_id=$_SESSION['adminSession']['admin_id'];
		$period = getPeriodForUser($admin_id);
	
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));
		
		$reportArray=getFirstPageBalanceSheet($to);
		
		$pl_sheet_current_period=getProfitAndLossSheet($from,$to);
		$books_starting_date=getBooksStartingDateForCurrentCompanyOfUser();
		$current_company = getCurrentCompanyForUser($admin_id);
		
		$one_day_before_from=getPreviousDate($from);
		$pl_sheet_opening_period=getProfitAndLossSheet($books_starting_date,$one_day_before_from);
		$pl_sheet_ledger_id = getProfitAndLossLedgerId();
		$next_to_date = date('d/m/Y',strtotime(getNextDate($to)));
	    $closing_balance_pl_ledger=getOpeningBalanceForLedgerForDate('L'.$pl_sheet_ledger_id,$next_to_date);
		$opening_balance_pl_ledger=getOpeningBalanceForLedgerForDate('L'.$pl_sheet_ledger_id,$from);
		$profitAndLossCurrentPeriod=$pl_sheet_current_period['nett_profit']+$closing_balance_pl_ledger-$opening_balance_pl_ledger;
		$profitAndLossOpeningPeriod=$pl_sheet_opening_period['nett_profit']+$opening_balance_pl_ledger;
		 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Balance Sheet Of  <?php echo $current_company[2]; echo ' ['.$from.'-'.$to."]";  ?></h4>
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


	<div class="no_print">
 <?php if(isset($reportArray))
{
	$transaction_array=$reportArray;
 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
  
    <table id="accountContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
         
        <th class="heading no_sort" colspan="2">Liabilties</th>
        <th class="heading date" colspan="2">Assets</th>
        </tr>
    </thead>
    <tbody>
        <?php
		if($transaction_array!="error" && is_array($transaction_array))
		{
			$debit_total=0;
			$credit_total=0;
			foreach($transaction_array as $payment)
			{
				if($payment['head_id']>=0)
				{
				$child_heads=$payment['child_heads'];
				 ?>
                 <?php if($payment['opening_balance']<0  || ($payment['opening_balance']==0 && ($payment['head_id']==7 || $payment['head_id']== 25)  ))
				  { 
				  $credit_total=$credit_total+$payment['opening_balance'];
				  ?>
                  <?php if($payment['head_id']>0) 
				  { ?>
				 <tr class="resultRow mainHeadRow">
					
					<td class="mainHeadTd"><a href="index.php?view=second&id=<?php echo $payment['head_id']; ?>"><b><?php echo $payment['head_name']; ?></b></a><br /><table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table></td>			
					<td class="mainHeadBalTd" valign="top"><?php echo number_format(-$payment['opening_balance'],2); ?></td>
					<td></td>
                    <td></td> 
				</tr>	
                <?php 
				  } 
				else if($payment['head_id']==0) // if pl_sheet head
				{ ?>
                	<tr class="resultRow mainHeadRow">
                    <td class="mainHeadTd"><a href="../pl_sheet/index.php"><b><?php echo $payment['head_name']; ?></b></a></td>
                    <td class="mainHeadBalTd"><?php echo number_format(-$payment['opening_balance'],2); ?></td>
					<td></td>
                    <td></td> 
				</tr>	
                <tr class="resultRow mainHeadRow">
                    <td class="mainHeadTd">Opening Balance</td>
                    <td class="mainHeadBalTd"><?php if($profitAndLossOpeningPeriod>0) echo number_format(-$profitAndLossOpeningPeriod,2); else echo number_format(-$profitAndLossOpeningPeriod,2); ?></td>
					<td></td>
                    <td></td> 
				</tr>	
                <tr class="resultRow mainHeadRow">
                    <td class="mainHeadTd">Current Period</td>
                    <td class="mainHeadBalTd"><?php if($profitAndLossCurrentPeriod>0) echo number_format(-$profitAndLossCurrentPeriod,2); else echo number_format(-$profitAndLossCurrentPeriod,2); ?></td>
					<td></td>
                    <td></td> 
				</tr>	
                    <?php } ?>
              <!--      <?php if(isset($payment['child_heads']) && is_array($payment['child_heads']) && count($payment['child_heads'])>0 )
					{ 
                    foreach($payment['child_heads'] as $child_head)
					{
				 ?>
               
				 <tr class="resultRow subHeadRow">
					
					<td class="subHeadTd"><a href="index.php?view=second&id=<?php echo $child_head['head_id']; ?>"><?php echo $child_head['head_name']; ?></a></td>
					<td class="subHeadBalTd"><?php if($child_head['opening_balance']<0) echo $child_head['opening_balance']; else echo $child_head['opening_balance']; ?></td>
					<td></td>
                    <td></td>
                    </tr>
                    <?php }} ?> -->
                <?php 
				  }
				  else if($payment['opening_balance']>=0) 
				  { 
				   $debit_total=$debit_total+$payment['opening_balance'];
				  ?>
                <?php if($payment['head_id']>0) { ?>
                 <tr class="resultRow">
                    <td></td>
                    <td></td> 
					<td><a href="index.php?view=second&id=<?php echo $payment['head_id']; ?>"><b><?php echo $payment['head_name']; ?></b></a><br /><table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table></td>			
					<td><?php echo number_format($payment['opening_balance'],2); ?></td>
				</tr>
                  <?php } else if($payment['head_id']==0) { ?>
                    <tr class="resultRow">
                    <td></td>
                    <td></td> 
                    <td><a href="../pl_sheet/index.php"><b><?php echo $payment['head_name']; ?></b></a></td>
                    <td><?php echo number_format($payment['opening_balance'],2); ?></td>
				</tr>
                  <tr class="resultRow mainHeadRow">
                  	<td></td>
                    <td></td> 
                    <td class="mainHeadTd">Opening Balance</td>
                    <td class="mainHeadBalTd"><?php if($profitAndLossOpeningPeriod>0) echo number_format($profitAndLossOpeningPeriod,2); else echo number_format($profitAndLossOpeningPeriod,2); ?></td>
				</tr>	
                <tr class="resultRow mainHeadRow">
                	<td></td>
                    <td></td> 
                    <td class="mainHeadTd">Current Period</td>
                    <td class="mainHeadBalTd"><?php if($profitAndLossCurrentPeriod>0) echo number_format($profitAndLossCurrentPeriod,2); else echo number_format($profitAndLossCurrentPeriod,2); ?></td>
				</tr>	
                    <?php } ?>  
                    
              <!--      <?php if(isset($payment['child_heads']) && is_array($payment['child_heads']) && count($payment['child_heads'])>0 ) 
					{ 
                    foreach($payment['child_heads'] as $child_head)
					{
				 ?>
               
				 <tr class="resultRow">
					
                    <td></td>
                    <td></td>
                   
					<td><a href="index.php?view=second&id=<?php echo $child_head['head_id']; ?>"><?php echo $child_head['head_name']; ?></a></td>
					<td><?php if($child_head['opening_balance']<0) echo $child_head['opening_balance']; else echo round($child_head['opening_balance'],2); ?></td>
					
				  
		  
				</tr>
                 <?php 
				 	}
				 	} ?> -->
              <?php } ?>
        <?php }}}} ?>
        
        		<?php if($debit_total+$credit_total!=0)
				{
					$difference_in_opening_balances=$debit_total+$credit_total;
					?>
       			 <tr class="resultRow">
					
                    <?php if($difference_in_opening_balances>0){ ?>	
                   <td class="dangerRow">Diff in Opening Balance</td>
					<td class="dangerRow"><?php echo number_format($difference_in_opening_balances,2); ?></td>
					<?php }else{ ?>
					<td></td>
                    <td></td>
					<?php } ?>
                
                	<?php if($difference_in_opening_balances<0){ ?>	
                   <td class="dangerRow">Diff in Opening Balance</td>
					<td class="dangerRow"><?php echo number_format(-$difference_in_opening_balances,2); ?></td>
					<?php }else{ ?>
					<td></td>
                    <td></td>
					<?php } ?>
                   
					
				  
		  
				</tr>
               <?php } if($debit_total>(-$credit_total)) $total=$debit_total; else $total=-$credit_total;?>
       		   <tr class="resultRow">
					
                   <td><b>Total</b></td>
					<td><b><?php echo number_format($total,2); ?></b></td>
					
                   
					<td><b>Total</b></td>
					<td><b><?php echo number_format($total,2); ?></b></td>
					
				  
		  
				</tr>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>
