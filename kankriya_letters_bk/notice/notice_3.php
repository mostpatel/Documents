<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

if(isset($_GET['bulk']) && $_GET['bulk']==1)
{
	$notice_id_array = getNoticesForBulkNoticeId($_GET['id']);
}
else
{
$notice_id=$_GET['id'];
$notice_id_array = array($notice_id);
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


<div class="addDetailsBtnStyling no_print"><a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="index.php?view=search"><button class="btn btn-warning">Go to Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/notice/index.php?id=<?php echo $file_id; ?>"><button class="btn btn-success">Back</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<?php
foreach($notice_id_array as $notice_id)
{ 

$notice=getNoticeById($notice_id);
$file_id=$notice['file_id'];
$advocate_id = $notice['advocate_id'];
$advocate = getAdvocateById($advocate_id);
$file=getFileDetailsByFileId($file_id);
if(is_numeric($file['oc_id']))
$our_company=getOurCompanyByID($file['oc_id']);
else if(is_numeric($file['agency_id']))
$agency = getAgencyById($file['agency_id']);
$loan=getLoanDetailsByFileId($file_id);
$guarantor=getGuarantorDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
if($vehicle && $vehicle!="error")
{
  $company = getVehicleCompanyById($vehicle['vehicle_company_id']);  
  $modelName = getModelNameById($vehicle['model_id']);	
}
$bucket_details=getBucketDetailsForLoan($loan['loan_id'],$notice['notice_date']);
$bucket_amount = getTotalBucketAmountForLoan($loan['loan_id'],$notice['notice_date']);
$bucket_string = "";

 if(isset($bucket_details) && $bucket_details!=0 && is_array($bucket_details) && count($bucket_details)>0) 
 { 
 foreach($bucket_details as $e=>$corr_bucket) 
 { 
 
 $whole = floor($corr_bucket);      // 1
 $fraction = $corr_bucket - $whole; // .25
 $whole_bucket_amount = 0;
	 if($fraction==0)
	 {
	 $whole_bucket_amount =  $whole_bucket_amount + ($e * $corr_bucket);
	 
	 $bucket_string= $bucket_string." રૂ.".$e."/- ના એક એવા ".number_format($corr_bucket,0);
	 if(count($bucket_details)>1)
	 $bucket_string=$bucket_string." | ";
	 }
 
 } 
 $fraction_bucket_amount = $bucket_amount - $whole_bucket_amount;
 if($fraction_bucket_amount>0)
 $bucket_string = $bucket_string." +  રૂ.".$fraction_bucket_amount;
 $bucket_string = $bucket_string." (= કુલ રૂ.".$bucket_amount." ) ";
}
?>
<?php if(isset($guarantor) && is_numeric($guarantor['guarantor_id'])) { ?>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:5px;">
<div id="page_fold" style="position:relative;top:500px;left:-20px;">___</div>
<div  style="float:left; ">
Phone : <?php echo $advocate['contact_no']; ?>
</div>

<div  style="float:Right; ">
<?php if(validateForNull($advocate['contact_no2'])) { ?>
Phone : <?php echo $advocate['contact_no2']; } ?>
</div>
<div  style="text-align:center; font-size:34px;font-weight:bold;">
<?php echo strtoupper($advocate['advocate_name']); ?>
</div>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo $advocate['advocate_address']; ?>
</div>
<div class="date" style="float:right;padding-right:10px;font-size:18px;height:0px;top:-30px;">તારીખ: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?> </div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;padding-top:15px;">પોલીસ કેસ દાખલ કરતા પહેલાની આખરી નોટીસ/ચેતવણી</div>


                                     <div style="padding-left:110px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:30px;">

<div class="customer_address" style="float:left;width:48%;">(1) <b><?php echo $notice['customer_name']; ?> (<?php echo $file['file_number']; ?>) Party,</b><br /><pre><?php echo $notice['customer_address']; ?></pre><span class=""><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span></div> 
<div style="clear:both"></div>
<div class="customer_address" style="float:left;width:48%;margin-top:80px;">(2) <b><?php echo $notice['guarantor_name']; ?> (<?php echo $file['file_number']; ?>) Guarantor,</b><br /><pre><?php echo $notice['guarantor_address']; ?></pre></div>
</div>
<br /><br />
<div style="clear:both"></div>
<br />
<b style="font-weight:bold;display:inline-block;width:23%;vertical-align:text-top;float:left;text-align:right;padding-right:10px;"><u>બાબત :</u></b><b style="font-weight:bold;display:inline-block;width:75%;"><u>ફોજદારી કાયદા સબંધિત જોગવાઈઓ હેઠળ પોલીસ માં ઈ.પી.કો. કલમ- 114, 406, 420, 503, 504 મુજબ કસુરવાર વિરુધ્ધ કેસ દાખલ કરતા પહેલાની આખરી નોટીસ/ચેતવણી </u></b><br />
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> <?php echo $advocate['secondary_advocate_name']; ?> </b>, રહે . ઉપર મુજબના તે અમારા અસીલશ્રી  <b style="font-weight:bold;"> મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b> કુલમુખ્યતાર <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?>ના ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. પોસ્ટ દ્વારા નોટીસ/ચેતવણી આપી જણાવવાનું કે.........<br />
(1)	અમારા અસીલશ્રી તેઓના ઉપરોકત જણાવેલ સરનામેથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા નાણાં ચુકવવાના કાયદેસરનો વેપાર ધંધો કરતા આવેલા છે.
<br />
(2)	તમોશ્રીના નં.1 તથા નં.2 અમારા અસલીશ્રીના ઉપરોકત જણાવેલ સરનામે રૂબરૂમાં આવેલ અને અમારા અસીલશ્રી પાસે એક કાયદેસરનો એગ્રીમેન્ટ કરેલ. જેમાં તમોશ્રીના નં.2 દ્વારા જમીનદાર તરીકે મત્તુ કરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા કરવામાં આવેલ એગ્રીમેન્ટનો તમોશ્રીનાઓ દ્વારા બદઈરાદાપૂર્વક ભંગ કરી અમારા અસલીશ્રીને સાથે વિશ્વાસઘાત તથા છેતરપીંડી આચરી તમોશ્રીનાઓ દ્વારા <b>ઈ.પી.કો. કલમ- 114, 406, 420, 503, 504</b> મુજબના ગુનાઓ આચરવામાં આવેલ છે. તમોશ્રીનાઓ તેમજ અમારા અસીલશ્રી વચ્ચે થયેલ એગ્રીમેન્ટ મુજબ તમોશ્રીનાઓ દ્રારા મેળવવામાં આવેલ લોન વાળા વાહન કે જેનો રજીસ્ટ્રેશન નં. <b> <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?></b> થી આવેલ છે. તેની આર સી. બુકમાં પણ અમારા અસીલશ્રીએ અંગેની નોંધ કરાવેલ છે.
<br />
(3)	અમારા અસીલશ્રી તેમજ તમોશ્રીનાઓ વચ્ચે કરવામાં આવેલ એગ્રીમેન્ટ મુજબ લોન અંગેની સંપૂર્ણ રકમ તમોશ્રીનાઓએ માસીક  રૂ. <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના એક એવા ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના એક એવા ".$e['duration']." | ";
									  }
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારા અસીલશ્રીને ચુકવણી કરવાનું નક્કી કરવામાં આવેલ. 	માસીક હપ્તાઓની ઉપરોકત તમામ રકમ નિયમિત પણે ચુકવવા અંગે  	તમોશ્રીનાઓ 	દ્વારા અમારા અસીલશ્રીને એગ્રીમેન્ટ સમયે સ્પષ્ટ વિશ્વાસ અને 
બાંહેધરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રીને આજદિન સુધી <b><?php echo floor(getTotalEmiPaidForLoan($loan['loan_id'])); $fraction_amount = getFractionEmiPaidAmountForLoan($loan['loan_id']); if($fraction_amount>0) echo " + રૂ.".$fraction_amount; echo " = ( રૂ. ".getTotalPaymentForLoan($loan['loan_id'])." )"; ?></b> માસિક હપ્તા તેની ચુકવણી કરવામાં આવેલ છે  અને અમારા અસીલશ્રી આજદિન સુધી તમોશ્રીનાઓ પાસેથી  <b><?php  echo $bucket_string; ?></b> માસિક હપ્તા તમામ પ્રકારના ચાર્જીસ તેમજ ખર્ચ સહિત મેળવવા માટે હક્કદાર બનેલ છે.
 <div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:60px;">(2)</div>
(4)	અમારા અસીલશ્રી તમોશ્રીના નં.1 પાસે રૂબરૂમાં ઉપરોકત બાકી નિકળતા હપ્તાની રકમ મેળવવા માટે આવેલ તેવા સમયે તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રી સાથે ખુબજ ઉધ્ધાઈ ભર્યું વર્તન કરવામાં આવેલ છે અને અમારા અસીલશ્રીને ફરી વખત બાકી નિકળતી રકમ મેળવવાનો પ્રયત્ન ન કરવા માટે ધમકી ઉચ્ચારવામાં આવેલ. ઉપરાંત તમોશ્રીનાઓના કબજામાં રહેલ વાહન અન્યત્ર વેચાણ કરી દેવાની તેમજ સંતાડી દેવાની ઉપરાંત અમારા અસીલશ્રીને તમોશ્રીનાઓ દ્વારા વિવિધ ખોટા ફોજદારી કેસોમાં ફસાવી દેવાની ધમકીઓ ઉચ્ચારવામાં આવેલ છે. જે બાબતે અમારા અસીલશ્રી દ્વારા તમોશ્રીના નં.2 નાને જાણ કરતા તમોશ્રીના દ્વારા પણ અમારા અસલીશ્રી સાથે ઉધ્ધાતભર્યુ વર્તન કરી તમોશ્રીના નં.1 દ્વારા કરવામાં આવેલ ગેરકાયદેસરના કૃત્ચમાં બદઈરાદાપૂર્વક મદદગારી કરવામાં આવેલ છે. 

<br />
(5)	આમ, તમોશ્રીના નં. 1 અને નં.2 દ્વારા અમારા અસીલશ્રી સાથે કરવામાં આવેલ કાયદેસરના એગ્રીમેન્ટ શરતોનું ઉલ્લંઘન કરી અમારા અસીલશ્રી સાથે ઠગાઈ, વિશ્વાસઘાત, તેમજ છેતરપીંડી કરવામાં આવેલ હોઈ, તમોશ્રીનાઓએ <b>ઈ.પી.કો. કલમ- 114, 406, 420, 503, 504 </b> મુજબના ગુનાઓ આચરેલ હોઈ, તમોશ્રીના નં.1 તથા નં.2 નાઓને આખરી તક આપવાના હેતુસર આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયેલ છે. <br />
આથી તમોશ્રીનાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યે દિન-7 માં અમારા અસીલશ્રીને એગ્રીમેન્ટ અંગેની હપ્તા તેમજ વિવિધ ચાર્જીસ સહિતની કાયદેસર રીતે ચુકવવાપાત્ર થતી લેણી રકમ ચુકવી આપી તેઓના હાથની કાયદેસરની પહોંચ મેળવી લેવી. જો તેમ કરવામાં કસુર કરશો તો અમારા અસીલશ્રી તમોશ્રીનાઓના કબજામાં રહેલ વાહન જપ્ત કરવાની કાર્યવાહી ઉપરાંત તમોશ્રીનાઓ વિરૂધ્ધ પોલીસ ફરીયાદ તેમજ દિવાની તેમજ ફોજદારી કાર્યવાહી કાયદેસરની સલાહ મળ્યા મુજબ તમોશ્રીનાઓના ખર્ચે અને જોખમે કરશે તેની ગંભીર નોંધ લેશોજી.
<br />
(6)	આ નોટીસ તમોશ્રીનાની કસુરથી આપવી પડેલ હોઈ સદર નોટીસ ખર્ચના રૂ. 501/- તમોશ્રીનાઓ અમારા અસીલશ્રીનાને ચુકવી આપવા.

<br />
<br />

સ્થળ : અમદાવાદ</b>


                                                                     <div class="lee" style="font-weight:bold"> મારી મારફતે<br /> <?php echo $advocate['secondary_advocate_name']; ?>
  <br /><br />.......................................</div>       

</div>
<div style="page-break-after:always"></div>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:5px;">
<div id="page_fold" style="position:relative;top:500px;left:-20px;">___</div>
<div  style="float:left; ">
Phone : <?php echo $advocate['contact_no']; ?>
</div>

<div  style="float:Right; ">
<?php if(validateForNull($advocate['contact_no2'])) { ?>
Phone : <?php echo $advocate['contact_no2']; } ?>
</div>
<div  style="text-align:center; font-size:34px;">
<?php echo strtoupper($advocate['advocate_name']); ?>
</div>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo $advocate['advocate_address']; ?>
</div>
<div class="date" style="float:right;padding-right:10px;font-size:18px;height:0px;top:-30px;">તારીખ: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?> </div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;padding-top:15px;">પોલીસ કેસ દાખલ કરતા પહેલાની આખરી નોટીસ/ચેતવણી</div>


                                     <div style="padding-left:110px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:30px;">
<div class="customer_address" style="float:left;width:48%;">(2) <b><?php echo $notice['guarantor_name']; ?> (<?php echo $file['file_number']; ?>) Guarantor,</b><br /><pre><?php echo $notice['guarantor_address']; ?></pre><span class=""><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span></div>
<div style="clear:both"></div>
<div class="customer_address" style="float:left;width:48%;margin-top:80px;">(1) <b><?php echo $notice['customer_name']; ?> (<?php echo $file['file_number']; ?>) Party,</b><br /><pre><?php echo $notice['customer_address']; ?></pre></div> 


</div>
<br /><br />
<div style="clear:both"></div>
<br />
<b style="font-weight:bold;display:inline-block;width:23%;vertical-align:text-top;float:left;text-align:right;padding-right:10px;"><u>બાબત :</u></b><b style="font-weight:bold;display:inline-block;width:75%;"><u>ફોજદારી કાયદા સબંધિત જોગવાઈઓ હેઠળ પોલીસ માં ઈ.પી.કો. કલમ- 114, 406, 420, 503, 504 મુજબ કસુરવાર વિરુધ્ધ કેસ દાખલ કરતા પહેલાની આખરી નોટીસ/ચેતવણી </u></b><br />
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> <?php echo $advocate['secondary_advocate_name']; ?> </b>, રહે . ઉપર મુજબના તે અમારા અસીલશ્રી  <b style="font-weight:bold;"> મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b> કુલમુખ્યતાર <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?>ના ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. પોસ્ટ દ્વારા નોટીસ/ચેતવણી આપી જણાવવાનું કે.........<br />
(1)	અમારા અસીલશ્રી તેઓના ઉપરોકત જણાવેલ સરનામેથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા નાણાં ચુકવવાના કાયદેસરનો વેપાર ધંધો કરતા આવેલા છે.
<br />
(2)	તમોશ્રીના નં.1 તથા નં.2 અમારા અસલીશ્રીના ઉપરોકત જણાવેલ સરનામે રૂબરૂમાં આવેલ અને અમારા અસીલશ્રી પાસે એક કાયદેસરનો એગ્રીમેન્ટ કરેલ. જેમાં તમોશ્રીના નં.2 દ્વારા જમીનદાર તરીકે મત્તુ કરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા કરવામાં આવેલ એગ્રીમેન્ટનો તમોશ્રીનાઓ દ્વારા બદઈરાદાપૂર્વક ભંગ કરી અમારા અસલીશ્રીને સાથે વિશ્વાસઘાત તથા છેતરપીંડી આચરી તમોશ્રીનાઓ દ્વારા <b>ઈ.પી.કો. કલમ- 114, 406, 420, 503, 504</b> મુજબના ગુનાઓ આચરવામાં આવેલ છે. તમોશ્રીનાઓ તેમજ અમારા અસીલશ્રી વચ્ચે થયેલ એગ્રીમેન્ટ મુજબ તમોશ્રીનાઓ દ્રારા મેળવવામાં આવેલ લોન વાળા વાહન કે જેનો રજીસ્ટ્રેશન નં. <b> <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?></b> થી આવેલ છે. તેની આર સી. બુકમાં પણ અમારા અસીલશ્રીએ અંગેની નોંધ કરાવેલ છે.
<br />
(3)	અમારા અસીલશ્રી તેમજ તમોશ્રીનાઓ વચ્ચે કરવામાં આવેલ એગ્રીમેન્ટ મુજબ લોન અંગેની સંપૂર્ણ રકમ તમોશ્રીનાઓએ માસીક  રૂ. <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના એક એવા ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના એક એવા ".$e['duration']." | ";
									  }
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારા અસીલશ્રીને ચુકવણી કરવાનું નક્કી કરવામાં આવેલ. 	માસીક હપ્તાઓની ઉપરોકત તમામ રકમ નિયમિત પણે ચુકવવા અંગે  	તમોશ્રીનાઓ 	દ્વારા અમારા અસીલશ્રીને એગ્રીમેન્ટ સમયે સ્પષ્ટ વિશ્વાસ અને 
બાંહેધરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રીને આજદિન સુધી <b><?php echo floor(getTotalEmiPaidForLoan($loan['loan_id'])); $fraction_amount = getFractionEmiPaidAmountForLoan($loan['loan_id']); if($fraction_amount>0) echo " + રૂ.".$fraction_amount; echo " = ( રૂ. ".getTotalPaymentForLoan($loan['loan_id'])." )"; ?></b> માસિક હપ્તા તેની ચુકવણી કરવામાં આવેલ છે  અને અમારા અસીલશ્રી આજદિન સુધી તમોશ્રીનાઓ પાસેથી  <b><?php  echo $bucket_string; ?></b> માસિક હપ્તા તમામ પ્રકારના ચાર્જીસ તેમજ ખર્ચ સહિત મેળવવા માટે હક્કદાર બનેલ છે.
 <div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:80px;">(2)</div>
(4)	અમારા અસીલશ્રી તમોશ્રીના નં.1 પાસે રૂબરૂમાં ઉપરોકત બાકી નિકળતા હપ્તાની રકમ મેળવવા માટે આવેલ તેવા સમયે તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રી સાથે ખુબજ ઉધ્ધાઈ ભર્યું વર્તન કરવામાં આવેલ છે અને અમારા અસીલશ્રીને ફરી વખત બાકી નિકળતી રકમ મેળવવાનો પ્રયત્ન ન કરવા માટે ધમકી ઉચ્ચારવામાં આવેલ. ઉપરાંત તમોશ્રીનાઓના કબજામાં રહેલ વાહન અન્યત્ર વેચાણ કરી દેવાની તેમજ સંતાડી દેવાની ઉપરાંત અમારા અસીલશ્રીને તમોશ્રીનાઓ દ્વારા વિવિધ ખોટા ફોજદારી કેસોમાં ફસાવી દેવાની ધમકીઓ ઉચ્ચારવામાં આવેલ છે. જે બાબતે અમારા અસીલશ્રી દ્વારા તમોશ્રીના નં.2 નાને જાણ કરતા તમોશ્રીના દ્વારા પણ અમારા અસલીશ્રી સાથે ઉધ્ધાતભર્યુ વર્તન કરી તમોશ્રીના નં.1 દ્વારા કરવામાં આવેલ ગેરકાયદેસરના કૃત્ચમાં બદઈરાદાપૂર્વક મદદગારી કરવામાં આવેલ છે. 

<br />
(5)	આમ, તમોશ્રીના નં. 1 અને નં.2 દ્વારા અમારા અસીલશ્રી સાથે કરવામાં આવેલ કાયદેસરના એગ્રીમેન્ટ શરતોનું ઉલ્લંઘન કરી અમારા અસીલશ્રી સાથે ઠગાઈ, વિશ્વાસઘાત, તેમજ છેતરપીંડી કરવામાં આવેલ હોઈ, તમોશ્રીનાઓએ <b>ઈ.પી.કો. કલમ- 114, 406, 420, 503, 504 </b> મુજબના ગુનાઓ આચરેલ હોઈ, તમોશ્રીના નં.1 તથા નં.2 નાઓને આખરી તક આપવાના હેતુસર આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયેલ છે. <br />
આથી તમોશ્રીનાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યે દિન-7 માં અમારા અસીલશ્રીને એગ્રીમેન્ટ અંગેની હપ્તા તેમજ વિવિધ ચાર્જીસ સહિતની કાયદેસર રીતે ચુકવવાપાત્ર થતી લેણી રકમ ચુકવી આપી તેઓના હાથની કાયદેસરની પહોંચ મેળવી લેવી. જો તેમ કરવામાં કસુર કરશો તો અમારા અસીલશ્રી તમોશ્રીનાઓના કબજામાં રહેલ વાહન જપ્ત કરવાની કાર્યવાહી ઉપરાંત તમોશ્રીનાઓ વિરૂધ્ધ પોલીસ ફરીયાદ તેમજ દિવાની તેમજ ફોજદારી કાર્યવાહી કાયદેસરની સલાહ મળ્યા મુજબ તમોશ્રીનાઓના ખર્ચે અને જોખમે કરશે તેની ગંભીર નોંધ લેશોજી.
<br />
(6)	આ નોટીસ તમોશ્રીનાની કસુરથી આપવી પડેલ હોઈ સદર નોટીસ ખર્ચના રૂ. 501/- તમોશ્રીનાઓ અમારા અસીલશ્રીનાને ચુકવી આપવા.

<br />
<br />
સ્થળ : અમદાવાદ</b>


                                                                     <div class="lee" style="font-weight:bold"> મારી મારફતે<br /> <?php echo $advocate['secondary_advocate_name']; ?>
  <br /><br />.......................................</div>       

</div>
<div style="page-break-after:always"></div>
<?php }else { ?>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:5px;">
<div id="page_fold" style="position:relative;top:500px;left:-20px;">___</div>
<div  style="float:left; ">
Phone : <?php echo $advocate['contact_no']; ?>
</div>
<div  style="float:Right; ">
Phone : <?php echo $advocate['contact_no2']; ?>
</div>
<div  style="text-align:center; font-size:34px;">
<?php echo  strtoupper($advocate['advocate_name']); ?>
</div>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo $advocate['advocate_address']; ?>
</div>
<div class="date" style="float:right;padding-right:10px;font-size:18px;height:0px;top:-30px;">તારીખ: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?> </div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;padding-top:15px;">પોલીસ કેસ દાખલ કરતા પહેલાની આખરી નોટીસ/ચેતવણી</div>


                                     <div style="padding-left:110px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:30px;">

<div class="customer_address" style="float:left;width:48%;">(1) <b><?php echo $notice['customer_name']; ?> (<?php echo $file['file_number']; ?>) Party,</b><br /><pre><?php echo $notice['customer_address']; ?></pre><span class=""><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span></div> 
<div style="clear:both"></div>

</div><br /><br /><br /><br />
<b style="font-weight:bold;display:inline-block;width:23%;vertical-align:text-top;float:left;text-align:right;padding-right:10px;"><u>બાબત :</u></b><b style="font-weight:bold;display:inline-block;width:75%;"><u>ફોજદારી કાયદા સબંધિત જોગવાઈઓ હેઠળ પોલીસ માં ઈ.પી.કો. કલમ- 406, 420, 503, 504 મુજબ કસુરવાર વિરુધ્ધ કેસ દાખલ કરતા પહેલાની આખરી નોટીસ/ચેતવણી </u></b>
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"><?php echo $advocate['secondary_advocate_name']; ?></b>, રહે . ઉપર મુજબના તે અમારા અસીલશ્રી  <b style="font-weight:bold;"> મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b> કુલમુખ્યતાર <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?>ના ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા,   અમદાવાદની સુચના અને ફરમાઈશથી તમોશ્રીનાને આ રજી. એ.ડી. પોસ્ટ દ્વારા નોટીસ/ચેતવણી આપી જણાવવાનું કે.........

<br /><br />
(1)	અમારા અસીલશ્રી તેઓના ઉપરોકત જણાવેલ સરનામેથી વાહનો ઉપર કાયદેસરના એગ્રીમેન્ટ દ્વારા નાણાં ચુકવવાના કાયદેસરનો વેપાર ધંધો કરતા આવેલ છે.<br /><br />
(2)	તમોશ્રીના અમારા અસીલશ્રીના ઉપરોકત જણાવેલ સરનામે રૂબરૂમાં આવેલ અને અમારા અસીલશ્રી પાસે તમોશ્રીના દ્વારા એક કાયદેસરનો એગ્રીમેન્ટ કરેલ અને સદરહુ કરારમાં તમોશ્રીનાઓ દ્વારા સહી મત્તુ કરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા કરવામાં આવેલ એગ્રીમેન્ટનો તમોશ્રીનાઓ દ્વારા બદઈરાદાપૂર્વક ભંગ કરી અમારા અસીલશ્રી સાથે વિશ્વાઘાત થતા છેતરપીંડી આચરી તમોશ્રીનાઓ દ્વારા <b>ઈ.પી.કો. કલમ- 406, 420, 503, 504,</b> મુજબના ગુનાઓ આચરવામાં આવેલ છે. તમોશ્રીનાઓ તેમજ અમારા અસીલશ્રી વચ્ચે થયેલ એગ્રીમેન્ટ મુજબ તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રી પાસેથી નાણાં મેળવેલ. તમોશ્રીના દ્વારા મેળવવામાં આવેલ 
નાણાંવાળા વાહન કે જેનો રજીસ્ટ્રેશન નં <b><?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?></b> થી આવેલ છે. તેની આર.સી. બુકમાં પણ અમારા અસીલશ્રીએ નોંધ કરાવેલ છે.<br /><br />
(3)	અમારા અસીલશ્રી તેમજ તમોશ્રીનાઓ વચ્ચે કરવામાં આવેલ એગ્રીમેન્ટ મુજબ મેળવેલ નાણાંની સંપૂર્ણ રકમ તમોશ્રીનાઓ માસીક રૂ. <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના એક એવા ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના એક એવા ".$e['duration']." | ";
									  }
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારા અસીલશ્રીને ચુકવણી કરવાનું નક્કી કરવામાં આવેલ. માસીક હપ્તાઓની ઉપરોત તમામ રકમ નિયમિત પણે ચુકવવા અંગે તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રીને એગ્રીમેન્ટ સમયે સ્પષ્ટ વિશ્વાસ અને બાંહેધરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રીને આજદિન સુધી  <b><?php echo floor(getTotalEmiPaidForLoan($loan['loan_id'])); $fraction_amount = getFractionEmiPaidAmountForLoan($loan['loan_id']); if($fraction_amount>0) echo " + રૂ.".$fraction_amount; echo " = ( રૂ. ".getTotalPaymentForLoan($loan['loan_id'])." )"; ?></b> માસિક હપ્તા તેમજ ની ચુકવણી કરવામાં આવેલ છે અને અમારા અસીલશ્રી આજદિન સુધી તમોશ્રીનાઓ પાસેથી  <b><?php  echo $bucket_string; ?></b> માસિક હપ્તા તમામ પ્રકારના ચાર્જીસ તેમજ ખર્ચ સહિત મેળવવા માટે હક્કદાર બનેલ છે.
<div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:80px;">(2)</div>
(4)	અમારા અસીલશ્રી તમોશ્રીના પાસે રૂબરૂમાં ઉપરોકત બાકી નિકળતા હપ્તાની રકમ મેળવવા માટે આવેલ તેવા સમયે તમોશ્રીનાઓ દ્વારા અમારા અસીલશ્રી સાથે ખુબજ ઉધ્ધાત ભર્યું વર્તન કરવામાં આવેલ છે અને અમારા અસીલશ્રીને ફરી વખત બાકી નિકળતી રકમ મેળવવાનો પ્રત્યન ન કરવા માટે ધમકી ઉચ્ચારવામાં આવેલ. ઉપરાંત તમોશ્રીનાઓના કબજામાં રહેલ વાહન અન્યત્ર વેચાણ કરી દેવાની તેમજ સંતાડી દેવાની ઉપરાંત અમારા અસીલશ્રીને તમોશ્રીનાઓ દ્વારા વિવિધ ખોટા ફોજદારી કેસોમાં ફસાવી દેવાની ધમકીઓ ઉચ્ચારવામાં આવેલ છે.<br /><br />
(5)	આમ, તમોશ્રીના દ્વારા અમારા અસીલશ્રી સાથે કરવામાં આવેલ કાયદેસરના એગ્રીમેન્ટની શરતોનું ઉલ્લંઘન કરી એકબીજાના મેળાપીપણામાં અમારા અસીલશ્રીને સાથે ઠગાઈ, વિશ્વાસઘાત, તેમજ છેતરપીંડી કરવામાં આવેલ હોઈ, તમોશ્રીનાઓએ<b> ઈ.પી.કો. કલમ- 406, 420, 503, 504</b> મુજબના ગુનાઓ આચરેલ હોઈ, તમોશ્રીનાઓને આખરી તક આપવાના હેતુસર આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયેલ છે.<br />
આથી તમોશ્રીનાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યે દિન-7 માં અમારા અસીલશ્રીને એગ્રીમેન્ટ અંગેની હપ્તા તેમજ વિવિધ ચાર્જીસ સહિતની કાયદેસર રીતે ચુકવવાપાત્ર થતી લેણી રકમ ચુકવી આપી તેઓના હાથની કાયદેસરની પહોંચ મેળવી લેવી. જો તેમ કરવામાં કસુર કરશો તો અમારા અસીલશ્રી તમોશ્રીનાઓના કબજામાં રહેલ વાહન જપ્ત કરવાની કાર્યવાહી ઉપરાંત તમોશ્રીનાઓ વિરૂધ્ધ પોલીસ ફરીયાદ તેમજ દિવાની તેમજ ફોજદારી કાર્યવાહી કાયદેસરની સલાહ મળ્યા મુજબ તમોશ્રીનાઓના ખર્ચે અને જોખમે કરશે તેની ગંભીર નોંધ લેશોજી.<br /><br />

(6)	આ નોટીસ તમોશ્રીનાની કસુરથી આપવી પડેલ હોઈ સદર નોટીસ ખર્ચના રૂ. 501/- તમોશ્રીનાઓ અમારા અસીલશ્રીનાને ચુકવી આપવા.
<br /><br />

સ્થળ : અમદાવાદ


                                                                       <div class="lee"> મારી મારફતે<br /> <?php echo $advocate['secondary_advocate_name']; ?><br /><br />.......................................</div>      
<div style="page-break-after:always"></div>

</div>
<?php } ?>

<div style="page-break-after:always;"></div>
<?php } ?>

</div>
<div class="clearfix"></div>