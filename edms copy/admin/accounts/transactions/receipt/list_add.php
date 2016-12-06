<?php if(isset($_GET['sales_id']) && is_numeric($_GET['sales_id'])) {
$sales_id = $_GET['sales_id'];
$sales=getSaleById($sales_id);
$to_ledger_id = $sales['to_ledger_id'];
$to_customer_id = $sales['to_customer_id'];
$receipt_amount = getReceiptAmountForSalesId($sales_id);
$tax_amount = getTotalTaxForSalesId($sales_id);
$remaining_amount = $sales['amount'] - $receipt_amount + $tax_amount;
}
$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$curent_companny = getCurrentCompanyForUser($admin_id);
$oc_id = $curent_companny[0];
?>
<?php if(defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==1) { ?>
<?php echo SALES_NAME." Company : ".$sales['our_company_name']; ?>
<?php } ?>
<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/multi_receipt/index.php"><button class="btn btn-success"> Receipt</button></a>
	<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/payment/index.php"><button class="btn btn-success"> Payment</button></a> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/multi_jv/index.php"><button class="btn btn-success"> JV </button></a>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/contra/index.php"><button class="btn btn-success"> Contra</button></a>
    <?php if(TAX_MODE==0) { ?>
    <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/purchase_inventory/index.php"><button class="btn btn-success"> Purchase</button></a>
    <?php } ?>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/delivery_challan/index.php"><button class="btn btn-success"> <?php echo DELIVERY_CHALLAN_NAME; ?></button></a>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php"><button class="btn btn-success"><?php echo SALES_NAME; ?></button></a>
      <?php if(TAX_MODE==0) { ?>
      <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/credit_note/index.php"><button class="btn btn-success"> Credit Note</button></a>
       <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/debit_note/index.php"><button class="btn btn-success"> Debit Note</button></a>
       <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/inventory_jv/index.php"><button class="btn btn-success"> Inventory JV</button></a>
       <?php } ?>
     <a href="<?php echo WEB_ROOT; ?>admin/accounts/ledgers/index.php"><button class="btn btn-success"> Add Ledger</button></a>
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
<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="sales_id" value="<?php if(is_numeric($sales_id)) echo $sales_id; else echo 0; ?>"  />
<input type="hidden" name="customer_id" value="<?php if(is_numeric($to_customer_id)) echo $to_customer_id; else echo 0; ?>"  />
<input type="hidden" name="auto_rasid_type" value="<?php if(is_numeric($sales_id)) echo 5; else echo 0; ?>"  />
<input type="hidden" name="oc_id" value="<?php echo $oc_id ?>" />
<?php if(isset($_GET['sales_id']) && is_numeric($_GET['sales_id'])) { ?>
<input type="hidden" name="from_customer" value="<?php echo $to_customer_id ?>" />
<?php } ?>
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Payment Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="payment_date" id="payment_date" class="datepicker1" placeholder="click to select date!" value="<?php echo date('d/m/Y',strtotime(getTodaysDate())); ?>" autofocus="autofocus"/><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td width="220px">Amount<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="amount" id="amount" placeholder="Only Digits!" value="<?php echo $remaining_amount; ?>" /><span class="DateError customError">Amount Should less than <?php echo -$balance; ?> Rs. !</span>
                            </td>
</tr>

<tr>
<td>By (Debit)<span class="requiredField">* </span> : </td>
				<td>
					<select id="by_ledger" name="from_ledger_id" onchange="createChequeDetails()">
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
<td>To (Credit)<span class="requiredField">* </span> : </td>
				<td>
                <?php if(is_numeric($to_ledger_id) || is_numeric($to_customer_id)) { ?>
					<input type="hidden" id="to_ledger_id" name="to_ledger_id" value="<?php if(is_numeric($to_ledger_id)) echo "L".$to_ledger_id; else echo "C".$to_customer_id; ?>"  /> 
                 <?php }else{ ?>   
                   <select id="to_ledger" name="to_ledger_id" >
                    	<option value="-1" selected="selected"></option>
                    <?php
					$ledgers=listCustomerAndLedgersWithoutPurchaseAndSales();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="<?php echo $ledger['id']; ?>"><?php echo $ledger['name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                    <?php } ?>
                            </td>
</tr>
<?php if(is_numeric($sales_id)) { ?>
<tr>
	<td>Kasar :</td>
    <td><input type="text" name="kasar_amount" id="kasar_amount" placeholder="Only Digits!" value="<?php echo 0; ?>" /><select name="kasar_type">
    <option value="0">Minus (-)</option>
   <!--   <option value="1">Plus (+)</option> -->
    </select></td>
</tr>
<?php } ?>
<tr>

</table>
<table id="chequePaymentTable" class="insertTableStyling no_print" style="display:none;">

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
                    <option value="<?php echo $bank_cash_ledger['payment_mode_id']; ?>"><?php echo $bank_cash_ledger['payment_mode']; ?></option>			
                    <?php	
						}
					 ?>
                    </select>
                            </td>
</tr>
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!"  value="<?php  if(defined("DEF_CHQ_VALUES") && DEF_CHQ_VALUES==1) echo "NA"; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!"  value="<?php if(defined("DEF_CHQ_VALUES") && DEF_CHQ_VALUES==1) echo "NA"; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque No<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_no" id="cheque_no" placeholder="Only Digits!" value="<?php if(defined("DEF_CHQ_VALUES") && DEF_CHQ_VALUES==1) echo "000000"; ?>" />
                            </td>
</tr>
<tr>
<td width="220px">Cheque Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="cheque_date" id="cheque_date" class="datepicker3" placeholder="click to select date!"  /><span class="DateError customError">Please select a date!</span>
                            </td>
</tr>
</table>

<table style="display:none;" id="receipt_ref_table" class="insertTableStyling no_print">
<tr>
<td width="220px">Receipt Type<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="ref_type" name="ref_type" onchange="changeRefFeild(this.value)" >
                    	<option value="0" selected="selected">NEW</option>
                  		<option value="1" >Advance</option>
                        <option value="2" >Against Sales</option>
                        <option value="3" >On Account</option>
                    </select>
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_new_tr">
<td>Receipt Ref<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" id="pay_ref_new" name="ref" /> 
                  	
                            </td>
</tr>

<tr style="display:none;" id="pay_ref_against_tr">
<td>Receipt Ref<span class="requiredField">* </span> : </td>
				<td>
					<select type="text" id="pay_ref_aganist" name="ref" style="width:250px;" >
                    </select> 
                   
                </td>
                
</tr>


 
</table>

<table>
<tr>

<td class="firstColumnStyling" width="220px">
Remarks (ctrl + g to change english/gujarati) : 
</td>

<td>
<textarea name="remarks" id="transliterateTextarea"></textarea>
</td>
</tr>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Receipt"  class="btn btn-warning">
<a href="<?php if(isset($_GET['sales_id']) && is_numeric($_GET['sales_id']) && is_numeric($to_customer_id)) { echo WEB_ROOT."admin/customer/index.php?view=details&id=".$to_customer_id; } else echo  WEB_ROOT."admin/accounts/"; ?>"><input type="button" class="btn btn-success" value="Back"/></a>
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
$( "#to_ledger" ).combobox();
 
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