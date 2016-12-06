<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$customer_id=$_GET['id'];


$customerDetails = getCustomerById($customer_id);


$contactNumbers=getCustomerContactNo($customer_id);

$extraCustomerDetails = getExtraCustomerDetailsById($customer_id);

$notes = getNotesByCustomerId($customer_id);


$memberDetails = getMembersByCustomerId($customer_id);
$proof_details=getCustomerProofByCustomerId($customer_id); 



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


<?php if(!$extraCustomerDetails) { ?>

<a href="customer_extra_details/index.php?view=extraCusDetails&id=<?php echo $customer_id ?>">
<input type="button" value="+Add More Details" class="btn btn-success" />
</a>


<?php } ?>

<a href="index.php?id=<?php echo $customer_id ?>">
<input type="button" value="+Add Enquiry" class="btn btn-warning" />
</a>

<a href="index.php?id=<?php echo $customer_id."&status=1" ?>">
<input type="button" value="+Add Previous Enquiry" class="btn btn-warning" />
</a>

<!--<a href="anotherDirectInvoice/index.php?id=<?php echo $customer_id ?>">
<input type="button" value="+Add Invoice" class="btn btn-warning" />
</a>-->

<a href="add_member/index.php?id=<?php echo $customer_id ?>">
<input type="button" value="+Add Member" class="btn btn-warning" />
</a>

<a href="index.php?view=addProof&id=<?php echo $customer_id ?>">
<input type="button" value="+Add Proof" class="btn btn-warning" />
</a>

<a href="index.php?view=addToGroup&id=<?php echo $customer_id ?>">
<input type="button" value="+Add To Group" class="btn btn-success" />
</a>

<a href="customerNote/index.php?id=<?php echo $customer_id ?>">
<input type="button" value="+Add a Note" class="btn btn-warning"/>
</a>

<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=deleteCustomer&lid=<?php echo $customer_id ?>">
 <button title="Delete this Customer" class="btn splEditBtn editBtn btn-danger">Delete Customer</button>
</a>

<!--

<a href="vehicle/index.php?id=<?php echo $customer_id ?>">
<input type="button" value="Add Vehicle" class="btn btn-success" />
</a>
-->
 
</div>




<div class="detailStyling" style="min-height:170px;z-index:1000">

<h4 class="headingAlignment">Basic Customer Details</h4>

<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling" width="130px;">
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

<tr>
<td> Group : </td>
				<td>
				<?php
                   $groupNameDetailsArray = getCustomerGroupNamesByCustomerId($customer_id);
				  foreach($groupNameDetailsArray as $groupNameArray)
				  {
					echo $groupNameArray['customer_group_name']. ", ";  
				  }       				
                  ?>          

                 </td>
</tr>

<tr>
	<td></td>
  <td class="no_print"> 

            
  <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&lid='.$customer_id.'&redirect=one' ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
            </td>
</tr>            

</table>
</div>






