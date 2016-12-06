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
<?php for($rep=0;$rep<3;$rep++) { ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<div class="mainClass">
<div class="leftSection">
    
    <div class="head_office">
       
       <div class="name_heading">
       Head Office
       </div> <!-- End of name_heading -->
       
       <div class="address_line">
       મેઘદૂત હોટલ પાછળ, <br />
       સારંગપુર પુલ નીચે, <br />
       અમદાવાદ. <br />
       ફોન : ૨૨૧૬૩૬૨૮, ૨૨૧૬૩૪૫૮
       
       </div>  <!-- End of address_line -->
       <hr />
    </div> <!-- End of head_office -->
    
    <div class="delivery_booking_godown">
    
    <div class="name_heading">
    DELIVERY AND <br />
    BOOKING GODOWN
    </div>  <!-- End of name_heading -->
    
    <hr />
    
     <div class="name_heading">
       Sarangpur
     </div> <!-- End of name_heading -->
       
       <div class="address_line">
       મેઘદૂત હોટલ પાછળ, <br />
       સારંગપુર પુલ નીચે, <br />
       અમદાવાદ. <br />
       ફોન : ૨૨૧૬૩૬૨૮, ૨૨૧૬૩૪૫૮
       </div>  <!-- End of address_line -->
       
        <hr />
       <div class="name_heading">
       Narol
     </div> <!-- End of name_heading -->
       
       <div class="address_line">
       મેઘદૂત હોટલ પાછળ, <br />
       સારંગપુર પુલ નીચે, <br />
       અમદાવાદ. <br />
       ફોન : ૨૨૧૬૩૬૨૮, ૨૨૧૬૩૪૫૮
       </div>  <!-- End of address_line -->
       
        <hr />
       <div class="name_heading">
       Aslali
     </div> <!-- End of name_heading -->
       
       <div class="address_line">
       મેઘદૂત હોટલ પાછળ, <br />
       સારંગપુર પુલ નીચે, <br />
       અમદાવાદ. <br />
       ફોન : ૨૨૧૬૩૬૨૮, ૨૨૧૬૩૪૫૮
       </div>  <!-- End of address_line -->
       
        <hr />
       <div class="name_heading">
       Sarkhej
     </div> <!-- End of name_heading -->
       
       <div class="address_line">
       મેઘદૂત હોટલ પાછળ, <br />
       સારંગપુર પુલ નીચે, <br />
       અમદાવાદ. <br />
       ફોન : ૨૨૧૬૩૬૨૮, ૨૨૧૬૩૪૫૮
       </div>  <!-- End of address_line -->
       
       <hr />
       
       <div class="name_heading">
       Madhupura
     </div> <!-- End of name_heading -->
       
       <div class="address_line">
       મેઘદૂત હોટલ પાછળ, <br />
       સારંગપુર પુલ નીચે, <br />
       અમદાવાદ. <br />
       ફોન : ૨૨૧૬૩૬૨૮, ૨૨૧૬૩૪૫૮
       </div>  <!-- End of address_line -->
       
       
    </div> <!-- End of delivery_booking_godown -->
    
</div>   <!-- End of leftSection -->


<div class="rightSection">



  
  <div class="rowOneHeader">
  
   <div class="juridiction">
   Subject To Ahmedabad Juridiction
   </div>  <!-- End of juridiction -->
    
    <div class="companyDetails">
    
        <div class="companyLogo">
        </div>  <!-- End of companyLogo -->
        
        
        <div class="companyName">
       G. DALABHAI CARGO MOVERS
        </div>  <!-- End of companyName -->
        
        <div class="clearFix">
        </div>  <!-- End of clearFix -->
        
        

    </div>  <!-- End of companyDetails -->
  
  </div> <!-- End of rowOneHeader -->
  
 
  
  <div class="rowTwo">
    
    <div class="leftSectionInRowTwo">
    
    <div class="upperInMiddleRowTwo">
        Service Tax No : 
        </div>  <!-- End of upperInMiddleRowTwo -->
        
        <div class="lowerInMiddleRowTwo">
        AABHH - 8974D - ST - 001
        </div>  <!-- End of lowerInMiddleRowTwo -->
        
    </div>  <!-- End of leftSectionInRowTwo -->
    
    <div class="middleSectionInRowTwo">
        
        <div class="upperInMiddleRowTwo">
        From : <?php echo getLedgerNameFromLedgerId($lr['from_branch_ledger_id']); ?>
        </div>  <!-- End of upperInMiddleRowTwo -->
        
        <div class="lowerInMiddleRowTwo">
        <?php if(validateForNull($lr['delivery_at'])) { ?>
         To : <?php echo $lr['delivery_at']; ?>
        <?php }else{ ?>
        To : <?php echo getLedgerNameFromLedgerId($lr['to_branch_ledger_id']); ?>
        <?php } ?>
        </div>  <!-- End of lowerInMiddleRowTwo -->
       
    </div>  <!-- End of middleSectionInRowTwo -->
    
    <div class="rightSectionInRowTwo" >
    
    <div class="upperInMiddleRowTwo" style="text-align:center;font-size:22px;">
        GVC No : <?php $branch_code = getBranchCodeForBranchID($lr['from_branch_ledger_id']); echo $branch_code."  ".str_replace($branch_code,'',$lr['lr_no']); ?>
        </div>  <!-- End of upperInMiddleRowTwo -->
        
        <div class="lowerInMiddleRowTwo" style="text-align:center;">
        Date : <?php echo date('d/m/Y',strtotime($lr['lr_date']))."<br> Time : ".date('H:i:s',strtotime($lr['date_added'])); ?>
        </div>  <!-- End of lowerInMiddleRowTwo -->
        
    </div>  <!-- End of rightSectionInRowTwo -->
    
    <div class="clearFix"></div>
  
  </div>  <!-- End of rowTwo -->
  
  <div class="clearFix"></div>
  
  <hr style="margin-top:5px;margin-bottom:5px;" />
  
  <div class="rowThree">
  
    <div class="leftPartInRowThree">
    Consignor : <?php echo $from_customer['customer_name']; echo "<br>"; echo $from_customer['customer_address']; ?>
    </div>  <!-- End of leftPartInRowThree -->
  
  
    <div class="rightPartInRowThree">
    Consignee : <?php echo $to_customer['customer_name']; echo "<br>"; echo $to_customer['customer_address']; ?>
    </div>  <!-- End of rightPartInRowThree -->
  
  </div>  <!-- End of rowThree -->
  
  <hr style="margin-top:5px;margin-bottom:5px;"/>
  
  <div class="rowFour">
  
    <table>
  
  <tr>
  <th width="10%"> Qty</td>
   <th width="10%"> Pack </td>
  <th width="40%"> Said To Contain </td>
   
   <th width="12%"> Actual Weight Kgs </td>
    <th width="12%"> Freight</td>
     <th width="16%"> Remarks </td>
 
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
  
   <td > <?php if($qty_no>0) echo $qty_no; else echo "loose"; ?> </td>
   <td > <?php echo getPackingUnitNameById($packing_unit_id); ?> </td>
  <td > <?php echo getProductNameById($product_id); ?> </td>
 
 <?php if($i==0) { ?>
 <td rowspan="<?php if($n>2) echo $n-2; else echo $n; ?>" valign="middle"><?php if($lr['weight']>0) echo $lr['weight']." Kgs"; ?></td>
 <td  rowspan="<?php if($n>2) echo $n-2; else echo $n; ?>" valign="middle"><?php echo $lr['freight']; ?></td>
 <td  rowspan="<?php if($n>2) echo $n+1; else echo $n+3; ?>" valign="middle"><?php echo $lr['remarks']; ?></td>
 
 <?php } ?>
 
  <?php if($n>2 && $i==$n-2) { ?>

 <td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle">Tempo Fare</td>

 <td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle"><?php if($lr['tempo_fare']>=0) echo $lr['tempo_fare']." Rs"; ?></td>

 <?php } ?> 
 
   <?php if($n>2 && $i==$n-1) { ?>
 <td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle">Rebooking Charges</td>

 <td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle"><?php if($lr['rebooking_charges']>=0) echo $lr['rebooking_charges']." Rs"; ?></td>

 <?php } ?> 
 <?php  ?>
  </tr>
 
  <?php
  $qtySum = $qtySum + $qty_no;
 
  }
  ?>
  <?php if($n<3) { ?>
  	<?php   { ?>
    <tr>
    	 <td></td>
  <td >  </td>
  <td>  </td>
    	 <td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle">Tempo Fare</td>

 		<td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle"><?php if($lr['tempo_fare']>=0) echo $lr['tempo_fare']." Rs"; ?></td>
        
         </tr>
     <?php } ?>
     <?php if($n<3)  { ?>
     <tr>
     	 <td></td>
  <td >  </td>
  <td>  </td>
     	<td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle">Rebooking Charges</td>

 		<td rowspan="<?php echo 1; ?>" style="border-top:1px solid #000;"  valign="middle"><?php if($lr['rebooking_charges']>=0) echo $lr['rebooking_charges']." Rs"; ?></td>
		
         </tr>
      <?php } ?>
  <?php } ?>
    <tr>

   <td style="border-top:1px solid #000;"  ><b><?php echo $qtySum; ?></b></td>
  <td >  </td>
  <td>  </td>
  
 <td rowspan="1" style="border-top:1px solid #000;" colspan="1"> B. Charge</td>
  <td rowspan="1" style="border-top:1px solid #000;" colspan="1"><?php echo $lr['builty_charge']; ?></td>
   
  </tr>
  
  <tr>

   <td style="border-top:1px solid #000;font-weight:bold" colspan="3" > Service Tax TOBE REMMITED TO GOVT. A/C BY -  <?php if($lr['tax_pay_type']==1) echo "CONSIGNEE";
					else if($lr['tax_pay_type']==2) echo "CONSIGNOR";
					else if($lr['tax_pay_type']==3) echo "TRANSPORTER";
					else echo "Default";  ?> </td>
 
   <td rowspan="1" style="border-top:1px solid #000;">Ser. Tax + Edu. Cess</td>
	<td rowspan="1" style="border-top:1px solid #000;"><?php if(is_numeric($lr['total_tax']) && $lr['total_tax']>0) echo $lr['total_tax']; else echo "-"; ?></td>
 <td rowspan="2" style="border-top:1px solid #000;font-weight:bold"><?php if($lr['to_pay']>0) echo "TO PAY"; else if($lr['paid']>0) echo "PAID"; else echo "TO BE BILLED"; ?></td>
  </tr>
  
  <tr>

   <td style="border-top:1px solid #000;" colspan="3"> TRANSIT INSURANCE LIABILITY OF CONSIGNOR / CONSIGNEE </td>
  
   <td rowspan="1" style="border-top:1px solid #000;">Total Amount</td>
	<td rowspan="1" style="border-top:1px solid #000;font-weight:bold"><?php if(is_numeric($lr['total_tax']) && $lr['total_tax']>0) echo $lr['total_tax'] + $lr['total_freight']; else echo $lr['total_freight'];  ?></td>

  </tr>
  
  <tr>

   <td colspan="2" style="border-top:1px solid #000;" align="left">Delivery :   <?php if(validateForNull($lr['delivery_at'])) { ?>
          <?php echo getLedgerNameFromLedgerId($lr['to_branch_ledger_id']); ?> 
        <?php }else{ ?>
        <?php echo $lr['delivery_at']; ?>
        <?php } ?>  </td>

  <td colspan="2" style="border-top:1px solid #000;"> DAMMARRAGE WILL BE CHARGED AFTER THREE DAYS  </td>
  
 <td  valign="bottom" style="border-top:1px solid #000;padding-top:50px;" colspan="2" align="center">  For, જી. દલાભાઈ કાર્ગો મુવર્સ</td>
  </tr>
  
  
  
  </table>
  
  
  </div>  <!-- End of rowFour -->
  
 
 </div> <!-- End of rightSection -->
 <div class="clearfix"></div>
<div style="width:100%;text-align:center;font-size:24px;font-weight:bold"><?php if($rep==0) { ?>( કનસાઈનર કોપી ) <?php } else if($rep==1) { ?>( કનસાઈની કોપી )<?php } else if($rep==2) { ?>( ડ્રાઈવર કોપી )<?php } ?></div>
</div> <!-- End of mainClass -->

<div class="clearfix"></div>
      
</div>
<div class="clearfix" style="page-break-after:always;"></div>
<?php } ?>
<script type="text/javascript">
window.print();
document.location.href="index.php";
</script>