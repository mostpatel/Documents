<?php
require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/common.php";
require_once "../lib/city-functions.php";
require_once "../lib/sub-category-functions.php";
require_once "../lib/category-functions.php";
require_once "../lib/super-category-functions.php";
require_once "../lib/customer-type-functions.php";
require_once "../lib/adminuser-functions.php";
require_once "../lib/lead-functions.php";
require_once "../lib/enquiry-functions.php";
require_once "../lib/customer-functions.php";
require_once "../lib/report-functions.php";
require_once "../lib/rel-subcat-enquiry-functions.php";
require_once "../lib/quotes-functions.php";

$selectedLink="home";
require_once("../inc/header.php");

$quote_counter_id = getCurrentQuoteCounter();
$todaysQuote = getQuoteByCurrentQuoteId($quote_counter_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper"> 
 <div class="widgetContainer">
 
    <div class="notificationCenter" style="margin-bottom:10px;">
       Quote of the Day
   </div>
   
   <div class="quoteDisplay" style="margin-bottom:30px;">
   "<?php echo $todaysQuote ?>"
   </div><!-- End of quoteDisplay-->
   
   <div class="notificationCenter" style="margin-bottom:10px;">
       To Do List For Today
   </div>
   
   <div class="quoteDisplay" style="margin-bottom:30px;">
  <span style="color:#000"> Today's Follow-ups : </span> <?php  echo countTodaysFollowUpsNew(); ?>  <br />
  <span style="color:#000"> Expired Follow-ups : </span><?php  echo countExpiredFollowUpsNew(); ?>  <br /> <br />
  Keep generating more Enqiries :)
   </div><!-- End of quoteDisplay-->
   
 
   <div class="notificationCenter">
       Notification Center
   </div>
   
   

 <div class="Column">
     
        
        <h4 class="widgetTitle"> Upcoming Follow Ups </h4>
         
         <table class="adminContentTable">
    <thead>
    	<tr>
           <th class="heading">No</th>
        	<th class="heading">Follow Up Date</th>
            <th class="heading">Discussion</th>
             <th class="heading">Name</th>
            <th class="heading"><?php echo PRODUCT_GLOBAL_VAR; ?></th>
            <th class="heading">Extra Details</th>
            
            <?php
			if($show_amount==1)
			{
			?>
              <th class="heading"> Amount </th>
              
              <?php
			}
			  ?>
             <th class="heading">Phone</th>
              <th class="heading">Handled By</th>
              <!-- <th class="heading">Visit Date</th>-->
            <th class="heading btnCol no_print"></th>
            
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 $upComingFollowUps=viewFollowUpsWidget();
		 $i=0;
			
			if(is_array($upComingFollowUps) && count($upComingFollowUps)>0)
			{
		    foreach($upComingFollowUps as $upComingFollowUp)
			{
			
			$enquiry_form_id = $upComingFollowUp['enquiry_form_id'];
			
			
			
			$next_follow_up_date_string = $upComingFollowUp['next_follow_up_date'];
			$followUpAdminArray = explode(' # ', $next_follow_up_date_string);
			$next_follow_up_date_string = $followUpAdminArray[0];
			$handled_by_id = $upComingFollowUp['current_lead_holder'];
			$handled_by = getAdminUserNameByID($handled_by_id);
			$followUpArray = explode(' ^ ', $next_follow_up_date_string);
			
			$follow_up_date = $followUpArray[0];
			$follow_up_details = $followUpArray[1];
		     
		?>
        
          <tr class="resultRow <?php if($upComingFollowUp['is_imp']==1){ ?> shantiRow <?php }?>">
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName">
			 <?php 
			 
			 $follow_up_date_for_widget = date('d/m/Y',strtotime($follow_up_date));
			
			 if($follow_up_date_for_widget=="01/01/1970")
			 {
				 echo "NA";
			  }
			 else
			 echo $follow_up_date_for_widget;
			 
			 ?>
            </span>
            </td>
            
            <td class="breakClass"><span  class="editLocationName">
			 <?php 
			  
			  if($follow_up_details == NULL)
			  {
				 echo " - ";  
			  }
			  else
			  echo $follow_up_details;
			  ?>
              </span>
             </td>
            
             <td><span  class="editLocationName">
			 
			 <?php echo $upComingFollowUp['customer_name']; ?>
             
             
             </span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			echo $upComingFollowUp['sub_cat_name'];
			?>
            </span>
            </td>

            
            <td>
            <span  class="editLocationName">
			<?php echo $upComingFollowUp['attribute_types_sub_cat_wise']; ?>
            </span>
            </td>
            
            <?php
			if($show_amount==1)
			{
			?>
            <td>
            <span  class="editLocationName">
			<?php echo $upComingFollowUp['customer_price']; ?>
            </span>
            </td>
            <?php
			}
			?>
            
            
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                         echo $upComingFollowUp['contact_no'];  ?>
            
            </span>
            </td>
            
            <td><?php echo $handled_by; ?></td>
            
            <!--<td><?php 
			
			 $visitDate = date('d/m/Y',strtotime($upComingFollowUp['visit_date'])); 
			 if($visitDate == "01/01/1970")
			 {
			   echo "-";	 
			 }
			 else
			 echo $visitDate;
			 ?></td> -->
            
            
             <td class="no_print"> 
             
             <a href="<?php echo WEB_ROOT."admin/customer/follow_up/index.php?id=".$enquiry_form_id?>" target="_blank">
             <input type="button" value="+F" class="btn btn-success" /> 
             </a>
            </td>
            
             
            
             <td class="no_print"> 
             <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id?>" target="_blank">
             <button title="View this entry" class="btn viewBtn"><span class="view">V</span></button>
             </a>
            </td>
            
            
          
  
        </tr>
        <?php
			}
			}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/follow_up_reports/custom/index.php?action=fromHomeUpcomingFollowUps"><div class="more">View all Upcoming Follow Ups..</div></a>