<?php
if(!validateForNull($extraCustomerDetails))
{
}
else
{  
?>

<div class="detailStyling" style="min-height:170px;z-index:1000">

<h4 class="headingAlignment">

 Other Details 
</h4>
<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling" width="130px">
DOB : 
</td>

<td>

                             	
                             <?php 
							 $dob = date('d/m/Y',strtotime($extraCustomerDetails['customer_dob']));
							 
							 if($dob=="01/01/1970")
							 {
								 echo "DOB Not Available!";
							 }
							 else
							 {
								echo $dob; 
							 }
							 
							 ?>					
                            
</td>
</tr>

<tr>
<td> Profession : </td>
<td>

                             <?php  
                                   $professionId = $extraCustomerDetails['profession_id'];
								   
								   if($professionId==NULL)
								   {
									 echo "Not available";   
								   }
								   else
								   {
								   $professionDetails = getProfessionByID($professionId);
								   echo $professionDetails['profession'];
								   }
                              ?>					
                          
</td>
</tr>

<tr>
<td> Data From : </td>
<td>

                             <?php  
                                   $dataFromId = $extraCustomerDetails['data_from_id'];
								   
								   if($dataFromId==NULL)
								   {
									 echo "Not available";   
								   }
								   else
								   {
								   $dataFromDetails = getDataFromById($dataFromId);
								   echo $dataFromDetails['data_from'];
								   }
                              ?>					
                          
</td>
</tr>



<tr>
<td>Primary Address : </td>
<td>

                             <?php 
							 $address =  $extraCustomerDetails['customer_address'];
  							   $address = str_replace("\\r\\n"," ", $address);
  							   echo $address;  
							 
							 
							 ?>					
                          
</td>
</tr>

<tr>
<td>Secondary Address : </td>
<td>

                             <?php 
							 
							    $address2 =  $extraCustomerDetails['secondary_address'];
  							   $address2 = str_replace("\\r\\n"," ", $address2);
							 
							    if($address2==NULL)
								   echo "NOT AVAILBALE";
								   else
								   {
								   
								   echo $address2;
								   } 
							 
							 
							 ?>					
                          
</td>
</tr>

<tr>
<td>City : </td>
<td>

                             <?php  
                                   $cityId = $extraCustomerDetails['city_id'];
								   if($cityId==NULL)
								   echo "NOT AVAILBALE";
								   else
								   {
								   $cityDetails = getCityByID($cityId);
								   echo $cityDetails['city_name'];
								   }
                              ?>					
                          
</td>
</tr>

<tr>
<td>Area : </td>
<td>

                             <?php  
                                   $areaId = $extraCustomerDetails['area_id'];
								   if($areaId==NULL)
								   echo "NOT AVAILBALE";
								   else
								   {
								   $areaDetails = getAreaByID($areaId);
								   echo $areaDetails['area_name'];
								   }
                              ?>					
                          
</td>
</tr>

<tr>
<td> Nationality : </td>
<td>

                             <?php  
                                   $nationality_id = $extraCustomerDetails['customer_nationality']; 
							 
							 if($nationality_id==1)
							 {
								   echo "Indian";
							 }
							 else if($nationality_id==0)
								   {
								   
								   echo "Other";
								   } 
								 ?>				
                          
</td>
</tr>





 
	<td></td>
  <td class="no_print"> 
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editExtraCustomerDetails&lid='.$customer_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button>
             </a>
             
             <a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=deleteExtraCustomerDetails&lid=<?php echo $customer_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button>
             </a>
             
            </td>
</tr>            

</table>
</div>

<?php
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

                             	
               <?php echo $note['customer_note'];  ?>					
                            
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
            
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomerNote&id='.$note['customer_note_id'].'&lid='.$customer_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             <a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteCustomerNote&id='.$note['customer_note_id'].'&lid='.$customer_id ?>"><button title="Delete this entry" class="btn splEditBtn editBtn"><span class="delete">X</span></button></a>
            </td>
</tr>            

</table>
</div>

<?php
}}
?>



<?php
if(!validateForNull($memberDetails))
{
}
else
{  
$i=1;
foreach($memberDetails as $memberDetail) { 

?>

<div class="detailStyling" style="min-height:170px">

<h4 class="headingAlignment">

 Member Details [<?php echo $i++;  ?>]
</h4>
<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td class="firstColumnStyling">
Member Name : 
</td>

<td>

         <?php echo $memberDetail['member_name'];  ?>	                    	
                            				
                            
</td>
</tr>

<tr>
<td> Gender : </td>
<td>

                             <?php 
							 
							  $gender = $memberDetail['gender']; 
							 
							  if($gender==-1)
							  {
							  echo "Not Specified";
							  }
							  
							  else if($gender==0)
							  {
							  echo "Male";
							  }
							  
							  if($gender==1)
							  {
							  echo "Female";
							  }
							  ?>
                          
</td>
</tr>

<tr>
<td>Relation : </td>
<td>

                             <?php 
							 
							  $id = $memberDetail['relation_id']; 
							  $relationDetails = getRelationById($id); 
							  echo $relationDetails['relation'];
							  
							  ?>
                          
</td>
</tr>




<tr>
<td>Email : </td>
<td>

                            <?php echo $memberDetail['member_email'];  ?>						
                          
</td>
</tr>

<tr>
<td>Contact No : </td>
<td>

                            <?php 
							 $nos = getMemberContactNo($memberDetail['member_id']);
							 foreach($nos as $no)
							 {
							    echo $no['member_contact_no'];	 
							 }
							?>						
                          
</td>
</tr>


<tr>
<td>DOB : </td>
<td>

                            <?php echo date('d/m/Y',strtotime($memberDetail['member_dob']))?>				
                          
</td>
</tr>
<?php
  $memberId = $memberDetail['member_id'];
 
?>
 
	<td></td>
  <td class="no_print"> 
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editMemberDetails&id='.$memberId ?>&lid=<?php echo $customer_id ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button>
             </a>
             
             
  <a href="<?php echo WEB_ROOT ?>admin/customer/index.php?action=deleteMember&lid=<?php echo $memberId ?>&state=<?php echo $customer_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button>
  </a>
             
            
             
            </td>
</tr>            

</table>
</div>

<?php
}}
?>


