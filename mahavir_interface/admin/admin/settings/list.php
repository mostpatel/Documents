<div class="adminContentWrapper wrapper">

<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if((in_array(6, $admin_rights) || in_array(13, $admin_rights)|| in_array(7,$admin_rights)))
{
?>

<!--<h4 class="headingAlignment">Users & Team Settings</h4>

<div class="settingsSection">

<div class="rowOne">


<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if((in_array(6, $admin_rights) ||  in_array(7,$admin_rights)))
{
?>

    <div class="package">
     
     <a href="general_settings/adminuser_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         U
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage System Users
     </div>
     
     </div>
     
<?php
}
?>  

<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if((in_array(13, $admin_rights) ||  in_array(7, $admin_rights)))
{
?>
     
     <div class="package">
     
     <a href="general_settings/team_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         T
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage User Teams
     </div>
     
     </div>
     
     
     
     <div class="package">
     
     <a href="general_settings/team_leader_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         TL
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Team Leaders
     </div>
     
     </div>
   
  <?php
}
  ?>  
     
  </div>
     
</div>
-->

<?php
}
?>




<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if((in_array(10, $admin_rights) ||  in_array(7, $admin_rights)))
{
?>

<!--<h4 class="headingAlignment">General Settings</h4>

<div class="settingsSection">

<div class="doubleRowOne">

     <div class="package">
     
     <a href="general_settings/ourcompany_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         C
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Our Companies
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="general_settings/decline_reasons_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         DR
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Decline Reasons
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="general_settings/customer_type_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         CT
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Customer Types
     </div>
     
     </div>
     
     
     <div class="package">
     
     <a href="general_settings/follow_up_type_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         FT
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Follow Up Types
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="general_settings/profession_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         Pr
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Professions
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="general_settings/data_from_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         DF
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Data From
     </div>
     
     </div>
     
     
     <div class="package">
     
     <a href="general_settings/relations_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         R
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Relations
     </div>
     
     </div>
     
     
      <div class="package">
     
     <a href="general_settings/prefix_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         Pr
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Customer Prefix 
     </div>
     
     </div>
     
     
     
     
     <div class="package">
     
     <a href="general_settings/supplier_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         Su
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Suppliers
     </div>
     </div>
     
     
     
     <div class="package">
     
     <a href="general_settings/supplier_mapping_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         Su
         </div>
         
     </div>
     </a>
     <div class="explanation">
     Map Suppliers-Sectors
     </div>
     </div>
     
     
     
     <div class="package">
     
     <a href="general_settings/smsCredits_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         S
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Check SMS Credits
     </div>
     
     </div>
     
     
     
   <div class="package">
     
     <a href="general_settings/city_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
        C
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage City Settings
     </div>
     
     </div>
     
     
  </div>
     
</div> -->


<?php
}
?>

<h4 class="headingAlignment">Product Settings</h4>

<div class="settingsSection">

<div class="rowOne">

     
     <!--<div class="package">
     
     <a href="product_settings/super_category_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         Su
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Super Category
     </div>
     
     </div>-->
     
     <div class="package">
     
     <a href="product_settings/category_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         C
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Category
     </div>
     
     </div>
     
     
     <div class="package">
     
     <a href="product_settings/sub_category_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         Sc
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Sub Category
     </div>
     
     </div>
     
    <!-- <div class="package">
     
     <a href="product_settings/attribute_type_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         AT
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Attribute Type
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="product_settings/attriibute_name_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         AN
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Attribute Name
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="product_settings/quantity_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         Q
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Quantity
     </div> 
     
     </div> -->
     
  
  </div>
     
</div>


<h4 class="headingAlignment">Client Settings</h4>

<div class="settingsSection">

<div class="rowOne">

     
     <!--<div class="package">
     
     <a href="product_settings/super_category_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         Su
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Super Category
     </div>
     
     </div>-->
     
     <div class="package">
     
     <a href="event_settings/events_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         C
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Clients
     </div>
     
     </div>
     
     
     
     
    <!-- <div class="package">
     
     <a href="product_settings/attribute_type_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         AT
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Attribute Type
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="product_settings/attriibute_name_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         AN
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Attribute Name
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="product_settings/quantity_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         Q
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Quantity
     </div> 
     
     </div> -->
     
  
  </div>
     
</div>
<!--
<h4 class="headingAlignment">TAX Settings</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="tax_settings/tax_type_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage TAX Types
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="tax_settings/tax_group_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage TAX Groups
     </div>
     
     </div>
     
</div>
     
</div>


<!--<h4 class="headingAlignment"> Insurance Settings </h4>

<div class="settingsSection">

<div class="rowOne">

    
     
     
     <div class="package">
     
     <a href="vehicle_insurance_settings/insurance_company_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Insurance Companies
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="vehicle_insurance_settings/insurance_period_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Insurance Period
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="vehicle_insurance_settings/insurance_percentage_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Insurance Percentage
     </div>
     
     </div>
     
      

 </div>
     
</div>


<h4 class="headingAlignment">Vehicle Settings</h4>

<div class="settingsSection">

<div class="rowOne">

    <div class="package">
     
     <a href="vehicle_insurance_settings/vehicle_company_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Vehicle Companies
     </div>
     
     </div>
     
     
     
     
      <div class="package">
     
     <a href="vehicle_insurance_settings/vehicle_model_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Vehicle Models
     </div>
     
     </div>

     <div class="package">
     
     <a href="vehicle_insurance_settings/vehicle_cc_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Vehicle CC
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="vehicle_insurance_settings/vehicle_type_settings">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Vehicle Types
     </div>
     
     </div>
          
</div>
     
</div>

-->

<!-- <h4 class="headingAlignment">Group Settings</h4>

<div class="settingsSection">

<div class="rowOne">

     
     <div class="package">
     
     <a href="group_settings/customer_group_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         cG
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Customer Groups
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="group_settings/enquiry_group_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         eG
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Enquiry Groups
     </div>
     
     </div>
     
     
     
     
  
  </div>
     
</div> -->


<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if((in_array(14, $admin_rights) ||  in_array(7, $admin_rights)))
{
?>


<!-- <h4 class="headingAlignment">Backup & Restore</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="backup_restore/backup/">
     <div class="squareBox">
         <div class="imageHolder">
         B
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Backup
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="backup_restore/restore/">
     <div class="squareBox">
         <div class="imageHolder">
         R
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Restore
     </div>
     
     </div>
     
     
      
  </div>
     
</div> -->

<?php
}
?>

<!--<h4 class="headingAlignment">General Settings</h4>
    <ul class="secondaryList">
    	<a href="general_settings/ourcompany_settings/"><li>Manage Our Companies</li>
        <a href="general_settings/city_settings/"><li>Manage Cities</li>
        <a href="general_settings/adminuser_settings/"><li>Manage Admin Users</li>
        <a href="general_settings/bank_settings/"><li>Manage Bank Settings</li>
        <a href="general_settings/agency_settings/"><li>Manage Agency Settings</li>
    </ul>
    
    <h4 class="headingAlignment">Vehicle Settings</h4>
    
    <ul class="secondaryList">
    	<a href="vehicle_settings/company_settings/"><li>Manage Vehicle companies</li>
        <a href="vehicle_settings/type_settings/"><li>Manage Vehicle Types</li>
        <a href="vehicle_settings/dealer_settings/"><li>Manage Vehicle Dealers</li>
        
    </ul>-->
</div> 