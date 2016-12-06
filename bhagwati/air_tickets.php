<?php 
$body_id="contact";
$active_link = "air";
require_once('admin/lib/cg.php');
require_once('admin/lib/bd.php');
require_once('admin/lib/common.php');
require_once('admin/lib/inquiry-location-type-functions.php');
require_once('admin/lib/inquiry-package-type-functions.php');
require_once('header.php'); ?>
 <style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  </style>
<link href="css/jquery-ui.min.css" rel="stylesheet" />
<link href="css/jquery-ui.theme.min.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-ui.min.js" ></script>
<div id="main" role="main">
<div id="herowrap">
<div id="hero" >
				
				<img class="main_photo" src="images/bhagwati-holidays-air-tickets-ahmedabad.jpg"  width="100%" style="min-height:500px;" />
                
    </div>
<script Type="text/javascript">
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
$('.main_photo').hide();
}
</script>    
</div>                
<div class="row">

<section class="page-twocols clearfix">
	<div class="col">
		<h1 class="typ5u" style="margin-bottom:20px;">Contact: <span style="color:#666;">079-40223314-24 | 9825035515</span> </h1>	
		<h1 class="typ2u underline">Air Ticket Booking</h1>	
       
		<br />
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
		<form action="<?php echo "processMail.php?action=air_tickets"; ?>" method="post"  class="form1 clearfix" id="contact-form">
	
		
			<p class="medium box border shadow clearfix"><label class="hidden">Name:</label><input type="text" name="contact.firstname" maxlength="100" placeholder="Name" id="fn"></p>
            <p class="medium box border shadow clearfix"><label class="hidden">Contact Number:</label><input type="text" name="contact.mobile" maxlength="100" placeholder="Mobile Number (10 digits)" id="ln"></p>
			<p class="medium box border shadow clearfix"><label class="hidden">Email:</label><input type="text" name="contact.emailaddress1" maxlength="100" placeholder="Email" id="em"></p>
            
            <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;line-height: 1.6666em;font-weight: 400;font-style: italic;font-size: 14px;">Type: &nbsp;</label><select name="contact.flight_type" maxlength="100" placeholder="no_of_nights" id="no_of_nights">
            	<?php $package_types = listInquiryLocTypes();
			 	foreach($package_types as $package_type)
				{
			  ?>
             	<option value="<?php echo $package_type['loc_type_id']; ?>"><?php echo $package_type['loc_type']; ?></option>
            	
                <?php } ?>
             </select></p>
             
             <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;
line-height: 1.6666em;
font-weight: 400;
font-style: italic;
font-size: 14px;">Adult: &nbsp;</label><select name="contact.adult" maxlength="100" placeholder="adult" id="adult">
             <?php for($i=1;$i<31;$i++) { ?>	
             	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
             <?php } ?>
             </select></p>
             
              <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;
line-height: 1.6666em;
font-weight: 400;
font-style: italic;
font-size: 14px;">Child: &nbsp;</label><select name="contact.child" maxlength="100" placeholder="child" id="child">
             <?php for($i=0;$i<31;$i++) { ?>	
             	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
             <?php } ?>
             </select></p>
             
              <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;line-height: 1.6666em;font-weight: 400;font-style: italic;font-size: 14px;">Return: &nbsp;</label><select name="contact.return_type" maxlength="100" placeholder="no_of_nights" id="return_type" onchange="changeReturnType(this.value)">
            	<option value="1">One Way</option>
                <option value="2">Return</option>
             </select></p>
            
            <p class="medium box border shadow clearfix"><label class="hidden">Start Date:</label><input type="text" name="contact.booking_date" maxlength="100" placeholder="Departure Date" id="bd"></p>
            
             <p class="medium box border shadow clearfix" style="display:none;" id="return_date"><label class="hidden">Return Date:</label><input type="text" name="contact.return_date" maxlength="100" placeholder="Return Date" id="ed"></p>
			
			 <p class="medium box border shadow clearfix"><label class="hidden">Destination:</label><input type="text" name="contact.destination" maxlength="100" placeholder="From" id="destination"></p>
             
              <p class="medium box border shadow clearfix"  id="to_destination" ><label class="hidden">Destination:</label><input type="text" name="contact.to_destination" maxlength="100" placeholder="To" id="to_d"></p>

			<p class="comment-form-comment box border shadow"><textarea rows="" cols="" name="contact.inquiry" placeholder="Type message here..."></textarea></p>

			<p><input type="submit" value="submit" class="btn big-btn typ16"></p>
		</form>

		<br /><br /><br />

	

	</div>

	


</section>

</div> <!-- .row -->


	</div> <!-- #main -->
     <script>
	 
	  $( "#destination" ).autocomplete({
      minLength: 3,
      source:  function(request, response) {
                $.getJSON ('admin/json/airport_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#destination" ).val(ui.item.label);
			return false;
		}
    });	
	
	 $( "#to_d" ).autocomplete({
      minLength: 3,
      source:  function(request, response) {
                $.getJSON ('admin/json/airport_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#to_d" ).val(ui.item.label);
			return false;
		}
    });	
	 
	 function changeReturnType(type)
	 {
		 if(type==2)
		 {
			 
			document.getElementById('return_date').style.display="block";
			
		}
		else
		{
			document.getElementById('return_date').style.display="none";
			
		}
	}
  $(function() {
    $( "#bd" ).datepicker({
	
      changeMonth: true,
      numberOfMonths: 3,
	  minDate : 0,
	  dateFormat: 'dd/mm/yy',
	  onClose: function( selectedDate ) {
        $( "#ed" ).datepicker( "option", "minDate", selectedDate );
      }
  });
  });
   $(function() {
    $( "#ed" ).datepicker({
	
      changeMonth: true,
      numberOfMonths: 3,
	  minDate : 0,
	  dateFormat: 'dd/mm/yy',
	  onClose: function( selectedDate ) {
        $( "#bd" ).datepicker( "option", "maxDate", selectedDate );
      }
  });
  });
  

 
  </script>
<?php require_once('footer.php'); ?>