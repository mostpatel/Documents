<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$memberDetails=getMemberById($_GET['id']);
$member_id = $_GET['id'];
$customer_id = $_GET['lid'];	
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Member Details</h4>
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

<tr>
<input type="hidden" name="lid" value="<?php echo $customer_id ?>" />
<td class="firstColumnStyling">
Member Name <span class="requiredField">* </span> :
</td>
<td>
<input type="text" name="name" id="txtName" value="<?php echo $memberDetails['member_name']; ?>"/>
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Member Email <span class="requiredField">* </span> :
</td>
<td>
<input type="text" name="email" id="txtName" value="<?php echo $memberDetails['member_email']; ?>"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Date of Birth : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off" 
value="<?php 
 $memberDob = $memberDetails['member_dob'];
  $memberDob = date('d/m/Y',strtotime($memberDob));
  echo $memberDob;

 ?>"  
name="dob" class="datepicker2 datepick" placeholder="Click to Select!" />
<span class="customError DateError">Please select a date!</span>
</td>
</tr>


<tr>
<td width="130px" class="firstColumnStyling"> Relation : </td>
<td>
					<select id="relation" name="relation_id">
                        <option value="-1" >-- Select The Relation --</option>
                        <?php
                            $relations = listRelations();
                            foreach($relations as $relation)
                              {
                             ?>
                             
                             <option value="<?php echo $relation['relation_id']; ?> " <?php if($relation['relation_id']==$memberDetails['relation_id']) { ?> selected="selected" <?php } ?>><?php echo $relation['relation'] ?></option>
                             <?php } ?>
                              
                         
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