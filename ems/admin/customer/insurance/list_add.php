<?php if(!isset($_GET['id']) || !isset($_GET['lid']))
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
$state = $_GET['state'];


$vehicle_id=$_GET['id'];


$vehicleDetails = getVehicleDetailsById($vehicle_id);

$reg_date = $vehicleDetails['vehicle_reg_date'];
$dateDiffernce = getDateDifference($reg_date);


$vehicle_model_id = $vehicleDetails['vehicle_model_id'];

$vehicleModelDetails = getVehicleModelById($vehicle_model_id);

$vehicle_type_id = $vehicleModelDetails['vehicle_type_id'];
$typeDetails = getVehicleTypeById($vehicle_type_id);
$vehicle_type = $typeDetails['vehicle_type'];

$vehicle_cc_id = $vehicleModelDetails['vehicle_cc_id'];
$ccDetails = getVehicleCCById($vehicle_cc_id);
$vehicle_cc = $ccDetails['vehicle_cc'];


$customer_id=$_GET['lid'];

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
<input name="vehicle_id" value="<?php echo $vehicle_id; ?>" type="hidden" />

<table class="insertTableStyling no_print">
<tr>

<td class="firstColumnStyling" width="250px">
Insurance Issue Date : 
</td>

<td >
<input type="text" id="datepicker" size="12" autocomplete="off"  name="insurance_issue_date" class="datepicker1 datepick" placeholder="Click to Select!" value=<?php $todayDate = getTodaysDate(); echo date('d/m/Y H:i:s',strtotime($todayDate)) ?>/><span class="customError DateError">Please select a date!</span>



</td>
</tr>

</table>

<h4 class="headingAlignment"> Count Insurance Premium </h4>

<table id="insertInsuranceTable" class="insertTableStyling no_print">

<tr>
<td width="250px">Insurance Company<span class="requiredField">* </span> : </td>
				<td>
					<select id="insurance_company_id" name="insurance_company_id" onchange="getInsuranceDetails(this.value, <?php echo $vehicle_id; ?>)">
                        <option value="-1" >--Please Select Company--</option>
                        <?php
                            $companies = listInsuranceCompanies();
                            foreach($companies as $super)
                              {
                             ?>
                             
                      <option value="<?php echo $super['insure_com_id'] ?>"><?php echo $super['insure_com_name'] ?></option>
                             <?php } ?>
                              
                         
                            </select> 
                            </td>
</tr>


<tr>
<td class="firstColumnStyling">
Third Party Rate <span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="rate" id="rate" placeholder="Only Digits!"/> &nbsp; %
</td>
</tr>


<tr>
<td class="firstColumnStyling">
Insurance Declared Value (IDV)<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="idv" id="idv" onchange="countPremium()" placeholder="Only Digits!"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
<span style="font-weight:900; border-bottom:1px solid #000"> Calculated Premium </span><span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="premium" id="premium" placeholder="Only Digits" />
</td>
</tr>


<tr>
<td class="firstColumnStyling">
 Discount <span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="discount" id="discount" onchange="countDiscount()" placeholder="Only Digits!" value="0"/> &nbsp; %
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Discounted Amount <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="dAmount" id="dAmount" placeholder="Only Digits" value="0" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
<span style="font-weight:900; border-bottom:1px solid #000"> Premium - Discount </span> <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="disAmount" id="disAmount" placeholder="Only Digits"/>
</td>
</tr>


<tr>
<td> CNG Details <span class="requiredField">* </span> : </td> 
<td>
<table><tr><td><input type="radio" name="cd" value="1"  onchange="toggleCNG(this.value)"   id="cng_toggle_yes"></td><td><label for="running">Yes</label></td></tr>
<tr><td>
<input type="radio" name="cd" value="0" id="cng_toggle_no" checked="checked" onchange="toggleCNG(this.value)"></td><td><label for="completed">No</label></td></tr></table></td>
</tr>

</table>