<?php
$gh=0;
if(is_array($proof_details) && count($proof_details)>0)
{
foreach($proof_details as $proof) 
{
	
	
	?>
<div class="detailStyling" style="min-height:170px">
<h4 class="headingAlignment" >Proof <?php echo ++$gh; ?></h4> 



<table id="" class="insertTableStyling detailStylingTable detailStyling">

<tr>

<td class="firstColumnStyling">
 Proof For : 
</td>

<td>

                             <?php 
							 $member_id = $proof['member_id'];
							 
							 
							 if($member_id == NULL)
							 {
				                echo $customerDetails['customer_name'];
							 }
							 
							 else 
							 {
								 
							$member_info	= getMemberById($member_id); 
						    $member_name = $member_info['member_name'];
							echo $member_name;
							 }
							 
							  ?>					
                            
</td>
</tr>


<tr>

<td class="firstColumnStyling">
 Proof Type : 
</td>

<td>

                             <?php echo $proof['proof_type']; ?>					
                            
</td>
</tr>

<tr>
<td>
Proof No : 
</td>

<td>

                             <?php echo $proof['customer_proof_no']; ?>					
                            
</td>
</tr>

<?php $imgArray=getCustomerProofimgByProofId($proof['customer_proof_id']); 
if(is_array($imgArray) && count($imgArray)>0)
{
foreach($imgArray as $img)
{
  $ext = substr(strrchr($img['customer_proof_img_href'], "."), 1); 	
  if($ext=="jpg" || $ext=="JPG" || $ext=="png" || $ext=="PNG" || $ext=="gif" || $ext=="GIF" || $ext=="jpeg" || $ext=="JPEG")
  { 
?>

<tr>
<td>Image : </td>
				<td>

                             <a href="<?php echo WEB_ROOT."images/customer_proof/".$img['customer_proof_img_href']; ?>" target="_blank"><img style="height:100px;" src="<?php echo WEB_ROOT."images/customer_proof/".$img['customer_proof_img_href']; ?>" /></a>
                            </td>
</tr>

 

<?php
  }
  else if($ext=="pdf" || $ext=="PDF")
  {
?>
<tr>
<td>Proof Link: </td>
				<td>

                             <a style="text-decoration:underline;color:#00F;" href="<?php echo WEB_ROOT."images/customer_proof/".$img['customer_proof_img_href']; ?>" target="_blank">Proof link</a>
                            </td>
</tr>
<?php	  
	  }
  
 } }?>

<tr>
	<td></td>
  <td class="no_print">
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delCustomerProof&id='.$customer_id.'&state='.$proof['customer_proof_id']; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
  </td>          
</tr>        

</table>
</div>
<?php } } ?>


 <?php
		
