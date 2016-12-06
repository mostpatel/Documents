<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$job_card_id = $_GET['id'];
$job_card = getJobCardById($job_card_id);
$job_card_detials = $job_card['job_card_details'];
$job_card_customer_complaints=$job_card['job_card_description'];
$job_card_work_done = $job_card['job_card_work_done'] ;
$job_card_remarks = $job_card['job_card_remarks'];
$regular_items=$job_card['job_card_regular_items'];
$warranty_items=$job_card['job_card_warranty_items'];
$regular_ns_items=$job_card['job_card_ns_items'];
$outside_job_items=$job_card['job_card_outside_job'];
$service_checks=$job_card['job_card_checks'];
$sale=$job_card['job_card_sales'];
$vehicle_id = $job_card_detials['vehicle_id'];
$vehicle = getVehicleById($vehicle_id);	
$customer_id = $vehicle['customer_id'];
$customer = getCustomerDetailsByCustomerId($customer_id);
$oc_id =$admin_id=$_SESSION['edmsAdminSession']['oc_id'];
$invoice_counter = getInvoiceCounterForOCID($oc_id);
$job_card_counter = getJobCounterForOCID($oc_id);
$invoice_no = getFinalizeDetailsForJobCard($job_card_id);
}
else
exit;
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<table>
<tr >
<td style="padding:10px;padding-bottom:20px;" colspan="2">
<?php if(!validateForNull($invoice_no)) { ?>
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card_id; ?>"><button title="Finalize this entry" class="btn delBtn btn-danger">Finalize</button></a><?php } else { ?>
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card_id; ?>"><button title="Finalize this entry" class="btn btn-success">Invoice</button></a>
<?php } ?>
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard&id='.$job_card_id; ?>"><button class="btn"><i class="icon-print"></i> Print Job Card Front Page</button></a>
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard2&id='.$job_card_id; ?>"><button class="btn"><i class="icon-print"></i> Print Job Card Back Page</button></a>
<br />
<br />
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=edit&id='.$job_card_id; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>       
<a href="<?php echo  WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>
<h4 class="headingAlignment"> Job Card For <?php echo $customer['customer_name']; echo " ( ".$vehicle['vehicle_reg_no']." - ".getModelNameById($vehicle['model_id'])." )"; ?>  </h4>
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
<td width="220px">Job Card Date and Time : </td>
				<td>
              <?php echo date('d/m/Y H:i:s',strtotime($job_card_detials['job_card_datetime'])); ?>
				
                            </td>
</tr>
<tr>
<td width="220px">Job Card No : </td>
				<td>
					<?php echo $job_card_detials['job_card_no']; ?>
                            </td>
</tr> 

<tr>
<td>Sales Account : </td>
				<td>
					
                    <?php
					$bank_cash_ledgers=listSalesLedgers();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <?php if($bank_cash_ledger['ledger_id']==$sale['from_ledger_id']) { ?><?php echo $bank_cash_ledger['ledger_name']; } ?>
                    <?php	
						}
					 ?>
                  
                            </td>
</tr>


<tr>
<td>Service Type : </td>
				<td>
					<?php echo getServiceTypeNameById($job_card_detials
					['service_type_id']); ?>
                            </td>
</tr>
<?php if($job_card_detials['service_type_id']==2 || $job_card_detials
					['service_type_id']==5) { ?>
                    
<tr  id="srevice_no_tr">
<td>Service No : </td>
				<td>
					<?php echo $job_card_detials['free_service_no']; ?>
                            </td>
</tr>
<?php } ?>
<tr>
<td width="220px">Date of Sale : </td>
				<td>
					<?php if($job_card_detials['date_of_sale']!="1970-01-01") echo date('d/m/Y',strtotime($job_card_detials['date_of_sale'])); else echo "NA"; ?>
                            </td>
</tr>

