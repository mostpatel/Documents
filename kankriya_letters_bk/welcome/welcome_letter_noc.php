<?php
if(!isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
$notice_id=$_GET['id'];
$notice=getWelcomeById($notice_id);
$file_id=$notice['file_id'];
$file = getFileDetailsByFileId($file_id);
$loan=getLoanDetailsByFileId($file_id);
$vehicle=getVehicleDetailsByFileId($file_id);
$total_coolection  = getTotalCollectionForLoan($loan['loan_id']);
$total_coolection_in_words = numberToWord($total_coolection);
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
<a href="index.php?view=welcomeChart&id=<?php echo $notice_id; ?>"><button class="btn ">EMI CHART</button></a>
<a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-warning">Go to Main File</button></a> <a href="index.php?view=search"><button class="btn btn-warning">Go to Search</button></a> <a href="<?php echo WEB_ROOT; ?>admin/customer/welcome/index.php?id=<?php echo $file_id; ?>"><button class="btn btn-success">Back</button></a></div>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button>    </div> 
<div class="interest_certificate_container"  style="padding-left:25px; padding-right:25px; padding-top:25px;">
<div  style="float:left; ">
Mob: 8347950589
</div>
<div  style="float:Right; ">
Landline: 079-25454910
</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>

<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">WELCOME LETTER ( પાર્ટી )</div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">તારીખ: <?php echo date('d/m/Y',strtotime($notice['welcome_date'])); ?> </div>
<div class="prati" style="padding-bottom:0;">રેફ નં. <b><?php echo $file['file_number']; ?></b></div>
<div class="prati" style="padding-bottom:0;">પ્રતિશ્રી ,</div>
<div class="customer_address" style="float:left"><?php echo $notice['customer_name']; ?><br /><pre><?php echo $notice['customer_address'] ?></pre></div> <div style="clear:both;"></div>       
<b style="text-align:center;width:100%;display:block;">બાબત - વાહન  <?php if ($vehicle) echo "નં. ".$vehicle['vehicle_reg_no']; else echo $notice['vehicle_model']; ?> સંબંધે કરેલ કરાર બાબત</b>          
                                &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; આથી આપશ્રીને આ પત્ર દ્વારા જાણ કરવાની કે, આપશ્રી દ્વારા અમારી પેઢી સાથે વાહન <?php if ($vehicle) echo "નં. ".$vehicle['vehicle_reg_no']." ( ";  echo $notice['vehicle_model']." ) "; ?> ઉપર કરાર કરી અમારી પેઢી સાથે વ્યવહાર પ્રસ્થાપિત કરેલ છે અને અમારી પેઢી સાથે કરેલ કરાર મુજબ તા <b><?php echo date('d/m/Y',strtotime($loan['loan_approval_date'])); ?></b> ના રોજ માસિક રૂ 
   <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo " ".number_format($loan['emi'])." ના ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના ".$e['duration']." | ";
									  }
								  
								  } ?></b> હપ્તા મુજબ કુલ રૂ <b><?php echo $total_coolection; ?></b> (અંકે રૂપિયા <b><?php echo $total_coolection_in_words." Only"; ?></b>) ચુકવી આપવા માટે સંમતિ દર્શાવેલ છે.

 .આપશ્રી દ્વારા અમારી પેઢી સાથે કરેલ કરાર અંગે પેઢી આપને અભિનંદન પાઠવે છે અને આપશ્રી દ્વારા પેઢી સાથે શરૂ કરેલ વ્યવહાર ભવિષ્યમાં પણ વખત ચાલતો તેવી પેઢી આશા વ્યક્ત કરે છે.




..........................................................................................................................................................<br />...........................................................................................................................................................................................................<br />...........................................................................................................................................................................................................
                                                                     <div class="lee">   આભાર સહ... </div> <br /><br />       

</div>
</div>
<div class="clearfix"></div>