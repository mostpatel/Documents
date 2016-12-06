<div class="adminContentWrapper wrapper">

<h4 class="headingAlignment">General Settings</h4>

<div class="settingsSection">

<div class="rowOne">
 <?php if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (SLAVE==0 && in_array(11,$admin_rights)))
			{ ?>
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
    
     
      <?php if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (SLAVE==0 && in_array(11,$admin_rights)))
			{ ?>
    <div class="package">
     
     <a href="../accounts/ledgers/agents">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Agents 
     </div>
     
     </div>
     
       
       <div class="package">
     
     <a href="../accounts/ledgers/branches">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Branches
     </div>
     
     </div>
     
     
       <div class="package">
             
             <a href="general_settings/product_settings/">
             <div class="squareBox">
                 <div class="imageHolder">
                 </div>
             </div>
             </a>
             
             <div class="explanation">
             Manage Products
             </div>
             
             </div>
      <?php } ?>       
             
               <div class="package">
             
             <a href="general_settings/packing_unit_settings/">
             <div class="squareBox">
                 <div class="imageHolder">
                 </div>
             </div>
             </a>
             
             <div class="explanation">
             Manage Packing Units
             </div>
             
             </div>

 </div>
  <?php if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (SLAVE==0 && in_array(11,$admin_rights)))
			{ ?>
 <div class="rowOne"> 
 
 			  <div class="package">
     
     <a href="../accounts/ledgers/truck_owners">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Truck Owners
     </div>
     
     </div>
     
       <div class="package">
     
     <a href="../accounts/ledgers/truck_drivers">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Truck Drivers
     </div>
     
     </div>   
    
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
             
              <div class="package">
             
             <a href="general_settings/truck_settings/">
             <div class="squareBox">
                 <div class="imageHolder">
                 </div>
             </div>
             </a>
             
             <div class="explanation">
             Manage Trucks
             </div>
             
             </div>
</div> 
<?php  }?>    
     
</div>
 <?php if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (SLAVE==0 && in_array(11,$admin_rights)))
			{ ?>
<h4 class="headingAlignment">Inventory Settings</h4>

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
     Manage Item Types
     </div>
     
     
     
     </div>
     
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
    
     <div class="package">
     
     <a href="inventory_settings/nonStock_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Non Stock Items / Labour
     </div>
     
    </div>
     
</div>
</div>   


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
     
 <?php if(isset($_SESSION['edmsAdminSession']['admin_rights']) && (SLAVE==0 && in_array(11,$admin_rights)))
			{ ?>     
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
<?php } ?>     
     
      
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