<?php if(isset($_GET['id']))
{
$purchase_items = getInventoryItemForPurchaseId($purchase_id);	// tax details inside the array
$regular_ns_items = getNonStockItemForPurchaseId($purchase_id);
$barcode_trans= getBarcodesFromTransIdAndTransType($purchase_id,1);
$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);
}
else
exit; ?>

<?php
for($i=1;$i<=count($purchase_items);$i++) 
{ 
			$purchase_item=$purchase_items[$i-1]['purchase_item_details'];
			$barcodes =getBarcodesFromTransIdAndTransType($purchase_id,1,$purchase_item['item_id']);  
			if($barcodes && is_array($barcodes)) 
			{ 
				foreach($barcodes as $barcode)
				 {   
			 
						  $barcode = $barcode[0]; 
						  $barcode_img= "<br> <img  src='".WEB_ROOT."lib/barcode.php?text=".$barcode."' />"; 
				
			?>
            <div>
             <div  style="padding-left:50px;"><?php echo $our_company['our_company_name']; ?></div>
             <?php echo $barcode_img; ?>
             <div style="padding-left:50px"><?php echo $barcode; ?></div>
             <?php echo $barcode_img; ?>
             <div style="padding-left:50px"><?php echo $barcode; ?></div>
             <?php echo $barcode_img; ?>
             <div style="padding-left:50px"><?php echo $barcode; ?></div>
             </div>
             <div style="page-break-after:always;"></div>
            	<?php 
					}
			} 
} ?>
   
<script type="text/javascript">
window.print();
</script>
