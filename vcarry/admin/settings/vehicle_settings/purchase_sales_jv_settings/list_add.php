<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Add a New Vehicle Type</h4>
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
Ledger Name<span class="requiredField">* </span> :
</td>

<td>
<input type="hidden" name="ledger_id" class="ledger_id" id="ledger_id" value="" />
<input type="text" name="ledger_name" id="txtName"/>
</td>
</tr>

<tr>
<tr>
       <td>Debit Or Credit The Supplier<span class="requiredField">* </span> :</td>
           
           
        <td>
              <table>
               <tr><td><input type="radio"  name="cr_dr"  value="0" id="debit" checked="checked" ></td><td><label for="debit">Debit</label></td>
               </tr>
               <tr><td><input type="radio"   name="cr_dr"  value="1" id="credit"></td><td><label for="credit">Credit</label></td></tr>
            </table>
        </td>
 </tr>
 <tr>
       <td>Type<span class="requiredField">* </span> :</td>
           
           
        <td>
              <table>
               <tr><td><input type="radio"   name="type"  value="1" checked="checked" id="sales"></td><td><label for="sales">Sales</label></td></tr>
            <tr><td><input type="radio"  name="type"  value="0" id="purchase" ></td><td><label for="purchase">Purchase</label></td>
               </tr> 
            </table>
        </td>
 </tr>
 
<td></td>
<td>
<input type="submit" value="Add Purchase / Sales JV" class="btn btn-warning">
<a href="<?php echo WEB_ROOT ?>admin/settings/"><input type="button" value="back" class="btn btn-success" /></a>
</td>
</tr>

</table>
</form>

<hr class="firstTableFinishing" />

<h4 class="headingAlignment">List of Vehicle Types</h4>
<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Ledger Name </th>
             <th class="heading">Credit / Debit </th>
              <th class="heading">Type </th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$vehicles=listPurchaseSalesJvs();
		$no=0;
		foreach($vehicles as $vehicleType)
		{
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php echo getLedgerNameFromLedgerId($vehicleType['ledger_id']); ?>
            </td>
            <td><?php if($vehicleType['cr_dr']==0) echo "DR"; else echo "CR"; ?>
            </td>
              <td><?php if($vehicleType['type']==0) echo "Purchase"; else echo "Sales"; ?>
            </td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&lid='.$vehicleType['purchase_sales_jv_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&lid='.$vehicleType['purchase_sales_jv_id'] ?>"><button title="Edit this entry" class="btn editBtn"><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&lid='.$vehicleType['purchase_sales_jv_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
     </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>
<script>
  $( "#txtName" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/ledgersOnly.php',
                { term: request.term }, 
                response );
            },
			autoFocus: true,
    selectFirst: true,
	response: function(e, ui) {
		
		
    if (ui.content.length == 0) {
        $(this).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
},change: function(e, ui) {
    if (!ui.item) {
        $(this).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
},
    open: function(event, ui) {  select_var=false; target_el=event.target },
    select: function(event, ui) { select_var=true; $(event.target).val(ui.item.label);
	
	$(this).prevAll(".ledger_id").val(ui.item.id);  
	if (!ui.item) {
        $(this).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
			 }
}).blur(function(){
    if(!select_var)
    {
		$(target_el).val("");
		$(this).prevAll(".ledger_id").val("");  
    }
 });
</script>