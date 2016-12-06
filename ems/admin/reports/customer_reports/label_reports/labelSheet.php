<?php

if(isset($_POST['selectTR']))
{
	$customer_id_array = $_POST['selectTR'];
   
	if(is_array($customer_id_array) && is_numeric($customer_id_array[0]))
	{
		
		
	}
else
exit;
}
else
exit;	 
?>

<link rel="stylesheet" href="../../../../css/labelSheet.css" />
<div class="mainInvoiceContainer">
<?php

for($i=0; $i<count($customer_id_array); $i++)
{
   
?>
 <?php if($i%8==0 && $i!=0) { ?>
    	<div style="page-break-before:always"></div>
        <?php } ?>
	<div class="row">
    	<?php if($i==0 || $i%8==0) { ?>
    	<div style="height:50px; width:100%;"></div>
        <?php } ?>
    	<div class="col1">
        
        <div class="colContent">
        
		<?php $customer_id=$customer_id_array[$i++]; if(is_numeric($customer_id)) { $customer=getCustomerById($customer_id); $contactNumbers=getCustomerContactNo($customer_id); $extra = getExtraCustomerDetailsById($customer_id); ?><span class="name"><?php echo $customer['customer_name'];?></span>
        <br />
        <pre style="padding:0; margin:0; font-family:Verdana, Geneva, sans-serif; font-size:14px" ><?php echo $extra['customer_address']  ;?><br/><?php  $cityId = $extra['city_id'];
			 if($cityId==NULL)
								   {}
								   else
								   {
								   $cityDetails = getCityByID($cityId);
								  // echo $cityDetails['city_name'];
								   } ?></pre>
                                 <pre style="font-family:Verdana, Geneva, sans-serif; font-size:14px" >Contact : <?php for($z=0; $z<count($contactNumbers); $z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." | ";				
                              } ?><?php } ?> </pre>
           </div>
           </div>
           
        <div class="col2" style="width:32.5%;">
		<div class="colContent">
		<?php $customer_id=$customer_id_array[$i++]; if(is_numeric($customer_id)) { $customer=getCustomerById($customer_id); $contactNumbers=getCustomerContactNo($customer_id); $extra = getExtraCustomerDetailsById($customer_id); ?><span class="name"><?php echo $customer['customer_name'];?></span><br /><pre style="padding:0; margin:0; font-family:Verdana, Geneva, sans-serif; font-size:14px" ><?php echo $extra['customer_address']  ;?><br/><?php  $cityId = $extra['city_id'];
    		 if($cityId==NULL)
								   {}
								   else
								   {
								   $cityDetails = getCityByID($cityId);
								   //echo $cityDetails['city_name'];
								   } ?></pre>
                                   <pre style="font-family:Verdana, Geneva, sans-serif; font-size:14px" >Contact : <?php for($z=0; $z<count($contactNumbers); $z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." | ";				
                              } ?><?php } ?> </pre>
                              
                </div>
                </div>
           <div class="col3">
		   <div class="colContent">
		   <?php $customer_id=$customer_id_array[$i]; if(is_numeric($customer_id)) { $customer=getCustomerById($customer_id); $contactNumbers=getCustomerContactNo($customer_id); $extra = getExtraCustomerDetailsById($customer_id); ?><span class="name"><?php echo $customer['customer_name'];?></span><br /><pre style="padding:0; margin:0; font-family:Verdana, Geneva, sans-serif; font-size:14px" ><?php echo $extra['customer_address'] ;?><br/><?php $cityId = $extra['city_id'];
			 if($cityId==NULL)
								   {}
								   else
								   {
								   $cityDetails = getCityByID($cityId);
								   //echo $cityDetails['city_name'];
								   } ?></pre> 
                                  <pre style="font-family:Verdana, Geneva, sans-serif; font-size:14px" >Contact : <?php for($z=0; $z<count($contactNumbers); $z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." | ";				
                              } ?><?php } ?></pre></div>
        <div style="clear:both">
        
        </div>
        </div>
    </div>
   
<?php } ?>    
   
</div>