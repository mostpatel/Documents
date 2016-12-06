<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Select Appropriate Filters</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF']."?action=add"; ?>" method="post">

<table  class="insertTableStyling no_print">

<tr>

<td class="firstColumnStyling">
From Date : 
</td>

<td>
<input type="text" id="datepicker" size="12" autocomplete="off"  name="from_date" class="datepicker1 datepick" value="<?php if(isset($_SESSION['sWiseReport']['from_date']) && validateForNull($_SESSION['sWiseReport']['from_date']))
		 { echo $_SESSION['sWiseReport']['from_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>

<td>
To Date : 
</td>

<td>
<input type="text" id="datepicker1" size="12" autocomplete="off"  name="to_date" class="datepicker2 datepick" value="<?php if(isset($_SESSION['sWiseReport']['to_date']) && validateForNull($_SESSION['sWiseReport']['to_date']))
		 { echo $_SESSION['sWiseReport']['to_date'];} ?>" placeholder="Click to Select!" /><span class="customError DateError">Please select a date!</span>
</td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Generate" class="btn btn-warning">
</td>
</tr>


</table>
</form>
 <?php
	
		if(isset($_SESSION['sWiseReport']['sWise_array']) && is_array($_SESSION['sWiseReport']['sWise_array']))
		{
			
			$sourceWiseDetailsArray = $_SESSION['sWiseReport']['sWise_array'];
			
			
		$i=0; 
?>
     
   

<hr class="firstTableFinishing" />

<h4 class="headingAlignment"> Enquiry Source Reports </h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentReport" class="adminContentTable">
    <thead>
    	<tr>
        <th class="heading no_print">Print</th>
        	<th class="heading">No</th>
            <th class="heading">Source of Enquiry</th>
            <th class="heading">Total Enquiries Generated</th>
            <th class="heading">Successful</th>
            <th class="heading">Unsuccessful</th>
            
           
        </tr>
    </thead>
    <tbody>
        
        <?php
		
		foreach($sourceWiseDetailsArray as $sourceWiseDetails)
		{
		
        $customer_type = $sourceWiseDetails['customer_type'];
		$total_enquiry_generated = $sourceWiseDetails['total_enquiry_generated'];
		$successful = $sourceWiseDetails['successful_enquiry_generated'];
		$unsuccessful = $sourceWiseDetails['unsuccessful_enquiry_generated'];
		
		
		$successful_efficiency = round((($successful/$total_enquiry_generated)*100), 2);
		$unsuccessful_efficiency = round((($unsuccessful/$total_enquiry_generated)*100), 2);
		
		
		?>
      
          <tr class="resultRow">
          <td class="no_print"><input type="checkbox" class="selectTR" name="selectTR"  /></td>
        	
            <td><?php echo ++$i; ?>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $customer_type; 
			?>
            </span>
            </td>
            
            <td><span  class="editLocationName">
			<?php
			echo $total_enquiry_generated;
			?>
            </span>
            </td>

        
            <td>
            <span  class="editLocationName">
			<?php 
			echo $successful. " (".$successful_efficiency."%)";
			?>
            </span>
            </td>
            
            <td>
            <span  class="editLocationName">
			<?php 
			echo $unsuccessful. " (".$unsuccessful_efficiency."%)";
			?>
            </span>
            </td>
            
            
      
            
            
          </tr>
          
          <?php
		}
		  ?>
      
            </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
         <?php 
		 }?>
      
</div>
<div class="clearfix"></div>
