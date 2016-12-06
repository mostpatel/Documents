<?php 
if(isset($_GET['id']))
$model_id = $_GET['id'];
else
exit;
$opening_vehicles=getOpeningVehiclesForModel($model_id);
if($purchase=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$no_of_vehicles = count($opening_vehicles);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Opening Vehicles </h4>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="lid" value="<?php echo $model_id; ?>" />

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Vehicle Details</h4>

<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td width="220px">
No of Vehicles<span class="requiredField">* </span> : 
</td>
<td>
<select name="no_vehicles" id="no_vehicles" onchange="alterNoOfVehicles(this.value)" >
	<?php for($i=$no_of_vehicles;$i<30;$i++)
	{ ?>
    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
    <?php } ?>
    
</select>
</td>
</tr>

<tbody id="vehicle_details" style="display:none">
<input type="hidden" name="vehicle_id[]" value="" /> 
<input type="hidden" name="model_id[]" value="<?php echo $model_id; ?>"  /> 
<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Vehicle</span>
</td>


</tr>

 <tr>
<td>Vehicle Model Year<span class="requiredField">* </span> : </td>
				<td>
					<select id="model" name="model_year[]">
                        <option value="-1" >--Please Select Model Year--</option>
                       <?php
					   for($i=date('Y');$i>=1990;$i--)
					   {
						 ?>
                          <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                         <?php  
						   }
					    ?>
                     </select> 
                            </td>
</tr>

<tr>
<td>Vehicle Color<span class="requiredField">* </span> : </td>
				<td>
					<select id="color" name="vehicle_color_id[]">
                        <option value="-1" >--Please Select Vehicle Color--</option>
                      <?php $models = listVehicleColors();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['vehicle_color_id'] ?>"><?php echo $model['vehicle_color']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<tr>
<td>Godown<span class="requiredField">* </span> : </td>
				<td>
					<select id="color" name="godown_id[]">
                        <option value="-1" >--Please Select Godown--</option>
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>
<tr>
       <td>Vehicle Condition<span class="requiredField">* </span> :</td>
           
           
       
        <td>
					<select id="condition" name="condition[]"  onchange="toggleRegNumber(this,this.value)">
                        <option value="1" selected="selected" >NEW</option>
                        <option value="0"  >OLD</option>
                            </select> 
                            </td>
 </tr>
 
<tr style="display:none;">
<td class="firstColumnStyling">
Reg Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_reg_no" name="vehicle_reg_no[]"  placeholder="Reg No Eg: GJ1AB1234 !" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"/><span id="agerror" class="availError">Reg Number already taken!</span>	
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Engine Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_engine_no" name="vehicle_engine_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"/><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_chasis_no" name="vehicle_chasis_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')"/><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td>Service Book Number<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="service_book"  name="service_book_no[]" placeholder="Only Digits!"  />
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Cylinder Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_cylinder_no" name="cng_cylinder_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"/><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_kit_no" name="cng_kit_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')"/><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td width="220px">Basic Price<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="basic_price[]" id="basic_price" placeholder="Only Digits!" />
                            </td>
</tr>



</tbody>

<?php if($opening_vehicles) { for($j=1;$j<=$no_of_vehicles;$j++) {
	$opening_vehicle_id = $opening_vehicles[$j-1][0];
	$vehicle = getVehicleByID($opening_vehicle_id);
	$basic_price  = $vehicle['basic_price'];
	
	 ?>
    
<tbody id="vehicle_details<?php if($j>1) echo $j; ?>">
<input type="hidden" name="vehicle_id[]" value="<?php echo $vehicle['vehicle_id'] ?>" /> 
<input type="hidden" name="model_id[]" value="<?php echo $model_id; ?>"  />
<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Vehicle <?php echo $j; ?></span>
</td>


</tr>

 <tr>
<td>Vehicle Model Year<span class="requiredField">* </span> : </td>
				<td>
					<select id="model" name="model_year[]">
                        <option value="-1" >--Please Select Model Year--</option>
                       <?php
					   for($i=date('Y');$i>=1990;$i--)
					   {
						 ?>
                          <option value="<?php echo $i; ?>" <?php if($i==$vehicle['vehicle_model']){ ?> selected="selected" <?php } ?> ><?php echo $i; ?></option>
                         <?php  
						   }
					    ?>
                     </select> 
                            </td>
</tr>

<tr>
<td>Vehicle Color<span class="requiredField">* </span> : </td>
				<td>
					<select id="color" name="vehicle_color_id[]">
                        <option value="-1" >--Please Select Vehicle Color--</option>
                      <?php $models = listVehicleColors();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['vehicle_color_id'] ?>" <?php if($model['vehicle_color_id']==$vehicle['vehicle_color_id']){ ?> selected="selected" <?php } ?>><?php echo $model['vehicle_color']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<tr>
<td>Godown<span class="requiredField">* </span> : </td>
				<td>
					<select id="color" name="godown_id[]">
                        <option value="-1" >--Please Select Godown--</option>
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>" <?php if($model['godown_id']==$vehicle['godown_id']){ ?> selected="selected" <?php } ?>><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<tr>
       <td>Vehicle Condition<span class="requiredField">* </span> :</td>
           
           
       
        <td>
					<select id="condition" name="condition[]"  onchange="toggleRegNumber(this,this.value)">
                        <option value="1" <?php if($vehicle['vehicle_condition']==1){ ?> selected="selected" <?php } ?>>NEW</option>
                        <option value="0" <?php if($vehicle['vehicle_condition']==0){ ?> selected="selected" <?php } ?> >OLD</option>
                            </select> 
                            </td>
 </tr>
 
<tr style="display:none;">
<td class="firstColumnStyling">
Reg Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_reg_no" name="vehicle_reg_no[]"  placeholder="Reg No Eg: GJ1AB1234 !" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')" value="<?php echo $vehicle['vehicle_reg_no']; ?>"/><span id="agerror" class="availError">Reg Number already taken!</span>	
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Engine Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_engine_no" name="vehicle_engine_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')" value="<?php echo $vehicle['vehicle_engine_no']; ?>"/><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_chasis_no" name="vehicle_chasis_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')" value="<?php echo $vehicle['vehicle_chasis_no'] ?>" /><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td>Service Book Number<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="service_book"  name="service_book_no[]" placeholder="Only Digits!" value="<?php echo $vehicle['service_book']; ?>"  />
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Cylinder Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_cylinder_no" name="cng_cylinder_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')" value="<?php echo $vehicle['cng_cylinder_no'] ?>" /><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_kit_no" name="cng_kit_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')" value="<?php echo $vehicle['cng_kit_no'] ?>" /><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td width="220px">Basic Price<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="basic_price[]" id="basic_price" value="<?php echo $basic_price; ?>" placeholder="Only Digits!" />
                            </td>
</tr>

</tbody>
<?php } }
else
{ ?>
<?php for($j=1;$j<15;$j++) { ?>
<tbody id="vehicle_details<?php if($j>1) echo $j; ?>">

<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Vehicle <?php echo $j; ?></span>
</td>


</tr>
<input type="hidden" name="model_id[]" value="<?php echo $model_id ?>"  />

<tr>
<td>Vehicle Model Year<span class="requiredField">* </span> : </td>
				<td>
					<select id="model" name="model_year[]">
                        <option value="-1" >--Please Select Model Year--</option>
                       <?php
					   for($i=date('Y');$i>=1990;$i--)
					   {
						 ?>
                          <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                         <?php  
						   }
					    ?>
                     </select> 
                            </td>
</tr>

<tr>
<td>Vehicle Color<span class="requiredField">* </span> : </td>
				<td>
					<select id="color" name="vehicle_color_id[]">
                        <option value="-1" >--Please Select Vehicle Color--</option>
                      <?php $models = listVehicleColors();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['vehicle_color_id'] ?>"><?php echo $model['vehicle_color']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<tr>
<td>Godown<span class="requiredField">* </span> : </td>
				<td>
					<select id="godown" name="godown_id[]">
                        <option value="-1" >--Please Select Godown--</option>
                      <?php $models = listGodowns();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['godown_id'] ?>"><?php echo $model['godown_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>

<tr>
       <td>Vehicle Condition<span class="requiredField">* </span> :</td>
           
           
       
        <td>
					<select id="condition" name="condition[]"  onchange="toggleRegNumber(this,this.value)">
                        <option value="1" selected="selected" >NEW</option>
                        <option value="0"  >OLD</option>
                            </select> 
                            </td>
 </tr>
 
<tr style="display:none;">
<td class="firstColumnStyling">
Reg Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_reg_no" name="vehicle_reg_no[]"  placeholder="Reg No Eg: GJ1AB1234 !" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"/><span id="agerror" class="availError">Reg Number already taken!</span>	
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Engine Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_engine_no" name="vehicle_engine_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"/><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="vehicle_chasis_no" name="vehicle_chasis_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')"/><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td>Service Book Number<span class="requiredField">* </span> : </td>
				<td>
					
                    <input type="text" id="service_book"  name="service_book_no[]" placeholder="Only Digits!"  />
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Cylinder Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_cylinder_no" name="cng_cylinder_no[]"  placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/engineNo.php','')"/><span id="agerror" class="availError">Engine Number already taken!</span>	

</td>
</tr>

<tr>
<td class="firstColumnStyling">
CNG Kit Number<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" id="cng_kit_no" name="cng_kit_no[]" placeholder="Only Digits!" onblur="checkAvailibilty(this,'agerror','ajax/chasisNo.php','')"/><span id="agerror" class="availError">Chasis Number already taken!</span>	

</td>
</tr>

<tr>
<td width="220px">Basic Price<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="basic_price[]" id="basic_price" placeholder="Only Digits!" />
                            </td>
</tr>


</tbody>
<?php } ?>
<?php } ?>
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit Opening"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/settings/vehicle_settings/model_settings/"; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>
function toggleRegNumber(condition_el,condition)
{
reg_no_tr_el = $(condition_el).parent().parent().next()[0];
if(condition==0)
$(reg_no_tr_el).show();
else
$(reg_no_tr_el).hide();
}
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
$( "#to_ledger" ).combobox();


document.no_vehicles = <?php echo $no_of_vehicles+1; ?>;

 
</script>