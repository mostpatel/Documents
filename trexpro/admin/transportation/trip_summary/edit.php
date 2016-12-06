<?php 
$delivery_challan_id=$_GET['id'];
$branches = listBranches();
$truck_drivers=listTruckDrivers();
$trip_memo=getTripSummaryById($delivery_challan_id);

$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);

$brnach_id = $admin_branches[0][0];
$branch_code = getBranchCodeForBranchID($brnach_id);
if(is_array($trip_memo) && $trip_memo)
{
	$trips=getTripsBySummaryId($delivery_challan_id);
	
}

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Edit Trip Summary</h4>
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
<form id="addLocForm" onsubmit="return submitOurVehicle();" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurVehicle()">
<input name="trip_invoice_id" value="<?php echo $delivery_challan_id; ?>" type="hidden" />
<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td width="230px;">Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="lr_date" class="datepicker1" name="invoice_date" value="<?php echo date('d/m/Y',strtotime($trip_memo['trip_memo_summary_date'])); ?>" />
                            </td>
</tr>

<tr>
<td>Summary No<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="invoice_no"  name="invoice_no" value="<?php echo str_replace($branch_code,'',$trip_memo['trip_memo_summary_no']); ?>" />
                            </td>
</tr>

<tr>
<td>Advance<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="advance"  name="advance" value="<?php echo $trip_memo['advance'] ?>"  />
                            </td>
</tr>

<tr>
<td>
Remarks : 
</td>

<td>
<textarea  name="remarks" id="notes" class="notes"  ><?php echo $trip_memo['remarks']; ?></textarea>
</td>
</tr>

</table>

<h4 class="headingAlignment no_print">Trip Memo Details</h4>
 <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
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
		
		foreach($trips as $emi)
		{
		
		$truck_no = getTruckNoById($emi['truck_id']);
		$driver = getLedgerNameFromLedgerId($emi['driver_id']);	
		$total_freight = getTotalFreightForTripMemo($emi['trip_memo_id']);
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['trip_memo_id']; ?>" checked="checked"/></td>
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
       
             <td class="no_print"> <a target="_blank" class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/lr/index.php?view=details&id='.$emi['trip_memo_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }  ?>
        <?php
		$total=0;
		$untripped_lrs=getUnSummarizedTrips();
		foreach($untripped_lrs as $emi)
		{
		
		$truck_no = getTruckNoById($emi['truck_id']);
		$driver = getLedgerNameFromLedgerId($emi['driver_id']);	
		$total_freight = getTotalFreightForTripMemo($emi['trip_memo_id']);
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['trip_memo_id']; ?>" /></td>
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
       
             <td class="no_print"> <a target="_blank" class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/lr/index.php?view=details&id='.$emi['trip_memo_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }  ?>
         </tbody>
    </table>

<table class="insertTableStyling no_print">


<tr>
<td width="230px;"></td>
<td>
<input type="submit" value="Edit Trip Summary"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $customer_id; ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>
<script>
$('.myLink').click(function(e) {
    
	  var winPop = window.open(this.href);  //`this` is reference to link, get href
        return false;  //prevent click event from clicking the link
});

</script>
<script>
$( ".agent" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/agents.php',
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
 
 $( ".expense_name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/expenses.php',
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
 
  $( "#truck_no" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/truck_no.php',
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
function generateAgentDetails(or_span)
{

var sanket=document.getElementById('regenrateAgent').innerHTML;
sanket=sanket.replace('style="display:none;"', '');
var mytbody=document.createElement('tbody');
mytbody.innerHTML=sanket;



pTable = document.getElementById('insertAgentTable');
pTable.appendChild(mytbody);
$(mytbody).children().each(function(index, element) {
		
	if(index==0)
	{
		$(element).children().each(function(indexx, elementt) {
            
			if(indexx==1)
			{
				
				$(elementt).children().each(function(indexxx, elementtt) {
					if(indexxx==1)
					{
						elementtt.focus();
					}
				});
			}
			
        });
		
		}
   
});
$(or_span).parent()[0].innerHTML='<input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="removeThisProduct(this)"/>';
}

function generateExpenseDetails(or_span)
{

var sanket=document.getElementById('regenrateExpense').innerHTML;
sanket=sanket.replace('style="display:none;"', '');
var mytbody=document.createElement('tbody');
mytbody.innerHTML=sanket;



pTable = document.getElementById('insertExpenseTable');
pTable.appendChild(mytbody);
$(mytbody).children().each(function(index, element) {
		
	if(index==0)
	{
		$(element).children().each(function(indexx, elementt) {
            
			if(indexx==1)
			{
				
				$(elementt).children().each(function(indexxx, elementtt) {
				
					if(indexxx==1)
					{
						elementtt.focus();
					}
				});
			}
			
        });
		
		}
   
});
$(or_span).parent()[0].innerHTML='<input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="removeThisProduct(this)"/>';

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