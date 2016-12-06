<?php if(isset($_GET['id']) && is_numeric($_GET['id']))
{
$job_card_id = $_GET['id'];
$job_card = getJobCardById($job_card_id);
$job_card_detials = $job_card['job_card_details'];
$job_card_customer_complaints=$job_card['job_card_description'];
$job_card_work_done = $job_card['job_card_work_done'] ;
$job_card_remarks = $job_card['job_card_remarks'];
$regular_items=$job_card['job_card_regular_items'];
$warranty_items=$job_card['job_card_warranty_items'];
$regular_ns_items=$job_card['job_card_ns_items'];
$outside_job_items=$job_card['job_card_outside_job'];
$service_checks=$job_card['job_card_checks'];
$sale=$job_card['job_card_sales'];
$vehicle_id = $job_card_detials['vehicle_id'];
$vehicle = getVehicleById($vehicle_id);	
$customer_id = $vehicle['customer_id'];
$customer = getCustomerDetailsByCustomerId($customer_id);
$oc_id =$admin_id=$_SESSION['edmsAdminSession']['oc_id'];
$invoice_counter = getInvoiceCounterForOCID($oc_id);
$job_card_counter = getJobCounterForOCID($oc_id);
}
else
exit;
 ?>
<link rel="stylesheet" href="../../../../css/jobcard.css" />
<div class="addDetailsBtnStyling no_print">
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard2&id='.$job_card_id; ?>">
<button class="btn viewBtn no_print"> Back Page </button>
</a>

