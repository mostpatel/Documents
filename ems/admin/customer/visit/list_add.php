<?php 

$enquiry_id = $_GET['id'];
if (!checkForNumeric($enquiry_id))
{
	exit;
}


$enquiryDetails=getEnquiryById($enquiry_id);

$customer_id = $enquiryDetails['customer_id'];
$customerDetails=getCustomerById($customer_id);

$contactNumbers=getCustomerContactNo($customer_id);


?>

<div class="insideCoreContent adminContentWrapper wrapper">

<!--<a href="<?php echo WEB_ROOT."admin/customer/close_lead/index.php?id=".$enquiry_id?>">
<input type="button" value="+Close Lead" class="btn btn-warning" />
</a>-->


<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment">Customer Details</h4>

<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Customer Name : 
</td>

<td>

                             <?php echo $customerDetails['customer_name']; ?>					
                            
</td>
</tr>


<tr>
<td>Email : </td>
<td>

                             <?php echo $customerDetails['customer_email'];  ?>					
                          
</td>
</tr>



 <tr id="addcontactTrCustomer">
                <td>
                Contact No : 
                </td>
                
                <td id="addcontactTd">
                <?php
                            
							
                            for($z=0; $z<count($contactNumbers); $z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." <br> ";				
                              } ?>
                </td>
            </tr>

<tr>
	<td></td>
  <td class="no_print"> 

            
  <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&lid='.$customer_id.'&state='.$enquiry_form_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            

</table>
</div>




<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment no_print"><?php echo MEETING_GLOBAL_VAR; ?> Details</h4>
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
<td class="firstColumnStyling">
<?php echo MEETING_GLOBAL_VAR; ?> Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="visit_date" class="datepicker2 datepick next_follow_up_date" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>


<tr>
<td>
Discussion : 
</td>

<td>
<textarea id="discussion" class="discussion" name="visitDiscussion"  cols="5" rows="6"></textarea>
</td>
</tr>

<tr>
<td> Send SMS? <span class="requiredField">* </span>: </td>
				<td>
					<select id="sms_status" name="sms_status">
                    
                    <option value="1"> Yes </option>
                    <option value="0"> No </option>        
                              
                    </select> 
                     
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

       
</div>
<div class="clearfix"></div>