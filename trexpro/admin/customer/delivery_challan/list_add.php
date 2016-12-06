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

$customer_id=$_GET['id'];
$customer=getCustomerDetailsByCustomerId($customer_id);
$vehicles = getAvailabaleVehiclesForSale();

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
<form id="addLocForm" onsubmit="return submitOurVehicle();" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurVehicle()">

<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td>Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="delivery_date" class="datepicker1" name="delivery_date" value="<?php echo date('d/m/Y',strtotime(getTodaysDate())); ?>" />
                            </td>
</tr>

<tr>
<td>Challan No<span class="requiredField">* </span> : </td>
				<td>
					<span><?php echo getPrefixFromOCId($_SESSION['edmsAdminSession']['oc_id']); ?></span>
                  <input type="text" id="challan_no"  name="challan_no" value="<?php echo getChallanCounterForOCID($_SESSION['edmsAdminSession']['oc_id']); ?>" />
                            </td>
</tr>

<tr>
<td>Engine No<span class="requiredField">* </span> : </td>
				<td>
					 <select  id="combobox" name="engine_no" >
                    	<option value="" selected="selected"></option>
                    <?php
					
					foreach($vehicles as $vehicle)
					{
					?>
                    <option value="<?php echo "en".$vehicle['vehicle_engine_no']; ?>"><?php echo $vehicle['vehicle_engine_no']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Chasis No<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="combobox2" name="chasis_no" >
                    	<option value="" selected="selected"></option>
                     <?php
					
					foreach($vehicles as $vehicle)
					{
					?>
                    <option value="<?php echo "ch".$vehicle['vehicle_chasis_no']; ?>"><?php echo $vehicle['vehicle_chasis_no']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td width="220px">CNG Cylinder No<span class="requiredField">* </span> : </td>
				<td>
					<span id="cng_cyl_no"></span>
                            </td>
</tr>

<tr>
<td width="220px">CNG Kit No<span class="requiredField">* </span> : </td>
				<td>
					<span id="cng_kit_no"></span>
                            </td>
</tr>

<tr>
<td width="220px">Vehicle Company<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_company"></span>
                            </td>
</tr>

<tr>
<td>Vehicle Model<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_model"></span>
                            </td>
</tr>
 
 <tr>
<td>Vehicle Model Year<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_model"></span>
                            </td>
</tr>

<tr>
<td>Vehicle Type<span class="requiredField">* </span> : </td>
				<td>
					<span id="vehicle_model"></span>
                            </td>
</tr>

<tr>
<td>Insurance Company<span class="requiredField">* </span> : </td>
				<td>
					
                   <select id="insurance_comp" name="insurance_comp" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                     <?php
					$vehicles = listInsuranceCompanies();
					foreach($vehicles as $vehicle)
					{
					?>
                    <option value="<?php echo $vehicle['insurance_company_id']; ?>"><?php echo $vehicle['insurance_company_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Insurance Issue Date<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="insurance_date" class="datepicker2" name="insurance_date" value="<?php echo date('d/m/Y',strtotime(getTodaysDate())); ?>" />
                            </td>
</tr>

<tr>
<td>Salesman<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="salesman" name="salesman" >
                    	<option value="-1" selected="selected">-- Please Select --</option>
                     <?php
					$vehicles=listSalesman();
					foreach($vehicles as $vehicle)
					{
					?>
                    <option value="<?php echo $vehicle['ledger_id']; ?>"><?php echo $vehicle['ledger_name']; ?></option>			
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
                    	<option value="0" >No Finance</option>
                     <?php
					$vehicles=listFinancers();
					foreach($vehicles as $vehicle)
					{
					?>
                    <option value="<?php echo $vehicle['ledger_id']; ?>"><?php echo $vehicle['ledger_name']; ?></option>			
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
					$vehicles = listBatteryMakes();
					foreach($vehicles as $vehicle)
					{
					?>
                    <option value="<?php echo $vehicle['battery_make_id']; ?>"><?php echo $vehicle['battery_make']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Battery No<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="battery_no"  name="battery_no"  />
                            </td>
</tr>

<tr>
<td>Key No<span class="requiredField">* </span> : </td>
				<td>
					
                  <input type="text" id="key_no"  name="key_no"  />
                            </td>
</tr>

<tr>
<td>Service Book<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="service_book"  name="service_book_no"  />
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
                 	<tr><td><input type="radio" value="1" checked="checked" name="tool_kit" id="tool_kit_y" /></td><td><label for="tool_kit_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="tool_kit" id="tool_kit_n" /></td><td><label for="tool_kit_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Jack + Tommy<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" checked="checked" name="service_book" id="service_book_y" /></td><td><label for="service_book_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="service_book" id="service_book_n" /></td><td><label for="service_book_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Battery<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" checked="checked" name="battery_included" id="battery_included_y" /></td><td><label for="battery_included_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="battery_included" id="battery_included_n" /></td><td><label for="battery_included_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Spare Wheel<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" checked="checked" name="spare_wheel" id="spare_wheel_y" /></td><td><label for="spare_wheel_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="spare_wheel" id="spare_wheel_n" /></td><td><label for="spare_wheel_n">No</label></td></tr>
                 </table>
                            </td>
</tr>

<tr>
<td>Wheel Panu + Tommy<span class="requiredField">* </span> : </td>
				<td>
					
                 <table>
                 	<tr><td><input type="radio" value="1" checked="checked" name="water_bottle" id="water_bottle_y" /></td><td><label for="water_bottle_y">Yes</label></td></tr>
                    <tr><td><input type="radio" value="0" name="water_bottle" id="water_bottle_n" /></td><td><label for="water_bottle_n">No</label></td></tr>
                 </table>
                            </td>
</tr>
 


<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Make Delivery Challan"  class="btn btn-warning">
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