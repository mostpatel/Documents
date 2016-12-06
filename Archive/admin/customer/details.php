<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$enquiry_form_id=$_GET['id'];

$enquiryDetails=getEnquiryById($enquiry_form_id);

$customer_id = $enquiryDetails['customer_id'];
$customerDetails=getCustomerById($customer_id);
$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);

$contactNumbers=getCustomerContactNo($customer_id);


$followUpDetails = getFollowUpDetailsByEnquiryId($enquiry_form_id);
$visitDetails = getVisitDetailsByEnquiryId($enquiry_form_id);

$closeLeadDetails = getCloseLeadByEnquiryId($enquiry_form_id);
 
$isBoughtVariable = $enquiryDetails['is_bought'];
 
$priceAndQuantityDetails= getRelSubCatEnquiryFromEnquiryId($enquiry_form_id);
 
$notes = getNotesByEnquiryId($enquiry_form_id);

$booking_id = getBookingFormByEnquiryId($enquiry_form_id);
 
?>


<div class="insideCoreContent adminContentWrapper wrapper">

<?php 
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert no_print  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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

<div class="addDetailsBtnStyling no_print">




<?php if(!$closeLeadDetails && ($isBoughtVariable==0 || $isBoughtVariable==3)) { ?>

<?php
if(show_booking_form == 1)
{
?>
<a href="supplier_email/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Supplier Email" class="btn btn-warning" />
</a>

<?php
}
?>

<a href="close_lead/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Close Lead" class="btn btn-warning" />
</a>

<a href="follow_up/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Follow Up" class="btn btn-warning" />
</a>

<!-- <a href="visit/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+<?php echo MEETING_GLOBAL_VAR; ?>" class="btn btn-warning" />
</a> -->

<a href="note/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Add a Note" class="btn btn-warning" />
</a>

<?php } else if($isBoughtVariable==1) { ?>

 <div class="alert alert-success">
  Lead converted in to a customer!
</div>

<a href="note/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Add a Note" class="btn btn-warning" />
</a>

<a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=addRemainder&id=<?php echo $enquiry_form_id; ?>">
<input type="button" value="+Add/View Reminder" class="btn btn-warning" />
</a>

<?php
if(show_booking_form == 1)
{
?>

<a href="<?php echo WEB_ROOT; ?>admin/customer/booking_form?id=<?php echo $enquiry_form_id; ?>">
<input type="button" value="+Booking Form" class="btn btn-success" />
</a>

<?php
}
?>


<?php } else if($isBoughtVariable['is_bought']==2) { ?>
<div class="alert alert-danger">
  Lead did not convert in to a customer!
</div>


<a href="note/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Add a Note" class="btn btn-warning" />
</a>
<?php } ?>

<a href="quotation/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Generate Quotation" class="btn btn-warning" />
</a>

<a href="invoice/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="+Generate Invoice" class="btn btn-warning" />
</a>

<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=customerDetails&id=<?php echo $customer_id ?>">
<input type="button" value="View Customer Profile" class="btn btn-success" />
</a>


<?php
$admin_rights=$_SESSION['EMSadminSession']['admin_rights'];

if(ASSIGN_TO == 1)
{
	?>
<a href="assign_to/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="Assign Lead To" class="btn btn-warning" />
</a>
<?php	
}

else if((ASSIGN_TO == 0 && (in_array(15, $admin_rights) || in_array(7,$admin_rights))))
{
?>

<a href="assign_to/index.php?id=<?php echo $enquiry_form_id ?>">
<input type="button" value="Assign Lead To" class="btn btn-warning" />
</a>

<?php
}
?>

<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=deleteEnquiry&lid=<?php echo $enquiry_form_id ?>&state=<?php echo $customer_id ?>">
 <button title="Delete this Enquiry" class="btn splEditBtn editBtn btn-danger">Delete Enquiry</button>
 </a>

</div>

<div class="addDetailsBtnStyling no_print">

<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=sendAddressSMS&id=<?php echo $customer_id ?>&state=<?php echo $enquiry_form_id ?>">
<input type="button" value="Send Address" class="btn btn-warning" />
</a>