<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card_id ?>"><button title="View this entry" class="btn viewBtn btn-success">Back</button></a>
</div>
<div class="mainDiv">

  <div class="section1">
  
    <div class="address">
   <b> Vaibhav Auto Parts & Service Station </b> <br />
    Shop No.: 1/2, Raghuvir chambers, <br />
    Opp. S.T. Bus Stop, Naroda Gam, <br />
    Ahmedabad-30. Ph : 22810533, Fax : 22815708
    </div>  <!-- End of address-->
    
    <div class="logo">
    </div>  <!-- End of logo-->
    
     <div class="name">
     
         <div class="vaibhav">
         Vaibhav
         </div> <!-- End of vaibhav--> 
         
         <div class="rest">
         Auto Parts & Service Station 
         </div> <!-- End of vaibhav--> 
     
    </div>  <!-- End of name-->
    
    <div class="clearDiv"></div>
  
  </div>  <!-- End of section1-->
  
  
  <div class="section2">
    
    <div class="strip1">
    REGULAR JOB CARD
    </div> <!-- End of strip1-->
    
    <div class="strip2">
      
      <div class="srno">
    <b>  Job Card Sr No. </b> <?php echo $job_card_detials['job_card_no']; ?>
      </div>  <!-- End of srno-->
      
      <div class="date">
    <b>  Date : </b>  <?php echo date('d/m/Y',strtotime($job_card_detials['job_card_datetime'])); ?>
      </div> <!-- End of date-->
      
      <div class="time">
     <b> Time : </b> <?php echo date('h:i:s A',strtotime($job_card_detials['job_card_datetime'])); ?>
      </div>  <!-- End of time-->
      
      <div class="clearDiv"></div>
      
    </div> <!-- End of strip2-->
     
  </div>  <!-- End of section2-->
  
  <div class="section3">
   
    <div class="leftSection3">
   <b> Vehicle Model </b>
    <br>
    
   <?php  $model= getVehicleModelById($vehicle['model_id']); echo $model['model_name']; ?>
    </div>      <!-- End of leftSection3-->
    
    <div class="rightSection3">
  <b>  Warranty Status </b>
    <br>
    <?php  echo getServiceTypeNameById($job_card_detials
					['service_type_id']);  ?>
    </div>      <!-- End of rightSection3-->
    
    <div class="clearDiv"></div>
  </div>  <!-- End of section3-->
  
  <div class="section4">
    
    <div class="freeCoupon">
     <b> Free Coupon Service </b>
    </div>  <!-- End of Free Coupon-->
  
    <div class="6km">
      
       <div class="dtkm">
        <div class="no">
        1
        </div>
        
        <div class="data">
        Dt. <br />
        Km.
        </div>
        
        <div class="clearDiv"></div>
       </div> <!-- End of dtkm -->
       
       <div class="dtkm">
        <div class="no">
        2
        </div>
        
        <div class="data">
        Dt. <br />
        Km.
        </div>
        
        <div class="clearDiv"></div>
       </div> <!-- End of dtkm -->
       
       <div class="dtkm">
        <div class="no">
        3
        </div>
        
        <div class="data">
        Dt. <br />
        Km.
        </div>
        
        <div class="clearDiv"></div>
       </div> <!-- End of dtkm -->
       
       <div class="dtkm">
         <div class="no">
        4
        </div>
        
        <div class="data">
        Dt. <br />
        Km.
        </div>
        
        <div class="clearDiv"></div>
       </div> <!-- End of dtkm -->
       
       <div class="dtkm">
         <div class="no">
        5
        </div>
        
        <div class="data">
        Dt. <br />
        Km.
        </div>
        
        <div class="clearDiv"></div>
       </div> <!-- End of dtkm -->
       
       <div class="lastdtkm">
        <div class="no">
        6
        </div>
        
        <div class="data">
        Dt. <br />
        Km.
        </div>
        
        <div class="clearDiv"></div>
       </div> <!-- End of dtkm -->
       
       <div class="clearDiv"></div>
    </div> <!-- End of 6km -->
    
  </div>  <!-- End of section4-->
  
  <div class="section5">
  
   <div class="leftSection5">
   <table width="100%">
      
       <tr>
         <td>
      <b>   Name of Customer : </b><?php echo $customer['customer_name']; ?>
         </td>
         
         <td>
         </td>
       </tr>
       
      <tr>
         <td>
       <b>  Address : </b><?php echo $customer['customer_address']; ?>
         </td>
         
         <td>
         </td>
       </tr>
       
      
       
       <tr>
         <td>
        <b> Vehicle Chasis No : </b><?php echo $vehicle['vehicle_chasis_no']; ?>
         </td>
         
         <td>
         </td>
       </tr>
       
       <tr>
         <td>
        <b> Date of Sale : </b><?php echo date('d/m/Y',strtotime($job_card_detials['date_of_sale'])); ?>
         </td>
         
         <td>
         </td>
       </tr>
       
       <tr>
         <td>
        <b>Job Card Prepared By :</b> <?php echo getAdminUserNameByID($job_card_detials['created_by']); ?>
         </td>
         
         <td>
         </td>
       </tr>
       
       <tr>
         <td>
       <b> Estimated Repair Cost Rs. :</b> <?php echo number_format($job_card_detials['estimated_repair_cost']); ?>
         </td>
         
         <td>
         </td>
       </tr>
       
      </table>
   </div>
   
   <div class="rightSection5">
   <table>
      
       <tr>
         <td>
        <b> Tel / Mob No. :</b> <?php foreach($customer['contact_no'] as $con) echo $con['customer_contact_no']."  "; ?>
         </td>
         
         <td>
         </td>
       </tr>
       
      <tr>
         <td>
        <b> Vehicle Reg No. : </b><?php echo $vehicle['vehicle_reg_no']; ?>
         </td>
         
         <td>
         </td>
       </tr>
       
       <tr>
         <td>
        <b> KMs Covered :</b> <?php echo number_format($job_card_detials['kms_covered']); ?>
         </td>
         
         <td>
         </td>
       </tr>
       
       <tr>
         <td>
        <b>Delivery Promise Time & Date :</b> <?php if($job_card_detials['delivery_promise']!="1970-01-01") echo date('d/m/Y h:i:s A',strtotime($job_card_detials['delivery_promise'])); ?>
         </td>
         
         <td>
         </td>
       </tr>
       
       
       
      </table>
   </div>
   
   <div class="clearDiv"></div>
  </div>  <!-- End of section5-->


   <div class="section6">
   
    
      <table border="1"> 
        <tr>
             <td style="padding-left:43px; padding-right:43px">
             <b>Job Description
             (As per customer & Service Advisor)</b>
             </td>
             
             <td style="padding-left:45px; padding-right:45px">
             <b>Actual Work dome
             (As per Floor supervisor)</b>
             </td>
             
             <td style="padding-left:80px; padding-right:80px">
             <b>Remark</b>
             </td>
        </tr>
       
       <?php
	   $max_rows = max(count($job_card_customer_complaints),count($job_card_work_done),count($job_card_remarks)); 
	   for($i=0; $i<$max_rows; $i++)
	   {
	   ?>
        <tr  >
          <td style="min-height:20px;padding-left:10px;" > <?php echo $job_card_customer_complaints[$i]['job_desc']; ?> </td>
          <td style="min-height:20px;padding-left:10px;"><?php echo $job_card_work_done[$i]['job_wd']; ?> </td>
          <td style="min-height:20px;padding-left:10px;"><?php echo $job_card_remarks[$i]['jb_remarks']; ?> </td> 
        </tr>
        
        <?php
	   }
		?>
        
         <?php
	  
	   for($i=0; $i<7-$max_rows; $i++)
	   {
	   ?>
        <tr  >
          <td height="20px" >  </td>
          <td> </td>
          <td> </td> 
        </tr>
        
        <?php
	   }
		?>
       
      </table>
        
    
    
    <div class="clearDiv"></div>
    
   </div>  <!-- End of section6-->
   
   <div class="section7">
    
    <div class="sec7Heading">
    SERVICE CHECK :
    </div>  <!-- End of sec7Heading-->
    
    <div class="sec7Content">
    
      <div class="sec7ContentUpper">
      
        <table>
        <?php

