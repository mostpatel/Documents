<?php
$admin_id=$_SESSION['adminSession']['admin_id'];
$current_company = getCurrentCompanyForUser($admin_id);
$period = getPeriodForUser($admin_id);
		
$from=date('d/m/Y',strtotime($period[0]));
$to=date('d/m/Y',strtotime($period[1]));
$reportArray=getProfitAndLossSheet($from,$to);
		
 ?>
<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">Profit And Loss Sheet Of  <?php echo $current_company[2];  echo ' ['.$from.'-'.$to."]";  ?></h4>
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

<hr class="firstTableFinishing" />
 

	<div class="no_print">
 <?php if(isset($reportArray))
{
	
	$transaction_array=$reportArray;

	
	 ?>    
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>     
  
    <table id="accountContentTable" class="adminContentTable no_print">
    <thead>
    	<tr> 
        <th class="heading no_sort" colspan="2">Particulars</th>
        <th class="heading date" colspan="2">Particulars</th>
        </tr>
    </thead>
    <tbody>
        <?php
		if($transaction_array!="error" && is_array($transaction_array))
		{
			$debit_total=0;
			$credit_total=0;
			$direct_income=$transaction_array[0]['opening_balance'];
			$indirect_income=$transaction_array[1]['opening_balance'];
			$direct_exp=$transaction_array[2]['opening_balance'];
			$indirect_exp=$transaction_array[3]['opening_balance'];
			
			$gross_profit=$transaction_array['gross_profit']; // should be negative if profit and positive if loss
	$sub_total=$transaction_array['subtotal']; // always positive
	$net_profit=$transaction_array['nett_profit']; // should be negative if profit and positive if loss
	$total=$transaction_array['total']; // always positive
			
		?>
				
             <?php } ?>
       	
            	<?php if($direct_income<=0) { 
				$child_heads=getSecondPagePLSheet($transaction_array[0]['head_id']);
				$child_ledgers = $child_heads['ledgers'];
				$child_heads = $child_heads['child_heads'];
				
				?>
                	<tr class="resultRow">
                <td></td>
                <td></td>
                <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[0]['head_id']; ?>"><b><?php echo $transaction_array[0]['head_name']; ?></b></a><br />
               
                <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                    
                    </td>			
					<td><?php echo number_format(-$transaction_array[0]['opening_balance'],2); ?></td>
					</tr>
                 <?php } else if($direct_income>0) {
				 $child_heads=getSecondPagePLSheet($transaction_array[0]['head_id']);
				 $child_ledgers = $child_heads['ledgers'];
				$child_heads = $child_heads['child_heads'];
				
					  ?>
                 <tr class="resultRow">
				  <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[0]['head_id']; ?>"><b><?php echo $transaction_array[0]['head_name']; ?></b></a>
                  
                    <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                  
                  </td>			
					<td><?php echo number_format($transaction_array[0]['opening_balance'],2); ?></td>
				 <td></td>
                <td></td>
                </tr>
				 <?php } ?>
                 
                 <?php if($direct_exp<0) {
					 $child_heads=getSecondPagePLSheet($transaction_array[2]['head_id']);
					 $child_ledgers = $child_heads['ledgers'];
				     $child_heads = $child_heads['child_heads'];
				
					  ?>
                	<tr class="resultRow">
                <td></td>
                <td></td>
                <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[2]['head_id']; ?>"><b><?php echo $transaction_array[2]['head_name']; ?></b></a>
                
                  <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                
                </td>			
					<td><?php echo number_format(-$transaction_array[2]['opening_balance'],2); ?></td>
					</tr>
                 <?php } else if($direct_exp>=0) { 
				  $child_heads=getSecondPagePLSheet($transaction_array[2]['head_id']);
				  	$child_ledgers = $child_heads['ledgers'];
				$child_heads = $child_heads['child_heads'];
			
				 ?>
                 <tr class="resultRow">
				  <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[2]['head_id']; ?>"><b><?php echo $transaction_array[2]['head_name']; ?></b></a>
                  
                    <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                  
                  </td>			
					<td><?php echo number_format($transaction_array[2]['opening_balance'],2); ?></td>
				 <td></td>
                <td></td>
                </tr>
				 <?php } ?>
                 
                  <?php if($gross_profit>0) { ?>
                	<tr class="resultRow">
                <td></td>
                <td></td>
                <td>Gross Loss c/o</td>			
					<td><?php echo number_format($gross_profit,2); ?></td>
					</tr>
                 <?php } else if($gross_profit<=0) { ?>
                 <tr class="resultRow">
				  <td>Gross Profit c/o</td>			
					<td><?php echo number_format(-$gross_profit,2); ?></td>
				 <td></td>
                <td></td>
                </tr>
				 <?php } ?>
            
       		 <tr class="resultRow">
					
                   <td><b>Sub Total</b></td>
					<td><b><?php echo number_format($sub_total,2); ?></b></td>
					
                   
					<td><b>Sub Total</b></td>
					<td><b><?php echo number_format($sub_total,2); ?></b></td>
					
				  
		  
				</tr>
                
                 <?php if($gross_profit<=0) { ?>
                	<tr class="resultRow">
                <td></td>
                <td></td>
                <td>Gross Profit b/f</td>			
					<td><?php echo number_format(-$gross_profit,2); ?></td>
					</tr>
                 <?php } else if($gross_profit>0) { ?>
                 <tr class="resultRow">
				  <td>Gross Loss b/f</td>			
					<td><?php echo number_format($gross_profit,2); ?></td>
				 <td></td>
                <td></td>
                </tr>
				 <?php } ?>
                
               <?php if($indirect_income<=0) {
				  
				   $child_heads=getSecondPagePLSheet($transaction_array[1]['head_id']);
				 
				  $child_ledgers = $child_heads['ledgers'];
				$child_heads = $child_heads['child_heads'];
				
				
				    ?>
                	<tr class="resultRow">
                <td></td>
                <td></td>
                <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[1]['head_id']; ?>"><b><?php echo $transaction_array[1]['head_name']; ?></b></a>
                
                  <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                
                </td>			
					<td><?php echo number_format(-$transaction_array[1]['opening_balance'],2); ?></td>
					</tr>
                 <?php } else if($indirect_income>0) { 
				 $child_heads=getSecondPagePLSheet($transaction_array[1]['head_id']);
				 	$child_ledgers = $child_heads['ledgers'];
				$child_heads = $child_heads['child_heads'];
			
				 ?>
                 <tr class="resultRow">
				  <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[1]['head_id']; ?>"><b><?php echo $transaction_array[1]['head_name']; ?></b></a>
                  
                   <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                  
                  </td>			
					<td><?php echo number_format($transaction_array[1]['opening_balance'],2); ?></td>
				 <td></td>
                <td></td>
                </tr>
				 <?php } ?>
                 
                 <?php if($indirect_exp<0) { 
				 $child_heads=getSecondPagePLSheet($transaction_array[3]['head_id']);
				 $child_ledgers = $child_heads['ledgers'];
				$child_heads = $child_heads['child_heads'];
				
				 ?>
                	<tr class="resultRow">
                <td></td>
                <td></td>
                <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[3]['head_id']; ?>"><b><?php echo $transaction_array[3]['head_name']; ?></b></a>
                
                  <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                
                </td>			
					<td><?php echo number_format(-$transaction_array[3]['opening_balance'],2); ?></td>
					</tr>
                 <?php } else if($indirect_exp>=0) { 
				  $child_heads=getSecondPagePLSheet($transaction_array[3]['head_id']);
				  $child_ledgers = $child_heads['ledgers'];
				$child_heads = $child_heads['child_heads'];
				
				 ?>
                 <tr class="resultRow">
				  <td><a href="<?php echo WEB_ROOT; ?>admin/accounts/pl_sheet/index.php?view=second&id=<?php echo $transaction_array[3]['head_id']; ?>"><b><?php echo $transaction_array[3]['head_name']; ?></b></a>
                  
                    <table border="0" width="100%"><?php foreach($child_heads as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['head_name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} foreach($child_ledgers as $child_head) {  if(is_numeric($child_head['opening_balance']) && ($child_head['opening_balance']>0 || $child_head['opening_balance']<0)) { ?><tr>
                    <td style="border:none;"><?php echo $child_head['name']; ?></td>
                    <td><?php if($child_head['opening_balance']>=0) echo round($child_head['opening_balance'],2)." DR"; else echo -round($child_head['opening_balance'],2)." CR";  ?></td>
                    </tr> <?php }} ?></table>
                  
                  </td>			
					<td><?php echo number_format($transaction_array[3]['opening_balance'],2); ?></td>
				 <td></td>
                <td></td>
                </tr>
				 <?php } ?> 
                 
                <?php if($net_profit>0) { ?>
                	<tr class="resultRow">
                <td></td>
                <td></td>
                <td>Nett Loss</td>			
					<td><?php echo number_format($net_profit,2); ?></td>
					</tr>
                 <?php } else if($net_profit<=0) { ?>
                 <tr class="resultRow">
				  <td>Nett Profit</td>			
					<td><?php echo number_format(-$net_profit,2); ?></td>
				 <td></td>
                <td></td>
                </tr>
				 <?php } ?>
                  <tr class="resultRow">
					
                   <td><b>Total</b></td>
					<td><b><?php echo number_format($total,2); ?></b></td>
					
                   
					<td><b>Total</b></td>
					<td><b><?php echo number_format($total,2); ?></b></td>
					
				  
		  
				</tr>
               
         </tbody>
    </table>
    </div>
    <?php } ?> 
   <table id="to_print" style="width:100%;" class="to_print adminContentTable"></table> 
<?php  ?>      
</div>
<div class="clearfix"></div>