<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=addVisit&id=<?php echo $customer_id ?>&state=<?php echo $enquiry_form_id ?>">
<input type="button" value="Add Visit" class="btn btn-warning" />
</a>

<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=markImportant&id=<?php echo $customer_id ?>&state=<?php echo $enquiry_form_id ?>">
<input type="button" value="Mark Important" class="btn btn-success" />
</a>

<a href="index.php?view=addToEnquiryGroup&id=<?php echo $enquiry_form_id ?>">
<input type="button" value="Add To Group" class="btn btn-warning" />
</a>


</div>

<div class="detailStyling">

<h4 class="headingAlignment">Enquiry Status</h4>

<table class="insertTableStyling detailStylingTable">

<tr>
<td> Enquiry ID : </td>
				<td>
				<?php
                    echo $enquiryDetails['unique_enquiry_id'];      				
                  ?>          

                 </td>
</tr>

<tr>
<td>Current Lead Status : </td>
				<td>
				
                             <?php
							 if($isBoughtVariable==0)
							 {
							  echo "New Enquiry"; 
							 }
							 else if($isBoughtVariable==3)
							 {
								echo "Ongoing Enquiry"; 
							 }
							 else if($isBoughtVariable==1)
							 {
								echo "Successfully Closed Enquiry"; 
							 }
							 else if($isBoughtVariable==2)
							 {
								echo "Unsuccessfully Closed Enquiry"; 
							 }
							  ?>					
                            

                 </td>
</tr>


<tr>
<td> Successful/Total Enquiries : </td>
				<td>
				<?php
                 $tNumber = getNoOfEnquiriesForCustomerId($customer_id);
				 $sNumber = getNoOfSuccessfullEnquiriesForCustomerId($customer_id);  
				 echo $sNumber.'/'.$tNumber;           				
                  ?>          

                 </td>
</tr>

<tr>
<td> Group : </td>
				<td style="color:#33C">
				<?php
                   $groupNameDetailsArray = getEnquiryGroupNamesByEnquiryId($enquiry_form_id);
				  foreach($groupNameDetailsArray as $groupNameArray)
				  {
					echo $groupNameArray['enquiry_group_name']. ", ";  
				  }       				
                  ?>          

                 </td>
</tr>

</table>

</div>

<div class="detailStyling" style="min-height:250px;">

<h4 class="headingAlignment"> Interested <?php echo PRODUCT_GLOBAL_VAR; ?> Details </h4>


<table class="insertTableStyling">

