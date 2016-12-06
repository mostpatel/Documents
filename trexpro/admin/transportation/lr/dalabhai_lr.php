<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$delivery_challan_id=$_GET['id'];
$lr=getLRById($delivery_challan_id);

if(is_array($lr) && $lr)
{
	$lr_Tax = getTaxForLr($delivery_challan_id);
	$tax_group_id = $lr_Tax[0]['tax_group_id'];
	$lr_products=getProductsByLRId($delivery_challan_id);
	$from_customer = getCustomerDetailsByCustomerId($lr['from_customer_id']);
	
	$to_customer = getCustomerDetailsByCustomerId($lr['to_customer_id']);
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}

?>
<link rel="stylesheet" href="../../../css/dalabhai_lr.css" />
<div class="insideCoreContent adminContentWrapper wrapper" >
<?php for($rep=0;$rep<3;$rep++) { ?>


<div class="mainClass">
<div class="topSection">
    <div class="top_second_row" style="margin-top:80px;height:33mm;">
    	<table style="width:100%;border:none;">
        	<tr height="25%">
            	<td width="12%;" ></td>
                <td width="28%"></td>
                <td width="8%;"></td>
                <td width="27%;"> </td>
                <td width="8%"></td>
                <td><b style="font-size:20px;"><?php $branch_code = getBranchCodeForBranchID($lr['from_branch_ledger_id']); echo $branch_code."  ".str_replace($branch_code,'',$lr['lr_no']); ?></b></td>
            </tr>
        	<tr height="25%">
            	<td width="12%;"></td>
                <td width="28%"> <b><?php echo $from_customer['customer_name'];  ?></b></td>
                <td width="8%;"></td>
                <td width="27%;">  <b><?php echo $to_customer['customer_name'];  ?></b> </td>
                <td width="8%"></td>
                <td> <b><?php echo date('d/m/Y',strtotime($lr['lr_date'])); ?></b></td>
            </tr>
            <tr height="25%">
            	<td  ></td>
                <td><b> <?php echo getLedgerNameFromLedgerId($lr['from_branch_ledger_id']); ?></b></td>
                <td></td>
                <td><b>  <?php if(validateForNull($lr['delivery_at'])) {  echo $lr['delivery_at'];  } else {  echo getLedgerNameFromLedgerId($lr['to_branch_ledger_id']); } ?> </b></td>
                <td></td>
                <td><b> <?php echo date('H:i:s',strtotime($lr['date_added'])); ?></b></td>
            </tr>
            <tr height="25%">
            	<td ></td>
                <td></td>
                <td></td>
                <td><b>  <?php if(validateForNull($lr['delivery_at'])) { ?>
          <?php echo getLedgerNameFromLedgerId($lr['to_branch_ledger_id']); ?> 
        <?php }else{ ?>
        <?php echo $lr['delivery_at']; ?>
        <?php } ?></b></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
 
  
  <div class="rowFour" style="height:48mm;">
  
    <table style="width:100%;height:98%;" border="0" >
  
  <tr>
  <th width="9.5%" height="24px;"> </td>
  <th width="10%" height="24px;"> </td>
   <th width="48.5%">  </td>
  <th width="10.5%">  </td>
   
   <th width="21.5%">  </td>
   
 
  </tr>
  
  
  <?php
  $toPaySum = 0;
  $paidSum = 0;
  $servTaxSum = 0;
  $pckSum = 0;
  $n=count($lr_products);
 $qtySum=0;	  
  for($i=0; $i<$n; $i++)
  {
	  $lr_product = $lr_products[$i];
	 $product_id = $lr_product['product_id'];
     $packing_unit_id = $lr_product['packing_unit_id'];
     $qty_no = $lr_product['qty_no'];
     $tax_group_id = $lr_product['tax_group_id'];
	  
	  
  ?>
  <tr>
  
   <td height="15mm;" width="8%" > <?php if($qty_no>0) echo $qty_no; else echo "loose";  ?> </td>
   <td width="8%"><?php echo getPackingUnitNameById($packing_unit_id); ?></td>
  <td align="left" style="padding-left:20px;box-sizing:border-box;" > <?php echo getProductNameById($product_id); ?> </td>
  <?php  if($i==0) { ?>
 <td rowspan="5"  style="padding:0"  ><div style="word-break:break-all;overflow:hidden;max-height:33mm;height:33mm;padding:2px;box-sizing:border-box;"><?php echo $lr['remarks']; ?></div></td>
 <?php } ?>
 <?php  if($i==0) { ?>
 <td rowspan="5" style="font-weight:bold;font-size:20px;" >  <?php if($lr['weight']>0) echo $lr['weight']." Kgs";  ?> <br /><br />
 <?php  if(is_numeric($lr['lr_type']) && $lr['lr_type']>0) { 
 
 if($lr['lr_type']==1) echo "TO PAY"; else if($lr['lr_type']==2) echo "PAID"; else echo "TO BE BILLED";
 
   } else if($lr['to_pay']>0) echo "TO PAY"; else if($lr['paid']>0) echo "PAID"; else echo "TO BE BILLED"; ?> </td>
 <?php } ?>
 
  </tr>
  
 
  <?php
  $qtySum = $qtySum + $qty_no;
 
  }
  ?>
  <tr>
  
   <td height="15mm" style="border-top:solid 1px #000;" ><b><?php echo $qtySum; ?></b> </td>
   <td></td>
  <td >  </td>
 
 
  </tr>
  
  <?php
  for($i; $i<4; $i++)
  {
  ?>
   <tr>
  
   <td height="15mm" > </td>
   <td></td>
  <td >  </td>
 
 
  </tr>
  <?php } ?>
  </table>
   </div>  <!-- End of rowFour -->
   <div style="width:100%;padding:0;height:15mm;">
   <table style="width:100%;" border="0">
  
  <tr>
  <th width="5.1%" height="45px;"> </th>
  <th width="8.5%" ><b><?php echo $lr['freight']."/-"; ?></b></th>
  <th width="5%" > </th>
  <th width="9.2%" ><b><?php if($lr['tempo_fare']>=0) echo $lr['tempo_fare']."/-"; else echo "-"; ?> </b> </th>
  <th width="5.6%" > </th>
   <th width="6.6%" ><b><?php if($lr['rebooking_charges']>=0) echo $lr['rebooking_charges']."/-";  else echo "-"; ?></b> </th>
   <th width="5%" > </th>
   <th width="7.2%"><b><?php if(is_numeric($lr['total_tax']) && $lr['total_tax']>0) echo $lr['total_tax']."/-"; else echo "-"; ?></b>  </th>
   <th width="5.6%">  </th>
   <th width="9.75%"><b><?php echo $lr['builty_charge']."/-"; ?></b>  </th>
  <th width="10.75%">  </th>
   <th><b><?php if(is_numeric($lr['total_tax']) && $lr['total_tax']>0) echo $lr['total_tax'] + $lr['total_freight']."/-"; else echo $lr['total_freight']."/-";  ?></b></th>
 
  
   
 
  </tr>
  </table>
  <div style="width:100%;font-weight:bold;padding-top:10px;text-align:center;"><?php if($lr['freight']>=750) { ?>SERVICE TAX TOBE REMMITED TO GOVT. A/C BY -  <?php if($lr['tax_pay_type']==1) echo "CONSIGNEE";
					else if($lr['tax_pay_type']==2) echo "CONSIGNOR";
					else if($lr['tax_pay_type']==3) echo "TRANSPORTER";
					else echo "Default"; } ?></div>
 </div>
  
 
 </div> <!-- End of rightSection -->
 <div class="clearfix"></div>

</div> <!-- End of mainClass -->

<div class="clearFix" style="min-height:<?php  echo 96-($rep*5);  ?>px;"></div>

<?php } ?>
<div class="clearfix"></div>
      
</div>

<script type="text/javascript">
$( document ).ready(function() {
window.print();
document.location.href="index.php";
});
</script>