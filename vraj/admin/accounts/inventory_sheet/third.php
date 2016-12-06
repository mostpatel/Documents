<?php
	    $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$period = getPeriodForUser($admin_id);
		$from=date('d/m/Y',strtotime($period[0]));
		$to=date('d/m/Y',strtotime($period[1]));
	    $id=$_GET['id'];
		$item = getInventoryItemById($id);
$reportArray=getAllTransactionsForItemIdMonthWise($id,$transaction_array,$from,$to);
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment ">Transactions For <?php echo $item['item_name']; ?></h4>
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
	
	
	$openingBalance=getOpeningBalanceForItemForDate($id,$from);
	
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Month</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Debit</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Credit</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Closing Balance</label> 
    </div>
    <table id="accountContentTable" class="adminContentTable no_print inventoryTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading ">No</th>
        <th class="heading date default_sort">Month</th>
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
		if($transaction_array!="error" && is_array($transaction_array))
		{
		foreach($transaction_array as $key => $value)
		{
			
			$purchase_amount=$value[0];
			$purchase_quantity=$value[1];
			$purchase_rate=$value[2];
			
			$sale_amount=$value[3];
			$sale_quantity=$value[4];
			$sale_rate=$value[5];
			
			$closing_amount=$value[6];
			$closing_quantity=$value[7];
			$closing_rate=$value[8];
			
			
			$month_id=$value[9];
			$year=$value[10];
			
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
             <td class="date"> <a href="<?php echo 'index.php?view=fourth&id='.$id.'&month='.$month_id.'&year='.$year.'&from='.$from.'&to='.$to ?>"> <?php  echo $key; ?></a>
            <td style="padding:0;margin:0;"><table width="100%">
				  	<tr>
                    	<td width="25%"><?php echo $purchase_quantity; ?></td>
                        <td width="25%"><?php echo $purchase_rate; ?></td>
                        <td width="50%"><?php echo $purchase_amount;  ?></td>
                    </tr>          
            	</table></td>
            <td style="padding:0;margin:0;"><table width="100%">
				     <tr>
                    	<td width="25%"><?php echo $sale_quantity; ?></td>
                        <td width="25%"><?php echo $sale_rate; ?></td>
                        <td width="50%"><?php echo $sale_amount;  ?></td>
                    </tr>           
            	</table></td>
            <td style="padding:0;margin:0;"><table width="100%">
				       <tr>
                    	<td width="25%"><?php echo $closing_quantity; ?></td>
                        <td width="25%"><?php echo $closing_rate; ?></td>
                        <td width="50%"><?php echo $closing_amount;  ?></td>
                    </tr>          
            	</table></td>
            <td class="no_print"> <a href="<?php echo 'index.php?view=fourth&id='.$id.'&month='.$month_id.'&year='.$year.'&from='.$from.'&to='.$to ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }}}
		 ?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>
