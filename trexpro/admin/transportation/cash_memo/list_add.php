<?php 
$branches = listBranches();
$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);
$admin_branch_id_array = "";
foreach($admin_branches as $admin_branch)
$admin_branch_id_array[] = $admin_branch['branch_id'];

$admin_branch_id_string = implode(",",$admin_branch_id_array);
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Add Cash Memo</h4>
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

<h4 class="headingAlignment no_print">LR Details</h4>
 <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print no_sort"></th>
        	<th class="heading">No</th>
           
            <th class="heading file">LR No</th>
            <th class="heading file">LR Date</th>
            <th class="heading">From Branch</th>
            <th class="heading ">To Branch</th>
            <th class="heading">From Customer</th>
            <th class="heading">To Customer</th>
            <th class="heading">To Pay</th>
            <th class="heading">Paid</th>
            <th class="heading">To be billed</th>
            <th class="heading">Total Tax</th>
            <th class="heading">Remarks</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
       
        <?php
		$total=0;
		$untripped_lrs  = getUnCashMemoPaidLrs();
		
		foreach($untripped_lrs as $emi)
		{
			
		 ?>
         <tr class="resultRow">
         <td class="no_print"></td>
        
        	<td><?php echo ++$i; ?></td>
            
            <td><?php echo $emi['lr_no']; ?></td>
             <td><?php echo date('d/m/Y',strtotime($emi['lr_date'])); ?></td>
              <td><?php  echo $emi['from_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['to_branch_ledger_name']; ?>
            </td>
             <td><?php  echo $emi['from_customer_name']; ?>
            </td>
             <td><?php  echo $emi['to_customer_name']; ?>
            </td>
            <td><?php echo $emi['to_pay']; ?>
            </td>
            <td><?php echo $emi['paid']; ?>
            </td>
            <td><?php echo $emi['to_be_billed']; ?>
            </td>
            <td><?php if(is_numeric($emi['total_tax'])) echo $emi['total_tax']; else echo 0; ?>
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