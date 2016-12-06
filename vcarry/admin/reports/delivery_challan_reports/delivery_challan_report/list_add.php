<div class="jvp"><?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['agency_id']) && $_SESSION['cUninvoicedDeliveryChallanReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cUninvoicedDeliveryChallanReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Delivery Challan Reports</h4>
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
<td> From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['from'])) echo $_SESSION['cUninvoicedDeliveryChallanReport']['from']; ?>"/>	
                 </td>
</tr>



<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['to'])) echo $_SESSION['cUninvoicedDeliveryChallanReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td> Invoice Generated : </td>
				<td>
				<select name="invoice">
                	<option value="" <?php if((isset($_SESSION['cUninvoicedDeliveryChallanReport']['invoice']) && !is_numeric($_SESSION['cUninvoicedDeliveryChallanReport']['invoice'])) || !isset($_SESSION['cUninvoicedDeliveryChallanReport']['invoice'])) { ?> selected="selected" <?php }  ?>>Both</option>
                    <option value="0" <?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['invoice']) && is_numeric($_SESSION['cUninvoicedDeliveryChallanReport']['invoice']) && $_SESSION['cUninvoicedDeliveryChallanReport']['invoice']==0  ) {?> selected="selected"<?php } ?>>No</option>
                    <option value="1" <?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['invoice']) && is_numeric($_SESSION['cUninvoicedDeliveryChallanReport']['invoice']) && $_SESSION['cUninvoicedDeliveryChallanReport']['invoice']==1  ) {?> selected="selected"<?php } ?>>Yes</option>
                </select>
                 </td>
</tr>

<tr>
<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>



</table>

</form>

  
<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cUninvoicedDeliveryChallanReport']['emi_array'];
	
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
    
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Challan No</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Customer</label> 
     <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Remarks</label> 
       
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
     <thead>
    	<tr> <th class="heading"></th>
        	 <th class="heading">No</th>
             <th class="heading">Date</th>
              <th class="heading"><?php echo DELIVERY_CHALLAN_NAME; ?> No</th>
              <th class="heading">Debit</th>
               <th class="heading">Remarks</th>
              <th class="heading no_print"><?php echo SALES_NAME; ?></th>
              
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
      <tbody>
        
        <?php
		
		$no=0;
		foreach($emi_array as $receipt)
		{
			if(is_numeric($receipt['to_ledger_id']))
			{
				$debit_name = getLedgerNameFromLedgerId($receipt['to_ledger_id']);
			}
			else 
			{
				$debit_name = getCustomerNameByCustomerId($receipt['to_customer_id']);
			}
		 ?>
          <tr class="resultRow">
          <td></td>
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo date('d/m/Y',strtotime($receipt['trans_date'])); ?>
            </td>
            <td><?php echo $receipt['challan_no']; ?>
            </td>
           
            <td><?php echo $debit_name; ?>
            </td>
          	  <td><?php echo $receipt['remarks']; ?>
            </td>
             <td class="no_print">
          
             <a target="_blank" href="<?php  echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=delivery_challan&id='.$receipt['delivery_challan_id'];  ?>"><button title="View this entry" class="btn viewBtn <?php echo "btn-success"; ?>"><?php ?>Print Delivery Challan</button></a>
              <a target="_blank" href="<?php if(is_numeric($receipt['sales_id'])) echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$receipt['sales_id']; else echo  WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?id='.$receipt['delivery_challan_id']; ?>"><button title="View this entry" class="btn viewBtn <?php if(is_numeric($receipt['sales_id'])) echo "btn-success"; else echo "btn-warning"; ?>"><?php if(is_numeric($receipt['sales_id'])) echo "View"; else echo "Create"; ?> Invoice</button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=details&id='.$receipt['delivery_challan_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?view=edit&id='.$receipt['delivery_challan_id']; ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/delivery_challan/index.php?action=delete&lid='.$receipt['delivery_challan_id'];  ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    <?php  } ?>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['from']) && $_SESSION['cUninvoicedDeliveryChallanReport']['from']!="") echo $_SESSION['cUninvoicedDeliveryChallanReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cUninvoicedDeliveryChallanReport']['to']) && $_SESSION['cUninvoicedDeliveryChallanReport']['to']!="") echo $_SESSION['cUninvoicedDeliveryChallanReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
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
