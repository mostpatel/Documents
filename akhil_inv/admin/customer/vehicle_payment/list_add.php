<?php if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$customer_id=$_GET['id'];
if(!is_numeric($customer_id))
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$receipts=getPaymentsForVehicleForCustomerId($customer_id);
$customer = getCustomerDetailsByCustomerId($customer_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment"> Payment </h4>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['../payment/PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="customer_id" value="<?php echo $customer['customer_id']; ?>" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getCurrentDateForUser($_SESSION['edmsAdminSession']['admin_id']))); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" value="<?php echo $emi; ?>" /><span class="DateError customError">Amount Should less than <?php echo -$balance; ?> Rs. !</span>
                            </td>
</tr>

<tr id="financer_tr">
<td>Vehicle<span class="requiredField">* </span> : </td>
				<td>
					<select id="finanacer" name="vehicle_id" onchange="createDropDownVehiclePurchaseLedger(this.value);">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listVehiclesForCustomer($customer_id);
					foreach($bank_cash_ledgers as $vehicle)
					{
						$vehicle_model = getVehicleModelById($vehicle['model_id']);
					?>
                    <option value="<?php echo $vehicle['vehicle_id']; ?>"><?php echo $vehicle_model['model_name']." | R : ".$vehicle['vehicle_reg_no']." | "." E : ".$vehicle['vehicle_engine_no']." | C : ".$vehicle['vehicle_chasis_no']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>


<tr>
<td>Mode<span class="requiredField">* </span> : </td>
				<td>
					<select  id="by_ledger" name="to_ledger_id">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listAccountingLedgers();
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
<td>Customer / Purchase Dealer<span class="requiredField">* </span> : </td>
				<td>
                	
					<select id="customer_purchase" name="from_ledger_id"  >
                    	<option value="C<?php echo $customer_id; ?>"><?php $customer = getCustomerDetailsByCustomerId($customer_id);  echo $customer['customer_name']; ?></option>
                        
                    </select> 
                  
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Remarks (ctrl + g to change english/gujarati) : 
</td>

<td>
<textarea name="remarks" id="transliterateTextarea"></textarea>
</td>
</tr>

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Payment"  class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$customer_id; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
</td>
</tr>

</table>

</form>
<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Payments</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading">Amount</th>
              <th class="heading">Credit</th>
             <th class="heading">Mode</th>
              <th class="heading">Vehicle</th>
              <th class="heading">Reg No</th>
              <th class="heading">Chasis No</th>
              <th class="heading">Engine No</th>
              <th class="heading">Remarks</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$no=0;
		foreach($receipts as $receipt)
		{
			$by_account_id=$receipt['to_ledger_id'];
			
			$by_account=getLedgerById($by_account_id);
			$to_ledger_id = $receipt['from_ledger_id'];
			$to_customer_id = $receipt['from_customer_id'];
			if(is_numeric($to_ledger_id))		
			$ledger_name=getLedgerNameFromLedgerId($to_ledger_id);
			else if(is_numeric($to_customer_id))
			{
			$from_customer =  getCustomerDetailsByCustomerId($to_customer_id);
			$ledger_name = $from_customer['customer_name'];
			}
			$vehicle = getVehicleById($receipt['auto_id']);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
            <td><?php echo $receipt['amount']; ?>
            </td>
             <td><?php echo $ledger_name; ?>
            </td>
            <td><?php echo $by_account['ledger_name']; ?>
            </td>
          	 <td><?php $vehicle_model = getVehicleModelById($vehicle['model_id']); echo $vehicle_model['model_name']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_chasis_no']; ?>
            </td>
             <td><?php echo $vehicle['vehicle_engine_no']; ?>
            </td>
             <td><?php echo $receipt['remarks']; ?>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_payment/index.php?view=details&id='.$receipt['payment_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_payment/index.php?view=edit&id='.$receipt['payment_id']; ?>"><button title="Edit this entry" class="btn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/customer/vehicle_payment/index.php?action=delete&lid='.$receipt['payment_id'].'&id='.$customer_id;  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
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
/* $( "#to_ledger" ).combobox(); */
 
</script>