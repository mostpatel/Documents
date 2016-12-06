<?php 
if(!isset($_GET['id']))
{

header("Location: ".WEB_ROOT."admin/accounts/");
exit;
}
$customer_id=$_GET['id'];

$owner =getContactPersonDetailsForContactPersonId($customer_id);
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment">Update Contact Person Details</h4>
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
<form id="addLocForm" action="<?php echo 'index.php?action=edit'; ?>" method="post" enctype="multipart/form-data" >
<input type="hidden" name="customer_id" value="<?php echo $owner['customer_id']; ?>"  />
<input type="hidden" name="cp_id" value="<?php echo $owner['cp_id']; ?>"  />
<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td>Prefix<span class="requiredField">* </span> : </td>
<td><select name="prefix" id="prefix" >
	<?php $prefix=listPrefix();
	foreach($prefix as $p)
	{
	 ?>
     <option value="<?php echo $p['prefix_id']; ?>" <?php if($p['prefix_id']==$owner['prefix_id']) { ?> selected <?php } ?>><?php echo $p['prefix']; ?></option>
     <?php } ?>
</select></td>
</tr>
<tr>
<td width="230px" class="firstColumnStyling">
Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="customer_name" id="customer_name" class="person_name" placeholder="Only Letters" value="<?php echo $owner['cp_name']; ?>" autofocus />
</td>
</tr>



<tr id="">
                <td>
             Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="cp_con_no" placeholder="more than 6 Digits!" value="<?php echo $owner['cp_contact_no_1']; ?>" /> 
                </td>
            </tr>
<tr>
<td width="230px" class="firstColumnStyling">
Email : 
</td>

<td>
<input type="text" name="email" id="txtEmail" class="email" placeholder="Only Valid Email Address" value="<?php if($owner['cp_email']!="NA") echo $owner['cp_email']; ?>"  />
</td>
</tr>

<tr>
<td width="230px" class="firstColumnStyling">
DOB : 
</td>

<td>
<input type="text" name="cp_dob" id="contact_person_dob" class="dob datepicker1" placeholder="dd/mm/yyyy"  value="<?php $cp_dpb= date('d/m/Y',strtotime($owner['cp_dob'])); if($cp_dpb!="01/01/1900") echo $cp_dpb; ?>" />
</td>
</tr>           

<tr>
<td width="230px" class="firstColumnStyling">
Anniversary : 
</td>

<td>
<input type="text" name="cp_anniversary" id="contact_person_anniversary" class="dob datepicker2" placeholder="dd/mm/yyyy" value="<?php if(validateForNull($owner['cp_anniversary'])  && $owner['cp_anniversary']!="1900-01-01") echo date('d/m/Y',strtotime
($owner['cp_anniversary']));   ?>" /></td>
</tr>            
               
            
            
       

 
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Edit"  class="btn btn-warning">
<?php if(isset($_SERVER['HTTP_REFERER'])) { ?><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" class="btn btn-success" value="Back"/></a><?php } ?>
</td>
</tr>

</table>

</form>
</div>
<div class="clearfix"></div>
<script>

  
 
</script>