<div class="adminContentWrapper wrapper">
<?php
if(isset($_SESSION['edmsAdminSession']['report_rights']))
{
	$report_rights=$_SESSION['edmsAdminSession']['report_rights'];
	}
?>
<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">Purchase Order Reports</h4>

<div class="settingsSection">

<div class="rowOne">
  <div class="package">
     
         <a href="purchase_order_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Purchase Order Reports
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