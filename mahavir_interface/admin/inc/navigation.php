<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];
?>
            
            <a  href="<?php echo WEB_ROOT ?>admin/"><div class="link <?php if($selectedLink=="home") { ?> selected <?php } ?>">
            Home
           </div></a>
           
          
           
           
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