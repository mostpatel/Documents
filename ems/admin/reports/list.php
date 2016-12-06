

<div class="adminContentWrapper wrapper">

<h4 class="headingAlignment">  Customer Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="customer_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             C
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Customer Reports
         </div>
     
     </div>
     
      
     <div class="package">
     
         <a href="customer_reports/label_reports">
         <div class="squareBox">
         
             <div class="imageHolder">
             Lp
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Customer Label Printing Reports
         </div>
     
     </div>
     
     
<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if((in_array(11, $admin_rights) || in_array(7,$admin_rights)))
{
?>
     
     <div class="package">
     
         <a href="customer_reports/sms_reports">
         <div class="squareBox">
         
             <div class="imageHolder">
             S
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Send SMS To Customers
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="customer_reports/email_reports">
         <div class="squareBox">
         
             <div class="imageHolder">
             eM
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Send Email To Customers
         </div>
     
     </div>
     
  <?php
}
  ?>   
     
   
     
     
     
</div>
</div> 



<!--<h4 class="headingAlignment">Decline Reasons Report</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="account_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             D
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Decline reasons Report 
         </div>
     
     </div>
     

     
     
     

</div> 
</div>  -->


<h4 class="headingAlignment">Follow Up Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="follow_up_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             dF
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Follow Ups
         </div>
     
     </div>
     

     
     <div class="package">
     
         <a href="follow_up_reports/expired">
         <div class="squareBox">
         
             <div class="imageHolder">
             eF
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Expired Follow Ups
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="follow_up_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             cF
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Follow Ups
         </div>
     
     </div>
     
</div>
</div>  



<h4 class="headingAlignment">Done Follow Up Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="done_follow_up_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             dF
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Done Follow Ups
         </div>
     
     </div>
     

     
  
     
     <div class="package">
     
         <a href="done_follow_up_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             cF
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Done Follow Ups
         </div>
     
     </div>
     
</div>
</div>  

<h4 class="headingAlignment">Reminder Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="reminder_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             dR
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Reminders Reports
         </div>
     
     </div>
     

     
     <div class="package">
     
         <a href="reminder_reports/expired">
         <div class="squareBox">
         
             <div class="imageHolder">
             eR
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Expired Reminders
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="reminder_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             cR
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Reminders
         </div>
     
     </div>
     
</div>
</div>  




<!--<h4 class="headingAlignment">Invoice Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="invoice_reports/daily">
         <div class="squareBox">
                                                                                                                                                      
             <div class="imageHolder">
             I
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Invoice Reports
         </div>
     
     </div>
     
     
      <div class="package">
     
         <a href="invoice_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             I
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Invoice Reports
         </div>
     
     </div>
     
</div>
</div> 
-->
<!--<h4 class="headingAlignment"> Insurance Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="insurance_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             I
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Expiry Insurance Reports
         </div>
     
     </div>
     
      
      <div class="package">
     
         <a href="insurance_reports/new">
         <div class="squareBox">
         
             <div class="imageHolder">
             I
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         New Insurance Reports
         </div>
     
     </div>
     

</div>
</div>     
-->

  


<h4 class="headingAlignment">Enquiry Reports</h4>

<div class="settingsSection">

<div class="rowOne">

       <div class="package">
     
         <a href="lead_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             dE
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Enquiry Reports
         </div>
     
     </div>

	 <div class="package">
     
         <a href="lead_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             cE
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Enquiry Reports
         </div>
     
     </div>
     
</div>
</div> 

<?php
if(SHOW_TOUR_REPORTS == 1)
{
?>
<h4 class="headingAlignment">Tour Reports</h4>

<div class="settingsSection">

<div class="rowOne">

       

	 <div class="package">
     
         <a href="purchase_date_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             tD
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Tour Departure Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="tour_ending_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             tE
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Tour Ending Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="purchase_date_reports/feedback">
         <div class="squareBox">
         
             <div class="imageHolder">
             tF
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Tour Feedback SMS 
         </div>
     
     </div>
     
</div>
</div>
 
<?php
}
?>


<h4 class="headingAlignment">Director Reports</h4>

<div class="settingsSection">

<div class="rowOne">

       

	
     
     <div class="package">
     
         <a href="meeting_reports/feedback">
         <div class="squareBox">
         
             <div class="imageHolder">
             dF
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Director Feedback SMS 
         </div>
     
     </div>
     
</div>
</div>
 


<h4 class="headingAlignment">Graph Reports</h4>

<div class="settingsSection">

<div class="rowOne">

       <div class="package">
     
         <a href="bar">
         <div class="squareBox">
         
             <div class="imageHolder">
             G
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
          Enquiry Graph Reports
         </div>
     
     </div>

	
     
</div>
</div> 



<h4 class="headingAlignment">Efficiency Reports</h4>

<div class="settingsSection">

<div class="rowOne">

       

	 <div class="package">
     
         <a href="lead_efficiency_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             %
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Lead Efficiency Reports
         </div>
     
     </div>
     
     
     
     <div class="package">
     
         <a href="lead_efficiency_reports/decline_reasons">
         <div class="squareBox">
         
             <div class="imageHolder">
             dL
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Decline Reason Reports
         </div>
     
     </div>
     
     
     <!--<div class="package">
     
         <a href="lead_efficiency_reports/dalabhai_invoice">
         <div class="squareBox">
         
             <div class="imageHolder">
             D1
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Dalabhai Invoice
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="lead_efficiency_reports/dalabhai_trip_memo">
         <div class="squareBox">
         
             <div class="imageHolder">
             D2
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Dalabhai Trip Memo
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="lead_efficiency_reports/dalabhai_lr">
         <div class="squareBox">
         
             <div class="imageHolder">
             D3
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Dalabhai LR
         </div>
     
     </div>-->
     
    
</div>
</div> 



<h4 class="headingAlignment">Snapshot Reports</h4>

<div class="settingsSection">

<div class="rowOne">

       

	 <div class="package">
     
         <a href="snapshot_reports/user_wise">
         <div class="squareBox">
         
             <div class="imageHolder">
             uW
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         User Snapshot Report
         </div>
     
     </div>
     
     
     <div class="package">
     
         <a href="snapshot_reports/enquiry_source">
         <div class="squareBox">
         
             <div class="imageHolder">
             eS
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Enquiry Source Report
         </div>
     
     </div>
     
     
</div>
</div> 








</div> 