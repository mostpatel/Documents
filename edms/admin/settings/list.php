<div class="adminContentWrapper wrapper">

<h4 class="headingAlignment">General Settings</h4>

<div class="settingsSection">

<div class="rowOne">
<?php if(INVENTORY_MODE!=1) {   ?>   
     <div class="package">
     
     <a href="general_settings/ourcompany_settings/">
     <div class="squareBox">
     
         <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     
     <div class="explanation">
     Manage Our Companies
     </div>
     
     </div>
    <?php } ?> 
    
     <?php if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (in_array(6,$admin_rights) || in_array(7,					$admin_rights)))
			{ ?>
     <div class="package">
     
     <a href="general_settings/adminuser_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Admin Users
     </div>
     
     </div>
     <?php } ?>
    
     <?php if(EDMS_MODE==1 && INVENTORY_MODE!=1) { ?>
     
    <div class="package">
     
     <a href="../accounts/ledgers/financer">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Financers 
     </div>
     
     </div>
     
    
     
       <div class="package">
     
     <a href="../accounts/ledgers/dealer">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Dealers 
     </div>
     
     </div>
     
      <?php } ?>
      <?php if(INVENTORY_MODE!=1) {   ?>   

      
     
       <div class="package">
     
     <a href="../accounts/ledgers/Broker">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage <?php echo BROKER_NAME; ?>  
     </div>
     
     </div>
      <?php } ?>
     <?php if(TAX_MODE==0 && INVENTORY_MODE!=1) { ?>
     <div class="package">
     
     <a href="../accounts/ledgers/saleman">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Salesman 
     </div>
     
     </div>
     <?php } ?>
    
  </div>
     
      <?php if(INVENTORY_MODE!=1) {   ?>   
     <div class="rowOne">
      <div class="package">
     
     <a href="general_settings/group_settings/customer_group_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Customer Groups 
     </div>
     
     </div>
        <?php if(TAX_MODE==0) { ?>
              <div class="package">
             
             <a href="general_settings/receipt_type_settings/">
             <div class="squareBox">
                 <div class="imageHolder">
                 </div>
             </div>
             </a>
             
             <div class="explanation">
             Manage Receipt Types
             </div>
             
             </div>
             <?php } ?>
               <?php if(TAX_MODE==1) { ?>
              <div class="package">
             
             <a href="inventory_settings/product_desc_settings/">
             <div class="squareBox">
                 <div class="imageHolder">
                 </div>
             </div>
             </a>
             
             <div class="explanation">
             Manage Product Desc (Years)
             </div>
             
             </div>
             <?php } ?>
              <div class="package">
             
             <a href="general_settings/invoice_type_settings/">
             <div class="squareBox">
                 <div class="imageHolder">
                 </div>
             </div>
             </a>
             
             <div class="explanation">
             Manage Invoice Types
             </div>
             
             </div>
     </div>
     
     <?php } ?>
</div>

<h4 class="headingAlignment">   <?php if(TAX_MODE==0) { ?>Inventory <?php } else echo "Service"; ?> Settings</h4>

<div class="settingsSection">

<div class="rowOne">
  <div class="package">
     
     <a href="inventory_settings/type_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage    <?php if(TAX_MODE==0) { ?> Item <?php }else echo "Service"; ?> Types
     </div>
     
     
     
     </div>
        <?php if(TAX_MODE==0) { ?>
     <div class="package">
     
     <a href="inventory_settings/unit_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Item Units
     </div>
     
     
     
     </div>
     
      <div class="package">
     
     <a href="inventory_settings/item_rel_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Item Units Relation with items
     </div>
     
     
     
     </div>
     
     <div class="package">
     
     <a href="inventory_settings/manufacturer_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Item Manufacturer Settings
     </div>
     
     
     
     </div>
     
     </div>
     <div class="rowOne">
     
     <div class="package">
     
     <a href="inventory_settings/item_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Inventory Items / Spares
     </div>
     
    </div>
     <?php if(INVENTORY_MODE!=1) {   ?>   
     <div class="package">
     
     <a href="inventory_settings/merge_item_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Merge Inventory Items / Spares
     </div>
     
    </div>
    <?php } ?>
    <?php } ?>
     <?php if(INVENTORY_MODE!=1) {   ?>   
     <div class="package">
     
     <a href="inventory_settings/nonStock_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage    <?php if(TAX_MODE==0) { ?> Non Stock Items / Labour <?php } else echo "Services"; ?>
     </div>
     
    </div>
    <?php } ?>
     