<tr>
<td width="220px">Kms Covered : </td>
				<td>
					<?php echo $job_card_detials['kms_covered']." Kms"; ?>
                            </td>
</tr> 






<tr>
<td width="220px">Estimated Cost : </td>
				<td>
					<?php echo "Rs ".$job_card_detials['estimated_repair_cost']; ?>
                            </td>
</tr> 
<tr>
<td width="220px">Delivery Promise : </td>
				<td>
               <?php if($job_card_detials['delivery_promise']!="1970-01-01") echo date('d/m/Y H:i:s',strtotime($job_card_detials['delivery_promise'])); ?>
                            </td>
</tr>

<?php  foreach($job_card_customer_complaints as $com) { ?>


<tr id="addcontactTrCustomer">
                <td>
                Customer Compalints  : 
                </td>
                
                <td id="addcontactTd">
              <?php echo $com['job_desc']; ?>
                </td>
            </tr>

<?php } ?>
<?php  foreach($job_card_work_done as $com) { ?>
<tr id="addcontactTrCustomer1">
                <td>
                Actual Work Done : 
                </td>
                
                <td id="addcontactTd1">
              <?php echo $com['job_wd']; ?>
                </td>
            </tr>
<?php  } ?>

<?php  foreach($job_card_remarks as $com) { ?>
<tr id="addcontactTrCustomer2">
                <td>
                Remarks : 
                </td>
                
                <td id="addcontactTd2">
               <?php echo $com['jb_remarks']; ?>
                </td>
            </tr>

<?php } ?>
</table>
<h4 class="headingAlignment">Service Check</h4>
<table class="insertTableStyling detailStylingTable">
<?php

$service_checks = listServiceChecksOrderByType();
foreach($service_checks as $service_check)
{
?>
<tr>
	<td><?php echo $service_check['service_check']; ?> :</td>
    <td>
    	<?php 
		$service_check_values=listServiceCheckValuesForServiceCheck($service_check['service_check_id']);
		$checked_service_values=listServiceCheckValuesForServiceCheckForJobCardId($service_check['service_check_id'],$job_card_id);
		
		if($service_check['check_type']==0) {
			
			foreach($service_check_values as $sr_value)
			{
			 ?>
    	<input  type="radio" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="service_check<?php echo $sr_value['service_check_value_id']; ?>" value="<?php echo $sr_value['service_check_value_id']; ?>" <?php if(in_array($sr_value['service_check_value_id'],$checked_service_values)) { ?> checked="checked" <?php } ?> disabled="disabled" /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="service_check<?php echo $sr_value['service_check_value_id'];?>"><?php echo $sr_value['service_check_value']; ?></label>
	
    	<?php } ?>
        <input type="radio" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="none<?php echo $service_check['service_check_id'];  ?>"  value="-1" disabled="disabled" /><label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="none<?php echo $service_check['service_check_id']; ?>" > None </label>
		<?php 
		} 
		else if($service_check['check_type']==1) { 
		
        foreach($service_check_values as $sr_value)
			{
			 ?>
    	<input  type="checkbox" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="service_check<?php echo $sr_value['service_check_value_id']; ?>" value="<?php echo $sr_value['service_check_value_id']; ?>"  <?php if(in_array($sr_value['service_check_value_id'],$checked_service_values)) { ?> checked="checked" <?php } ?>  disabled="disabled" /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="service_check<?php echo $sr_value['service_check_value_id'];?>"><?php echo $sr_value['service_check_value']; ?></label>
	
    	<?php } ?>
        
        <?php } 
		if($service_check['check_type']==2) { 
		 foreach($service_check_values as $sr_value)
			{
			 ?>
    	<input  type="radio" name="service_check[<?php echo $service_check['service_check_id']; ?>][]" id="service_check<?php echo $sr_value['service_check_value_id']; ?>" value="<?php echo $sr_value['service_check_value_id']; ?>"  <?php if(in_array($sr_value['service_check_value_id'],$checked_service_values)) { ?> checked="checked" <?php } ?> disabled="disabled" /> <label style="display:inline-block; top:3px;position:relative;margin-right:10px;" for="service_check<?php echo $sr_value['service_check_value_id'];?>"><?php echo $sr_value['service_check_value']; ?></label>
	
    	<?php } } ?>
    </td>
</tr>
<?php	
	
}
  ?>
  
