<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Career Opportunity</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=add'; ?>" method="post">

<table class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
Position Name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="name" id="txtName"/>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Qualification <span class="requiredField">* </span> :
</td>

<td>
<input type="text" name="qualification" id="txtName"/>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
 Description <span class="requiredField">* </span> :
</td>

<td>
<textarea rows="6" cols="8" name="description"></textarea>
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Gender Prefrence <span class="requiredField">* </span> :
</td>

<td>
<select name="gender">
  <option value="0">Both</option>
  <option value="1">Female</option>
  <option value="2">Male</option>
</select>

</td>
</tr>

<tr>

<td class="firstColumnStyling">
 No. of Position <span class="requiredField">* </span> :
</td>

<td>
<select name="no">
 
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  
</select>

</td>
</tr>


<tr>
<td></td>
<td>
<input type="submit" value="Add" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Current Openings</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading"> Position </th>
            <th class="heading"> Qualification</th>
            <th class="heading"> Gender </th>
             <th class="heading no_print btnCol" ></th>
            
        </tr>
    </thead>
    <tbody>
        
        <?php
		$careers = listCareers();
		$i=0;
		foreach($careers as $career)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $career['career_id'] ?>"><?php echo $career['position_name']; ?></span>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $career['career_id'] ?>"><?php echo $career['qualification']; ?></span>
            </td>
            
            <td><span  class="editLocationName" id="<?php echo $career['gender'] ?>">
			<?php 
			if($career['gender']==0) 
			{
			echo "Both";
			}
			else if($career['gender']==1) 
			{
			echo "F";
			}
			else if($career['gender']==2) 
			{
			echo "M";
			}
			?>
            </span>
            </td>
             
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$career['career_id']?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>