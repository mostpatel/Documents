<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$agencyDetails=getRasidBookById($_GET['lid']);

$rasid_book_id=$_GET['lid'];	

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Rasis Book</h4>
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
<form id="addAgencyForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post" onsubmit="return checkCheckBox()">
<input type="hidden" name="lid" value="<?php echo $agencyDetails['rasid_book_id'] ?>" />
<table class="insertTableStyling no_print">

<tr>
<td width="220px">Company Name : </td>
				<td>
					<?php if(validateForNull($agencyDetails['agency_name'])) echo $agencyDetails['agency_name']; else echo $agencyDetails['our_company_name'] ?>
                    </td>
                    
                    
                  
</tr>

<tr>

<td class="firstColumnStyling">
Book No<span class="requiredField">* </span> : 
</td>

<td>
<?php  echo $agencyDetails['book_no']; ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Rasid No From<span class="requiredField">* </span> : 
</td>

<td>
<?php  echo $agencyDetails['rasid_no_from'];  ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Rasid No To<span class="requiredField">* </span> : 
</td>

<td>
<?php  echo $agencyDetails['radid_no_to'];  ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Given To<span class="requiredField">* </span> : 
</td>

<td>
<?php  echo $agencyDetails['given_to']; ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Given Date<span class="requiredField">* </span> : 
</td>

<td>
<?php  echo date('d/m/Y',strtotime($agencyDetails['given_date'])); ?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Received Date<span class="requiredField">* </span> : 
</td>

<td>
<input class="datepicker2"  type="text" name="received_date" id="received_date"   />
</td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>
</table>
</form>


</div>
<div class="clearfix"></div>