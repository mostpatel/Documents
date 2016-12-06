<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Choose a Team Leader/Leaders</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>
<td> Team <span class="requiredField">* </span>: </td>
				<td>
					<select id="team_id" name="team_id">
                     <option value="-1" > -- Select Team -- </option>
                        <?php
                            $teams = listTeams();
                            foreach($teams as $team)
                              {
                             ?>
                             
                             <option value="<?php echo $team['team_id'] ?>"><?php echo $team['team_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td> Choose Team Leader <span class="requiredField">* </span>: </td>
				<td>
					<select id="admin_id" name="admin_id[]" class="selectpic show-tick form-control" multiple data-live-search="true">
                       
                        <?php
                            $users = listAdminUsers();
                            foreach($users as $user)
                              {
                             ?>
                             
                             <option value="<?php echo $user['admin_id'] ?>"><?php echo $user['admin_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add Team" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Team Leaders</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Team Name</th>
            <th class="heading">Team Leaders</th>
            
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$teamWithLeaders = getTeamWithLeaders();
		$i=0;
		foreach($teamWithLeaders as $teamWithLeader)
		{
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
           <td><span  class="editLocationName" id="<?php echo $teamWithLeader['team_id'] ?>"><?php echo $teamWithLeader['team_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName" id="<?php $teamWithLeader['team_id'] ?>">
			<?php 
			echo $teamWithLeader['member_names']; 
			?>
            </span>
            </td>
            
    
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$teamWithLeader['team_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$teamWithLeader['team_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$teamWithLeader['team_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>