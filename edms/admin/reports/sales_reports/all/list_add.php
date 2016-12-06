<div class="jvp"><?php if(isset($_SESSION['cAllSalesReport']['agency_id']) && $_SESSION['cAllSalesReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cAllSalesReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Sales Reports</h4>
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
				 <input autocomplete="off" type="text"  name="from_date" id="from_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cAllSalesReport']['from'])) echo $_SESSION['cAllSalesReport']['from']; ?>"/>	
                 </td>
</tr>



<tr>
<td> To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="to_date" id="to_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cAllSalesReport']['to'])) echo $_SESSION['cAllSalesReport']['to']; ?>"/>	
                 </td>
</tr>

<tr>
<td> Outstanding Amount (>=) : </td>
				<td>
				 <input autocomplete="off" type="text"  name="outstanding_amount" id="outstanding_amount" placeholder="Click to select Date!"  value="<?php if(isset($_SESSION['cAllSalesReport']['outstanding_amount'])) echo $_SESSION['cAllSalesReport']['outstanding_amount']; ?>"/>	
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
 <?php if(isset($_SESSION['cAllSalesReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cAllSalesReport']['emi_array'];
	
		if(isset($_SESSION['cAllSalesReport']['outstanding_amount']) && is_numeric($_SESSION['cAllSalesReport']['outstanding_amount']))
$outstanding_amount = $_SESSION['cAllSalesReport']['outstanding_amount'];
else
$outstanding_amount=-9999;
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>  
    
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Date</label> 
       <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Sales Account</label> 
  		<input class="showCB" type="checkbox" id="4" checked="checked"   /><label class="showLabel" for="4">Customer</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"   /><label class="showLabel" for="5">Amount</label> 
     <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Remarks</label> 
       
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
         <th class="heading no_print"><input type="checkbox" id="selectAllTR" name="selectAllTR"  /></th>
           
        	<th class="heading">No</th>
             <th class="heading">Type</th>
             <th class="heading">Invoice No</th>
             <th class="heading date">Date</th>
             <th class="heading">Customer Name</th>
             <?php if(EDMS_MODE==1) { ?>
             <th class="heading">Reg No</th>
             <?php } ?>
              <?php if(TAX_MODE==1) { ?>
             <th class="heading">Service</th>
             <?php } ?>
             <th class="heading">Amount</th>
             <th class="heading"> Received</th>
             <th class="heading"> Paid</th>
            <th class="heading"> Left</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
                   </tr>
    </thead>
    <tbody>
      
        <?php
	    $total =0;
		$paid =0;
		$paid_other = 0;
		$kasar_total_other=0;
		$due = 0;	
		$no=0;
		$kasar_total = 0;
		$we_paid = 0;
		$from =$_SESSION['cAllSalesReport']['from'];
		$to =$_SESSION['cAllSalesReport']['to'];
		foreach($emi_array as $job_card)
		{
		
		if($job_card['type']=='JOB CARD' || ($job_card['type']=='RECEIPT' && $job_card['auto_rasid_type']==3 ))	
		{
			
		$vehicle_id = $job_card['vehicle_id'];
		$job_card_id = $job_card['auto_id'];
		$customer_id = getCustomerIDFromVehicleId($vehicle_id);
		$customer = getCustomerDetailsByCustomerId($customer_id);
		$vehicle = getVehicleById($vehicle_id);	
		$vehicle_model = getVehicleModelById($vehicle['model_id']);
		$invoice_no = getFinalizeDetailsForJobCard($job_card_id);
		$receipt_amount_total = getReceiptAmountAndKasarAmountForJobCardId($job_card_id);
	
		$receipt_amount_period = getReceiptAmountAndKasarAmountForJobCardId($job_card_id,$from,$to);
		
		$kasar = $receipt_amount_period[1];
		$receipt_amount=$receipt_amount_period[0];
		$kasar_other = $receipt_amount_total[1] - $kasar;
		$receipt_amount_other=$receipt_amount_total[0] - $receipt_amount;
		$total_amount  = getTotalAmountForJobCard($job_card_id);
		$total = $total + $total_amount;
		$paid = $paid +$receipt_amount;
		$paid_other = $paid_other + $receipt_amount_other;
		$kasar_total = $kasar_total + $kasar;
		$kasar_total_other = $kasar_total_other + $kasar_other;
		if($total_amount-$receipt_amount-$kasar>=$outstanding_amount)
		{
		 ?>
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo "JOB CARD(".$job_card['job_card_no'].")"; ?>
            </td>
            <td><?php if($invoice_no) echo $invoice_no; ?>
            </td>
            <td><?php echo date('d/m/Y',strtotime($job_card['job_card_datetime'])); ?>
            </td>
            <td><?php echo $customer['customer_name']; ?>
            </td>
            <?php if(EDMS_MODE==1) { ?>
            <td><?php echo $vehicle['vehicle_reg_no']; ?>
            </td>
            <?php } ?>
             <td>Rs.<?php echo round($total_amount); ?>
            </td>
             <td align="center">Cur : Rs.<?php echo round($receipt_amount); ?>
             <br />
             Oth : Rs.<?php echo round($receipt_amount_other); ?>
             <br />
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?view=allReceipts&id='.$job_card_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a>
            </td>
            <td></td>
             <td align="center">Rs.<?php echo round($total_amount-$receipt_amount-$kasar); ?>
             <br />
             Kasar(<?php echo round($kasar+$kasar_other); ?>)
             <br />
             
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/customer/vehicle/jobCard/receipt/index.php?&id='.$job_card_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-warning"><span class="">Add Payment</span></button></a> 
            </td>
    		
            <td class="no_print"> <a class="no_print" href="<?php if(!validateForNull($invoice_no
			)) echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=finalize&id='.$job_card_id; else echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=invoice&id='.$job_card_id; ?>"><button title="Finalize this entry" class="btn <?php if(!validateForNull($invoice_no
			)){ ?>btn-danger<?php }else { ?>btn-success<?php } ?>"><?php if(!validateForNull($invoice_no
			)) { ?>Finalize<?php } else { ?>Invoice<?php } ?></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
        </tr>
         <?php }} 
		else if ($job_card['type']=="SALES"  || ($job_card['type']=='RECEIPT' && $job_card['auto_rasid_type']==5 ))
		{
			if($job_card['type']=="SALES")
		    $sales_id=$job_card['id'];
			else if($job_card['type']=='RECEIPT' && $job_card['auto_rasid_type']==5)
			$sales_id = $job_card['auto_id'];
			
			$sales=getSaleById($sales_id);
			$receipt_amount_total = getReceiptAndKasarAmountForSalesId($sales_id);
			$receipt_amount_period = getReceiptAndKasarAmountForSalesId($sales_id,$from,$to);
			$kasar = $receipt_amount_period[1];
			$receipt_amount=$receipt_amount_period[0];
			$kasar_other = $receipt_amount_total[1] - $kasar;
			$receipt_amount_other=$receipt_amount_total[0] - $receipt_amount;
			$tax_amount = getTotalTaxForSalesId($sales_id);
			
			if(is_numeric($sales['to_ledger_id']))
			{
				
			$ledger_type=getLedgerHeadType($sales['to_ledger_id']);
			
			if(is_numeric($ledger_type) && $ledger_type==0)
			{ $type =1;
			$kasar_payment=getKasarPaymentForCashSale($sales_id);
			$kasar = $kasar_payment['amount'];
			}
			else $type=0;
			}
			else
			$type=0;
			if($type==1)
			{
			$remaining_amount=0;
			$paid = $paid +$sales['amount'] + $tax_amount - $kasar;
			$kasar_total = $kasar_total + $kasar;
			}
			else
			{
		    $remaining_amount = $sales['amount'] + $tax_amount - $receipt_amount - $kasar;	
			
	     	$paid = $paid +$receipt_amount;
			$paid_other = $paid_other + $receipt_amount_other;
			$kasar_total = $kasar_total + $kasar;
			$kasar_total_other = $kasar_total_other + $kasar_other;
			}
			$total = $total + $sales['amount'] + $tax_amount;
			if($remaining_amount>=$outstanding_amount)
			{
				if(TAX_MODE==1)
				$non_stock_items = getNonStockItemForSaleId($sales['sales_id']);
		?>
        <tr class="resultRow">
        <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
           <td><?php if($type==1) echo "CASH "; ?>SALES</td>
           <td><?php echo $sales['invoice_no'] ?></td>
            <td><?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
            </td>
            <td><?php if(is_numeric($job_card['to_ledger_id'])) echo $job_card['to_ledger_name']; else echo $job_card['customer_name']; ?>
            </td>
            <?php if(EDMS_MODE==1)  { ?>
            <td></td>
            <?php } ?>
            <?php if(TAX_MODE==1) { ?>
            <td><?php 	for($j=0; $j<count($non_stock_items); $j++)
			{
	
			$inventory_item = $non_stock_items[$j]['sales_item_details'];	
			echo getItemNameFromItemId($inventory_item['item_id'])." X ".round(($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)),2)."Rs <br>";
			}
			?></td>
            <?php } ?>
            <td><?php echo ($sales['amount']+$tax_amount)." Rs"; ?>
            </td>
            
          	 
             <td align="center"><?php if($type==1) echo $sales['amount'] + $tax_amount-$kasar;else { echo " Cur : Rs.".round($receipt_amount)."<br> Oth : Rs.".round($receipt_amount_other);} ?>
             <br />
             <?php if($type!=1 || !isset($type)) { ?>
              <a class="no_print" href="<?php  echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=allReceipts&id='.$sales_id; ?>"><button style="width:120px;" title="View this entry" class="btn  btn-success"><span class="">View Payment</span></button></a><?php } ?>
            </td>
            <td></td>
             <td align="center" ><?php echo number_format($remaining_amount)." Rs"; ?>   
               <br />
             Kasar(<?php echo round($kasar); ?>)
             <br /> <?php if($type!=1 || !isset($type)) { ?> <a class="no_print" href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?sales_id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-warning">Add Payment</button></a> <?php } ?>
            </td>
       
             <td class="no_print">  <?php if($type==1) { ?>  <a  href="<?php echo WEB_ROOT.'admin/accounts/transactions/cash_sale/index.php?view=invoice&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-success">Invoice</button></a> <?php }else{ ?> <a  href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=invoice&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn btn-success">Invoice</button></a><?php } ?>
            </td>
            <td class="no_print"> <?php if($type==1) { ?> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/cash_sale/index.php?view=details&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a> <?php }else { ?><a href="<?php echo WEB_ROOT.'admin/accounts/transactions/sales_inventory/index.php?view=details&id='.$sales['sales_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a><?php } ?>            </td>
            
   
  
        </tr>
         <?php }}
		 else if ($job_card['type']=='RECEIPT' && $job_card['auto_rasid_type']!=3 && $job_card['auto_rasid_type']!=5)
		{
			
			$receipt_id = $job_card['id'];
			
			$sales=getReceiptById($receipt_id);
			$paid =$paid + $sales['amount'];
			$total = $total + $sales['amount'];
			
		?>
        <tr class="resultRow">
        <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
           <td><?php  ?>RECEIPT</td>
           <td><?php echo "NA"; ?></td>
            <td><?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
            </td>
            <td><?php if(is_numeric($sales['to_ledger_id'])) echo getLedgerNameFromLedgerId($sales['to_ledger_id']); else echo getCustomerNameByCustomerId($sales['to_customer_id']); ?>
            </td>
            <?php if(EDMS_MODE==1 || TAX_MODE==1)  { ?>
            <td>-</td>
            <?php } ?>
            <td><?php echo ($sales['amount'])." Rs"; ?>
            </td>
            
          	 
             <td align="center">
             <?php echo ($sales['amount'])." Rs"; ?>
            </td>
             <td align="center" >
            </td>
             <td class="no_print"> 
            </td>
            <td></td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$sales['id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
   
  
        </tr>
         <?php }
		  else if ($job_card['type']=='PAYMENT')
		{
			
			$receipt_id = $job_card['id'];
			
			$sales=getPaymentById($receipt_id);
			$we_paid = $we_paid + $sales['amount'];
			
		?>
        <tr class="resultRow">
        <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
           <td><?php  ?>PAYMENT</td>
           <td><?php echo "NA"; ?></td>
            <td><?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
            </td>
            <td><?php if(is_numeric($sales['from_ledger_id'])) echo getLedgerNameFromLedgerId($sales['from_ledger_id']); else echo getCustomerNameByCustomerId($sales['from_customer_id']); ?>
            </td>
             <?php if(EDMS_MODE==1  || TAX_MODE==1)  { ?>
            <td>-</td>
            <?php } ?>
            <td><?php echo ($sales['amount'])." Rs"; ?>
            </td>
            
          	 
             <td align="center">
            </td>
             <td align="center" >
             <?php echo ($sales['amount'])." Rs"; ?>
            </td>
             <td class="no_print"> 
            </td>
            <td></td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$sales['id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
   
  
        </tr>
         <?php }
		  else if ($job_card['type']=='PURCHASE')
		{
		
			$receipt_id = $job_card['id'];
			
			$sales=getPurchaseById($receipt_id);
			$we_paid = $we_paid + $sales['amount'];
			
		?>
        <tr class="resultRow">
        <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$no; ?>
            </td>
           <td><?php  ?>PURCHASE</td>
           <td><?php echo "NA"; ?></td>
            <td><?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
            </td>
            <td><?php if(is_numeric($sales['from_ledger_id'])) echo getLedgerNameFromLedgerId($sales['from_ledger_id']); else echo getCustomerNameByCustomerId($sales['from_customer_id']); ?>
            </td>
             <?php if(EDMS_MODE==1  || TAX_MODE==1)  { ?>
            <td></td>
            <?php } ?>
            <td><?php echo ($sales['amount'])." Rs"; ?>
            </td>
            
          	 
             <td align="center">
            </td>
             <td align="center" >
             <?php echo ($sales['amount'])." Rs"; ?>
            </td>
             <td class="no_print"> 
            </td>
            <td></td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$sales['id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
   
  
        </tr>
         <?php }
		 ?>
		 <?php
		 } ?>
         
            </tbody>
    </table>
    <?php  } ?>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cAllSalesReport']['from']) && $_SESSION['cAllSalesReport']['from']!="") echo $_SESSION['cAllSalesReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cAllSalesReport']['to']) && $_SESSION['cAllSalesReport']['to']!="") echo $_SESSION['cAllSalesReport']['to']; else echo "NA"; ?></td>
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
    <span class="Total">Total Amount : <?php echo $total; ?></span>
    <span class="Total" style="margin-left:20px;"> Amount Received (Cur): <?php echo $paid; ?></span>
      <span class="Total" style="margin-left:20px;"> Amount Received (Oth): <?php echo $paid_other; ?></span>
     <span class="Total" style="margin-left:20px;"> Amount Paid: <?php echo $we_paid; ?></span>
     <span class="Total" style="margin-left:20px;"> Kasar: <?php echo $kasar_total+$kasar_total_other; ?></span>
    <span class="Total" style="margin-left:20px;"> Amount Dues: <?php echo $total-$paid-$paid_other-$kasar_total-$kasar_total_other; ?></span>
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
