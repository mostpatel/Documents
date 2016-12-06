<div class="jvp"><?php if(isset($_SESSION['cSMSReport']['agency_id']) && $_SESSION['cSMSReport']['agency_id']!="") { echo getAgecnyIdOrOCidNameFromAgnecySelectInput($_SESSION['cSMSReport']['agency_id']);  } ?></div>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Custom SMS Reports</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">

<tr>
<td>From Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="start_date" id="start_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSMSReport']['from'])) echo $_SESSION['cSMSReport']['from']; ?>" />	
                 </td>
</tr>


<tr>
<td>Up To Date : </td>
				<td>
				 <input autocomplete="off" type="text"  name="end_date" id="end_date" placeholder="Click to select Date!" class="datepicker2" value="<?php if(isset($_SESSION['cSMSReport']['to'])) echo $_SESSION['cSMSReport']['to']; ?>"/>	
                 </td>
</tr>



<tr>
<td>Type : </td>
				<td>
					<select id="type" name="type" class="type" >
                        <option value="-1" >--Please Select--</option>
                             <option value="1" >Payment Received SMS</option>
                             <option value="101" >Upcoming Service Date</option>
                            </select> 
                            </td>
</tr>
<tr>

<td></td>
				<td>
				 <input type="submit" value="Generate" class="btn btn-warning"/>	
                </td>
</tr>


</table>

</form>

  
<hr class="firstTableFinishing" />

	<div class="no_print">
     <?php if(isset($_SESSION['cSMSReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cSMSReport']['emi_array'];
		
		
	 ?>
     <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
     <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Msg ID</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Message</label> 
         <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Contact No</label> 
        <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Date Added</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"   /><label class="showLabel" for="6">Type</label> 
        <input class="showCB" type="checkbox" id="7" checked="checked"  /><label class="showLabel" for="7">No Of Times Sent</label> 
    </div>    
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Msg Id</th>
            <th class="heading">Message</th>
            <th class="heading">Contact No</th>
            <th class="heading date">Date Added</th>
            <th class="heading">Type</th>
            <th class="heading">No Of Times Sent</th>
            <th class="heading no_print btnCol"></th>
           
        </tr>
    </thead>
    <tbody>
       
        <?php
		$total=0;
		
		foreach($emi_array as $emi)
		{
			
			
		 ?>
         <tr class="resultRow">
         <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><?php echo ++$i; ?></td>
            <td> <?php  echo  $emi['msg_id']; ?>
            </td>
            <td><?php  echo  $emi['message']; ?>
            </td>
            <td><?php echo $emi['mobile_no']; ?></td>
            <td><?php echo date('d/m/Y',strtotime($emi['date_added'])); ?>
            </td>
            <td><?php  if($emi['type']==1)  echo "Amount Received"; else if($emi['type']>100) echo "Next Service Date"; ?>
            </td>
            <td><?php   echo $emi['number_Sent']; ?></td>
           
             <td class="no_print"> <a href="<?php echo 'index.php?action=send_sms&id='.$emi['sms_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">Resend</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
     <table class="reportFiltersTable">
    <tr>
    	<td> From : <?php if(isset($_SESSION['cSMSReport']['from']) && $_SESSION['cSMSReport']['from']!="") echo $_SESSION['cSMSReport']['from']; else echo "NA"; ?></td>
        <td> To : <?php if(isset($_SESSION['cSMSReport']['to']) && $_SESSION['cSMSReport']['to']!="") echo $_SESSION['cSMSReport']['to']; else echo "NA"; ?></td>
       
    </tr>
    </table> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
  
<?php  ?>      
</div>
<div class="clearfix"></div>
<script>
 $( "#city_area1" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/city_area.php',
                { term: request.term, city_id:$('#customer_city_id').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#city_area1" ).val(ui.item.label);
			return false;
		}
    });

</script>