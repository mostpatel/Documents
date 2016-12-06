<?php
        $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$period = getPeriodForUser($admin_id);
	
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));
		
		$reportArray=getFirstPageClosingStockSheet($to);
		
		
		$books_starting_date=getBooksStartingDateForCurrentCompanyOfUser();
		$one_day_before_from=getPreviousDate($from);
		
		 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Closing Stock Sheet <?php  echo ' ['.$from.'-'.$to."]";  ?></h4>
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
        <th class="heading no_print">Print</th>
        <th class="heading">No</th>
        <th class="heading" >Item Name</th>
        <th class="heading" >Qty</th>
        <th class="heading" >Rate</th>
        <th class="heading" >Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
		if($transaction_array!="error" && is_array($transaction_array))
		{
			$total=0;
			
			foreach($transaction_array as $item)
			{
		?>		
			<tr class="resultRow">	  
            	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	     <td></td>
		  		<td><a href="index.php?view=third&id=<?php echo $item['item_id']; ?>"><?php  echo $item['item_name']; ?></a></td>
                <td><?php echo $item['closing_quantity']; ?></td>
                <td><?php echo $item['closing_rate']; ?></td>
                <td><?php echo $item['closing_balance']; ?></td>
			</tr>
           <?php } ?>     
               <?php } if($debit_total>(-$credit_total)) $total=$debit_total; else $total=-$credit_total;?>
       		   <tr class="resultRow">
					<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td></td>
                   <td><b>Total</b></td>
					<td></td>
					
                   
					<td></td>
					<td><b><?php echo number_format($total,2); ?></b></td>
					
				  
		  
				</tr>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php }  ?>      
</div>
<div class="clearfix"></div>
