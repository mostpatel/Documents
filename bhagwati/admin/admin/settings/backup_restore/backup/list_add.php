<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a Backup</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=backup'; ?>" method="post">

<table class="insertTableStyling no_print">



<tr>
<td></td>
<td>
<input type="submit" value="Backup" class="btn btn-warning" >
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Backups</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	  <div class="no_print">
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">File Name</th>
            <th class="heading">Date</th>
             <th class="heading no_print btnCol" ></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$locations=listBackups();
		$i=0;
		foreach($locations as $location)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><?php echo $location['file_name']; ?>
            </td>
            <td><span style="display:none"><?php echo $location['date']; ?></span><?php echo date('d/m/Y',strtotime($location['date'])); ?>
            </td>
              <td class="no_print"> <a href="<?php echo WEB_ROOT."backups/".$location['file_name']; ?>"><button title="Download this backup" class="btn btn-warning">Download</button></a>
            </td>
        </tr>
         <?php }?>
         </tbody>
    </table>
      </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>