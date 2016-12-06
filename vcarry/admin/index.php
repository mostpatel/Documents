<?php
require_once("../lib/cg.php");
require_once("../lib/bd.php");
require_once("../lib/trip-functions.php");
require_once("../lib/driver-functions.php");
require_once("../lib/report-functions.php");
require_once("../lib/vehicle-type-functions.php");
$selectedLink="home";
require_once("../inc/header.php");

 ?>
<div class="insideCoreContent adminContentWrapper wrapper"> 
 <div class="widgetContainer">
 
   <div class="notificationCenter">
       Notification Center
   </div>
   
  <?php ?>
  
<?php 
if(isset($_SESSION['edmsAdminSession']['report_rights']))
{
	$report_rights=$_SESSION['edmsAdminSession']['report_rights'];
	}
  ?>
      
     <div class="Column">
     
        
        <h4 class="widgetTitle"> UnAssigned Trips </h4>
         
         <table id="adminContentTable3" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">From</th>
             <th class="heading">To</th>
             <th class="heading">Trip Date</th>
             <th class="heading">Fare</th>
             <th class="heading">Vehicle Type</th>
            <th class="heading">Trip Created By</th>
             <th class="heading">Name</th>
            <th class="heading no_print btnCol" ></th>
            
        </tr>
    </thead>
    <tbody>
        
        <?php
		$job_cards = getAllUnAssignedTrips();
		$no=0;
		foreach($job_cards as $trip)
		{
		$vehicle_type = getVehicleTypeNameById($trip['vehicle_type_id']);
		$driver_name=getDriverNameFromDriverId($trip['driver_id']);
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo $trip['from_shipping_location']; echo "<br>(".getAreaNameByID($trip['from_area_id']).")"; ?>
            </td>
            <td><?php echo $trip['to_shipping_location']; echo "<br>(".getAreaNameByID($trip['to_area_id']).")"; ?>
            </td>
             <td><?php echo date('d/m/Y H:i:s',strtotime($trip['trip_datetime'])); ?>
            </td>
             
             <td align="center"><?php echo $trip['fare']; ?>
            </td>
              
            <td class="no_print" width="120px;"> 
            
            <?php echo $vehicle_type; ?>
            </td>
            
             <td>
             <?php   echo getAdminUserNameByID($trip['last_modified_by']); ?>
             </td>
             <td>
             <?php echo $trip['customer_name']; ?>
             </td>
            <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/trip/index.php?view=details&id='.$trip['trip_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
          
            
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>



<div style="clear:both"></div>
</div>


      
     
  <?php
 
if(isset($_SESSION['edmsAdminSession']['report_rights']) && (in_array(105,$report_rights) || in_array(199,$report_rights)))
			{ ?>
   
    <div class="Column">
     
        
        <h4 class="widgetTitle"> Upcoming Reminders </h4>
         
         <table class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">Date</th>
            <th class="heading">Remarks</th>
            <th class="heading">Name</th>
            <th class="heading">Contact No.</th>
             <th class="heading">Reg No.</th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 $upcomingEmis=generalRemianderReportsWidget(date('d/m/Y'));
			$mj=0;
			if(is_array($upcomingEmis) && count($upcomingEmis)>0)
			{
		    foreach($upcomingEmis as $upEmi)
			{
			
		?>
        
         <tr class="resultRow">
        	
            
            <td><?php echo date('d/m/Y',strtotime($upEmi['date'])); ?>
            </td>
            
            <td><?php echo $upEmi['remarks']; ?>
            </td>
            
             <td><?php echo $upEmi['customer']['customer_name']; ?>
            </td>
            
            
            <td><?php echo $upEmi['customer']['contact_no'][0][0]; ?>
            </td>
            
            
             <td><?php if($upEmi['reg_no']=="" || $upEmi['reg_no']==null) echo "NA"; else echo $upEmi['reg_no']; ?>
            </td>
            
           
          
            
             	<td class="no_print"><a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=addRemainder&id=<?php echo $upEmi['customer']['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
                					
            </td>
            
  
        </tr>
        <?php
		$mj++;
		if($mj==5) break;
			}
		}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/remainder_reports/custom/index.php?action=fromHomeUpcoming"><div class="more">View all Remainders..</div></a>

<div style="clear:both"></div>
</div>

 <div class="Column">
     
        
        <h4 class="widgetTitle"> Expired Reminders </h4>
         
         <table class="adminContentTable">
    <thead>
    	<tr>
           
        	<th class="heading">Date</th>
            <th class="heading">Remarks</th>
            <th class="heading">Name</th>
            
            <th class="heading">Contact No.</th>
              <th class="heading">Reg No.</th>
            <th class="heading btnCol no_print"></th>
        </tr>
    </thead>
    <tbody>
         <?php
		 
		 $upcomingEmis=generalRemianderReportsWidget(null,date('d/m/Y'));
		 
			$mj=0;
			if(is_array($upcomingEmis) && count($upcomingEmis)>0)
			{
		    foreach($upcomingEmis as $upEmi)
			{
			
		?>
        
         <tr class="resultRow">

            <td><?php  if(date('d/m/Y',strtotime($upEmi['date']))=='01/01/1970') echo "NA"; else echo date('d/m/Y',strtotime($upEmi['date'])); ?>
            </td>
            
            <td><?php echo $upEmi['remarks']; ?>
            </td>
            
             <td><?php echo $upEmi['customer']['customer_name']; ?>
            </td>
            
            
            <td><?php echo $upEmi['customer']['contact_no'][0][0]; ?>
            </td>
            
             <td><?php if($upEmi['reg_no']=="" || $upEmi['reg_no']==null) echo "NA"; else echo $upEmi['reg_no']; ?>
            </td>
            
            
            
            
             	<td class="no_print"><a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=addRemainder&id=<?php echo $upEmi['customer']['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
                					
            </td>
            
             
          
  
        </tr>
        <?php
		$mj++;
		if($mj==5) break;
			}
			}
		?>
         
         </tbody>
    </table>

<a href="<?php echo WEB_ROOT ?>admin/reports/remainder_reports/custom/index.php?action=fromHomeExpired"><div class="more">View all Remainders..</div></a>

<div style="clear:both"></div>
</div>
<?php }
?>   

        
        
 </div>
 </div>
 <div class="clearfix"></div>
<?php
require_once("../inc/footer.php");
 ?> 