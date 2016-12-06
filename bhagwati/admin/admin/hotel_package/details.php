<?php
if(!isset($_GET['id']))
header("Location: ".WEB_ROOT."admin/search");

$package_id=$_GET['id'];
$package=getHotelPackageByID($package_id);
if(is_array($package) && $package!="error")
{
	
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
<div class="detailStyling">

<h4 class="headingAlignment">Package's Details</h4>


<table id="insertCustomerTable" class="insertTableStyling detailStylingTable">


<tr>

<td width="90px" class="firstColumnStyling">
Location : 
</td>

<td>

                             <?php echo $package['location_name']; ?>					
                            
</td>
</tr>

<tr>

<td  class="firstColumnStyling">
Name : 
</td>

<td>

                             <?php echo $package['hotel_package_name']; ?>					
                            
</td>
</tr>

<tr>
<td>
Days : 
</td>

<td>

                             <?php echo $package['days']." Days"; ?>					
                            
</td>
</tr>

<tr>
<td>
Nights : 
</td>

<td>

                             <?php echo $package['nights']." Nights"; ?>					
                            
</td>
</tr>

<tr>
<td>
Stars : 
</td>

<td>

                             <?php echo $package['stars'].""; ?>					
                            
</td>
</tr>

<tr>
<td>
Tarriff : 
</td>

<td>

                             <?php echo $package['tarriff']." Rs"; ?>					
                            
</td>
</tr>


<tr>
<td>
Image : 
</td>

<td>

                           <img src="<?php echo WEB_ROOT."images/package_icons/".$package['thumb_href']; ?>" />					
                            
</td>
</tr>

                  

</table>





</div>

</div>
<div class="clearfix"></div>