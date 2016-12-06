<?php if(!isset($_GET['lid']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}

if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$customer_id=$payment['from_customer_id'];
if(validateForNull($customer_id) && is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
	
}
$to_customer_id=$payment['to_customer_id'];

if(validateForNull($to_customer_id) && is_numeric($to_customer_id))
{
	$to_customer=getCustomerDetailsByCustomerId($to_customer_id);
}
$debit_details=$payment['debit_details'];
$credit_details=$payment['credit_details'];
$debit_details = getDebitJVCDsForJVID($jv_id);

$credit_details = getCreditJVCDsForJVID($jv_id);
$debit_string = "";
$credit_string = "";
foreach($debit_details as $debit_detail)
{
	$debit_detail=$debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id);
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
		$name = $customer['customer_name'];
		
	}
	$debit_string = $debit_string.$name." : ".$amount." <br>";
}

foreach($credit_details as $debit_detail)
{
	$debit_detail = $debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id);
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
		$name = $customer['customer_name'];
		
	}
	$credit_string = $credit_string.$name." : ".$amount." <br>";
}

if(validateForNUll($ledger_id) && is_numeric($ledger_id))
$from_ledger=getLedgerById($ledger_id);

if(validateForNUll($to_ledger_id) && is_numeric($to_ledger_id))
$to_ledger=getLedgerById($to_ledger_id);



?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Multiple Journal Entry </h4>
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
<form onsubmit="return submitMultiJV();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="lid" value="<?php echo $jv_id; ?>" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime($payment['trans_date'])); ?>" autofocus="autofocus" /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>



<?php
$debit_index=1;

 foreach($debit_details as $debit_detail) {
	$debit_detail=$debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id)." | [L".$ledger_customer_id."]";
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
	
		$name = $customer['customer_name'];
		$name = $name." | [C".$ledger_customer_id."]";
		
	}
	 ?>
     <tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text"  class="to_ledger" name="to_ledger_id[]"  value="<?php echo $name; ?>" /></td>
                      <td> Amount :   <input type="text" class="to_ledger_amount" name="to_ledger_id_amount[]" value="<?php echo $amount; ?>"  /></td>
                       <td>     
                <input  <?php if($debit_index<count($debit_details)) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',0)"/><input type="button" <?php if($debit_index==count($debit_details)) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/>
                		</td>
                        </tr>
                        </table>
                </td>            
</tr>
<?php
$debit_index++;
 } ?>


<tr style="display:none;" id="DebitRow">
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text" class="to_ledger1" name="to_ledger_id[]"  /></td>
                      <td> Amount :   <input type="text" class="to_ledger_amount" name="to_ledger_id_amount[]"  /></td>
                       <td>     
                <input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',0)"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/>
                		</td>
                        </tr>
                        </table>
                </td>            
</tr>

<tr>
	<td colspan="2"><hr /></td>
    
</tr>



<?php 
$credit_index=1;
foreach($credit_details as $debit_detail) {
	$debit_detail=$debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id)." | [L".$ledger_customer_id."]";
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
				$name = $customer['customer_name'];
		
		$name = $name." | [C".$ledger_customer_id."]";
		
	}
	 ?>
     <tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text" class="from_ledger" name="from_ledger_id[]" value="<?php echo $name; ?>" /></td>
                      <td> Amount :   <input type="text" class="from_ledger_amount" name="from_ledger_id_amount[]" value="<?php echo $amount; ?>"  /></td>
                       <td>     
                <input  <?php if($credit_index<count($credit_details)) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',1)"/><input type="button" <?php if($credit_index==count($credit_details)) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/>
                		</td>
                        </tr>
                        </table>
                </td>            
</tr>

<?php
$credit_index++;
 } ?>     
<tr style="display:none;" id="CreditRow">
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text" class="from_ledger1" name="from_ledger_id[]"  /></td>
                      <td> Amount :   <input type="text" class="from_ledger_amount" name="from_ledger_id_amount[]"  /></td>
                       <td>     
                <input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',1)"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/>
                		</td>
                        </tr>
                        </table>
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
<input id="disableSubmit" type="submit" value="Update JV"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/accounts/" ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
document.product_count=1;
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
$( ".from_ledger" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/CustomersAndLedgersWithoutPurchaseAndSales.php',
                { term: request.term }, 
                response );
            },
    open: function(event, ui) {  select=false; target_el=event.target; },
	 select: function( event, ui ) {
		 select=true;
			$(event.target).val(ui.item.label);
			
		}
    }).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });	

 function addProductRow(addBtn,url,type)
 {
	
	if(type==0)
	{
    var newTbodyData=document.getElementById('DebitRow').innerHTML;
	rowIndex = document.getElementById('DebitRow').rowIndex;
	newTbodyData=newTbodyData.replace("to_ledger1","to_ledger");
	}
	else
	{
	var newTbodyData=document.getElementById('CreditRow').innerHTML;
	rowIndex = document.getElementById('CreditRow').rowIndex;
	newTbodyData=newTbodyData.replace("from_ledger1","from_ledger");
	}
	var insertTable=document.getElementById('insertInsuranceTable');
	var newTbody = insertTable.insertRow(rowIndex);
	newTbody.innerHTML=newTbodyData;
	var product_count = document.product_count;
	newTbody.id = 'DebitRow'+product_count;
	
	
	//$(addBtn).hide();
	//$(addBtn).next().show();
	//$(addBtn).next().focus();
	document.product_count = document.product_count+1;

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

function deleteProductTr(elem){
	
	var parent1=elem.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
	parent1.innerHTML="";
	}


</script>	