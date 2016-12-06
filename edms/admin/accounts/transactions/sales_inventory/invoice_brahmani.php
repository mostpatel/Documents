<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$sales_id = $_GET['id'];
$inventory_items=getInventoryItemForSaleId($sales_id);
$tax_wise_amount_array  = getTaxwiseAmountForSaleId($sales_id);

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
$items_per_page=35;
$total_items = count($inventory_items) + count($non_stock_items) + count($tax_wise_amount_array) + count($individual_tax_wise_amount_array)+1;
if($total_items>$items_per_page)
$no_of_pages = ceil($total_items/$items_per_page);
else
$no_of_pages=1;
 ?>
 <?php 
$total = 0;
$i=1;
$j=1;
$k=0;
$l=0;
$invoice_type = getInvoiceTypeById($sales['retail_tax']);
$tax_invoice_type = getTaxInvoiceTypeForOcId($oc['our_company_id']);

for($page=0;$page<$no_of_pages;$page++)
{ ?>
<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/a5_tata.css" />
<div class="mainInvoiceContainer" style="width:95%;page-break-after: always;">

   <div class="sectionOne" style="min-height:75px;">
         <div class="">
          	
             
               <div style="font-size:40px;height:80%;text-align:center;letter-spacing:7px;font-weight:bold;">BRAHMANI</div>
               <div style="font-size:20px;height:80%;text-align:center;font-weight:bold;">AUTOMOBILES & ELECTRIC WORKS</div>
            
          </div>   <!-- End of rightSectionOne -->
            <div class="" style="">
            <div style="font-size:16px;text-align:center;"> 
          Shop No. 24-25, Shukan Bungalow, Opp. Alekh Complex, Opp. Cargil Petrol Pump, Ghatlodiya, Ahmedabad-380061<br>
          Mobile : 9879561230 / 8734956930
          </div>
          </div>
         
          
          <div class="clearFix"></div>
     
          
        </div> <!-- End of SectionOne -->
        
        <div class="sectionTwo">
        
        <div class="cusName">
         Name : <?php if(is_numeric($sales['to_ledger_id']))  echo getLedgerNameFromLedgerId($sales['to_ledger_id']); else
		{
			
				 echo $customer['customer_name'];} echo "<br>"; if($sales['retail_tax']==$tax_invoice_type['invoice_type_id']) { ?> TIN No : <?php if(is_numeric($sales['to_customer_id'])) echo $customer['tin_no']; else echo $ledger['sales_no']; ?> <?php } ?>
      
        </div>   <!-- End of chalanNo -->
        
         <div class="date">
           <b><?php echo $invoice_type['invoice_type'];  ?>  Invoice</b><br />
        Date : <?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
        
        </div>   <!-- End of vNo -->
        
     
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionTwo -->
  
  
        <div class="sectionThree">
        
        <div class="cusName">
         Area : <?php  echo $area; ?>
        </div>   <!-- End of cusName -->
        
         <div class="vNo">
     
        </div>   <!-- End of vNo -->
       
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionThree -->
        
           <div class="sectionThree">
        
         <div class="custNo" style="width:65%;">
        Contact No : <?php foreach($customer['contact_no'] as $contact) echo $contact['customer_contact_no']." "; ?>
        </div>   <!-- End of vNo -->
      
           <div class="date">
          Invoice No : <?php echo $sales['invoice_no']; ?>
       
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionThree -->
        
        
        
        <div class="sectionFour">
        
          <table class="vaibhavTable" style="border-right:1px solid #000;">
              <tr class="headingRow" style="border-top:1px solid #000;border-bottom:1px solid #000">
            
            <td> No. </td>
            <td> Item Code </td> 
            <td> Particulars </td>
            <td> Qty. </td>
            <td> Unit </td>
            <td> Rate </td>
         
            <td> Discount (%) </td> 
            <td> Tax</td> 
            <td style="border:none;"> Net Amount</td>
            
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
			$page_item_count++;
			if($page_item_count>$page_max_item_count)
			break;
			?>
            <tr>
            <td> <?php echo $i; ?> </td>
             <td> <?php echo getItemCodeFromItemId($inventory_item['item_id']); ?> </td>
            <td align="left"> <?php echo getItemNameFromItemId($inventory_item['item_id']); ?> </td>
            <td>  <?php echo $inventory_item['quantity']; ?> </td>
             <td>  <?php echo getUnitNameFromItemId($inventory_item['item_id']); ?> </td>
             <td> <?php  echo round((($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)))/$inventory_item['quantity'],3) ; ?> </td>
            <td> <?php echo $inventory_item['discount']; ?> </td>  
            <td> <?php // if(is_numeric($inventory_item['tax_amount'])) echo round($inventory_item['tax_amount'],2); else echo 0;
						echo getMainTaxPercentForTaxGroupId($inventory_item['tax_group_id'])."% ";
			 ?> </td> 
            <td> <?php   echo round($inventory_item['net_amount'],2); ?> </td>
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
          <td> <?php echo getMFgCodeFromItemId($inventory_item['item_id']); ?> </td> 
            <td align="left"> <?php echo getItemNameFromItemId($inventory_item['item_id']); ?> </td>
            <td>  <?php echo 1; ?></td>
            <td>  <?php echo getUnitNameFromItemId($inventory_item['item_id']); ?> </td>
            <td> <?php  echo round(($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)),2) ; ?></td>
          
           <td> <?php echo $inventory_item['discount']; ?> </td> 
            <td> <?php // if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['tax_amount']; else echo 0;
			echo getMainTaxPercentForTaxGroupId($inventory_item['tax_group_id'])."% ";
			 ?> </td> 
            <td> <?php  echo round($inventory_item['net_amount'],2); ?> </td>
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
            	 <tr >
            
            <td height="27px"> <?php  ?>  </td>
            <td>  </td> 
            <td>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td>Amount @ <?php echo $tax_wise[0]; ?></td> 
            <td><?php echo $tax_wise[1]; ?>   </td> 
            <td></td>
            
            </tr>
            <?php }} ?>
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
            <td>  </td> 
            <td>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td> <?php echo $tax_wise[0]; ?></td> 
            <td><?php echo $tax_wise[2]." %"; ?>  </td> 
            <td><?php echo round($tax_wise[1],2); ?> </td>
            
            </tr>
            <?php
			$total = $total +  round($tax_wise[1],2);
			 }} ?>
             <?php  if($page==$no_of_pages-1 && (round(round($total)-round($total,2),2))!=0) { ?>
              <tr  >
            
            <td>  </td>
           <td>  </td> 
            <td>   </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td>Round Off</td> 
            <td> <?php ?>  </td> 
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
            <td>  </td> 
            <td>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td></td> 
            <td> <?php ?>  </td> 
            <td></td>
            
            </tr>
            
            <?php	
				}
			}
			
			  ?>
               <tr class="total_tr" style="border-top:1px solid #000;border-bottom:1px solid #000">
            
            <td>  </td>
           <td>  </td> 
            <td> <b><?php  if($page==$no_of_pages-1) { ?>GRAND<?php } else { ?>PAGE<?php } ?> TOTAL</b>  </td>
            <td>  </td>
             <td>  </td>
            <td></td> 
            <td></td> 
            <td> <?php ?>  </td> 
            <td> <b><?php  if($page==$no_of_pages-1) { ?><?php echo round($total); ?><?php } ?></b> </td>
            
            </tr>
          </table>
           
          <?php if($page==$no_of_pages-1) { ?>
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
        
       <?php if($page==$no_of_pages-1) { ?> Amount in words : <?php  } else { ?>Page Total in words : <?php } ?> <?php echo numberToWord(round($total))." Only"; ?>
        </div>
         <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Company's VAT TIN No :  <?php  echo $oc['tin_no']; ?> <?php  } ?>
        </div>
        <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Company's C.S.T. No :  <?php echo $oc['cst_no']; ?> <?php  } ?>
        </div>
         <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Note : <?php echo $sales['invoice_note']; ?> <?php  } ?> 
        </div>
       
  
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
        <div class="sectionFive" style="margin-bottom:100px;">
        <div class="customerSign" style="font-size:13px;width:70%">
      <ul style="font-size:16px;">
      <li>Goods once sold will not be taken back.</li>
      <li>Prices running at the time of supply will be applicable.</li>
      <li>Subject to Ahmedabad jurisdiction.</li>
      <li>Any type of damages while repairing the vehicle will be beared by customer.</li>
      </ul>
       </div>
        <?php if($page==$no_of_pages-1) { ?> 
       <div class="total" style="font-size:12px;width:25%">
       E. & O.E. <br /> For, Brahmani Auto
        </div>   <!-- End of date -->
        <?php } ?>
       
       </div>
    
</div>  
</div>
<?php } ?>