<?php
$subCategory = getSubCatFromEnquiryId($enquiry_form_id);
if(count($subCategory)==1 && is_numeric($subCategory[0][0]))
{
foreach($subCategory as $subC)
{
$sub_cat_id=$subC['sub_cat_id'];
$subCatNameArray = getsubCategoryById($sub_cat_id);
$subCatName = $subCatNameArray['sub_cat_name'];

$quantity_id = $subC['quantity_id'];
$quantityDetails = getQuantityById($quantity_id);
$quantity = $quantityDetails['quantity'];


$unit_id = $subC['product_unit_id'];
$unitDetails = getUnitById($unit_id);
$unit_name = $unitDetails['unit_name'];

$price = $subC['customer_price'];


$attribute_type_names_array=getAttributeTypesForASubCatOfAnEnquiry($sub_cat_id,$enquiry_form_id);

?>

<tr>
<td class="firstColumnStyling">
<b style="font-family:myFontBold"><?php echo PRODUCT_GLOBAL_VAR; ?> :</b> 
</td>

<td>
                             <?php echo $subCatName; ?>					
                          
</td>
</tr>
<?php
foreach($attribute_type_names_array as $attribute_type_names)
{

?>

<tr>
<td class="firstColumnStyling">
<b style="font-family:myFontBold"> 
<?php  echo  $attribute_type_names['attribute_type']. " : ";	?>
</b>
 
</td>
  
<td>
  <?php echo  $attribute_type_names['attribute_names_string'];	?>                      			
                          
</td>
</tr>

<?php
}
?>

<?php if(defined('SHOW_QUANTITY') && SHOW_QUANTITY==1) { ?>
<tr>
<td class="firstColumnStyling">
<b style="font-family:myFontBold"> <?php echo QUANTITY_GLOBAL_VAR; ?>:</b> 
</td>

<td>
                             <?php 
							 
							  
							  
							 
							 echo $quantity; 
							 
							 ?>					
                          
</td>
</tr>
<?php  } ?>
<tr>
<td class="firstColumnStyling">
<b style="font-family:myFontBold">Estimated Price : </b>
</td>

<td>
                              <?php 
							  
							  echo $price. " ". $unit_name;
							   ?>						
                          
</td>
</tr>

<?php
}
}
else if(count($subCategory)>1 && is_numeric($subCategory[0][0]))
{
?>
<tr>
<td >
	<table>
    	<tr>
        	<th align="left" style="font-family:myFontBold"><b><?php echo PRODUCT_GLOBAL_VAR; ?></b></th>
            <th align="left" style="font-family:myFontBold"><b> Extra Details </b></th>
            <th align="left" style="font-family:myFontBold"><?php echo QUANTITY_GLOBAL_VAR; ?></th>
            <th align="left" style="font-family:myFontBold">Price/unit</th> 
        </tr>
        
  
<?php	
foreach($subCategory as $subC)
{
$sub_cat_id=$subC['sub_cat_id'];
$subCatNameArray = getsubCategoryById($sub_cat_id);
$subCatName = $subCatNameArray['sub_cat_name'];

$quantity_id = $subC['quantity_id'];
$quantityDetails = getQuantityById($quantity_id);
$quantity = $quantityDetails['quantity'];

$price = $subC['customer_price'];

$attribute_type_names_array=getAttributeTypesForASubCatOfAnEnquiry($sub_cat_id, $enquiry_form_id);

?>

<tr>

<td width="160px;" style="padding-left:0;">
                             <?php echo $subCatName; ?>
                          
</td>

<td width="200px;" style="padding-left:0;">
  <?php
  
  if(is_array($attribute_type_names_array))
  {
  foreach($attribute_type_names_array as $attribute_type_names)
  {
  ?>
<u> <?php echo  $attribute_type_names['attribute_type']; ?> </u> <?php echo " : ". $attribute_type_names['attribute_names_string'];?> <br />
  <?php
  }
  }
  else
  {
	echo "NA";  
  }
  ?>                           					
                          
</td>


<td width="120px;" style="padding-left:0;">
                             <?php 
							 echo $quantity; 
							 ?>					
                          
</td>


<td width="120px;" style="padding-left:0;">
                              <?php 
							  
							  echo $price;
							   ?>						
                          
</td>

</tr>

<?php
}	
?>
</table>
</td>
</tr>
<?php } ?>
<tr>
	<td></td>
  <td class="no_print"> 
  
            
 <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editProducts&lid='.$enquiry_form_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             
            </td>
            
</tr>  

</table>
</div>

<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment">Customer Details</h4>

<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Customer Name : 
</td>

<td>

                             <?php
							 if(SHOW_PREFIX == 1)
							 {
							  $prefix_id = $customerDetails['prefix_id'];
							  
							  if($prefix_id !=0)
							  {
							  
							  $prefixDetails = getPrefixById($prefix_id);
							  
							  $customer_prefix = $prefixDetails['prefix'];
							  
							  echo $customer_prefix. " ".$customerDetails['customer_name']; 
							  }
							  else
							  echo $customerDetails['customer_name']; 
							 }
							  else
							  echo $customerDetails['customer_name']; 
							  
							  ?>					
                            
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


<?php
if(SHOW_AREA == 1)
{
	
?>

<tr>
<td>Customer Area : </td>
<td>

                             <?php  
							 
							 
							 if($extraCustomerDetails['area_id'] == -1 || $extraCustomerDetails['area_id'] == NULL)
							 {
							   echo "NA";	 
							 }
							 else
							 {
							 $areaDetails = getAreaByID($extraCustomerDetails['area_id']);
							 echo $areaDetails['area_name'];
							 }
							 ?>					
                          
</td>
</tr>


<?php
}
?>

