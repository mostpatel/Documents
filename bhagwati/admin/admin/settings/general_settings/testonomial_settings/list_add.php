<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Testimonial</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post" enctype="multipart/form-data">

<table class="insertTableStyling no_print">



<tr>

<td class="firstColumnStyling">
Person name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="person_name" name="person_name"/>
</td>
</tr>

<tr>

<tr>

<td class="firstColumnStyling">
Person Company<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="person_company" name="person_company"/>
</td>
</tr>

<tr>

<tr>

<td class="firstColumnStyling">
Person Designation<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="person_designation" name="person_designation"/>
</td>
</tr>



<td class="firstColumnStyling">
Testimonial<span class="requiredField">* </span> :
</td>

<td>
<textarea id="testimonial" name="testonomial"></textarea>
</td>
</tr>




<td class="firstColumnStyling">
Person Image For Testimonial <br />[width : 100-200px | height : 150-300px]<span class="requiredField">* </span> :
</td>

<td>
<input type="file" id="location_image" name="testonomial_image"/>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Testimonial" class="btn btn-warning" >
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Testimonials</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	  <div class="no_print">
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Company</th>
            <th class="heading">Designation</th>
            <th class="heading">Testinomial</th>
            <th class="heading">Photo</th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$locations=listTestonomials();
		$i=0;
		foreach($locations as $location)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><?php echo $location['person_name']; ?>
            </td>
             <td><?php echo $location['person_company']; ?>
            </td>
            <td><?php echo $location['person_designation']; ?>
            </td>
            <td><?php echo $location['testonomial']; ?>
            </td>
             <td><img width="306"  src="<?php echo WEB_ROOT."images/testonomial_icons/".$location['img_href']; ?>" class="attachment-full wp-post-image" alt="<?php echo $package['package_name']; ?>" />	
              
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$location['testonomial_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$location['testonomial_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
      </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>