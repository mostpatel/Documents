<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$teamDetails = getTeamDetailsByTeamId($_GET['lid']);
$team_id = $_GET['lid'];	
$team_member_ids = $teamDetails['member_ids'];
$member_id_array = (explode(' , ',$team_member_ids));

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Team Details</h4>
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


<table id="DetailsTable" class="insertTableStyling">

<tr>

<td class="firstColumnStyling">
Team Name :
</td>

<td>
<?php echo $teamDetails['team_name']; ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Team Memebrs:
</td>

<td>
<?php echo $teamDetails['member_names']; ?>
</td>
</tr>

<tr>

<tr>

<td class="firstColumnStyling">
Created By:
</td>

<td>
<?php echo $teamDetails['admin_name']; ?>
</td>
</tr>

<tr>



<tr class="no_print">
<td></td>
<td>
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$team_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$team_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a></td>
</tr>

</table>


</div>
<div class="clearfix"></div>