<?php
$page="contact";
include_once('header.php');
if(isset($_GET['sub']))
$subject = $_GET['sub'];

?>
<?php if(isset($_GET['return']) && $_GET['return']=="success") { ?>
<div class="alert no_print alert-success col-md-10 col-md-offset-1" style="margin-top:10px;">
  
   <strong>Success!</strong> Inquiry successfully Submitted! We will contact you shortly! 
</div>
<?php } else if(isset($_GET['return']) && $_GET['return']=="error") { ?>
<div class="alert no_print alert-warning col-md-10 col-md-offset-1" style="margin-top:10px;">
  
   <strong>Warning!</strong> Inquiry not Saved! Incorrect OR Insufficient Data!
</div>
<?php } ?>
 <section id="main-container" style="margin-top:20px;">
 <div class="container">
<div class="row">

				<div class="col-md-12">

					<h2 class="article-title">Contact Mahavir interface</h2>

				</div>
</div>
			</div><!-- Title row end -->

     <!-- Feature area start -->
     <section id="features" class="ts-features no-padding" style="margin-top:50px">
     	<div class="container">
			<div class="row">
            
				<div class="col-md-12">
                
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d29372.482037062186!2d72.518895!3d23.039913!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x8c661a78a5c9460e!2sMahavir+Interface+Pvt.+Ltd.!5e0!3m2!1sen!2sin!4v1458193708415" width="100%" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
					

			</div><!-- Content row end -->
		</div><!--/ Container end -->
     </section> <!--/ Feature area end -->
     
    
		<div class="container">

		



			<div class="row">

	    		<div class="col-md-6">

	    			<h3 class="title-normal">Contact Form</h3>

	    			<form id="contact-form" action="processMail.php?action=sendInquiry" method="post" role="form">

						<div class="row">

							<div class="col-md-12">

								<div class="form-group">

									<label>Name</label>

								<input <?php if(isset($subject)) { ?> autofocus <?php } ?> class="form-control" name="name" id="name" placeholder="" type="text" required>

								</div>

							</div>

							<div class="col-md-12">

								<div class="form-group">

									<label>Email</label>

									<input class="form-control" name="email" id="email" 

									placeholder="" type="email" required>

								</div>

							</div>

							<div class="col-md-12">

								<div class="form-group">

									<label>Subject</label>

									<input class="form-control" name="subject" id="subject" 

									placeholder="" required <?php if(isset($subject)) { ?> value="Products For <?php echo $subject; ?>" <?php } ?>>

								</div>

							</div>

						</div>

						<div class="form-group">

							<label>Message</label>

							<textarea class="form-control" name="message" id="message" placeholder="" rows="10" required></textarea>

						</div>

						<div class="text-right"><br>

							<button class="btn btn-primary solid blank" type="submit">Send Message</button> 

						</div>

					</form>

	    		</div>

	    		<div class="col-md-6">

	    			



	    			<h3 class="widget-title"><strong>Get</strong> In Touch</h3>

					<p>We are a great company to work with. We believe in quality and standard products.</p>
					<h4><i class="fa fa-map-marker">&nbsp;</i> Head Office</h4>
					<p>325/B, Platinum Plaza, <br>Nr. I.O.C. Petrol Pump,<br> Bodakdev, Ahmedabad-380054,<br> Gujarat, India.</p>

					<div class="row">
						<div class="col-md-12">
							<h4><i class="fa fa-envelope-o">&nbsp;</i> Email</h4>
							<p>admin@mahavirinterface.com</p>
						</div>
						<div class="col-md-12">
							<h4><i class="fa fa-phone">&nbsp;</i> Phone No</h4>
							<p>09825690912</p>
						</div>
					</div>

	    		</div>

	    	</div>



		</div><!--/ container end -->



	</section><!--/ Main container end -->

	



	<div class="gap-40"></div>





  

	

<?php
include_once('footer.php');
?>
<style>
/**
 * @author GeekTantra
 * @date 20 September 2009
 */
input, select {
    border: 1px solid #888;
    background: #ffffff;
    padding: 3px 4px;
    color: #222;
    margin: 0px 5px 0px 0px;
    border-radius: 7px;
    -moz-border-radius: 7px;
}

input:focus, select:focus {
    outline: none;
}

.InputGroup {
    display: inline-block;
    padding: 3px 4px;
    border: 1px solid #FFF;
    border-radius: 7px;
    -moz-border-radius: 7px;
}

.ErrorField {
    border-color: #D00;
    color: #D00;
    background: #FFFFFE;
}

span.ValidationErrors {
    display: inline-block;
    font-size: 12px;
    color: #D00;
    padding-left: 10px;
    font-style: italic;
}

</style>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script>
 jQuery("#name").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter Name!"
                });
				jQuery("#subject").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter Subject!"
                });
				jQuery("#message").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please Enter Message!"
                });
  jQuery("#email").validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Should be a valid Email id"
                });				
				
</script>
