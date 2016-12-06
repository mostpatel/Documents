<?php if(!isset($_GET['id']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}
$delivery_challan_id=$_GET['id'];
$trip_memo=getTripInvoiceById($delivery_challan_id);

if(is_array($trip_memo) && $trip_memo)
{
	$trips=getTripsByInvoiceId($delivery_challan_id);
	$jv_id = getJVIdForInvoice($delivery_challan_id);
	$jv = getJVById($jv_id);
	$jv_cr_dr_details = getDebitCreditDetailsForJv($jv_id);
	
	$branch_wise=getBranchWiseTotalForInvoice($delivery_challan_id);

	$agent_wise=getAgentWiseTotalForInvoice($delivery_challan_id);

	$expense_wise=getExpenseWiseTotalForInvoice($delivery_challan_id);
	
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
<div class="detailStyling" style="min-height:300px;z-index:1000;">
<h4 class="headingAlignment">Invoice Details</h4>
<table id="insertVehicleTable" class="insertTableStyling detailStylingTable">

<tr>
<td width="230px;">Invoice Date : </td>
				<td>
					
                  <?php echo date('d/m/Y',strtotime($trip_memo['invoice_date'])); ?>
                            </td>
</tr>

<tr>
<td>Invoice No : </td>
				<td>
					
                <?php echo $trip_memo['invoice_no']; ?>
                            </td>
</tr>

<tr>
<td>
Remarks : 
</td>

<td>
<?php echo $trip_memo['remarks']; ?>
</td>
</tr>

<tr>
	<td></td>
  <td class="no_print">
            
          <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$delivery_challan_id; ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
           <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&id='.$delivery_challan_id; ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">X</span></button></a>
             <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$customer['customer_id'] ?>"><button title="Back" class="btn btn-success">Back</button></a>
            </td>
</tr> 


</table>
</div>
<h4 class="headingAlignment">Overview</h4>
<div class="detailStyling" style="min-height:300px">
<table id="insertVehicleTable" class="adminContentTable">
<thead>
<tr>
	<th class="heading">Branch</th>
    <th class="heading">Amount</th>
    <th class="heading">Balance</th>
</tr>
</thead>
<tbody>
<?php foreach($branch_wise as $branch) { ?>
<tr>
<td width="230px;"><?php echo $branch['ledger_name'] ?>  </td>
				<td>
					
                  <?php echo $branch['amount']." Dr"; ?>
                            </td>
                <td>
					
                  <?php echo ($branch['amount']-$branch['paid_amount'])." Dr"; ?>
                            </td>            
</tr>
<?php } ?>
</tbody>
<thead>
<tr>
	<th class="heading">Agenct</th>
    <th class="heading">Amount</th>
    <th class="heading">Balance</th>
</tr>
</thead>
<tbody>
<?php foreach($agent_wise as $branch) { ?>
<tr>
<td width="230px;"><?php echo $branch['ledger_name'] ?>  </td>
				<td>
					
                  <?php echo $branch['amount']." Cr"; ?>
                            </td>
                <td>
					
                  <?php echo ($branch['amount']-$branch['paid_amount'])." Cr"; ?>
                            </td>            
</tr>
<?php } ?>
</tbody>
<thead>
<tr>
	<th class="heading">Expense</th>
    <th class="heading">Amount</th>
    <th class="heading">Balance</th>
</tr>
</thead>
<tbody>
<?php foreach($expense_wise as $branch) { ?>
<tr>
<td width="230px;"><?php echo $branch['ledger_name'] ?>  </td>
				<td>
					
                  <?php echo $branch['amount']." Cr"; ?>
                            </td>
                <td>
					
                  <?php echo ($branch['amount']-$branch['paid_amount'])." Cr"; ?>
                            </td>            
</tr>
<?php } ?>
</tbody>




</table>
</div>
<h4 class="headingAlignment no_print">Trip Memo Details</h4>
 <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
       
        	<th class="heading">No</th>
            <th class="heading file">Trip Memo No</th>
            <th class="heading file">Memo Date</th>
            <th class="heading">From Branch</th>
            <th class="heading ">To Branch</th>
            <th class="heading">Truck No</th>
            <th class="heading">Driver</th>
            <th class="heading">Total Freight</th>
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
       
        <?php
		$total=0;
		$untripped_lrs=getTripsByInvoiceId($delivery_challan_id);
		foreach($untripped_lrs as $emi)
		{
		
		$truck_no = getTruckNoById($emi['truck_id']);
		$driver = getLedgerNameFromLedgerId($emi['driver_id']);	
		$total_freight = getTotalFreightForTripMemo($emi['trip_memo_id']);
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $emi['trip_memo_no']; ?></td>
             <td><?php echo date('d/m/Y',strtotime($emi['trip_date'])); ?></td>
              <td><?php  echo $emi['from_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['to_branch_ledger_name']; ?>
            </td>
            
             <td><?php  echo $truck_no; ?>
            </td>
            <td><?php echo $driver; ?>
            </td>
            <td><?php echo $total_freight; ?></td>
            <td class="payment_amount"><?php   echo $emi['remarks'] ?>
            </td>
       
             <td class="no_print"> <a target="_blank" class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/trip_memo/index.php?view=details&id='.$emi['trip_memo_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }  ?>
         </tbody>
    </table>
    
<h4 style="margin-top:100px;display:block;" class="headingAlignment no_print">Credit/Debit Details</h4>
 <table id="adminContentTable"  class="adminContentTable no_print">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Particulars</th>
            <th class="heading">Debit Amount</th>
            <th class="heading">Credit Amount</th>
            <th class="heading">Payment / Receipt</th>
            <th class="heading">Status</th>
        </tr>
    </thead>
    <tbody>
       
        <?php
		$i=0;
		$total=0;
		$debit_total = 0;
		$credit_total = 0;
		foreach($jv_cr_dr_details as $emi)
		{
		
		$jv_type=$emi['type'];
		if($jv_type==0)
		{
			if(is_numeric($emi['to_ledger_id']))
			{
			$ledger_name = getLedgerNameFromLedgerId($emi['to_ledger_id']);
			$ledger_id = "L".$emi['to_ledger_id'];
			}
			else
			{
			$customer=getCustomerDetailsByCustomerId($emi['to_customer_id']);	
			$ledger_name = $customer['customer_name'];
			$ledger_id = 'C'.$customer['customer_id'];
			}
		$receipt_amount = getTotalReceiptAmountForInvoiceLedger($delivery_challan_id,$ledger_id);	
		$debit_total = $debit_total + $emi['amount'];
		}
		else if($jv_type==1)
		{
			if(is_numeric($emi['from_ledger_id']))
			{
			$ledger_name = getLedgerNameFromLedgerId($emi['from_ledger_id']);
			$ledger_id = "L".$emi['from_ledger_id'];
			}
			else
			{
			$customer=getCustomerDetailsByCustomerId($emi['from_customer_id']);	
			$ledger_name = $customer['customer_name'];
			$ledger_id = 'C'.$customer['customer_id'];
			}
		$payment_amount = getTotalPaymentAmountForInvoiceLedger($delivery_challan_id,$ledger_id);	
		$credit_total = $credit_total + $emi['amount'];	
		}
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $ledger_name; ?></td>
             <td><?php if($jv_type==0) echo $emi['amount']." Dr"; ?></td>
              <td><?php  if($jv_type==1) echo $emi['amount']." Cr"; ?></td>
             <td><?php  if($jv_type==0) { if(($emi['amount'] -  $receipt_amount)>0) { ?>    <a class="no_print" href="<?php  echo WEB_ROOT.'admin/transportation/receipt/index.php?id='.$delivery_challan_id.'&state='.$ledger_id; ?>"><button style="width:120px;" title="Add Receipt" class="btn  btn-success"><span class="">Add Receipt</span></button></a> <?php }  echo $receipt_amount." Rs Received"; } else {  ?> <a class="no_print" href="<?php  echo WEB_ROOT.'admin/transportation/payment/index.php?id='.$delivery_challan_id.'&state='.$ledger_id; ?>"><button style="width:120px;" title="Add Receipt" class="btn  btn-warning"><span class="">Add Payment</span></button></a> <?php   echo $payment_amount." Rs Paid"; } ?></td>
             <td><?php if($jv_type==0) echo $emi['amount'] -  $receipt_amount." Dr";  else echo $emi['amount'] -  $payment_amount." Cr";  ?></td>
            
       
             
           
            
          
  
        </tr>
         <?php }  ?>
         <tr class="resultRow">
         	<td><?php echo ++$i; ?></td>
            <td><b>TOTAL</b></td>
             <td><b><?php  echo $debit_total; ?></b></td>
              <td><b><?php echo $credit_total; ?></b></td>
             <td></td>
             <td></td>
         </tr>
         </tbody>
    </table>    



</div>
<div class="clearfix"></div>
<script>
$('.myLink').click(function(e) {
    
	  var winPop = window.open(this.href);  //`this` is reference to link, get href
        return false;  //prevent click event from clicking the link
});

</script>
<script>
function checkFreights()
{
	
	var total_freight = document.getElementById('total_freight').value;
	var to_pay = document.getElementById('to_pay').value;
	var paid = document.getElementById('paid').value;
	var to_be_billed = document.getElementById('to_be_billed').value;
	var total1=0
	if(!isNaN(parseFloat(to_pay)))
	total1 = total1 + parseFloat(to_pay);
	
	if(!isNaN(parseFloat(paid)))
	total1 = total1 + parseFloat(paid);
	
	if(!isNaN(parseFloat(to_be_billed)))
	total1 = total1 + parseFloat(to_be_billed);
	
	var total_freight =parseFloat(total_freight);
	if(total1 == total_freight )
	{
		return true;
	}
	alert('Total Freight does not match total of To Pay, Paid and To Be Billed!');
	return false;
}

function calculateTotalTax()
{
	var total_tax = 0;
	var tax_group_elements=$('.tax_group');
	$('.lr_freight').each(function(index, element) {
		if(!isNaN(element.value) && element.value!=null && element.value!='')
		{
        freight = parseInt(element.value);
		var tax_group_el = tax_group_elements[index];
		var tax_val = tax_group_el.options[tax_group_el.selectedIndex].id;
			if(tax_val!=-1)
			{
				tax = tax_val.replace('tax','');
				if(!isNaN(tax))
				var tax_percent = tax;
				else tax_percent = 0;
			}
        total_tax = total_tax + ((freight*tax_percent)/100)
		}
    });
	
	document.getElementById('total_tax').value = total_tax;
}

function calculateTotalFreight()
{
	var total_freight = 0;
	
	$('.lr_freight').each(function(index, element) {
		
		if(!isNaN(element.value) && element.value!=null && element.value!='')
		{
		
        total_freight = total_freight + parseInt(element.value);
		}
    });
	calculateTotalTax();
	document.getElementById('total_freight').value = total_freight;
}
  $( ".inventory_item_autocomplete" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/customer_name.php',
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
			 }
}).blur(function(){
	
    if(!select)
    {
		
		$(target_el).val("");
    }
 });		
</script>
<script>
function updateTotalExpense()
{
	var total_expense=0;
	$('.expense_amount').each(function(index, element) {
   		if(!isNaN(parseInt(element.value)))
		total_expense=total_expense+parseInt(element.value);
		
    });
	if(total_expense>=0)
	{
	document.getElementById('extra_charges').value=total_expense;
	
	}
	updateTotalFreightAndComm();
}
function generateProductDetails()
{

var sanket=document.getElementById('productDetails').innerHTML;
sanket=sanket.replace('style="display:none;"', '');
var mytbody=document.createElement('tbody');

mytbody.innerHTML=sanket;



$(mytbody).children().each(function(index, element) {
	if(index==1)
	{
		$(element).children().each(function(indexx, elementt) {
            
			if(indexx==1)
			{
				$(elementt).children().each(function(indexxx, elementtt) {
					
					if(indexxx==1)
					{
						elementtt.innerHTML="";
						$(elementtt).removeClass();
						}
					
				});
				
				}
			
        });
		
		}
   
});
pTable.appendChild(mytbody);

}

function generateExpenseDetails()
{

var sanket=document.getElementById('expenseDetails').innerHTML;
sanket=sanket.replace('style="display:none;"', '');
var mytbody=document.createElement('tbody');
mytbody.innerHTML=sanket;
$(mytbody).children().each(function(index, element) {
	if(index==1)
	{
		$(element).children().each(function(indexx, elementt) {
            
			if(indexx==1)
			{
				$(elementt).children().each(function(indexxx, elementtt) {
					
					if(indexxx==1)
					{
						elementtt.innerHTML="";
						$(elementtt).removeClass();
						}
					
				});
				
				}
			
        });
		
		}
   
});
eTable.appendChild(mytbody);

}

function removeThisProduct(spanRemoveLink)
{
	var tbody=$(spanRemoveLink).parent().parent().parent();
	tbody=tbody[0];
	tbody.innerHTML="";
	}
	
function removeThisExpense(spanRemoveLink)
{
	var tbody=$(spanRemoveLink).parent().parent().parent();
	tbody=tbody[0];
	tbody.innerHTML="";
	}	

</script>