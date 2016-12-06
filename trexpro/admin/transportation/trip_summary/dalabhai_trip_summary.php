<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$delivery_challan_id=$_GET['id'];
$trip_memo=getTripSummaryById($delivery_challan_id);

if(is_array($trip_memo) && $trip_memo)
{
	$trips=getTripsBySummaryId($delivery_challan_id);
	$driver_name = getLedgerNameFromLedgerId($trips[0]['driver_id']);
	$truck_no = getTruckNoById($trips[0]['truck_id']);
	
}

$total_rows = 24 - count($trips);
?>
<link rel="stylesheet" href="summary.css"/>
<?php for($rep=0;$rep<2;$rep++) { ?>
<div class="insideCoreContent adminContentWrapper wrapper" <?php if($rep==0) { ?> style="margin-bottom:100px;" <?php } ?>>

<div class="mainClass">

<div class="header">
    
    <div class="companyName">
    G. Dalabhai Cargo Movers
    </div>  <!-- End of companyName -->
    
    <div class="companyAddress">
    Regd. Office : Behind Meghdoot Hotel, Sarangpur, Ahmedabad-2. 22163628, 22163458
    </div>  <!-- End of companyAddress -->
    
</div>  <!-- End of header -->

<div class="details">

    <div class="row">
      
      <div class="rowLeft">
      To :
      </div><!-- End of rowLeft -->
      
      <div class="rowRight">
      Driver's Name : <?php echo strtoupper($driver_name); ?>
      </div><!-- End of rowRight -->
      
      <div class="clrBoth"></div>
      
    </div> <!-- End of row -->
    
    <div class="row">
    
      <div class="middleRowLeft">
      Truck No : <?php echo strtoupper($truck_no); ?>
      </div><!-- End of middleRowLeft -->
      
      <div class="middleRowRight1">
      Adv. Rs. : <?php echo $trip_memo['advance']; ?>
      </div><!-- End of middleRowRight1 -->
      
      <div class="middleRowRight2">
      Date :<?php echo date('d/m/Y',strtotime($trip_memo['trip_memo_summary_date'])) ?>
      </div><!-- End of middleRowRight2 -->
      
      <div class="clrBoth"></div>
      
    </div> <!-- End of row -->
    
    <div class="row">
    
       <div class="rowLeft">
       Ahmedabad To :  <?php foreach($trips as $trip) {  echo strtoupper(getLedgerNameFromLedgerId($trip['to_branch_ledger_id'])).", "; } ?>
       </div><!-- End of rowLeft -->
      
      <div class="rowRight">
      Memo No : <?php foreach($trips as $trip) { echo $trip['trip_memo_no'].", ";  } ?>
      </div><!-- End of rowRight -->
      
      <div class="clrBoth"></div>
      
    </div> <!-- End of row -->
    
</div> <!-- End of details -->

<div class="tableClass">
  <table border="1">
    <tr>
    
      <td width="20%" height="35px;">
      Station
      </td>
      <td width="10%">
      Memo No
      </td>
      <td width="10%">
      Qty
      </td>
      
      <td width="10%">
     To Pay
      </td>
      
      <td width="10%">
       Paid
      </td>
       <td width="10%">
       To Be Billed
      </td>
      <td width="40%">
      Remarks
      </td>
      
    </tr>
    
     <?php
	 $total_qty=0;
	 $total_to_pay = 0;
	 $total_paid = 0;
	 $total_to_bebilled = 0;
	 $total_tax_amount=0;
	 $total_to_pay_tax_amount=0;
	  $total_paid_tax_amount=0;
	 $total_to_be_billed_tax_amount=0;
	  for($i=0; $i<count($trips); $i++)
	  {
		  $trip = $trips[$i];
		  if(is_numeric($trip['to_pay_tax_amount']))
		  $total_rows--;
		  if(is_numeric($trip['paid_tax_amount']))
		  $total_rows--;
		  if(validateForNUll($trip['paid_lr_nos']))
		  {
		  $paid_lr_array = explode(",",$trip['paid_lr_nos']);
		  $total_rows = $total_rows - count($paid_lr_array);
		  }
		   if(validateForNUll($trip['to_be_billed_lr_nos']))
		  {
		  $to_be_billed_lr_array = explode(",",$trip['to_be_billed_lr_nos']);
		   $total_rows = $total_rows - count($to_be_billed_lr_array);
		  }
	 ?>
      <tr>
    
      <td style="height:35px;">
     <?php echo strtoupper(getLedgerNameFromLedgerId($trip['to_branch_ledger_id'])); ?>
      </td>
      
      <td>
      <?php echo $trip['trip_memo_no']; ?>
      </td>
      
      <td>
      <?php echo $trip['total_qty']; if(is_numeric($trip['total_qty']))  $total_qty = $total_qty+$trip['total_qty'];?>
      </td>
      
      <td>
      <?php echo $trip['to_pay']; if(is_numeric($trip['to_pay']))  $total_to_pay = $total_to_pay+$trip['to_pay']; if(is_numeric($trip['to_pay_tax_amount'])) echo " <br>Tax:".$trip['to_pay_tax_amount']; if(is_numeric($trip['to_pay_tax_amount']))  $total_to_pay_tax_amount = $total_to_pay_tax_amount+$trip['to_pay_tax_amount'];  ?>
      </td>
      
      <td>
       <?php echo $trip['paid']; if(is_numeric($trip['paid']))  $total_paid = $total_paid+$trip['paid']; if(is_numeric($trip['paid_tax_amount'])) echo "<br>Tax:".$trip['paid_tax_amount']; if(is_numeric($trip['paid_tax_amount']))  $total_paid_tax_amount = $total_paid_tax_amount+$trip['paid_tax_amount']; echo "<br><span style='font-size:14px;'>".$trip['paid_lr_nos']."</span>"; ?>
      </td>
      
       <td>
       <?php echo $trip['to_be_billed']; if(is_numeric($trip['to_be_billed']))  $total_to_bebilled = $total_to_bebilled+$trip['to_be_billed']; if(is_numeric($trip['to_be_billed_tax_amount'])) echo "<br>Tax:".$trip['to_be_billed_tax_amount']; if(is_numeric($trip['paid_tax_amount']))  $total_to_be_billed_tax_amount = $total_to_be_billed_tax_amount+$trip['to_be_billed_tax_amount']; echo "<br><span style='font-size:14px;'>".$trip['to_be_billed_lr_nos']."</span>"; ?>
      </td>
      
      <td>
       <?php  ?>
      </td>
     <?php if($i==0) { ?> 
      <td rowspan="<?php echo count($trips)+2; ?>" style="font-size:16px;">
     <?php if($trip_memo['remarks']!="NA") echo $trip_memo['remarks']; ?>
      </td>
      <?php } ?>
    </tr>
     <?php
	  }
	 ?>
    
     <tr>
    
      <td height="<?php echo $total_rows*10; ?>px;">
    
      </td>
      
      <td>
      
      </td>
      
      <td>
    
      </td>
      
      <td>
     
      </td>
      
      <td>
      
      </td>
      
       <td>
      
      </td>
      
      <td>
     
      </td>
      
     
      
    </tr>
     
      <tr class="total_row">
    
      <td height="35px">
    
      </td>
      
      <td>
      <?php ?>
      </td>
      
      <td>
      <?php echo $total_qty; ?>
      </td>
      
      <td>
      <?php echo $total_to_pay; if($total_to_pay_tax_amount>0) echo "<br>Tax:".$total_to_pay_tax_amount; ?>
      </td>
      
      <td>
       <?php echo $total_paid; if($total_paid_tax_amount>0) echo "<br>Tax:".$total_paid_tax_amount; ?>
      </td>
      
       <td>
       <?php echo $total_to_bebilled; if($total_to_be_billed_tax_amount>0) echo "<br>Tax:".$total_to_be_billed_tax_amount; ?>
      </td>
      
      <td>
       <?php echo $total_tax_amount; ?>
      </td>
      
      
    </tr>
  </table>
</div> <!-- End of tableClass -->
    
</div> 

<div class="clrBoth"></div>
      
</div>
<div class="clrBoth"></div>
<?php } ?>
