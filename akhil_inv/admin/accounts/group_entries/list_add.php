<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Transaction For Ledgers Group</h4>
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
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['ledgerGroupEntriess']['from'])) echo $_SESSION['ledgerGroupEntriess']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['ledgerGroupEntriess']['to'])) echo $_SESSION['ledgerGroupEntriess']['to']; ?>"/>	
                 </td>
</tr>



<tr>
<td>Ledgers Group<span class="requiredField">* </span> : </td>
				<td>
					<!--<input type="text" id="to_ledger" name="to_ledger_id" /> -->
                   <select id="to_ledger" name="ledger_id" >
                    	<option value="" selected="selected"></option>
                        <option value="-1" <?php if($_SESSION['ledgerGroupEntriess']['group_id']==-1) { ?> selected="selected" <?php } ?> >ALL Customers</option>
                          <option value="-2" <?php if($_SESSION['ledgerGroupEntriess']['group_id']==-2) { ?> selected="selected" <?php } ?> >ALL Ledgers</option>
                          <option value="-5" <?php if($_SESSION['ledgerGroupEntriess']['group_id']==-5) { ?> selected="selected" <?php } ?> >Cash Group</option>
                    <?php
					$ledgers=listLedgerGroups();
					foreach($ledgers as $ledger)
					{
					?>
                    <option value="<?php echo $ledger['group_id']; ?>" <?php if($ledger['group_id']==$_SESSION['ledgerGroupEntriess']['group_id']) { ?>  selected="selected"<?php } ?>><?php echo $ledger['group_name']; ?></option>			
                    <?php	
						}
					 ?>
                    </select><span class="DateError customError">Please select a Ledger!</span>
                            </td>
</tr>

