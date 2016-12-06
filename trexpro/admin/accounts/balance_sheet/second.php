<?php 
if(!isset($_GET['id']))
		exit;
else
$head_id=$_GET['id'];	
$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$period = getPeriodForUser($admin_id);
	
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));	
if(is_numeric($head_id))
$transaction_array=getSecondPageBalanceSheet($head_id);
else if($head_id=="closePL")
{
	$closing_stock = getClosingStockForDateItemwise($to);
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Balance Sheet <?php  echo ' ['.$from.'-'.$to."]";  ?></h4>
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
  
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
  <?php if(is_numeric($head_id)) { ?>
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading no_sort">No</th>
        <th class="heading no_sort"><?php echo $transaction_array['head_name']; ?></th>
        <th class="heading no_sort" >Debit</th>
      	  <th class="heading no_sort" >Credit</th>
        </tr>
    </thead>
    <tbody>
        <?php
		if($transaction_array!="error" && is_array($transaction_array))
		{
			$i=1;
				$child_heads=$transaction_array['child_heads'];
				
				if(is_array($child_heads) && count($child_heads)>0)
				{
					
				foreach($child_heads as $child_head)
				{
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo $i++; ?></td>
					<td><a href="index.php?view=second&id=<?php echo $child_head['head_id']; ?>"><b><?php echo $child_head['head_name']; ?></b></a></td>
					<td><?php if($child_head['opening_balance']>=0) echo number_format($child_head['opening_balance'],2)." Dr"; ?></td>
					<td><?php if($child_head['opening_balance']<0) echo number_format(-$child_head['opening_balance'],2)." Cr"; ?></td>
					
                   </tr>
               <?php } }
			   $ledgers=$transaction_array['ledgers'];
				if(is_array($ledgers) && count($ledgers)>0)
				{
				foreach($ledgers as $ledger)
				{
					
					if(!isset($ledger['opening_balance']))
					continue;
					
					if(isset($ledger['opening_balance']) && is_numeric($ledger['opening_balance']))
					$opening_balance = $ledger['opening_balance'];
					else
					$opening_balance = 0;
					
					if(isset( $ledger['payment_amount'])  && is_numeric($ledger['payment_amount']))
					$payment_amount = $ledger['payment_amount'];
					else
					$payment_amount = 0;
					
					if(isset( $ledger['receipt_amount']) && is_numeric($ledger['receipt_amount']))
					$receipt_amount = $ledger['receipt_amount'];
					else
					$receipt_amount = 0;
					
					if(isset( $ledger['credit_jv_amount'])  && is_numeric($ledger['credit_jv_amount']))
					$credit_jv_amount = $ledger['credit_jv_amount'];
					else
					$credit_jv_amount = 0;
					
					if(isset( $ledger['debit_jv_amount']) && is_numeric($ledger['debit_jv_amount']))
					$debit_jv_amount = $ledger['debit_jv_amount'];
					else
					$debit_jv_amount = 0;
					
					if(isset( $ledger['credit_contra_amount']) && is_numeric($ledger['credit_contra_amount']))
					$credit_contra_amount = $ledger['credit_contra_amount'];
					else
					$credit_contra_amount = 0;
					
					if(isset( $ledger['debit_contra_amount']) && is_numeric($ledger['debit_contra_amount']))
					$debit_contra_amount = $ledger['debit_contra_amount'];
					else
					$debit_contra_amount = 0;
					
					if(isset( $ledger['purchase_amount']) && is_numeric($ledger['purchase_amount']))
					$purchase_amount = $ledger['purchase_amount'];
					else
					$purchase_amount = 0;
					
					if(isset( $ledger['sales_amount']) && is_numeric($ledger['sales_amount']))
					$sales_amount = $ledger['sales_amount'];
					else
					$sales_amount = 0;
					
					if(isset( $ledger['credit_note_amount']) && is_numeric($ledger['credit_note_amount']))
					$credit_note_amount = $ledger['credit_note_amount'];
					else
					$credit_note_amount = 0;
					
					if(isset( $ledger['debit_note_amount']) && is_numeric($ledger['debit_note_amount']))
					$debit_note_amount = $ledger['debit_note_amount'];
					else
					$debit_note_amount = 0;
					
					if(isset( $ledger['purchase_tax_amount']) && is_numeric($ledger['purchase_tax_amount']))
					$purchase_tax_amount = $ledger['purchase_tax_amount'];
					else
					$purchase_tax_amount = 0;
					
					if(isset( $ledger['sales_tax_amount']) && is_numeric($ledger['sales_tax_amount']))
					$sales_tax_amount = $ledger['sales_tax_amount'];
					else
					$sales_tax_amount = 0;
					
					if(isset( $ledger['credit_note_tax_amount']) && is_numeric($ledger['credit_note_tax_amount']))
					$credit_note_tax_amount = $ledger['credit_note_tax_amount'];
					else
					$credit_note_tax_amount = 0;
					
					if(isset( $ledger['debit_note_tax_amount']) && is_numeric($ledger['debit_note_tax_amount']))
					$debit_note_tax_amount = $ledger['debit_note_tax_amount'];
					else
					$debit_note_tax_amount = 0;
					
					
					$net_amount = $opening_balance + $payment_amount + $receipt_amount + $credit_jv_amount + $debit_jv_amount + $credit_contra_amount + $debit_contra_amount + $purchase_amount + $sales_amount + $debit_note_amount + $credit_note_amount + $purchase_tax_amount + $sales_tax_amount + $credit_note_tax_amount + $debit_note_tax_amount;
					
			    ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo $i++; ?></td>
					<td><a href="index.php?view=third&id=<?php echo $ledger['id']; ?>"><?php echo $ledger['name']; ?></a></td>
					<td><?php  if($net_amount>=0) echo number_format($net_amount,2)." Dr"; ?></td>
				<td><?php if($net_amount<0) echo number_format(-$net_amount,2)." Cr"; ?></td>
                </tr>
            <?php }
				}?>
				  
		  
		<?php } ?>
          <tr class="resultRow">
					
                   <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo $i++;  ?></td>
					<td><b>Closing Balance</b></td>
					<td><?php if($transaction_array['opening_balance']>=0) echo number_format($transaction_array['opening_balance'],2)." Dr"; ?></td>
					<td><?php if($transaction_array['opening_balance']<0) echo number_format(-$transaction_array['opening_balance'],2)." Cr"; ?></td>
					
				  
		  
				</tr>		
         </tbody>
    </table>
    <?php  } ?>
     <?php if($head_id=="closePL") { ?>
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading no_sort">No</th>
        <th class="heading no_sort"><?php echo "Item Name"; ?></th>
        <th class="heading no_sort"><?php echo "Quantity"; ?></th>
        <th class="heading no_sort"><?php echo "Avg Rate"; ?></th>
        <th class="heading no_sort" >Debit</th>
      	  <th class="heading no_sort" >Credit</th>
        </tr>
    </thead>
    <tbody>
        <?php
		if($closing_stock!="error" && is_array($closing_stock))
		{
			
				if(is_array($closing_stock) && count($closing_stock)>0)
				{
				$i=1;	
				foreach($closing_stock as $item)
				{
				 ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        			<td><?php echo $i++; ?></td>
					<td><a href="index.php?view=second&id=<?php echo $child_head['head_id']; ?>"><b><?php echo $item['item_name']; ?></b></a></td>
                    <td><?php echo $item['closing_quantity']; ?></td>
                    <td><?php echo $item['closing_rate']; ?></td>
					<td><?php if($item['closing_balance']>=0) echo number_format($item['closing_balance'],2)." Dr"; ?></td>
					<td><?php if($item['closing_balance']<0) echo number_format(-$item['closing_balance'],2)." Cr"; ?></td>
					
                   </tr>
               <?php } } ?>
			  
				  
		  
		<?php } ?>
          <tr class="resultRow">
					
                   <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo $i++;  ?></td>
					<td><b>Closing Balance</b></td>
					<td><?php if($transaction_array['opening_balance']>=0) echo number_format($transaction_array['opening_balance'],2)." Dr"; ?></td>
					<td><?php if($transaction_array['opening_balance']<0) echo number_format(-$transaction_array['opening_balance'],2)." Cr"; ?></td>
					
				  
		  
				</tr>		
         </tbody>
    </table>
    <?php  } ?>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>