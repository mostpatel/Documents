<?php 
$branches = listBranches();
$truck_drivers=listTruckDrivers();
$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Make Trip Memo</h4>
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
<form id="addLocForm" onsubmit="return submitTripMemo();" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data">

<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<input type="hidden" value="" name="total_no_of_lrs" id="total_no_of_lrs" />
<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td width="230px;">Trip Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="lr_date" class="datepicker1" name="trip_memo_date" value="<?php echo date('d/m/Y',strtotime(getTodaysDate())); ?>" />
                            </td>
</tr>

<tr>
<td>Memo No<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="lr_no"  name="trip_memo_no" value="<?php echo getTripMemoCounterForBranchID($admin_branches[0]['branch_id']); ?>" />
                            </td>
</tr>

<tr>
<td>From Branch<span class="requiredField">* </span> : </td>
				<td>
					 <select id="from_branch_ledger_id"  name="from_branch_ledger_id" onchange="getTotalNoOfLrs()" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                    <?php
					
					foreach($admin_branches as $branch)
					{
					?>
                    <option value="<?php echo $branch['branch_id']; ?>"><?php echo $branch['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Branch!</span>
                            </td>
</tr>

<tr>
<td>To Branch<span class="requiredField">* </span> : </td>
				<td>
					 <select id="to_branch_ledger_id"  name="to_branch_ledger_id" onchange="arrangeLr(this.value)" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                    <?php
					
					foreach($branches as $branch)
					{
					?>
                    <option value="<?php echo $branch['ledger_id']; ?>"><?php echo $branch['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Branch!</span>
                            </td>
</tr>


<tr>
<td>Truck No<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   	<input type="text" name="truck_no" id="truck_no" class="inventory_item_autocomplete" />
                            </td>
</tr>

<tr>
<td>Driver Name<span class="requiredField">* </span> : </td>
				<td>
					 <input type="text" id="driver_id"  name="driver_id" >
                    	
                            </td>
</tr>

<!-- <tr>
<td>LR Selection<span class="requiredField">* </span> : </td>
				<td>
					<select name="select_lr_option" id="select_lr_option" onchange="generateLRTable(this.value)">
                    	<option value="-1">Please Select</option>
                        <option value="1">Select All</option>
                        <option value="0">Select Manually</option>
                    </select>
                    	
                            </td>
</tr> -->


<tr>
<td>
Remarks : 
</td>

<td>
<textarea  name="remarks" id="notes" class="notes"  ></textarea>
</td>
</tr>

</table>
<div id="lrDiv">
<h4 class="headingAlignment no_print">LR Details</h4>
 <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print"><input type="checkbox" id="selectAllTRTripMemo" name="selectAllTR"  /></th>
        	<th class="heading">No</th>
            <th class="heading file">LR No</th>
            <th class="heading file">LR Date</th>
            <th class="heading">From Branch</th>
            <th class="heading ">To Branch</th>
            <th class="heading">From Customer</th>
            <th class="heading">To Customer</th>
            <th class="heading">Total Freight</th>
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
     
    <tbody>
       
        <?php
		$total=0;
		$untripped_lrs=getUnTrippedLrs();
		
		foreach($untripped_lrs as $emi)
		{
			
		 ?>
         <tr class="resultRow lr<?php echo $emi['to_branch_ledger_id']; ?>">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $emi['lr_id']; ?>" /></td>
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $emi['lr_no']; ?></td>
             <td><?php echo date('d/m/Y',strtotime($emi['lr_date'])); ?></td>
              <td><?php  echo $emi['from_branch_ledger_name']; ?>
            </td>
             <td ><?php  echo $emi['to_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['from_customer_name']; ?>
            </td>
             <td><?php  echo $emi['to_customer_name']; ?>
            </td>
            <td><?php echo $emi['total_freight']; ?>
            </td>
            <td class="payment_amount"><?php   echo $emi['remarks'] ?>
            </td>
       
             <td class="no_print"> <a target="_blank" class="myLink" href="<?php echo WEB_ROOT.'admin/transportation/lr/index.php?view=details&id='.$emi['lr_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }  ?>
         </tbody>
    </table>
</div>
<table class="insertTableStyling no_print">


<tr>
<td width="230px;"></td>
<td>
<input type="submit" value="Insert Trip Memo"  class="btn btn-warning">
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
            }
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
function arrangeLr(to_branch)
{
	$('tr.resultRow').each(function(index, element) {
        $(element).show();
    });	
	 $('.selectTR').prop('checked',false);
	 $('#selectAllTRTripMemo').prop('checked',false);
	if(to_branch>0)
	{
	var class_name = '.lr'+to_branch;
	   $('tr.resultRow').not(class_name).each(function(index, element) {
       $(element).hide();
    });
	var total_no_of_lrs=getTotalNoOfLrs();
	}
	
}
$('#selectAllTRTripMemo').click(function(e) {
    var to_branch=$('#to_branch_ledger_id').val();
	var class_name = '.lr'+to_branch;
	
	 $(class_name+' .selectTR').prop('checked',true);
	 $('#selectAllTRTripMemo').prop('checked',true);
	
});
function generateLRTable(lr_select_option)
{
	if(lr_select_option==0)
	{
		$('#lrDiv').show();
		
	}
	else
	$('#lrDiv').hide();
}
//$('#lrDiv').hide();
function getTotalNoOfLrs()
{
var from_branch = document.getElementById('from_branch_ledger_id').value;
var to_branch = document.getElementById('to_branch_ledger_id').value;	

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
	
    var no_of_lrs=(xmlhttp1.responseText);
	document.getElementById('total_no_of_lrs').value = no_of_lrs;
	return no_of_lrs;
    }
  }
  
  xmlhttp1.open('GET', "<?php echo WEB_ROOT; ?>json/total_lr_no.php?from_branch="+from_branch+"&to_branch="+to_branch, true );    
  xmlhttp1.send(null);
	
}
function submitTripMemo()
{
	var total_no_lrs = document.getElementById('total_no_of_lrs').value;
	var checked_lrs = $('.selectTR:checked').size();
	if(checked_lrs!=total_no_lrs)
	return confirm("Only "+checked_lrs+" Lrs Checked Out Of "+total_no_lrs+" Lrs! Are you sure you want to continue!");
	else
	return true;
}
</script>