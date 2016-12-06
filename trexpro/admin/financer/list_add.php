<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
$financer_id = $_GET['id'];
else
exit;
$financer_receipts = getUnPaidReceiptsForFinancerId($financer_id);  ?>
<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/financer/index.php?view=list&id=<?php echo $financer_id; ?>"><button class="btn btn-success">View Financer Payments</button></a> </div>
<div class="jvp"><?php if(isset($_SESSION['cRasidReport']['agency_id']) && $_SESSION['cRasidReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cRasidReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Financer Receipt Reports</h4>
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
   <form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="total_amount" placeholder="Only Digits!" value="0" readonly="readonly" />
                            </td>
</tr>

<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
					<select  id="by_ledger" name="to_ledger_id">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listAccountingLedgers();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>"><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<input type="hidden" id="from_ledger_id" name="from_ledger_id" value="L<?php echo $financer_id; ?>" /> 
                   
                    <?php
				echo getLedgerNameFromLedgerId($financer_id);
					 ?>
                   
                            </td>
</tr>


<tr>
<td class="firstColumnStyling">
Remarks : 
</td>

<td>
<textarea name="remarks" id="remarks"></textarea>
</td>
</tr>

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Payment"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/accounts/" ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>


     <?php if(isset($financer_receipts) && is_array($financer_receipts))
	{
	
	
		
		
	 ?>
     <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
     <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
         <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Amount</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Name</label> 
      
       
    </div> 
    <!-- <div id="deleteSelectedDiv"><button id="deleteSelected" class="btn viewBtn">delete selected rows</button></div>    -->
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
        	<th class="heading">No</th>
             <th class="heading file">Date</th>
            <th class="heading">Amount</th>
            <th class="heading">Name</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
       
        <?php
		$total=0;
	
		foreach($financer_receipts as $financer_receipt)
		{
			$receipt_id = $financer_receipt[0];
			$receipt = getReceiptById($receipt_id);
			$customer_id = $receipt['to_customer_id'];
			$customer=getCustomerDetailsByCustomerId($customer_id);
			
		 ?>
         <tr class="resultRow <?php if($settled=="Yes") echo "dangerRow"; ?>">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $receipt_id; ?>" /></td>
        	<td><?php echo ++$i; ?></td>
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?></td>
              <td><?php echo $receipt['amount']; ?>
            </td>
            
            <td><?php echo $customer['customer_name']; ?>
            </td>
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/financer_receipt/index.php?id='.$customer_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
    
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
   
<?php  ?>  
</form>    
</div>
<div class="clearfix"></div>
<script>


</script>