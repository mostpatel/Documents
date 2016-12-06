<?php if(isset($_GET['id']))
{

$sales_items = getInventoryItemForSaleId($sales_id);	// tax details inside the array
$regular_ns_items = getNonStockItemForDisplayForSaleId($sales_id);
}
else
exit; ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"><?php echo SALES_NAME; ?> Details</h4>
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
<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php?view=invoice&id=<?php echo $sales_id; ?>"><button class="btn btn-success">Print Invoice</button></a>    </div> 
<table id="insertInsuranceTable" class="insertTableStyling detailStylingTable">

<tr>
<td width="220px"><?php echo SALES_NAME; ?> Date : </td>
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
</tr> -->

<tr>
<td>To (Credit) : </td>
				<td>
					
                    <?php
				echo 	getLedgerNameFromLedgerId($sale['from_ledger_id']);
					?>
                  
                            </td>
</tr>

<tr>
<td>By (Debit) : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                  
                    <?php
					$ledgers=listCustomerAndLedgersWithoutPurchaseAndSales();
					foreach($ledgers as $ledger)
					{
					?>
                     <?php if((is_numeric($sale['to_ledger_id']) && $ledger['id']=='L'.$sale['to_ledger_id']) || (is_numeric($sale['to_customer_id']) && $ledger['id']=='C'.$sale['to_customer_id'])) { ?> <?php echo $ledger['name']; ?>		 <?php } ?>	
                    <?php	
						}
					 ?>
                    
                            </td>
</tr>

</table>
<?php if(SALES_STOCK==1) { ?>
<h4 class="headingAlignment">Spare Parts</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
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
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); if(checkForNumeric($sales_item['barcode_transaction_id'])) {
						 $barcode = $sales_item['barcode']; echo "<br> <img  src='".WEB_ROOT."lib/barcode.php?text=".$barcode."' />"."<br>".$barcode; 
						} ?></td>
                    <td align="center"><?php if(!is_numeric($trans_item_unit_details['quantity'])) echo number_format($sales_item['quantity']); else echo $trans_item_unit_details['quantity']; echo " ".$trans_item_unit_details['unit_name']; ?></td>
                     <td align="center"><?php if(!is_numeric($trans_item_unit_details['rate'])) echo number_format($sales_item['rate']); else echo $trans_item_unit_details['rate'];  ?> Rs</td>
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php echo round($sales_item['net_amount']+$sales_item['tax_amount'],2); ?> Rs</td>
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
<h4 class="headingAlignment"><?php if(EDMS_MODE==1){ ?>Labour / <?php } ?>Service</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="nonStockSaleTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Rate</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 
            </tr>
            <?php  
			
			 for($i=1;$i<=count($regular_ns_items);$i++) { 
			$sales_item=$regular_ns_items[$i-1]['sales_item_details'];
			$item_tax_details = $regular_ns_items[$i-1]['tax_details'];
			
			$item =  getInventoryItemById($inventory_item['item_id']);
			
		
			
			if($sales_item['tax_amount'])
			 $total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); if(defined('INVOICE_TAX_YEARS') && INVOICE_TAX_YEARS==1) { ?> Year : <?php echo $sales_item['item_desc']; }  ?></td>
                    
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                    
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_amount'])) echo round($sales_item['net_amount']+$sales_item['tax_amount'],2); else echo round($sales_item['net_amount'],2);  ?> Rs</td>
                    
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>

</table>
<?php } ?>
<table class="insertTableStyling detailStylingTable">
<tr>
<td width="220px;">Amount : </td>
				<td>
					<?php echo number_format(round($sale['amount'])); ?> Rs
                            </td>
</tr>

<tr  id="pay_ref_new">
<td>Total Tax : </td>
				<td>
					<?php echo number_format(round($total_tax_amount)); ?> Rs
                  
                            </td>
</tr>

<tr>
<td >Net Amount : </td>
				<td>
					<?php echo number_format(round($sale['amount']+$total_tax_amount)); ?> Rs
                            </td>
</tr>




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
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$sales_id; ?>"><button title="Edit this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="<?php echo WEB_ROOT."admin/accounts/transactions/sales_inventory" ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

<h4 class="headingAlignment">List of Receipts</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading">Amount</th>
             <th class="heading">Mode</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$receipts=getReceiptsForSalesId($sales_id);
		$no=0;
		foreach($receipts as $receipt)
		{
			$by_account_id=$receipt['from_ledger_id'];
		    
			$by_account=getLedgerById($by_account_id);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
            <td><?php echo $receipt['amount']; ?>
            </td>
            <td><?php echo $by_account['ledger_name']; ?>
            </td>
          
             
     
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$receipt['receipt_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=edit&lid='.$receipt['receipt_id'].'&type=5'; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?action=delete&lid='.$receipt['receipt_id'].'&type=5';  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 

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