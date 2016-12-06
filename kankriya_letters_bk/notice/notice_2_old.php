<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}

$notice_id=$_GET['id'];
$notice=getNoticeById($notice_id);
$file_id=$notice['file_id'];
$file=getFileDetailsByFileId($file_id);
$loan=getLoanDetailsByFileId($file_id);
$guarantor=getGuarantorDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
if($vehicle && $vehicle!="error")
{
 $company = getVehicleCompanyById($vehicle['vehicle_company_id']);  
  $modelName = getModelNameById($vehicle['model_id']);	
}
$bucket_details=getBucketDetailsForLoan($loan['loan_id'],date('d/m/Y',strtotime($notice['notice_date'])));

$bucket_string = "";

 if(isset($bucket_details) && $bucket_details!=0 && is_array($bucket_details) && count($bucket_details)>0) 
 { 
 
 foreach($bucket_details as $e=>$corr_bucket) 
 { 
  
 $bucket_string= $bucket_string." રૂ.".$e."/- ના એક એવા ".$corr_bucket;
 if(count($bucket_details)>1)
 $bucket_string=$bucket_string." | ";
 } 
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
<?php if(isset($guarantor) && is_numeric($guarantor['guarantor_id'])) { ?>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
<div style="text-align:left;font-size:24px;text-decoration:underline;">પાર્ટી અને ગેરેન્ટ ની નોટીસ</div>
<div  style="float:left; ">
રેફ નં. <b><?php echo $file['file_number']; ?></b>
</div>
<div  style="float:Right; ">
તારીખ: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?>
</div>
<div  style="text-align:center; font-size:34px;">
નોટીસ રજી. એ.ડી. પોસ્ટ દ્વારા
</div>


                                     <div style="padding-left:100px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:40px;">
<div class="prati" style="padding-bottom:0;">પ્રતિ ,</div>
<div class="customer_address" style="float:left;width:48%;">(1)<?php echo $notice['customer_name']; ?>,<br /><pre><?php echo $notice['customer_address'] ?></pre></div> 
<div style="clear:both"></div>
<div class="customer_address" style="float:left;width:48%;">(2)<?php echo $notice['guarantor_name']; ?>,<br /><pre><?php echo $notice['guarantor_address'] ?></pre></div>
</div><br /><br />
<div style="clear:both"></div>
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b> કુલમુખ્યતાર કાંકરીયા ફાઈનાન્સના ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. તથા યુ.પી.સી. પોસ્ટ દ્વારા નોટીસ આપી જણાવવાનું કે ......<br />
(1)	અમો કાંકરીયા ફાઈનાન્સએ નામથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા કાયદેસરનો વ્યાપાર ધંધો કરતા આવેલ છે અને સદરહુ તમામ ધંધાકીય વ્યવહાર ઓફીસના સરનામેથી કરતા આવેલ છે.<br />
(2)	તમોશ્રીના નં.1 તથા નં.2 નાઓ અમારી  ઉપરોકત જણાવેલ ઓફીસના સરનામે રૂબરૂમાં આવેલ અને વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> ઉપર અમારી પાસેથી એગ્રીમેન્ટ કરેલું છે અને સદરહુ એગ્રીમેન્ટ મુજબ માસિક હપ્તાઓમાં અમને ચુકવવા સંમત થયેલ અને સદરહુ એગ્રીમેન્ટ ઉપર ના તમોશ્રીના નં.1 દ્વારા અમારી ઉપરોકત જણાવેલ સરનામે સહી મત્તુ કરી આપવામાં આવેલ અને તમોશ્રીનાં નં.2 
દ્વારા તમોશ્રીના નં.1 ના જમીનદાર તરીકે સદરહુ એગ્રીમેન્ટમાં સહી મત્તુ કરી આપવામાં આવેલ. જે અંગેની નોંધ અમારી દ્વારા ઉપરોકત વાહનની આર.સી. બુકમાં પણ કરવામાં આવેલ.
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
                                 
                                   તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ આજદિન સુધી <?php echo getTotalEmiPaidForLoan($loan['loan_id']); ?> માસિક હપ્તા તેમજ ની ચુકવણી કરવામાં આવેલ છે અને અમારી તમોશ્રીનાઓ પાસેથી એગ્રીમેન્ટની શરતો મુજબ <?php  echo $bucket_string; ?> માસિક હપ્તા તેમજ એગ્રીમેન્ટની શરતો મુજબ લેટ પેનલ્ટી તથા અન્ય ચાર્જીસ સહીતની રકમ મેળવવા માટે હક્કદાક બનેલ.
 <div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:80px;">(2)</div>
(4)	અમારી દ્વારા એગ્રીમેન્ટની શરતો મુજબ લેણી થતી કાયદેસરની રકમ મેળવવા સારૂ અવાર નવાર તમોશ્રીના નં.1 તથા નં.2 ના ઓનો રૂબરૂમાં તેમજ ટેલીફોન દ્વારા સંપર્ક કરવામાં આવેલ છે તેમ છતાં તમોશ્રીનાઓ દ્વારા તે અંગે ઘોર ઉપેક્ષા તથા બેદરકારી દાખવવામાં આવી રહેલ છે. અમો તેમજ તમોશ્રીના નં. 1 ત્યાં એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ મેળવવા સારૂ રૂબરૂમાં આવેલ ત્યારે તમોશ્રીનાઓ દ્વારા અમારી સાથે ખુબ જ ઉધ્ધાત ભર્યુ વર્તન કરવામાં આવેલ છે અને અમારી જણાવવામાં આવેલ છે કે, "તમોને એકપણ રૂપિયો મળશે નહિ કે વાહન પણ મળશે નહિ તમારાથી થાય તે કરી લેજો" તમોશ્રીના નં.1 દ્વારા કરવામાં આવેલ આવા ઉધ્ધતાઈ ભર્યા વર્તનની જાણ અમારી દ્વારા તમોશ્રીના નં.2 નાને કરવામાં આવેલ પરંતુ તમોશ્રીના નં.2 દ્વારા પણ અમારી તેઓની કાયદેસરની લેણી થતી રકમ ચુકવી આપવામાં ઘોર ઉપેક્ષા તેમજ બેદરકારી દાખવવામાં આવેલ છે 

ઉપરાંત અમારી બાકી નિકળતી કાયદેસરની રકમ ચુકવી આપવાની કોઈ પણ પ્રકારે તૈયારી બતાવવામાં આવેલ નથી.
<br />
(5)	અમો તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા નાણાં મેળવી એગ્રીમેન્ટની શરતો મુજબ અમારી નાણાંની પરત ચુકવણી કરી આપવામાં આવેલ ન હોઈ તમોશ્રીનાઓએ અમારી સાથે કરેલ એગ્રીમેન્ટની શરતોનો ભંગ કરેલ હોઈ તમોશ્રીનાઓએ અમારી સાથે વિશ્વાઘાત તેમજ છેતરપીંડી આચરવામાં આવેલ છે. તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી તેઓની એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ આજદિન સુધી ચુકવી આપવામાં આવેલ ન હોઈ તેમજ એગ્રીમેન્ટની શરતો મુજબ અમારી તમોશ્રીનાઓ પાસેથી બાકી નિકળતા માસિક હપ્તા, લેટ હપ્તાની પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય તમાં ચાર્જીસ કાયદેસર રીતે મેળવવા હક્કદાર થતા હોઈ તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયલે છે.
<br />
(6)	આથી તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યેથી દિન-7 માં તમોશ્રીનાઓએ અમારી એગ્રીમેન્ટની શરતો મુજબ બાકી નિકળતા માસિક હપ્તા, લેટ પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય ચાર્જીસ રોકડમાં ચુકવી આપી અમારી હાથની કાયદેસરની પહોંચ મેળવી લેવી. જો તેમ કરવામાં કસુર કરશો તો અમારી તમોશ્રીનાઓના કબજામાં રહેલ  <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> વાહનનો પ્રત્યક્ષ કબજો મેળવી સદરહુ વાહનનો અન્યત્ર વેચાણ કરી તેઓની કાયદેસરની બાકી નિકળતી રકમ વસુલ કરશે અને ત્યારબાદ બાકી નિકળતી કાયદેસરની બાકી નિકળતી રકમ માટે તમોશ્રીનાઓ વિરુધ્ધ કાયદેસરની સલાહ મળ્યા મુજબની દિવાની તેમજ ફોજદારી કાનુની કાર્યવાહી તમોશ્રીનાઓના ખર્ચે અને જોખમે હાથ ઘરશે જેની ગંભીરપણે નોંધ લેશોજી.


તારીખ :  <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?><br />
સ્થળ : અમદાવાદ


                                                                     <div class="lee"> મારી મારફતે<br /> કાંકરીયા ફાઈનાન્સ  <br /><br />.......................................</div>       

</div>
<div style="page-break-after:always"></div>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px;padding-top:0px;">
<div style="text-align:left;font-size:24px;text-decoration:underline;">પાર્ટી અને ગેરેન્ટની નોટીસ</div>
<div  style="float:left; ">
રેફ નં. <b><?php echo $file['file_number']; ?></b>
</div>
<div  style="float:Right; ">
તારીખ: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?>
</div>
<div  style="text-align:center; font-size:34px;">
નોટીસ રજી. એ.ડી. પોસ્ટ દ્વારા
</div>


                                     <div style="padding-left:100px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:40px;">
<div class="prati" style="padding-bottom:0;">પ્રતિ ,</div>

<div class="customer_address" style="float:left;width:48%;">(2)<?php echo $notice['guarantor_name']; ?>,<br /><pre><?php echo $notice['guarantor_address'] ?></pre></div> 
<div style="clear:both"></div>
<div class="customer_address" style="float:left;width:48%;">(1)<?php echo $notice['customer_name']; ?>,<br /><pre><?php echo $notice['customer_address'] ?></pre></div> 

</div><br /><br /><div style="clear:both"></div>
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b> કુલમુખ્યતાર કાંકરીયા ફાઈનાન્સના ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. તથા યુ.પી.સી. પોસ્ટ દ્વારા નોટીસ આપી જણાવવાનું કે ......<br />
(1)	અમો કાંકરીયા ફાઈનાન્સએ નામથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા કાયદેસરનો વ્યાપાર ધંધો કરતા આવેલ છે અને સદરહુ તમામ ધંધાકીય વ્યવહાર ઓફીસના સરનામેથી કરતા આવેલ છે.<br />
(2)	તમોશ્રીના નં.1 તથા નં.2 નાઓ અમારી  ઉપરોકત જણાવેલ ઓફીસના સરનામે રૂબરૂમાં આવેલ અને વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> ઉપર અમારી પાસેથી એગ્રીમેન્ટ કરેલું છે અને સદરહુ એગ્રીમેન્ટ મુજબ માસિક હપ્તાઓમાં અમને ચુકવવા સંમત થયેલ અને સદરહુ એગ્રીમેન્ટ ઉપર ના તમોશ્રીના નં.1 દ્વારા અમારી ઉપરોકત જણાવેલ સરનામે સહી મત્તુ કરી આપવામાં આવેલ અને તમોશ્રીનાં નં.2 
દ્વારા તમોશ્રીના નં.1 ના જમીનદાર તરીકે સદરહુ એગ્રીમેન્ટમાં સહી મત્તુ કરી આપવામાં આવેલ. જે અંગેની નોંધ અમારી દ્વારા ઉપરોકત વાહનની આર.સી. બુકમાં પણ કરવામાં આવેલ.
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
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારી પાસે નિયમિત રીતે પરત ચુકવવા સંમત થયેલ તેમજ બંધાયેલ હતા અને તે મુજબ તમોશ્રીનાઓ એગ્રીમેન્ટ મુજબના સંપૂર્ણ નાણાંની ચુકવણી કરવા સંમત થયેલ તેમજ બંધાયેલ. પરંતુ તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ નિયમિત રીતે માસિક હપ્તાના નાણાં ચુકવી આપવામાં આવેલ નહિ અને માસિક હપ્તા મુજબના નાણાંની ચુકવણીમાં અક્ષમ્ય બેદરકારીન અને ઘોર ઉપેક્ષા દાખવવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ આજદિન સુધી <?php echo getTotalEmiPaidForLoan($loan['loan_id']); ?> માસિક હપ્તા તેમજ ની ચુકવણી કરવામાં આવેલ છે અને અમારી તમોશ્રીનાઓ પાસેથી એગ્રીમેન્ટની શરતો મુજબ <?php  echo $bucket_string; ?> માસિક હપ્તા તેમજ એગ્રીમેન્ટની શરતો મુજબ લેટ પેનલ્ટી તથા અન્ય ચાર્જીસ સહીતની રકમ મેળવવા માટે હક્કદાક બનેલ.
<div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:80px;">(2)</div>
(4)	અમારી દ્વારા એગ્રીમેન્ટની શરતો મુજબ લેણી થતી કાયદેસરની રકમ મેળવવા સારૂ અવાર નવાર તમોશ્રીના નં.1 તથા નં.2 ના ઓનો રૂબરૂમાં તેમજ ટેલીફોન દ્વારા સંપર્ક કરવામાં આવેલ છે તેમ છતાં તમોશ્રીનાઓ દ્વારા તે અંગે ઘોર ઉપેક્ષા તથા બેદરકારી દાખવવામાં આવી રહેલ છે. અમો તેમજ તમોશ્રીના નં. 1 ત્યાં એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ મેળવવા સારૂ રૂબરૂમાં આવેલ ત્યારે તમોશ્રીનાઓ દ્વારા અમારી સાથે ખુબ જ ઉધ્ધાત ભર્યુ વર્તન કરવામાં આવેલ છે અને અમારી જણાવવામાં આવેલ છે કે, "તમોને એકપણ રૂપિયો મળશે નહિ કે વાહન પણ મળશે નહિ તમારાથી થાય તે કરી લેજો" તમોશ્રીના નં.1 દ્વારા કરવામાં આવેલ આવા ઉધ્ધતાઈ ભર્યા વર્તનની જાણ અમારી દ્વારા તમોશ્રીના નં.2 નાને કરવામાં આવેલ પરંતુ તમોશ્રીના નં.2 દ્વારા પણ અમારી તેઓની કાયદેસરની લેણી થતી રકમ ચુકવી આપવામાં ઘોર ઉપેક્ષા તેમજ બેદરકારી દાખવવામાં આવેલ છે 

ઉપરાંત અમારી બાકી નિકળતી કાયદેસરની રકમ ચુકવી આપવાની કોઈ પણ પ્રકારે તૈયારી બતાવવામાં આવેલ નથી.
<br />
(5)	(5)	અમો તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા નાણાં મેળવી એગ્રીમેન્ટની શરતો મુજબ અમારી નાણાંની પરત ચુકવણી કરી આપવામાં આવેલ ન હોઈ તમોશ્રીનાઓએ અમારી સાથે કરેલ એગ્રીમેન્ટની શરતોનો ભંગ કરેલ હોઈ તમોશ્રીનાઓએ અમારી સાથે વિશ્વાઘાત તેમજ છેતરપીંડી આચરવામાં આવેલ છે. તમોશ્રીના નં.1 તથા નં.2 દ્વારા અમારી તેઓની એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ આજદિન સુધી ચુકવી આપવામાં આવેલ ન હોઈ તેમજ એગ્રીમેન્ટની શરતો મુજબ અમારી તમોશ્રીનાઓ પાસેથી બાકી નિકળતા માસિક હપ્તા, લેટ હપ્તાની પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય તમાં ચાર્જીસ કાયદેસર રીતે મેળવવા હક્કદાર થતા હોઈ તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયલે છે.<br />

(6)	(6)	આથી તમોશ્રીના નં.1 તથા નં.2 નાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યેથી દિન-7 માં તમોશ્રીનાઓએ અમારી એગ્રીમેન્ટની શરતો મુજબ બાકી નિકળતા માસિક હપ્તા, લેટ પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય ચાર્જીસ રોકડમાં ચુકવી આપી અમારી હાથની કાયદેસરની પહોંચ મેળવી લેવી. જો તેમ કરવામાં કસુર કરશો તો અમારી તમોશ્રીનાઓના કબજામાં રહેલ  <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> વાહનનો પ્રત્યક્ષ કબજો મેળવી સદરહુ વાહનનો અન્યત્ર વેચાણ કરી તેઓની કાયદેસરની બાકી નિકળતી રકમ વસુલ કરશે અને ત્યારબાદ બાકી નિકળતી કાયદેસરની બાકી નિકળતી રકમ માટે તમોશ્રીનાઓ વિરુધ્ધ કાયદેસરની સલાહ મળ્યા મુજબની દિવાની તેમજ ફોજદારી કાનુની કાર્યવાહી તમોશ્રીનાઓના ખર્ચે અને જોખમે હાથ ઘરશે જેની ગંભીરપણે નોંધ લેશોજી.
<br />

તારીખ :  <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?><br />
સ્થળ : અમદાવાદ


                                                                     <div class="lee"> મારી મારફતે<br /> કાંકરીયા ફાઈનાન્સ  <br /><br />.......................................</div>       


</div>

<?php }else { ?>
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
<div style="text-align:left;font-size:24px;text-decoration:underline;">પાર્ટી ની નોટીસ</div>
<div  style="float:left; ">
રેફ નં. <b><?php echo $file['file_number']; ?></b>
</div>
<div  style="float:Right; ">
તારીખ: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?>
</div>
<div  style="text-align:center; font-size:34px;">
નોટીસ રજી. એ.ડી. પોસ્ટ દ્વારા
</div>


                                     <div style="padding-left:100px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:40px;">
<div class="prati" style="padding-bottom:0;">પ્રતિ ,</div>

<div class="customer_address" style="float:left;width:48%;"><?php echo $notice['customer_name']; ?>,<br /><pre><?php echo $notice['customer_address'] ?></pre></div> 

</div><br /><br /><br /><br />
                                 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  આથી અમો નીચે સહી કરનાર <b style="font-weight:bold;"> મહેન્દ્રરાજ ભંવરલાલ કાંકરીયા </b> કુલમુખ્યતાર કાંકરીયા ફાઈનાન્સના ઠે. 35, વાણીજય ભવન, દિવાન બલ્લુભાઈ સ્કુલ સામે, કાંકરીયા, અમદાવાદ તમોશ્રીનાને આ રજી. એ.ડી. તથા યુ.પી.સી. પોસ્ટ દ્વારા નોટીસ આપી જણાવવાનું કે ......<br /><br />
(1)	અમો કાંકરીયા ફાઈનાન્સએ નામથી વાહનો ઉપર એગ્રીમેન્ટ દ્વારા કાયદેસરનો વ્યાપાર ધંધો કરતા આવેલ છે અને સદરહુ તમામ ધંધાકીય વ્યવહાર ઓફીસના સરનામેથી કરતા આવેલ છે.<br /><br />
(2)	તમો ઉપરોકત જણાવેલ ઓફીસના સરનામે રૂબરૂમાં આવેલ અને વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> ઉપર અમારી પાસેથી એગ્રીમેન્ટ કરેલું છે અને સદરહુ એગ્રીમેન્ટ ઉપર તમોશ્રીનાઓ દ્વારા અમારી ઉપરોકત જણાવેલ સરનામે સહી મત્તુ કરી આપવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારી પાસેથી મેળવવામાં આવેલ એગ્રીમેન્ટ વાળા વાહન નં. <?php echo $vehicle['vehicle_reg_no']; ?> ની આર. સી બુકમાં પણ આ અંગેની અમારી દ્વારા નોંધ કરાવવામાં આવેલ છે.<br /><br />
(3)	અમારી પાસે તેમજ તમોશ્રીનાઓ વચ્ચે કરવામાં આવેલ કાયદેસરના એગ્રીમેન્ટની શરતો મુજબ તમોશ્રીનાઓ અમારી પાસેથી મેળવવામાં આવેલ નાણાં રૂ. <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના એક એવા ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના એક એવા ".$e['duration']." | ";
									  }
								  
								  } ?></b> માસિક હપ્તાઓમાં અમારી પાસેથી નિયમિત રીતે પરત ચુકવવા સંમત થયેલ તેમજ બંધાયેલા હતા અને તે મુજબ તમોશ્રીનાઓ એગ્રીમેન્ટ મુજબના સંપૂર્ણ નાણાંની ચુકવણી કરવા સંમત થયેલ તેમજ બંધાયેલ. પરંતુ તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ નિયમિત રીતે માસિક હપ્તાના નાણાં ચુકવી આપવામાં આવેલ નહિ અને માસિક હપ્તા મુજબના નાણાંની ચુકવણીમાં અક્ષમ્ય બેદરકારી અને ઘોર ઉપેક્ષા દાખવવામાં આવેલ. તમોશ્રીનાઓ દ્વારા અમારી સાથે કરવામાં આવેલ એગ્રીમેન્ટની શરતો મુજબ આજદિન સુધી રૂ. 7,500/- ના એક એવા <?php echo getTotalEmiPaidForLoan($loan['loan_id']); ?> માસિક હપ્તા તેમજ ની ચુકવણી કરવામાં આવેલ છે અને અમારી તમોશ્રીનાઓ પાસેથી એગ્રીમેન્ટની શરતો મુજબ <?php  echo $bucket_string; ?> માસિક હપ્તા તેમજ એગ્રીમેન્ટની શરતો મુજબ લેટ પેનલ્ટી તથા અન્ય ચાર્જીસ સહીતની રકમ મેળવવા માટે હક્કદાક બનેલ.
