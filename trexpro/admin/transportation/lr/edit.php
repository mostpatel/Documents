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
$lr=getLRById($delivery_challan_id);
$branch_code = getBranchCodeForBranchID($lr['from_branch_ledger_id']);
$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);

$admin_branches_ids_array = array();
foreach($admin_branches as $branch)
{
	$admin_branches_ids_array[] = $branch['branch_id'];
}
if(!(in_array($lr['from_branch_ledger_id'],$admin_branches_ids_array) || in_array($lr['to_branch_ledger_id'],$admin_branches_ids_array)))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

if(is_array($lr) && $lr)
{
	$lr_Tax = getTaxForLr($delivery_challan_id);
	$lr_products=getProductsByLRId($delivery_challan_id);
	$from_customer = getCustomerDetailsByCustomerId($lr['from_customer_id']);
	$branches = listBranches();
	$to_customer = getCustomerDetailsByCustomerId($lr['to_customer_id']);
	
}

?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Edit Lorry Receipt</h4>
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
<input name="lr_id" value="<?php echo $delivery_challan_id; ?>" type="hidden" />
<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td width="230px;">LR Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="lr_date" class="datepicker1" name="lr_date" value="<?php echo date('d/m/Y',strtotime($lr['lr_date'])); ?>" />
                            </td>
</tr>

<tr>
<td>LR No<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="lr_no"  name="lr_no" value="<?php echo str_replace($branch_code,'',$lr['lr_no']); ?>" />
                            </td>
</tr>

