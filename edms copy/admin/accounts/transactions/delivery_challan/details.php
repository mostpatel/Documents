<?php if(isset($_GET['id']))
{
$sales_items = getInventoryItemForDeliveryChallanId($sales_id);	// tax details inside the array
$regular_ns_items = getNonStockItemForDeliveryChallanId($sales_id);
}
else
exit; ?>
<div class="insideCoreContent adminContentWrapper wrapper">
  <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=delivery_challan&id='.$sales_id;  ?>"><button title="View this entry" class="btn viewBtn <?php echo "btn-success"; ?>"><?php ?>Print Delivery Challan</button></a>
<h4 class="headingAlignment"><?php echo DELIVERY_CHALLAN_NAME; ?> Details</h4>
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
<table id="insertInsuranceTable" class="insertTableStyling detailStylingTable">

<tr>
<td width="220px"><?php echo DELIVERY_CHALLAN_NAME; ?> Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($sale['trans_date'])); ?>
                            </td>
</tr>

<!-- <tr>
<td width="220px">Delivery Date : </td>
				<td>
					<input type="text" name="delivery_date" id="delivery_date" class="datepicker2" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr> -->

<!--<tr>
<td width="220px">Amount : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" />
                            </td>
</tr> 

<tr>
<td>To (Credit) : </td>
				<td>
					
                    <?php
			//	echo 	getLedgerNameFromLedgerId($sale['from_ledger_id']);
					?>
                  
                            </td>
</tr> -->

<tr>
<td>By (Debit) : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                  
                    <?php
					
					
					
					?>
                     <?php if(is_numeric($sale['to_ledger_id'])) echo  getLedgerNameFromLedgerId($sale['to_ledger_id']); else if(is_numeric($sale['to_customer_id'])) { ?> <?php echo getCustomerNameByCustomerId($sale['to_customer_id']); ?>		 <?php } ?>	
                    <?php	
						
					 ?>
                    
                            </td>
</tr>

</table>
<?php if(is_array($sales_items)) { ?>
<h4 class="headingAlignment">Spare Parts</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Qty</th>
                 <th>Godown</th>
                 
            </tr>
            
            <?php $total_tax_amount = 0; for($i=1;$i<=count($sales_items);$i++) { 
			$sales_item=$sales_items[$i-1]['sales_item_details'];
			$item_tax_details = $sales_items[$i-1]['tax_details'];
			if($sales_item['tax_amount'])
			$total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			$trans_item_unit_details = getTransItemUnitBySalesItemId($sales_item['sales_item_id']);
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><b><?php echo getItemNameFromItemId($sales_item['item_id']); $barcode = $sales_item['barcode']; if(validateForNull($barcode))  echo "<br> <img  src='".WEB_ROOT."lib/barcode.php?text=".$barcode."' />"."<br>".$barcode; ?></b><br /> <span style="  font-size:15px;font-style:italic">&nbsp; &nbsp;<?php $item_desc = str_replace('##','<br/>&nbsp;&nbsp;',$sales_item['item_desc']); echo $item_desc; ?></span></td>
                    <td align="center"><?php if(!is_numeric($trans_item_unit_details['quantity'])) echo number_format($sales_item['quantity']); else echo $trans_item_unit_details['quantity']; echo " ".$trans_item_unit_details['unit_name']; ?></td>
               
                     <td align="center"><?php echo getGodownNameFromGodownId($sales_item['godown_id']); ?> </td>
                           
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>
</table>
<?php } ?>
<?php if(is_array($regular_ns_items) && count($regular_ns_items)) { ?>
<h4 class="headingAlignment"><?php  if(EDMS_MODE==1) { ?>Labour / <?php } ?>  Service</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="nonStockSaleTable">
    		<tr>
            	<th>Item Name / Code</th>
                 
                 
            </tr>
            <?php   for($i=1;$i<=count($regular_ns_items);$i++) { 
			$sales_item=$regular_ns_items[$i-1]['sales_item_details'];
			$item_tax_details = $regular_ns_items[$i-1]['tax_details'];
			if($sales_item['tax_amount'])
			 $total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); if(defined('INVOICE_TAX_YEARS') && INVOICE_TAX_YEARS==1) { ?> Year : <?php echo $sales_item['item_desc']; } ?></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>

</table>
<?php } ?>
<table class="insertTableStyling detailStylingTable">

<td class="firstColumnStyling">
Remarks : 
</td>

<td>
<?php echo $sale['remarks']; ?>
</td>
</tr>

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>

<a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$sales_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=delete&lid='.$sales_id; ?>"><button title="Edit this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="<?php echo WEB_ROOT."admin/accounts/transactions/delivery_challan" ?>"><input type="button" class="btn btn-success" value="Back to Add Delivery Challan"/></a>
</td>
</tr>

</table>

</div>
<div class="clearfix"></div>
<script>
document.product_count=6;
  (function( $ ) {
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
			getRateQuantityAndTaxForSalesFromItemId(ui.item.option.value,ui.item.option);  
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
  })( jQuery );
$( "#to_ledger" ).combobox();
 $('.item_id').combobox();

function onchangeQuantity(quantity_el) {
    
	var quantity = $(quantity_el).val();
	var rate = $(quantity_el).parent().next().children('input').val();
	var disc = $(quantity_el).parent().next().next().next().children('input').val();
	var tax_select =  $(quantity_el).parent().next().next().next().next().children('select')[0];
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	var amount_el = $(quantity_el).parent().next().next().children('input');
	var net_amount_el = $(quantity_el).parent().next().next().next().next().next().children('input');
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
			
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
	
}

function onchangeRate(rate_el) {
    
	var rate = $(rate_el).val();
	var quantity = $(rate_el).parent().prev().children('input').val();
	var disc = $(rate_el).parent().next().next().children('input').val();
	var tax_select =  $(rate_el).parent().next().next().next().children('select')[0];
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	
	var amount_el = $(rate_el).parent().next().children('input');
	var net_amount_el = $(rate_el).parent().next().next().next().next().children('input');
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}
function onchangeDisc(disc_el) {
    
	var disc = $(disc_el).val();
	var quantity = $(disc_el).parent().prev().prev().prev().children('input').val();
	var rate = $(disc_el).parent().prev().prev().children('input').val();
	var tax_select =  $(disc_el).parent().next().children('select')[0];
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	var amount_el = $(disc_el).parent().prev().children('input');
	var net_amount_el = $(disc_el).parent().next().next().children('input');
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}

function onchangeTaxGroup(tax_el) {
    
	var tax_select = tax_el;
	
	
	var quantity = $(tax_el).parent().prev().prev().prev().prev().children('input').val();
	var rate = $(tax_el).parent().prev().prev().prev().children('input').val();
	var disc = $(tax_el).parent().prev().children('input').val();
	
	
	var tax_val = tax_select.options[tax_select.selectedIndex].id;
	
	if(tax_val!=-1)
	{
		tax = tax_val.replace('tax','');
		if(!isNaN(tax))
		var tax_percent = tax;
		else tax_percent = 0;
	}
   
    if(!isNaN(disc))
	disc_percent = -disc;
	else
	disc_percent = 0;
	
	
	
	var amount_el = $(tax_el).parent().prev().prev().children('input');
	var net_amount_el = $(tax_el).parent().next().children('input');
	
	
	
	if(!isNaN(quantity) && !isNaN(rate))
	{
	
	var amount = quantity*rate;
	
	amount_el.val(amount);
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}
</script>