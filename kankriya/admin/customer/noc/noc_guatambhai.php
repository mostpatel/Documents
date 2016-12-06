<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$file_id=$_GET['id'];
$noc = getNOCByFileId($file_id);
$customer = getCustomerDetailsByFileId($file_id);
$file=getFileDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
$loan_id = getLoanIdFromFileId($file_id);
$loan = getLoanById($loan_id);
$admin_rights=$_SESSION['adminSession']['admin_rights'];

if(!in_array(11,$admin_rights))
exit;
?>
<style>
.notice_details
{
	margin:30px;
	width:90%;
	}
.notice_details tr td{
	
	padding:2px;
	padding-left:10px;
	font-weight:bold;
	}
</style>
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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="../legal_notice/index.php?view=search"><button class="btn btn-warning">Go to Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/sale_notice/index.php?id=<?php echo $file_id; ?>"><button class="btn btn-success">Back</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;width:90%;">
<div  style="float:right;font-size:18px;line-height:25px;text-align:right; ">
<b>Office Address</b><br />
35 Vanijya Bhavan,<br /> 
Opp. Diwan Ballubhai School,<br />
Kankaria Road, Ahmedabad<br />
(O): 079-25454626<br />
(M): 9426060148<br />
</div>
<div  style="float:left;font-size:34px;font-weight:bold; ">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>
<div  style="text-align:center; font-size:34px;clear:both">

</div>

<div class="notice_address" style="text-align:center;padding-bottom:0px;">

</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;"></div>

                                     								 
                                                                    
                                                                    <div class="date" style="float:right;padding-right:20px;font-size:20px;">Ref No : <b><?php echo $file['file_number']; ?></b><br />Date : <?php echo date('d/m/Y',strtotime($noc['noc_date'])); ?><br /> </div>
<div style="clear:both;padding-bottom:30px;"></div>  
<b style="position:relative;width:100%;text-align:center;display:block;"> <i> <?php if(isset($_GET['duplicate']) && $_GET['duplicate']==0) {} else echo "(Duplicate Copy)"; ?></i></b>                                                                   
<div class="prati" style="padding-bottom:0;"><br /><pre style="font-size:20px;line-height:26px;">The Regional Transport Officer
<?php    $cid = $customer['city_id'];
							 		
							       $cityDetails = getCityByID($cid);
								   echo $cityDetails['city_name'];
							 ?>

Dear Sir,

</pre>
<span style="font-weight:bold;text-align:center;width:100%;font-size:22px;display:block;">Reg.: No Objection Certificate for Lien Endorsement Removal</span>
Borrower Name & Address : </div>
<div class="customer_address" style="float:left;width:48%;font-size:20px;line-height:26px;"><b><?php echo $customer['customer_name'] ?>,</b><br /><pre><?php echo $customer['customer_address'] ?></pre></div>   
<div class="guarantor_address" style="float:right;width:30%;text-align:justify;font-size:20px;line-height:26px;font-weight:bold;">Regn. No. :<?php echo $vehicle['vehicle_reg_no']; ?><br />Product :<?php $company=getVehicleCompanyById($vehicle['vehicle_company_id']); echo $company['company_name']; ?>,<br />Variant :<?php echo getModelNameById($vehicle['model_id']); ?>,<br />Engine No :<?php echo $vehicle['vehicle_engine_no']; ?>,<br />Chasis No :<?php echo $vehicle['vehicle_chasis_no']; ?> </div> <div style="clear:both;"></div>           
<div style="font-size:20px;line-height:26px;padding-top:30px;">
With reference to the above, we hereby confirm that we have no objection for cancellation of loan lien marking / endorsement made in our favour in the Registration Certificate. The RTO Form 35 duly signed by borrower and countersigned by us, is enclosed.
</div>
<div style="width:48%;float:left;font-size:20px;line-height:26px;font-weight:bold">
<pre style="font-size:20px;line-height:26px;font-weight:bold">



Thanking you,

For,<b><?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?></b>




AUTHORISED SIGNATORY
Name of Authorised person: Mahendra Bhawarlal Kankriya
</pre>
</div>
<div style="width:50%;float:right;font-size:20px;line-height:26px;font-weight:bold">For your reference, we have printed the specimen 
	Signature of the authorized signatory as below:<br />
    <img src="<?php echo WEB_ROOT."images/noc.jpg"; ?>" />
</div>
<div style="clear:both;"></div>
<div style="font-size:20px;line-height:26px;border-top:1px solid black;margin-top:20px;padding-top:20px;">
<pre>
<b>Note:</b> 
1. Any unauthorized alteration or overwriting would make this letter "Invalid"
2. The NOC is valid for 90 days from the date of issuance.

</pre>
</div>

<div style="page-break-after:always"></div>
<?php for($i=0;$i<2;$i++) { ?>
<div >

<span style="float:right;font-size:18px;height:18px;">Ref. No. <?php echo $file['file_number']; ?></span>
<div style="clear:both;"></div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">FORM 35</div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">(See Rule 61)</div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">(To be submitted in duplicate)</div>
<div style="font-size:18px;line-height:24px;font-weight:bold;text-align:center">
Notice of Termination of an Agreement of hire purchase / Lease/ Hypothecation </div>
<div class="prati" style="padding-bottom:0;"><pre style="font-size:18px;line-height:22px;">To,
The Registering Authority 
<?php    $cid = $customer['city_id'];
							 		
							       $cityDetails = getCityByID($cid);
								   echo $cityDetails['city_name'];
							 ?>

</pre>

</div>

<div style="font-size:18px;line-height:24px;">
We hereby declare that the agreement o hire purchase / lease / hypothecation entered into between us has terminated. We, therefore, request that the note endorsed in the certificate of Registration <b ><?php echo $customer['customer_name'] ?></b><br />

<pre style="text-align:center;font-weight:bold;padding-top:5px;font-size:20px;padding-bottom:5px;">Vehicle No. <?php echo $vehicle['vehicle_reg_no']; ?>   	  Classis No. <?php echo $vehicle['vehicle_chasis_no']; ?> 	    Engine No. <?php echo $vehicle['vehicle_engine_no']; ?> </pre>

in respected of the said Agreement between us be cancelled.<br />
The Certificate of Registration is enclosed
</div>
<span style="font-size:18px;">Date:_______________</span>
<span style="float:right;padding-right:300px;height:0px;top:-45px;position:relative;">X</span>
<pre style="float:right;text-align:right;font-size:18px;line-height:26px;"><b>Signature of the Registered Owner 
(Formally) <?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?></b>


Signature of the Financier 
Authorized Signatory</pre>
<div style="clear:both;width:100%;padding-top:5px;"></div>
<div style="font-size:18px;line-height:23px;text-align:center">FOR OFFICE USE ONLY</div>
<div style="font-size:18px;line-height:23px;text-align:center">FORM OF INTIMATION TO THE FINANCER</div>
<div style="font-size:18px;line-height:23px;">
R.N. ___________________<br />
The cancellation of the entry of an agreement as requested above is recorded in this office registration record in Form ______________________ and Registration Certificate on____________________(date)
<br /><br /><br /><br />


Date: ___________________							<div style="float:right">Signature of the Registration Authority </div>
<br /><br />
Note: The duplicate application form with the above columns filled in shall be send either to the financier by registered post to be delivered to be financier under proper acknowledgement.  
</div>
<?php if($i==0) { ?><div style="border-bottom:2px solid #000;padding-top:10px;"></div><?php } ?>
<?php } ?>
</div>
</div>
</div>
</div>
<div class="clearfix"></div>