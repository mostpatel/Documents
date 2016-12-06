<div class="adminContentWrapper wrapper">
<?php
if(isset($_SESSION['edmsAdminSession']['report_rights']))
{
	$report_rights=$_SESSION['edmsAdminSession']['report_rights'];
	}
 if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(101,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment"> <?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>Vehicle<?php } ?> Outstanding Reports</h4>

<div class="settingsSection">

<div class="rowOne">
<?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
	 <div class="package">
     
         <a href="vehicle_outstanding_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Vehicle Outstanding Reports
         </div>
     
     </div>
<?php } ?>     
     
      <div class="package">
     
         <a href="customer_outstanding_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Customer Outstanding Reports
         </div>
     
     </div>
     
     
</div>
</div> 
<?php } ?>
<?php  if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(102,$report_rights) || in_array(199,$report_rights)))
			{
?>


<h4 class="headingAlignment">Job Card Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="job_card_reports/free_service_claim">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Free Service Claim Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="job_card_reports/fsc">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        FSC Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="job_card_reports/warranty_claim">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Warranty Claim Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="job_card_reports/wrc">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        WRC Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="job_card_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Job Card Reports
         </div>
     
     </div>
     
     
       <div class="package">
     
         <a href="job_card_reports/custom_next_sd">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Job Card Reports By Service Date
         </div>
     
     </div>
     </div>
     <div class="rowOne">
       <div class="package">
     
         <a href="job_card_reports/custom_vehicle_wise">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Job Card Reports VehicleWise
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="job_card_reports/servicewise">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Job Card Reports ServiceWise
         </div>
     
     </div>
     
</div>
</div> 
<?php }
?>
<?php } ?>

<h4 class="headingAlignment">Reminder Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="remainder_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Reminder Reports
         </div>
     
     </div>
     
<!--      <div class="package">
     
         <a href="EMI_reports/weekly">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Next 15 Days EMI Reports
         </div>
     
     </div> -->
     
     <div class="package">
     
         <a href="remainder_reports/expired">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Expired Reminder Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="remainder_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Reminder Reports
         </div>
     
     </div>
</div>
</div>  


<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment"><?php echo  SALES_NAME;  ?> Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 
     <div class="package">
     
         <a href="sales_reports/all">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Custom <?php echo  SALES_NAME;  ?> Reports
         </div>
     
     </div>
     
    
     
     <div class="package">
     
         <a href="sales_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        <?php echo  SALES_NAME;  ?> Reports Partywise
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="sales_reports/sales_accountwise">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        <?php echo  SALES_NAME;  ?> Reports <?php echo  SALES_NAME;  ?> Accountwise
         </div>
     
     </div>
     <?php if(TAX_MODE==0) { ?>
      <div class="package">
     
         <a href="sales_reports/itemwise">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        <?php echo  SALES_NAME;  ?> Reports ItemWise
         </div>
     
     </div>
     <?php } ?>
      <div class="package">
     
         <a href="sales_reports/servicewise">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
          <?php echo  SALES_NAME;  ?> Reports ServiceWise
         </div>
     
     </div>
     
     
     
</div>
</div> 
<?php } ?>
<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">Delivery Challan Reports</h4>

<div class="settingsSection">

<div class="rowOne">
  <div class="package">
     
         <a href="delivery_challan_reports/delivery_challan_report">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Uninvoiced <?php echo DELIVERY_CHALLAN_NAME; ?> Reports
         </div>
     
     </div>
</div>
</div>     
<?php } ?>  
<?php if(defined('SALES_PURCHASE_INCLUDE') && SALES_PURCHASE_INCLUDE==1){ ?>   
<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">Purchase Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 
     <div class="package">
     
         <a href="purchase_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Custom Purchase Reports
         </div>
     
     </div>
     
       <div class="package">
     
         <a href="purchase_reports/itemwise">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Custom Purchase Reports Itemwise
         </div>
     
     </div>
     
    
     
     
     
</div>
</div> 
<?php } ?>

<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">Inventory Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 
     <div class="package">
     
         <a href="inventory_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Closing Stock Reports
         </div>
     
     </div>
     
     
      <div class="package">
     
         <a href="inventory_reports/stock_transaction">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Stock Transaction Reports
         </div>
     
     </div>
     
    
     
     
     
</div>
</div> 
<?php } ?>
<?php  ?>
<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">SMS Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 
     <div class="package">
     
         <a href="smsReports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        SMS Sent Reports
         </div>
     
     </div>
     
    
     
     
     
</div>
</div> 
<?php } ?>
<?php } ?>

</div> 