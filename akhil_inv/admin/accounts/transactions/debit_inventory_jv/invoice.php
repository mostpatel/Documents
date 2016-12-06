<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$sales_id = $_GET['id'];
$inventory_items=getInventoryItemForSaleId($sales_id);
$non_stock_items = getNonStockItemForSaleId($sales_id);
$sales = getSaleById($sales_id);
	$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);
if(is_numeric($sales['to_customer_id']))
{
$customer=getCustomerDetailsByCustomerId($sales['to_customer_id']);
$area = getAreaNameByID($customer['area_id']);
}
}
else
exit;
 ?>
<link rel="stylesheet" href="<?php echo WEB_ROOT ?>css/a5.css" />
<div class="mainInvoiceContainer">

  <div class="sectionOne">
        
          <div class="leftSectionOne">
             <img src="<?php echo WEB_ROOT."images/logo.png" ?>" style="min-width:100px;min-height:48px" />   
            
          </div>   <!-- End of LeftSectionOne -->
        <div class="rightSectionOne">  ||  શ્રી કૃષ્ણ  ||  </div>
          <div class="rightSectionOne">
          	<div class="companyName"><u>Retail Invoice</u></div>

            <div class="companyName"> Vaibhav Auto Parts & Service Station</div>
            
            <div class="address">
     Shop No.: 1/2, Raghuvir chambers, 
    Opp. S.T. Bus Stop, Naroda Gam, 
    Ahmedabad-30.<br> Ph : 22810533, Fax : 22815708, Mob : 9825043973
            </div>   <!-- End of address -->
            
            
            
          </div>   <!-- End of rightSectionOne -->
          
          <div class="clearFix"></div>
          
        </div> <!-- End of SectionOne -->
        
        <div class="sectionTwo">
        
        <div class="chalanNo">
         Name : <?php if(is_numeric($sales['to_ledger_id']))  echo getLedgerNameFromLedgerId($sales['to_ledger_id']); else
		{
			
				 echo $customer['customer_name'];} ?>
      
        </div>   <!-- End of chalanNo -->
        
        <div class="date">
          Invoice No : <?php echo $sales['invoice_no']; ?>
       
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionTwo -->
  
  
        <div class="sectionThree">
        
        <div class="cusName">
         Area : <?php  echo $area; ?>
        </div>   <!-- End of cusName -->
        
        
        <div class="vNo">
        Date : <?php echo date('d/m/Y',strtotime($sales['trans_date'])); ?>
        
        </div>   <!-- End of vNo -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionThree -->
        
           <div class="sectionThree">
        
         <div class="cusName">
      
        </div>   <!-- End of cusName -->
        
        
        <div class="vNo">
      <!-- Vehicle No : <?php echo $vehicle['vehicle_reg_no']; ?> -->
        </div>   <!-- End of vNo -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionThree -->
        
        
        
        <div class="sectionFour">
        
          <table border="1" class="vaibhavTable">
            <tr>
            
            <td> No. </td>
            <td> Item Code </td>
            <td> Component Name </td>
            <td> Rate </td>
            <td> Qty. </td>
       <!--     <td> Discount (%) </td> -->
            <td> Tax</td> 
            <td> Net Amount</td>
            
            </tr>
            
           
            
            <?php
			$total = 0;
			$tax = 0; 
			for($i=1; $i<=count($inventory_items); $i++)
			{
			$inventory_item = $inventory_items[$i-1]['sales_item_details'];	
			$tax_details = $inventory_items[$i-1]['tax_details'];
			
			?>
            <tr>
            <td> <?php echo $i; ?> </td>
             <td> <?php echo getItemCodeFromItemId($inventory_item['item_id']); ?> </td>
            <td> <?php echo getItemNameFromItemId($inventory_item['item_id']); ?> </td>
            <td> <?php echo $inventory_item['rate']; ?> </td>
            <td>  <?php echo $inventory_item['quantity']; ?> </td>
          <!--  <td> <?php echo $inventory_item['discount']; ?> </td> -->
            <td> <?php if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['tax_amount']; else echo 0; ?> </td> 
            <td> <?php  if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['net_amount']+$inventory_item['tax_amount']; else echo $inventory_item['net_amount']; ?> </td>
            </tr>
            
            <?php
		if(is_numeric($inventory_item['tax_amount']))
		{
			$tax = $tax + $inventory_item['tax_amount'];
			$total = $total + $inventory_item['net_amount']+$inventory_item['tax_amount'];
		}
		else
		{
			$total = $total + $inventory_item['net_amount'];
			}	
			}
			?>
             <?php 
			for($j=1; $j<=count($non_stock_items); $j++)
			{
	
			$inventory_item = $non_stock_items[$j-1]['sales_item_details'];	
			$tax_details = $non_stock_items[$j-1]['tax_details'];
			
			?>
            <tr>
            <td> <?php echo $i++; ?> </td>
            <td> <?php echo getMFgCodeFromItemId($inventory_item['item_id']); ?> </td>
            <td> <?php echo getItemNameFromItemId($inventory_item['item_id']); ?> </td>
            <td> <?php echo $inventory_item['amount']; ?> </td>
            <td>  <?php echo 1; ?></td>
        <!--    <td> <?php echo $inventory_item['discount']; ?> </td> -->
            <td> <?php if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['tax_amount']; else echo 0; ?> </td> 
            <td> <?php  if(is_numeric($inventory_item['tax_amount'])) echo $inventory_item['net_amount']+$inventory_item['tax_amount']; else echo $inventory_item['net_amount']; ?> </td>
            </tr>
            
            <?php
			if(is_numeric($inventory_item['tax_amount']))
		{
			$tax = $tax + $inventory_item['tax_amount'];
			$total = $total + $inventory_item['net_amount']+$inventory_item['tax_amount'];
		}
		else
		{
			$total = $total + $inventory_item['net_amount'];
			}	
			}
			?>
               <tr>
            
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td></td>
          <!--  <td></td> -->
            <td> <?php echo $tax; ?>  </td> 
            <td> <?php echo $total; ?> </td>
            
            </tr>
          </table>
           
        </div> <!-- End of sectionFour -->
        
        
        <div class="sectionFive">
        
        <div class="customerSign">
        
        </div>   <!-- End of chalanNo -->
        
        <div class="total">
        Total : Rs. <?php echo number_format($total); ?>
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
         <div class="sectionFive">
         
        <div class="customerSign" style="width:100%">
        
       Amount in words :  <?php echo numberToWord($total)." Only"; ?>
        </div>
  
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
        <div class="sectionFive">
         <div class="customerSign" style="font-size:13px;">
       VAT TIN : <?php echo $our_company['tin_no']; ?> &nbsp; &nbsp; &nbsp; Dt: <?php echo date('d/m/Y',strtotime($our_company['tin_date']));?>
       </div>
       <div class="total" style="font-size:12px;">
        For, Vaibhav Auto Parts And Service Station
        </div>   <!-- End of date -->
        
       
       </div>
    
</div>  <!-- End of mainInvoiceContainer -->

</body>
</html>