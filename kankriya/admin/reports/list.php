<div class="adminContentWrapper wrapper">
<?php
if(isset($_SESSION['adminSession']['report_rights']))
{
	$report_rights=$_SESSION['adminSession']['report_rights'];
	}
 if(isset($_SESSION['adminSession']['report_rights']) && (in_array(101,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">EMI Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="emi_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily EMI Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="emi_reports/weekly">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Next 15 Days EMI Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="emi_reports/expired">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Expired EMI Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="emi_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Emi Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="emi_reports/custom_payment_date">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Emi Reports By Payment Date
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="emi_reports/custom_payment_date_kankariya">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Emi Reports Between Payment Date
         </div>
     
     </div>
     
    
   
         
    
</div>
<div class="rowOne">
  <div class="package">
     
         <a href="emi_reports/custom_loan_approval_date">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Emi Reports By Approval Date
         </div>
     
     </div>
     
 <div class="package">
         
             <a href="emi_reports/loan_starter">
             <div class="squareBox">
             
                 <div class="imageHolder">
                 </div>
                 
             </div>
             </a>
         
         
             <div class="explanation">
             Non Starter Reports
             </div>
     
    	 </div>
</div>
</div> 
<?php } ?>

<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(111,$report_rights) || in_array(199,$report_rights)) && KANKRIYA_REPORTS==1)
			{ ?>    

<h4 class="headingAlignment">Kankriya Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="kankriya_reports/first_report">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         TVR Report (Step 1)
         </div>
     
     </div>
      <div class="package">
     
         <a href="kankriya_reports/second_report">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Simple Office Notice Reports (Step 2)
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="kankriya_reports/second_report_due">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Last Office Notice Reports (Step 3)
         </div>
     
     </div>
      <div class="package">
     
         <a href="kankriya_reports/third_report">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Collection Report (Step 4)
         </div>
     
     </div>
     <div class="package">
     
         <a href="kankriya_reports/fourth_report">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Advocate Notice Report (Step 5)
         </div>
     
     </div>
      <div class="package">
     
         <a href="kankriya_reports/fifth_report">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Cheque Return Reports (Step 6)
         </div>
     
     </div>
</div>
<div class="rowOne">     
      <div class="package">
     
         <a href="kankriya_reports/sixth_report">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
       Legal Case Report (Step 7)
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="kankriya_reports/custom_loan_approval_date">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
      Custom Loan Approval_date
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="kankriya_reports/custom_loan_percent">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
      Custom Loan Percent Report
         </div>
     
     </div>
     
     
</div>
</div>     

<?php } ?>

<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(102,$report_rights) || in_array(199,$report_rights)))
			{ ?>
            
<h4 class="headingAlignment">Cheque Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="cheque_reports/return">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Cheque Return Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="cheque_reports/return_undeleted">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Cheque Return Undeleted Reports
         </div>
     
     </div> 
     
       <div class="package">
     
         <a href="cheque_reports/cheque_received_reports">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Customer Cheque  Reports
         </div>
     
     </div> 
</div>
</div>     
<?php } ?> 


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(103,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">Insurance Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="insurance_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Insurance Reports
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
         Next 15 Days Insurance Reports
         </div>
     
     </div> -->
     
     <div class="package">
     
         <a href="insurance_reports/expired">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Expired Insurance Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="insurance_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Insurance Reports
         </div>
     
     </div>
     
</div>
</div>  

<?php } ?>


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(104,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">Rasid Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="rasidReports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Rasid Reports
         </div>
     
     </div>
     
      
     
   
     
     <div class="package">
     
         <a href="rasidReports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Rasid Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="rasidReports/custom_entry">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Rasid Reports By Entry Date
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="rasidReports/stfc">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         STFC Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="rasidReports/export_rasid">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Export Rasid Reports
         </div>
     
     </div>
     
     
</div>
</div> 

<?php  } ?>

<h4 class="headingAlignment">Vehicle Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="vehicle_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Vehicle Document Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="vehicle_reports/reg_no_reports">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Vehicle Added Reports
         </div>
     
     </div>
</div>
</div>     
      
     
<h4 class="headingAlignment">Notice Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="notice_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Notice Reports
         </div>
     
     </div>
     
     <div class="package">
     
         <a href="notice_reports/custom_reg_ad">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Notice Reports Reg Ad
         </div>
     
     </div>

