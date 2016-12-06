
            <a  href="<?php echo WEB_ROOT ?>admin/"><div class="link <?php if($selectedLink=="home") { ?> selected <?php } ?>">
            Home
           </div></a>
           
           <a  href="<?php echo WEB_ROOT ?>admin/customer"><div class="link <?php if($selectedLink=="newCustomer") { ?> selected <?php } ?>">
            Add new customer
           </div></a>
           
           <a  href="<?php echo WEB_ROOT ?>admin/search"><div class="link <?php if($selectedLink=="searchCustomer") { ?> selected <?php } ?>">
            Find customer
           </div></a>
           
           <a  href="<?php echo WEB_ROOT ?>admin/customer/EMI/index.php?view=search"><div class="link <?php if($selectedLink=="searchEMI") { ?> selected <?php } ?>">
           Find EMI details
           </div></a>
           
            <a  href="<?php echo WEB_ROOT ?>admin/customer/vehicle/insurance/index.php?view=search"><div class="link <?php if($selectedLink=="searchInsurance") { ?> selected <?php } ?>">
           Find Insurance details
           </div></a>
           
            <a  href="<?php echo WEB_ROOT ?>admin/reports/"><div class="link <?php if($selectedLink=="reports") { ?> selected <?php } ?>">
           Reports
           </div></a>
			
            <a  href="<?php echo WEB_ROOT ?>admin/calculators"><div class="link <?php if($selectedLink=="calc") { ?> selected <?php } ?>">
           Calculators
           </div></a>
           
            <a  href="<?php echo WEB_ROOT ?>admin/settings/"><div class="link <?php if($selectedLink=="settings") { ?> selected <?php } ?>">
           Settings
           </div></a>
             <?php if(isset($_SESSION['adminSession']['admin_rights']) && (in_array(12,$admin_rights)))
			{ ?>
           <a  href="<?php echo WEB_ROOT ?>admin/accounts/"><div class="link <?php if($selectedLink=="accounts") { ?> selected <?php } ?>">
           Accounts
           </div></a> 
           <?php } ?>
           <?php if(defined('SHOW_ENQUIRY_MODULE') && SHOW_ENQUIRY_MODULE==1) { ?>
             <a  href="<?php echo WEB_ROOT ?>admin/settings/general_settings/enquiry_settings/"><div class="link <?php if($selectedLink=="enquiry") { ?> selected <?php } ?>">
           Enquiry
           </div></a> 
           <?php } ?>
           <div class="clearfix"></div>