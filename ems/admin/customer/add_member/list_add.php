<?php 

$customer_id = $_GET['id'];
if (!checkForNumeric($customer_id))
{
	exit;
}

?>

<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Customer Member Details</h4>
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

<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />

<table class="insertTableStyling no_print">



<tr>

<td width="220px" class="firstColumnStyling">
Member's Name<span class="requiredField">* </span> : 
</td>

<td> 
<input type="text" name="name" id="name" class="customer_name" placeholder="Only Letters" <?php if(is_numeric($customer_id) && isset($customerDetails['customer_name'])) { ?> value="<?php echo $customerDetails['customer_name']; ?>" disabled="disabled" <?php } ?>/>

</td>
</tr>

<tr>
<td width="130px" class="firstColumnStyling"> Gender : </td>
<td>
					<select id="gender" name="gender">
                        <option value="-1" >-- Select The Gender --</option>
                       
                            <option value="1">Female</option> 
                           <option value="0">Male</option>
                             
                              
                         
                            </select> 
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
                             
                           <option value="<?php echo $relation['relation_id'] ?>"><?php echo $relation['relation'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
</td>
</tr>

<tr>
<td class="firstColumnStyling"> Nationality : </td>
<td>
                         <select  name="member_nationality">
                             <option value="1">Indian</option>
                              <option value="0">Other</option>
                          </select> 
                            
</td>
</tr>


<?php  if(is_numeric($customer_id) && isset($contact_nos[0][0]) && is_numeric($contact_nos[0][0])) { 

foreach($contact_nos as $contact_no)
{
?>


<tr id="addcontactTrGuarantor">
                <td>
                Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="mobile_no[]" placeholder="more than 6 Digits!" disabled="disabled" value="<?php echo $contact_no[0]; ?>" /> 
                </td>
            	</tr>
                

<?php } }else { ?>



 
 <tr id="addcontactTrGuarantor">
                <td>
                Contact No<span class="requiredField">* </span> : 
                </td>
                
                <td id="addcontactTd">
                <input type="text" class="contact" id="customerContact" name="mobile_no[]" placeholder="more than 6 Digits!" onchange="searchForDuplicateMobile()" /> <span class="addContactSpan"><input type="button" title="add more contact no" value="+" class="btn btn-success addContactbtnCustomer"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            	</tr>
                

<!-- for regenreation purpose Please donot delete -->
            
            <tr id="addcontactTrGeneratedGuarantor">
            <td>
            Contact No : 
            </td>
            
            <td id="addcontactTd">
            <input type="text" class="contact" name="mobile_no[]" onblur="checkContactNo(this.value,this)" placeholder="more than 6 Digits!" onchange="searchForDuplicateMobile()" />  <span class="deleteContactSpan"><input type="button" value="-" title="delete this entry"  class="btn btn-danger deleteContactbtn" onclick="deleteContactTr(this)"/></span><span class="ValidationErrors contactNoError">Please enter a valid Phone No (only numbers)</span>
                </td>
            </td>
            </tr>
       
       
<!-- end for regenreation purpose -->

<tr style="display:none" id="dup_contact_no">
<td></td>
<td><a id="dup_a_href" style="font-size:12px;text-decoration:underline;cursor:pointer;color:#00C;" href="">Duplicate Customer Found</a></td>
</tr>
<?php } ?>
<?php  if(is_numeric($customer_id) && $customerDetails['customer_email']) { ?>

<tr>

<td width="220px" class="firstColumnStyling">
Email Address : 
</td>

<td>
<input type="text" id="email" name="email"  placeholder="Only Letters!" value="<?php echo $customerDetails['customer_email']; ?>" disabled="disabled" />
</td>
</tr>


<?php }else { ?>

<tr>

<td width="220px" class="firstColumnStyling">
Email Address : 
</td>

<td>
<input type="text" id="email" name="email"  placeholder="Only Letters!" onchange="searchForDuplicateEmail()"/>
</td>
</tr>

<?php } ?>

<tr>
<td class="firstColumnStyling">
Date of Birth : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="dob" class="datepicker2 datepick" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add" class="btn btn-warning">
<a href="<?php echo WEB_ROOT."admin/customer/index.php?view=customerDetails&id=".$customer_id ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
</td>
</tr>

</table>
</form>

       
</div>
<div class="clearfix"></div>


<script>

function searchForDuplicateEmail()
{
	
	var emailAddress = document.getElementById('email').value;
	
	if(emailAddress!="")
	checkForDuplicateEmail(emailAddress);
	
}

function checkForDuplicateEmail(emailAddress)
{
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	
      var customer_id=parseInt(xmlhttp.responseText);
	  
	  if(!isNaN(customer_id) && customer_id>0)
	  {
		  $('#dup_contact_no').show();
		  var hreff="<?php echo WEB_ROOT ?>admin/customer/index.php?view=customerDetails&id="+customer_id;
		 
		  document.getElementById('dup_a_href').setAttribute('href',hreff);
		  }
		else
		 {
			 $('#dup_contact_no').hide();
			 
		 } 

    }
  }
   
   
  var url="<?php echo WEB_ROOT ?>json/exact_email.php?q="+emailAddress;
 
  xmlhttp.open("GET",url,true);
  xmlhttp.send();
	
}


function searchForDuplicateMobile(){
	
	 var contact_str="";
	$('.contact').each(function(index, element) {
     
	
	 if(!isNaN(parseInt(element.value)))
	 {
		 var con_no=parseInt(element.value);
		 contact_str=contact_str+con_no+",";
		 }
	
	
    });
	if(contact_str!="")
	checkForDuplicateCustomer(contact_str);
	
}

function checkForDuplicateCustomer(multi_contact_str)
{
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	
      var customer_id=parseInt(xmlhttp.responseText);
	  
	  if(!isNaN(customer_id) && customer_id>0)
	  {
		  $('#dup_contact_no').show();
		  var hreff="<?php echo WEB_ROOT ?>admin/customer/index.php?view=customerDetails&id="+customer_id;
		
		  document.getElementById('dup_a_href').setAttribute('href',hreff);
		  }
		else
		 {
			 $('#dup_contact_no').hide();
			 
			 } 

    }
  }
   
   
	var url="<?php echo WEB_ROOT ?>json/exact_mobile_no.php?q="+multi_contact_str;
  xmlhttp.open("GET",url,true);
  xmlhttp.send();
	
	}
</script>