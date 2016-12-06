<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$notice_id=$_GET['id'];
$notice=getWelcomeById($notice_id);
$file_id=$notice['file_id'];
$file = getFileDetailsByFileId($file_id);
$loan=getLoanDetailsByFileId($file_id);
$emiTable=getLoanTableForLoanId($loan['loan_id']);
?>
<div class="insideCoreContent adminContentWrapper wrapper">

<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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


<h4 class="headingAlignment">Emi Details</h4>

<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button><?php  if($loan_emi_id_unpaid!=false && is_numeric($loan_emi_id_unpaid) && ($file['file_status']==1 || $file['file_status']==5)) { ?> <a class="no_print" href="payment/index.php?id=<?php echo $file_id; ?>&state=<?php echo $loan_emi_id_unpaid; ?>" style="font-size:12px; color:#d00;"><button class="btn btn-success" style="float:right;position:relative;top:10px;margin-left:10px;" >+ Add payment</button></a> <?php } ?> <?php  if($loan_emi_id_unpaid!=false && is_numeric($loan_emi_id_unpaid)) { ?>  <a class="no_print" href="payment/index.php?view=addMultiple&id=<?php echo $file_id; ?>&state=<?php echo $loan_emi_id_unpaid; ?>" style="font-size:12px; color:#d00;"><button class="btn btn-success" style="float:right;position:relative;top:10px;" >+ Add Multiple payment</button></a>  <?php } ?> </div>
    <div class="no_print" style="width:100%;">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading date">EMI Date</th>
            <th class="heading">Amount</th>
            <th class="heading" width="20%">Remarks</th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($emiTable as $emi)
		{	
			$emiDetails=$emi['loanDetails'];
			$paymentDetails=$emi['paymentDetails'];
			$acBal=getBalanceForEmi($emiDetails['loan_emi_id']);
			
			$balance=0;
		 ?>
         
         <tr class="resultRow <?php if(date("Y-m-d")>=$emiDetails['actual_emi_date'] && $acBal<0) { ?> dangerRow<?php } ?> <?php if(date("Y-m-d")>=$emiDetails['actual_emi_date'] && $acBal==0) { ?> shantiRow<?php } ?>">
        	<td><?php echo ++$no; ?>
            </td>
            
            <td><?php echo date('d/m/Y',strtotime($emiDetails['actual_emi_date'])); ?>
            </td>
            
            <td><?php echo  number_format($emiDetails['emi_amount']); ?>
            </td>
            
           
            
             <td>
            </td>
            
            
        </tr>

         <?php }?>
         </tbody>
    </table>
    
	</div>  
 <table style="width:100%;" id="to_print" class="to_print adminContentTable"></table>   
</div>
<div class="clearfix"></div>