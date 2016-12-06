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
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['dReasonsEfficiencyReport']['from_date']) && validateForNull($_SESSION['dReasonsEfficiencyReport']['from_date']))
		 { echo $_SESSION['dReasonsEfficiencyReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['dReasonsEfficiencyReport']['to_date']) && validateForNull($_SESSION['dReasonsEfficiencyReport']['to_date']))
		 { echo $_SESSION['dReasonsEfficiencyReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>


<tr>
<td> Select User : </td>
<td>
<select id="bs3Select" class="selectpic show-tick form-control" multiple data-live-search="true" name="user_id[]">
       
       <?php
	                 $adminUsers = listAdminUsers();
						 
						 foreach($adminUsers as $adminUser)
						 {
							 
						?>
                            <option value="<?php echo $adminUser['admin_id'] ?>" <?php if(isset($_SESSION['dReasonsEfficiencyReport']['user_id']) && is_array($_SESSION['dReasonsEfficiencyReport']['user_id']))
		 { if(in_array($adminUser['admin_id'], $_SESSION['dReasonsEfficiencyReport']['user_id'])) { ?> selected="selected" <?php } }?>><?php echo $adminUser['admin_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
</tr>

<tr>
<td> Team : </td>

<td>
 <select name="stream" onchange="createDropDown('getRelatedTeamMembers.php?catId='+this.value,'admin_id',null)">
<option value="-1" >--Please Select--</option>
<?php
    $teamList = listTeams();
	foreach($teamList as $team)
	  {
	 ?>
     
     <option value="<?php echo $team['team_id'] ?>"><?php echo $team['team_name'] ?></option>
     <?php } ?>
      }
 
    </select> 
    </td>
   </tr>
    
    <tr>
    
  <td> Team Members : </td> 
  <td>
   <select name="admin_id" id="admin_id">
  <option value="-1" >--Please Select--</option>
  
  
   </select>
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
	
		if(isset($_SESSION['dReasonsEfficiencyReport']['dReasons_array']) && is_array($_SESSION['dReasonsEfficiencyReport']['dReasons_array']))
		{
			
			$declineReasonsDetailsArray = $_SESSION['dReasonsEfficiencyReport']['dReasons_array'];
			
			
		$i=0; 
?>
     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Reason</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">No/total</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Percentage</label> 
    </div>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> Decline Reasons Analysis </h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Decline Reason</th>
            <th class="heading">No/Total</th>
            <th class="heading">Percentage</th>
            <th class="heading">View</th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		foreach($declineReasonsDetailsArray as $declineReasonsDetails)
		{
		
        $decline_reason = $declineReasonsDetails['decline_reason'];
		$total = $declineReasonsDetails['total_not_bought_enquiries'];
		$particular = $declineReasonsDetails['total_enquiries_for_a_declined_reason'];
		$decline_id = $declineReasonsDetails['decline_id'];
		
		
		$particular_percentage = round((($particular/$total)*100), 2);
		
		
		?>
      
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	
            <td><?php echo ++$i; ?>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $decline_reason; 
			?>
            </span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			echo $particular."/".$total;
			?>
            </span>
            </td>

        
            <td>
            <span  class="editLocationName">
			<?php 
			echo $particular_percentage. " ". "%";
			?>
            </span>
            </td>
            
            
       <td class="no_print"> 
       <a href="<?php echo WEB_ROOT."admin/reports/lead_efficiency_reports/decline_reasons/index.php?view=details&id=".$decline_id?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
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
