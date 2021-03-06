<?php if(!isset($_GET['id']) || !isset($_GET['state']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}
$file_id=$_GET['id'];
$customer_id=$_GET['state'];
$vehicle_id=getVehicleIdByFileId($file_id);
if(!checkForNumeric($vehicle_id))
{
	$_SESSION['ack']['msg']="Please add a Vehicle to add Insurance!";
	$_SESSION['ack']['type']=4; // 4 for error
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
	}
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Add Insurance Details </h4>
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
<form onsubmit="return submitInsurance();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurInsurance()">

<input name="customer_id" value="<?php echo $customer_id; ?>" type="hidden" />
<input name="file_id" value="<?php echo $file_id; ?>" type="hidden" />
<input name="vehicle_id" value="<?php echo $vehicle_id; ?>" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="220px">Insurance Company : </td>
				<td>
					<select id="insurance_company" name="insurance_company_id">
                        <option value="-1" >--Please Select Company--</option>
                        <?php
                            $companies = listInsuranceCompanies();
                            foreach($companies as $super)
                              {
                             ?>
                             
                             <option value="<?php echo $super['insurance_company_id'] ?>"><?php echo $super['insurance_company_name'] ?></option					>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>

<tr>
<td>Insurance Issue Date : </td>
				<td>
					<input placeholder="Click to select Date!" type="text" id="issue_date" name="issue_date" class="datepicker1 date"  onchange="onChangeDate(this.value,this)" /><span class="ValidationErrors contactNoError">Please select a date!</span>
                            </td>
</tr>

<tr>
<td>Insurance Expiry Date : </td>
				<td>
					<input placeholder="Click to select Date!"  type="text" id="exp_date" name="exp_date" class="datepicker2 date"  onchange="onChangeDate(this.value,this)" /><span class="ValidationErrors contactNoError">Please select a date!</span>
                            </td>
</tr>


<td class="firstColumnStyling">
Isurance Declared Value (IDV) : 
</td>

<td>
<input type="text" name="idv" id="idv" placeholder="Only Digits!"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Premium : 
</td>

<td>
 <input type="text"  name="premium" id="premium" placeholder="Only Digits" />
</td>
</tr>


<!-- for regenreation purpose Please donot delete -->



<tr id="vehicleProofImgTr">
<td>
Insurance Image : 
</td>
<td>
<input type="file" name="" class="customerFile"  /><br /> - OR - <br /><input type="button" name="scanProof" class="btn scanBtn" value="scan" /><input type="button" value="+" class="btn btn-primary addscanbtnGuarantor"/>
</td>
</tr> 

<!-- end of used for regeneration -->
</table>

<table style="margin-top:0px;margin-bottom:10px;">
<tr>
<td width="250px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Image" id="addInsuranceProofBtn"/></td>
</tr>     
</table>

<table>
<tr>
<td width="250px;"></td>
<td>
<input type="submit" value="Add Insurance Details" class="btn btn-warning">
</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>