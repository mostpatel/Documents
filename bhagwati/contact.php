<?php 
$body_id="contact";
$active_link = "contact";
require_once('admin/lib/cg.php');
require_once('header.php'); ?>
<div id="main" role="main">
<div id="herowrap">
	<div id="hero" style="position: relative; width: 1500px; margin-left: 0px; height: 482px;">
				
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3671.9385601436343!2d72.50905800000001!3d23.02602799999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b30af37188d%3A0x6100550e509da276!2sBhagwati+Holidays!5e0!3m2!1sen!2sin!4v1404823411241" width="100%" height="450" frameborder="0" style="border:0"></iframe>
                
    </div>
</div>   

<div class="row">

<div class="headOffice" style="float:left; width:50%; font-size:14px">
<h1 class="typ2u underline" style="margin-bottom:10px">Ahmedabad Head Office</h1>
         
         Nr. Fun Republic, <br />
         S.G Road, <br />
         ISKCON Flyover, <br />
         Ramdevnagar, <br />
         Ahmedabad - 380015 <br />
         Gujarat. <br /> <br />
Phone:079 4022 3333
</div>

<div class="branchOffice" style="float:right; width:50%; font-size:14px">
<h1 class="typ2u underline" style="margin-bottom:10px"> Surat Branch Office</h1>
          
          UG/22, Ascon Plaza, <br />
          Anand Mahel Road, <br />
          Adajan, <br />
          Surat - 395 009. <br />
          Gujarat. <br /> <br />
Phone : +91 261 2740041
</div>

</div>

             
<div class="row">

<section class="page-twocols clearfix">
	<div class="col">
		<h1 class="typ5u" style="margin-bottom:20px;">Contact: <span style="color:#666;">079-40223333</span> </h1>	
		<h1 class="typ2u underline">Contact Us</h1>	
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
		<form action="<?php echo "processMail.php?action=contact"; ?>" method="post"  class="form1 clearfix" id="contact-form">
	
		
			<p class="medium box border shadow clearfix"><label class="hidden">Name:</label><input type="text" name="contact.firstname" maxlength="100" placeholder="Name" id="fn"></p>
            <p class="medium box border shadow clearfix"><label class="hidden">Contact Number:</label><input type="text" name="contact.mobile" maxlength="100" placeholder="Mobile Number (10 digits)" id="ln"></p>
			<p class="medium box border shadow clearfix"><label class="hidden">Email:</label><input type="text" name="contact.emailaddress1" maxlength="100" placeholder="Email" id="em"></p>
			
			

			<p class="comment-form-comment box border shadow"><textarea rows="" cols="" name="contact.inquiry" placeholder="Type message here..."></textarea></p>

			<p><input type="submit" value="submit" class="btn big-btn typ16"></p>
		</form>

		<br /><br /><br />

	

	</div>

	


</section>

</div> <!-- .row -->


	</div> <!-- #main -->
<?php require_once('footer.php'); ?>