<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Group Details</h4>
<?php 
if(!isset($_GET['lid']))
{
	header("Location: index.php");
	}
$city=getCustomerGroupByID($_GET['lid']);
$city_id=$_GET['lid'];
$parties=listCustomerForCustomerGroupId($city_id);
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
<table id="DetailsTable" class="insertTableStyling">

<tr>

<td class="firstColumnStyling">
name :
</td>

<td>
<?php echo $city['group_name']; ?>
</td>
</tr>

<tr class="no_print">
<td></td>
<td >
<a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$city_id ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
<a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$city_id ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
<a href="index.php"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>


</table>    

<h4 class="headingAlignment">List of Customers In The Group</h4>
  
   
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
		
	
		$no=0;
		if($parties!=false)
		{ 
		foreach($parties as $agencyDetails)
		{
		 ?>
         <tr class="resultRow">
         	<td><input type="checkbox" class="selectTR" name="selectTR[]" value="<?php echo $agencyDetails['customer_id']; ?>"/></td>
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
</div>
<div class="clearfix"></div>