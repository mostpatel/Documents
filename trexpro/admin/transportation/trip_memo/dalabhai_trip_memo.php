<?php if(!isset($_GET['id']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}
$delivery_challan_id=$_GET['id'];
$trip_memo=getTripMemoById($delivery_challan_id);
$items_per_page=26;
if(is_array($trip_memo) && $trip_memo)
{
	$lrs=getLRsByTripId($delivery_challan_id);
	$branches = listBranches();
	$truck_drivers=listTruckDrivers();
}
$total_items = count($lrs);
if($total_items>$items_per_page)
$no_of_pages = ceil($total_items/$items_per_page);
else
$no_of_pages=1;

?>
<link rel="stylesheet" href="../../../css/dalabhai_trip_memo.css" />
<div class="insideCoreContent adminContentWrapper wrapper">

<div class="mainClass">
  
  <div class="rowOneHeader">
  
   <div class="juridiction">
   Subject To Ahmedabad Juridiction
   </div>  <!-- End of juridiction -->
    
    <div class="companyDetails">
    
        <div class="companyLogo">
        </div>  <!-- End of companyLogo -->
        
        <div class="companyName">
        જી. દલાભાઈ કાર્ગો મુવર્સ
        </div>  <!-- End of companyName -->
        
        <div class="clearFix">
        </div>  <!-- End of clearFix -->
        
        <div class="addressLineOne">
        હેડ ઓફીસ : મેઘદૂત હોટલ પાછળ, સારંગપુર પુલ નીચે, અમદાવાદ. ફોન : ૨૨૧૬૩૬૨૮, ૨૨૧૬૩૪૫૮
        </div>  <!-- End of addressLineOne -->
        
        <div class="addressLineTwo">
        બ્રાંચ ઓફીસ નં : નારોલ : ૨૫૭૧૨૫૩૨, બીલીમોરા : ૨૭૯૦૭૬, નવસારી : ૨૫૮૬૨૪, બારડોલી : ૨૨૨૦૩૭, બાજીપુરા : ૨૩૩૭૮૯
        </div> <!-- End of addressLineTwo -->

    </div>  <!-- End of companyDetails -->
  
  </div> <!-- End of rowOneHeader -->
  
  
  
  <div class="rowTwo">
  <hr />
  <div class="line">
  
      <div class="number">
      નંબર : <?php echo $trip_memo['trip_memo_no'] ?>
      </div>  <!-- End of number -->
      
      <div class="date">
      તારીખ : <?php echo date('d/m/Y',strtotime($trip_memo['trip_date']))." ".date('H:i:s',strtotime($trip_memo['date_added'])); ?>
      </div>  <!-- End of date -->
      
      <div class="clearFix"></div>
  
  </div> <!-- End of line -->
  
  <div class="line">
  
      <div class="number">
      From Station : <?php echo getLedgerNameFromLedgerId($trip_memo['from_branch_ledger_id']); ?>
      </div>  <!-- End of number -->
      
      <div class="date">
      To Station : <?php echo getLedgerNameFromLedgerId($trip_memo['to_branch_ledger_id']); ?>
      </div>  <!-- End of date -->
      
      <div class="clearFix"></div>
  
  </div> <!-- End of line -->
  
  
  <div class="clearFix"></div>
  
  <div class="line">
  
      <div class="carNo">
      મોટર નં : <?php echo strtoupper(getTruckNoById($trip_memo['truck_id'])); ?>
      </div>  <!-- End of carNo -->
      
      <div class="clearFix"></div>
      
  </div> <!-- End of line -->
  
  <div class="clearFix"></div>
  
  <div class="line">
  
      <div class="driverName">
      ડ્રાઈવર નું નામ : <?php echo strtoupper(getLedgerNameFromLedgerId($trip_memo['driver_id'])); ?>
      </div>  <!-- End of driverName -->
      
      
  
  <div class="clearFix"></div>
  
   
  </div> <!-- End of line -->
  
  <div class="line">
  
      <div class="driverName">
     Remarks <?php echo $trip_memo['remarks']; ?>
      </div>  <!-- End of driverName -->
      
      
  
  <div class="clearFix"></div>
  
   
  </div> <!-- End of line -->
  
  <div class="clearFix"></div>
  
  </div> <!-- End of rowTwo -->
  
  <hr />
  
 
  
  <div class="rowThreeGoodsDetails">
  
  <table>
  
  <tr>
   <th width="1.5%"> No.</th>
  <th width="2.5%"> L.R. No.</th>
  <th width="30%"> Consignor </th>
  <th width="30%"> Consignee </th>
  <th width="8%"> Product</th>
  <th width="7%"> Pkgs.</th>
  <th width="4%" > To Pay</th>
  <th width="2.5%"> Paid</th>
   <th width="2.5%" > To Be Billed</th>
  <th width="2%"> Ser. Tax </th>
  <th width="5%"> Weight </th>
  </tr>
  
  
  <?php
  $toPaySum = 0;
  $paidSum = 0;
  $to_be_billed_sum=0;
  $servTaxSum = 0;
  $pckSum = 0;
  $total_weight = 0;
  $n=count($lrs);
	 $no=1; 
  for($i=0,$j=0; $i<$n; $i++,$j++)
  {
	$lr=$lrs[$i];
    $lr=getLRById($lr['lr_id']);
    $lr_Tax = getTaxForLr($lr['lr_id']);
	$tax_group_id = $lr_Tax[0]['tax_group_id'];
	$lr_products=getProductsByLRId($lr['lr_id']);
	$from_customer = getCustomerDetailsByCustomerId($lr['from_customer_id']);
	$to_customer = getCustomerDetailsByCustomerId($lr['to_customer_id']);  
	$pck = getQuantityByLRId($lr['lr_id']);
	
  ?>
  <tr>
  <td><?php echo $no; $no++; ?></td>
  <td > <?php echo $lr['lr_no']; ?></td>
  <td > <?php echo $from_customer['customer_name'] ?> </td>
  <td > <?php echo $to_customer['customer_name'] ?> </td>
   <td > <?php  $no_of_product=0;   foreach($lr_products as $lr_product) { echo $lr_product['product_name']."<br>";  if(count($lr_products)>1 && $no_of_product>0) $j=$j+0.5; $no_of_product++;} ?> </td>
  <td > <?php foreach($lr_products as $lr_product)  {echo $lr_product['qty_no']." ".$lr_product['packing_unit']."<br>"; if(strlen($lr_product['packing_unit'])>6) $j=$j+0.5; }  ?> </td>
  <td > <?php echo $lr['to_pay']; ?> </td>
  <td > <?php echo $lr['paid']; ?> </td>
   <td > <?php echo $lr['to_be_billed']; ?> </td>
  <td > <?php echo $lr['total_tax'];  ?> </td>
  <td><?php  if(is_numeric($lr['weight']) && $lr['weight']>0) echo $lr['weight']." Kgs"; ?></td>
  </tr>
  
  
  
  <?php
  $toPaySum = $toPaySum + $lr['to_pay'];
  $paidSum = $paidSum + $lr['paid'];
  $to_be_billed_sum = $to_be_billed_sum + $lr['to_be_billed'];
  $servTaxSum = $servTaxSum + $lr['total_tax'];
  $pckSum = $pckSum + $pck;
   if(is_numeric($lr['weight']) && $lr['weight']>0)
	 $total_weight = $total_weight + $lr['weight'];
  if(round($j)%$items_per_page==0 && $j!=0)
  {
	?>
    </table>
    <div style="float:right">Continued..</div>
    <div style="clear:both;page-break-after:always;"></div>
    <div style="margin-top:15%;"></div>
    <div class="rowTwo">
  <hr />
  <div class="line">
  
      <div class="number">
      નંબર : <?php echo $trip_memo['trip_memo_no'] ?>
      </div>  <!-- End of number -->
      
      <div class="date">
      તારીખ : <?php echo date('d/m/Y',strtotime($trip_memo['trip_date'])); ?>
      </div>  <!-- End of date -->
      
      <div class="clearFix"></div>
  
  </div> <!-- End of line -->
  
  <div class="line">
  
      <div class="number">
      From Station : <?php echo getLedgerNameFromLedgerId($trip_memo['from_branch_ledger_id']); ?>
      </div>  <!-- End of number -->
      
      <div class="date">
      To Station : <?php echo getLedgerNameFromLedgerId($trip_memo['to_branch_ledger_id']); ?>
      </div>  <!-- End of date -->
      
      <div class="clearFix"></div>
  
  </div> <!-- End of line -->
  
  
  <div class="clearFix"></div>
  
  <div class="line">
  
      <div class="carNo">
      મોટર નં : <?php echo strtoupper(getTruckNoById($trip_memo['truck_id'])); ?>
      </div>  <!-- End of carNo -->
      
      <div class="clearFix"></div>
      
  </div> <!-- End of line -->
  
  <div class="clearFix"></div>
  
  <div class="line">
  
      <div class="driverName">
      ડ્રાઈવર નું નામ : <?php echo strtoupper(getLedgerNameFromLedgerId($trip_memo['driver_id'])); ?>
      </div>  <!-- End of driverName -->
      
      
  
  <div class="clearFix"></div>
  
   
  </div> <!-- End of line -->
  
  <div class="clearFix"></div>
  
  </div> <!-- End of rowTwo -->
  
  <hr />
    <table>
     <tr>
    <th width="1.5%"> No.</th>
  <th width="2.5%"> L.R. No.</th>
  <th width="30%"> Consignor </th>
  <th width="30%"> Consignee </th>
  <th width="8%"> Product</th>
  <th width="7%"> Pkgs.</th>
  <th width="4%" > To Pay</th>
  <th width="2.5%"> Paid</th>
   <th width="2.5%" > To Be Billed</th>
  <th width="2%"> Ser. Tax </th>
  <th width="5%"> Weight </th>
  </tr>
    <?php 	  
   }
  }
  ?>
  <?php if(count($lrs)<22) { 
  for($i;$i<$i;$i++)
  {
  ?>
    <tr>
     <td> - </td>
  <td  height="35px;"> </td>
  <td> - </td>
  <td> - </td>
   <td> <b>  </b> </td>
  <td> <b> </b></td>
  <td > <b>  </b> </td>
  <td > <b></b> </td>
   <td> <b></b> </td>
  <td><b>  </b></td>
  <td></td>
  </tr>
  <?php }} ?> 
  
  <tr>
  <td > </td>
  <td > </td>
  <td > - </td>
  <td> - </td>
   <td > - </td>
  <td > <b> <?php echo $pckSum; ?> </b></td>
  <td > <b> <?php echo $toPaySum; ?> </b> </td>
  <td > <b><?php echo $paidSum; ?></b> </td>
   <td > <b><?php echo $to_be_billed_sum; ?></b> </td>
  <td ><b> <?php echo $servTaxSum; ?> </b></td>
  <td><b><?php echo $total_weight." Kgs"; ?></b></td>
  </tr>
  
  
  
  </table>
  
  
  </div> <!-- End of rowThreeGoodsDetails -->
  
  <hr />
  
  <div class="rowFiveFooter">
  
  <div class="leftSign">
  ડ્રાઈવર ની સહી 
  </div>  <!-- End of leftSign -->
  
  <div class="rightSign">
  For, જી. દલાભાઈ કાર્ગો મુવર્સ
  </div>  <!-- End of rightSign -->
  
  <div class="clearFix"></div>
  
  </div> <!-- End of rowFiveFooter -->
  
 

</div> <!-- End of mainClass -->

      
</div>
<div class="clearfix"></div>
<script type="text/javascript">

$(document).ready(function(e) {
   window.print();
document.location.href="index.php"; 
});
</script>