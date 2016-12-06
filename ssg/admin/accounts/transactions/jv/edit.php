<?php 
if(!isset($_GET['lid']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$jv_id=$_GET['lid'];
$payment=getJVById($jv_id);
$debit_details = getDebitJVCDsForJVID($jv_id);
$credit_details = getCreditJVCDsForJVID($jv_id);
if(count($debit_details)>1 || count($credit_details)>1)
{
header("Location: ".WEB_ROOT."admin/accounts/transactions/multi_jv");
exit;
}
$trans_date=getNextDate($payment['trans_date']);
$trans_date = date('d/m/Y',strtotime($trans_date));
if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
foreach($debit_details as $debit_detail)
{
	$debit_detail=$debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$to_ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
}
foreach($credit_details as $debit_detail)
{
	$debit_detail = $debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
}
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Receipt </h4>
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
<form onsubmit="return submitTransaction();" id="addLocForm" action="<?php echo 'index.php?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="lid" value="<?php echo $jv_id; ?>"  />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime($payment['trans_date'])); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" value="<?php echo $payment['amount']; ?>" />
                            </td>
</tr>

<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					 <select  id="combobox" name="to_ledger_id" >
                    	<option value="" selected="selected"></option>
                    <?php
					$ledgers=listCustomerAndLedgers();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="<?php echo $ledger['id']; ?>" <?php if($ledger['id']==$to_ledger_customer_id){ ?> selected="selected" <?php } ?>><?php echo $ledger['name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                    <span id="comboboxbal_span" style="margin-left:40px;"><?php $opening_balance = getOpeningBalanceForLedgerForDate($to_ledger_customer_id,$trans_date); if($opening_balance>=0) echo $opening_balance." DR"; else echo -$opening_balance." CR"; ?></span>
                            </td>
</tr>

<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
					
                   <select id="combobox2" name="from_ledger_id" >
                    	<option value="" selected="selected"></option>
                    <?php
					$ledgers=listCustomerAndLedgers();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="<?php echo $ledger['id']; ?>" <?php if($ledger['id']==$ledger_customer_id){ ?> selected="selected" <?php } ?>><?php echo $ledger['name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                     <span id="combobox2bal_span" style="margin-left:40px;"><?php $opening_balance = getOpeningBalanceForLedgerForDate($ledger_customer_id,$trans_date); if($opening_balance>=0) echo $opening_balance." DR"; else echo -$opening_balance." CR"; ?></span>
                            </td>
</tr>



<td class="firstColumnStyling">
Remarks : 
</td>

<td>
<textarea name="remarks" id="remarks"><?php echo $payment['remarks']; ?></textarea>
</td>
</tr>

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit"  class="btn btn-warning">
<?php if(isset($_SERVER['HTTP_REFERER'])) { ?><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" class="btn btn-success" value="Back"/></a><?php } ?>
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
			
			getLedgerOpeningBalance(ui.item.option.value,this.element[0]);	
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