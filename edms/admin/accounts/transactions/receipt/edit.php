<?php 
if(!isset($_GET['lid']))
{
header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$receipt_id=$_GET['lid'];
$payment=getReceiptById($receipt_id);
$extra_payment_details = getReceiptDetailsForReceiptId($receipt_id);
if($payment=="error")
{ ?>
<script>
  window.history.back();
  
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
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo  date('d/m/Y',strtotime($payment['trans_date'])); ?>" autofocus /><span class="DateError customError">Please select a date!</span>
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
					<select id="by_ledger" name="from_ledger_id" onchange="createChequeDetails()">
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=listAccountingLedgers($payment['oc_id']);
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
                <input type="hidden" id="to_ledger" name="to_ledger_id" value="<?php if(is_numeric($payment['to_ledger_id'])) echo 'L'.$payment['to_ledger_id']; else if(is_numeric($payment['to_customer_id'])) echo 'C'.$payment['to_customer_id']; ?>" />
                <?php if(is_numeric($payment['to_ledger_id'])) echo $to_ledger['ledger_name']; else if(is_numeric($payment['to_customer_id'])) echo $customer['customer_name']; ?>
                <?php } else { ?>
                
                   <select id="to_ledger" name="to_ledger_id"  >
                    	<option selected="selected"></option>
                    <?php
					$ledgers=listCustomerAndLedgersWithoutPurchaseAndSales();
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
   <!--   <option value="1" <?php if(is_array($kasar_jv) && is_numeric($kasar_jv['to_customer_id'])) { ?> selected="selected" <?php } ?>>Plus (+)</option> -->
    </select></td>
</tr>
<?php } ?>

</table>
<table id="chequePaymentTable" class="insertTableStyling no_print" <?php if(!$extra_payment_details){ ?> style="display:none;" <?php } else { ?> style="display:table" <?php } ?>>

<tr>
<td>Payment Mode<span class="requiredField">* </span> : </td>
				<td>
					<select  id="payment_mode" name="payment_mode_id" >
                    	<option value="-1">-- Please Select --</option>
                    <?php
					$bank_cash_ledgers=getAllPaymentModes();
					foreach($bank_cash_ledgers as $bank_cash_ledger)
					{
					?>
                    <option value="<?php echo $bank_cash_ledger['payment_mode_id']; ?>" <?php if($extra_payment_details['payment_mode_id']==$bank_cash_ledger['payment_mode_id']) { ?> selected="selected" <?php } ?>><?php echo $bank_cash_ledger['payment_mode']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!"  value="<?php  if($extra_payment_details) echo $extra_payment_details['bank_name']; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!"  value="<?php  if($extra_payment_details) echo $extra_payment_details['branch_name']; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!"  value="<?php  if($extra_payment_details) echo $extra_payment_details['chq_no']; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" id="cheque_date" class="datepicker3" placeholder="click to select date!" value="<?php  if($extra_payment_details) echo date('d/m/Y',strtotime($extra_payment_details['chq_date'])); ?>"  /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>
</table>

<table class="insertTableStyling no_print">

<tr>
<td width="220px">Receipt Type<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="ref_type" name="ref_type"  onchange="changeRefFeild(this.value)" >
                    	<option value="0" <?php if($payment['receipt_ref_type']==0) { ?> selected="selected" <?php } ?>>NEW</option>
                  		<option value="1"  <?php if($payment['receipt_ref_type']==1) { ?> selected="selected" <?php } ?> >Advance</option>
                        <option value="2"  <?php if($payment['receipt_ref_type']==2) { ?> selected="selected" <?php } ?> >Against Sales</option>
                        <option value="3"  <?php if($payment['receipt_ref_type']==3) { ?> selected="selected" <?php } ?> >On Account</option>
                    </select>
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_new">
<td> </td>
				<td>
					
                            </td>
</tr>

<tr  <?php if($payment['receipt_ref_type']!=2) { ?> style="display:none;" <?php } ?> id="pay_ref_against_tr">
<td>Receipt Ref<span class="requiredField">* </span> : </td>
				<td>
					<select type="text" id="pay_ref_aganist" name="ref" >
                    <option value="-1">-- Please Select --</option>
                    <?php if($payment['receipt_ref_type']==2) {
					$sales = generalSalesReports($ledger_customer_id,NULL,$payment['trans_date'],NULL,NULL,NULL,1,$payment['receipt_id']);
					foreach($sales as $s)
					{	 
					$label = $s['invoice_no']." ".date("d/m/Y",strtotime($s['trans_date']))." ".$s['outstanding_amount']." Rs";
						 ?>
					<option value="<?php echo $s['sales_id'] ?>" <?php if($payment['receipt_ref']==$s['sales_id']) { ?> selected="selected" <?php } ?>><?php echo $label; ?></option>
					
					<?php }} ?>
                    </select> 
                   
                </td>
                
</tr>
<tr>

<td width="220px" class="firstColumnStyling">
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
document.disablePeriodModal = 1;
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
			if(document.getElementById('ref_type').value>0)
			changeRefFeild(document.getElementById('ref_type').value);
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
			 $('#receipt_ref_table').show();
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
		   $('#receipt_ref_table').show();
          return;
        }
 			$('#receipt_ref_table').hide();
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
  <?php if($payment['auto_rasid_type']!=5) { ?> 
$( "#to_ledger" ).combobox();
 <?php } ?>
  function createChequeDetails()
{	
    var ledger_id =document.getElementById('by_ledger').value;
	
	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
	
    var is_bank_ledger=xmlhttp1.responseText;
	
// Before adding new we must remove previously loaded elements

	if(is_bank_ledger==1)
	$('#chequePaymentTable').show();
	else
	$('#chequePaymentTable').hide();
    }
  }
  var url=document.web_root+'json/ledger_head_id_bank.php?id='+ledger_id;
   xmlhttp1.open('GET', url, true );    
  xmlhttp1.send(null);
}
 
 
</script>