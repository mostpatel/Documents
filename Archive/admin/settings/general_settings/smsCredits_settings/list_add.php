<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print"> Remaining SMS Credits</h4>
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


<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Promotional SMS Credits <span class="requiredField"> </span> :
</td>

<td>
<b> <?php echo checkCredits(1); ?> </b>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Template based SMS Credits <span class="requiredField"></span> :
</td>

<td>
 <b> <?php echo checkCredits(2); ?> </b>
</td>
</tr>


<tr>
</tr>

<tr>
</tr>

<tr>
</tr>



</table>

<br />
<br />
<br />

<h4> To renew SMS Pack, contact on : 09978812644, 09824143009 </h4> 





	
    
        
</div>
<div class="clearfix"></div>