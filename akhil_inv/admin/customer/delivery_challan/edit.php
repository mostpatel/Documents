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
$delivery_challan  = getDeliveryChallanById($delivery_challan_id);
$customer_id = $delivery_challan['customer_id'];
$customer=getCustomerDetailsByCustomerId($customer_id);
$vehicle_id = $delivery_challan['vehicle_id'];
$vehicles = getAvailabaleVehiclesForSale($vehicle_id);
$vehicle = getVehicleById($vehicle_id);
$model = getVehicleModelById($vehicle['model_id']);
$insurance = getInsuranceForDeliveryChallanID($delivery_challan_id);

?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Make Delivery Challan</h4>
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
<input name="delivery_challan_id" value="<?php echo $delivery_challan_id; ?>" type="hidden" />
<input name="vehicle_id" value="<?php echo $vehicle_id; ?>" type="hidden" />
<input name="insurance_id" value="<?php echo $insurance['insurance_id']; ?>" type="hidden" />
<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<input name="engine_no" value="<?php echo $vehicle['vehicle_engine_no']; ?>" type="hidden" />
<input name="chasis_no" value="<?php echo $vehicle['vehicle_chasis_no']; ?>" type="hidden" />
<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td>Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="delivery_date" class="datepicker1" name="delivery_date" value="<?php echo date('d/m/Y',strtotime($delivery_challan['delivery_date'])); ?>" />
                            </td>
</tr>

<tr>
<td>Challan No<span class="requiredField">* </span> : </td>
				<td>
					<span><?php $prefix=getPrefixFromOCId($_SESSION['edmsAdminSession']['oc_id']); echo $prefix; ?></span>
                  <input type="text" id="challan_no"  name="challan_no" value="<?php echo str_replace($prefix,'',$delivery_challan['challan_no']); ?>" />
                            </td>
</tr>

<tr>
<td>Engine No<span class="requiredField">* </span> : </td>
				<td>
					
                    <?php echo $vehicle['vehicle_engine_no']; ?>
                            </td>
</tr>

<tr>
<td>Chasis No<span class="requiredField">* </span> : </td>
				<td>
					
                     <?php echo $vehicle['vehicle_chasis_no']; ?>
                            </td>
</tr>

<tr>
<td width="220px">CNG Cylinder No<span class="requiredField">* </span> : </td>
				<td>
					<span id="cng_cyl_no"><?php echo $vehicle['cng_cylinder_no']; ?></span>
                            </td>
</tr>

<tr>
<td width="220px">CNG Kit No<span class="requiredField">* </span> : </td>
				<td>
					<span id="cng_kit_no"><?php echo $vehicle['cng_kit_no']; ?></span>
                            </td>
</tr>

<tr>
<td width="220px">Vehicle Company<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_company"><?php  echo $model['company_name']; ?></span>
                            </td>
</tr>

<tr>
<td>Vehicle Model<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_model"><?php  echo $model['model_name']; ?></span>
                            </td>
</tr>
 
 <tr>
<td>Vehicle Model Year<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_model"><?php  echo $vehicle['vehicle_model']; ?></span>
                            </td>
</tr>

<tr>
<td>Vehicle Type<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_model"><?php  echo getVehicleTypeNameById($model['vehicle_type_id']); ?></span>
                            </td>
</tr>