<tr>
	<td></td>
  <td class="no_print"> 

            
  <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&lid='.$customer_id.'&state='.$enquiry_form_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            

</table>
</div>




<div class="detailStyling" style="min-height:170px;">

<h4 class="headingAlignment">Enquiry Details</h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
 Enquiry Type : 
</td>

<td>
                             <?php 
							 if($enquiryDetails['customer_type_id']==NULL)
							 {
								echo "Not Available"; 
							}
							else
							{
							 $customerTypeId = $enquiryDetails['customer_type_id'];
							 $customerTypeDetails = getCustomerTypeById($customerTypeId);
							 echo $customerTypeDetails['customer_type']; 
							}
							 ?>					
                             
</td>
</tr>

<?php
if($enquiryDetails['customer_type_id']==3)
{
?>

<tr>

<td class="firstColumnStyling">
Refrence Name : 
</td>

<td>
                             <?php 
							 $refrence_details = getRefrenceForEnquiryId($enquiry_form_id);
							 if($refrence_details['refrence_name'] != NULL)
							 {
							   echo $refrence_details['refrence_name'];	 
							 }
							 ?>					
                             
</td>
</tr>

<?php
}
?>


<tr>

<td class="firstColumnStyling">
Customer Budget : 
</td>

<td>
                             <?php 
							 if($enquiryDetails['budget']==0)
							 {
								echo "Not Available"; 
							}
							else
							{
							 echo $enquiryDetails['budget']; 
							}
							 ?>					
                             
</td>
</tr>


<tr>
<td >
Discussion : 
</td>

<td style="max-width:300px;" class="breakClass">

                             <?php 
							 
							 $discussion = $enquiryDetails['enquiry_discussion'];
							 
							 if(!validateForNull($discussion))
								 {
									echo "No Discussion Available!"; 
								 }
							 
							 echo $discussion; 
							 
							 ?>					
                            
</td>
</tr>




<tr>
<td>1st Follow Up Date : </td>
				<td>
				         
                             
                             
                             <?php
							 
							 if(date('d/m/Y H:i:s', strtotime($enquiryDetails['follow_up_date']))=="01/01/1970")
							 {
								echo "Reminder not set."; 
							  }
							  else
							  {
							  echo date('d/m/Y H:i:s', strtotime($enquiryDetails['follow_up_date']));
							  }
							  
							 ?>		
                            

                 </td>
</tr>

<tr>
<td>
Date of Enquiry : 
</td>

<td>

                             	
                             <?php echo date('d/m/Y H:i:s',strtotime($enquiryDetails['enquiry_date']))?>				
                             </td>
</tr>


<tr>
<td>Enquiry Added By : </td>
				          <td>
   
                             <?php 
							  $adminUserID = $enquiryDetails['created_by']; 
							  $adminUserDetails = getAdminUserByID($adminUserID);
							  echo $adminUserDetails['admin_name'];
							  ?>
                            </td>
</tr>


<tr>
<td>Enquiry Currently Handled By : </td>
				          <td>
   
                             <?php 
							  $holderAdminID = $enquiryDetails['current_lead_holder']; 
							  $adminUserDetails = getAdminUserByID($holderAdminID);
							  echo $adminUserDetails['admin_name'];
							  ?>
                            </td>
</tr>

<?php
if(SHOW_AREA == 1)
{
	
?>

<tr>
<td> KM Travelled : </td>
<td>

                             <?php  
							 
							 
							 
							 $kmDetails = getKMByEnquiryID($enquiry_form_id);
							 echo $kmDetails['km'];
							 
							 ?>					
                          
</td>
</tr>

<?php
}
?>



 <tr>
	<td></td>
  <td class="no_print"> 
  
  <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editEnquiry&lid='.$enquiry_form_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            
           
            
 </table>
</div>

