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
	<div class="envelope">
    	
        
        <div class="envelopeContent">
        
		<?php $customer_id=$customer_id_array[$i]; if(is_numeric($customer_id)) { $customer=getCustomerById($customer_id); $contactNumbers=getCustomerContactNo($customer_id); $extra = getExtraCustomerDetailsById($customer_id); ?><span class="name"><?php echo $customer['customer_name'];?></span>
        <br />
        <pre stye="padding:0;margin:0;" ><?php echo $extra['customer_address']  ;?><br/><?php  $cityId = $extra['city_id'];
			 if($cityId==NULL)
								   {}
								   else
								   {
								   $cityDetails = getCityByID($cityId);
								  // echo $cityDetails['city_name'];
								   } ?></pre>
                                   Contact : <?php for($z=0; $z<count($contactNumbers); $z++)
                              {
								$c=$contactNumbers[$z];
								if($z==(count($contactNumbers)-1))
								echo $c[0];  
								else
                      			echo $c[0]." | ";				
                              } ?><?php } ?>
           </div>
         
    </div>
<?php } ?>    
   
</div>