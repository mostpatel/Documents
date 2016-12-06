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
$transaction_array=getSecondPagePLSheet($head_id);
else if($head_id=="closePL")
{
	$closing_stock = getClosingStockForDateItemwise($to);
}
else if($head_id=="openPL")
{
    
	$closing_stock = getClosingStockForDateItemwise(date('d/m/Y',strtotime(getPreviousDate($from))));
	
}
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Profit And Loss Sheet <?php  echo ' ['.$from.'-'.$to."]";  ?></h4>
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
			$i=0;
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
			    ?>
				 <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo $i++; ?></td>
					<td><a href="index.php?view=third&id=<?php echo $ledger['id']; ?>"><?php echo $ledger['name']; ?></a></td>
					<td><?php  if($ledger['opening_balance']>=0) echo number_format($ledger['opening_balance'],2)." Dr"; ?></td>
				<td><?php if($ledger['opening_balance']<0) echo number_format(-$ledger['opening_balance'],2)." Cr"; ?></td>
                </tr>
            <?php }
				}?>
				  
		  
		
          <tr class="resultRow">
					
                   <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo $i++;  ?></td>
					<td><b>Closing Balance</b></td>
					<td><?php if($transaction_array['opening_balance']>=0) echo number_format($transaction_array['opening_balance'],2)." Dr"; ?></td>
					<td><?php if($transaction_array['opening_balance']<0) echo number_format(-$transaction_array['opening_balance'],2)." Cr"; ?></td>
					
				  
		  
			</tr>		
            <?php } ?>
         </tbody>
    </table>
    <?php } else if($head_id=="closePL" || $head_id=="openPL") { ?>
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading no_sort">No</th>
        <th class="heading no_sort"><?php echo "Item Name"; ?></th>
        <th class="heading no_sort"><?php echo "Debit Qty"; ?></th>
        <th class="heading no_sort"><?php echo "Credit Qty"; ?></th>
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
					<td><a href="index.php?view=third&&type=inv&id=<?php echo $item['item_id']; ?>"><b><?php echo $item['item_name']; ?></b></a></td>
                    <td><?php if($item['closing_quantity']>0) echo $item['closing_quantity']; ?></td>
                     <td><?php if($item['closing_quantity']<0) echo $item['closing_quantity']; ?></td>
                    <td><?php echo round($item['closing_rate'],2); ?></td>
					<td><?php if($item['closing_balance']>=0) echo number_format($item['closing_balance'],2)." Dr"; ?></td>
					<td><?php if($item['closing_balance']<0) echo number_format(-$item['closing_balance'],2)." Cr"; ?></td>
					
                   </tr>
               <?php } } ?>
			  
				  
		  
		<?php } ?>
          <tr class="resultRow">
					
                   <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo $i++;  ?></td>
					<td><b>Closing Balance</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    
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