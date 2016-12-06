<?php if(INVENTORY_MODE!=1) {   ?>   
<a  href="<?php echo WEB_ROOT ?>admin/"><div class="link <?php if($selectedLink=="home") { ?> selected <?php } ?>">
            Home
           </div></a>
      
           <a  href="<?php echo WEB_ROOT ?>admin/customer"><div class="link <?php if($selectedLink=="newCustomer") { ?> selected <?php } ?>">
            Add new customer
           </div></a>
           
           <a  href="<?php echo WEB_ROOT ?>admin/search"><div class="link <?php if($selectedLink=="searchCustomer") { ?> selected <?php } ?>">
            Find customer
           </div></a>
		   <?php } ?>
           <?php if(defined('CASH_SALE') && CASH_SALE==1 && INVENTORY_MODE!=1) { ?>
             <a  href="<?php echo WEB_ROOT ?>admin/accounts/transactions/cash_sale"><div class="link <?php if($selectedLink=="cash_sale") { ?> selected <?php } ?>">
           Cash Sale
           </div></a> 
           <?php } ?>
           <?php if(defined('EDMS_MODE') && EDMS_MODE==1 && INVENTORY_MODE!=1) { ?>

           <a  href="<?php echo WEB_ROOT ?>admin/purchase/vehicle/"><div class="link <?php if($selectedLink=="purchaseVehicle") { ?> selected <?php } ?>">
          Purchase Vehicles
           </div></a>
           <?php } ?>
         <!--   <a  href="<?php echo WEB_ROOT ?>admin/customer/vehicle/insurance/index.php?view=search"><div class="link <?php if($selectedLink=="searchInsurance") { ?> selected <?php } ?>">
           Find Insurance details
           </div></a> -->
           
            <a  href="<?php echo WEB_ROOT ?>admin/reports/"><div class="link <?php if($selectedLink=="reports") { ?> selected <?php } ?>">
         <?php if(INVENTORY_MODE!=1) {   ?>        Reports <?php } else echo "Home"; ?>
           </div></a>
			
         <!--   <a  href="<?php echo WEB_ROOT ?>admin/calculators"><div class="link <?php if($selectedLink=="calc") { ?> selected <?php } ?>">
           Calculators
           </div></a> -->
           
            <a  href="<?php echo WEB_ROOT ?>admin/settings/"><div class="link <?php if($selectedLink=="settings") { ?> selected <?php } ?>">
           Settings
           </div></a>
           <?php if(INVENTORY_MODE!=1) {   ?>         
           <a  href="<?php echo WEB_ROOT ?>admin/accounts/"><div class="link <?php if($selectedLink=="accounts") { ?> selected <?php } ?>">
           Accounts
           </div></a> 
           <?php } ?>
           <div class="clearfix"></div>