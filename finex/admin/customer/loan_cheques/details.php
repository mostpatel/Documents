<?php if(!isset($_GET['id']) )
{

header("Location: ".WEB_ROOT."admin/search");
exit;

}
$file_id=$_GET['id'];
$loan_cheques = ListChequesForFileId($file_id);
$cheque_numbers = getChequeNumbersForChequeDetailsId($loan_cheques['cheque_details_id']);
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<a href="ech/index.php?id=<?php echo $file_id; ?>&state=<?php echo $customer_id; ?>"><button class="btn btn-success">Print ECS Form</button></a>
<h4 class="headingAlignment"> Loan Cheuqes Details </h4>
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

<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td width="220px">Bank Name : </td>
				<td>
					<?php echo $loan_cheques['bank_name']; ?>
                            </td>
</tr>
<tr>
<td width="220px">Branch Name : </td>
				<td>
					<?php echo $loan_cheques['branch_name']; ?>
                            </td>
</tr>

<tr>
<td width="220px">Required Cheques : </td>
				<td>
					<?php echo $loan_cheques['required_cheques']; ?>
                            </td>
</tr>

<tr>
<td width="220px">Cheques Received : </td>
				<td>
					  <?php echo $loan_cheques['cheques_received']; ?>
                  
                            </td>
</tr>

<tr>
<td width="220px">Used Cheques : </td>
				<td>
					<?php echo $loan_cheques['used_cheques']; ?>
                            </td>
</tr>

<tr>
<td width="220px">Unused Cheques : </td>
				<td>
					<?php echo $loan_cheques['unused_cheques']; ?>
                            </td>
</tr>

<tr>
<td class="firstColumnStyling">
 Ac No : 
</td>

<td>
<?php echo $loan_cheques['ac_no']; ?>
</td>
</tr>

<tr>
<td class="firstColumnStyling">
 remarks : 
</td>

<td>
<?php echo $loan_cheques['remarks']; ?>
</td>
</tr>

<?php 
	for($i=1;$i<=$loan_cheques['required_cheques'];$i++)
	{
	 ?>
     <tr>
<td class="firstColumnStyling">
 Cheque <?php echo $i; ?> : 
</td>

<td>
<?php echo $cheque_numbers[$i-1]['cheque_no']; ?>
</td>
</tr>
     
     <?php } ?>
<tr>
<td width="250px;"></td>
<td>
 <a href="<?php echo 'index.php?view=edit&id='.$file_id; ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
  <a href="<?php echo 'index.php?action=delete&id='.$file_id; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
   
<a href="<?php echo WEB_ROOT; ?>admin/customer/index.php?view=details&id=<?php echo $file_id; ?>"><button class="btn btn-warning" >Back</button></a>

</td>
</tr>
</table>

</div>
<div class="clearfix"></div>
<script>

 $( "#bank" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/bank_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#bank" ).val(ui.item.label);
			return false;
		}
    });
	 $( "#branch" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/branch_name.php',
                { term: request.term, bank_name:$('#bank').val() }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#branch" ).val(ui.item.label);
			return false;
		}
    });	
</script>