<?php
if(!validateForNull($followUpDetails))
{
}
else
{  
$i=1;
foreach($followUpDetails as $followUpDetail) { 

$follow_up_id = $followUpDetail['follow_up_id'];


?>

<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment">

Follow Up Details [<?php echo $i++;  ?>]
</h4>
<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Follow Up Date : 
</td>

<td>

                             	
                             <?php echo date('d/m/Y H:i:s',strtotime($followUpDetail['next_follow_up_date']))?>					
                            
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Follow Up Type : 
</td>

<td>

                             	
                             <?php  
							 $follow_up_type_id = $followUpDetail['follow_up_type_id'];
							 if($follow_up_type_id!=NULL)
							 {
							 $follow_up_type_details = getFollowUpTypeById($follow_up_type_id);
							 echo $follow_up_type_details['follow_up_type'];
							 }
							 else
							 {
								 echo "-";
							 }
							 
							 ?>					
                            
</td>
</tr>


<tr>
<td>Discussion : </td>
<td>

                             <?php 
							 
							$followUpDiscussion = $followUpDetail['discussion']; 
							 if(!validateForNull($followUpDiscussion))
								 {
									echo "No Discussion Available!"; 
								 } 
							 echo $followUpDiscussion;
							 ?>					
                          
</td>
</tr>

<tr>
<td>Handled By : </td>
<td>

                             <?php  
                             $adminId = $followUpDetail['created_by'];
							 $adminNameArray = getAdminUserByID($adminId);
							 $adminName = $adminNameArray['admin_name'];
							 echo $adminName; 
?>					
                          
</td>
</tr>

<tr>
<td>Date Added : </td>
<td>

                            <?php echo date('d/m/Y H:i:s',strtotime($followUpDetail['date_added']))?>				
                          
</td>
</tr>
<?php
  $followUpId = $followUpDetail['follow_up_id'];
 
?>
 
	<td></td>
  <td class="no_print"> 
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editFollwUpDetails&id='.$follow_up_id; ?>&lid=<?php echo $enquiry_form_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             
             
  <a href="<?php echo WEB_ROOT ?>admin/customer/follow_up/index.php?action=delete&lid=<?php echo $followUpId ?>&state=<?php echo $enquiry_form_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button>
  </a>
             
            
             
            </td>
</tr>            

</table>
</div>

<?php
}}
?>

<?php
if(!validateForNull($visitDetails))
{
}
else
{  
$i=1;
foreach($visitDetails as $visitDetail) { 

$visit_id = $visitDetail['visit_id'];


?>

<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment">

<?php echo MEETING_GLOBAL_VAR; ?> Details [<?php echo $i++;  ?>]
</h4>
<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
<?php echo MEETING_GLOBAL_VAR; ?> Date : 
</td>

<td>

                             	
                             <?php echo date('d/m/Y',strtotime($visitDetail['visit_date']))?>					
                            
</td>
</tr>


<tr>
<td> <?php echo MEETING_GLOBAL_VAR; ?> Discussion : </td>
<td>

                             <?php 
							 
							$visitDiscussion = $visitDetail['visit_discussion']; 
							 if(!validateForNull($visitDiscussion))
								 {
									echo "No Discussion Available!"; 
								 } 
							 echo $visitDiscussion;
							 ?>					
                          
</td>
</tr>

<tr>
<td>Handled By : </td>
<td>

                             <?php  
                             $adminId = $visitDetail['created_by'];
							 $adminNameArray = getAdminUserByID($adminId);
							 $adminName = $adminNameArray['admin_name'];
							 echo $adminName; 
?>					
                          
</td>
</tr>

<tr>
<td>Date Added : </td>
<td>

                            <?php echo date('d/m/Y H:i:s',strtotime($visitDetail['date_added']))?>				
                          
</td>
</tr>
<?php
  $visitId = $visitDetail['follow_up_id'];
 
?>
 
	<td></td>
  <td class="no_print"> 
            
            <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editFollwUpDetails&id='.$visitId; ?>&lid=<?php echo $enquiry_form_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             
             
  <a href="<?php echo WEB_ROOT ?>admin/customer/follow_up/index.php?action=delete&lid=<?php echo $visitId ?>&state=<?php echo $enquiry_form_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button>
  </a>
             
            
             
            </td>
</tr>            

</table>
</div>

<?php
}}
?>




<?php

