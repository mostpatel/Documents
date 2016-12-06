<div class="insideCoreContent adminContentWrapper wrapper">
<div class="addDetailsBtnStyling no_print">
<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/receipt/index.php"><button class="btn btn-success"> Receipt</button></a>
	<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/payment/index.php"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/jv/index.php"><button class="btn btn-success"> JV </button></a>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/contra/index.php"><button class="btn btn-success"> Contra</button></a>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/multi_jv/index.php"><button class="btn btn-success"> Multi JV</button></a>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/ledgers/index.php"><button class="btn btn-success"> Add Ledger</button></a>
     <?php $current_company=getCurrentCompanyForUser($_SESSION['adminSession']['admin_id']);
		
		$company_heading = $current_company[2]; ?> <b style="margin-left:40px;"><?php echo $company_heading; ?></b>
</div>
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
<form onsubmit="return submitJV();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['adminSession']['admin_id']))); ?>" autofocus/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>
<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text" class="to_ledger" name="to_ledger_id[]"  /></td>
                      <td> Amount :   <input type="text" class="to_ledger_amount" name="to_ledger_id_amount[]" onchange="calculateDebitTotal()" /></td>
                       <td>     
                <input  type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/CustomersAndLedgersWithoutPurchaseAndSales.php',0)"/>
                		</td>
                        </tr>
                        </table>
                </td>            
</tr>

<tr style="display:none;" id="DebitRow">
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text" class="to_ledger1" name="to_ledger_id[]"  /></td>
                      <td> Amount :   <input type="text" class="to_ledger_amount" name="to_ledger_id_amount[]"  onchange="calculateDebitTotal()" /></td>
                       <td>     
                <input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',0)"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/>
                		</td>
                        </tr>
                        </table>
                </td>            
</tr>
<tr>
	<td>Debit Total : </td>
    <td><span id="debit_total_span"><?php echo 0; ?></span></td>
</tr>

<tr>
	<td colspan="2"><hr /></td>
    
</tr>

<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text" class="from_ledger" name="from_ledger_id[]"  /></td>
                      <td> Amount :   <input type="text" class="from_ledger_amount" name="from_ledger_id_amount[]" onchange="calculateCreditTotal()"  /></td>
                       <td>     
                <input  type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/CustomersAndLedgersWithoutPurchaseAndSales.php',1)"/>
                		</td>
                        </tr>
                        </table>
                </td>            
</tr>

<tr style="display:none;" id="CreditRow">
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                <table>
                <tr>
                	<td>
					  <input type="text" class="from_ledger1" name="from_ledger_id[]"  /></td>
                      <td> Amount :   <input type="text" class="from_ledger_amount" name="from_ledger_id_amount[]" onchange="calculateCreditTotal()" /></td>
                       <td>     
                <input  <?php if($i<5) { ?> style="display:none;"  <?php } ?> type="button" title="add more product" value="+" class="btn btn-success addProductbtn" onclick="addProductRow(this,'<?php echo WEB_ROOT; ?>json/inventory_item.php',1)"/><input type="button" <?php if($i==5) { ?> style="display:none;"  <?php } ?>value="-" title="delete this entry"  class="btn btn-danger deleteProductbtn" onclick="deleteProductTr(this)"/>
                		</td>
                        </tr>
                        </table>
                </td>            
</tr>

<tr>
	<td>Credit Total : </td>
    <td ><span id="credit_total_span"><?php echo 0; ?></span></td>
</tr>

<td class="firstColumnStyling">
Remarks : 
</td>

<td>
<textarea name="remarks" id="remarks"></textarea>
</td>
</tr>

<?php if(QTY_IN_JV==1) { ?>
</tr>


<td class="firstColumnStyling">
Quantity : 
</td>

<td>
<input type="text" name="qty" id="quantity" value="0"  />
</td>
</tr>
<?php } ?>
 

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add JV"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/accounts/" ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
document.product_count=1;
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
 
 function calculateCreditTotal()
 {

	 total_amount = 0;
	 $('.from_ledger_amount').each(function(index, element) {
        amount=element.value;
		
		if(!isNaN(amount) && amount>0)
		{
			
			 total_amount = parseFloat(total_amount) + parseFloat(amount);
		}
    });
	 
	 document.getElementById('credit_total_span').innerHTML=total_amount;
	 return total_amount;
}

function calculateDebitTotal()
 {

	 total_amount = 0;
	 $('.to_ledger_amount').each(function(index, element) {
        amount=element.value;
		
		if(!isNaN(amount) && amount>0)
		{
			
			 total_amount = parseFloat(total_amount) + parseFloat(amount);
		}
    });
	 
	 document.getElementById('debit_total_span').innerHTML=total_amount;
	  return total_amount;
}

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
	if(type==0)
	{
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
	else
	{
	
		$( ".from_ledger" ).autocomplete({
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
	
}

function deleteProductTr(elem){
	
	var parent1=elem.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
	parent1.innerHTML="";
	}
function submitJV()
{
	
	debit_total=calculateDebitTotal();
	credit_total = calculateCreditTotal();
	
	if(debit_total!=credit_total)
	{alert('Credit And Debit Total does not match!');
	return false;}
	
}

</script>	