<tr>
<td>Insurance Company<span class="requiredField">* </span> : </td>
				<td>
					
                   <select id="insurance_comp" name="insurance_comp" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                     <?php
					$ics = listInsuranceCompanies();
					foreach($ics as $ic)
					{
					?>
                    <option value="<?php echo $ic['insurance_company_id']; ?>" <?php if($ic['insurance_company_id']==$insurance['insurance_company_id']) {?> selected="selected" <?php } ?>><?php echo $ic['insurance_company_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Insurance Issue Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="insurance_date" class="datepicker2" name="insurance_date" value="<?php echo date('d/m/Y',strtotime($insurance['insurance_issue_date'])); ?>" />
                            </td>
</tr>

<tr>
<td>Salesman<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="salesman" name="salesman" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                     <?php
					$salesmans=listSalesman();
					foreach($salesmans as $salesman)
					{
					?>
                    <option value="<?php echo $salesman['ledger_id']; ?>" <?php if($salesman['ledger_id']==$delivery_challan['salesman_ledger_id']) {?> selected="selected" <?php } ?>><?php echo $salesman['ledger_name']; ?></option>			
                    <?php	
					}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>H.P.A / H.V.P<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="financer" name="financer" >
                  	    <option value="-1" selected="selected">-- Please Select --</option>
                    	<option value="0" <?php if($delivery_challan['financer_ledger_id']==0) {?> selected="selected" <?php } ?> >CASH</option>
                     <?php
					$financers=listFinancers();
					foreach($financers as $financer)
					{
					?>
                    <option value="<?php echo $financer['ledger_id']; ?>" <?php if($financer['ledger_id']==$delivery_challan['financer_ledger_id']) {?> selected="selected" <?php } ?>><?php echo $financer['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Battery Make<span class="requiredField">* </span> : </td>
				<td>
					
                   <select id="battery_make" name="battery_make" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                     <?php
					$btms = listBatteryMakes();
					foreach($btms as $btm)
					{
					?>
                    <option value="<?php echo $btm['battery_make_id']; ?>" <?php if($btm['battery_make_id']==$vehicle['battery_make_id']) {?> selected="selected" <?php } ?> ><?php echo $btm['battery_make']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Battery No<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="battery_no"  name="battery_no" value="<?php echo $vehicle['battery_no']; ?>"  />
                            </td>
</tr>

<tr>
<td>Key No<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="key_no"  name="key_no" value="<?php echo $vehicle['key_no']; ?>" />
                            </td>
</tr>


<tr>
<td>Service Book<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="service_book"  name="service_book_no" value="<?php echo $vehicle['service_book']; ?>"  />
                            </td>
</tr>

<tr>
<td>Battery Service Book No<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="battery_service_book_no"  name="battery_service_book_no" value="<?php echo $vehicle['battery_service_book_no']; ?>"  />
                            </td>
</tr>

<tr>
<td>Service No<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="service_no"  name="service_no" value="<?php echo $vehicle['service_no']; ?>"  />
                            </td>
</tr>

</table>
<h4 class="headingAlignment">Included Items </h4>

<table class="insertTableStyling no_print">

<tr>
<td>Tool Kit<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1"  name="tool_kit" id="tool_kit_y" <?php if($delivery_challan['toolkit_inc']==1) { ?> checked="checked" <?php } ?> /></td><td><label for="tool_kit_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="tool_kit" id="tool_kit_n" <?php if($delivery_challan['toolkit_inc']==0) { ?> checked="checked" <?php } ?> /></td><td><label for="tool_kit_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Jack + Tommy<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" <?php if($delivery_challan['service_book_inc']==1) { ?> checked="checked" <?php } ?> name="service_book" id="service_book_y" /></td><td><label for="service_book_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="service_book" id="service_book_n" <?php if($delivery_challan['service_book_inc']==0) { ?> checked="checked" <?php } ?> /></td><td><label for="service_book_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Battery<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" <?php if($delivery_challan['battery_inc']==1) { ?> checked="checked" <?php } ?> name="battery_included" id="battery_included_y" /></td><td><label for="battery_included_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="battery_included" id="battery_included_n" <?php if($delivery_challan['battery_inc']==0) { ?> checked="checked" <?php } ?> /></td><td><label for="battery_included_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Spare Wheel<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" <?php if($delivery_challan['spare_wheel_inc']==1) { ?> checked="checked" <?php } ?> name="spare_wheel" id="spare_wheel_y" /></td><td><label for="spare_wheel_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="spare_wheel" id="spare_wheel_n" <?php if($delivery_challan['spare_wheel_inc']==0) { ?> checked="checked" <?php } ?> /></td><td><label for="spare_wheel_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Wheel Panu + Tommy<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" <?php if($delivery_challan['water_bottle_inc']==1) { ?> checked="checked" <?php } ?> name="water_bottle" id="water_bottle_y" /></td><td><label for="water_bottle_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="water_bottle" id="water_bottle_n" <?php if($delivery_challan['water_bottle_inc']==0) { ?> checked="checked" <?php } ?> /></td><td><label for="water_bottle_n">No</label></td></tr>
                 </table>
                            </td>
</tr>
 


<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit Delivery Challan"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $customer_id; ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>
<script>
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

$( "#combobox" ).combobox();
$( "#combobox2" ).combobox();
</script>