<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$sales_id = $_GET['id'];
$inventory_items=getInventoryItemForDeliveryChallanId($sales_id);
$non_stock_items = getNonStockItemForDeliveryChallanId($sales_id);
$sales = getACDeliveryChallanByACDeliveryChallanId($sales_id);
$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);
$kasar = $receipt_amount[1];
$receipt_amount=$receipt_amount[0];
if(is_numeric($sales['to_customer_id']))
{
$customer=getCustomerDetailsByCustomerId($sales['to_customer_id']);
$area = getAreaNameByID($customer['area_id']);
}
}
else
exit;
$items_per_page=20;
$total_items = count($inventory_items) + count($non_stock_items);
if($total_items>$items_per_page)
$no_of_pages = ceil($total_items/$items_per_page);
else
$no_of_pages=1;

?>
<link rel="stylesheet" href="../../../../css/a5.css" />
<?php 
$total = 0;
$i=1;
$j=1;
for($page=0;$page<$no_of_pages;$page++)
{ ?>
<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/a5.css" />
<div class="mainInvoiceContainer" style="page-break-after: always;">

   <div class="sectionOne">
        
          <div class="leftSectionOne">
             <img src="<?php echo WEB_ROOT."images/ambica-logo.png" ?>" style="min-width:300px;min-height:48px" />   
          
          </div>   <!-- End of LeftSectionOne -->
        
          <div class="rightSectionOne">
          	
             <img src="<?php echo WEB_ROOT."images/atul-logo.png" ?>" style="max-width:200px;float:right" id="atul_logo" />   
            
            
          </div>   <!-- End of rightSectionOne -->
          
          <div class="clearFix"></div>
          
        </div> <!-- End of SectionOne -->
        
        <div class="sectionTwo">
        
        <div class="cusName">
         Name : <?php if(is_numeric($sales['to_ledger_id']))  echo getLedgerNameFromLedgerId($sales['to_ledger_id']); else
		{
			
				 echo $customer['customer_name'];} ?>
      
        </div>   <!-- End of chalanNo -->
        
         <div class="date">
           <b>Retail Invoice</b><br />
        Date : <?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
        
        </div>   <!-- End of vNo -->
        
     
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionTwo -->
  
  
        <div class="sectionThree">
        
        <div class="cusName">
         Area : <?php  echo $area; ?>
        </div>   <!-- End of cusName -->
        
         <div class="vNo">
      <!-- Vehicle No : <?php echo $vehicle['vehicle_reg_no']; ?> -->
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
        
          <table class="vaibhavTable">
              <tr class="headingRow" style="border-top:1px solid #000;border-bottom:1px solid #000">
            
            <td> No. </td>
         <!--   <td> Item Code </td> -->
            <td> Particulars </td>
               <td> Qty. </td>
            <td> Rate </td>
         
       <!--     <td> Discount (%) </td> -->
         <!--   <td> Tax</td>  -->
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
           <!--  <td> <?php echo getItemCodeFromItemId($inventory_item['item_id']); ?> </td> -->
            <td align="left"> <?php echo getItemNameFromItemId($inventory_item['item_id']); ?> </td>
           
            <td>  <?php echo $inventory_item['quantity']; ?> </td>
             <td> <?php echo $inventory_item['rate']; ?> </td>
          <!--  <td> <?php echo $inventory_item['discount']; ?> </td> -->
           <!-- <td> <?php if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['tax_amount']; else echo 0; ?> </td> -->
            <td> <?php  if(is_numeric($inventory_item['tax_amount'])) echo round($inventory_item['net_amount']+$inventory_item['tax_amount']); else echo round($inventory_item['net_amount']); ?> </td>
            </tr>
            
            <?php
		if(is_numeric($inventory_item['tax_amount']))
		{
			$tax = $tax + round($inventory_item['tax_amount']);
			$total = $total + round($inventory_item['net_amount'])+round($inventory_item['tax_amount']);
		}
		else
		{
			$total = $total + round($inventory_item['net_amount']);
			}	
			}
			?>
             <?php 
				for($j; $j<=count($non_stock_items); $j++)
			{
	
			$inventory_item = $non_stock_items[$j-1]['sales_item_details'];	
			$tax_details = $non_stock_items[$j-1]['tax_details'];
			$page_item_count++;
		    if($page_item_count>$page_max_item_count)
			break;
			
			?>
            <tr>
            <td> <?php echo $i++; ?> </td>
       <!--     <td> <?php echo getMFgCodeFromItemId($inventory_item['item_id']); ?> </td> -->
            <td align="left"> <?php echo getItemNameFromItemId($inventory_item['item_id']); ?> </td>
              <td>  <?php echo 1; ?></td>
            <td> <?php echo $inventory_item['amount']; ?> </td>
          
        <!--    <td> <?php echo $inventory_item['discount']; ?> </td> 
            <td> <?php if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['tax_amount']; else echo 0; ?> </td> -->
            <td> <?php  if(is_numeric($inventory_item['tax_amount'])) echo round($inventory_item['net_amount']+$inventory_item['tax_amount']); else echo round($inventory_item['net_amount']); ?> </td>
            </tr>
            
            <?php
			if(is_numeric($inventory_item['tax_amount']))
		{
			$tax = $tax + round($inventory_item['tax_amount']);
			$total = $total + round($inventory_item['net_amount'])+round($inventory_item['tax_amount']);
		}
		else
		{
			$total = $total + round($inventory_item['net_amount']);
			}	
			}
			?>
               <?php $total_rows=$i;
			if($total_rows<$page_max_item_count)
			{
				for($k=$total_rows;$k<$page_max_item_count;$k++)
				{
			?>
             <tr >
            
            <td height="23px"> <?php  ?>  </td>
        <!--    <td>  </td> -->
            <td>  </td>
            <td>  </td>
         <!--   <td></td> -->
          <!--  <td></td> -->
            <td> <?php ?>  </td> 
            <td></td>
            
            </tr>
            
            <?php	
				}
			}
			
			  ?>
             <tr class="total_tr" style="border-top:1px solid #000;border-bottom:1px solid #000">
            
            <td>  </td>
        <!--    <td>  </td> -->
             <td> <b><?php  if($page==$no_of_pages-1) { ?>GRAND TOTAL<?php } else { ?>To be continued<?php } ?></b>  </td>
            <td>  </td>
         <!--   <td></td> -->
          <!--  <td></td> -->
            <td> <?php ?>  </td> 
           <td> <b><?php  if($page==$no_of_pages-1) { ?><?php echo round($total); ?><?php } ?></b> </td>
            </tr>
          </table>
           
        </div> <!-- End of sectionFour -->
        
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
        
       <?php if($page==$no_of_pages-1) { ?> Amount in words : <?php echo numberToWord(round($total))." Only"; } else { ?>Turn on the next page. <?php } ?> <?php  ?>
        </div>
  
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
        <div class="sectionFive" style="margin-bottom:100px;">
        <div class="customerSign" style="font-size:13px;width:70%">
      <ul>
      <li>Goods once sold will not be taken back.</li>
      <li>Prices running at the time of supply will be applicable.</li>
      <li>Subject to Ahmedabad jurisdiction.</li>
      <li>Any type of damages while repairing the vehicle will be beared by customer.</li>
      </ul>
       </div> 
        <?php if($page==$no_of_pages-1) { ?>
       <div class="total" style="font-size:12px;width:25%">
       E. & O.E. <br /> For, Ambica Auto
        </div><?php } ?>
       
       </div>
      
    
</div>  <!-- End of mainInvoiceContainer -->
<?php } ?>
</body>
</html>