<div id="yesCNG" style="display:none;">
<h4 class="headingAlignment no_print" id="cngHeading"> CNG Details </h4>
<table class="insertTableStyling no_print">


<tr>
<td class="firstColumnStyling" width="250px">
CNG IDV <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="cngIDV" id="cngIDV" placeholder="Only Digits" value="0" onchange="countCNGPremium()" />
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Percentage <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="cngPercentage" id="cngPercentage" placeholder="Only Digits"
   value="<?php $cngDetails = getCNGById(1); echo $cngDetails['cng_value']; ?>"
  /> &nbsp; %
</td>
</tr>

<tr>
<td class="firstColumnStyling">
<span style="font-weight:900; border-bottom:1px solid #000"> Calculated CNG Premium </span> <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="cngPremium" id="cngPremium" placeholder="Only Digits"/>
</td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>

</table>
</div>

<table class="insertTableStyling no_print">

<tr>
<td width="250px"> Electrical Accessories Details <span class="requiredField">* </span> : </td> 
<td>
<table><tr><td><input type="radio" name="jobStatus" value="1"  onchange="toggleElectrical(this.value)"   id="electric_toggle_yes"></td><td><label for="running">Yes</label></td></tr>
<tr><td>
<input type="radio" name="jobStatus" value="0" id="electric_toggle_no" checked="checked" onchange="toggleElectrical(this.value)"></td><td><label for="completed">No</label></td></tr></table></td>
</tr>

</table>

<div id="yesElectric" style="display:none;">

<h4 class="headingAlignment"> Electrical Accessories Details </h4>
<table   class="insertTableStyling no_print">

<tr>
<td class="firstColumnStyling" width="250px">
Electric IDV <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="electricIDV" id="electricIDV" placeholder="Only Digits" value="0" onchange="countElectronicPremium()"/>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Percentage <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="electricPercentage" id="electricPercentage" placeholder="Only Digits"
  value="<?php $cngDetails = getCNGById(1); echo $cngDetails['cng_value']; ?>"
 /> &nbsp; %
</td>
</tr>

<tr>
<td class="firstColumnStyling">
<span style="font-weight:900; border-bottom:1px solid #000"> Calculated Electric Premium </span> <span class="requiredField">* </span> : 
</td>

<td>
<input type="text"  name="electricPremium" id="electricPremium" placeholder="Only Digits" />
</td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>

</table>
</div>

<table class="insertTableStyling no_print" >

<tr>

<td class="firstColumnStyling" width="250px">
NCB % on IDV <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="ncb" id="ncb" placeholder="Only Digits" onchange="countNCB()" value="0" /> &nbsp; %
</td>
</tr>

<tr>

<td class="firstColumnStyling" width="250px">
 NCB Amount <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="ncbAmount" id="ncbAmount" placeholder="Only Digits" value="0" /> 
</td>
</tr>

<tr>

<td class="firstColumnStyling" width="250px">
<span style="font-weight:900; border-bottom:1px solid #000"> Premium - NCB </span> <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="premumAfterNCB" id="premumAfterNCB" placeholder="Only Digits" /> 
</td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>

</table>


<div id="cngNCB" style="display:none; margin-bottom:15px">
<h4 class="headingAlignment"> NCB on CNG Details </h4>
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling" width="250px">
NCB on CNG <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="ncbCNG" id="ncbCNG" placeholder="Only Digits" onchange="countCNGNCB()" value="0" /> &nbsp; %
</td>
</tr>

<tr>

<td class="firstColumnStyling" width="250px">
CNG NCB Amount <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="cngNcbAmount" id="cngNcbAmount" placeholder="Only Digits"  value="0"/> 
</td>
</tr>

<tr>

<td class="firstColumnStyling" width="250px">
<span style="font-weight:900; border-bottom:1px solid #000"> CNG Premium - NCB </span> <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="cngPremiumAfterNCB" id="cngPremiumAfterNCB" placeholder="Only Digits" /> 
</td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>

</table>
</div>

