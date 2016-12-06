<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$customer_id=$_GET['id'];


?>
<div class="insideCoreContent adminContentWrapper wrapper">
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=addCustomerProof'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">
<input name="lid" value="<?php echo $customer_id ?>" type="hidden">
<table id="insertCustomerTable" class="insertTableStyling no_print">

<!-- for regenreation purpose Please donot delete -->



<tr id="customerProofTypeTr">
<td>Proof type : </td>
				<td id="customerProofTypeTd">
					<select id="proof" name="customerProofId[]" class="customerProofId" onblur="checkProofId(this.value,this)">
                        <option value="-1" >--Please Select Proof Type--</option>
                        <?php
                            $types = listProofTypes();
                            foreach($types as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['human_proof_type_id'] ?>"><?php echo $super['proof_type'] ?></option>
                             <?php } ?>
                              
                         
                            </select><span class="ValidationErrors contactNoError">Please select a Proof Type!</span> 
                            </td>
</tr>




<tr id="customerProofNoTr">
<td> Proof Number : </td>
<td id="customerProofNoTd"><input type="text" name="customerProofNo[]" autocomplete="off" class="customerProofNo" placeholder="Only Letters and Digits!" onblur="checkProofNo(this.value,this)"/><span class="ValidationErrors contactNoError">Please enter Proof No (only numbers and letters)!</span></td>
</tr>


<tr id="customerProofImgTr">
<td>
Proof Image : 
<br />(.jpg,.jpeg,.png,.gif,.pdf)
</td>
<td>
<input type="file" name="" class="customerFile"  /><br />  <br /><input type="button" value="+" class="btn btn-primary addscanbtnCustomer"/>
</td>
</tr> 

<tr id="MemberSelectTr" style="display:none">
<td> Prrof For : </td>
				<td id="MemberSelectTd">
					<select id="member" name="member" class="member" >
                        <option value="-1" ><?php  $customerDetails = getCustomerById($customer_id); echo $customerDetails['customer_name']; ?></option>
                        <?php
                            $members = getMembersByCustomerId($customer_id);
                            foreach($members as $member)
                              {
                             ?>
                             
                             <option value="<?php echo $member['member_id'] ?>"><?php echo $member['member_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select><span class="ValidationErrors contactNoError">Please select a Proof Type!</span> 
                            </td>
</tr>



<!-- end of used for regeneration -->

</table>

<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="250px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Proof" id="addCustomerProofBtn"/></td>
</tr> 

    
</table>

<table style="margin-top:10px;margin-bottom:10px;">
<tr>
<td width="250px;"> </td>
<td><input type="submit" class="btn btn-warning" value="Add"/>
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=customerDetails&id='.$customer_id ?>"><input type="button" value="Back" class="btn btn-success" /></a>
</td>
</tr> 

    
</table>



</form>

</div>
<div class="clearfix"></div>
<script>
document.generateCustomerProof = 1;
	</script>