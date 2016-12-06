<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$job_card_id = $_GET['id'];
$job_card = getJobCardById($job_card_id);
$job_card_detials = $job_card['job_card_details'];
$job_card_customer_complaints=$job_card['job_card_description'];
$job_card_work_done = $job_card['job_card_work_done'] ;
$job_card_remarks = $job_card['job_card_remarks'];
$regular_items=$job_card['job_card_regular_general_items'];
$lub_items=$job_card['job_card_regular_lub_items'];
$warranty_items=$job_card['job_card_warranty_items'];
$regular_ns_items=$job_card['job_card_ns_items'];
$outside_job_items=$job_card['job_card_outside_job'];
$service_checks=$job_card['job_card_checks'];
$sale=$job_card['job_card_sales'];
$vehicle_id = $job_card_detials['vehicle_id'];
$vehicle = getVehicleById($vehicle_id);	
$customer_id = $vehicle['customer_id'];
$customer = getCustomerDetailsByCustomerId($customer_id);
$oc_id =$admin_id=$_SESSION['edmsAdminSession']['oc_id'];
$invoice_counter = getInvoiceCounterForOCID($oc_id);
$job_card_counter = getJobCounterForOCID($oc_id);
}
else
exit;
 ?>
<link rel="stylesheet" href="../../../../css/jobcard2.css" />
<div class="addDetailsBtnStyling no_print">
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard&id='.$job_card_id; ?>">
<button class="btn viewBtn no_print"> Front Page </button>
</a>

<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card_id ?>"><button title="View this entry" class="btn viewBtn btn-success">Back</button></a>
</div>
<div class="mainDiv">

<div class="mainTitle">
REGULAR JOB CARD PARTS AND CONSUMABLE RECORD
</div>  <!-- End of tableTitle -->

<?php if(is_array($regular_items) && count($regular_items)) { ?>
<h4 class="headingAlignment">Spare Parts</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Godown</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
              <?php  $total_tax_amount = 0; $total_net_amount=0; for($i=1;$i<=count($regular_items);$i++) { 
			$sales_item=$regular_items[$i-1]['sales_item_details'];
			$item_tax_details = $regular_items[$i-1]['tax_details'];
			if(is_numeric($sales_item['tax_amount'])){
			 $total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			}
			if(is_numeric($sales_item['tax_amount'])) 
			$nett_amount = round($sales_item['net_amount']+$sales_item['tax_amount'],2); 
			else 
			$nett_amount = round($sales_item['net_amount'],2);
			
			$total_net_amount = $total_net_amount + $nett_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                     <td align="center"><?php echo getGodownNameFromGodownId($sales_item['godown_id']); ?> </td>
                    <td align="center"><?php echo number_format($sales_item['quantity']); ?></td>
                     <td align="center"><?php echo number_format($sales_item['rate']); ?> Rs</td>
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php  echo $nett_amount; ?> Rs</td>
                    
            	</tr>
            </tbody>
            <?php } ?>
            
             </tr>
              <?php  
			$total_tax_amount_spares = $total_tax_amount;
			$total_net_amount_spares = $total_net_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><b>TOTAL</b></td>
                     <td align="center"></td>
                    <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"><?php echo $total_tax_amount_spares; ?> Rs</td>
                     <td align="center"><?php  echo $total_net_amount_spares; ?> Rs</td>
                    
            	</tr>
             </tbody>  
    	</table>
    </td>

</tr>

<table>
<?php } ?>

<?php if(is_array($lub_items) && count($lub_items)) { ?>
<h4 class="headingAlignment">Consumables</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="productPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Godown</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
              <?php  $total_tax_amount = 0; $total_net_amount=0; for($i=1;$i<=count($lub_items);$i++) { 
			$sales_item=$lub_items[$i-1]['sales_item_details'];
			$item_tax_details = $lub_items[$i-1]['tax_details'];
			if(is_numeric($sales_item['tax_amount'])){
			 $total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			}
			if(is_numeric($sales_item['tax_amount'])) 
			$nett_amount = round($sales_item['net_amount']+$sales_item['tax_amount'],2); 
			else 
			$nett_amount = round($sales_item['net_amount'],2);
			
			$total_net_amount = $total_net_amount + $nett_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                     <td align="center"><?php echo getGodownNameFromGodownId($sales_item['godown_id']); ?> </td>
                    <td align="center"><?php echo number_format($sales_item['quantity']); ?></td>
                     <td align="center"><?php echo number_format($sales_item['rate']); ?> Rs</td>
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php  echo $nett_amount; ?> Rs</td>
                    
            	</tr>
            </tbody>
            <?php } ?>
            
             </tr>
              <?php  
			$total_tax_amount_spares_lub = $total_tax_amount;
			$total_net_amount_spares_lub = $total_net_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><b>TOTAL</b></td>
                     <td align="center"></td>
                    <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"><?php echo $total_tax_amount_spares_lub; ?> Rs</td>
                     <td align="center"><?php  echo $total_net_amount_spares_lub; ?> Rs</td>
                    
            	</tr>
             </tbody>  
    	</table>
    </td>

</tr>

<table>
<?php } ?>