<div style="page-break-after:always"></div>
<div style="width:100%;text-align:center;padding-bottom:80px;">(2)</div>
(4)	અમારી દ્વારા એગ્રીમેન્ટની શરતો મુજબ લેણી થતી કાયદેસરની રકમ મેળવવા સારૂ અવાર નવાર તમોશ્રીનાઓનો રૂબરૂ તેમજ ટેલીફોન દ્વારા સંપર્ક કરવામાં આવેલ છે તેમ છતાં તમોશ્રીનાઓ દ્વારા તે અંગે ઘોર ઉપેક્ષા તથા બેદરકારી દાખવવામાં આવી રહેલ છે. અમારી તમોશ્રીનાઓને ત્યાં એગ્રીમેન્ટ મુજબની કાયદેસરની લેણી રકમ મેળવવા સારૂ રૂબરૂમાં આવેલ ત્યારે તમોશ્રીનાઓ દ્વારા અમારી સાથે ખુબજ ઉધ્ધાતાઈ ભર્યુ વર્તન કરવામાં આવેલ છે અને અમારી સાથે જણાવવામાં આવેલ છે કે, "તમોને એકપણ રૂપિયો મળશે નહિ કે વાહન પણ મળશે નહિ તમારાથી થાય તે કરી લેજો"<br />
(5)	આમો, તમોશ્રીનાઓ દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા અમારી પાસેથી કાયદેસરના એગ્રીમેન્ટ દ્વારા નાણાં મેળવી એગ્રીમેન્ટની શરતો મુજબ અમારી નાણાંની પરત ચુકવણી કરી આપવામાં આવેલ ન હોઈ તમોશ્રીનાઓએ અમારી સાથે કરેલ એગ્રીમેન્ટની શરતોનો ભંગ કરેલ હોઈ તમોશ્રીનાઓએ અમારી સાથે વિશ્વાસઘાત તેમજ છેતરપીંડી આચરવામાં આવેલ છે. તમોશ્રીનાઓ દ્વારા અમારી તેઓની એગ્રીમેન્ટની મુજબની કાયદેસરની લેણી રકમ આજદિન સુધી ચુકવી આપવામાં આવેલ ન હોઈ તેમજ એગ્રીમેન્ટની શરતો મુજબ અમારી તમોશ્રીનાઓ પાસેથી બાકી નિકળતા માસિક હપ્તા, લેટ હપ્તાની પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય તમામ ચાર્જીસ કાયદેસર રીતે મેળવવા હક્કદાર થતા હોઈ તમોશ્રીનાઓને આ કાયદેસરની નોટીસ આપવાની જરૂરીયાત ઉપસ્થિત થયેલ છે.<br />