$enquiryDetails = getEnquiryByCustomerId($customer_id);
		
		
			
			if($enquiryDetails)
			{
			
?>
<div style="clear:both;"></div>
<h4 class="headingAlignment">Generated Enquiries</h4>
<div class="printBtnDiv no_print">
	<button class="printBtn btn"><i class="icon-print"></i> Print</button>
</div>
<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Enquiry Date</th>
            <th class="heading">Enquiry For(Product)</th>
            <th class="heading">Enquiry Status</th> 
            <th class="heading">Enquiry Managed By</th>
            
			           
            <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
    
    <?php
	
	$i=0;
		
			foreach($enquiryDetails as $enquiryDetail)
			{
			
			$enquiryDate = $enquiryDetail['enquiry_date'];
			
			$enquiry_form_id = $enquiryDetail['enquiry_form_id'];
			
			$subCategory = getSubCatFromEnquiryId($enquiry_form_id);
			
			
			$isBoughtVariable = $enquiryDetail['is_bought'];
			
			$leadHolder = $enquiryDetail['current_lead_holder'];
	 
	        $adminDetails = getAdminUserByID($leadHolder);
			
			$handled_by = $adminDetails['admin_name'];
	?>
        
       
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($enquiryDate))?></span>
            </td>
            
            <td>
            <?php	
				foreach($subCategory as $subC)
				{
				$sub_cat_id=$subC['sub_cat_id'];
				$subCatNameArray = getsubCategoryById($sub_cat_id);
				$subCatName = $subCatNameArray['sub_cat_name'];
			?>
            
             
             
             <span  class="editLocationName">
             <?php echo $subCatName; ?>
             </span>
             <br />
			<?php
				}
			?>
            </td>
            <td>
            <span  class="editLocationName">
			<?php 
			if($isBoughtVariable==0)
			{
			echo "New Enquiry";
			}
			else if($isBoughtVariable==3)
			{
				echo "Ongoing Enquiry";
			}
			else if($isBoughtVariable==1 || $isBoughtVariable==2)
			{
				echo "Closed Enquiry";
			}
			?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName"><?php echo $handled_by; ?></span>
            </td>
            
            
            
            
            
            
             <td class="no_print"> 
             <?php
			 $admin_session_id = $_SESSION['EMSadminSession']['admin_id'];
			
	        $his_member_id_array = getHisTeamMemberIdsForAnAdminId($admin_session_id);
	        
			
			
			if (in_array($leadHolder , $his_member_id_array))
			{
			?>
             <a href="<?php echo WEB_ROOT."admin/customer/index.php?view=details&id=".$enquiry_form_id?>">
             <button title="View this entry" class="btn viewBtn"><span class="view">V</span></button>
             </a>
             <?php
			}
		   ?>
            </td>
            
           
            
          
  
        </tr>
        
        <?php
			}
		?>
        
         </tbody>
    </table>
 </div>   
       <table id="to_print" class="to_print adminContentTable"></table> 


<?php
}


