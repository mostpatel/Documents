<?php 
if(!isset($_GET['lid']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$receipt_id=$_GET['lid'];
$payment=getReceiptById($receipt_id);

$total_amount = 0;
$receipt_ids = "";
$i=0;
if(isset($payment['receipt_id']) && is_numeric($payment['receipt_id']))
{
$receipt = $payment;
$payment = array();
$payment[]=$receipt;
}


foreach($payment as $p)
{
	if($i!=0)
	$receipt_ids = $receipt_ids.",".$p['receipt_id'];
	else
	$receipt_ids = $receipt_ids.$p['receipt_id'];
	$total_amount = $total_amount + $p['amount'];
	$i++;
}

$extra_payment_details = getReceiptDetailsForReceiptId($payment['parent_id']);
if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$by_account_id=$payment[0]['from_ledger_id'];
$by_account=getLedgerById($by_account_id);	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Receipt </h4>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="oc_id" id="oc_id" value="<?php echo $receipt['oc_id']; ?>" />
<input type="hidden" id="lid" name="lid" value="<?php echo $receipt_id; ?>"  />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime($payment[0]['trans_date'])); ?>" autofocus/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>



<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<select id="by_ledger" name="from_ledger_id" onchange="createChequeDetails()">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listAccountingLedgers($payment['oc_id']);
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>"  <?php if($bank_cash_ledger['ledger_id']==$by_account_id){ ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>
</table>
<table id="chequePaymentTable" class="insertTableStyling no_print" <?php if(!$extra_payment_details){ ?> style="display:none;" <?php } else { ?> style="display:table" <?php } ?>>

<tr>
<td>Payment Mode<span class="requiredField">* </span> : </td>
				<td>
					<select  id="payment_mode" name="payment_mode_id" >
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=getAllPaymentModes();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['payment_mode_id']; ?>" <?php if($extra_payment_details['payment_mode_id']==$bank_cash_ledger['payment_mode_id']) { ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['payment_mode']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!"  value="<?php  if($extra_payment_details) echo $extra_payment_details['bank_name']; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!"  value="<?php  if($extra_payment_details) echo $extra_payment_details['branch_name']; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!"  value="<?php  if($extra_payment_details) echo $extra_payment_details['chq_no']; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" id="cheque_date" class="datepicker3" placeholder="click to select date!" value="<?php  if($extra_payment_details) echo date('d/m/Y',strtotime($extra_payment_details['chq_date'])); ?>"  /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>
</table>

<table class="insertTableStyling no_print" id="regenerate_table">
<tbody id="regenrate_tbody" style="display:none;">
<tr>
<td colspan="2"><h4 class="headingAlignment"> Credit Details </h4></td>
</tr>
<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount[]" class="amount" placeholder="Only Digits!" value="<?php echo $remaining_amount; ?>" /><span class="DateError customError">Amount Should less than <?php echo -$balance; ?> Rs. !</span>
                            </td>
</tr>
<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                <?php if(is_numeric($to_ledger_id) || is_numeric($to_customer_id)) { ?>
					<input type="hidden" class="to_ledger_id" name="to_ledger_id[]" value="<?php if(is_numeric($to_ledger_id)) echo "L".$to_ledger_id; else echo "C".$to_customer_id; ?>"  /> 
                 <?php }else{ ?>   
                 <input type="text" class="to_ledger1" name="to_ledger_id[]" />
                    <?php } ?>
                            </td>
</tr>
<tr>
<td width="220px">Receipt Type<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select class="ref_type" name="ref_type[]" onchange="changeRefFeildMulti(this)" >
                    	<option value="0" selected="selected">NEW</option>
                  		<option value="1" >Advance</option>
                        <option value="2" >Against Sales</option>
                        <option value="3" >On Account</option>
                    </select>
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_new">
<td> </td>
				<td>
					
                  	
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_against">
<td>Receipt Ref<span class="requiredField">* </span> : </td>
				<td>
					<select type="text" class="pay_ref_aganist" name="ref[]" >
                    <option value="-1">-- Please Select --</option>
                    </select> 
                   
                </td>
                
</tr>

</tbody>


<?php foreach($payment as $p) {
	
$customer_id=$p['to_customer_id'];
if(validateForNull($customer_id) && is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
	$ledger_customer_id='C'.$customer_id;
	$name = $customer['customer_name']." | [".$ledger_customer_id."]";
}
$ledger_id=$p['to_ledger_id'];
if(validateForNUll($ledger_id) && is_numeric($ledger_id))
{
$to_ledger=getLedgerById($ledger_id);
$ledger_customer_id='L'.$ledger_id;
$name = $to_ledger['ledger_name']." | [".$ledger_customer_id."]";
} ?>
<tbody id="regenrate_tbody">
<tr>
<td colspan="2"><h4 class="headingAlignment"> Credit Details </h4></td>
</tr>
<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount[]" class="amount" placeholder="Only Digits!" value="<?php echo $p['amount']; ?>" /><span class="DateError customError">Amount Should less than <?php echo -$balance; ?> Rs. !</span>
                            </td>
</tr>
<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                
                 <input type="text" class="to_ledger" name="to_ledger_id[]" value="<?php echo $name; ?>" />
                   
                            </td>
</tr>
<tr>
<td width="220px">Receipt Type<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select class="ref_type" name="ref_type[]" onchange="changeRefFeildMulti(this)" >
                    	<option value="0" <?php if($p['receipt_ref_type']==0) { ?> selected="selected" <?php } ?>>NEW</option>
                  		<option value="1"  <?php if($p['receipt_ref_type']==1) { ?> selected="selected" <?php } ?> >Advance</option>
                        <option value="2"  <?php if($p['receipt_ref_type']==2 || $p['auto_rasid_type']==5) { ?> selected="selected" <?php } ?> >Against Sales</option>
                        <option value="3"  <?php if($p['receipt_ref_type']==3) { ?> selected="selected" <?php } ?> >On Account</option>
                    </select>
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_new">
<td> </td>
				<td>
					
                  	
                            </td>
</tr>

<tr  <?php if($p['receipt_ref_type']!=2 && $p['auto_rasid_type']!=5) { ?> style="display:none;" <?php } ?> id="pay_ref_against">
<td>Receipt Ref<span class="requiredField">* </span> : </td>
				<td>
					<select type="text" class="pay_ref_aganist" name="ref[]" >
                    <option value="-1">-- Please Select --</option>
                    <?php if($p['receipt_ref_type']==2 ||$p['auto_rasid_type']==5) {
					$sales = generalSalesReports($ledger_customer_id,NULL,$p['trans_date'],NULL,NULL,NULL,1,$receipt_ids);
						  
					foreach($sales as $s)
					{	 
					$label = $s['invoice_no']." ".date("d/m/Y",strtotime($s['trans_date']))." ".$s['outstanding_amount']." Rs";
						 ?>
					<option value="<?php echo $s['sales_id'] ?>" <?php if($p['auto_id']==$s['sales_id']) { ?> selected="selected" <?php } ?>><?php echo $label; ?></option>
					
					<?php }} ?>
                    </select> 
                   
                </td>
                
</tr>

</tbody>
<?php } ?>
</table>

<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="260px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add More Credit Details" id="addCreditDetailsBtn"/></td>
</tr>     
</table>

<table>
<tr>

<td class="firstColumnStyling" width="220px">
Remarks (ctrl + g to change english/gujarati) : 
</td>

<td>
<textarea name="remarks" id="transliterateTextarea"><?php echo $p['remarks']; ?></textarea>
</td>
</tr>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="edit Receipt"  class="btn btn-warning">
<a href="<?php if(isset($_GET['sales_id']) && is_numeric($_GET['sales_id']) && is_numeric($to_customer_id)) { echo WEB_ROOT."admin/customer/index.php?view=details&id=".$to_customer_id; } else echo  WEB_ROOT."admin/accounts/"; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
document.disablePeriodModal = 1;
 $( ".to_ledger" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/CustomersAndLedgersWithoutPurchaseAndSales.php',
                { term: request.term }, 
                response );
            },
    open: function(event, ui) {   select=false;   target_el=event.target;   },
	 select: function( event, ui ) {
		 select=true;
			$(event.target).val(ui.item.label);
			
		}
    }).blur(function(){
	
    if(!select)
    {
		
		$(event.target).val("");
    }
 });	

 function createChequeDetails()
{	
    var ledger_id =document.getElementById('by_ledger').value;
	
	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
	
    var is_bank_ledger=xmlhttp1.responseText;
	
// Before adding new we must remove previously loaded elements

	if(is_bank_ledger==1)
	$('#chequePaymentTable').show();
	else
	$('#chequePaymentTable').hide();
    }
  }
  var url=document.web_root+'json/ledger_head_id_bank.php?id='+ledger_id;
   xmlhttp1.open('GET', url, true );    
  xmlhttp1.send(null);
}

$('#addCreditDetailsBtn').click(function(e) {
  addCreditDetails();

});

function addCreditDetails()
{
	  var regenerate_table = $('#regenerate_table')[0];
	var regenerate_tbody = $('#regenrate_tbody')[0];
	var regenerate_tbody_content = regenerate_tbody.innerHTML;
	var new_tbody=document.createElement('tbody');
	regenerate_tbody_content=regenerate_tbody_content.replace("to_ledger1","to_ledger");

	new_tbody.innerHTML = regenerate_tbody_content;
	regenerate_table.appendChild(new_tbody);
	
	$( ".to_ledger" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/CustomersAndLedgersWithoutPurchaseAndSales.php',
                { term: request.term }, 
                response );
            },
    open: function(event, ui) {   select=false;   target_el=event.target;   },
	 select: function( event, ui ) {
		 select=true;
			$(event.target).val(ui.item.label);
			
		}
    }).blur(function(){
	
    if(!select)
    {
		
		$(event.target).val("");
    }
 });		
}
  
</script>