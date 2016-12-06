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
$non_stock_items = getNonStockItemForSaleId($sale['sales_id']);
$inventory_items = getInventoryItemForSaleId($sale['sales_id']);
$invoice_no = getFinalizeDetailsForJobCard($job_card_id);
$area = getAreaNameByID($customer['area_id']);
$our_company = getOurCompanyByID($_SESSION['edmsAdminSession']['oc_id']);
}
else
exit;
 ?>
<link rel="stylesheet" href="../../../../css/a5.css" />
<div class="mainInvoiceContainer">

  <div class="sectionOne">
        
          <div class="leftSectionOne">
             <img src="<?php echo WEB_ROOT."images/logo.png" ?>" style="min-width:100px;min-height:48px" />   
            
          </div>   <!-- End of LeftSectionOne -->
        
          <div class="rightSectionOne">
          	<div class="address">  || શ્રી કૃષ્ણ || </div><div class="companyName"><u>Retail Invoice</u></div>

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
        Invoice No : <?php echo $invoice_no ?>
        </div>   <!-- End of chalanNo -->
        
        <div class="date">
        Date : <?php echo date('d/m/Y',strtotime($job_card_detials['job_card_datetime'])); ?>
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionTwo -->
  
  
        <div class="sectionThree">
        
        <div class="cusName">
        Name : <?php  echo $customer['customer_name']; ?>
        </div>   <!-- End of cusName -->
        
        
        <div class="vNo">
        Vehicle No : <?php echo $vehicle['vehicle_reg_no']; ?>
        </div>   <!-- End of vNo -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionThree -->
        
           <div class="sectionThree">
        
         <div class="cusName">
        Area : <?php  echo $area; ?>
        </div>   <!-- End of cusName -->
        
        
        <div class="vNo">
      
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
            <td> <?php  if(is_numeric($inventory_item['tax_amount'])) echo round($inventory_item['net_amount']+$inventory_item['tax_amount']); else echo round($inventory_item['net_amount']); ?> </td>
            </tr>
            
            <?php
			if(is_numeric($inventory_item['tax_amount']))
		{
			$tax = $tax + $inventory_item['tax_amount'];
			$total = $total + round($inventory_item['net_amount'])+round($inventory_item['tax_amount']);
		}
		else
		{
			$total = $total + round($inventory_item['net_amount']);
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
            <td> <?php ?>  </td> 
            <td> <?php echo round($total); ?> </td>
            
            </tr>
          </table>
           
        </div> <!-- End of sectionFour -->
        
        
        <div class="sectionFive">
        
        <div class="customerSign">
        Sign :
        </div>   <!-- End of chalanNo -->
        
        <div class="total">
        Total : Rs. <?php echo round($total); ?>
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
   <div class="sectionFive">
         
        <div class="customerSign" style="width:100%">
        
       Amount in words :  <?php echo numberToWord(round($total))." Only"; ?>
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