<tr>
<td>From Station<span class="requiredField">* </span> : </td>
				<td>
					 <select id="from_branch_ledger_id"  name="from_branch_ledger_id" >
                    	
                    <?php
					
					foreach($admin_branches as $branch)
					{
					?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($lr['from_branch_ledger_id']==$branch['branch_id'])  { ?> selected="selected" <?php } ?>><?php echo $branch['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Branch!</span>
                            </td>
</tr>

<tr>
<td>Destination<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   	<input type="text" name="delivery_at" class="delivery_at" value="<?php $lr['delivery_at']; ?>" />
                            </td>
</tr>

<tr>
<td>To Station<span class="requiredField">* </span> : </td>
				<td>
					 <select id="to_branch_ledger_id"  name="to_branch_ledger_id" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                    <?php
					
					foreach($branches as $branch)
					{
					?>
                    <option value="<?php echo $branch['ledger_id']; ?>" <?php if($lr['to_branch_ledger_id']==$branch['ledger_id'])  { ?> selected="selected" <?php } ?>><?php echo $branch['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Branch!</span>
                            </td>
</tr>



<tr>
<td>From Customer<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   	<input type="text" name="from_customer_name" class="inventory_item_autocomplete"  value="<?php echo $from_customer['customer_name']." | C".$from_customer['customer_id']; ?>"/>
                            </td>
</tr>

<tr>
<td>To Customer<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   	<input type="text" name="to_customer_name" class="inventory_item_autocomplete" value="<?php echo $to_customer['customer_name']." | C".$to_customer['customer_id']; ?>" />
                            </td>
</tr>

</table>
<h4 class="headingAlignment no_print">Product Details</h4>
<table id="insertAgentTable" class="insertTableStyling no_print">
<tbody id="regenrateAgent" style="display:none;">
<tr >
<td>Product<span class="requiredField">* </span> : </td>
				<td>
					
                  	<input type="text" class="product_name1"  name="product_name_array[]" >
                            </td>
                             <td>Qty: </td>
				<td>
                
					<input type="text" name="qty_no_array[]" style="width:80px" class="qty_no_array" placeholder="Only Digits" /><span id="agerror" class="customError availError">Please Enter Valid LR Number!</span>	
                          
                            </td>
                            <td>Packing Unit <span class="requiredField">* </span>: </td>
				<td>
					<select class="packing_unit_id"  name="packing_unit_id_array[]">
                          <option value="-1" >--Please Select--</option>
                        <?php
                            $quantities = listPackingUnits();
							
                            foreach($quantities as $quantity)
                              {
                             ?>
                             
                           <option value="<?php echo $quantity['packing_unit_id'] ?>"><?php echo $quantity['packing_unit'] ?></option>
                             <?php } ?>
                              
                         
                            </select><span  class="customError availError">Please Select Product!</span>	
                            <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer" onclick="generateProductDetails(this)"/></span>
                            </td>
</tr>
</tbody>
<?php $i=-1;
 foreach($lr_products as $lr_product) {
$i++;	 
$product_id = $lr_product['product_id'];
$product_name = $lr_product['product_name'];
$packing_unit_id = $lr_product['packing_unit_id'];
$qty_no = $lr_product['qty_no'];


?>
<tbody>
<tr >
<td>Product<span class="requiredField">*</span>: </td>
				<td>
					
                  	<input type="text" class="product_name"  name="product_name_array[]" value="<?php echo $product_name; ?>" >
                            </td>
                             <td>Qty: </td>
				<td>
                
					<input type="text" name="qty_no_array[]" style="width:80px" value="<?php echo $qty_no; ?>" class="qty_no_array" placeholder="Only Digits" /><span id="agerror" class="customError availError">Please Enter Valid LR Number!</span>	
                          
                            </td>
                            <td>Packing Unit <span class="requiredField">* </span>: </td>
				<td>
					<select class="packing_unit_id"  name="packing_unit_id_array[]">
                          <option value="-1" >--Please Select--</option>
                        <?php
                            $quantities = listPackingUnits();
							
                            foreach($quantities as $quantity)
                              {
                             ?>
                             
                           <option value="<?php echo $quantity['packing_unit_id'] ?>" <?php if($quantity['packing_unit_id']==$packing_unit_id) { ?> selected="selected"<?php  } ?>><?php echo $quantity['packing_unit'] ?></option>
                             <?php } ?>
                              
                         
                            </select><span  class="customError availError">Please Select Product!</span>	
                            <?php if($i==count($lr_products)-1) { ?><span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer" onclick="generateProductDetails(this)"/></span><?php } else { ?><span class="addContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="removeThisProduct(this)"/></span><?php } ?>
                            </td>
</tr>

</tbody>
<?php  } ?>
</table>

<table class="insertTableStyling no_print">

<tr>

<td width="230px" class="firstColumnStyling">
Total Weight<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="qty_wt" id="qty_wt" class="qty_wt" placeholder="Only Digits" value="<?php echo $lr['weight']; ?>" />
</td>
</tr>

<tr>

<tr>

<td width="230px" class="firstColumnStyling">
Builty Charge<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" value="<?php echo $lr['builty_charge']; ?>" name="builty_charge" id="builty_charge" class="builty_charge" readonly="readonly" placeholder="Only Digits"  />
</td>
</tr>

<tr>

<td width="230px" class="firstColumnStyling">
Tempo Fare<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="tempo_fare" id="tempo_fare" class="tempo_fare"   placeholder="Only Digits" value="<?php echo $lr['tempo_fare']; ?>" />
</td>
</tr>


<tr>

<td width="230px" class="firstColumnStyling">
Rebooking Charges<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="rebooking_charges" id="rebooking_charges" class="rebooking_charges" value="<?php echo $lr['rebooking_charges']; ?>"  placeholder="Only Digits" />
</td>
</tr>


<tr>

<td width="230px" class="firstColumnStyling">
Total Freight<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="total_freight" id="total_freight" class="total_freight" placeholder="Only Digits"  value="<?php echo $lr['freight']; ?>"  onchange="calculateTotalTax();" />
</td>
</tr>

<tr  <?php if(SELECT_TAX==0) { ?> style="display:none;" <?php } ?>>
<td width="230px"class="firstColumnStyling" >Tax <span class="requiredField">* </span>: </td>
				<td>
					<select class="tax_group" id="tax_group" name="tax_group_id"  onchange="calculateTotalTax();">
                        
                      <?php 
					  $tax_grps = listTaxGroups();
$no_of_taxes = count($tax_grps);
$no=1;
									foreach($tax_grps as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>" id="tax<?php if($model['in_out']!=3) echo getTotalTaxPercentForTaxGroup($model['tax_group_id']); else echo 0; ?>" <?php if($model['tax_group_id']==$lr_Tax[0]['tax_group_id'] && $lr_Tax[0]['tax_group_id']!=0){ ?> selected="selected"<?php } else if($no_of_taxes==$no) { ?> selected="selected"<?php } $no++; ?>><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select><span  class="customError availError">Please Select Tax!</span>	
                            </td>
</tr>

<tr>
<td>Tax Payer <span class="requiredField">* </span>: </td>
				<td>
					<select class="tax_pay_type"  name="tax_pay_type" id="tax_pay_type" onchange="calculateTotalTax();">
                          <option value="-1" >--Please Select--</option>
                      
                          <option value="1" <?php if($lr['tax_pay_type']==1) { ?> selected="selected" <?php } ?>>Consignee</option>
                              <option value="2" <?php if($lr['tax_pay_type']==2) { ?> selected="selected" <?php } ?>>Consignor</option>
                               <option value="3" <?php if($lr['tax_pay_type']==3) { ?> selected="selected" <?php } ?>>Transporter</option>
                              
                         
                            </select>
                            </td>
</tr>

<tr>

<td width="230px" class="firstColumnStyling">
Total Tax<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="total_tax" id="total_tax" class="total_tax" placeholder="Only Digits" onchange="calculateTotalTax()" value="<?php echo $lr['total_tax']; ?>" readonly="readonly"/>
</td>
</tr>

<tr>
<td>LR Type <span class="requiredField">* </span>: </td>
				<td>
					<select class="lr_type"  name="lr_type">
                          <option value="-1" >--Please Select--</option>
                      
                             
                           <option value="1" <?php if($lr['to_pay']>0 || (is_numeric($lr['lr_type']) && $lr['lr_type']==1 )) { ?> selected="selected" <?php } ?>>To Pay</option>
                              <option value="2" <?php if($lr['paid']>0 || (is_numeric($lr['lr_type']) && $lr['lr_type']==2 )) { ?> selected="selected" <?php } ?>>Paid</option>
                               <option value="3" <?php if($lr['to_be_billed']>0 || (is_numeric($lr['lr_type']) && $lr['lr_type']==3 )) { ?> selected="selected" <?php } ?>>To Be Billed</option>
                              
                         
                            </select>
                            </td>
</tr>





<!-- <tr>

<td width="230px" class="firstColumnStyling">
To Pay<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="to_pay" id="to_pay" class="to_pay" placeholder="Only Digits" onchange="updateTotalFreightAndComm()" value="<?php echo $lr['to_pay']; ?>"/>
</td>
</tr>

<tr>

<td width="230px" class="firstColumnStyling">
Paid<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="paid" id="paid" class="paid" placeholder="Only Digits" onchange="updateTotalFreightAndComm()" value="<?php echo $lr['paid']; ?>"/>
</td>
</tr>

<tr>

<td width="230px" class="firstColumnStyling">
To be billed<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="to_be_billed" id="to_be_billed" class="to_be_billed" placeholder="Only Digits" onchange="updateTotalFreightAndComm()" value="<?php echo $lr['to_be_billed']; ?>"/>
</td>
</tr> -->

<tr>
<td>
Remarks : 
</td>

<td>
<textarea  name="remarks" id="notes" class="notes"  ><?php echo $lr['remarks']; ?></textarea>
</td>
</tr>


<tr>
<td width="230px;"></td>
<td>
<input type="submit" value="Edit Lorry receipt" onclick="return checkFreights();" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/transportation/lr/index.php?view=details&id=<?php echo $delivery_challan_id; ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>
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

	var tax_on_amount = <?php echo getFreightAmmountForTax(); ?>;
	var total_tax = 0;
	element = document.getElementById('total_freight');

		if(!isNaN(element.value) && element.value!=null && element.value!='' && element.value>=tax_on_amount)
		{
        freight = parseInt(element.value);

		freight=  freight * <?php echo getTaxOnFreightPercentage(); ?>/100;

		var tax_group_el  = document.getElementById('tax_group');

		var tax_val = tax_group_el.options[tax_group_el.selectedIndex].id;
alert(tax_val);
		var tax_type_el  = document.getElementById('tax_pay_type');
		var tax_type = tax_type_el.options[tax_type_el.selectedIndex].value;
			if(tax_val!=-1 && tax_type==3)
			{
				tax = tax_val.replace('tax','');
				if(!isNaN(tax))
				var tax_percent = tax;
				else tax_percent = 0;
			
        		total_tax = total_tax + ((freight*tax_percent)/100)
			}
			else
			total_tax = 0;
		}
		else
		total_tax = 0;
	 total_tax = Math.round(total_tax);
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
delay: 500,
minLength: 2,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
			 }
}).blur(function(){
	
    if(!select)
    {
		
	//	$(target_el).val("");
    }
 });		
 
  $( ".product_name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/product_name.php',
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
		
	//	$(target_el).val("");
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
function generateProductDetailsOld()
{

var sanket=document.getElementById('productDetails').innerHTML;
sanket=sanket.replace('style="display:none;"', '');
sanket=sanket.replace('product_name1', 'product_name');
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
		
 $( ".product_name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/product_name.php',
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
			 }
});
 
}

function generateProductDetails(or_span)
{

var sanket=document.getElementById('regenrateAgent').innerHTML;
sanket=sanket.replace('style="display:none;"', '');
sanket=sanket.replace('product_name1', 'product_name');
var mytbody=document.createElement('tbody');
mytbody.innerHTML=sanket;



pTable = document.getElementById('insertAgentTable');
pTable.appendChild(mytbody);
 $( ".product_name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/product_name.php',
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
    open: function(event, ui) {  select=false; target_el=event.target },
    select: function(event, ui) { select=true; $(event.target).val(ui.item.label);
			 }
});
 
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
<script>
/* (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
			
		  
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.data( "ui-autocomplete" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery ); */

//$( "#combobox" ).combobox();
//$( "#combobox2" ).combobox();


</script>