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
	 $whole_bucket_amount =  $whole_bucket_amount + ($e * $whole);
	 $bucket_string = $bucket_string." રૂ.".$e."/- ના એક એવા ".number_format($whole,0);
	 if(count($bucket_details)>1)
	 $bucket_string=$bucket_string." | ";
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
Mob: 9426060148 
</div>
<div  style="float:Right; ">
Landline: 079-25454626
</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>
<?php if(is_numeric($file['agency_id']))  { ?>
<div style="text-align:center;padding-top:10px" >
<?php ?>Franchisee of <?php echo getAgencyNameFromFileId($file_id); ?> 
</div>
<?php } ?>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">NOTICE</div>


                                     <div style="padding-left:110px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:30px;">

<div class="customer_address" style="float:left;width:48%;">(1)<?php echo $notice['customer_name']; ?> (<?php echo $file['file_number']; ?>) Party,<br /><pre><?php echo $notice['customer_address']; ?></pre><span class=""><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span></div> 
<div style="clear:both"></div>
<div class="customer_address" style="float:left;width:48%;margin-top:80px;">(2)<?php echo $notice['guarantor_name']; ?> (<?php echo $file['file_number']; ?>) Guarantor,<br /><pre><?php echo $notice['guarantor_address']; ?></pre></div>
</div>
<br /><br />
<div style="clear:both"></div>
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?> કુલમુખ્યતાર મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b> ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. પોસ્ટ દ્વારા નોટીસ આપી જણાવવાનું કે ......<br />
(1)	અમો  <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?> એ નામથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા નાણાં ચૂકવવાનો કાયદેસરનો વ્યાપાર ધંધો કરતા આવેલ છે અને સદરહુ તમામ ધંધાકીય વ્યવહાર ઓફીસના સરનામેથી કરતા આવેલ છે.<br />
(2)	તમોશ્રીના નં.1 તથા નં.2 નાઓ અમારી  ઉપરોકત જણાવેલ ઓફીસના સરનામે રૂબરૂમાં આવેલ અને વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> ઉપર અમારી પાસેથી એગ્રીમેન્ટ કરી જરૂરીયાત મુજબના નાણાં મેરવેલ  છે અને સદરહુ એગ્રીમેન્ટ મુજબ માસિક હપ્તાઓમાં નાણાં પરત ચુકવવા સંમત થયેલ અને સદરહુ એગ્રીમેન્ટ ઉપર તમોશ્રીના નં.1 દ્વારા અમારી ઉપરોકત જણાવેલ સરનામે સહી મત્તુ કરી આપવામાં આવેલ અને તમોશ્રીનાં નં.2 
દ્વારા તમોશ્રીના નં.1 ના જમીનદાર તરીકે સદરહુ એગ્રીમેન્ટમાં સહી મત્તુ કરી આપવામાં આવેલ. જે અંગેની નોંધ અમારા દ્વારા ઉપરોકત વાહનની આર.સી. બુકમાં પણ કરવામાં આવેલ.
<br />
(3)	અમો તેમજ તમોશ્રીનાઓ વચ્ચે કરવામાં આવેલ કાયદેસરના એગ્રીમેન્ટની શરતો મુજબ તમોશ્રીનાઓ અમારી પાસેથી મેળવવામાં આવેલ નાણાં  રૂ. <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના એક એવા ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના એક એવા ".$e['duration']." | ";
									  }
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારી પાસે નિયમિત રીતે પરત ચુકવવા સંમત થયેલ તેમજ બંધાયેલ હતા અને તે મુજબ તમોશ્રીનાઓ એગ્રીમેન્ટ મુજબના સંપૂર્ણ નાણાંની ચુકવણી કરવા સંમત થયેલ તેમજ બંધાયેલ. પરંતુ તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ નિયમિત રીતે માસિક હપ્તાના નાણાં ચુકવી આપવામાં આવેલ નહિ અને માસિક હપ્તા મુજબના નાણાંની ચુકવણીમાં અક્ષમ્ય બેદરકારીન અને ઘોર ઉપેક્ષા દાખવવામાં આવેલ.
                                
                                   તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ આજદિન સુધી <?php echo floor(getTotalEmiPaidForLoan($loan['loan_id'])); $fraction_amount = getFractionEmiPaidAmountForLoan($loan['loan_id']); if($fraction_amount>0) echo " + રૂ.".$fraction_amount; echo " = ( રૂ. ".getTotalPaymentForLoan($loan['loan_id'])." )"; ?> માસિક હપ્તા તેમજ ની ચુકવણી કરવામાં આવેલ છે 
                                     <div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:20px;">(2)</div> અને અમો તમોશ્રીનાઓ પાસેથી એગ્રીમેન્ટની શરતો મુજબ <?php  echo $bucket_string; ?> માસિક હપ્તા તેમજ એગ્રીમેન્ટની શરતો મુજબ લેટ પેનલ્ટી તથા અન્ય ચાર્જીસ સહીતની રકમ મેળવવા માટે હક્કદાક બનેલ.<br />

