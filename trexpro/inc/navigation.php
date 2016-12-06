
            <a  href="<?php echo WEB_ROOT ?>admin/"><div class="link <?php if($selectedLink=="home") { ?> selected <?php } ?>">
            Home
           </div></a>
           <?php if(CASH_MEMO_MODE!=1) { ?>
             <a  href="<?php echo WEB_ROOT ?>admin/transportation/lr"><div class="link <?php if($selectedLink=="newLR") { ?> selected <?php } ?>">
            Add LR
           </div></a>
           
           
             <a  href="<?php echo WEB_ROOT ?>admin/transportation/trip_memo"><div class="link <?php if($selectedLink=="newMemo") { ?> selected <?php } ?>">
            Add Trip Memo
           </div></a>
           
             <a  href="<?php echo WEB_ROOT ?>admin/transportation/paid_lrs"><div class="link <?php if($selectedLink=="paid_lrs") { ?> selected <?php } ?>">
            Cash Received
           </div></a>
             <a  href="<?php echo WEB_ROOT ?>admin/transportation/trip_summary"><div class="link <?php if($selectedLink=="paid_lrs") { ?> selected <?php } ?>">
            Trip Summary
           </div></a>
           <?php if(SLAVE==0 && in_array(11,$admin_rights)) { ?>
             <a  href="<?php echo WEB_ROOT ?>admin/transportation/trip_invoice"><div class="link <?php if($selectedLink=="newInvoice") { ?> selected <?php } ?>">
            Add Invoice
           </div></a>
           <?php } ?>
           <?php } else { ?>
           
            <a  href="<?php echo WEB_ROOT ?>admin/transportation/import_trip_memo"><div class="link <?php if($selectedLink=="cash_memo") { ?> selected <?php } ?>">
            Import Trip Memo
           </div></a>
           
            <a  href="<?php echo WEB_ROOT ?>admin/transportation/cash_memo"><div class="link <?php if($selectedLink=="cash_memo") { ?> selected <?php } ?>">
           Add Cash Memo
           </div></a>
           
           <?php } ?>
           <a  href="<?php echo WEB_ROOT ?>admin/customer"><div class="link <?php if($selectedLink=="newCustomer") { ?> selected <?php } ?>">
            Add new customer
           </div></a>
           
           <a  href="<?php echo WEB_ROOT ?>admin/search"><div class="link <?php if($selectedLink=="searchCustomer") { ?> selected <?php } ?>">
            Find 
           </div></a>
            
           
        
           
            <a  href="<?php echo WEB_ROOT ?>admin/reports/"><div class="link <?php if($selectedLink=="reports") { ?> selected <?php } ?>">
           Reports
           </div></a>
			
       
           
            <a  href="<?php echo WEB_ROOT ?>admin/settings/"><div class="link <?php if($selectedLink=="settings") { ?> selected <?php } ?>">
           Settings
           </div></a>
             <?php if(CASH_MEMO_MODE!=1) { ?>
             <?php if(SLAVE==0 && in_array(11,$admin_rights)) { ?>
           <a  href="<?php echo WEB_ROOT ?>admin/accounts/"><div class="link <?php if($selectedLink=="accounts") { ?> selected <?php } ?>">
           Accounts
           </div></a> 
           <?php }} ?>
           <div class="clearfix"></div>