<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
}

$teamLeaderDetails = getTeamWithLeadersByTeamId($_GET['lid']);
$team_id = $_GET['lid'];	
$team_leader_ids = $teamLeaderDetails['leader_ids'];
$team_leader_array = (explode(' , ',$team_leader_ids));



?>
 
 
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Team Leader</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">

<table class="insertTableStyling no_print">

<input type="hidden" name="lid" value="<?php echo $team_id ?>" />

<tr>
<td> Team : </td>
<td>
<select id="bs3Select"  name="team_id">
       
       <?php
	                 $teams = listTeams();
						 
						 foreach($teams as $team)
						 {
							 
							 
						?>
                    <option value="<?php echo $team['team_id'] ?>" <?php if(in_array($team['team_id'], $team_id)) { ?> selected="selected" <?php } ?>><?php echo $team['team_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    
    
</tr>


<tr>
<td> Select Leaders : </td>
<td>
<select id="bs3Select superCat" class="selectpic selectpic1 show-tick form-control" multiple data-live-search="true" name="admin_id[]">
       
       <?php
	                 $admins = listAdminUsers();
						 
						 foreach($admins as $admin)
						 {
							 
							 
						?>
                    <option value="<?php echo $admin['admin_id'] ?>" <?php if(in_array($admin['admin_id'], $team_leader_array)) { ?> selected="selected" <?php } ?>><?php echo $admin['admin_name'] ?></option>
                            
                          <?php
						 }
						  ?> 
</select>
    </td>
    
    
</tr>



<tr>
<td></td>
<td>
<input type="submit" class="btn btn-warning" value="Save"/>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>


</div>
<div class="clearfix"></div>