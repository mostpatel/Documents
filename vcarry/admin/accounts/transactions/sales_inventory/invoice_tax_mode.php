<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$_GET['show_header']=1;
$sales_id = $_GET['id'];
$inventory_items=getInventoryItemForSaleId($sales_id);
$tax_wise_amount_array  = getTaxwiseAmountForSaleId($sales_id);
$sales_info = getSalesInfoForSalesId($sales_id);
$individual_tax_wise_amount_array = getIndividualTaxWiseAmountForSaleId($sales_id);

$non_stock_items = getNonStockItemForSaleId($sales_id);

$sales = getSaleById($sales_id);
$our_company = getOurCompanyByID($sales['oc_id']);
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
$items_per_page=31;
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
for($page=0;$page<$no_of_pages;$page++)
{ ?>
<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/a5_tata.css" />
<!-- <div class="addDetailsBtnStyling no_print">
<?php if(isset($_GET['show_header']) && $_GET['show_header']==1) { ?> <a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php?view=invoice&id=<?php echo $sales_id; ?>"><button class="btn btn-success">Hide Header</button></a> 
<?php } else { ?>
<a href="<?php echo WEB_ROOT; ?>admin/accounts/transactions/sales_inventory/index.php?view=invoice&id=<?php echo $sales_id; ?>&show_header=1"><button class="btn btn-success">Show Header</button></a>  <?php } ?>  </div> -->
<div class="mainInvoiceContainer" style="<?php if(isset($_GET['show_header']) && $_GET['show_header']==1) { ?>width:95%;<?php }else { ?> width:90%; <?php } ?>page-break-after: always;border-radius:0;border:none">
<?php if(is_numeric($sales['to_customer_id'])) { ?>
<a class="no_print" href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$sales['to_customer_id'] ?>"><input type="button" class="btn btn-success" value="Back to customer"/></a>
<?php } ?>
<?php if((isset($_GET['show_header']) && $_GET['show_header']==1)) { ?>
<div style="position:relative;width:100%;height:220px">

<span style="width:95%;text-align:center;font-size:30px;text-transform:uppercase;position:relative;display:block;"><img src="<?php echo WEB_ROOT ?>images/vcarry_logo.png" height="100px" style="max-height:170px;margin-bottom:10px;" ></span>
<span style="width:95%;text-align:center;font-size:20px;text-transform:uppercase;position:relative;display:block;">508, SUKHSAGAR COMPLEX,
NR.FORTUNE LANDMARK,
USMANPURA,
ASHRAM ROAD, AHMEDABAD.</span>
</div>
<?php } ?>
   <div class="sectionOne" style="min-height:0;border-bottom:0;<?php if(!isset($_GET['show_header'])) { ?> margin-top:220px;  <?php } ?>">
        
        <div style="width:100%;height:1px;margin-bottom:25px;border:4px solid #bbb; "></div>
       <div class="leftSectionOne" style="width:69.55%;text-align:left;padding:0;box-sizing:border-box;">
      
            
            <div style="border:2px solid black;height:125px;height:125px;padding-left:5px;border-right:none;">
            
             M/s : <br />
             <span style="font-size:24px;font-weight:bold;">
             
           <?php if(is_numeric($sales['to_ledger_id']))  echo getLedgerNameFromLedgerId($sales['to_ledger_id']); else
		{
			
				 echo $customer['customer_name'];} echo "<br>"; echo $customer['customer_address'];  ?> </span>
            </div>
            
          
          </div>   <!-- End of LeftSectionOne -->
          
        
        
          <div class="rightSectionOne" style="width:30%;float:right;padding:0;border-right:none;border:2px solid black;height:125px;">
          		
                
          		<table class="sales_info_table" width="100%"  style="border-top:0;border-bottom:0;border-right:0;border-left:0;"  >
                <tr>
                <td style="border-bottom:2px solid black;padding-left:5px;height:62.5px;font-size:22px;font-weight:bold;" valign="middle">
                	  Bill No : <b><?php echo $sales['invoice_no']; ?></b>
                </td>
                </tr>
                <tr>
                <td style="padding-left:5px;font-size:22px;font-weight:bold;" valign="middle">Bill Date : <b> <?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?></b>
        </td>
            </tr>
            </table>
          </div>   <!-- End of rightSectionOne -->
          
          
          
          <div class="clearFix"></div>
          
        </div> <!-- End of SectionOne -->
        
        <div style="text-align:center;width:100%;padding-top:5px;padding-bottom:5px;border:2px solid black;border-bottom:none;margin-top:5px;box-sizing:border-box;font-size:16px">
        	Dear Sir, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; the statement of charges for services rendered.
        </div>
       
        
        
        <div class="sectionFour" style="margin:0">
        
          <table class="vaibhavTable" style="border-right:2px solid #000;border-left:2px solid #000;">
              <tr class="headingRow" style="border-top:2px solid #000;border-bottom:2px solid #000;padding-top:2%;">
            
            <td> No. </td>
           
            <td> Particulars </td>
           
            <td style="border:none;">Amount</td>
            
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
            <td style="font-size:24px;"> <?php echo $i; ?> </td>
            
            <td align="left" style="font-size:24px;"> <b><?php echo getItemNameFromItemId($inventory_item['item_id']); ?><?php if($inventory_item['item_desc']!="") echo "(".$inventory_item['item_desc'].")"; ?></b></td>
            
            <td style="font-size:24px;"> <?php   echo number_format(round($inventory_item['net_amount'],2)); ?> </td>
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
            <td style="font-size:24px;"> <?php echo $i; ?> </td>
         
            <td align="left" style="font-size:24px;">  <b><?php echo getItemNameFromItemId($inventory_item['item_id']); ?>  <?php if($inventory_item['item_desc']!="") echo "(".$inventory_item['item_desc'].")"; ?></b> </td>
           
            <td style="border-right:none;font-weight:bold;font-size:24px"> <?php  echo number_format(round($inventory_item['net_amount'],2),2); ?> </td>
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
            
          
            <?php }} ?>
        <?php $total_rows=$i;
			if($total_rows<$page_max_item_count)
			{
				for($k=$total_rows;$k<$page_max_item_count;$k++)
				{
			?>
             <tr >
            
            <td height="27px"> <?php  ?>  </td>
            <td>  </td> 
           
            <td style="border-right:none;"></td>
            
            </tr>
            
            <?php	
				}
			}
			
			  ?>
               <tr class="total_tr" style="border-top:1px solid #000;border-bottom:2px solid #000">
            
            <td>  </td>
           
            <td> <b ><?php  if($page==$no_of_pages-1) { ?>GRAND<?php } else { ?>PAGE<?php } ?> TOTAL</b>  </td>
           
            <td style="border-right:none;"> <b  style="font-size:22px;"><?php  if($page==$no_of_pages-1) { ?><?php echo "Rs ".number_format(round($total),2); ?><?php } ?></b> </td>
            
            </tr>
          </table>
           
          <?php if($page==$no_of_pages-1) { ?>
        <div class="sectionFive">
        
       
        
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->   
        
      <?php } ?>
   <div class="sectionFive">
         
        <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1) { ?> Amount in words : Rupees <?php  } else { ?>Page Total in words : <?php } ?> <?php echo numberToWord(round($total))." Only"; ?>
        </div>
         
         <div class="customerSign" style="width:100%">
        
       <?php if($page==$no_of_pages-1 && $sales['invoice_note']!="") { ?> Note : <?php echo $sales['invoice_note']; ?> <?php  } ?> 
        </div>
       
  
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
        <div class="sectionFive" style="margin-bottom:100px;">
        <div class="customerSign" style="font-size:13px;width:70%">
      <ul style="font-size:16px;">
    
      <li>Please make the payment immediately</li>
     
      </ul>
       </div>
        <?php if($page==$no_of_pages-1) { ?> 
       <div class="total" style="font-size:20px;width:25%;top:-50px;">
        For, <b><?php echo $our_company['our_company_name']; ?></b>
        <br /><br />
         <br /><br /> <br />
        Authorised Signatory
        </div>   <!-- End of date -->
        <?php } ?>
       
       </div>
    
</div>  
</div>
<?php } ?>