<?php if(is_array($warranty_items) && count($warranty_items)) { ?>
<h4 class="headingAlignment">Spare parts under warranty</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="warProductPurchaseTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Godown</th>
                 <th>Qty</th>
                 <th>Rate</th>
                 <th>Amount</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 <th></th>
            </tr>
            <?php  $total_tax_amount = 0; $total_net_amount=0; for($i=1;$i<=count($warranty_items);$i++) { 
			$sales_item=$warranty_items[$i-1]['sales_item_details'];
			$item_tax_details = $warranty_items[$i-1]['tax_details'];
			if(is_numeric($sales_item['tax_amount'])){
			 $total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			}
			if(is_numeric($sales_item['tax_amount'])) 
			$nett_amount = round($sales_item['net_amount']+$sales_item['tax_amount'],2); 
			else 
			$nett_amount = round($sales_item['net_amount'],2);
			
			$total_net_amount = $total_net_amount + $nett_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                     <td align="center"><?php echo getGodownNameFromGodownId($sales_item['godown_id']); ?> </td>
                    <td align="center"><?php echo number_format($sales_item['quantity']); ?></td>
                     <td align="center"><?php echo number_format($sales_item['rate']); ?> Rs</td>
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php echo $nett_amount;  ?> Rs</td>
                    
            	</tr>
            </tbody>
            <?php } ?>
              <?php  
			$total_tax_amount_spares_warranty = $total_tax_amount;
			$total_net_amount_spares_warranty = $total_net_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><b>TOTAL</b></td>
                     <td align="center"></td>
                    <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"></td>
                     <td align="center"><?php echo $total_tax_amount_spares_warranty; ?> Rs</td>
                     <td align="center"><?php  echo $total_net_amount_spares_warranty; ?> Rs</td>
                    
            	</tr>
             </tbody>  
    	</table>
    </td>

</tr>

<table>
<?php } ?>
<?php if(is_array($regular_ns_items) && count($regular_ns_items)) { ?>
<h4 class="headingAlignment">Labour / Service</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td >
    	<table width="100%" class="adminContentTable productPurchaseTable" id="nonStockSaleTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Rate</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                 
            </tr>
            <?php  $total_tax_amount = 0; $total_net_amount=0; for($i=1;$i<=count($regular_ns_items);$i++) { 
			$sales_item=$regular_ns_items[$i-1]['sales_item_details'];
			$item_tax_details = $regular_ns_items[$i-1]['tax_details'];
			 if(is_numeric($sales_item['tax_amount'])){
			 $total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			}
			if(is_numeric($sales_item['tax_amount'])) 
			$nett_amount = round($sales_item['net_amount']+$sales_item['tax_amount'],2); 
			else 
			$nett_amount = round($sales_item['net_amount'],2);
			
			$total_net_amount = $total_net_amount + $nett_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                    
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                    
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php echo $nett_amount; ?> Rs</td>
                    
            	</tr>
            </tbody>
            <?php } ?>
            <?php  
			$total_tax_amount_nonstock = $total_tax_amount;
			$total_net_amount_nonstock = $total_net_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><b>TOTAL</b></td>
                     <td align="center"></td>
                    <td align="center"></td>
                     
                     
                     <td align="center"><?php echo $total_tax_amount_nonstock; ?> Rs</td>
                     <td align="center"><?php  echo $total_net_amount_nonstock; ?> Rs</td>
                    
            	</tr>
             </tbody>  
    	</table>
    </td>

</tr>

</table>
<?php } ?>
<?php if(is_array($outside_job_items) && count($outside_job_items)) { ?>
<h4 class="headingAlignment">Out Side Job</h4>
<table width="100%" style="margin-top:20px;margin-bottom:20px;">
<tr>
	<td>
    	<table width="100%" class="adminContentTable productPurchaseTable" id="outSideJobTable">
    		<tr>
            	<th>Item Name / Code</th>
                 <th>Rate</th>
                 <th>Disc.</th>
                 <th>Tax</th>
                 <th>Nett Amt.</th>
                
                 
            </tr>
            <?php  $total_tax_amount = 0; $total_net_amount=0; for($i=1;$i<=count($outside_job_items);$i++) { 
			$sales_item=$outside_job_items[$i-1]['sales_item_details'];
			$item_tax_details = $outside_job_items[$i-1]['tax_details'];
			 if(is_numeric($sales_item['tax_amount'])){
			 $total_tax_amount = $total_tax_amount + $sales_item['tax_amount'];
			}
			if(is_numeric($sales_item['tax_amount'])) 
			$nett_amount = round($sales_item['net_amount']+$sales_item['tax_amount'],2); 
			else 
			$nett_amount = round($sales_item['net_amount'],2);
			
			$total_net_amount = $total_net_amount + $nett_amount;
			 $outside_job_details = getOutSideLabourJVForNonStockId($sales_item['sales_non_stock_id']);
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><?php echo getItemNameFromItemId($sales_item['item_id']); ?></td>
                    
                     <td align="center"><?php echo number_format($sales_item['amount']); ?> Rs</td>
                    
                     <td align="center"><?php echo $sales_item['discount']; ?> %</td>
                     <td align="center"><?php if(is_numeric($sales_item['tax_group_id'])) echo getTaxGroupNameByID($sales_item['tax_group_id'])."(".getTotalTaxPercentForTaxGroup($sales_item['tax_group_id'])."%)"." - ".$sales_item['tax_amount']." Rs"; else echo "Not Applicable"; ?> </td>
                     <td align="center"><?php echo $nett_amount;  ?> Rs</td>
                    
            	</tr>
            </tbody>
            <?php } ?>
            <?php  
			$total_tax_amount_nonstock_oj = $total_tax_amount;
			$total_net_amount_nonstock_oj = $total_net_amount;
			?>
            <tbody id="p<?php echo $i; ?>">
            	<tr >
                    <td><b>TOTAL</b></td>
                     <td align="center"></td>
                    <td align="center"></td>
                     
                     
                     <td align="center"><?php echo $total_tax_amount_nonstock_oj; ?> Rs</td>
                     <td align="center"><?php  echo $total_net_amount_nonstock_oj; ?> Rs</td>
                    
            	</tr>
             </tbody>  
    	</table>
    </td>

</tr>
</table>
<?php } ?>
  
  <div class="totalOfAll">
  
  <div class="tableTitle" style="border-bottom: 1px solid #000">
   FINAL CALCULATION
  </div>  <!-- End of tableTitle -->
  
    <div class="block">
     <b> Spares Used : </b> Rs.<?php $grand_total = 0; if(is_numeric($total_net_amount_spares)) { echo $total_net_amount_spares; $grand_total = $grand_total + $total_net_amount_spares; } else echo 0; ?>
    </div>   <!-- End of block -->
    
    <div class="block">
    <b> Consumable Used : </b> Rs.<?php if(is_numeric($total_net_amount_spares_lub)){ echo $total_net_amount_spares_lub; $grand_total = $grand_total + $total_net_amount_spares_lub; }else echo 0; ?>
    </div>   <!-- End of block -->
    
    <div class="block">
    <b> Labour Charges : </b> Rs.<?php if(is_numeric($total_net_amount_nonstock)) { echo $total_net_amount_nonstock; $grand_total = $grand_total + $total_net_amount_nonstock; } else echo 0; ?>
    </div>   <!-- End of block -->
    
    <div class="block">
    <b> Outside Job Done : </b> Rs.<?php if(is_numeric($total_net_amount_nonstock_oj)) {echo $total_net_amount_nonstock_oj; $grand_total = $grand_total + $total_net_amount_nonstock_oj; }else echo 0; ?>
    </div>   <!-- End of block -->
    
    <div class="clearDiv"> </div>
    
  </div>  <!-- End of totalofAll -->
  
  <DIV class="lastDiv">
  
  <div class="date">
  Date :   <?php echo date('d/m/Y',strtotime($job_card_detials['job_card_datetime'])); ?> 
  </div>
  
  <div class="grandtotal">
   <b> Grand Total : </b> Rs.<?php echo $grand_total; ?>
  </div>
  
  <div class="signature">
  Customer Signature : 
  </div>
  
  <div class="clearDiv"></div>
  
  </div>  <!-- End of lastDiv-->
  <div class="addDetailsBtnStyling no_print">
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard&id='.$job_card_id; ?>">
<button class="btn viewBtn no_print"> Front Page </button>
</a>

<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card_id ?>"><button title="View this entry" class="btn viewBtn btn-success">Back</button></a>
  </div>
</div>  <!-- End of mainDiv-->