<?php 

$enquiry_id = $_GET['id'];
if (!checkForNumeric($enquiry_id))
{
	exit;
}

$enquiryDetails=getEnquiryById($enquiry_id);
$current_holder = $enquiryDetails['current_lead_holder'];

?>



<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Assign Lead To</h4>
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

<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_id; ?>" />

<table class="insertTableStyling no_print">



<tr>
<td> Assign Lead To <span class="requiredField">* </span>: </td>
				<td>
					<select id="admin_id" name="admin_id">
                       
                        <?php
                            $users = listAdminUsers();
                            foreach($users as $user)
							
                              {
								 $userId = $user['admin_id'];
								 $teamIdData = getTeamIdByAdminId($userId);
								 $teamId = $teamIdData['team_id'];
								 $teamNamedata = getTeamNameByTeamId($teamId);
								 $team_name = $teamNamedata['team_name']; 
                             ?>
                             
                             <option value="<?php echo $user['admin_id'] ?>" <?php if($user['admin_id'] == $current_holder) { ?> selected="selected" <?php } ?>> <?php echo $user['admin_name']."(".$team_name.")" ?>
                             
                             </option>
                             <?php 
							 } 
							 ?>
                              
                         
                            </select> 
                            </td>
</tr>



<tr>
<td>
Reasons to Change : 
</td>

<td>
<textarea id="reasonsToChange" class="reasonsToChange" name="reasonsToChange"  cols="5" rows="6"></textarea>
</td>
</tr>





<tr>
<td></td>
<td>
<input type="submit" value="Add" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=details&id=<?php echo $enquiry_id;  ?>"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

       
</div>
<div class="clearfix"></div>