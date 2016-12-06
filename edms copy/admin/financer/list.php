<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
$financer_id = $_GET['id'];
else
exit;
$financer_receipts = getAllPaymentsForFinancer($financer_id);  ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">List of Financer Payments</h4>
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
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Payment Date</th>
            <th class="heading">Receipts</th>
            <th class="heading">Amount</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		if($financer_receipts!=false)
		{ 
		foreach($financer_receipts as $payment)
		{
			$receipt_ids_array=getReceiptsForFinancerPaymentId($payment['payment_id']);
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
            </td>
            <td><?php foreach($receipt_ids_array as $receipt){
				echo $receipt['customer_name']." ".$receipt['amount']."<br>";
				}  ?>
            </td> 
             <td><?php echo number_format($payment['amount'],2); ?>
            </td>
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$agencyDetails['purchase_id']; ?>"><button title="View this entry" class="btn editBtn viewBtn "><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$financer_id."&state=".$payment['payment_id']; ?>"><button title="Edit this entry" class="btn editBtn splEditBtn "><span class="edit">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&id='.$payment['payment_id']."&financer_id=".$financer_id; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table> 

</div>
<div class="clearfix"></div>