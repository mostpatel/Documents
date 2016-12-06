<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Select Appropriate Filters</h4>
<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php if(isset($type)  && $type>0 && $type<4) { ?> <strong>Success!</strong> <?php } else if(isset($type) && $type>3) { ?> <strong>Warning!</strong> <?php } ?> <?php echo $msg; ?>
</div>
<?php
		
		
		}
	if(isset($type) && $type>0)
		$_SESSION['ack']['type']=0;
	if($msg!="")
		$_SESSION['ack']['msg']=="";
}

?>
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF']."?action=add"; ?>" method="post">

<table  class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
From Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['uWiseReport']['from_date']) && validateForNull($_SESSION['uWiseReport']['from_date']))
		 { echo $_SESSION['uWiseReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['uWiseReport']['to_date']) && validateForNull($_SESSION['uWiseReport']['to_date']))
		 { echo $_SESSION['uWiseReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Generate" class="btn btn-warning">
</td>
</tr>


</table>
</form>
 <?php
	
		if(isset($_SESSION['uWiseReport']['uWise_array']) && is_array($_SESSION['uWiseReport']['uWise_array']))
		{
			
			$userWiseDetailsArray = $_SESSION['uWiseReport']['uWise_array'];
			
			
		$i=0; 
?>
     
   

<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> User Wise Snapshot Report </h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">User</th>
            <th class="heading">Total Enquiries Generated</th>
            
            <th class="heading">Done Follow Ups</th>
            <th class="heading">Follow Ups Done Per Enquiry</th>
            
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		foreach($userWiseDetailsArray as $userWiseDetails)
		{
		
        $admin_name = $userWiseDetails['admin_name'];
		$total_enquiry_generated_by_user = $userWiseDetails['total_enquiry_generated_by_user'];
		$done_follow_ups_by_user = $userWiseDetails['done_follow_ups_by_user'];
		
		
		
		$follow_up_efficiency = round((($done_follow_ups_by_user/$total_enquiry_generated_by_user)), 2);
		
		
		
		
		?>
      
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	
            <td><?php echo ++$i; ?>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $admin_name; 
			?>
            </span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			echo $total_enquiry_generated_by_user;
			?>
            </span>
            </td>

        
           
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $done_follow_ups_by_user;
			?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $follow_up_efficiency;
			?>
            </span>
            </td>
            
            
      
            
            
          </tr>
          
          <?php
		}
		  ?>
      
            </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
         <?php 
		 }?>
      
</div>
<div class="clearfix"></div>
