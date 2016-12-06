<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$sales_id = $_GET['id'];
$inventory_items=getInventoryItemForSaleId($sales_id);

$tax_wise_amount_array  = getTaxwiseAmountForSaleId($sales_id);
$sales_info = getSalesInfoForSalesId($sales_id);

$individual_tax_wise_amount_array = getIndividualTaxWiseAmountForSaleId($sales_id);

$non_stock_items = getNonStockItemForSaleId($sales_id);

$sales = getSaleById($sales_id);
$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);
$receipt_amount = getReceiptAmountAndKasarAmountForJobCardId($job_card_id);
$kasar = $receipt_amount[1];
$receipt_amount=$receipt_amount[0];
if(is_numeric($sales['to_customer_id']))
{
$customer=getCustomerDetailsByCustomerId($sales['to_customer_id']);
$area = getAreaNameByID($customer['area_id']);
$oc = getOurCompanyByID($customer['oc_id']);
}
else
{
$ledger = getLedgerById($sales['to_ledger_id']);
$oc = 	getOurCompanyByID($ledger['oc_id']);
}
}
else
exit;
$items_per_page=29;
$total_items = count($inventory_items) + count($non_stock_items) + count($tax_wise_amount_array) + count($individual_tax_wise_amount_array)+1;
if($total_items>$items_per_page)
$no_of_pages = ceil($total_items/$items_per_page);
else
$no_of_pages=1;
 ?>
<div class="addDetailsBtnStyling no_print"> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php?view=details&id=<?php echo $sales_id; ?>"><button class="btn btn-success">Back To Sale Details</button></a>    </div> 
 <?php 
