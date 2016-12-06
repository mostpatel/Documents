<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$customer_id=$_GET['id'];
$customer=getCustomerDetailsByCustomerId($customer_id);
if(is_array($customer) && $customer!="error")
{
	if($customer!="error")
	{
	$oc_id = $customer['oc_id'];
	$account_settings = getAccountsSettingsForOC($oc_id);	
	$remarks=listRemainderForCustomer($customer_id);
	$latest_transaction_next_day_date = getNextDate(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']));
	$all_transaction=getAllTransactionsForLedgerId('C'.$customer_id,NULL,$account_settings['ac_starting_date'],$latest_transaction_next_day_date);
	$from_lrs = getFromLRsForCustomer($customer_id);
	$to_lrs = getToLRsForCustomer($customer_id);
	}
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

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

<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/receipt/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Receipt</button></a> <a href="<?php echo 'index.php?action=deleteCustomer&lid='.$customer_id; ?>"><button title="Delete this entry" class="btn delBtn btn-danger">Delete Customer</button></a>  <a href="<?php echo WEB_ROOT; ?>admin/customer/payment/index.php?id=<?php echo $customer_id; ?>"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/jv/index.php"><button class="btn btn-success"> JV </button></a></div>

<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=addRemainder&id=<?php echo $customer_id; ?>"><button class="btn btn-success">+ Add / View Reminder</button></a> <span class="noOfRemainders"><b><?php if($remarks!=false) echo count($remarks)." Pending Reminders!"; ?></b></span>  </div>




<div class="detailStyling" style="min-height:300px">

<h4 class="headingAlignment">Customer's Details</h4>

<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td width="150px" class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php echo $customer['customer_name']; ?>					
                            
</td>
</tr>

<tr>
<td>
Address : 
</td>

<td>

                             <?php echo $customer['customer_address']; ?>					
                            
</td>
</tr>


<tr>
<td>City : </td>
				<td>

                             <?php $cid = $customer['city_id'];
							 		
							       $cityDetails = getCityByID($cid);
								   echo $cityDetails['city_name'];
							?>
                            </td>
</tr>

<tr>
<td>Pincode : </td>
<td>

                             <?php if($customer['customer_pincode']!=0) echo $customer['customer_pincode']; else echo "NA"; ?>					
                          
</td>
</tr>

 <tr id="addcontactTrCustomer">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                <?php
                            $contactNumbers = $customer['contact_no'];
							
                           for($z=0;$z<count($contactNumbers);$z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." | ";				
                              } ?>
                </td>
            </tr>
<tr>
<td>PAN No : </td>
<td><?php if(validateForNull($customer['pan_no'])) echo $customer['pan_no']; else echo "NA"; ?></td>
</tr>


<tr>
<td>TIN no : </td>
<td><?php if(validateForNull($customer['tin_no'])) echo $customer['tin_no'];  else echo "NA"; ?></td>
</tr>

<tr>
<td>Opening Balance : </td>
<td><?php echo $customer['opening_balance'];  if(isset($customer['opening_cd']) && $customer['opening_cd']==0) { echo " Rs DEBIT"; } else echo " Rs CREDIT"; ?></td>
</tr>

<tr>
<td>Current Balance (<?php echo date('d/m/Y',strtotime(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']))); ?>): </td>
<td><?php $current_balance = getOpeningBalanceForLedgerForDate('C'.$customer['customer_id'],getNextDate(getLatestTransactionDateForLedgerId('C'.$customer['customer_id']))); if($current_balance>=0) echo round($current_balance,2); else echo round(-$current_balance,2);  if(isset($current_balance) && $current_balance>=0) { echo " Rs DEBIT"; } else echo " Rs CREDIT"; ?></td>
</tr>

<tr>
<td>Notes : </td>
<td><?php if(validateForNull($customer['notes'])) echo $customer['notes'];  else echo "NA"; ?></td>
</tr>
        

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=customerDetails&id='.$customer_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&id='.$customer_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            

</table>
</div>

<?php if($remarks!=false && is_array($remarks) && count($remarks)>0)
{ ?>
<div class="detailStyling">

<h4 class="headingAlignment">Remarks Details </h4>


<table class="insertTableStyling detailStylingTable">

<?php foreach($remarks as $remark)
{
?> 
<tr>
    <td class="firstColumnStyling">
   <?php  if($remark['date']=='1970-01-01' || $remark['date']=='0000-00-00')  {?> Remark : <?php } else {  echo date('d/m/Y',strtotime($remark['date'])); } ?> 
    </td>
    
    <td>
     
                                 <?php echo $remark['remarks']; ?>					
                               
    </td>
</tr>

<?php
}
 ?>
 
 
 
 

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT; ?>admin/party/index.php?view=addRemainder&id=<?php echo $customer_id; ?>&state=<?php echo $customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr> 




</table>
</div>

 
<?php } ?>

<?php if($from_lrs && is_array($from_lrs) && count($from_lrs)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Lorry Receipts From Customer</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable1" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading file">LR No</th>
            <th class="heading file">LR Date</th>
            <th class="heading">From Branch</th>
            <th class="heading ">To Branch</th>
          
            <th class="heading">To Customer</th>
             <th class="heading">To pay</th>
             <th class="heading">Paid</th>
              <th class="heading">To Be Billed</th>
            <th class="heading">Total Freight</th>
              <th class="heading">Amt Received</th>
                <th class="heading">Amt Left</th>
             <th class="heading">Tax</th>
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($from_lrs as $emi)
		{

		$total_receipt_amount = getTotalReceiptAmountForLRId($emi['lr_id']);
		 ?>
           <tr class="resultRow">
        
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $emi['lr_no']; ?></td>
             <td><?php echo date('d/m/Y',strtotime($emi['lr_date'])); ?></td>
              <td><?php  echo $emi['from_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['to_branch_ledger_name']; ?>
            </td>
          
             <td><?php  echo $emi['to_customer_name']; ?>
            </td>
             <td><?php echo $emi['to_pay']; ?>
            </td>
             <td><?php echo $emi['paid']; ?>
            </td>
             <td><?php echo $emi['to_be_billed']; ?>
            </td>
            <td><?php echo $emi['total_freight']; ?>
            </td>
             <td align="center"><?php if(is_numeric($total_receipt_amount)) echo $total_receipt_amount; else echo 0; ?>
             <?php if(is_numeric($total_receipt_amount)) { ?> <a href="<?php  echo WEB_ROOT.'admin/customer/lr_receipt/index.php?&id='.$customer_id.'&lid='.$emi['lr_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Receipts</span></button></a> <?php } ?>
            </td>
             <td align="center"><?php  if(is_numeric($total_receipt_amount)) echo $emi['to_be_billed']-$total_receipt_amount; else echo $emi['to_be_billed']; ?> <?php if(!is_numeric($total_receipt_amount) || ($emi['to_be_billed']-$total_receipt_amount)>0) { ?> <a href="<?php  echo WEB_ROOT.'admin/customer/lr_receipt/index.php?&id='.$customer_id.'&lid='.$emi['lr_id']; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Receipt</span></button></a> <?php } ?>
            </td>
             <td><?php if(is_numeric($emi['total_tax'])) echo $emi['total_tax']; else echo 0; ?>
            </td>
            <td class="payment_amount"><?php   echo $emi['remarks'] ?>
            </td>
       		
             <td class="no_print"> <a class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/lr/index.php?view=details&id='.$emi['lr_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print1" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>

<?php if($to_lrs && is_array($to_lrs) && count($to_lrs)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Lorry Receipts To Customer</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading file">LR No</th>
            <th class="heading file">LR Date</th>
            <th class="heading">From Branch</th>
            <th class="heading ">To Branch</th>
            <th class="heading">From Customer</th>
          
            <th class="heading">To pay</th>
             <th class="heading">Paid</th>
              <th class="heading">To Be Billed</th>
            <th class="heading">Total Freight</th>
            <th class="heading">Tax</th>
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($to_lrs as $emi)
		{
		
	
		 ?>
           <tr class="resultRow">
        
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $emi['lr_no']; ?></td>
             <td><?php echo date('d/m/Y',strtotime($emi['lr_date'])); ?></td>
              <td><?php  echo $emi['from_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['to_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['from_customer_name']; ?>
            </td>
             
             <td><?php echo $emi['to_pay']; ?>
            </td>
             <td><?php echo $emi['paid']; ?>
            </td>
             <td><?php echo $emi['to_be_billed']; ?>
            </td>
            <td><?php echo $emi['total_freight']; ?>
            </td>
            <td><?php if(is_numeric($emi['total_tax'])) echo $emi['total_tax']; else echo 0; ?>
            </td>
            <td class="payment_amount"><?php   echo $emi['remarks'] ?>
            </td>
       
             <td class="no_print"> <a  class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/lr/index.php?view=details&id='.$emi['lr_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print3" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>


<?php if($delivery_challans && is_array($delivery_challans) && count($delivery_challans)>0) { ?>


<div class="detailStyling" style="width:100%;">


<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Delivery Challans</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
  <table id="adminContentTable" class="adminContentTable">

    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
             <th class="heading">Challan No</th>
             <th class="heading">Model</th>
            <th class="heading">Engine No </th>
             <th class="heading">Chasis No </th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($delivery_challans as $delivery_challan)
		{
			$vehicle_id = $delivery_challan['vehicle_id'];
			$vehicle = getVehicleById($vehicle_id);
			$invoice = getVehicleInvoiceByDeliveryChallanId($delivery_challan['delivery_challan_id']);
			$form21 = getSaleCertByDeliveryChallanId($delivery_challan['delivery_challan_id']);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($delivery_challan['delivery_date'])); ?>
            </td>
            <td><?php echo $delivery_challan['challan_no']; ?>
            </td>
            <td><?php echo getModelNameById($vehicle['model_id']); ?>
            </td>
            <td><?php echo $vehicle['vehicle_engine_no']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_chasis_no']; ?>
            </td>
              <td class="no_print" width="120px;"> <a href="<?php if($invoice){ echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.$delivery_challan['delivery_challan_id'];} else echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?id='.$delivery_challan['delivery_challan_id']; ?>"><button style="width:120px;" title="View this entry" class="btn <?php if($invoice){ ?> btn-success<?php }else { ?> btn-warning<?php } ?>"><span class=""><?php if($invoice){ ?> View Invoice<?php } else { ?>Add Invoice<?php } ?></span></button></a> 
              <a style="margin-top:10px;position:relative;display:block;" href="<?php if($form21){echo WEB_ROOT.'admin/customer/vehicle_form21/index.php?view=details&id='.$delivery_challan['delivery_challan_id'];} else echo WEB_ROOT.'admin/customer/vehicle_form21/index.php?id='.$delivery_challan['delivery_challan_id']; ?>"><button style="width:120px;" title="View this entry" class="btn<?php if($form21){ ?> btn-success <?php }else{ ?> btn-warning<?php } ?>"><span class=""><?php if($form21){ ?> View Form  21<?php }else { ?> Add Form 21 <?php } ?></span></button></a>
            </td>
             
              <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/delivery_challan/index.php?view=details&id='.$delivery_challan['delivery_challan_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/delivery_challan/index.php?view=edit&id='.$delivery_challan['delivery_challan_id']; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/delivery_challan/index.php?action=delete&id='.$delivery_challan['delivery_challan_id']."&state=".$customer_id;; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>

<?php if($vehicle_invoices && is_array($vehicle_invoices) && count($vehicle_invoices)>0) { ?>
<div class="detailStyling" style="width:100%;">
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Vehicle Invoices</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable2" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
             <th class="heading">Invoice No</th>
             <th class="heading">Model</th>
            <th class="heading">Reg No </th>
             <th class="heading">Engine No </th>
             <th class="heading">Chasis No </th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($vehicle_invoices as $delivery_challan)
		{
			
			$vehicle_id = $delivery_challan['vehicle_id'];
			$vehicle = getVehicleById($vehicle_id);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($delivery_challan['invoice_date'])); ?>
            </td>
            <td><?php echo $delivery_challan['invoice_no']; ?>
            </td>
            <td><?php echo getModelNameById($vehicle['model_id']); ?>
            </td>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
            <td><?php echo $vehicle['vehicle_engine_no']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_chasis_no']; ?>
            </td>
     
              <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=details&id='.$delivery_challan['delivery_challan_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?view=edit&id='.$delivery_challan['delivery_challan_id'].'&state='.$vehicle_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_invoice/index.php?action=delete&id='.$delivery_challan['vehicle_invoice_id']."&state=".$customer_id;  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print2" class="to_print adminContentTable"></table> 
</div> 
<?php } ?>
</div>
<div class="clearfix"></div>
<script>
document.no_of_tables=3;
</script>