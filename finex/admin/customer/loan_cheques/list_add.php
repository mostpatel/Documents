<?php if(!isset($_GET['id']) || !isset($_GET['state']))
{
if(isset($_GET['id']))
{
header("Location: ".WEB_ROOT."admin/customer/index.php?view=details&id=".$_GET['id']);
exit;
}
else
{
header("Location: ".WEB_ROOT."admin/search");
exit;
}
}
$file_id=$_GET['id'];
$customer_id = $_GET['state'];

 ?>
<div class="insideCoreContent adminContentWrapper wrapper">

<h4 class="headingAlignment"> Add Loan Cheques </h4>
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

<form onsubmit="return submitPayment();" id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data" >
<input name="file_id" value="<?php echo $file_id; ?>" id="file_id" type="hidden" />
<input name="customer_id" value="<?php echo $customer_id; ?>" id="customer_id" type="hidden" />
<table id="insertInsuranceTable" class="insertTableStyling no_print">
<tr>
<td width="220px">Bank Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="bank_name" id="bank" placeholder="Only Letters!" autocomplete="off"  />
                            </td>
</tr>
<tr>
<td width="220px">Branch Name<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="branch_name" id="branch" placeholder="Only Letters!" autocomplete="off"  />
                            </td>
</tr>

<tr>
<td width="220px">Required Cheques<span class="requiredField">* </span> : </td>
				<td>
					<select id="required_cheques" name="required_cheques" onchange="alterItenary(this.value)">
                    	<?php for($i=1;$i<=60;$i++) { ?>
                    	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                            </td>
</tr>

<tr>
<td width="220px">Cheques Received<span class="requiredField">* </span> : </td>
				<td>
					<select id="cheques_received" name="cheques_received">
                    	<?php for($i=0;$i<=60;$i++) { ?>
                    	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                            </td>
</tr>

<tr>
<td width="220px">Used Cheques<span class="requiredField">* </span> : </td>
				<td>
					<select id="used_cheques" name="used_cheques">
                    	<?php for($i=0;$i<=60;$i++) { ?>
                    	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                            </td>
</tr>

<tr>
<td width="220px">Unused Cheques<span class="requiredField">* </span> : </td>
				<td>
					<select id="unused_cheques" name="unused_cheques">
                    	<?php for($i=0;$i<=60;$i++) { ?>
                    	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                            </td>
</tr>


<tr>
<td class="firstColumnStyling">
Ac No : 
</td>

<td>
<input type="text" name="ac_no" id="ac_no" />
</td>
</tr>
<tr>
<td class="firstColumnStyling">
Remarks : 
</td>

<td>
<textarea name="remarks" id="remarks"></textarea>
</td>
</tr>
</table>
<table id="insertItenaryTable" class="insertTableStyling no_print">

<tbody id="day" style="display:none">



<tr>



<td colspan="2" class="firstColumnStyling">

<span class="headingAlignment">Cheque 1</span>

</td>





</tr>





<tr>



<td width="230px" class="firstColumnStyling">

Cheque No<span class="requiredField">* </span> : 

</td>



<td>

<input type="text" name="cheque_no[]"  class="itenary_heading" placeholder="Only Numbers"/>

</td>

</tr>


</tbody>

<tbody id="day1">



<tr>



<td colspan="2" class="firstColumnStyling">

<span class="headingAlignment">Cheque 1</span>

</td>





</tr>





<tr>



<td width="230px" class="firstColumnStyling">

Cheque No<span class="requiredField">* </span> : 

</td>



<td>

<input type="text" name="cheque_no[]"  class="itenary_heading" placeholder="Only Numbers"/>

</td>

</tr>


</tbody>

</table>
<table>
<tr>
<td width="250px;"></td>
<td>
<input id="disableSubmit" type="submit" value="Add Loan Cheques"  class="btn btn-warning">
<?php if(isset($_SERVER['HTTP_REFERER'])) { ?><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" class="btn btn-success" value="Back"/></a><?php } ?>
</td>
</tr>

</table>

</form>
</div>
</div>
<div class="clearfix"></div>
<script>
document.package_days = 2;
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
	
	function alterItenary(days)
{
	var prev_days = document.package_days;
	var itenary_inner_html = document.getElementById('day').innerHTML;
	var itenary_table = document.getElementById('insertItenaryTable');
	if(prev_days==(days+1))
	{
		
		
	}
	else if(prev_days<=days)
	{
		
		for(i=prev_days;i<=days;i++)
		{
			
			var new_tbody=document.createElement('tbody');
			new_tbody.setAttribute('id','day'+i);
			new_tbody.innerHTML=itenary_inner_html;
			itenary_table.appendChild(new_tbody);
			$('#day'+i+' span:first-child')[0].innerHTML='Cheque '+i;
			}
	}
	else if(prev_days>days)
	{
		
		for(i=(prev_days-1);i>days;i--)
		{
			$('#day'+i).remove();
			}
	}	
	
	document.package_days=parseInt(days)+1;
	
}
</script>