<div class="jvp"><?php if(isset($_SESSION['cSalesReport']['agency_id']) && $_SESSION['cSalesReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cSalesReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Sales Reports Party Wise</h4>
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
                    <option value="<?php echo $ledger['id']; ?>" <?php if( $ledger['id']==$_SESSION['cSalesReport']['ledger_id']) { ?>  selected="selected"<?php } ?>><?php echo $ledger['name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td> From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSalesReport']['to'])) echo $_SESSION['cSalesReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSalesReport']['to'])) echo $_SESSION['cSalesReport']['to']; ?>"/>	
                 </td>
</tr>

<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>

</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['cSalesReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cSalesReport']['emi_array'];
		
	$or_from_ledger = $_SESSION['cSalesReport']['ledger_id'];
	
	
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
   
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Supplier Name</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Amount</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Tax Amount</label> 
     <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Total</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Remarks</label>
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
         <th class="heading no_print"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
           
        	<th class="heading">No</th>
            
             <th class="heading">Date</th>
             <th class="heading">Particulars</th>
            
             <th class="heading">Amount</th>
             <th class="heading">Tax Amount</th>
            <th class="heading">Total</th>
             <th class="heading">Remarks</th>
            <th class="heading no_print btnCol" ></th>
            
        </tr>
    </thead>
    <tbody>
      
       <?php
	$total =0;
		$tax =0;
		
		foreach($emi_array as $job_card)
		{
		
				if(substr($or_from_ledger, 0, 1) == 'L')
				{
					$from_ledger=str_replace('L','',$or_from_ledger);
					$from_ledger=intval($from_ledger);
					$from_customer="NULL";
					
					$head_type=getLedgerHeadType($from_ledger);
					if($head_type==3 && is_numeric($job_card['from_ledger_id']))
					$ledger_name = getLedgerNameFromLedgerId($job_card['from_ledger_id']);
					else if($head_type==3)
					{
							$customer = getCustomerDetailsByCustomerId($job_card['from_customer_id']);
							$ledger_name= $customer['customer_name'];
							
					}
					else 
					$ledger_name = getLedgerNameFromLedgerId($job_card['to_ledger_id']);
				}
				else if(substr($or_from_ledger, 0, 1) == 'C')
				{
					$from_ledger=str_replace('C','',$or_from_ledger);
					$from_customer=intval($from_ledger);
					$from_ledger="NULL";
					
					$ledger_name=  getLedgerNameFromLedgerId($job_card['to_ledger_id']);
					}	
		 ?>
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
            
            <td><?php echo date('d/m/Y',strtotime($job_card['trans_date'])); ?>
            </td>
            <td><?php echo $ledger_name; ?>
            </td>
            <td><?php $total = $total +$job_card['amount'];  echo $job_card['amount']; ?>
            </td>
             <td>Rs.<?php $tax = $tax + $job_card['tax_amount']; echo $job_card['tax_amount']; ?>
            </td>
             <td align="center">Rs.<?php if(is_numeric($job_card['tax_amount']) && $job_card['tax_amount']>0) echo round($job_card['amount']+$job_card['tax_amount']); else echo $job_card['amount']; ?>
            
            </td>
             <td align="center"><?php echo $job_card['remarks']; ?>
            
            </td>
    
           
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/purchase_inventory/index.php?view=details&id='.$job_card['purchase_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
        </tr>
        <?php  } ?>
         
            </tbody>
    </table>
   
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cSalesReport']['from']) && $_SESSION['cSalesReport']['from']!="") echo $_SESSION['cSalesReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cSalesReport']['to']) && $_SESSION['cSalesReport']['to']!="") echo $_SESSION['cSalesReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  <span class="Total">Amount : <?php echo $total; ?></span>
    <span class="Total" style="margin-left:20px;">Tax Amount: <?php echo $tax; ?></span>
    <span class="Total" style="margin-left:20px;">Total Amount: <?php echo $total+$tax; ?></span>
<?php  } ?>      
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