</div>
</div>


<h4 class="headingAlignment">Welcome Letter Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="welcome_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Welcome Letter Reports
         </div>
     
     </div>

	 <div class="package">
     
         <a href="welcome_reports/non_issued_custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         NON Issued Welcome Letter Reports
         </div>
     
     </div>


</div>
</div>


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(105,$report_rights) || in_array(199,$report_rights)))
			{ ?>

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
     
       <div class="package">
     
         <a href="remainder_reports/payment">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Payment Reminder Reports
         </div>
     
     </div>
</div>
</div>  



<?php } ?>   




<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(106,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">File Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="file_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily File Reports
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
     
         <a href="file_reports/closed">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Closed File Reports
         </div>
     
     </div> 
     
       <div class="package">
     
         <a href="file_reports/closed_non_noc">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Closed File NON NOC Reports
         </div>
     
     </div> 
     
     <div class="package">
     
         <a href="file_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom File Reports
         </div>
     
     </div>
     <?php if(FILE_CHARGES==1) { ?>
      <div class="package">
     
         <a href="file_reports/file_charges_reports">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
          File Charges Reports
         </div>
     
     </div>
     <?php } ?>
     
</div>
</div>


<?php } ?>


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(107,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">Loan Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="loan_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Loan Reports
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
     
     </div> 
     
     <div class="package">
     
         <a href="remainder_reports/expired">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Expired Remainder Reports
         </div>
     
     </div> -->
     
     <div class="package">
     
         <a href="loan_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Loan Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="loan_reports/summary">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Loan Summary Reports Brokerwise
         </div>
     
     </div>

</div> 
</div>  



<?php  } ?>


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(108,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">Account Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="account_reports/daily">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Daily Account Reports
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
     
         <a href="account_reports/closed">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Closed File Account Reports
         </div>
     
     </div> 
     
     <div class="package">
     
         <a href="account_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Custom Account Reports
         </div>
     
     </div>

</div> 
</div>  


<?php } ?>


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(109,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">Collection Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="collection_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Collection Reports
         </div>
     
     </div>
</div>
</div>  


<?php  } ?>  

<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(115,$report_rights) || in_array(199,$report_rights)))
			{ ?>    

<h4 class="headingAlignment">Vehicle Seize Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="vehicle_seize_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Vehicle Seize Reports
         </div>
     
     </div>
</div>
</div>     

<?php } ?>


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(113,$report_rights) || in_array(199,$report_rights)))
			{ ?>
<h4 class="headingAlignment">Capital And Interest Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="capital_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Capital And Interest Reports
         </div>
     
     </div>


	 <div class="package">
     
         <a href="interest_gained_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Interest Gained Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="interest_gained_reports/mercantile">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Interest Gained Reports Mercantle
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="interest_gained_reports/cash">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Interest Gained Reports Cash
         </div>
     
     </div>
</div>     

</div>  


<?php  } ?>  


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(114,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">Loan Starting & Ending Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="loan_starting_ending/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Loan Starting & Ending Reports
         </div>
     
     </div>
</div>
</div>  


<?php  } ?>  


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(110,$report_rights) || in_array(199,$report_rights)))
			{ ?>

<h4 class="headingAlignment">Company Paid Date Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="company_Paid_Reports/custom_kankriya">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Company Paid Date Reports
         </div>
     
     </div>
</div>
</div>  


<?php } ?>


<?php if(isset($_SESSION['adminSession']['report_rights']) && (in_array(111,$report_rights) || in_array(199,$report_rights)))
			{ ?>    

<h4 class="headingAlignment">Full Custom Reports</h4>

<div class="settingsSection">

<div class="rowOne">

	 <div class="package">
     
         <a href="custom_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
         Full Custom Reports
         </div>
     
     </div>
      <div class="package">
     
         <a href="custom_reports/nagnath">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Nagnath Due Reports
         </div>
     
     </div>
     
      <div class="package">
     
         <a href="kamalbhai_reports/custom">
         <div class="squareBox">
         
             <div class="imageHolder">
             </div>
             
         </div>
         </a>
     
     
         <div class="explanation">
        Kamal Bhai Reports
         </div>
     
     </div>
</div>
</div>     

<?php } ?>

</div> 