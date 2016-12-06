<?php
if(!isset($_GET['id']))
header("Location: index.php");

$vehicle_id=$_GET['id'];
$cusomter_id = getCustomerIDFromVehicleId($vehicle_id);
$customer=getCustomerDetailsByCustomerId($cusomter_id);

if(is_array($customer) && $customer!="error")
{
	$vehicle=getVehicleById($vehicle_id);
	$vehicle_model = getVehicleModelById($vehicle['model_id']);
	$insurance = getLatestInsuranceDetailsForVehicleID($vehicle['vehicle_id']);
	
	$proof_details=getVehicleProofById($vehicle_id);
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
}



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


<div class="detailStyling">
<h4 class="headingAlignment"> Vehicle Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td width="160px">Vehicle Company : </td>
<td><?php  echo $vehicle_model['company_name']; ?> </td>
</tr>

<tr>
<td>Vehicle Model : </td>
				<td>
					<?php echo $vehicle_model['model_name']; ?>
                            </td>
</tr>

<tr>
       <td>Vehicle Condition :</td>
           
           
        <td>
            <?php if($vehicle['vehicle_condition']==1) echo "NEW"; else echo "OLD"; ?>
        </td>
 </tr>
 <tr>
       <td>Vehicle Condition :</td>
           
           
        <td>
            <?php if( $vehicle['is_sold_by_customer']==1) echo "YES"; else echo "NO"; ?>
        </td>
 </tr>
<tr>
<td>Financer / Dealer / Broker : </td>
<td><?php   if(is_numeric($vehicle['ledger_id'])) echo getLedgerNameFromLedgerId($vehicle['ledger_id']); else echo "NA"; ?> </td>
</tr>

 
 <tr>
<td>Vehicle Model : </td>
				<td>
					<?php if($vehicle['vehicle_model']!=1970) echo $vehicle['vehicle_model']; else echo "NA"; ?>
                            </td>
</tr>

<tr>
<td>Vehicle Type : </td>
				<td>
					<?php $vehicle_type = getVehicleTypeById($vehicle_model['vehicle_type_id']); echo $vehicle_type['vehicle_type']; ?>	
                </td>
</tr>
 
<tr>
<td class="firstColumnStyling">
Registration Number : 
</td>

<td>
<?php  $reg_no=$vehicle['vehicle_reg_no']; $reg_no=strtoupper($reg_no); echo $reg_no;?>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
 Registration Date : 
</td>

<td>
<?php  $reg_date=date('d/m/Y',strtotime($vehicle['vehicle_reg_date'])); if($reg_date!="01/01/1970") echo $reg_date; else echo "NA"; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Engine Number : 
</td>

<td>
<?php echo $vehicle["vehicle_engine_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Chasis Number : 
</td>

<td>
<?php echo $vehicle["vehicle_chasis_no"]; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Fitness Exp Date: 
</td>

<td>
<?php $fitness_date=date('d/m/Y',strtotime($vehicle["fitness_exp_date"])); if($fitness_date!="01/01/1970") echo $fitness_date; else echo "NA"; ?>

</td>
</tr>

<tr>
<td class="firstColumnStyling">
Permit Exp Date : 
</td>

<td>
<?php $permit_date=date('d/m/Y',strtotime($vehicle["permit_exp_date"])); if($permit_date!="01/01/1970") echo $permit_date; else echo "NA"; ?>

</td>
</tr>
<tr>
<td> Opening Balance  : </td>
<td> <?php echo $vehicle['opening_balance']; if($vehicle['opening_cd']>=0) echo " DR"; else echo " CR"; ?> </tr>
</tr>
<tr>
	<td></td>
  <td class="no_print">
            
          <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editVehicle&id='.$vehicle_id.'&state='.$cusomter_id; ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$cusomter_id ?>"><button title="Back" class="btn btn-success">Back</button></a>
            </td>
</tr>   

</table>

<?php
$gh=0;
if(is_array($proof_details) && count($proof_details)>0)
{
foreach($proof_details as $proof) 
{
	
	?>

<h4 class="headingAlignment">Proof <?php echo ++$gh; ?></h4> 



<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">
<tr>

<td class="firstColumnStyling">
 Proof Type : 
</td>

<td>

                             <?php echo $proof['vehicle_document_type']; ?>					
                            
</td>
</tr>

<tr>
<td>
Proof No : 
</td>

<td>

                             <?php echo $proof['vehicle_document_no']; ?>					
                            
</td>
</tr>

<?php $imgArray=getVehicleProofimgByProofId($proof['vehicle_document_id']); 
if(is_array($imgArray) && count($imgArray)>0)
{
foreach($imgArray as $img)
{
	 $ext = substr(strrchr($img['vehicle_document_img_href'], "."), 1); 	
  if($ext=="jpg" || $ext=="JPG" || $ext=="png" || $ext=="PNG" || $ext=="gif" || $ext=="GIF" || $ext=="jpeg" || $ext=="JPEG")
  { 
?>

<tr>
<td>Image : </td>
				<td>

                             <a href="<?php echo WEB_ROOT."images/vehicle_proof/".$img['vehicle_document_img_href']; ?>"><img style="height:100px;" src="<?php echo WEB_ROOT."images/vehicle_proof/".$img['vehicle_document_img_href']; ?>" /></a>
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

                             <a style="text-decoration:underline;color:#00F;" href="<?php echo WEB_ROOT."images/vehicle_proof/".$img['vehicle_document_img_href']; ?>">Proof link</a>
                            </td>
</tr>
<?php	  
	  }
  
 } } ?>

<tr>
	<td></td>
  <td class="no_print">
            
             <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delVehicleProof&id='.$vehicle_id.'&state='.$proof['vehicle_document_id']; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
  </td>          
</tr>        

</table>
<?php } } ?>
</div>

<?php if(is_array($insurance)) {?>
<div class="detailStyling">
<h4 class="headingAlignment"> Insurance Details </h4>


<table id="insertGuarantorTable" class="insertTableStyling detailStylingTable">

<tr>
<td>Insurance Company : </td>
				<td>
					<?php  $comp=getInsuranceCompanyById($insurance['insurance_company_id']); echo $comp[1]; ?>
                            </td>
</tr>

<tr>
<td>Insurance Issue Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($insurance['insurance_issue_date'])); ?>
                            </td>
</tr>

<tr>
<td>Insurance Expiry Date : </td>
				<td>
					<?php echo date('d/m/Y',strtotime($insurance['insurance_expiry_date'])); ?>
                            </td>
</tr>

<tr>

    <td class="firstColumnStyling">
    Isurance Declared Value (IDV) : 
    </td>
    
    <td>
    <?php echo "Rs. ".number_format($insurance['idv']); ?>
    </td>
</tr>

<tr>

    <td class="firstColumnStyling">
    Premium : 
    </td>
    
    <td>
     <?php echo "Rs. ".number_format($insurance['insurance_premium']); ?>
    </td>
</tr>

<tr>
	<td></td>
  <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/vehicle/insurance/?view=details&id='.$vehicle_id.'&state='.$cusomter_id ?>"><button title="View this entry" class="btn viewBtn"><span class="view">View All</span></button></a>
   </td>        
</tr>  


</table>
</div>
<?php } ?>
</div>
<div class="clearfix"></div>