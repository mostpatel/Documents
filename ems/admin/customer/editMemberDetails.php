<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$memberDetails=getMemberById($_GET['id']);
$member_id = $_GET['id'];
$customer_id = $_GET['lid'];	
$contactNumbers = getMemberContactNo($member_id);



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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=editMemberDetails'; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>
<input type="hidden" name="lid" value="<?php echo $customer_id ?>" />
<input type="hidden" name="id" value="<?php echo $member_id ?>" />

<td class="firstColumnStyling">
Member Name <span class="requiredField">* </span> :
</td>
<td>
<input type="text" name="name" id="txtName" value="<?php echo $memberDetails['member_name']; ?>"/>
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
<td width="130px" class="firstColumnStyling"> Gender : </td>
<td>
					<select id="gender" name="gender">
       <option value="-1" <?php if($memberDetails['gender']==-1) { ?> selected="selected" <?php } ?> >-- Select The Gender --</option>
                       
       <option value="1" <?php if($memberDetails['gender']==1) { ?> selected="selected" <?php } ?> > Female </option> 
       
       <option value="0" <?php if($memberDetails['gender']==0) { ?> selected="selected" <?php } ?> >Male</option>
                             
                              
                         
                            </select> 
</td>
</tr>

<?php  
$lj=0;
foreach($contactNumbers as $contact)
{
 ?>
  <tr>
            <td>
            Contact No<?php if($lj==0) { ?><span class="requiredField">* </span> <?php } ?> : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" <?php if($lj==0) { ?> id="memberContact" <?php } ?> name="memberContact[]" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> placeholder="more than 6 Digits!" value="<?php echo $contact[0]; ?>" /><span></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
<?php
$lj++;
 } ?> 


 <tr id="addcontactTrCustomer">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" <?php if($lj<1) { ?> id="memberContact" <?php } ?> name="memberContact[]" placeholder="more than 6 Digits!" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
            Contact No : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="memberContact[]" onblur="checkContactNo(this.value,this)" placeholder="more than 6 Digits!" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
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
<td></td>
<td>
<input type="submit" value="Save" class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>

</table>
</form>


</div>
<div class="clearfix"></div>