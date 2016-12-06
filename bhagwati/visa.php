<?php 
require_once('admin/lib/cg.php');
$active_link = "visa";
$body_id="contact";
require_once('admin/lib/inquiry-visa-type-functions.php');
require_once('header.php'); ?>
<link href="css/jquery-ui.min.css" rel="stylesheet" />
<link href="css/jquery-ui.theme.min.css" rel="stylesheet" />
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
<script type="text/javascript" src="js/jquery-ui.min.js" ></script>
<div id="main" role="main">
<div id="herowrap">
	<div id="hero" class="main_photo" style="position: relative; width: 100%; margin-left: 0px; height: 500px; background-image:url(images/bhagwati-holidays-visa.jpg);background-repeat:no-repeat;background-size:cover;background-position-y:20%">       
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
		
       <h1 class="typ5u" style="margin-bottom:20px;">Contact: <span style="color:#666;">079-40223333</span> </h1>	
		<h1 class="typ2u underline">Plese Fill Up The Details Below</h1>	
       
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
		<form action="<?php echo "processMail.php?action=visa"; ?>" method="post"  class="form1 clearfix" id="contact-form">
	
    		
		
			<p class="medium box border shadow clearfix"><label class="hidden">Name:</label><input type="text" name="contact.firstname" maxlength="100" placeholder="Name" id="fn"></p>
            <p class="medium box border shadow clearfix"><label class="hidden">Contact Number:</label><input type="text" name="contact.mobile" maxlength="100" placeholder="Mobile Number (10 digits)" id="ln"></p>
			<p class="medium box border shadow clearfix"><label class="hidden">Email:</label><input type="text" name="contact.emailaddress1" maxlength="100" placeholder="Email" id="em"></p>
            
              <p class="medium box border shadow clearfix"><label class="hidden">Destination:</label><input type="text" name="contact.destination" maxlength="100" placeholder="Destination" id="destination"></p>
			
             
              <p class="medium box border shadow clearfix"><label for="no_of_nights" style="font-family: Georgia, serif;line-height: 1.6666em;font-weight: 400;font-style: italic;font-size: 14px;">Visa Type: &nbsp;</label><select name="contact.visa_type" maxlength="100" placeholder="no_of_nights" id="no_of_nights">
             
             	<?php $package_types = listInquiryVisaTypes();
			 	foreach($package_types as $package_type)
				{
			  ?>
             	<option value="<?php echo $package_type['visa_type_id']; ?>"><?php echo $package_type['visa_type']; ?></option>
            	
                <?php } ?>
             </select></p>
            
			
			

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
                $.getJSON ('admin/json/country_name.php',
                { term: request.term }, 
                response );
            },
	 select: function( event, ui ) {
			$( "#destination" ).val(ui.item.label);
			return false;
		}
    });	
	
  </script>
<?php require_once('footer.php'); ?>