$service_checks = listServiceChecksOrderByType();
 $i=0;
for($j=0;$j<count($service_checks);$j++)
{ 


?>
          
          <?php 
          $service_check = $service_checks[$j];
		  
		 
		  if($service_check['check_type']==0) {
			  
			 $i++;
			 
			 if($i%3==1)
			 echo "<tr>";
$checked_service_values=listServiceCheckValuesForServiceCheckForJobCardId($service_check['service_check_id'],$job_card_id);
if(is_numeric($checked_service_values[0]))
$service_check_valye_checked = getServiceCheckValueNameById($checked_service_values[0]);
else
$service_check_valye_checked = "-";
			?>
            <td style="padding-right:85px">
            <ul>
           <li> <b> <?php echo $service_check['service_check']; ?> : </b> <?php echo $service_check_valye_checked; ?> </li>
            </ul>
            </td>
            <?php
			if($i%3==0)
			 echo "</tr>";
			 }  ?>
            
            
         
<?php } ?>          
         
          
        </table>
         
      </div>  <!-- End of sec7ContentUpper -->
    
      <ul>
     <?php

$service_checks = listServiceChecksOrderByType();
 
for($j=0;$j<count($service_checks);$j++)
{ 


?>
          
          <?php 
          $service_check = $service_checks[$j];
		  
		 
		  if($service_check['check_type']==1) {
			  
			 
			 
			
$checked_service_values=listServiceCheckValuesForServiceCheckForJobCardId($service_check['service_check_id'],$job_card_id);

			?>
           
           <li style="padding-top:10px"> <b> <?php echo $service_check['service_check']; ?> : </b> <?php if(is_numeric($checked_service_values[0])) { foreach($checked_service_values as $checked_service_value) echo getServiceCheckValueNameById($checked_service_value)." | ";} ?> </li>
            
           
            <?php
			
			 }  ?>
            
            
         
<?php } ?>
      </ul>
      
    </div>   <!-- End of sec7Content-->
    
   </div>  <!-- End of Section7 -->
   
   <div class="section8">
     
      <div class="leftSection8">
       
        <table>
           
           <tr>
             <td> <b>Time & Date Bay In :</b>  <?php echo date('d/m/Y h:i:s A',strtotime($job_card_detials['bay_in'])); ?></td>
             
             <td> </td>
           </tr>
           
           <tr>
             <td> <b>Technician Name :</b> <?php echo getTechnicianNameById($job_card_detials['technician_id']); ?> </td>
             <td> </td>
           </tr>
           
           <tr>
             <td> <b>Final Vehicle Delivery Date & Time :</b> <?php if($job_card_detials['actual_delivery']!="1970-01-01 00:00:00") echo date('d/m/Y H:i:s',strtotime($job_card_detials['actual_delivery'])); else echo "NA"; ?>
				 </td>
             <td> </td>
           </tr>
           
          
          
        </table>
      </div>  <!-- End of leftSection8 -->
      
      <div class="rightSection8">
      
       <table>
           <tr>
             <td><b>Time & Date Bay Out :</b>  <?php echo date('d/m/Y h:i:s A',strtotime($job_card_detials['bay_out'])); ?> </td>
             <td> </td>
           </tr>
       </table>
       
       <div class="supSign">
       Signature of the Supervisor <br />
      (including actual Work Carried out)
       </div>   <!-- End of supSign -->
       
      </div>   <!-- End of rightSection8 -->
      
      <div class="clearDiv"></div>
      
   </div>   <!-- End of section8 -->
   
    <div class="section9">
      
       <div class="part1Sec9" style="width:100%;border:0;padding:0;margin:0">
      
       <table>
        <?php

