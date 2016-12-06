<div class="adminContentWrapper wrapper">

<h4 class="headingAlignment">General Settings</h4>

<div class="settingsSection">

<div class="rowOne">

    
      
     
      <div class="package">
     
     <a href="general_settings/package_type_settings/">
     <div class="squareBox">
     
        <div class="imageHolder">
         </div>
         
     </div>
     </a>
     
     <div class="explanation">
     Manage Package Type Settings
     </div>
     
     
     
     </div>
    
     <?php if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(6,$admin_rights) || in_array(7,					$admin_rights)))
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
    
     
  </div>
     
</div>


</div> 