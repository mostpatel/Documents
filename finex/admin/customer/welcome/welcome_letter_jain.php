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
$total_collection=getTotalCollectionForLoan($loan['loan_id']);
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
Mob: 9898103089
</div>
<div  style="float:Right; ">
Landline: 079-25454520
</div>
<div  style="text-align:center; font-size:34px;">
<?php if(is_numeric($file['agency_id']))  {echo  getAgencyHeadingById($file['agency_id']);} else  echo getOurCompanyDisplayNameByID($file['oc_id']); ?>
</div>

<div class="notice_address" style="text-align:center;margin-bottom:20px;padding-bottom:0px; border-bottom:2px solid #000;">
<?php echo getOurCompanyAddressByID($_SESSION['adminSession']['oc_id']); ?>
</div>
<div style="text-align:center; font-weight:bolder; text-decoration:underline; line-height:20px; width:100%;">MOU LETTER</div>
                                     
                                                                    <div class="date" style="float:right;padding-right:20px;">તારીખ: <?php echo date('d/m/Y',strtotime($notice['welcome_date'])); ?> </div>
<div class="prati" style="padding-bottom:0;">પ્રતિ ,</div>
<div class="customer_address" style="float:left"><?php echo $notice['customer_name']; ?> (<?php echo $file['file_number']; ?>),<br /><pre><?php echo $notice['customer_address'] ?></pre></div>
<div class="guarantor_address" style="float:right"><?php echo $notice['guarantor_name']; ?>,<br /><pre><?php echo $notice['guarantor_address'] ?></pre></div> <div style="clear:both;"></div>   
<div style="text-align:center;position:relative;width:100%;">
 <span style="text-decoration:underline;">વિષય: </span>વાહન નંબર <b ><?php if(isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; else echo "New Vehicle"; ?></b><br>
 </div>
&nbsp; &nbsp;&nbsp; &nbsp;મે.સાહેબ  <br>
&nbsp; &nbsp;&nbsp; &nbsp;
 આપશ્રી  એ અમારી  પેઢી માં આવી અમારી સાથે કાયદેસરનો  એગ્રીમેન્ટ કરી હાયર પરચેજ  કરાર થી  ઉપર  મુજબ નો વાહન નંબર <b> <?php if(isset($vehicle['vehicle_reg_no'])) echo $vehicle['vehicle_reg_no']; else echo "New Vehicle"; ?> </b> લીધેલ છે .જેના ભરવાના થતા  ટોટલ  રૂ <b><?php echo $total_collection; ?></b> ના માસીક રૂ <b><?php $emi=getEmiForLoanId($loan['loan_id']); // amount if even loan or loan structure if loan is uneven
							 if($loan['loan_scheme']==1)
							  echo "Rs. ".number_format($loan['emi'])." ના ".$loan['loan_duration']." માસીક  હપ્તા ";
							  else
							  {
								  foreach($emi as $e)
								  {

									  echo number_format($e['emi'])." ના ".$loan['loan_duration']." માસીક હપ્તા અને ";
									  }
								  
								  } ?></b> તારીખ <b><?php echo date('d/m/Y',strtotime($loan['loan_starting_date'])); ?></b> થી શરુ  કરી ને દર મહિને ટોટલ <b> <?php echo $loan['loan_duration']; ?> </b> માસ સુધી સમયસર ભરવાની બાંહેધરી અને ખાતરી  આપેલ  છે તે બાબતે તમો સંમત  અને ખુશ છો અને એક પણ માસિક વાહન પેટે ભરવાનું હપ્તો સમયસર ન ભરાયું તો અમો પેર્મિત ઓનર હાયરર વાહન નો કબજો વગર વિવાદે શાંતિપૂર્વક તમોને સોંપી દઈશું.<br>
ઉપર મુજબ નો હાયર ધિરાણ નીચેની શર્તો ને આધીન રેહશે
 <br>1. આપશ્રી ઉપર મુજબ ભરવાના થતા માસીક હપ્તા ની રકમ અમારી ઓફિસે ઉપરોક્ત સરનામે  આવી ભરી જવું અને અમારી પાકી પાવતી મેળવી લઇ કોઈ ત્રાહીત વ્યક્તિ ને રૂપિયા આપવા નહી.<br>
2. સદર વાહના ના ટેક્ષ,વિમો,પાસીંગ સમયસર કરાવવાની તથા અન્ય સરકારી જવાબદારી ના ખર્ચા ની જવાબદારી તમારી રહેશે.<br>
3. લોડિંગ રીક્ષા ચલાવવા માટે TVA  અને પેસેન્જર રીક્ષા ચલાવવા  માટે  AIR લાઇસેંસ અને બેએજ વગર અકસ્માત ના ટાઈમમાં  બીમાં નો ક્લેમ મળશે નહી. દ્રાયવિંગ લાઈસેંસ વગર ગાડી ચાલવવું ગેરકાનૂની છે.<br>
4. ચાલુ  લોન માં ગાડી ત્રાહિત  વ્યક્તિ ને વેચાણ આપવી નહી . ચાલુ લોન ની ગાડી વેચાણ આપશો તો  ગાડી ના અકસ્માત અથવા ગાડી નો બિનકાયદેસર  હેતુ માટે ઉપયોગ  થશે  તો તેની  સઘલી જવાબદારી તમારી રહેશે.<br>
5. ફોન નંબર  અને સરનામું  બદલાયું હોય તો તાત્કાલિક  ઉપર ના સરનામે  પેઢી ને જાણ કરવી.<br>
6. કોઈ પણ કારણસર હપ્તા પેટે આપેલ ચેક તમારી બેંક માંથી સીકાર્યા વગર બાઉંસ થશે તો રિટર્ન ચાર્જ અને વ્યાજ રૂ ૫૦૦ /- ભરવા પડશે અને નેગોશીએબલ ઇન્સ્ત્રુંમેંટ એકટ મુજબ તમારા ઉપર કાયદેસર ની કાર્યવાહી થશે.<br>
7. કોઈપણ કારણસર વાહનનો પરમીટ ઓનર  હપ્તાઓ ભરવામાં નિષ્ફળ થાય નો બાકી રહેલા તમામ રૂ ભરવાની જવાબદારી તમારા જામીનદાર ની રેહશે.
                                                                  
</div>
<div style="width:100%;margin-top:50px;">
	<div style="float:left;width:30%;height:50px;border-top:1px solid #000;text-align:center;vertical-align:bottom;margin-right:5%;">
    Owner
    </div>
  
    	<div style="float:left;width:30%;height:50px;border-top:1px solid #000;text-align:center;vertical-align:bottom;margin-right:5%;">
    Hirer
    </div>
   
    	<div style="float:left;width:30%;height:50px;border-top:1px solid #000;text-align:center;vertical-align:bottom;">
    Gaurantor
    </div>
</div>
</div>
<div class="clearfix"></div>