$customerDetails = getInvoiceCustomerByCustomerId($customer_id);
if($customerDetails)
{

?>



<h4 class="headingAlignment" style="margin-top:100px">Generated Invoices</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table  class="adminContentTable" style="margin-top:10px">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Invoice Date</th>
            <th class="heading">Invoice For(Product)</th>
            <th class="heading">Invoice Amount</th>            
             <th class="heading no_print btnCol" ></th>
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		$customerDetails = getInvoiceCustomerByCustomerId($customer_id);
		
		$i=0;
		if(is_array($customerDetails) && count($customerDetails)>0 && $customerDetails!="error")
		{
		foreach($customerDetails as $details)
		{
		
		$details['in_customer_id'];
		
		
		
		
			
			
			$in_customer_id = $details['in_customer_id'];
			
			$name = $details['in_customer_name'];
			
			$invoice_date = $details['invoice_date'];
			
			$subCategory = getInvoiceRelSubCatEnquiryFromInCustomerId($in_customer_id);
			
			
			
			
			
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td><span  class="editLocationName"><?php echo date('d/m/Y',strtotime($invoice_date))?></span>
            </td>
            
            <td>
            <?php	
				foreach($subCategory as $subC)
				{
				$sub_cat_id=$subC['sub_cat_id'];
				$subCatNameArray = getsubCategoryById($sub_cat_id);
				$subCatName = $subCatNameArray['sub_cat_name'];
			?>
            
             
             
             <span  class="editLocationName">
             <?php echo $subCatName; ?>
             </span>
             <br />
			<?php
				}
			?>
            </td>
            
            <td>
             <?php	
				foreach($subCategory as $sub)
				{
				$price=$sub['invoice_price'];
				
			?>
            
             
             
             <span  class="editLocationName">
             <?php echo $price; ?>
             </span>
             <br />
			<?php
				}
			?>
            </td>
            
            
            
            
             <td class="no_print"> <a href="<?php echo WEB_ROOT."admin/customer/invoice/index.php?view=invoiceFinal&id=$in_customer_id "?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
          
  
        </tr>
        <?php
		} }
		?>
        
         </tbody>
    </table>
   
    </div>
    <?php
}
	?>
    
    
    <?php
	$vehicleDetails = getVehicleDetailsByCustomerId($customer_id);
	
	
	if($vehicleDetails!="error")
	{
	?>
    
<h4 class="headingAlignment" style="margin-top:100px">Vehicle Details</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table  class="adminContentTable" style="margin-top:10px">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Vehicle Company</th>
            <th class="heading">Vehicle Model</th>
            <th class="heading">Registration Number</th> 
            <th class="heading">Registration Date</th>    
            <th class="heading">Insurance Issue Date</th>   
            <th class="heading">Insurance Expiry Date</th>    
                    
            <th class="heading no_print btnCol" ></th>
           <th class="heading no_print btnCol" ></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		
		
		$i=0;
		foreach($vehicleDetails as $vehicles)
		{
			
		   $vCompanyId = $vehicles['vehicle_company_id'];
		   $vCompanyDetails = getVehicleCompanyById($vCompanyId);
		   $companyName = $vCompanyDetails['vehicle_company_name'];
		   
		   
			$mId = $vehicles['vehicle_model_id'];
			$modelDetails = getVehicleModelById($mId);
			$modelName = $modelDetails['vehicle_model_name'];
			
		   $vehicle_id = $vehicles['vehicle_id'];
		   
		   $regNumber = $vehicles['vehicle_reg_no'];
		   
		   $model_year = $vehicles['vehicle_reg_date'];
		   
		   
		   $insuranceDetails = getLatestInsuranceDetailsForVehicleID($vehicle_id);
		
		   
		   
		  ?>
          <tr class="resultRow">
          
        	<td><?php echo ++$i; ?>
            </td>
            
            
            <td>
            <span  class="editLocationName">
			<?php echo $companyName; ?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php echo $modelName; ?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php echo $regNumber; ?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName">
             <?php echo date('d/m/Y',strtotime($model_year))?>
            </span>
            </td>
            
              
            <td>
            
             <?php if($insuranceDetails) echo date('d/m/Y',strtotime($insuranceDetails['insurance_start_date'])); else echo "NA"; ?>
            
            </td>
            
            
              
            <td>
           
             <?php if($insuranceDetails) echo date('d/m/Y',strtotime($insuranceDetails['insurance_end_date'])); else echo "NA"; ?>
            
            </td>
            
            
   
            <td class="no_print"> 
            <a href="<?php echo WEB_ROOT."admin/customer/vehicle/index.php?view=vehicleDetails&id=".$vehicle_id?>&lid=<?php echo $customer_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            
            
            <?php
			if($insuranceDetails)
			{
			?>
			<td class="no_print"> 
            
            <a href="insurance/index.php?view=insuranceDetails&id=<?php echo $vehicle_id ?>&lid=<?php echo $customer_id ?>">
            <input type="button" value="View Insurance" class="btn btn-warning" />
            </a>
            </td>
            <?php	
			}
			else
			{
			?>
            <td class="no_print"> 
            
            <a href="insurance/index.php?id=<?php echo $vehicle_id ?>&lid=<?php echo $customer_id ?>">
            <input type="button" value="Add Insurance" class="btn btn-warning" />
            </a>
            </td>
            <?php
			}
			?>
            
            
          
  
        </tr>
        <?php
		}
		?>
        
         </tbody>
    </table>
    </div>
    
    
 
    <?php
	}
	?>
    
    
    
       <table  class="to_print adminContentTable"></table> 
       

</div>

<div class="clearfix"></div>




			