<div style="clear:both"></div>
</div>
       
       
       
  <div class="Column">
     
        
        <h4 class="widgetTitle"> Expired Follow Ups </h4>
         
         <table class="adminContentTable">
    <thead>
    	<tr>
           <th class="heading">No</th>
        	<th class="heading">Follow Up Date</th>
            <th class="heading">Discussion</th>
             <th class="heading">Name</th>
            <th class="heading"><?php echo PRODUCT_GLOBAL_VAR; ?></th>
            <th class="heading">Extra Details</th>
            <?php
			if($show_amount==1)
			{
			?>
              <th class="heading">Amount</th>
              <?php
			}
			  ?>
             <th class="heading">Phone</th>
              <th class="heading">Handled By</th>
             <!-- <th class="heading">Visit Date</th> -->
            <th class="heading btnCol no_print"></th>
             <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 
		  
		 
		 $upComingFollowUps=viewExpiredFollowUpsWidget();
		 
		 $i=0;
			
			if(is_array($upComingFollowUps) && count($upComingFollowUps)>0)
			{
		    foreach($upComingFollowUps as $upComingFollowUp)
			{
			
			$enquiry_form_id = $upComingFollowUp['enquiry_form_id'];
			
			$next_follow_up_date_string = $upComingFollowUp['next_follow_up_date'];
			$followUpAdminArray = explode(' # ', $next_follow_up_date_string);
			$next_follow_up_date_string = $followUpAdminArray[0];
			$handled_by_id = $upComingFollowUp['current_lead_holder'];
			$handled_by = getAdminUserNameByID($handled_by_id);
			$followUpArray = explode(' ^ ', $next_follow_up_date_string);
			
			$follow_up_date = $followUpArray[0];
			$follow_up_details = $followUpArray[1];
		
			
		?>
        
          <tr class="resultRow <?php if($upComingFollowUp['is_imp']==1){ ?> shantiRow <?php }?>">
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName">
			 <?php 
			 
			 $follow_up_date_for_widget = date('d/m/Y',strtotime($follow_up_date));
			
			 if($follow_up_date_for_widget=="01/01/1970")
			 {
				 echo "NA";
			  }
			 else
			 echo $follow_up_date_for_widget;
			 
			 ?>
            </span>
            </td>
            
            <td><span  class="editLocationName">
			 <?php 
			  
			  if($follow_up_details == NULL)
			  {
				 echo " - ";  
			  }
			  else
			  echo $follow_up_details;
			  ?>
              </span>
             </td>
            
             <td><span  class="editLocationName"><?php echo $upComingFollowUp['customer_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			echo $upComingFollowUp['sub_cat_name'];
			?>
            </span>
            </td>

            
            <td>
            <span  class="editLocationName">
			<?php echo $upComingFollowUp['attribute_types_sub_cat_wise']; ?>
            </span>
            </td>
            
            
            <?php
			if($show_amount==1)
			{
			?>
            <td>
            <span  class="editLocationName">
			<?php echo $upComingFollowUp['customer_price']; ?>
            </span>
            </td>
            <?php
			}
			?>
            
            
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                         echo $upComingFollowUp['contact_no'];  ?>
            
            </span>
            </td>
            
            
            
            <td><?php echo $handled_by; ?></td>
            
           <!-- <td><?php 
			
			 $visitDate = date('d/m/Y',strtotime($upComingFollowUp['visit_date'])); 
			 if($visitDate == "01/01/1970")
			 {
			   echo "-";	 
			 }
			 else
			 echo $visitDate;
			 ?></td> -->
            
            <td class="no_print"> 
             
             <a href="<?php echo WEB_ROOT."admin/customer/follow_up/index.php?id=".$enquiry_form_id?>" target="_blank">
             <input type="button" value="+F" class="btn btn-success" /> 
             </a>
            </td>
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id?>" target="_blank"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          
  
        </tr>
        <?php
			}
			}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/follow_up_reports/expired/index.php?action=fromHomeExpiredFollowUps"><div class="more">View all Expired Follow Ups..</div></a>

<div style="clear:both"></div>
</div>
   
   
   <div class="Column">
     
        
        <h4 class="widgetTitle"> Recently generated Enquiries </h4>
         
         <table class="adminContentTable">
    <thead>
    	<tr>
            <th class="heading">No</th>
            <th class="heading">Enquiry Date</th>
            <th class="heading">Follow Up Date</th>
            <th class="heading">Lead Status</th>
            <th class="heading">Customer Name</th>
            <th class="heading"><?php echo PRODUCT_GLOBAL_VAR; ?></th>
             <th class="heading">Extra Details</th>
             <?php
			if($show_amount==1)
			{
			?>
            <th class="heading">Amount</th>
            <?php
			}
			?>
            <th class="heading">Phone No.</th>
            <th class="heading">Handled By</th>
            <th class="heading btnCol no_print"></th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 $leads=viewLeadsWidget();
			$mj=0;
			$i=0;
			if(is_array($leads) && count($leads)>0)
			{
		    foreach($leads as $lead)
			{
				
			$enquiry_form_id = $lead['enquiry_form_id'];
			
			$isBoughtVariable = $lead['is_bought'];
			
			
						
		?>
        
        <tr class="resultRow <?php if($lead['is_imp']==1){ ?> shantiRow <?php }?>" >
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($lead['enquiry_date']))?></span>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			$follow_Up_date_for_lead = date('d/m/Y',strtotime($lead['next_follow_up_date'])); 
			
			if($follow_Up_date_for_lead== "01/01/1970")
			{
			echo "NA";	
			}
			else
			echo $follow_Up_date_for_lead;
			
			?>
            </span>
            
            
            </td>
            
            <td><span  class="editLocationName">
			<?php 
			if($isBoughtVariable==0)
			{
			 echo "New Enquiry";	
			}
			else if($isBoughtVariable==1)
			{
			 echo "Successful";	
			}
			else if($isBoughtVariable==2)
			{
			 echo "Unsuccessful";	
			}
			else if($isBoughtVariable==3)
			{
			 echo "On Going";	
			}
			?>
            </span>
            </td>
            
             <td><span  class="editLocationName"><?php echo $lead['customer_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			echo $lead['sub_cat_name'];
			?>
            </span>
            </td>

            
            <td>
            <span  class="editLocationName">
			<?php echo $lead['attribute_types_sub_cat_wise']; ?>
            </span>
            </td>
            
            <?php
			if($show_amount==1)
			{
			?>
            
             <td>
            <span  class="editLocationName">
			<?php echo $lead['customer_price']; ?>
            </span>
            </td>
            
            <?php
			}
			?>
            
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                         echo $lead['contact_no'];  ?>
            
            </span>
            </td>
            
            
            <td>
            <span  class="editLocationName">
			<?php   
			$holder_id = $lead['current_lead_holder'];
			$handled_by = getAdminUserNameByID($holder_id);
			echo $handled_by;
			 ?>
            </span>
            </td>
            
            <td class="no_print"> 
             
             <a href="<?php echo WEB_ROOT."admin/customer/follow_up/index.php?id=".$enquiry_form_id?>" target="_blank">
            <input type="button" value="+F" class="btn btn-success" /> 
             </a>
            </td>
            
            
            
            <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id?>" target="_blank">
            <button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          
  
        </tr>
         <?php }} ?>
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/lead_reports/custom/index.php?action=fromHomeUpcomingLeads"><div class="more">View all Enquiries..</div></a>

<div style="clear:both"></div>
</div>    
 
 
 
 <!-- <div class="Column">
     
        
        <h4 class="widgetTitle"> Recently ended Tour </h4>
         <table class="adminContentTable">
    <thead>
    	<tr>
            <th class="heading">No</th>
            <th class="heading">Tour Ending Date</th>
            <th class="heading">Customer Name</th>
            <th class="heading"><?php echo PRODUCT_GLOBAL_VAR; ?></th>
            <th class="heading">Phone No.</th>
            <th class="heading">Handled By</th>
            <th class="heading btnCol no_print"></th>
            <th class="heading btnCol no_print"></th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 $purchaseDates=viewTourEndingDates();
		 $i=0;
				
		foreach($purchaseDates as $purchaseDateDetails)
		{
			$enquiry_form_id = $purchaseDateDetails['enquiry_form_id'];
			
			$isBoughtVariable = $purchaseDateDetails['is_bought'];
			
			$tour_end_date = $purchaseDateDetails['tour_ending_date'];
			$purchase_date = $purchaseDateDetails['purchase_date'];
			
			$next_follow_up_date_string = $lead['next_follow_up_date'];
			
			$followUpAdminArray = explode(' # ', $next_follow_up_date_string);
			$next_follow_up_date_string = $followUpAdminArray[0];
			$handled_by = $followUpAdminArray[1];
			$followUpArray = explode(' ^ ', $next_follow_up_date_string);
			
			$follow_up_date = $followUpArray[0];
			$follow_up_details = $followUpArray[1];
			
			
		 ?>
          <tr class="resultRow">
          
          
          
        	<td><?php echo ++$i; ?>
            </td>
            
            <td><span  class="editLocationName">
			
            <?php 
			 
			 $tour_end_date = date('d/m/Y',strtotime($tour_end_date));
			 $purchase_date = date('d/m/Y',strtotime($purchase_date));
			 
			 if($tour_end_date == "01/01/1970")
			 {
				 echo "NA";
			  }
			 else
			 echo $tour_end_date;
			 
			 ?>
            </span>
            </td>
            
             <td><span  class="editLocationName">
			 <?php 
			 
			 echo $purchaseDateDetails['customer_name']; ?></span>
             </td>
            
           
            
             
            
            <td><span  class="editLocationName">
			<?php
			echo $purchaseDateDetails['products'];
			?>
            </span>
            </td>

            
            
            
            <td>
            <span  class="editLocationName">
            <?php
                            
							
                         echo $purchaseDateDetails['contact_no'];  ?>
            
            </span>
            </td>
            
            
            <td><?php echo $purchaseDateDetails['admin_name']; ?></td>
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            <td>
            <a href="http://www.bhagwatiholidays.com/feedback.php?a=<?php echo $purchaseDateDetails['customer_name'] ?>&b=<?php echo $purchaseDateDetails['contact_no']?>&c=<?php echo $purchaseDateDetails['customer_email'] ?>&d=<?php echo $purchaseDateDetails['products'] ?>&e=<?php echo $purchase_date ?>" target="_blank">
<input type="button" value="+F" class="btn btn-success" />
</a>
</td>
          
  
        </tr>
      <?php }
		 ?>
            </tbody>
    </table>
        
         

<a href="<?php echo WEB_ROOT ?>admin/reports/lead_reports/custom/index.php?action=fromHomeUpcomingLeads"><div class="more">View all..</div></a>

<div style="clear:both"></div>
</div>    
 -->
 
 
       
        
    
 </div>
 </div>
 <div class="clearfix"></div>

<?php
require_once("../inc/footer.php");
 ?> 