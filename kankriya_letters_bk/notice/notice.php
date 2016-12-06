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
$vehicle=getVehicleDetailsByFileId($file_id);
$loan=getLoanDetailsByFileId($file_id);
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
// $bucket_string = $bucket_string." (= કુલ રૂ.".$bucket_amount." ) ";
 }
 ?>

<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
<div id="page_fold" style="position:absolute;top:500px;left:-20px;">___</div>
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
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">તારીખ: <?php echo date('d/m/Y',strtotime($notice['notice_date'])); ?> </div>
                                                                     <div style="padding-left:100px;line-height:28px;font-size:19px;top:0px;position:relative;padding-top:20px;">

<div class="customer_address" style="float:left;width:48%;"><?php echo $notice['customer_name']." (".$file['file_number'].")"; ?> Party,<br /><pre><?php echo $notice['customer_address'] ?> </pre></div>   
<!--<div class="guarantor_address" style="float:right;widht:50%;"><?php echo $notice['guarantor_name']; ?>,<br /><pre><?php echo $notice['guarantor_address'] ?></pre></div> <div style="clear:both;"></div>    -->
</div><br /><br />       <div style="clear:both;"></div>
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;  &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;     આ સાથે આપને  નોટિસ આપવામાં આવે છે કે આપના દ્વારા અમારી company જે <?php
							 
							  $id =  $file['agency_id']; 
							 if($id!=null)
							 {
							        $agencyDetails=getAgencyById($id);
							?>
                           <span class="special_text">
                            <?php		
									echo $agencyDetails['agency_name'];
							?>
                           </span>  ની franchisee
                            <?php		
									
							 }
							 else
							 {
							 ?>
                              <span class="special_text">
                             <?php	 
								 $id=$file['oc_id'];
								 echo getOurCompanyDisplayNameByID($id);
							?>
                            </span>
                            <?php	 
								 }
?> છે, માં થી ગાડી નં <span class="special_text"><?php if($vehicle!=false && is_array($vehicle) && isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; ?></span> હપ્તા પદ્ધતીથી ખરીદ કરેલ છે,એ  વાહન ના અનુસંધાને  આપને જણાવામાં આવે છે કે આપના દ્વારા  ઉપરોક્ત વાહન માં <span class="special_text"><?php // echo number_format($notice['bucket'],2);
 echo $bucket_string; ?></span> હપ્તા ચઢી ગયેલ છે. તેની   થતી કુલ રકમ <span class="special_text"><?php echo number_format($notice['bucket_amount'],2); ?></span> રૂપિયા  ચઢેલ છે. આ વાહન ના હફતાની રકમ ભરપાઈ કરવા માટે આપ  ને  7  ( સાત )  દિવસનો સમય આપવામાં આવે છે.<br />  &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;
                આ વાહન ના હફતાની રકમ 7 ( સાત ) દિવસમાં  ઓફિસ પર  રૂબરૂમાં જમા કરવાની રેહશે,જો ઉપર મુજબના નિયત સમયમાં આપ રકમ  ભરપાઈ  નહીં  કર  તો  કંપની  પોતાની રીતે આગળ કાર્યવાહી કરશે.<br />
           &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;                આપને કંપની તરફ થી નોટિસ મળે કે તરત કંપનીનો  સંપર્ક સાધવો ત્યારબાદ ઉપરોક્ત સમય પછી આપની કોઈ દલીલ ધ્યાન માં લેવા માં આવશે નહિ  . કંપની તમારા ઉપર કાયદેસર  કાર્યવાહી  કરી શકશે તેની નોંધ લેશોજી .
                                                                     <div class="lee">For, <?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?><br /><br />  Authorized Signatory
</div> <br />       
ખાસ નોંધ : <br />
1.) વીમો તથા પાસીંગ સમયસર લેવા જરૂરી છે  .<br />
2.) હફતો પેઢી ઉપર રૂબરૂ આવીને તથા  સમયસર ભરવો .પેઢી સિવાય હપ્તાની રકમ કોઈને આપવી નહિ . <br />
3.) આર  ટી ઓ  ને લગતો દરેક ટેક્ષ ,વીમો ,પાસીંગ  વગેરે સમયસર ભરવાની જવાબદારી પાર્ટીની રેહશે. <br />
4.) દરેક હફતો સમયસર ભરવો જરૂરી છે , નહીતર લેટ પેનલ્ટી પાર્ટીએ ભરવાની રેહશે. <br />
5.) ચાલુ લોંન માં ગાડી વેચાણ આપવી નહિ  . ચાલુ લોંન માં ગાડી વેચાણ આપશો  તો <br />
ગાડી નો અકસ્માત અથવા તો ગાડી નો ગેરકાયદેસર હેતુ માટે ઉપયોગ થશે તો તેની સંપૂર્ણ જવાબદારી પાર્ટીની રેહશે. <br />
6.) ફોન નંબર  અથવા  સરનામું બદલાયું હોય તો તાત્કાલિક  પેઢી ઉપર જાણ કરવી. <br />

</div>
<div style="page-break-after:always;"></div>
<?php } ?>
</div>
<div class="clearfix"></div>