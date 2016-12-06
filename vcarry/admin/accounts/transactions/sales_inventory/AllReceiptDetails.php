<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$job_Card_id=$_GET['id'];
$receipts=getReceiptsForSalesId($job_Card_id);
$sales = getSaleById($job_Card_id);
if($receipts=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}

?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Receipt Details </h4>
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
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />
<a href="<?php echo  WEB_ROOT."admin/customer/index.php?view=details&id=".$sales['to_customer_id']; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
<h4 class="headingAlignment">List of Receipts</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading">Amount</th>
             <th class="heading">Mode</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($receipts as $receipt)
		{
			$by_account_id=$receipt['from_ledger_id'];
			
			$by_account=getLedgerById($by_account_id);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
            <td><?php echo $receipt['amount']; ?>
            </td>
            <td><?php echo $by_account['ledger_name']; ?>
            </td>
          
             
     
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$receipt['receipt_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=edit&lid='.$receipt['receipt_id'].'&type=5'; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?action=delete&lid='.$receipt['receipt_id'].'&type=5';  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div> 


</div>
<div class="clearfix"></div>