(4)	અમારી દ્વારા એગ્રીમેન્ટની શરતો મુજબ લેણી થતી કાયદેસરની રકમ મેળવવા સારૂ અવાર નવાર તમોશ્રીના નં.1 તથા નં.2 ના ઓનો રૂબરૂમાં તેમજ ટેલીફોન દ્વારા સંપર્ક કરવામાં આવેલ છે તેમ છતાં તમોશ્રીનાઓ દ્વારા તે અંગે ઘોર ઉપેક્ષા તથા બેદરકારી દાખવવામાં આવી રહેલ છે. અમો તમોશ્રીના નં. 1 સમક્ષ એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ મેળવવા સારૂ રૂબરૂમાં આવેલ ત્યારે તમોશ્રીનાઓ દ્વારા અમારી સાથે ખુબ જ ઉધ્ધાત ભર્યુ વર્તન કરવામાં આવેલ છે અને અમોએ જણાવવામાં આવેલ છે કે, "તમોને એકપણ રૂપિયો મળશે નહિ કે વાહન પણ મળશે નહિ તમારાથી થાય તે કરી લેજો" તમોશ્રીના નં.1 દ્વારા કરવામાં આવેલ આવા ઉધ્ધતાઈ ભર્યા વર્તનની જાણ અમોએ તમોશ્રીના નં.2 નાને કરવામાં આવેલ પરંતુ તમોશ્રીના નં.2 દ્વારા પણ અમારી કાયદેસરની લેણી થતી રકમ ચુકવી આપવામાં ઘોર ઉપેક્ષા તેમજ બેદરકારી દાખવવામાં આવેલ છે 

ઉપરાંત અમારી બાકી નિકળતી કાયદેસરની રકમ ચુકવી આપવાની કોઈ પણ પ્રકારે તૈયારી બતાવવામાં આવેલ નથી.
<br />
(5)	આમ, તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા નાણાં મેળવી એગ્રીમેન્ટની શરતો મુજબ અમારી નાણાંની પરત ચુકવણી કરી આપવામાં આવેલ ન હોઈ તમોશ્રીનાઓએ અમારી સાથે કરેલ એગ્રીમેન્ટની શરતોનો ભંગ કરેલ હોઈ તમોશ્રીનાઓએ અમારી સાથે વિશ્વાઘાત તેમજ છેતરપીંડી આચરવામાં આવેલ છે. તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી તેઓની એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ આજદિન સુધી ચુકવી આપવામાં આવેલ ન હોઈ તેમજ એગ્રીમેન્ટની શરતો મુજબ અમારી તમોશ્રીનાઓ પાસેથી બાકી નિકળતા માસિક હપ્તા, લેટ હપ્તાની પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય તમામ ચાર્જીસ કાયદેસર રીતે મેળવવા હક્કદાર થતા હોઈ તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયલે છે.
<br />
(6)	આથી તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યેથી દિન-7 માં તમોશ્રીનાઓએ અમારી એગ્રીમેન્ટની શરતો મુજબ બાકી નિકળતા માસિક હપ્તા, લેટ પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય ચાર્જીસ રોકડમાં ચુકવી આપી અમારી હાથની કાયદેસરની પહોંચ મેળવી લેવી. જો તેમ કરવામાં કસુર કરશો તો અમો તમોશ્રીનાઓના કબજામાં રહેલ  <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> વાહનનો પ્રત્યક્ષ કબજો મેળવી સદરહુ વાહનનો અન્યત્ર વેચાણ કરી તેઓની કાયદેસરની બાકી નિકળતી રકમ વસુલ કરશે અને ત્યારબાદ બાકી નિકળતી કાયદેસરની બાકી નિકળતી રકમ માટે તમોશ્રીનાઓ વિરુધ્ધ કાયદેસરની સલાહ મળ્યા મુજબની દિવાની તેમજ ફોજદારી કાનુની કાર્યવાહી તમોશ્રીનાઓના ખર્ચે અને જોખમે હાથ ઘરશે જેની ગંભીરપણે નોંધ લેશોજી.
<br />

