<div class="adminContentWrapper wrapper">
<?php
if(isset($_SESSION['edmsAdminSession']['report_rights']))
{
	$report_rights=$_SESSION['edmsAdminSession']['report_rights'];
	}
?>
<?php	
 if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(101,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">LR Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="LR_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        LR Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="LR_tax_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        LR TAX Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="lr_paid_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Cash Received Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="LR_reports/deleted">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
       Deleted LR Reports
         </div>
     
     </div>
     
     </div>
</div>
<?php } 
	
 if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(101,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">Trip Memo Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="trip_reports/incoming">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
       Incoming Trip Memo Reports
         </div>
     
     </div>

    
     <div class="package">
     
         <a href="trip_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Trip Memo Reports
         </div>
     
     </div>

	 <div class="package">
     
         <a href="trip_reports/commision">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
       Commision Reports
         </div>
     
     </div>
     

     
     </div>
</div>
<?php } 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(102,$report_rights) || in_array(199,$report_rights)))
			{
?>
<h4 class="headingAlignment">Branch Outstanding Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="branch_outstanding_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Branch Outstanding Reports
         </div>
     
     </div>
     </div>
     
</div>
<?php } ?>


</div> 