<div id="electronicNCB" style="display:none; margin-bottom:15px;">
<h4 class="headingAlignment"> NCB on Electronic Details </h4>
<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling" width="250px">
NCB on Electronic <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="ncbelectric" id="ncbelectric" placeholder="Only Digits" onchange="countElectricNCB()" value="0" /> &nbsp; %
</td>
</tr>

<tr>

<td class="firstColumnStyling" width="250px">
Electronic NCB Amount <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="ncbElectricAmount" id="ncbElectricAmount" placeholder="Only Digits" value="0" /> 
</td>
</tr>

<tr>
<td class="firstColumnStyling" width="250px">
<span style="font-weight:900; border-bottom:1px solid #000"> Electronic Premium - NCB </span> <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="electronicPremiumAfterNCB" id="electronicPremiumAfterNCB" placeholder="Only Digits" /> 
</td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>

</table>
</div>

<table class="insertTableStyling no_print">


<tr>
<td class="firstColumnStyling" width="250px">
<span style="font-weight:900; border-bottom:1px solid #000"> Premium + CNG + Electrical </span> <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="pce" id="pce" placeholder="Only Digits" />
</td>
</tr>


<tr>
<td class="firstColumnStyling" width="250px">
Liability Premium <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="lPremium" id="lPremium" placeholder="Only Digits" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Compulsory PA<span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="cPA" id="cPA" placeholder="Only Digits" />
</td>
</tr>

<tr>

<td class="firstColumnStyling">
PA Paid Driver <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="driverPA" id="driverPA" placeholder="Only Digits" />
</td>
</tr>

<tr>
<td><hr class="firstTableFinishing" /></td>
<td><hr class="firstTableFinishing" /></td>
</tr>

<tr>

<td class="firstColumnStyling">
 <span style="font-weight:900; border-bottom:1px solid #000"> Final Amount </span> <span class="requiredField">* </span> : 
</td>

<td>
 <input type="text"  name="finalAmount" id="finalAmount" placeholder="Only Digits" />
</td>
</tr>



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
<td width="280px;">  </td>
<td><input type="button" class="btn btn-success" value="+ Add Image" id="addInsuranceProofBtn"/></td>
</tr>     
</table>

<table>
<tr>
<td width="280px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add"  class="btn btn-warning">

<?php 
if($state==1)
{
?>

<a href="<?php echo WEB_ROOT ?>admin/customer/insurance/index.php?view=insuranceDetails&id=<?php echo $vehicle_id; ?>&lid=<?php echo $customer_id; ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
<?php
}
else
{
?>
<a href="<?php echo WEB_ROOT ?>admin/customer/index.php?view=customerDetails&id=<?php echo $customer_id; ?>">
<input type="button" value="back" class="btn btn-success" />
</a>
<?php
}
?>

</td>
</tr>

</table>

</form>

</div>
<div class="clearfix"></div>

<script>
function countPremium()
{
var idv=document.getElementById("idv").value;
var rate=document.getElementById("rate").value;
idv = parseFloat(idv);
rate = parseFloat(rate);
var mul = idv*rate;
var premium = mul/100;
document.getElementById('premium').value=premium;
countDiscount();
basicPremiumPlusCNGPlusElectric();
}
</script>

<script>
function countDiscount()
{
var discount=document.getElementById("discount").value;
var premium=document.getElementById("premium").value;

discount = parseFloat(discount);
premium = parseFloat(premium);

var mul = discount*premium;

var discountAmount = mul/100;
document.getElementById('dAmount').value=discountAmount;

var discountedPremium = premium - discountAmount;
document.getElementById('disAmount').value=discountedPremium;

countNCB();
basicPremiumPlusCNGPlusElectric();

}
</script>

<script>
function countCNGPremium()
{
var cngIDV=document.getElementById("cngIDV").value;
var cngPercentage=document.getElementById("cngPercentage").value;
cngIDV = parseFloat(cngIDV);
cngPercentage = parseFloat(cngPercentage);
var mul = cngIDV*cngPercentage;
var cngPremium = mul/100;
document.getElementById('cngPremium').value=cngPremium;
basicPremiumPlusCNGPlusElectric();
}
</script>

