<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">Search Customer / Vehicle Details</h4>
<h4 class="subheadingAlignment no_print">Minimum one Input Required</h4>
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
<form id="addLocForm" action="<?php echo $_SERVER['PHP_SELF'].'?action=search'; ?>" method="post" enctype="multipart/form-data" onsubmit="return submitOurCompany()">

<table class="insertTableStyling no_print">
<?php if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<tr>
<td>
Chasis Number : 
</td>
<td>
<input type="text" name="chasis_no" id="chasis_no" placeholder="Only letters and numbers" autocomplete="off"/>
</td>
</tr>

<tr>
<td>Engine Number : </td>
				<td>
				 <input type="text"  name="engine_no" id="engine_no" placeholder="Only Letters and numbers" autocomplete="off"/>	
                 </td>
</tr>

<tr>
<td>Vehicle Number : </td>
				<td>
				 <input type="text"  name="reg_no" id="reg_no" placeholder="Only Letters and numbers" autocomplete="off"/>	
                 </td>
</tr>
<?php } ?>
<tr>
<td>Customer's Mobile Number : </td>
				<td>
				 <input type="text"  name="mobile_no" id="mobile_no" placeholder="Only numbers" autocomplete="off"/>	
                </td>
</tr>

<tr>
<td>Customer's Name : </td>
				<td>
				 <input type="text"  name="name" id="name" placeholder="Only Letters" autocomplete="off" autofocus />	
                </td>
</tr>
<?php if(defined('EDMS_MODE') && EDMS_MODE==1) { ?>
<tr>
<td>Financer / Dealer / Broker Name : </td>
				<td>
				 <input type="text"  name="financer_name" id="financer_name" placeholder="Only Letters" autocomplete="off"/>	
                </td>
</tr>

<tr>
<td>Item Name : </td>
				<td>
				 <input type="text"  name="item_name" id="item_name" placeholder="Only Letters" autocomplete="off"/>	
                </td>
</tr>

<tr>
<td>
Item Desc : 
</td>
<td>
<input type="text" name="item_desc" id="item_desc" placeholder="Only letters and numbers" autocomplete="off"/>
</td>
</tr>
<?php } ?>
<tr>
<td></td>
				<td>
				 <input type="submit" value="search" class="btn btn-warning" autocomplete="off"/>	
                </td>
</tr>


</table>

</form>
<?php if(isset($_SESSION['search']['file_id_array']) && count($_SESSION['search']['file_id_array'])>0)
{
	$file_id_array=$_SESSION['search']['file_id_array'];
	 ?>
<hr class="firstTableFinishing" />
<?php if(isset($_SESSION['search']['parameter']) && isset($_SESSION['search']['value'])) { ?>
<h4 class="headingAlignment">Search Results For <?php  echo $_SESSION['search']['parameter']; ?><?php if(count($file_id_array)==1) echo " LIKE "; else echo " : "; ?> "<?php  echo $_SESSION['search']['value']; ?>" !</h4>
<?php } ?>
<h4 class="subheadingAlignment no_print">Please Select One From below results!</h4>
	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
              <th class="heading">Customer Name</th>
               <th class="heading">Address</th>
               <th class="heading">Contact</th>
             <th class="heading no_print btnCol" ></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$no=0;
		foreach($file_id_array as $file_id)
		{
			if(is_array($file_id))
			{	
			$allDetails=getCustomerSearchResultDetailsFromCustomerId($file_id['customer_id']);
		    
			
			$customer=$allDetails['customer_array'];
			
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
           
            <td><?php echo $customer['customer_name']; ?>
            </td>
             <td><?php echo $customer['customer_address']; ?>
            </td>
             <td><?php $contactNos=$customer['contact_no']; $len=count($contactNos); for($i=0;$i<$len;$i++){
				 $contact=$contactNos[$i];
				 if($i!=($len-1)) echo $contact[0]." | "; else echo $contact[0];} ?>
            </td>
           
             <td class="no_print"> <a href="<?php echo WEB_ROOT.'admin/customer/index.php?view=details&id='.$file_id['customer_id'] ?>"><button title="Select" class="btn btn-warning">select</button></a>
            </td>
        </tr>
         <?php }}?>
         </tbody>
    </table>
    </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
<?php } ?>       
</div>
<div class="clearfix"></div>
<script>
 
 $( "#chasis_no" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/chasis_no.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#chasis_no" ).val(ui.item.label);
			return false;
		}
    });

 $( "#engine_no" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/engine_no.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#engine_no" ).val(ui.item.label);
			return false;
		}
    });	

$( "#reg_no" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/reg_no.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#reg_no" ).val(ui.item.label);
			return false;
		}
    });	

$( "#mobile_no" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/mobile_no.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#mobile_no" ).val(ui.item.label);
			return false;
		}
    });	
	
$( "#name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/customer_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#name" ).val(ui.item.label);
			return false;
		}
    });		

$( "#financer_name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/financersDealersBrokers.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#financer_name" ).val(ui.item.label);
			return false;
		}
    });		
	$( "#item_name" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/inventory_item.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#item_name" ).val(ui.item.label);
			return false;
		}
    });	
	
	$( "#item_desc" ).autocomplete({
      minLength: 1,
    source:  function(request, response) {
                $.getJSON ('<?php echo WEB_ROOT; ?>json/item_desc.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#item_desc" ).val(ui.item.label);
			return false;
		}
    });					
	
</script>