$total = 0;
$i=1;
$j=1;
$k=0;
$l=0;
$invoice_type = getInvoiceTypeById($sales['retail_tax']);
for($page=0;$page<$no_of_pages;$page++)
{ ?>
<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/a5_tata.css" />
<div style="width:95%;font-size:20px;font-weight:bold;text-align:center;text-transform:uppercase"><?php  echo $invoice_type['invoice_type_print_name'];  ?></div>
<div class="mainInvoiceContainer" style="width:95%;page-break-after: always;border-radius:0;border:1px solid #ccc;">

   <div class="sectionOne" style="min-height:0;border-bottom:0;">
        
       <div class="leftSectionOne" style="width:49.8%;text-align:left;padding:0;box-sizing:border-box;padding-left:2px;padding-top:5px;box-sizing:border-box;">
      
             <b><?php echo $our_company['our_company_name']; ?></b><br />
             <span style="font-size:18px;">
             
            B-22/2, First Floor, Meldy Estate,<br />
            Nr. Railway Crossing, Gota,<br />
            Ahmedabad, Gujarat-382481</span>
            
            <div style="border-top:1px solid #ccc;margin-top:50px;">
            
             <span style="font-size:15px;">Buyer</span><br />
             <span style="font-size:18px;">
             
           <?php if(is_numeric($sales['to_ledger_id']))  echo "<b>".getLedgerNameFromLedgerId($sales['to_ledger_id'])."</b>"; else
		{
			
				 echo "<b>".$customer['customer_name']."</b>";} echo "<br>"; echo $customer['customer_address'];
				 if(validateForNull($sales_info['consignee_address']))
				 echo "<br>  <span style='font-size:15px;font-weight:bold'>Consignee Address :</span> ".$sales_info['consignee_address'];
				   ?>
                  
                  </span>
            </div>
            
          
          </div>   <!-- End of LeftSectionOne -->
          
        
        
          <div class="rightSectionOne" style="width:50%;float:right;padding:0;border-right:0;border-bottom:0;">
          		<table class="sales_info_table" width="100%" border="1" style="border-top:0;border-bottom:0;border-right:0;border-left:0" >
                <tr>
                <td>
                	  Invoice No.<br /> <b><?php echo $sales['invoice_no']; ?></b>
                </td>
                <td>Dated <br /> <b> <?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?></b>
        </td>
                </tr>
                	<tr >
<td >Delivery Note <br>
					<?php echo $sales_info['delivery_note']; ?>
                  
                            </td>

<td>Terms of Payment <br>
					<?php echo $sales_info['terms_of_payment']; ?> 
                  
                            </td>
</tr>

<tr >
<td>Supplier's Ref <br>
					<?php echo $sales_info['supplier_ref_no']; ?> 
                  
                            </td>

<td>Other Reference(s) <br>
					<?php echo $sales_info['other_reference']; ?> 
                  
                            </td>
</tr>

<tr >
<td>Buyer's Order No <br>
					<?php echo $sales_info['buyers_order_no']; ?> 
                  
                            </td>

<td>Dated <br>
					<?php if($sales_info['order_date']!="1970-01-01") echo date('d/m/Y',strtotime($sales_info['order_date'])); ?> 
                  
                            </td>
</tr>

<tr >
<td>Despatch Document No <br>
					<?php echo $sales_info['despatch_doc_no']; ?> 
                  
                            </td>

<td>Dated <br>
					<?php if($sales_info['despatch_dated']!="1970-01-01") echo date('d/m/Y',strtotime($sales_info['despatch_dated'])); ?> 
                  
                            </td>
</tr>

<tr >
<td>Despatched through <br>
					<?php echo $sales_info['despatched_through']; ?> 
                  
                            </td>

<td>Destination <br>
					<?php echo $sales_info['destination']; ?> 
                  
                            </td>
</tr>

<tr style="height:70px;">
<td colspan="2" >Terms Of Delivery <br />
					<?php echo $sales_info['terms_of_delivery']; ?> 
                            </td>
</tr>
                </table>
                
             
            
            
          </div>   <!-- End of rightSectionOne -->
          
          
          
          <div class="clearFix"></div>
          
        </div> <!-- End of SectionOne -->
        
       
        
        
        <div class="sectionFour" style="margin:0">
        
          <table class="vaibhavTable">
              <tr class="headingRow" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding-top:2%;">
            
            <td> No. </td>
          <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>  <td> Item Code </td> <?php } ?> 
            <td> Description Of Goods </td>
            <td> Qty. </td>
            
            <td> Rate </td>
            <td> Per </td>
            <td> Disc (%) </td> 
          <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>  <td> Tax</td>  <?php } ?>
            <td style="border:none;"> Amount</td>
            
            </tr>
            
           
            
              <?php
			 
				$tax = 0; 
			$page_total=0;
			$page_item_count = $page*$items_per_page;
			$page_max_item_count = ($page+1)*$items_per_page;
			
			for($i; $i<=count($inventory_items); $i++)
			{
			$inventory_item = $inventory_items[$i-1]['sales_item_details'];	
			
			$tax_details = $inventory_items[$i-1]['tax_details'];
			$trans_item_unit_details = getTransItemUnitBySalesItemId($inventory_item['sales_item_id']);
		
			$page_item_count++;
			if($page_item_count>$page_max_item_count)
			break;
			?>
            <tr>
            <td> <?php echo $i; ?> </td>
           <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>   <td> <?php echo getItemCodeFromItemId($inventory_item['item_id']); ?> </td> <?php } ?>
            <td align="left"> <b><?php echo getItemNameFromItemId($inventory_item['item_id']); ?></b> <br /> <span style="  font-size:15px;font-style:italic">&nbsp; &nbsp;<?php $item_desc = str_replace('##','<br/>&nbsp;&nbsp;',$inventory_item['item_desc']); echo $item_desc; ?></span></td>
            <td> <b> <?php if(!is_numeric($trans_item_unit_details['quantity'])) {echo number_format($inventory_item['quantity']); $quantity = $inventory_item['quantity'];} else {echo $trans_item_unit_details['quantity']; $quantity = $trans_item_unit_details['quantity']; }   ?> </b> </td>
            
             <td> <?php  echo round((($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)))/$quantity,3); ?> </td>
              <td>  <?php echo $trans_item_unit_details['unit_name'];  ?> </td>
            <td> <?php echo $inventory_item['discount']; ?> </td>  
            <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>  <td> <?php // if(is_numeric($inventory_item['tax_amount'])) echo round($inventory_item['tax_amount'],2); else echo 0;
						echo getMainTaxPercentForTaxGroupId($inventory_item['tax_group_id'])."% ";
			 ?> </td> <?php } ?>
            <td> <b> <?php   echo round($inventory_item['net_amount'],2); ?></b> </td>
            </tr>
            
            <?php
		if(is_numeric($inventory_item['tax_amount']))
		{
			$tax = $tax + round($inventory_item['tax_amount'],2);
			$total = $total + round($inventory_item['net_amount'],2);
		}
		else
		{
			$total = $total + round($inventory_item['net_amount'],2);
			}	
			}
			?>
             <?php 
			for($j; $j<=count($non_stock_items); $j++,$i++)
			{
	
			$inventory_item = $non_stock_items[$j-1]['sales_item_details'];	
			$tax_details = $non_stock_items[$j-1]['tax_details'];
			$page_item_count++;
			if($page_item_count>$page_max_item_count)
			break;
			?>
            <tr>
            <td> <?php echo $i; ?> </td>
        <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>   <td> <?php echo getMFgCodeFromItemId($inventory_item['item_id']); ?> </td>  <?php } ?>
            <td align="left"> <b><?php echo getItemNameFromItemId($inventory_item['item_id']); ?></b> <br /> <span style="  font-size:15px;font-style:italic">&nbsp; &nbsp;<?php $item_desc = str_replace('##','<br/>&nbsp;&nbsp;',$inventory_item['item_desc']); echo $item_desc; ?></span> </td>
            <td>  <?php echo 1; ?></td>
            <td>  <?php echo getUnitNameFromItemId($inventory_item['item_id']); ?> </td>
            <td> <?php  echo round(($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)),2) ; ?></td>
          
           <td> <?php echo $inventory_item['discount']; ?> </td> 
             <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?> <td> <?php // if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['tax_amount']; else echo 0;
			echo getMainTaxPercentForTaxGroupId($inventory_item['tax_group_id'])."% ";
			 ?> </td> <?php } ?>
            <td> <b> <?php  echo round($inventory_item['net_amount'],2); ?></b> </td>
            </tr>
            
            <?php
			if(is_numeric($inventory_item['tax_amount']))
		{
			$tax = $tax + round($inventory_item['tax_amount'],2);
			$total = $total + round($inventory_item['net_amount'],2);
		}
		else
		{
			$total = $total + round($inventory_item['net_amount'],2);
			}	
			}
			?>
            
          <?php 
			
			if(count($tax_wise_amount_array)>0)
			{
				$tax_wise_amount_keys_array = array_keys($tax_wise_amount_array);
				
			for($k; $k<count($tax_wise_amount_array); $k++,$i++)
			{ 
			$page_item_count++;
			$tax_wise = $tax_wise_amount_array[$tax_wise_amount_keys_array[$k]];
			
			if($page_item_count>$page_max_item_count)
			break;
			
			?>
            <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>
            	 <tr >
            
            <td height="27px"> <?php  ?>  </td>
          <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>   <td>  </td> <?php } ?>
            <td>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td>  <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>Amount @ <?php } ?> <?php echo $tax_wise[0]; ?></td> 
           <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>   <td><?php echo $tax_wise[1]; ?>   </td> <?php } ?> 
            <td></td>
            
            </tr>
            <?php } ?>
            <?php }} ?>
             <tr >
            
            <td height="27px"> <?php  ?>  </td>
         <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>    <td> </td> <?php } ?> 
            <td align="right">  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td> </td> 
            <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>  <td>  </td> <?php } ?> 
            <td style="border-top:1px solid #ccc;"><?php echo round($total,2); ?> </td>
            
            </tr>
            <?php 
			
			if(count($individual_tax_wise_amount_array)>0)
			{
			$individual_tax_wise_amount_keys_array = array_keys($individual_tax_wise_amount_array);
			
			for($l; $l<count($individual_tax_wise_amount_array); $l++,$i++)
			{ 
			$page_item_count++;
			$tax_wise = $individual_tax_wise_amount_array[$individual_tax_wise_amount_keys_array[$l]];
			if($page_item_count>$page_max_item_count)
			break; ?>
            	 <tr >
            
            <td height="27px"> <?php  ?>  </td>
         <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>    <td> </td> <?php } ?> 
            <td align="right"><b><i> <?php echo $tax_wise[0]; ?></i></b> </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td> </td> 
            <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>  <td><?php echo $tax_wise[2]." %"; ?>  </td> <?php } ?> 
            <td><b><?php echo round($tax_wise[1],2); ?></b> </td>
            
            </tr>
            <?php
			$total = $total +  round($tax_wise[1],2);
			 }} ?>
             <?php  if($page==$no_of_pages-1 && (round(round($total)-round($total,2),2))!=0) { ?>
              <tr  >
            
            <td>  </td>
     <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>       <td>  </td> <?php } ?> 
            <td align="right"> <b><i>Round Off</i></b>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td></td> 
            <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>  <td> <?php ?>  </td> <?php } ?> 
            <td> <b><?php  if($page==$no_of_pages-1) { ?><?php echo round(round($total)-round($total,2),2); ?><?php } ?></b> </td>
            
            </tr>
            <?php } ?>
               <?php $total_rows=$i;
			if($total_rows<$page_max_item_count)
			{
				for($k=$total_rows;$k<$page_max_item_count;$k++)
				{
			?>
             <tr >
            
            <td height="27px"> <?php  ?>  </td>
        <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>     <td>  </td> <?php  } ?> 
            <td>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td></td> 
            <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>  <td> <?php ?>  </td> <?php } ?>
            <td></td>
            
            </tr>
            
            <?php	
				}
			}
			
			  ?>
               <tr class="total_tr" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc">
            
            <td>  </td>
     <?php if(defined('INVOICE_ITEM_CODE') && INVOICE_ITEM_CODE==1) { ?>       <td>  </td> <?php } ?> 
            <td> <b><?php  if($page==$no_of_pages-1) { ?>GRAND<?php } else { ?>PAGE<?php } ?> TOTAL</b>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td></td> 
           <?php  if(defined('INVOICE_TAX_COLUMN') && INVOICE_TAX_COLUMN==1) { ?>   <td> <?php ?>  </td>  <?php } ?>
            <td> <b><?php  if($page==$no_of_pages-1) { ?>₹ <?php echo moneyFormatIndia(round($total)); ?><?php } ?></b> </td>
            
            </tr>
          </table>
           <span style="float:right">E & O.E</span>
          <?php if((defined('INVOICE_AMOUNT_PAID_LEFT' && INVOICE_AMOUNT_PAID_LEFT==1)) && $page==$no_of_pages-1) { ?>
        <div class="sectionFive">
        
        <div class="customerSign">
        Amount Paid : <?php echo round($receipt_amount); ?>
        </div>   <!-- End of chalanNo -->
        
        <div class="total">
        Amount Left : <?php echo round($total)- round($receipt_amount); ?>
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->   
        
      <?php } ?>
   <div class="sectionFive">
         
        <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Amount Chargeable in words : <?php  } else { ?>Page Total in words : <?php } ?> <b> <?php echo numberToWord(round($total))." Only"; ?> </b>
        </div>
         <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Company's VAT TIN No :<b>  <?php  echo $oc['tin_no']; ?> <?php  } ?></b>
        </div>
        <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Company's C.S.T. No :<b>  <?php echo $oc['cst_no']; ?> <?php  } ?></b>
        </div>
         <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Company's Service Tax No. :<b>  <?php echo $oc['service_tax_no']; ?> <?php  } ?></b>
        </div>
         <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Company's PAN No. : <b> <?php echo $oc['pan_no']; ?> <?php  } ?></b>
        </div>
         <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Note : <?php echo $sales['invoice_note']; ?> <?php  } ?> 
        </div>
       
  
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
        <div class="sectionFive" style="margin-bottom:100px;">
        <div class="customerSign" style="font-size:13px;width:70%">
        <span>Declaration (Terms):</span>
      <ol style="font-size:16px;">
      <li>Interest @18% will be charged on all bills unpaid<br /> after 30 days from the date of delivery of Invoice</li>
      <li>We reserve our rights to demand the payment of this bills any time.</li>
      <li>Bank Details: PENETRATE NAVIGATION DEVICE PVT LTD., CENTARL BANK OF INDIA , H LC C BRANCH, A/C NO. 3087157165. RTGS CODE NO. CBIN0281389</li>
     
      </ol>
       </div>
        <?php if($page==$no_of_pages-1) { ?> 
       <div class="total" style="font-size:12px;width:25%">
       <br /> For, <?php echo $our_company['our_company_name']; ?>
       <br /><br/><br /><br />
       Authorized Signatory
        </div>   <!-- End of date -->
        <?php } ?>
       
       </div>
    
</div>  
</div>
<?php } ?>
<script type="text/javascript">
window.print();
</script>