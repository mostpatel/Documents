<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Bank</h4>
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
Bank Name<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="bank_name" id="txtbank"/> 
</td>
</tr>

<tr>
<td>
Bank Branch<span class="requiredField">* </span> : 
</td>

<td>
<input type="text" name="branch" id="txtbranch"/> 
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Add Bank" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Banks</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Bank Name</th>
            <th class="heading">Branches</th>
           <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$banks=listBanks();
		$no=0;
		foreach($banks as $bank)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><span  class="editLocationName" id="<?php echo $bank['bank_id'] ?>"><?php echo $bank['bank_name']; ?></span>
            </td>
             <td><?php $branches=$bank['branchArray'];
			 foreach($branches as $branch)
			 {
				 echo $branch['branch_name']." | ";
				 }
			  ?>
            </td>
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$bank['bank_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$bank['bank_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$bank['bank_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
   <table id="to_print" class="to_print adminContentTable"></table>  
</div>
<div class="clearfix"></div>