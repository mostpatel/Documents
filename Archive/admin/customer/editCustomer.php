<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$enquiry_from_id=$_GET['state'];
$varOne=$_GET['redirect'];	
$customerDetails=getCustomerById($_GET['lid']);
$customer_id=$_GET['lid'];
$contactNumbers = getCustomerContactNo($customer_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Customer Details</h4>
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
<form id="addLocForm" action="<?php $submit_url = $_SERVER['PHP_SELF'].'?action=editCustomer'; if(isset($_GET['redirect'])) $submit_url=$submit_url."&redirect=".$_GET['redirect']; echo $submit_url; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>
<td> Customer Prefix : </td>
				<td>
					<select  name="prefix_id">
                        <option value="0"> --Please Select-- </option>
                        <?php
                            $prefixes = listPrefix();
							
                            foreach($prefixes as $prefix)
                              {
                             ?>
                             
                           <option value="<?php echo $prefix['prefix_id'] ?>" <?php if($prefix['prefix_id'] == $customerDetails['prefix_id']) { ?> selected="selected" <?php } ?>><?php echo $prefix['prefix'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>

<td class="firstColumnStyling">
Customer Name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $customerDetails['customer_id'] ?>" />
<input type="hidden" name="enquiry_id" value="<?php echo $enquiry_from_id ?>" />
<input type="text" name="name" id="txtName" value="<?php echo $customerDetails['customer_name']; ?>"/>
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
            <input type="text" class="contact" <?php if($lj==0) { ?> id="customerContact" <?php } ?> name="customerContact[]" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> placeholder="more than 6 Digits!" value="<?php echo $contact[0]; ?>" /><span></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
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
                <input type="text" class="contact" <?php if($lj<1) { ?> id="customerContact" <?php } ?> name="customerContact[]" placeholder="more than 6 Digits!" <?php if($lj!=0) { ?> onblur="checkContactNo(this.value,this)" <?php } ?> /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </tr>

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedCustomer">
            <td>
            Contact No : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="customerContact[]" onblur="checkContactNo(this.value,this)" placeholder="more than 6 Digits!" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
       
       

<td class="firstColumnStyling">
Email :
</td>

<td>

<input type="text" name="email" id="mrp" value="<?php echo $customerDetails['customer_email']; ?>"/>
</td>
</tr>






<tr>
<td></td>
<td>
<input type="submit" value="Save" class="btn btn-warning">

<a href="<?php if($varOne) { echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id; } else { echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_from_id; } ?>">
<input type="button" value="back" class="btn btn-success" />
</a>

</td>
</tr>

</table>
</form>


</div>
<div class="clearfix"></div>