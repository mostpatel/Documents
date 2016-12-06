<div class="insideCoreContent adminContentWrapper wrapper">



 <?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
}

$decline_id = $_GET['id'];
$declineDetails = getReasonById($decline_id);
$decline_reason = $declineDetails['decline_reason'];
$allCustomerDetails = getAllEnquiryDetailsForADeclineReason($decline_id);

 ?>
     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Closing Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Customer Name</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Product</label>
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Contact No</label>
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Closed By</label>
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Discussion</label> 
    </div>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> List for  "<?php echo $decline_reason  ?>" reason </h4> 
<a href="<?php echo WEB_ROOT."admin/reports/lead_efficiency_reports/decline_reasons/index.php"?>"><input type="button" value="back" class="btn btn-success" /></a>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Closing Date</th>
            <th class="heading">Customer Name</th>
            <th class="heading">Product</th>
            <th class="heading">Contact No</th>
            <th class="heading">Closed By</th>
            <th class="heading">Discussion</th>
            <th class="heading">View</th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		foreach($allCustomerDetails as $allCustomerDetail)
		{
		 
		 $customer_name = $allCustomerDetail['customer_name'];
		 $enquiry_close_date = $allCustomerDetail['enquiry_close_date'];
		 $enquiry_closed_by = $allCustomerDetail['admin_name'];
		 $contact_no = $allCustomerDetail['contact_no'];
		 $sub_cat_name = $allCustomerDetail['sub_cat_name'];
		 $discussion = $allCustomerDetail['discussion'];
		 $enquiry_form_id = $allCustomerDetail['enquiry_form_id']; 
        
		
		
		?>
      
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	
            <td><?php echo ++$i; ?>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo date('d/m/Y H:i', strtotime($enquiry_close_date));
			?>
            </span>
            </td>
            
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $customer_name; 
			?>
            </span>
            </td>
            
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $sub_cat_name; 
			?>
            </span>
            </td>
            
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $contact_no; 
			?>
            </span>
            </td>
            
             <td>
            <span  class="editLocationName">
			<?php 
			echo $enquiry_closed_by; 
			?>
            </span>
            </td>
            
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $discussion; 
			?>
            </span>
            </td>
            
            
           
            
            
            
            
       <td class="no_print"> 
       <a href="<?php echo WEB_ROOT."admin/customer/reports/lead_efficiency_reports/decline_reasons/index.php?view=details&id=".$decline_id?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          </tr>
          
          <?php
		}
		  ?>
      
            </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
        
      
</div>
<div class="clearfix"></div>
