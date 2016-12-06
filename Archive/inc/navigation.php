<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];
?>
            
            <a  href="<?php echo WEB_ROOT ?>admin/"><div class="link <?php if($selectedLink=="home") { ?> selected <?php } ?>">
            Home
           </div></a>
           
           <a  href="<?php echo WEB_ROOT ?>admin/customer"><div class="link <?php if($selectedLink=="newCustomer") { ?> selected <?php } ?>">
            Add a new Enquiry
           </div></a>
           
            <a  href="<?php echo WEB_ROOT ?>admin/search"><div class="link <?php if($selectedLink=="searchCustomer") { ?> selected <?php } ?>">
            Find customer
           </div></a>
           
           <a  href="<?php echo WEB_ROOT ?>admin/directCustomer"><div class="link <?php if($selectedLink=="customer") { ?> selected <?php } ?>">
         Add a new Customer
           </div></a>
           
           
           <a  href="<?php echo WEB_ROOT ?>admin/leads"><div class="link <?php if($selectedLink=="leads") { ?> selected <?php } ?>">
            Recently Generated Enquiries
           </div></a>
           
            <a  href="<?php echo WEB_ROOT ?>admin/follow_up"><div class="link <?php if($selectedLink=="followups") { ?> selected <?php } ?>">
            Upcoming Follow Ups
           </div></a>
           
          
           <a  href="<?php echo WEB_ROOT ?>admin/reports/"><div class="link <?php if($selectedLink=="reports") { ?> selected <?php } ?>">
           Reports
           </div></a>
           
        
        <!--<a  href="<?php echo WEB_ROOT ?>admin/invoice/"><div class="link <?php if($selectedLink=="directInvoice") { ?> selected <?php } ?>">
            Generate Invoice
           </div></a>-->
			
          
		<?php 
		if (in_array(10, $admin_rights) || in_array(7, $admin_rights))
		{
		?>
           
        <a  href="<?php echo WEB_ROOT ?>admin/settings/">
        <div class="link <?php if($selectedLink=="settings") { ?> selected <?php } ?>">
          Settings
        </div>
        </a>
		  
      <?php 
		}
	?>
           
           <div class="clearfix"></div>