(6)	આથી તમોશ્રીનાઓને આ કાયદેસરની નોટીસ આપી જણાવવાનું કે, સદરહુ નોટીસ મળ્યેથી દિન-7 માં તમોશ્રીનાઓએ અમારા એગ્રીમેન્ટની શરતો મુજબ બાકી નિકળતા માસિક હપ્તા, લેટ પેનલ્ટી તેમજ એગ્રીમેન્ટ મુજબના અન્ય ચાર્જીસ રોકડમાં ચુકવી આપી અમારા હાથની કાયદેસરની પહોંચ મેળવી લેવી જો તેમ કરવામાં કસુર કરશો તો અમારી તમોશ્રીનાઓના કબજામાં રહેલ ઉપરોકત <?php if(isset($vehicle) && $vehicle!="error") echo "(".$company['company_name']." ".$modelName.")"; ?> વાહનનો પ્રત્યક્ષ કબજો મેળવી સદરહુ વાહનનો અન્યત્ર વેચાણ કરી તેઓની કાયદેસરની બાકી લેણી નિકળતી રકમ વસુલ કરશે અને ત્યારબાદ બાકી નિકળતી કાયદેસરની બાકી લેણી રકમ માટે તમોશ્રીનાઓ વિરૂધ્ધ કાયદેસરની સલાહ મળ્યા મુજબની દિવાની તેમજ ફોજદારી કાનુની કાર્યવાહી તમોશ્રીનાઓના ખર્ચે અને જોખમે હાથ ધરશે જેની ગંભીરપણે નોંધ લેશોજી.	
<br />

તારીખ :  <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?><br />
સ્થળ : અમદાવાદ


                                                                     <div class="lee"> મારી મારફતે<br /> કાંકરીયા ફાઈનાન્સ  <br /><br />.......................................</div>      
<div style="page-break-after:always"></div>

</div>
<?php } ?>



</div>
<div class="clearfix"></div>