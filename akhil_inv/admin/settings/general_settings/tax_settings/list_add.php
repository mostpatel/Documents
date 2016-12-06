<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Tax</h4>
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
Tax name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="name"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Display name<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="txtlocation" name="display_name" />
</td>
</tr>


<tr>

<td class="firstColumnStyling">
Tax %<span class="requiredField">* </span> :
</td>

<td>
<input type="text" id="tax_percent" name="percent"/>
</td>
</tr>

<tr>

<td class="firstColumnStyling">
Type<span class="requiredField">* </span> :
</td>

<td>
<select  id="tax_type" name="in_out">
	<option value="0">INPUT</option>
    <option value="1">OUTPUT</option>
    <option value="2">INCLUDE IN PURCHASE</option>
    <option value="3">INCLUDE IN SALE</option>
</select>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Tax" class="btn btn-warning" >
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Taxes</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	  <div class="no_print">
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        	<th class="heading">No</th>
             <th class="heading">Tax Name</th>
            <th class="heading">Tax Percent</th>
            <th class="heading">Type</th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$locations=listTaxs();
		$i=0;
		foreach($locations as $location)
		{
		
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><?php echo $location['tax_name']; ?>
            </td>
             <td><?php echo $location['tax_percent']; ?>
            </td>
            <td>
            	<?php if($location['in_out']==0) echo  "INPUT";  else if($location['in_out']==1) echo "OUTPUT"; else if($location['in_out']==2) echo "INCLUDE IN PURCHASE"; ?>
            </td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$location['tax_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$location['tax_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$location['tax_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
      </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>