તારીખ :  <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?><br />
સ્થળ : અમદાવાદ


                                                                     <div class="lee" style="position:relative;top:-60px;"> મારી મારફતે<br /> <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?>
  <br /><br />.......................................</div>       

</div>
<div style="page-break-after:always"></div>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px;padding-top:5px;">
<div id="page_fold" style="position:relative;top:500px;left:-20px;">___</div>
<div  style="float:left; ">
Mob: 9426060148 
</div>
<div  style="float:Right; ">
Landline: 079-25454626
</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>
<?php if(is_numeric($file['agency_id']))  { ?>
<div style="text-align:center;padding-top:10px" >
<?php ?>Franchisee of <?php echo getAgencyNameFromFileId($file_id); ?> 
</div>
<?php } ?>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">NOTICE</div>


                                     <div style="padding-left:110px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:30px;">
<div class="customer_address" style="float:left;width:48%;">(2)<?php echo $notice['guarantor_name']; ?>,(<?php echo $file['file_number']; ?>) Guarantor,<br /><pre><?php echo $notice['guarantor_address'] ?></pre><span class=""><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span></div> 
<div style="clear:both"></div>
<div class="customer_address" style="float:left;width:48%;margin-top:100px;">(1)<?php echo $notice['customer_name']; ?>, (<?php echo $file['file_number'] ?>) Party, <br /><pre><?php echo $notice['customer_address'] ?></pre></div> 

</div><br /><br /><div style="clear:both"></div>
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?> કુલમુખ્યતાર  મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b>  ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. તથા યુ.પી.સી. પોસ્ટ દ્વારા નોટીસ આપી જણાવવાનું કે ......<br />
(1)	અમો <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?>એ નામથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા નાણાં ચૂકવવાનો કાયદેસરનો વ્યાપાર ધંધો કરતા આવેલ છે અને સદરહુ તમામ ધંધાકીય વ્યવહાર ઓફીસના સરનામેથી કરતા આવેલ છે.<br />
(2)	તમોશ્રીના નં.1 તથા નં.2 નાઓ અમારી  ઉપરોકત જણાવેલ ઓફીસના સરનામે રૂબરૂમાં આવેલ અને વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> ઉપર અમારી પાસેથી એગ્રીમેન્ટ જરૂરીયાત મુજબના નાણાં મેરવેલ છે અને સદરહુ એગ્રીમેન્ટ મુજબ માસિક હપ્તાઓમાં નાણાં પરત ચુકવવા સંમત થયેલ અને સદરહુ એગ્રીમેન્ટ ઉપર તમોશ્રીના નં.1 દ્વારા અમારી ઉપરોકત જણાવેલ સરનામે સહી મત્તુ કરી આપવામાં આવેલ અને તમોશ્રીનાં નં.2 
દ્વારા તમોશ્રીના નં.1 ના જમીનદાર તરીકે સદરહુ એગ્રીમેન્ટમાં સહી મત્તુ કરી આપવામાં આવેલ. જે અંગેની નોંધ અમારા દ્વારા ઉપરોકત વાહનની આર.સી. બુકમાં પણ કરવામાં આવેલ.
<br />
(3)	અમો તેમજ તમોશ્રીનાઓ વચ્ચે કરવામાં આવેલ કાયદેસરના એગ્રીમેન્ટની શરતો મુજબ તમોશ્રીનાઓ અમારી પાસેથી મેળવવામાં આવેલ નાણાં  રૂ. <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના એક એવા ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના એક એવા ".$e['duration']." | ";
									  }
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારી પાસે નિયમિત રીતે પરત ચુકવવા સંમત થયેલ તેમજ બંધાયેલ હતા અને તે મુજબ તમોશ્રીનાઓ એગ્રીમેન્ટ મુજબના સંપૂર્ણ નાણાંની ચુકવણી કરવા સંમત થયેલ તેમજ બંધાયેલ. પરંતુ તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ નિયમિત રીતે માસિક હપ્તાના નાણાં ચુકવી આપવામાં આવેલ નહિ અને માસિક હપ્તા મુજબના નાણાંની ચુકવણીમાં અક્ષમ્ય બેદરકારીન અને ઘોર ઉપેક્ષા દાખવવામાં આવેલ.  તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ આજદિન સુધી <?php echo floor(getTotalEmiPaidForLoan($loan['loan_id'])); $fraction_amount = getFractionEmiPaidAmountForLoan($loan['loan_id']); if($fraction_amount>0) echo " + રૂ.".$fraction_amount; echo " = ( રૂ. ".getTotalPaymentForLoan($loan['loan_id'])." )"; ?> માસિક હપ્તા તેમજ ની ચુકવણી કરવામાં આવેલ છે <div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:20px;">(2)</div> અને અમો તમોશ્રીનાઓ પાસેથી એગ્રીમેન્ટની શરતો મુજબ <?php  echo $bucket_string; ?> માસિક હપ્તા તેમજ એગ્રીમેન્ટની શરતો મુજબ લેટ પેનલ્ટી તથા અન્ય ચાર્જીસ સહીતની રકમ મેળવવા માટે હક્કદાક બનેલ.<br />
(4)	અમારી દ્વારા એગ્રીમેન્ટની શરતો મુજબ લેણી થતી કાયદેસરની રકમ મેળવવા સારૂ અવાર નવાર તમોશ્રીના નં.1 તથા નં.2 ના ઓનો રૂબરૂમાં તેમજ ટેલીફોન દ્વારા સંપર્ક કરવામાં આવેલ છે તેમ છતાં તમોશ્રીનાઓ દ્વારા તે અંગે ઘોર ઉપેક્ષા તથા બેદરકારી દાખવવામાં આવી રહેલ છે. અમો તમોશ્રીના નં. 1 સમક્ષ એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ મેળવવા સારૂ રૂબરૂમાં આવેલ ત્યારે તમોશ્રીનાઓ દ્વારા અમારી સાથે ખુબ જ ઉધ્ધાત ભર્યુ વર્તન કરવામાં આવેલ છે અને અમોને જણાવવામાં આવેલ છે કે, "તમોને એકપણ રૂપિયો મળશે નહિ કે વાહન પણ મળશે નહિ તમારાથી થાય તે કરી લેજો" તમોશ્રીના નં.1 દ્વારા કરવામાં આવેલ આવા ઉધ્ધતાઈ ભર્યા વર્તનની જાણ અમોએ દ્વારા તમોશ્રીના નં.2 નાને કરવામાં આવેલ પરંતુ તમોશ્રીના નં.2 દ્વારા પણ અમારી કાયદેસરની લેણી થતી રકમ ચુકવી આપવામાં ઘોર ઉપેક્ષા તેમજ બેદરકારી દાખવવામાં આવેલ છે 

