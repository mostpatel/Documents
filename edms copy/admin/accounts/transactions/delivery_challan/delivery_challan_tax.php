<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$sales_id = $_GET['id'];

$sales=getACDeliveryChallanByACDeliveryChallanId($sales_id);
$inventory_items = getNonStockItemForDeliveryChallanId($sales_id);	
$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);

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
 <?php 
$total = 0;
$i=1;
$j=1;
$k=0;
$l=0;
for($page=0;$page<$no_of_pages;$page++)
{ ?>
<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/a5_tata.css" />
<div style="width:95%;font-size:20px;font-weight:bold;text-align:center;text-transform:uppercase"><?php echo DELIVERY_CHALLAN_NAME; ?></div>
<div class="mainInvoiceContainer" style="width:95%;page-break-after: always;border-radius:0;border:1px solid #ccc;">

   <div class="sectionOne" style="min-height:0;border-bottom:0;">
        
       <div class="leftSectionOne" style="width:49.8%;text-align:left;padding:0;box-sizing:border-box;">
      
      <div style="border:1px solid #ccc;border-left:none;">
             <b><?php echo $our_company['our_company_name']; ?></b><br />
             <span style="font-size:18px;">
             
            B-22/2, First Floor, Meldy Estate,<br />
            Nr. Railway Crossing, Gota,<br />
            Ahmedabad, Gujarat-382481</span>
            </div>
            <div style="border:1px solid #ccc;margin-top:50px;border-left:none;padding-bottom:30px;">
            
            <b> Name and Address of Consignee</b><br />
             <span style="font-size:16px;">
             <b>
           <?php if(is_numeric($sales['to_ledger_id']))  echo getLedgerNameFromLedgerId($sales['to_ledger_id']); else
		{
			
				 echo $customer['customer_name'];} echo "</b><br>"; echo $customer['customer_address'];  ?> </span>
            </div>
            
          
          </div>   <!-- End of LeftSectionOne -->
          
        
        
          <div class="rightSectionOne" style="width:50%;float:right;padding:0;border-right:1px solid #ccc;">
          		<table class="sales_info_table" width="100%" border="1" style="border-top:0;border-bottom:0;border-right:0;border-left:0" >
                <tr>
                <td>
                	<?php echo DELIVERY_CHALLAN_NAME; ?>   No.<br /> <b><?php echo $sales['challan_no']; ?></b>
                </td>
                <td>Dated <br /> <b> <?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?></b>
        </td>
                </tr>
                <!--	<tr >
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
</tr> -->
                </table>
                
             
            
            
          </div>   <!-- End of rightSectionOne -->
          
          
          
          <div class="clearFix"></div>
          
        </div> <!-- End of SectionOne -->
        
       
        
        
        <div class="sectionFour" style="margin:0">
        
          <table class="vaibhavTable">
              <tr class="headingRow" style="border-top:1px solid #000;border-bottom:1px solid #000;padding-top:2%;">
            
            <td> No. </td>
            <td> Particulars </td>
            <td> Qty. </td>
            <td> Unit </td>
            <td> Rate </td>  
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
			$trans_item_unit_details = getTransItemUnitBySalesItemId($inventory_item['sales_item_id']);
			?>
            <tr>
            <td> <?php echo $i; ?> </td>
           
            <td align="left"> <b><?php echo getItemNameFromItemId($inventory_item['item_id']); ?></b> <br /> <span style="  font-size:15px;font-style:italic">&nbsp; &nbsp;<?php $item_desc = str_replace('##','<br/>&nbsp;&nbsp;',$inventory_item['item_desc']); echo $item_desc; ?></span></td>
            <td align="center"><?php if(!is_numeric($trans_item_unit_details['quantity'])) echo number_format($inventory_item['quantity']); else echo $trans_item_unit_details['quantity'];  ?></td>
           
             <td>  <?php echo $trans_item_unit_details['unit_name']; ?> </td>
             <td> <?php  echo round((($inventory_item['net_amount']) / (1 - ($inventory_item['discount']/100)))/$inventory_item['quantity'],3); ?> </td>
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
            
            
            </tr>
            
            <?php	
				}
			}
			
			  ?>
               <tr class="total_tr" style="border-top:1px solid #000;border-bottom:1px solid #000">
            
            <td>  </td>
          
            <td> <b>TOTAL</b>  </td>
             <td>  </td> 
            <td> <?php ?>  </td> 
            <td> <b><?php  if($page==$no_of_pages-1) { ?><?php echo round($total); ?><?php } ?></b> </td>
            
            </tr>
          </table>
           
         
        
   
   <div class="sectionFive">
         
       
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
       E. & O.E. <br /><br /><br /><br /> For,  <b><?php echo $our_company['our_company_name']; ?></b>
        </div>   <!-- End of date -->
        <?php } ?>
       
       </div>
    
</div>  
</div>
<?php } ?>
<script>
window.print();
</script>