<tr>
<td>Transactions : </td>
				<td>
					<select name="transaction_type[]" class="city_area selectpicker" multiple="multiple"  id="city_area1" >
                    	 <option value="1" <?php if(in_array(1,$_SESSION['ledgerGroupEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?> >Payments</option>
                         <option value="2" <?php if(in_array(2,$_SESSION['ledgerGroupEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Receipts</option>
                         <option value="3" <?php if(in_array(3,$_SESSION['ledgerGroupEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Journal Entries</option>
                         <option value="4" <?php if(in_array(4,$_SESSION['ledgerGroupEntriess']['transaction_array'])) { ?>  selected="selected"<?php } ?>>Contra Entries</option>
                    </select>
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

  <?php  if(isset($_SESSION['ledgerGroupEntriess']['entries_array']))
{ ?>
<h4 class="headingAlignment">Transaction For <?php 
if(isset($_SESSION['ledgerGroupEntriess']['from']) && validateForNull($_SESSION['ledgerGroupEntriess']['from']))
	{	
	$from=$_SESSION['ledgerGroupEntriess']['from'];
	}
	else
	{
	$from=getBooksStartingDateForLedgerCustomer($id);
	}
	
		if(isset($_SESSION['ledgerGroupEntriess']['to']) && validateForNull($_SESSION['ledgerGroupEntriess']['to']))
	{	
	$to=$_SESSION['ledgerGroupEntriess']['to'];
	}
	else
	{
	$to=getBooksEndingDateForLedgerCustomer($id);
	}
if(isset($_SESSION['ledgerGroupEntriess']['group_id']) && validateForNull($_SESSION['ledgerGroupEntriess']['group_id'])) { if($_SESSION['ledgerGroupEntriess']['group_id']!=-1 && $_SESSION['ledgerGroupEntriess']['group_id']!=-2) $display_name = getLedgerGroupNameByID($_SESSION['ledgerGroupEntriess']['group_id'])." (".$from;
else if($_SESSION['ledgerGroupEntriess']['group_id']==-1)
$display_name="All Customers (".$from; 
else if($_SESSION['ledgerGroupEntriess']['group_id']==-2)
$display_name="All Ledgers (".$from; 
	 if(isset($to))
	 $display_name = $display_name." - ".$to;
	 else
$display_name = $display_name." - ALL";	  
	 $display_name = $display_name.")";
	 echo $display_name; }else{ ?><?php } ?></h4>
     <hr class="firstTableFinishing" />
     	<div>
 <?php

 
	 $transactions_array=$_SESSION['ledgerGroupEntriess']['entries_array'];	
	 $net_balance=0;
	foreach($transactions_array as $id => $transaction_array)
	{
	$i=0;
	$payments=$transaction_array[0];
	$head_type=$transaction_array[1];
	
	
	
	
	
	
	if($head_type==0)
	{
	$contras=$transaction_array[2];
	$ledger_id=str_replace('L','',$id);
	$ledger_id=intval($ledger_id);
	$ledger_type=0;
	$ledger = getLedgerById($ledger_id);
			$head_id = $ledger['head_id'];
			$head = getHeadById($head_id);
			$display_name=$ledger['ledger_name']." (".$head['head_name'].")";
	}
	else if($head_type==1)
	{
	$jvs=$transaction_array[2];	
		if(substr($id, 0, 1) === 'L')
		{
			$ledger_id=str_replace('L','',$id);
			$ledger_id=intval($ledger_id);
			$ledger_type=0;
			$ledger = getLedgerById($ledger_id);
			$head_id = $ledger['head_id'];
			$head = getHeadById($head_id);
			$display_name=$ledger['ledger_name']." (".$head['head_name'].")";
		}
		else if(substr($id, 0, 1) === 'C')
		{
		$ledger_id=str_replace('C','',$id);
		$ledger_id=intval($ledger_id);	
		$main_customer_id=$ledger_id;
		$ledger_type=1;
			if(is_numeric($main_customer_id))
						  { 
						  $main_file_id=getFileIdFromCustomerId($main_customer_id);
						  $main_file_no=getFileNumberByFileId($main_file_id);
						  $main_reg_no=getRegNoFromFileID($main_file_id);
						  $main_customer=getCustomerNameANDCoByFileId($main_file_id);
						  $customer_name = $main_file_no.' '.$main_customer['customer_name']." ".$main_reg_no;
						  } 
		
		}	
	}

	$openingBalance=getOpeningBalanceForLedgerForDate($id,$from);
	
	 ?>    

    <table id="" class="adminContentTable ">
    <thead>

    <?php if($_SESSION['ledgerGroupEntriess']['group_id']==-5) {  ?><tr><th colspan="9"><?php echo  getCompanyNameByLedgerId($ledger_id); ?></th></tr><?php } ?>
    <tr>
    <th colspan="9">Transactions For <?php if($ledger_type==1) echo $customer_name."(Customer)"; else echo $display_name; ?> (<?php echo $from ?>-<?php echo $to; ?>)</th>
    </tr>
    </thead>
    <thead>
    	<tr>
       
        <th class="heading no_sort">No</th>
        <th class="heading date">Date</th>
        <th class="heading no_sort">Particulars</th>
        <th class="heading no_sort">Mode</th>
        <th class="heading no_sort">Type</th>
        <th class="heading no_sort">Debit</th>
        <th class="heading no_sort">Credit</th>
        <th class="heading no_sort">Closing Balance</th>
        <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
      
      	  <tr class="resultRow">
         
        	<td><?php echo ++$i; ?></td>
            <td><?php echo $from;  ?></td>
            <td><?php echo "Opening Balance";  ?></td>
            <td><?php  ?></td>
             <td></td>
            <td><?php if($openingBalance>=0) echo round($openingBalance,2); ?></td>
            <td><?php if($openingBalance<0) echo -round($openingBalance,2);  ?></td>
            <td><?php if($openingBalance>=0) echo round($openingBalance,2)." Dr"; else echo -round($openingBalance,2)." Cr"; ?></td>
           <td class="no_print"></td>
            </tr>
        <?php
		if($payments!="error" && is_array($payments))
		{
			foreach($payments as $payment)
			{
				
				if(array_key_exists('payment_id',$payment))
				{
				$from_ledger_id=$payment['from_ledger_id'];
				$from_customer_id=$payment['from_customer_id'];
				 ?>
				 <tr class="resultRow">
					
					<td><?php echo ++$i; ?></td>
					
					 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
					</td>
					
					  <td><?php 
						if($head_type==0)
						  {
						  if(is_numeric($from_ledger_id))  
						  echo getLedgerNameFromLedgerId($from_ledger_id);
						  else if(is_numeric($from_customer_id))
						  { 
						  $file_id=getFileIdFromCustomerId($from_customer_id);
						  $file_no=getFileNumberByFileId($file_id);
						  $reg_no=getRegNoFromFileID($file_id);
						  $customer=getCustomerNameANDCoByFileId($from_customer_id);
						  echo $customer[1]." "; echo $file_no." "; if($reg_no) echo $reg_no;}
						  } 
						  else echo getLedgerNameFromLedgerId($payment['to_ledger_id']); ?>
					</td>
					 <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
					</td>
					 <td width="160px"><?php echo "Payment"; ?>
					</td>
					 <td><?php if($head_type==1) echo round($payment['amount'],2); ?>
					</td>
					<td><?php if($head_type==0) echo round($payment['amount'],2); ?>
					</td>
				   <td><?php if($head_type==1) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==0) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
					</td>
					<td class="no_print"> <a href="<?php if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/payment/index.php?view=details&id='.$payment['payment_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/index.php?view=loanDetails&id='.getFileIdFromLoanId($payment['auto_id']); ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
				   
					
				  
		  
				</tr>
         <?php  }
		else if(array_key_exists('receipt_id',$payment))
				{	
					$to_ledger_id=$payment['to_ledger_id'];
					$to_customer_id=$payment['to_customer_id'];
					 ?>
					 <tr class="resultRow">
						
						<td><?php echo ++$i; ?></td>
						
						 <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
						</td>
						  <td><?php if($head_type==0){
									if(is_numeric($to_ledger_id))  echo getLedgerNameFromLedgerId($to_ledger_id);
									else if(is_numeric($to_customer_id)) 
									{ 
									$file_id=getFileIdFromCustomerId($to_customer_id); 
									$file_no=getFileNumberByFileId($file_id); 
									$reg_no=getRegNoFromFileID($file_id); 
									$customer=getCustomerNameANDCoByFileId($file_id); 
									echo $customer[1]; echo " ".$file_no." "; if($reg_no) echo $reg_no;				
									}
							  } else  echo getLedgerNameFromLedgerId($payment['from_ledger_id']); ?>
						</td>
						 <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
						</td>
						 <td width="160px"><?php echo "Receipt"; ?>
						</td>
						<td><?php if($head_type==0) echo round($payment['amount'],2); ?>
						</td>
						<td><?php if($head_type==1) echo round($payment['amount'],2); ?>
						</td>
					   <td><?php if($head_type==0) $openingBalance=$openingBalance+round($payment['amount'],2); else if($head_type==1) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
						</td>
					   
						<td class="no_print"> <a href="<?php
						   if($payment['auto_rasid_type']==2)
						{
						$emi_id=getEMIIDFromPaymentId($payment['auto_id']);
						$loan_id=getLoanIdFromEmiId($emi_id);
						$file_id=getFileIdFromLoanId($loan_id);
						}
						else if($payment['auto_rasid_type']==3)
						{
							$file_id=getFileIdFromPenaltyId($payment['auto_id']);
						}
						else if($payment['auto_rasid_type']==4)
						{
							$file_id=$payment['auto_id'];
						}
						 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/receipt/index.php?view=details&id='.$payment['receipt_id']; } else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/payment/index.php?view=details&id='.$file_id.'&lid='.$payment['auto_id'].'&state='.$emi_id; else if($payment['auto_rasid_type']==3) echo WEB_ROOT.'admin/customer/payment/penalty/index.php?view=details&id='.$file_id.'&state='.$payment['auto_id']; else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
						
					  
			  
					</tr>
         <?php } 
		else if(array_key_exists('jv_id',$payment))
				{
		
		$to_ledger_id=$payment['to_ledger_id'];
		$to_customer_id=$payment['to_customer_id'];
		$from_ledger_id=$payment['from_ledger_id'];
		$from_customer_id=$payment['from_customer_id'];
		
		
		
		if($ledger_type==0 && 'L'.$to_ledger_id==$id)
		$jv_type=0; // debit jv
		else if($ledger_type==0 && 'L'.$from_ledger_id==$id)
		$jv_type=1;  //credit jv
		else if($ledger_type==1 && 'C'.$to_customer_id==$id)
		$jv_type=0;
		else if($ledger_type==1 && 'C'.$from_customer_id==$id)
		$jv_type=1;
		
		$debit_details = getDebitJVCDsForJVID($payment['jv_id']);
$credit_details = getCreditJVCDsForJVID($payment['jv_id']);
$debit_string = "";
$credit_string = "";
foreach($debit_details as $debit_detail)
{
	$debit_detail=$debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id);
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
		$name = $customer['customer_name'];
		
	}
	$debit_string = $debit_string.$name." <br>";
}

foreach($credit_details as $debit_detail)
{
	$debit_detail = $debit_detail[0];
	$debit_detail_array = explode(' : ',$debit_detail);
	$ledger_customer_id = $debit_detail_array[0];
	$amount = $debit_detail_array[1];
	
	if(substr($ledger_customer_id, 0, 1) == 'L') // if the pament is done to a general account ledger
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		$name = getLedgerNameFromLedgerId($ledger_customer_id);
	}
	else if(substr($ledger_customer_id, 0, 1) == 'C') // if payment is done to a customer
	{
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		
		
		$customer=getCustomerDetailsByCustomerId($ledger_customer_id);
		$name = $customer['customer_name'];
		
	}
	$credit_string = $credit_string.$name." <br>";
}

		
		?>
         <tr class="resultRow">
         	
        	<td><?php echo ++$i; ?></td>
            
             <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
            </td>
            
            <td><?php if($jv_type==0) // if it is a accounts ledger and a debit jv
			 		{ echo $credit_string; }
					else if($jv_type==1) // if it is a accounts ledger and a credit jv
			 		{ echo $debit_string; }
					
					 ?>
            </td>
             <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
            </td>
             <td width="160px"><?php echo "Journal" ?>
            </td>
             <td><?php if($jv_type==0) echo round($payment['amount'],2);  ?>
            </td>
             <td><?php if($jv_type==1) echo round($payment['amount'],2);  ?>
            </td>
             <td><?php if($jv_type==0) $openingBalance=$openingBalance+round($payment['amount'],2); else if($jv_type==1) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
            </td>
           
             <td class="no_print"> <a href="<?php 
			 
			  if($payment['auto_rasid_type']==2)
						{
						$emi_id=getEMIIDFromPaymentId($payment['auto_id']);
						$loan_id=getLoanIdFromEmiId($emi_id);
						$file_id=getFileIdFromLoanId($loan_id);
						}
						else if($payment['auto_rasid_type']==3)
						{
							$file_id=getFileIdFromPenaltyId($payment['auto_id']);
						}
						else if($payment['auto_rasid_type']==4)
						{
							$file_id=$payment['auto_id'];
						}
			 
			 if($payment['auto_rasid_type']==0) { echo WEB_ROOT.'admin/accounts/transactions/jv/index.php?view=details&id='.$payment['jv_id']; } else if($payment['auto_rasid_type']==1) echo WEB_ROOT.'admin/customer/index.php?view=loanDetails&id='.$file_id; else if($payment['auto_rasid_type']==2) echo WEB_ROOT.'admin/customer/payment/index.php?view=details&id='.$file_id.'&lid='.$payment['auto_id'].'&state='.getEMIIDFromPaymentId($payment['auto_id']);else if($payment['auto_rasid_type']==4) echo WEB_ROOT.'admin/file/index.php?view=closureDetails&id='.$file_id;  ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
					</td>
					   
           
            
          
  
        </tr>
         <?php } 
		else if(array_key_exists('contra_id',$payment))
				{
		$to_ledger_id=$payment['to_ledger_id'];
		$from_ledger_id=$payment['from_ledger_id'];
		
		
		 ?>
         <tr class="resultRow">
         	
        	<td><?php echo ++$i; ?></td>
            
             <td class="date"> <?php  echo date('d/m/Y',strtotime($payment['trans_date'])); ?>
            </td>
              <td><?php if($head_type==0){
				  		if(is_numeric($to_ledger_id))  echo getLedgerNameFromLedgerId($to_ledger_id);
					  	else  echo getLedgerNameFromLedgerId($payment['from_ledger_id']); } ?>
            </td>
             <td><?php if($payment['auto_rasid_type']==0)echo "Manual"; else echo "Auto"; ?>
            </td>
             <td width="160px"><?php echo "Contra"; ?>
            </td>
             <td><?php if($to_ledger_id==$ledger_id) echo round($payment['amount'],2); ?>
            </td>
            <td><?php if($from_ledger_id==$ledger_id) echo round($payment['amount'],2); ?>
            </td>
           <td><?php if($to_ledger_id==$ledger_id) $openingBalance=$openingBalance+round($payment['amount'],2); else if($from_ledger_id==$ledger_id) $openingBalance=$openingBalance-round($payment['amount'],2); if($openingBalance>=0) echo $openingBalance." Dr"; else echo -$openingBalance." Cr";  ?>
            </td>
           
           <td class="no_print"> <a href="<?php  echo WEB_ROOT.'admin/accounts/transactions/contra/index.php?view=details&id='.$payment['contra_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
          
  
        </tr>
         <?php 
		 ?>
         <?php
				}
				
				}$net_balance = $net_balance + $openingBalance;  }}}?>
         </tbody>
    </table>
    </div>
   
<?php  ?>      
<span>NET CLOSING BALANCE = <?php if($net_balance>=0) echo $net_balance." DR"; else echo -$net_balance."CR"; ?></span>
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