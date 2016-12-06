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
					$ledgers=listSalesLedgers();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="L<?php echo $ledger['ledger_id']; ?>" <?php if('L'.$ledger['ledger_id']==$_SESSION['cSalesReport']['ledger_id']) { ?>  selected="selected"<?php } ?>><?php echo $ledger['ledger_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td> From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSalesReport']['from'])) echo $_SESSION['cSalesReport']['from']; ?>"/>	
                 </td>
</tr>

<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSalesReport']['to'])) echo $_SESSION['cSalesReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td> Outstanding Amount (>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="outstanding_amount" id="outstanding_amount" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cSalesReport']['outstanding_amount'])) echo $_SESSION['cSalesReport']['outstanding_amount']; ?>"/>	
                 </td>
</tr>

<tr>

<tr>
<td>Customer Group : </td>
				<td>
					<select name="group_id" class="broker selectpicker"   id="group_id" >
                    	 <option value="-1">--Please Select--</option>
                          <?php
						 $brokers = listCustomerGroups();
						  
                          
                            foreach($brokers as $broker)
                              {
                             ?>
                             <option value="<?php echo $broker['group_id'] ?>" <?php if(isset($_SESSION['cSalesReport']['group_id'])){ if($broker['group_id']==$_SESSION['cSalesReport']['group_id']) { ?> selected="selected" <?php }} ?>><?php echo $broker['group_name'] ?></option					>
                             <?php } 
						  
							 ?>
                    </select>
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
		
		if(isset($_SESSION['cSalesReport']['outstanding_amount']) && is_numeric($_SESSION['cSalesReport']['outstanding_amount']))
$outstanding_amount = $_SESSION['cSalesReport']['outstanding_amount'];
else
$outstanding_amount=0;
		
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
 <div id="deleteSelectedDiv"><button id="deleteSelected" class="btn viewBtn">delete selected rows</button></div>      
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Type</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Invoice No</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Date</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Due Days</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Customer Name</label> 
     <input class="showCB" type="checkbox" id="7" checked="checked"   /><label class="showLabel" for="7">Reg No</label> 
     <input class="showCB" type="checkbox" id="8" checked="checked"   /><label class="showLabel" for="8">Amount</label> 
     <input class="showCB" type="checkbox" id="9" checked="checked"   /><label class="showLabel" for="9">Amount Received</label> 
     <input class="showCB" type="checkbox" id="10" checked="checked"   /><label class="showLabel" for="10">Amount Left</label> 
       
    </div>
      <form action="index.php?action=sendSMS" method="post" >
    <input type="submit" value="send Sms" class="btn-warning btn"/>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
         <th class="heading no_print"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
           
        	<th class="heading">No</th>
             <th class="heading">Type</th>
             <th class="heading">Invoice No</th>
             <th class="heading">Date</th>
              <th class="heading">Due Days</th>
             <th class="heading">Customer Name</th>
             <?php if(EDMS_MODE==1) { ?>
             <th class="heading">Reg No</th>
             <?php } ?>
              <?php if(TAX_MODE==1) { ?>
             <th class="heading">Service</th>
             <?php } ?>
             <th class="heading">Amount</th>
             <th class="heading">Amount Received</th>
            <th class="heading">Amount Left</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
        </tr>
    </thead>
    <tbody>
      
       <?php
	$total =0;
		$paid =0;
		$due = 0;	
		$no=0;
		foreach($emi_array as $job_card)
		{
		
		if($job_card['auto_rasid_type']==3)	
		{
			
		$vehicle_id = $job_card['vehicle_id'];
		$job_card_id = $job_card['auto_id'];
		$sales_id = getSalesIdFromjobCardId($job_card_id);
		$customer_id = getCustomerIDFromVehicleId($vehicle_id);
		$customer = getCustomerDetailsByCustomerId($customer_id);
		$vehicle = getVehicleById($vehicle_id);	
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$invoice_no = getFinalizeDetailsForJobCard($job_card_id);
		$receipt_amount = getReceiptAmountForJobCardId($job_card_id);
		$total_amount  = getTotalAmountForJobCard($job_card_id);
		$total = $total + $total_amount;
		$paid = $paid +$receipt_amount;
		if($total_amount-$receipt_amount>=$outstanding_amount)
		{
			
		 ?>
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $sales_id; ?>" /></td>
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo "JOB CARD(".$job_card['job_card_no'].")"; ?>
            </td>
            <td><?php if($invoice_no) echo $invoice_no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
             <td>
            <?php echo floor( ( strtotime(getTodaysDateTime()) - strtotime($job_card['job_card_datetime']) ) / (60*60*24) );  ?>
            </td>
            <td><?php echo $customer['customer_name']; ?>
            </td>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
             <td>Rs.<?php echo round($total_amount); ?>
            </td>
             <td align="center">Rs.<?php echo round($receipt_amount); ?>
             <br />
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=allReceipts&id='.$job_card_id; ?>"><button type="button" style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a>
            </td>
             <td align="center">Rs.<?php echo round($total_amount-$receipt_amount); ?>
             <br />
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?&id='.$job_card_id; ?>"><button style="width:120px;" title="View this entry" type="button" class="btn  btn-warning"><span class="">Add Payment</span></button></a> 
            </td>
    
            <td class="no_print"> <a class="no_print" href="<?php if(!validateForNull($invoice_no
			)) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card_id; else echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card_id; ?>"><button type="button" title="Finalize this entry" class="btn <?php if(!validateForNull($invoice_no
			)){ ?>btn-danger<?php }else { ?>btn-success<?php } ?>"><?php if(!validateForNull($invoice_no
			)) { ?>Finalize<?php } else { ?>Invoice<?php } ?></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$customer_id; ?>"><button title="View Customer Profile" type="button" class="btn viewBtn"><span class="view">C</span></button></a>
            </td>
            
        </tr>
         <?php }} 
		else
		{
		    $sales_id=$job_card['sales_id'];
			$sales=getSaleById($sales_id);
			$receipt_amount = getReceiptAmountForSalesId($sales_id);
			$tax_amount = getTotalTaxForSalesId($sales_id);
			
			if(is_numeric($sales['to_ledger_id']))
			{
			$ledger_type=getLedgerHeadType($sales['to_ledger_id']);
			if($ledger_type==0) $type =1;
			
			}
		else;
		$ledger_type=1;
			if($type==1)
			{
			$remaining_amount=0;
			$paid = $paid +$sales['amount'] + $tax_amount;
			}
			else
			{
		   $remaining_amount = $sales['amount'] + $tax_amount - $receipt_amount;	
			
	     	$paid = $paid +$receipt_amount;
			}
			$total = $total + $sales['amount'] + $tax_amount;
			if($remaining_amount>=$outstanding_amount)
			{
				if(TAX_MODE==1)
				$non_stock_items = getNonStockItemForSaleId($sales['sales_id']);
		?>
        <tr class="resultRow">
        <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $sales_id; ?>" /></td>
        	<td><?php echo ++$no; ?>
            </td>
           <td>SALES (<?php $invoice_type= getInvoiceTypeById($sales['retail_tax']); echo $invoice_type['invoice_type']; ?>)</td>
           <td><?php echo $sales['invoice_no'] ?></td>
            <td><?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?></td>
            <td>
            <?php echo floor( ( strtotime(getTodaysDateTime()) - strtotime($sales['trans_date']) ) / (60*60*24) );  ?>
            </td>
           
            <td><?php if(is_numeric($job_card['to_ledger_id'])) echo $job_card['to_ledger_name']; else echo $job_card['customer_name']; ?>
            </td>
             <?php if(TAX_MODE==1) { ?>
            <td><?php 	for($j=0; $j<count($non_stock_items); $j++)
			{
	
			$inventory_item = $non_stock_items[$j]['sales_item_details'];	
			echo getItemNameFromItemId($inventory_item['item_id'])." X ".round(($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)),2)."Rs <br>";
			}
			?></td>
            <?php } else if(EDMS_MODE==1) { ?>
            <td>-</td>
            <?php } ?>
            <td><?php echo ($sales['amount']+$tax_amount)." Rs"; ?>
            </td>
            
          	 
             <td align="center">Rs.<?php if($type==1) echo $sales['amount'] + $tax_amount;else echo round($receipt_amount); ?>
             <br />
             <?php if($type!=1 || !isset($type)) { ?>
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=allReceipts&id='.$sales_id; ?>"><button style="width:120px;" title="View this entry" type="button" class="btn  btn-success"><span class="">View Payment</span></button></a><?php } ?>
            </td>
             <td align="center" ><?php echo number_format($remaining_amount)." Rs"; ?>  <br />  <?php if($type!=1 || !isset($type)) { ?> <a class="no_print" href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?sales_id='.$job_card['sales_id'] ?>"><button type="button" title="View this entry" class="btn viewBtn btn-warning">Add Payment</button></a> <?php } ?>
            </td>
             <td class="no_print"> <a c href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=invoice&id='.$sales['sales_id'] ?>"><button type="button" title="View this entry" class="btn viewBtn btn-success">Invoice</button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$sales['sales_id'] ?>"><button type="button" title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            	<?php if(is_numeric($job_card['to_customer_id'])) {?>
                                  <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$job_card['to_customer_id']; ?>"><button title="View Customer Profile" type="button" class="btn viewBtn"><span class="view">C</span></button></a>
                                  <?php } ?>
            </td>
            
   
  
        </tr>
         <?php }}} ?>
         
            </tbody>
    </table>
    </form>
    <?php  } ?>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cSalesReport']['from']) && $_SESSION['cSalesReport']['from']!="") echo $_SESSION['cSalesReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cSalesReport']['to']) && $_SESSION['cSalesReport']['to']!="") echo $_SESSION['cSalesReport']['to']; else echo "NA"; ?></td>
        <td> Customer : <?php if(isset($_SESSION['cSalesReport']['ledger_id']) && $_SESSION['cSalesReport']['ledger_id']!="") echo $_SESSION['cSalesReport']['ledger_id']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  <span class="Total">Total Amount : <?php echo $total; ?></span>
    <span class="Total" style="margin-left:20px;"> Amount Paid: <?php echo $paid; ?></span>
    <span class="Total" style="margin-left:20px;"> Amount Dues: <?php echo $total-$paid; ?></span>
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