ઉપરાંત અમારી બાકી નિકળતી કાયદેસરની રકમ ચુકવી આપવાની કોઈ પણ પ્રકારે તૈયારી બતાવવામાં આવેલ નથી.
<br />
(5) આમ, તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા નાણાં મેળવી એગ્રીમેન્ટની શરતો મુજબ અમારી નાણાંની પરત ચુકવણી કરી આપવામાં આવેલ ન હોઈ તમોશ્રીનાઓએ અમારી સાથે કરેલ એગ્રીમેન્ટની શરતોનો ભંગ કરેલ હોઈ તમોશ્રીનાઓએ અમારી સાથે વિશ્વાઘાત તેમજ છેતરપીંડી આચરવામાં આવેલ છે. તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી તેઓની એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ આજદિન સુધી ચુકવી આપવામાં આવેલ ન હોઈ તેમજ એગ્રીમેન્ટની શરતો મુજબ અમારી તમોશ્રીનાઓ પાસેથી બાકી નિકળતા માસિક હપ્તા, લેટ હપ્તાની પેનલ્ટી તેમજ એગ્રીમેન્ટ  મુજબના અન્ય તમામ ચાર્જીસ કાયદેસર રીતે મેળવવા હક્કદાર થતા હોઈ તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયલે છે.<br />

(6)	આથી તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યેથી દિન-7 માં તમોશ્રીનાઓએ અમારી એગ્રીમેન્ટની શરતો મુજબ બાકી નિકળતા માસિક હપ્તા, લેટ પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય ચાર્જીસ રોકડમાં ચુકવી આપી અમારી હાથની કાયદેસરની પહોંચ મેળવી લેવી. જો તેમ કરવામાં કસુર કરશો તો અમો તમોશ્રીનાઓના કબજામાં રહેલ  <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> વાહનનો પ્રત્યક્ષ કબજો મેળવી સદરહુ વાહનનો અન્યત્ર વેચાણ કરી તેઓની કાયદેસરની બાકી નિકળતી રકમ વસુલ કરશે અને ત્યારબાદ બાકી નિકળતી કાયદેસરની બાકી નિકળતી રકમ માટે તમોશ્રીનાઓ વિરુધ્ધ કાયદેસરની સલાહ મળ્યા મુજબની દિવાની તેમજ ફોજદારી કાનુની કાર્યવાહી તમોશ્રીનાઓના ખર્ચે અને જોખમે હાથ ઘરશે જેની ગંભીરપણે નોંધ લેશોજી.
<br />

