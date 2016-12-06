<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$vehicle_id=$_GET['id'];
$file_id=getFileidFromVehicleId($vehicle_id);
$file=getFileDetailsByFileId($file_id);
if(is_array($file) && $file!="error")
{
	
	$vehicle=getVehicleDetailsByFileId($file_id);
	$vehicle_docs = getVehicleDocsForVehicleId($vehicle['vehicle_id']);
	$rto_work_array = getRtoWorkForVehicleId($vehicle['vehicle_id']);
	$proof_details=getVehicleProofByFileId($file_id);
	
}
else
{
	$_SESSION['ack']['msg']="Invalid File!";
	$_SESSION['ack']['type']=4; // 4 for error
	header("Location: ".WEB_ROOT."admin/search");
	
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
<div class="detailStyling">
<h4 class="headingAlignment"> Vehicle Details </h4>



<h4 class="headingAlignment">Vehicle Documents With us</h4>
<table style="margin-top:0px;margin-bottom:10px;" class="insertTableStyling detailStylingTable">
<tr>
<td width="220px">RTO Agent : </td>
				<td>
					
                       <?php if($vehicle_docs['rto_agent_id']) echo getRtoAgentNameFromRtoAgentId($vehicle_docs['rto_agent_id']); ?>
                           
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Given : 
</td>

<td>
<?php if(is_array($rto_work_array)) {  
$total_amount = 0;
foreach($rto_work_array as $rto_work) {echo $rto_work['rto_work_name']." - ".$rto_work['rate']." Rs<br>"; $total_amount=$total_amount+$rto_work['rate'];}
echo "Total: ".$total_amount." Rs";
} ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Given Date : 
</td>

<td>
<?php if($vehicle_docs['work_given_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['work_given_date'])); else echo "NA"; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Work Completion Date : 
</td>

<td>
<?php if($vehicle_docs['work_completion_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['work_completion_date'])); else echo "NA"; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Customer Given Date : 
</td>

<td>
<?php if($vehicle_docs['customer_given_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['customer_given_date'])); else echo "NA"; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
Customer Received Date : 
</td>

<td>
<?php if($vehicle_docs['customer_received_date']!="1970-01-01") echo date('d/m/Y',strtotime($vehicle_docs['customer_received_date'])); else echo "NA"; ?>
</td>
</tr>

 <tr>
<td width="220px" class="firstColumnStyling">
Document Remarks : 
</td>

<td>
<?php echo $vehicle_docs['remarks']; ?>

</td>
</tr>
</table>
<table class="document_table" style="width:100%;" border="1" cellpadding="10" >
<tr>
	<td colspan="7">
    Document Status	
    </td>
</tr>
<tr>
       <th >R.C Book </th>
       <th>Passing </th>
       <th>Permit </th> 
       <th>Insurance </th>
       <th>HP </th>
        <th>Bill </th>
        <th>Key </th>
        </tr>
        <tr>
        <td>
               <?php if($vehicle_docs['rto_papers']==1) { ?> Yes <?php } else if($vehicle_docs['rto_papers']==0) { ?> No <?php } else  if($vehicle_docs['rto_papers']==2) { ?> Work Given to agent <?php } else  if($vehicle_docs['rto_papers']==3) { ?> Orginals given to customer <?php } else  if($vehicle_docs['rto_papers']==4) { ?> Not Applicable <?php } ?> 
        </td>
 
      
           
           
        <td>
               <?php if($vehicle_docs['passing']==1) { ?> Yes <?php } else if($vehicle_docs['passing']==0) { ?> No <?php } else  if($vehicle_docs['passing']==2) { ?> Work Given to agent <?php } else  if($vehicle_docs['passing']==3) { ?> Orginals given to customer <?php } else  if($vehicle_docs['passing']==4) { ?> Not Applicable <?php } ?> 
        </td>
 
       
           
           
        <td>
              <?php if($vehicle_docs['permit']==1) { ?> Yes <?php } else if($vehicle_docs['permit']==0) { ?> No <?php } else  if($vehicle_docs['permit']==2) { ?> Work Given to agent <?php } else  if($vehicle_docs['permit']==3) { ?> Orginals given to customer <?php } else  if($vehicle_docs['permit']==4) { ?> Not Applicable <?php } ?>
        </td>
 
      
           
           
        <td>
              <?php if($vehicle_docs['insurance']==1) { ?> Yes <?php } else if($vehicle_docs['insurance']==0) { ?> No <?php } else  if($vehicle_docs['insurance']==2) { ?> Work Given to agent <?php } else  if($vehicle_docs['insurance']==3) { ?> Orginals given to customer <?php } else  if($vehicle_docs['insurance']==4) { ?> Not Applicable <?php } ?>
        </td>
 
      
           
           
        <td>
               <?php if($vehicle_docs['hp']==1) { ?> Yes <?php } else if($vehicle_docs['hp']==0) { ?> No <?php } else  if($vehicle_docs['hp']==2) { ?> Work Given to agent <?php } else  if($vehicle_docs['hp']==3) { ?> Orginals given to customer <?php } else  if($vehicle_docs['hp']==4) { ?> Not Applicable <?php } ?>
        </td>
 
      
           
           
        <td>
               <?php if($vehicle_docs['bill']==1) { ?> Yes <?php } else if($vehicle_docs['bill']==0) { ?> No <?php } else  if($vehicle_docs['bill']==2) { ?> Work Given to agent <?php } else  if($vehicle_docs['bill']==3) { ?> Orginals given to customer <?php } else  if($vehicle_docs['bill']==4) { ?> Not Applicable <?php } ?>
        </td>
 
       
           
           
        <td>
               <?php if($vehicle_docs['vehicle_key']==1) { ?> Yes <?php } else if($vehicle_docs['vehicle_key']==0) { ?> No <?php } else  if($vehicle_docs['vehicle_key']==2) { ?> Work Given to agent <?php } else  if($vehicle_docs['vehicle_key']==3) { ?> Orginals given to customer <?php } else  if($vehicle_docs['vehicle_key']==4) { ?> Not Applicable <?php } ?>
        </td>
 </tr>
 </table>
 <style>
 .document_table
 {
	 border:1px solid #aaa;
	margin-bottom:30px;
	 
	}
	.document_table tr td, .document_table tr th
 {
	 padding:5px;
	 font-family: myFontBold;
	 text-align:left;
 }
 </style>
<table class="insertTableStyling no_print">
<tr>
	<td width="150px"></td>
  <td class="no_print">
            
          <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$file_id.'&state='.$vehicle['vehicle_id'] ?>"><button title="Edit this entry" class="btn splEditBtn editBtn"><span class="delete">E</span></button></a>
             <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$file_id ?>"><button title="Back" class="btn btn-success">Back</button></a>
            </td>
</tr>   

</table>

</div>
</div>
<div class="clearfix"></div>