<tr>
<td width="220px">Bay In : </td>
				<td>
                <?php echo date('d/m/Y H:i:s',strtotime($job_card_detials['bay_in'])); ?>
				
                            </td>
</tr>

<tr>
<td>Technician : </td>
				<td>
					<?php echo getTechnicianNameById($job_card_detials['technician_id']); ?>
                            </td>
</tr>

  
					
</table>
<?php if(is_array($regular_items) && count($regular_items)) { ?>
<h4 class="headingAlignment">Spare Parts</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Godown</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
              <?php  $total_tax_amount = 0; for($i=1;$i<=count($regular_items);$i++) { 
			$sales_item=$regular_items[$i-1]['sales_item_details'];
			$item_tax_details = $regular_items[$i-1]['tax_details'];
			 $total_tax_amount = $total_tax_amount + $regular_items['tax_amount'];
			
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                     <td align="center"><?php echo getGodownNameFromGodownId($sales_item['godown_id']); ?> </td>
                    <td align="center"><?php echo number_format($sales_item['quantity'],1); ?></td>
                     <td align="center"><?php echo number_format($sales_item['rate']); ?> Rs</td>
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

<table>
<?php } ?>
<?php if(is_array($warranty_items) && count($warranty_items)) { ?>
<h4 class="headingAlignment">Spare parts under warranty</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="warProductPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Godown</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
            <?php  $total_tax_amount = 0; for($i=1;$i<=count($warranty_items);$i++) { 
			$sales_item=$warranty_items[$i-1]['sales_item_details'];
			$item_tax_details = $warranty_items[$i-1]['tax_details'];
			 $total_tax_amount = $total_tax_amount + $warranty_items['tax_amount'];
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                     <td align="center"><?php echo getGodownNameFromGodownId($sales_item['godown_id']); ?> </td>
                    <td align="center"><?php echo number_format($sales_item['quantity']); ?></td>
                     <td align="center"><?php echo number_format($sales_item['rate']); ?> Rs</td>
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

<table>
<?php } ?>
<?php if(is_array($regular_ns_items) && count($regular_ns_items)) { ?>
<h4 class="headingAlignment">Labour / Service</h4>
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
            <?php  $total_tax_amount = 0; for($i=1;$i<=count($regular_ns_items);$i++) { 
			$sales_item=$regular_ns_items[$i-1]['sales_item_details'];
			$item_tax_details = $regular_ns_items[$i-1]['tax_details'];
			 $total_tax_amount = $total_tax_amount + $regular_ns_items['tax_amount'];
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                    
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
<?php if(is_array($outside_job_items) && count($outside_job_items)) { ?>
<h4 class="headingAlignment">Out Side Job</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td>
    	<table width="100%" class="adminContentTable productPurchaseTable" id="outSideJobTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Rate</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th>Our Rate</th>
                 <th>Job Provider</th>
                 
            </tr>
            <?php  $total_tax_amount = 0; for($i=1;$i<=count($outside_job_items);$i++) { 
			$sales_item=$outside_job_items[$i-1]['sales_item_details'];
			$item_tax_details = $outside_job_items[$i-1]['tax_details'];
			 $total_tax_amount = $total_tax_amount + $outside_job_items['tax_amount'];
			 $outside_job_details = getOutSideLabourJVForNonStockId($sales_item['sales_non_stock_id']);
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                    
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                    
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_amount'])) echo round($sales_item['net_amount']+$sales_item['tax_amount'],2); else echo round($sales_item['net_amount'],2);  ?> Rs</td>
                     <td align="center"><?php echo round($outside_job_details['amount'],2); ?> Rs</td>
                     <td align="center"><?php echo getLedgerNameFromLedgerId($outside_job_details['from_ledger_id']); ?></td>
            	</tr>
            </tbody>
            <?php } ?>
    	</table>
    </td>

</tr>
</table>
<?php } ?>
<table class="detailStylingTable insertTableStyling">

<tr>
<td width="220px">Bay Out : </td>
				<td>
                <?php if($job_card_detials['bay_out']!="1970-01-01 00:00:00") echo date('d/m/Y H:i:s',strtotime($job_card_detials['bay_out'])); else echo "NA"; ?>
				
                            </td>
</tr>

<tr>
<td width="220px">Actual Delivery : </td>
				<td>
               <?php if($job_card_detials['actual_delivery']!="1970-01-01 00:00:00") echo date('d/m/Y H:i:s',strtotime($job_card_detials['actual_delivery'])); else echo "NA"; ?>
				
                            </td>
</tr>


<tr>

<td width="240px;" class="firstColumnStyling">
Remarks / Any Advice To Customers: 
</td>

<td>
<?php echo $sale['remarks']; ?>
</td>
</tr>

</table>

<table>
<tr >
<td style="padding:10px;padding-top:20px;" colspan="2">
<?php if(!validateForNull($invoice_no)) { ?>
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card_id; ?>"><button title="Finalize this entry" class="btn delBtn btn-danger">Finalize</button></a><?php } else { ?>
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card_id; ?>"><button title="Finalize this entry" class="btn btn-success">Invoice</button></a>
<?php } ?>

<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard&id='.$job_card_id; ?>"><button class="btn"><i class="icon-print"></i> Print Job Card Front Page</button></a>
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard2&id='.$job_card_id; ?>"><button class="btn"><i class="icon-print"></i> Print Job Card Back Page</button></a>
<br />
<br />
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=edit&id='.$job_card_id; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>       
<a href="<?php echo  WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
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

function onchangeRateNS(rate_el) {
    
	var rate = $(rate_el).val();

	var disc = $(rate_el).parent().next().children('input').val();
	
	var tax_select =  $(rate_el).parent().next().next().children('select')[0];
	
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
	
	
	var net_amount_el = $(rate_el).parent().next().next().next().children('input');
	if(!isNaN(rate))
	{
	
	var amount = parseFloat(rate);
	
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		
		var net_amount = amount + parseFloat((amount*(disc_percent/100)));
		
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}
function onchangeDiscNS(disc_el) {
    
	var disc = $(disc_el).val();
	var rate = $(disc_el).parent().prev().children('input').val();
	
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
	
	
	var net_amount_el = $(disc_el).parent().next().next().children('input');
	rate = parseFloat(rate);
	if( !isNaN(rate))
	{
	
	var amount = parseFloat(rate);
	
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}

function onchangeTaxGroupNS(tax_el) {
    
	var tax_select = tax_el;
	
	var rate = $(tax_el).parent().prev().prev().children('input').val();
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
	
	
	
	
	var net_amount_el = $(tax_el).parent().next().children('input');
	
	
	
	if( !isNaN(rate))
	{
	
	var amount = parseFloat(rate);
	
	
	
		if(!isNaN(tax_percent) && !isNaN(disc_percent))
		{
		var net_amount = amount + (amount*(disc_percent/100));
		net_amount = net_amount + (net_amount*(tax_percent/100));
		net_amount_el.val(net_amount);
		}
	}
}

function toggleServiceNoSelect(service_type_id)
{
	if(service_type_id==5 || service_type_id==2)
	{
	$('#srevice_no_tr').show();	
	document.getElementById('service_no').options[0].selected=true;	
	}
	else
	{
	$('#srevice_no_tr').hide();	
	document.getElementById('service_no').options[0].selected=true;	
	}
	
}
</script>
