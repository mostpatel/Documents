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
$trip_memo=getPaidLrsByPageId($delivery_challan_id);

if(is_array($trip_memo) && $trip_memo)
{
	$lrs=getLRsByPaidLrId($delivery_challan_id);
	$branches = listBranches();
}

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
  
  <hr />
  
  <div class="rowTwo">
  
  <div class="line">
  
      <div class="number">
      નંબર : <?php echo $trip_memo['page_no'] ?>
      </div>  <!-- End of number -->
      
      <div class="date">
      તારીખ : <?php echo date('d/m/Y',strtotime($trip_memo['paid_lr_date'])); ?>
      </div>  <!-- End of date -->
      
      <div class="clearFix"></div>
  
  </div> <!-- End of line -->
  
 
  
  
  <div class="clearFix"></div>
  

      
  
  <div class="clearFix"></div>
  
   
  </div> <!-- End of line -->
  
  <div class="clearFix"></div>
  
  </div> <!-- End of rowTwo -->
  
  <hr />
  
  <div class="rowThreeGoodsDetails">
  
  <table width="95%">
  
  <tr>
  <th width="2.5%"> L.R. No.</th>
   <th width="2.5%"> Cash Memo No.</th>
  <th width="20%"> Consignor </th>
  <th width="20%"> Consignee </th>
  <th width="2.5%"> Pkgs.</th>
  <th width="5%"> Item</th>
  <th width="5%" > To Pay</th>
  <th width="5%"> Paid</th>
  <th width="5%" > To Be Billed</th>
  <th width="5%"> Ser. Tax </th>
  </tr>
  
  
  <?php
  $toPaySum = 0;
  $paidSum = 0;
  $to_be_billed_sum=0;
  $servTaxSum = 0;
  $pckSum = 0;
  $n=count($lrs);
	  
  for($i=0; $i<$n; $i++)
  {
	  $lr=$lrs[$i];
	  $cash_memo_no = $lr['cash_memo_no'];
		$lr=getLRById($lr['lr_id']);
		$lr_Tax = getTaxForLr($lr['lr_id']);
	$tax_group_id = $lr_Tax[0]['tax_group_id'];
	$lr_products=getProductsByLRId($lr['lr_id']);
	$from_customer = getCustomerDetailsByCustomerId($lr['from_customer_id']);
	$to_customer = getCustomerDetailsByCustomerId($lr['to_customer_id']);  
	$pck = getQuantityByLRId($lr['lr_id']);
	  
  ?>
  <tr>
  <td width="2.5%"> <?php echo $lr['lr_no']; ?></td>
   <td width="2.5%"> <?php echo $cash_memo_no; ?></td>
  <td width="20%"> <?php echo $from_customer['customer_name'] ?> </td>
  <td width="20%"> <?php echo $to_customer['customer_name'] ?> </td>
   <td width="5%"> <?php foreach($lr_products as $lr_product) echo $lr_product['product_name']."<br>"; ?> </td>
  <td width="2.5%"> <?php foreach($lr_products as $lr_product) echo $lr_product['qty_no']."<br>"; ?> </td>
  <td width="5%"> <?php echo $lr['to_pay']; ?> </td>
  <td width="5%"> <?php echo $lr['paid']; ?> </td>
   <td width="5%"> <?php echo $lr['to_be_billed']; ?> </td>
  <td width="5%"> <?php echo $lr['total_tax']; ?> </td>
  </tr>
  
  
  
  <?php
  $toPaySum = $toPaySum + $lr['to_pay'];
  $paidSum = $paidSum + $lr['paid'];
  $to_be_billed_sum = $to_be_billed_sum + $lr['to_be_billed'];
  $servTaxSum = $servTaxSum + $lr['total_tax'];
  $pckSum = $pckSum + $pck;
  
  }
  ?>
  <?php if(count($lrs)<22) { 
  for($i;$i<22;$i++)
  {
  ?>
    <tr>
  <td width="2.5%" height="35px;"> </td>
   <td width="2.5%" height="35px;"> </td>
  <td width="20%"> - </td>
  <td width="20%"> - </td>
   <td width="5%"> <b></b> </td>
  <td width="2.5%"> <b> </b></td>
  <td width="5%"> <b>  </b> </td>
  <td width="5%"> <b></b> </td>
   <td width="5%"> <b></b> </td>
  <td width="5%"><b>  </b></td>
  </tr>
  <?php }} ?>
  
  <tr>
  <td width="2.5%"> </td>
   <td width="2.5%" height="35px;"> </td>
  <td width="20%"> - </td>
  <td width="20%"> - </td>
   <td width="5%"> <b></b> </td>
  <td width="2.5%"> <b> <?php echo $pckSum; ?> </b></td>
  <td width="5%"> <b> <?php echo $toPaySum; ?> </b> </td>
  <td width="5%"> <b><?php echo $paidSum; ?></b> </td>
   <td width="5%"> <b><?php echo $to_be_billed_sum; ?></b> </td>
  <td width="5%"><b> <?php echo $servTaxSum; ?> </b></td>
  </tr>
  
  
  
  </table>
  
  
  </div> <!-- End of rowThreeGoodsDetails -->
  
  <hr />
  

  
 

</div> <!-- End of mainClass -->

      
</div>
<div class="clearfix"></div>
<script type="text/javascript">
window.print();
document.location.href="index.php";
</script>