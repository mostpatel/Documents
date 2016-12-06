<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Transaction For Ledger</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">

<tr >
<td width="260px;">From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['ledgerEntries']['from'])) echo $_SESSION['ledgerEntries']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['ledgerEntries']['to'])) echo $_SESSION['ledgerEntries']['to']; ?>"/>	
                 </td>
</tr>



<tr>
<td>Ledger<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="to_ledger" name="ledger_id" >
                    	<option value="" selected="selected"></option>
                    <?php
					$ledgers=listCustomerAndLedgersWithBankCash();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="<?php echo $ledger['id']; ?>" <?php if( $ledger['id']==$_SESSION['ledgerEntries']['ledger_id']) { ?>  selected="selected"<?php } ?>><?php echo $ledger['name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>


					
                    	 
                            
<tr>

<td></td>
				<td>
             
				 <input type="submit" value="Generate" class="btn btn-warning"/>
                <a href="<?php echo WEB_ROOT."admin/accounts/" ?>"><input type="button" class="btn btn-success" value="Back"/></a>	
                </td>
</tr>


</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['ledgerEntries']['entries_array']))
{
	$id=$_SESSION['ledgerEntries']['ledger_id'];
	$transaction_array=$_SESSION['ledgerEntries']['entries_array'];
	$head_type=$_SESSION['ledgerEntries']['head_type'];
	
	
	
	if(isset($_SESSION['ledgerEntries']['from']) && validateForNull($_SESSION['ledgerEntries']['from']))
	{	
	$from=$_SESSION['ledgerEntries']['from'];
	}
	else
	{	
	$from=date('d/m/Y',strtotime(getBooksStartingDateForLedgerCustomer($id)));
	}
	
	if(isset($_SESSION['ledgerEntries']['to']) && validateForNull($_SESSION['ledgerEntries']['to']))
	{	
	$to=$_SESSION['ledgerEntries']['to'];
	}
	else
	{	
	$to=date('d/m/Y');
	}
	

	
	if($head_type==0)
	{
	$ledger_id=str_replace('L','',$id);
	$ledger_id=intval($ledger_id);
	$ledger_type=0;
	}
	else if($head_type==1)
	{
		if(substr($id, 0, 1) === 'L')
		{
			$ledger_id=str_replace('L','',$id);
			$ledger_id=intval($ledger_id);
			$ledger_type=0;
		}
		else if(substr($id, 0, 1) === 'C')
		{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);	
		$ledger_type=1;
		}	
	}
	
	$openingBalance=getOpeningBalanceForLedgerForDate($id,$from);
	
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Particulars</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Mode</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Type</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Amount</label> 
    </div>
    <table id="accountContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        <th class="heading ">No</th>
        <th class="heading date default_sort">Month</th>
        <th class="heading no_sort">Debit</th>
        <th class="heading no_sort">Credit</th>
        <th class="heading no_sort">Closing Balance</th>
        <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
      	  <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            <td><?php echo "Opening Balance";  ?></td>
            <td><?php if($openingBalance>=0) echo $openingBalance; ?></td>
            <td><?php if($openingBalance<0) echo -$openingBalance;  ?></td>
            <td><?php if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr"; ?></td>
           <td></td>
            </tr>
        <?php
		if($transaction_array!="error" && is_array($transaction_array))
		{
		foreach($transaction_array as $key => $value)
		{
			
			$debit_amount=$value[0];
			$credit_amount=$value[1];
			$payment=$value[2];
			$month_id=$value[3];
			$year=$value[4];
		 ?>
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
             <td class="date"> <a href="<?php echo 'index.php?action=listEntriesForMonth&id='.$id.'&month='.$month_id.'&year='.$year.'&from='.$from.'&to='.$to ?>"> <?php  echo $key; ?></a>
            </td>
             </td>
             <td><?php if($debit_amount>0) echo number_format($debit_amount,2); ?>
            </td>
            <td><?php if($credit_amount>0) echo number_format($credit_amount,2); ?>
            </td>
           <td><?php
		   
		     $openingBalance=$openingBalance+$payment;  if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
            </td>
            <td class="no_print"> <a href="<?php echo 'index.php?action=listEntriesForMonth&id='.$id.'&month='.$month_id.'&year='.$year.'&from='.$from.'&to='.$to ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
          
        </tr>
         <?php }}}
		 ?>
         </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
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