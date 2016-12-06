<?php 
if(!isset($_GET['lid']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$receipt_id=$_GET['lid'];
$payment=getReceiptById($receipt_id);
if($payment=="error")
{ ?>
<script>
  window.history.back()
</script>
<?php
}
$customer_id=$payment['to_customer_id'];
if(validateForNull($customer_id) && is_numeric($customer_id))
{
	$customer=getCustomerDetailsByCustomerId($customer_id);
	$ledger_customer_id='C'.$customer_id;
	
}
$ledger_id=$payment['to_ledger_id'];
$by_account_id=$payment['from_ledger_id'];
if(validateForNUll($ledger_id) && is_numeric($ledger_id))
{
$to_ledger=getLedgerById($ledger_id);
$ledger_customer_id='L'.$ledger_id;
}
$by_account=getLedgerById($by_account_id);	

if($payment['auto_rasid_type']==5)
$sales_id = $payment['auto_id'];
else
$sales_id = 0;
$kasar_jv=getKasarJvForSalesId($receipt_id);

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
<input type="hidden" name="lid" value="<?php echo $receipt_id; ?>"  />
<input type="hidden" name="sales_id" value="<?php if(is_numeric($sales_id)) echo $sales_id; else echo 0; ?>"  />
<input type="hidden" name="auto_rasid_type" value="<?php if(is_numeric($sales_id)) echo 5; else echo 0; ?>"  />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo  date('d/m/Y',strtotime($payment['trans_date'])); ?>"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" value="<?php echo $payment['amount']; ?>" /> </td>
</tr>

<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<select id="by_ledger" name="from_ledger_id">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listAccountingLedgers();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['ledger_id']; ?>" <?php if($bank_cash_ledger['ledger_id']==$by_account_id){ ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>

<tr>
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                <?php if($payment['auto_rasid_type']==5) { ?>
                <input type="hidden" name="to_ledger_id" value="<?php if(is_numeric($payment['to_ledger_id'])) echo 'L'.$payment['to_ledger_id']; else if(is_numeric($payment['to_customer_id'])) echo 'C'.$payment['to_customer_id']; ?>" />
                <?php } else { ?>
                
                   <select id="to_ledger" name="to_ledger_id"  >
                    	<option selected="selected"></option>
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
                   <?php } ?> 
                            </td>
</tr>

<?php if(is_numeric($sales_id) && $sales_id
>0) { ?>
<tr>
	<td>Kasar :</td>
    <td><input type="text" name="kasar_amount" id="kasar_amount" placeholder="Only Digits!" value="<?php  if(is_array($kasar_jv)) echo $kasar_jv['amount']; ?>" /><select name="kasar_type">
    <option value="0" <?php if(is_array($kasar_jv) && is_numeric($kasar_jv['from_customer_id'])) { ?> selected="selected" <?php } ?>>Minus (-)</option>
      <option value="1" <?php if(is_array($kasar_jv) && is_numeric($kasar_jv['to_customer_id'])) { ?> selected="selected" <?php } ?>>Plus (+)</option>
    </select></td>
</tr>
<?php } ?>

<tr>

<td class="firstColumnStyling">
Remarks (ctrl + g to change english/gujarati) : 
</td>

<td>
<textarea name="remarks" id="transliterateTextarea"><?php echo $payment['remarks']; ?></textarea>
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
 
</script>