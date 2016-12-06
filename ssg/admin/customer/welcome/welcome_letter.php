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
Mob: 9724058646
</div>
<div  style="float:Right; ">
Landline: 079-25466250
</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyNameByID($file['oc_id']); ?>
</div>

<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">WELCOME LETTER</div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">તારીખ: <?php echo date('d/m/Y',strtotime($notice['welcome_date'])); ?> </div>
<div class="prati" style="padding-bottom:0;">પ્રતિ ,</div>
<div class="customer_address" style="float:left"><?php echo $notice['customer_name']; ?> (<?php echo $file['file_number']; ?>),<br /><pre><?php echo $notice['customer_address'] ?></pre></div>
<div class="guarantor_address" style="float:right"><?php echo $notice['guarantor_name']; ?>,<br /><pre><?php echo $notice['guarantor_address'] ?></pre></div> <div style="clear:both;"></div>            
                                &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; આપશ્રી નો આભાર કે આપશ્રી અમારી company સાથે જોડાયા છો.
આપશ્રીની ગાડીની લોન હાયપોથીકેશન પદ્ધતિથી અમારી કંપનીમાં <b><?php echo date('d/m/Y',strtotime($loan['loan_approval_date'])); ?></b> તારીખે થઇ છે.<br />  &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;
               આપશ્રીની ગાડી <b><?php echo $notice['vehicle_model']; ?></b> ની લોન <b><?php echo $loan['loan_amount']; ?></b>  છે. જેમાં હપ્તા <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo "Rs. ".number_format($loan['emi'])." X ".$loan['loan_duration'];
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." X ".$e['duration']." | ";
									  }
								  
								  } ?></b> મહિના ના થાય છે. આપની લોન નો હપ્તો દર મહિના ની <b><?php echo date('d',strtotime($loan['loan_starting_date'])); ?></b> તારીખે આવે છે, પ્રથમ હફતો <b><?php echo date('d/m/Y',strtotime($loan['loan_starting_date'])); ?></b>  આવશે. 
નીચે લખેલા ખાસ સૂચનો નો ખાસ અમલ થાય તેનો ખાસ આગ્રહ રાખશો, જેની નોંધ લેશો,. ..........................................................................................................................................................<br />...........................................................................................................................................................................................................<br />...........................................................................................................................................................................................................
                                                                     <div class="lee">   લી  .</div> <br /><br />       
ખાસ સૂચનો  : <br />
1.) વીમો તથા પાસીંગ સમયસર લેવા જરૂરી છે  .<br />
2.) હફતો પેઢી ઉપર રૂબરૂ આવીને તથા  સમયસર ભરવો .પેઢી સિવાય હપ્તાની રકમ કોઈને આપવી નહિ . <br />
3.) આર  ટી ઓ  ને લગતો દરેક ટેક્ષ ,વીમો ,પાસીંગ  વગેરે સમયસર ભરવાની જવાબદારી પાર્ટીની રેહશે. <br />
4.) દરેક હફતો સમયસર ભરવો જરૂરી છે , નહીતર લેટ પેનલ્ટી પાર્ટીએ ભરવાની રેહશે. <br />
5.) લોડીંગ રીક્ષા ચલાવવા માટે TVA અને પેસેન્જર રીક્ષા ચલાવવા માટે બેજ નંબર હોવો જરૂરી છે. <br />
6.) ચાલુ લોંન માં ગાડી વેચાણ આપવી નહિ  . ચાલુ લોંન માં ગાડી વેચાણ આપશો  તો <br />
ગાડી નો અકસ્માત અથવા તો ગાડી નો ગેરકાયદેસર હેતુ માટે ઉપયોગ થશે તો તેની સંપૂર્ણ જવાબદારી પાર્ટીની રેહશે. <br />
7.) ફોન નંબર  અથવા  સરનામું બદલાયું હોય તો તાત્કાલિક  પેઢી ઉપર જાણ કરવી. <br />
8.) ગાડી છોડાવ્યા તારીખથી હપ્તો ચાલુ થશે. 
</div>
</div>
<div class="clearfix"></div>