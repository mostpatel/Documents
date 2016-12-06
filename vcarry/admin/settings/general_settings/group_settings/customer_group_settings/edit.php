<?php
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Edit Customer Group</h4>
<?php 
$customer_group = getCustomerGroupByID($_GET['lid']);
$customer_group_id = $_GET['lid'];
if(isset($_SESSION['ack']['msg']) && isset($_SESSION['ack']['type']))
{
	
	$msg=$_SESSION['ack']['msg'];
	$type=$_SESSION['ack']['type'];
	
	
		if($msg!=null && $msg!="" && $type>0)
		{
?>
<div class="alert  <?php if(isset($type) && $type>0 && $type<4) echo "alert-success"; else echo "alert-error" ?>">
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=edit'; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Customer Group Name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="lid" value="<?php echo $customer_group['group_id']; ?>"/>
<input type="text" id="txtlocation" name="cus_group_name" value="<?php echo $customer_group['group_name']; ?>"/>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Edit" class="btn btn-warning">
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>

<h4 class="headingAlignment">List of Customers</h4>
  
   
    <table style="margin-bottom:50px;" id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
            <th class="heading">
            <input type="checkbox" id="selectAllTR" name="selectAllTR"  />
            </th>
        	<th class="heading no_sort">No</th>
            <th class="heading">Name</th>
           
           
            <th class="heading">Pan No</th>
            <th class="heading">Tin No</th>
            <th class="heading">CST No</th>
            <th class="heading">Service Tax No</th>
            <th class="heading no_print btnCol" ></th>
          
        </tr>
    </thead>
    <tbody>
        
        <?php
		$parties=listCustomer();
		$customer_ids_in_group=getCustomerIdsForCustomerGroupID($customer_group_id);
	
		$no=0;
		if($parties!=false)
		{ 
		foreach($parties as $agencyDetails)
		{
		 ?>
         <tr class="resultRow">
         	<td><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $agencyDetails['customer_id']; ?>" <?php if(in_array($agencyDetails['customer_id'],$customer_ids_in_group)) { ?> checked="checked" <?php } ?> /></td>
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php  echo $agencyDetails['customer_name']; ?>
            </td>
            
             <td><?php echo $agencyDetails['pan_no'] ?>
            </td> 
             <td><?php echo $agencyDetails['tin_no'] ?>
            </td>
            <td><?php echo $agencyDetails['cst_no'] ?>
            </td>  
            <td><?php echo $agencyDetails['service_tax_no'] ?>
            </td>  
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$agencyDetails['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
             </td>
          
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>

</form>

</div>
<div class="clearfix"></div>