if(date('Y-m-d',strtotime($enquiryDetails['enquiry_close_date']))=='1970-01-01')
{
}
else
{  

?>

<?php 
							   
								if($isBoughtVariable!=0)
								{
									
							 
							 ?>	

<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment">

Lead Closing Deatails
</h4>
<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Status : 
</td>

<td>

                             	
                             <?php 
							   
								if($isBoughtVariable==2)
								{
									echo not_booked;
								}
								else if($isBoughtVariable==1)
								{
									echo booked;
								}
							 
							 ?>					
                            
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Enquiry Closing Date : 
</td>

<td>

           <?php echo date('d/m/Y H:i:s',strtotime($enquiryDetails['enquiry_close_date']))?>                  	
                             				
                            
</td>
</tr>

<tr>
<td>Enquiry Closed By : </td>
<td>

                             <?php  
                             $adminId = $enquiryDetails['enquiry_closed_by'];
							 $adminNameArray = getAdminUserByID($adminId);
							 $adminName = $adminNameArray['admin_name'];
							 echo $adminName; 
?>					
                          
</td>
</tr>

<?php
if($enquiryDetails['is_bought']==1)
{
	

?>

<tr>
<td> <?php echo tour_departure_date ?> : </td>
<td>

                             <?php  
                           
							 $sale_date = date('d/m/Y', strtotime($enquiryDetails['purchase_date']));
							 if($sale_date=="01/01/1970")
							 {
							   echo "NA";	 
							 }
							 else
							 {
							   echo $sale_date;	 
							 }
							 
							 ?>					
                          
</td>
</tr>

<tr>
<td> <?php echo tour_ending_date ?> </td>
<td>

                             <?php  
                           
							 $tour_ending_date = date('d/m/Y', strtotime($enquiryDetails['tour_ending_date']));
							 if($tour_ending_date=="01/01/1970")
							 {
							   echo "NA";	 
							 }
							 else
							 {
							   echo $tour_ending_date;	 
							 }
							 
							 ?>					
                          
</td>
</tr>

<?php
}
?>

<?php
if($enquiryDetails['is_bought']==2)
{
	

?>

<tr>
<td>Reason to Decline: </td>
<td>

                             <?php 
							 
							 $declineId = $closeLeadDetails['decline_id'];
							 $reasonarray = getReasonById($declineId);  
							 $reason = $reasonarray['decline_reason'];
							 echo $reason;
							 
							 ?>					
                          
</td>
</tr>



<tr>
<td>Description : </td>
<td>

                            <?php echo $closeLeadDetails['discussion']; ?>				
                          
</td>
</tr>

<?php
}
?>

 
	<td></td>
  <td class="no_print"> 
  
  <a href="<?php echo WEB_ROOT ?>admin/customer/close_lead/index.php?view=editCloseLead&lid=<?php echo $enquiry_form_id ?>"><button title="Edit this entry" class="btn delBtn"><span class="delete">E</span></button>
  </a>
             
             
  <a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=deleteLeadClose&lid=<?php echo $enquiry_form_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button>
  </a> 
            
            </td>
</tr>            

</table>
</div>

<?php
}
}
?>


<?php
if(!validateForNull($notes))
{
}
else
{  
$i=1;
foreach($notes as $note) { 

?>

<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment">

Note Details [<?php echo $i++;  ?>]: 
</h4>
<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Note : 
</td>

<td>

                             	
               <?php echo $note['note'];  ?>					
                            
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Taken on Date : 
</td>

<td>

                             	
               
               <?php echo date('d/m/Y H:i:s',strtotime($note['date_added']))?>				
                            
</td>
</tr>



 
<td></td>
  <td class="no_print"> 
            
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editNote&id='.$note['note_id'].'&lid='.$enquiry_form_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             <a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteNote&id='.$note['note_id'].'&lid='.$enquiry_form_id ?>"><button title="Delete this entry" class="btn splEditBtn editBtn"><span class="delete">X</span></button></a>
            </td>
</tr>            

</table>
</div>

<?php
}}
?>

<hr class="firstTableFinishing" />
</div>
<div class="clearfix"></div>