<script>
function countElectronicPremium()
{
var electricIDV=document.getElementById("electricIDV").value;
var electricPercentage=document.getElementById("electricPercentage").value;
electricIDV = parseFloat(electricIDV);
electricPercentage = parseFloat(electricPercentage);
var mul = electricIDV*electricPercentage;
var electricPremium = mul/100;
document.getElementById('electricPremium').value=electricPremium;	
basicPremiumPlusCNGPlusElectric();
}
</script>

<script>
function countNCB()
{
var disAmount=document.getElementById("disAmount").value;
var ncb=document.getElementById("ncb").value;
var disAmount=document.getElementById("disAmount").value;


disAmount = parseFloat(disAmount);
ncb = parseFloat(ncb);

var mul = disAmount*ncb;
var premiumAfterNCB = mul/100;

document.getElementById('ncbAmount').value=premiumAfterNCB;	

var premiumAfterNCB = disAmount - premiumAfterNCB;
document.getElementById('premumAfterNCB').value=premiumAfterNCB;
basicPremiumPlusCNGPlusElectric();

}
</script>


<script>

function countCNGNCB()
{
var premumAfterNCB=document.getElementById("premumAfterNCB").value;
var cngPremium=document.getElementById("cngPremium").value;
var ncbCNG=document.getElementById("ncbCNG").value;


cngPremium = parseFloat(cngPremium);
ncbCNG = parseFloat(ncbCNG);

var mul = cngPremium*ncbCNG;
var cngAfterNcb = mul/100;

document.getElementById('cngNcbAmount').value=cngAfterNcb;

var cngPremiumAfterNCB = cngPremium - cngAfterNcb;
document.getElementById('cngPremiumAfterNCB').value=cngPremiumAfterNCB;	
basicPremiumPlusCNGPlusElectric();
}

</script>

<script>

function countElectricNCB()
{

var electricPremium=document.getElementById("electricPremium").value;
var ncbelectric=document.getElementById("ncbelectric").value;


electricPremium = parseFloat(electricPremium);
ncbelectric = parseFloat(ncbelectric);

var mul = electricPremium*ncbelectric;
var electricNcbAmount = mul/100;

document.getElementById('ncbElectricAmount').value=electricNcbAmount;

var electricPremiumAfterNCB = electricPremium - electricNcbAmount;
document.getElementById('electronicPremiumAfterNCB').value=electricPremiumAfterNCB;	
basicPremiumPlusCNGPlusElectric();
}

</script>

<script>

function basicPremiumPlusCNGPlusElectric()
{

var premumAfterNCB=document.getElementById("premumAfterNCB").value;
var cngPremiumAfterNCB=document.getElementById("cngPremiumAfterNCB").value;
var electronicPremiumAfterNCB=document.getElementById("electronicPremiumAfterNCB").value;
var cng_toggle = document.getElementById('cng_toggle_yes').checked;
var accessory_toggle = document.getElementById('electric_toggle_yes').checked;
var total=0;
premumAfterNCB = parseFloat(premumAfterNCB);
cngPremiumAfterNCB = parseFloat(cngPremiumAfterNCB);
electronicPremiumAfterNCB = parseFloat(electronicPremiumAfterNCB);

total = premumAfterNCB;
	
	if(cng_toggle==1)
	{
		
	var total = total+cngPremiumAfterNCB;
	
	}
   if(accessory_toggle==1) 
	{
	var total = total+electronicPremiumAfterNCB;
	
    }
	
document.getElementById('pce').value=total;

var lPremium=document.getElementById("lPremium").value;
var cPA=document.getElementById("cPA").value;
var driverPA=document.getElementById("driverPA").value;

lPremium = parseFloat(lPremium);
cPA = parseFloat(cPA);
driverPA = parseFloat(driverPA);

var finalAmount = total+lPremium+cPA+driverPA;
document.getElementById('finalAmount').value=finalAmount;


}

</script>