તારીખ :  <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?><br />
સ્થળ : અમદાવાદ


                                                                     <div class="lee" style="position:relative;top:-70px;float:right;padding-right:20px;"> મારી મારફતે<br /> <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?>
  <br /><br />.......................................</div>       


</div>

<?php }else { ?>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:5px;">
<div id="page_fold" style="position:relative;top:500px;left:-20px;">___</div>
<div  style="float:left; ">
Mob: 9426060148 
</div>
<div  style="float:Right; ">
Landline: 079-25454626
</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>
<?php if(is_numeric($file['agency_id']))  { ?>
<div style="text-align:center;padding-top:10px" >
<?php ?>Franchisee of <?php echo getAgencyNameFromFileId($file_id); ?> 
</div>
<?php } ?>
<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">NOTICE</div>


                                     <div style="padding-left:110px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:30px;">

<div class="customer_address" style="float:left;width:48%;">(1)<?php echo $notice['customer_name']; ?> (<?php echo $file['file_number']; ?>) Party,<br /><pre><?php echo $notice['customer_address']; ?></pre><span class=""><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span></div> 
<div style="clear:both"></div>

</div><br /><br /><br />
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?> કુલમુખ્યતાર  મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b>  ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. તથા યુ.પી.સી. પોસ્ટ દ્વારા નોટીસ આપી જણાવવાનું કે ......<br /><br />
(1)	અમો <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?>એ નામથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા નાણાં ચૂકવવાનો કાયદેસરનો વ્યાપાર ધંધો કરતા આવેલ છે અને સદરહુ તમામ ધંધાકીય વ્યવહાર ઓફીસના સરનામેથી કરતા આવેલ છે.<br />
(2)	તમો ઉપરોકત જણાવેલ ઓફીસના સરનામે રૂબરૂમાં આવેલ અને વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> ઉપર અમારી પાસેથી એગ્રીમેન્ટ અમારી પાસેથી એગ્રીમેન્ટ જરૂરીયાત મુજબના નાણાં મેરવેલ છે અને સદરહુ એગ્રીમેન્ટ ઉપર તમોશ્રીનાઓ દ્વારા અમારી ઉપરોકત જણાવેલ સરનામે સહી મત્તુ કરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારી પાસેથી મેળવવામાં આવેલ એગ્રીમેન્ટ વાળા વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> ની આર. સી બુકમાં પણ આ અંગેની અમારા દ્વારા નોંધ કરાવવામાં આવેલ છે.<br />
(3)	અમારી પાસે તેમજ તમોશ્રીનાઓ વચ્ચે કરવામાં આવેલ કાયદેસરના એગ્રીમેન્ટની શરતો મુજબ તમોશ્રીનાઓ અમારી પાસેથી મેળવવામાં આવેલ નાણાં રૂ. <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના એક એવા ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના એક એવા ".$e['duration']." | ";
									  }
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારી પાસેથી નિયમિત રીતે પરત ચુકવવા સંમત થયેલ તેમજ બંધાયેલા હતા અને તે મુજબ તમોશ્રીનાઓ એગ્રીમેન્ટ મુજબના સંપૂર્ણ નાણાંની ચુકવણી કરવા સંમત થયેલ તેમજ બંધાયેલ. પરંતુ તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ નિયમિત રીતે માસિક હપ્તાના નાણાં ચુકવી આપવામાં આવેલ નહિ અને માસિક હપ્તા મુજબના નાણાંની ચુકવણીમાં અક્ષમ્ય બેદરકારી અને ઘોર ઉપેક્ષા દાખવવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ આજદિન સુધી  <?php echo floor(getTotalEmiPaidForLoan($loan['loan_id'])); $fraction_amount = getFractionEmiPaidAmountForLoan($loan['loan_id']); if($fraction_amount>0) echo " + રૂ.".$fraction_amount; echo " = ( રૂ. ".getTotalPaymentForLoan($loan['loan_id'])." )"; ?> માસિક હપ્તા તેમજ ની ચુકવણી કરવામાં આવેલ છે અને અમો તમોશ્રીનાઓ પાસેથી એગ્રીમેન્ટની શરતો મુજબ <?php  echo $bucket_string; ?> માસિક હપ્તા તેમજ એગ્રીમેન્ટની શરતો મુજબ લેટ પેનલ્ટી તથા અન્ય ચાર્જીસ સહીતની રકમ મેળવવા માટે હક્કદાક બનેલ.
<div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:80px;">(2)</div>
(4)	અમારી દ્વારા એગ્રીમેન્ટની શરતો મુજબ લેણી થતી કાયદેસરની રકમ મેળવવા સારૂ અવાર નવાર તમોશ્રીનાઓનો રૂબરૂ તેમજ ટેલીફોન દ્વારા સંપર્ક કરવામાં આવેલ છે તેમ છતાં તમોશ્રીનાઓ દ્વારા તે અંગે ઘોર ઉપેક્ષા તથા બેદરકારી દાખવવામાં આવી રહેલ છે. અમો તમોશ્રીનાઓને સમક્ષ એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ મેળવવા સારૂ રૂબરૂમાં આવેલ ત્યારે તમોશ્રીનાઓ દ્વારા અમારી સાથે ખુબજ ઉધ્ધાતાઈ ભર્યુ વર્તન કરવામાં આવેલ છે અને અમોને જણાવવામાં આવેલ છે કે, "તમોને એકપણ રૂપિયો મળશે નહિ કે વાહન પણ મળશે નહિ તમારાથી થાય તે કરી લેજો"<br />
(5)	આમ, તમોશ્રીનાઓ દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા નાણાં મેળવી એગ્રીમેન્ટની શરતો મુજબ અમારી નાણાંની પરત ચુકવણી કરી આપવામાં આવેલ ન હોઈ તમોશ્રીનાઓએ અમારી સાથે કરેલ એગ્રીમેન્ટની શરતોનો ભંગ કરેલ હોઈ તમોશ્રીનાઓએ અમારી સાથે વિશ્વાસઘાત તેમજ છેતરપીંડી આચરવામાં આવેલ છે. તમોશ્રીનાઓ દ્વારા અમારી તેઓની એગ્રીમેન્ટની મુજબની કાયદેસરની લેણી રકમ આજદિન સુધી ચુકવી આપવામાં આવેલ ન હોઈ તેમજ એગ્રીમેન્ટની શરતો મુજબ અમારી તમોશ્રીનાઓ પાસેથી બાકી નિકળતા માસિક હપ્તા, લેટ હપ્તાની પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય તમામ ચાર્જીસ કાયદેસર રીતે મેળવવા હક્કદાર થતા હોઈ તમોશ્રીનાઓને આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયેલ છે.<br />