$service_checks = listServiceChecksOrderByType();
 $i=0;
for($j=0;$j<count($service_checks);$j++)
{ 


?>
          
          <?php 
          $service_check = $service_checks[$j];
		  
		 
		  if($service_check['check_type']==2) {
			  
			 $i++;
			 
			 if($i%4==1)
			 echo "<tr>";
$checked_service_values=listServiceCheckValuesForServiceCheckForJobCardId($service_check['service_check_id'],$job_card_id);
if(is_numeric($checked_service_values[0]))
$service_check_valye_checked = getServiceCheckValueNameById($checked_service_values[0]);
else
$service_check_valye_checked = "-";
			?>
            <td style="padding-right:85px">
            <ul>
           <li> <b> <?php echo $service_check['service_check']; ?> : </b> <?php echo $service_check_valye_checked; ?> </li>
            </ul>
            </td>
            <?php
			if($i%4==0)
			 echo "</tr>";
			 }  ?>
            
            
         
<?php } ?>  
</table>        
       </div>  <!-- End of part1Sec9 -->
       
       
       
       
       
       <div class="clearDiv"></div>
       
   </div>   <!-- End of section8 -->
   
    <div class="section10">
    
     I/We authorize M/s Khushbu Auto Pvt. Ltd. to carry out repairs enlisted above and the jobs as deemed necessary. I/We agree that M/s. Khushbu Auto Pvt. Ltd. will not be responsible for any damage the vehicle during the repair, testing and storage.  <br /> <br />
     
     I/We thankfully received my/our vehicle duly repaired to my/our entire satisfaction, I/We have received the replaced parts.
     
     <div class="cusSign">
       Customer's Signature
     </div>  <!-- End of cusSign -->
     
   </div>   <!-- End of section8 -->
<div class="addDetailsBtnStyling no_print">   
<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=jcard2&id='.$job_card_id; ?>">
<button class="btn viewBtn no_print"> Back Page </button>
</a>

<a href="<?php echo WEB_ROOT.'admin/customer/vehicle/jobCard/index.php?view=details&id='.$job_card_id ?>"><button title="View this entry" class="btn viewBtn btn-success">Back</button></a>
</div>
  
</div>  <!-- End of mainDiv-->