</div>
</div>   
   <?php if(EDMS_MODE==1) { ?>
<h4 class="headingAlignment">job Card Settings</h4>

<div class="settingsSection">

<div class="rowOne">

<div class="package">
     
     <a href="../accounts/ledgers/outside_labour">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Outside Labour Providers
     </div>
     
     </div>

  <div class="package">
     
     <a href="jobcard_settings/jb_desc_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Customer Complaints
     </div>
     
     
     
     </div>
     
     <div class="package">
     
     <a href="jobcard_settings/jb_wd_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Job Card Work Done
     </div>
     
     
     
     </div>
     
     <div class="package">
     
     <a href="jobcard_settings/service_type_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Service Types
     </div>
     
     
     
     </div>
     
      <div class="package">
     
     <a href="jobcard_settings/technician_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Technicians
     </div>
     
     
     
     </div>
     
     <div class="package">
     
     <a href="jobcard_settings/service_check_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Service Checks and Values
     </div>
     
    </div>
     
</div>
</div>   
<?php } ?>
 <?php if(INVENTORY_MODE!=1) {   ?>   
<h4 class="headingAlignment">City & Area Settings</h4>

<div class="settingsSection">

<div class="rowOne">
  <div class="package">
     
     <a href="general_settings/city_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage City Settings
     </div>
     
     
     
     </div>
     <div class="package">
     
     <a href="general_settings/area_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Area Settings
     </div>
     
     
     
     </div>
     
     <div class="package">
     
     <a href="general_settings/merge_area_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Merge Area Settings
     </div>
     
     
     
     </div>
     <div class="package">
     
     <a href="general_settings/area_group_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Area Group Settings
     </div>
     
     
     
     </div>
     
</div>
</div>     
 <?php if(EDMS_MODE==1) { ?>
<h4 class="headingAlignment">Vehicle Settings</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="vehicle_settings/company_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Vehicle companies
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="vehicle_settings/model_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Vehicle models
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="vehicle_settings/type_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
    Manage Vehicle Types
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="vehicle_settings/godown_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Vehicle Godowns
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="vehicle_settings/color_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Vehicle Colors
     </div>
     
     </div>
     
       <div class="package">
     
     <a href="vehicle_settings/fuel_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Fuel Types
     </div>
     
     </div>
      
  </div>
  
  <div class="rowOne">
  
    <div class="package">
     
     <a href="vehicle_settings/battery_make_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Battery Makes
     </div>
     
     </div>
     
    
   
    
     
         <div class="package">
     
     <a href="../accounts/ledgers/supplier">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Suppliers 
     </div>
     
     </div>
   
  </div>   
     
</div>

<h4 class="headingAlignment">Insurance Settings</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="insurance_settings/company_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Insurance companies
     </div>
     
     </div>
     
      
  </div>
     
</div>
<?php } ?>
<h4 class="headingAlignment">Tax Settings</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="general_settings/tax_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Tax Settings
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="general_settings/tax_group_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Tax Group Settings
     </div>
     
     </div>
     
       <div class="package">
     
     <a href="general_settings/tax_class_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Tax Class Settings
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="general_settings/tax_form_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Tax Form Settings
     </div>
     
     </div>
     
      
  </div>
     
</div>
<?php } ?>
<h4 class="headingAlignment">Backup & Restore</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="backup_restore/backup/">
     <div class="squareBox">
         <div class="imageHolder">
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
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Restore
     </div>
     
     </div>
     
      
  </div>
     
</div>


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