(6)	આથી તમોશ્રીનાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યેથી દિન-7 માં તમોશ્રીનાઓએ અમારા એગ્રીમેન્ટની શરતો મુજબ બાકી નિકળતા માસિક હપ્તા, લેટ પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય ચાર્જીસ રોકડમાં ચુકવી આપી અમારા હાથની કાયદેસરની પહોંચ મેળવી લેવી જો તેમ કરવામાં કસુર કરશો તો અમો તમોશ્રીનાઓના કબજામાં રહેલ ઉપરોકત <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> વાહનનો પ્રત્યક્ષ કબજો મેળવી સદરહુ વાહનનો અન્યત્ર વેચાણ કરી તેઓની કાયદેસરની બાકી લેણી નિકળતી રકમ વસુલ કરશે અને ત્યારબાદ બાકી નિકળતી કાયદેસરની બાકી લેણી રકમ માટે તમોશ્રીનાઓ વિરૂધ્ધ કાયદેસરની સલાહ મળ્યા મુજબની દિવાની તેમજ ફોજદારી કાનુની કાર્યવાહી તમોશ્રીનાઓના ખર્ચે અને જોખમે હાથ ધરશે જેની ગંભીરપણે નોંધ લેશોજી.	
<br />

તારીખ :  <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?><br />
સ્થળ : અમદાવાદ


                                                                     <div class="lee"> મારી મારફતે<br />  <?php if(isset($our_company)) { if(validateForNull($our_company['secondary_company_name'])) echo $our_company['secondary_company_name']; else echo $our_company['our_company_name'];   ?> <?php  } else if(isset($agency)) { echo $agency['agency_name']; ?> <?php } ?><br /><br />.......................................</div>      
<div style="page-break-after:always"></div>

</div>
<?php } ?>

<div style="page-break-after:always;"></div>
<?php } ?>

</div>
<div class="clearfix"></div>