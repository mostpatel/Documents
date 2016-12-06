<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">List Of Contact Inquiries</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=generateReport'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">

<tr>
<td width="230px">From Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="from" id="from_date" class="datepicker1"  value="<?php if(isset($_SESSION['cContactReport']['from'])) echo $_SESSION['cContactReport']['from']; ?>" placeholder="dd/mm/yyyy" />
                    </td>
                    
                    
                  
</tr>

<tr>
<td width="230px">To Date<span class="requiredField">* </span> : </td>
				<td>
					<input type="text" name="to" id="to_date" class="datepicker1" value="<?php if(isset($_SESSION['cContactReport']['to'])) echo $_SESSION['cContactReport']['to']; ?>" placeholder="dd/mm/yyyy" />
                    </td>
                    
                    
                  
</tr>

<tr>
<td ></td>
<td>
<input type="submit" value="Generate" id="disableSubmit" class="btn btn-warning">
</td>
</tr>
</table>
</form>

<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($_SESSION['cContactReport']['emi_array']))
{
	
	$emi_array=$_SESSION['cContactReport']['emi_array'];
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
    <div class="showColumns">
    	Print Columns : <input class="showCB" type="checkbox" id="1" checked="checked" /><label class="showLabel" for="1">No</label> 
        <input class="showCB" type="checkbox" id="2" checked="checked"  /><label class="showLabel" for="2">Inquiry Date</label> 
        <input class="showCB" type="checkbox" id="3" checked="checked"  /><label class="showLabel" for="3">Name</label> 
        <input class="showCB" type="checkbox" id="4" checked="checked"  /><label class="showLabel" for="4">Email</label> 
         <input class="showCB" type="checkbox" id="5" checked="checked"  /><label class="showLabel" for="5">Contact No</label> 
        <input class="showCB" type="checkbox" id="6" checked="checked"  /><label class="showLabel" for="6">Inquiry</label>  
    </div>
    <table id="adminContentReport" class="adminContentTable no_print">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
          
            <th class="heading date">Inquiry Date</th>
            <th class="heading">Name</th>
            <th width="10%" class="heading">Email</th>
            <th class="heading">Contact No</th>
           <th class="heading">Inquiry</th>
        </tr>
    </thead>
    <tbody>
      
        <?php
		
		foreach($emi_array as $emi)
		{
			
		 ?>
         
         <tr class="resultRow">
         	<td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	<td><div style="page-break-inside:avoid;"><?php echo ++$i; ?></div></td>
            
            <td><?php  $date_added=date('d/m/Y',strtotime($emi['date_added'])); if($date_added!='01/01/1970') echo $date_added; else echo "NA"; ?>
            </td>
            <td><?php   echo $emi['name']; ?></td>
             <td><?php   echo $emi['email']; ?></td>
             <td><?php   echo $emi['mobile_no']; ?></td>
			 <td><?php   echo $emi['message']; ?></td>
        
        </tr>
     
         <?php } }?>
            </tbody>
    </table>
    </div>
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>
