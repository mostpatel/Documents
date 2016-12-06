<?php $purchase_jvs = listPurchaseJvs(); ?>
<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/purchase/vehicle/index.php?view=list"><button class="btn btn-success">View Vehicle Purchases</button></a> </div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Purchase Vehicles </h4>
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

<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Purchase Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>




<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<select  id="by_ledger" name="to_ledger_id">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listPurchaseLedgers();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>"><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="to_ledger" name="from_ledger_id" >
                    	<option value="" selected="selected"></option>
                    <?php
					$ledgers=listSuppliers();
					foreach($ledgers as $ledger)
					{
						
					?>
                    <option value="L<?php echo $ledger['ledger_id']; ?>"><?php echo $ledger['ledger_name']; ?></option>			
                    <?php	
						}
					$customer_ledgers=listCustomerLegders();
					foreach($customer_ledgers as $ledger)
					{
						
					?>
                    <option value="<?php echo $ledger['id']; ?>"><?php echo $ledger['name']; ?></option>			
                    <?php	
						}	
					 ?>
                     
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

</table>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">Vehicle Details</h4>

<table id="insertVehicleTable" class="insertTableStyling no_print">

<tr>
<td width="220px">
No of Vehicles<span class="requiredField">* </span> : 
</td>
<td>
<select name="no_vehicles" id="no_vehicles" onchange="alterNoOfVehicles(this.value)" >
	<?php for($i=1;$i<14;$i++)
	{ ?>
    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
    <?php } ?>
    
</select>
</td>
</tr>

<?php for($j=1;$j<2;$j++) { ?>
<tbody id="vehicle_details<?php if($j>1) echo $j; ?>">

<tr>

<td colspan="2" class="firstColumnStyling">
<span class="headingAlignment">Vehicle <?php echo $j; ?></span>
</td>


</tr>


<tr>
<td>Vehicle Model<span class="requiredField">* </span> : </td>
				<td>
					<select id="vehicle_model" name="model_id[]">
                        <option value="-1" >--Please Select Model--</option>
                     			<?php $models = listVehicleModels();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['model_id'] ?>"><?php echo $model['model_name']; ?></option>
                                 <?php } ?>
                            </select> 
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
                        <option value="1" >NEW</option>
                        <option value="0" >OLD</option>
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
					
                    <input type="text" id="service_book"  name="service_book_no[]" placeholder="Only Digits!" />
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

<tr>
<td>Tax Group<span class="requiredField">* </span> : </td>
				<td>
					<select id="tax_group" name="tax_group_id[]">
                        <option value="-1" >--Please Select Tax--</option>
                      <?php $models = listTaxGroups();
									foreach($models as $model)
									{
								 ?>
                                 <option value="<?php echo $model['tax_group_id'] ?>"><?php echo $model['tax_group_name']; ?></option>
                                 <?php } ?>
                            </select> 
                            </td>
</tr>
<?php if(is_array($purchase_jvs) && count($purchase_jvs)>0) { 
foreach($purchase_jvs as $purchase_jv)
{
?>	
<tr>
	<td><?php echo getLedgerNameFromLedgerId($purchase_jv['ledger_id']); ?> :</td>
    <td><input type="text" name="purchase_jvs_array[<?php echo $j-1; ?>][<?php echo $purchase_jv['purchase_sales_jv_id']; ?>]" placeholder="Only Digits!" /></td>
</tr>
<?php	
}
?>

<?php } ?>
</tbody>
<?php } ?>
</table>
<hr class="firstTableFinishing" />

<table class="insertTableStyling no_print">

<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!"  />
                            </td>
</tr>

<tr>
<td>Payment Type<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="ref_type" name="ref_type" onchange="changeRefFeild(this.value)" >
                    	<option value="0" selected="selected">NEW</option>
                  		<option value="1" >Advance</option>
                        <option value="2" >Against Purchase</option>
                        <option value="3" >On Account</option>
                    </select>
                            </td>
</tr>

<tr  id="pay_ref_new">
<td>Payment Ref / Invoice No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" id="pay_ref_new" name="ref" /> 
                  
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_against">
<td>Payment Ref<span class="requiredField">* </span> : </td>
				<td>
					<select type="text" id="pay_ref_aganist" name="ref" >
                    </select> 
                </td>
</tr>




<td class="firstColumnStyling">
Remarks : 
</td>

<td>
<textarea name="remarks" id="remarks"></textarea>
</td>
</tr>

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Purchase"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/accounts/" ?>"><input type="button" class="btn btn-success" value="Back"/></a>
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


document.no_vehicles = 2;

 
</script>