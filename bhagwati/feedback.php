<?php 
$body_id="contact";
$active_link = "visa";
require_once('admin/lib/cg.php');
require_once('admin/lib/inquiry-visa-type-functions.php');
require_once('header.php'); ?>
<link href="css/jquery-ui.min.css" rel="stylesheet" />
<link href="css/jquery-ui.theme.min.css" rel="stylesheet" />
<link href="css/ratings.min.css" rel="stylesheet" />
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
  .stars{
    width: 130px;
    height: 26px;
    background: url(http://sandbox.bumbu.ru/ui/external/stars.png) 0 0 repeat-x;
    position: relative;
}

.stars .rating{
    height: 26px;
    background: url(http://sandbox.bumbu.ru/ui/external/stars.png) 0 -26px repeat-x;
}

.stars input{
    display: none;
}

.stars label{
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    height: 26px;
    width: 130px;
    cursor: pointer;
}
.stars:hover label{
    display: block;
}
.stars label:hover{
    background: url(http://sandbox.bumbu.ru/ui/external/stars.png) 0 -52px repeat-x;
}

.stars label + input + label{width: 104px;}
.stars label + input + label + input + label{width: 78px;}
.stars label + input + label + input + label + input + label{width: 52px;}
.stars label + input + label + input + label + input + label + input + label{width: 26px;}

.stars input:checked + label{
    display: block;
    background: url(http://sandbox.bumbu.ru/ui/external/stars.png) 0 -52px repeat-x;
}
#ui-datepicker-div
{
	z-index:1000;
	}

#pre_tour,#accommodation,#sightseeing,#transportation,#guides
{
	margin-top:25px;
	margin-bottom:25px;
	}	

  </style>
<script type="text/javascript" src="js/jquery-ui.min.js" ></script>
<div id="main" role="main">
<!-- <div id="herowrap">
	<div id="hero" style="position: relative; width: 100%; margin-left: 0px; height: 500px; background-image:url(images/bhagwati-holidays-visa.jpg);background-repeat:no-repeat;background-size:cover;background-position-y:20%">       
    </div>
</div>   -->              
<div class="row">

<section class="page-twocols clearfix">
	<div class="col">
		
       <h1 class="typ5u" style="margin-bottom:20px;">Contact: <span style="color:#666;">079-40223333</span> </h1>	
		<h1 class="typ2u underline">Please Fill Up The Feedback Below</h1>	
       
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
		<form action="<?php echo "processMail.php?action=feedback"; ?>" method="post"  class="form1 clearfix" id="contact-form">
	
    		<table>
           

 <tr>
            <td>

<p class="medium box border shadow clearfix"><label class="hidden">Name:</label><input type="text" name="contact.firstname" maxlength="100" placeholder="Name" id="fn" <?php if(isset($_GET['a'])) { ?> value="<?php echo $_GET['a']; ?>" <?php } ?> ></p>
  </td>
  <td>

 </td></tr>
 
 <tr><td>
<p class="medium box border shadow clearfix"><label class="hidden">Contact Number:</label><input type="text" name="contact.mobile" maxlength="100" placeholder="Mobile Number (10 digits)" id="ln" <?php if(isset($_GET['b'])) { ?> value="<?php echo $_GET['b']; ?>" <?php } ?>></p>
            
  </td>
  <td>
 
 </td>
 </tr>
 
<tr><td>
 <p class="medium box border shadow clearfix"><label class="hidden">Email:</label><input type="text" name="contact.emailaddress" maxlength="100" placeholder="Email" id="em" <?php if(isset($_GET['c'])) { ?> value="<?php echo $_GET['c']; ?>" <?php } ?>></p>
            
  </td>
  <td>
 
 </td>
 </tr>
 <tr>
 <td>

  <p class="medium box border shadow clearfix"> <label for="edit-submitted-about-you-name-of-tour-you-travelled-on" class="hidden">Name of Tour / Destination </label><input type="text" id="edit-submitted-about-you-name-of-tour-you-travelled-on" name
 ="name_of_tour_you_travelled_on" placeholder="Name of Tour / Destination" size="80" maxlength="200" class="form-text required" <?php if(isset($_GET['d'])) { ?> value="<?php echo $_GET['d']; ?>" <?php } ?> />
 </td>
 <td>
 
</td>
</tr>
<tr>
<td>
 
   <p class="medium box border shadow clearfix"><label class="hidden">Start Date:</label><input type="text" name="contact.tour_date" maxlength="100" placeholder="Tour Date" id="bd" <?php if(isset($_GET['e'])) { ?> value="<?php echo $_GET['e']; ?>" <?php } ?>></p>
            
  </td>
  <td>
 </td>
 </tr>
 <tr>
 <td>

  <p class="large box border shadow clearfix"><label for="edit-submitted-about-you-where-did-you-hear-about-us" style="margin-right:30px;">Where did you hear about us? </label>
  <select id="edit-submitted-about-you-where-did-you-hear-about-us" name="where_did_you_hear_about_us" class="form-select"><option value="" selected="selected">- None -</option><option value="search_engine">Search Engine</option><option value="word_of_mouth">Word of Mouth</option><option value="magazine">Social Media</option><option value="newspaper">Newspaper</option><option value="other">Other</option></select></p>
  </td>
  <td>
 
</td>
</tr>

 <tr>
 <td>

<h2 class="typ4u">Pre Tour Expirence</h2>	
  </td>
  <td>

 </td>
 </tr>
 <tr>
<td>
<input type="hidden"  name="pre_tour_rating"  id="pre_tour_rating" value="0" />
  <div id="pre_tour" style="z-index:0;"></div></td>
  <td>
</td>
 </tr>
 <tr>
 <td> <p class="medium box border shadow clearfix"> <label for="edit-submitted-pre-tour-experience-fieldset-how-did-you-find-the-booking-experience-with-your-travel-consultant" class="hidden">How did you find the booking experience?  </label> <textarea id="edit-submitted-pre-tour-experience-fieldset-how-did-you-find-the-booking-experience-with-your-travel-consultant" name="how_did_you_find_the_booking_experience" placeholder="How did you find the booking expirence with us?" cols="60" rows="5" class="form-textarea" style="resize:none"></textarea></p></td>
 <td></td>
 </tr>
 
 <tr>
 <td>

<h2 class="typ4u">Transport</h2>	
  </td>
  <td>

 </td>
 </tr>
 <tr>
<td>
<input type="hidden" name="transportation_rating" id="transportation_rating" value="0" />
  <div id="transportation"></div></td>
  <td>
</td>
 </tr>
 <tr>
 <td> <p class="medium box border shadow clearfix">  <label for="edit-submitted-transport-fieldset-what-did-you-think-of-the-transportation-used" class="hidden">What did you think of the transportation used? </label> <textarea id="edit-submitted-transport-fieldset-what-did-you-think-of-the-transportation-used" name="what_did_you_think_of_the_transportation_used" placeholder="what did you think of the transportation used?" cols="60" rows="5" class="form-textarea" style="resize:none"></textarea></p></td>
 <td></td>
 </tr>
 
 <tr>
 <td>

<h2 class="typ4u">Accommodation</h2>	
  </td>
  <td>

 </td>
 </tr>
 <tr>
<td>
<input type="hidden" name="accommodation_rating" id="accommodation_rating" value="0" />
  <div id="accommodation"></div></td>
  <td>
</td>
 </tr>
 <tr>
 <td> <p class="medium box border shadow clearfix">  <label class="hidden" for="edit-submitted-accommodation-fieldset-accommodation"> Accommodation</label><textarea id="edit-submitted-accommodation-fieldset-accommodation" name="what_did_you_think_of_the_accommodation_used" placeholder="what did you think of the accommodation used?" cols="60" rows="5" class="form-textarea" style="resize:none"></textarea></p></td>
 <td></td>
 </tr>
 
 
 <tr>
 <td>

<h2 class="typ4u">Guides</h2>	
  </td>
  <td>

 </td>
 </tr>
 <tr>
<td>
<input type="hidden" name="guides_rating" id="guides_rating" value="0" />
  <div id="guides"></div></td>
  <td>
</td>
 </tr>
 <tr>
 <td> <p class="medium box border shadow clearfix">   <label class="hidden" for="edit-submitted-guides-fieldset-what-did-you-think-of-your-tour-guides">What did you think of your tour guides? </label><textarea id="edit-submitted-guides-fieldset-what-did-you-think-of-your-tour-guides" name="what_did_you_think_of_your_tour_guides" placeholder="what did you think of your tour guides?" cols="60" rows="5" class="form-textarea" style="resize:none"></textarea></p></td>
 <td></td>
 </tr>
 
  <tr>
 <td>

<h2 class="typ4u">Sightseeing</h2>	
  </td>
  <td>

 </td>
 </tr>
 <tr>
<td>
<input type="hidden" name="sightseeing_rating"  id="sightseeing_rating" value="0" />
  <div id="sightseeing"></div></td>
  <td>
</td>
 </tr>
 <tr>
 <td> <p class="medium box border shadow clearfix">   <label class="hidden" for="edit-submitted-sightseeing-fieldset-how-did-you-find-the-sightseeing-on-tour">How did you find the sightseeing on tour? </label><textarea id="edit-submitted-sightseeing-fieldset-how-did-you-find-the-sightseeing-on-tour" name="how_did_you_find_the_sightseeing_on_tour" placeholder="How did you find the sightseeing on tour?" cols="60" rows="5" class="form-textarea" style="resize:none"></textarea></p></td>
 <td></td>
 </tr>
 <tr><td>

<p class="medium box border shadow clearfix">  <label class="hidden" for="edit-submitted-other-fieldset-what-were-your-most-memorable-moments-on-tour">What were your most memorable moments on tour?  </label><textarea id="edit-submitted-other-fieldset-what-were-your-most-memorable-moments-on-tour" name="what_were_your_most_memorable_moments_on_tour" cols="60" rows="5" class="form-textarea" placeholder="What were your most memorable moments on tour?"  style="resize:none"></textarea></p>
  </td>
  <td>

</td>
</tr>
<tr><td>
  <p class="medium box border shadow clearfix"><label class="hidden" for="edit-submitted-other-fieldset-what-was-your-impression-of-the-tour-and-do-you-have-any-suggestions-for-improvements">What was your impression of the tour and do you have any suggestions for improvements? </label>
   <textarea id="edit-submitted-other-fieldset-what-was-your-impression-of-the-tour-and-do-you-have-any-suggestions-for-improvements" name="what_was_your_impression_of_the_tour_and_do_you_have_any_suggestions_for_improvements" placeholder="What was your impression of the tour and do you have any suggestions for improvements?" cols="60" rows="5" class="form-textarea" style="resize:none"></textarea></p>
  </td>
  <td>

</td>
</tr>

<tr><td>
  <p class="large box border shadow clearfix"><label style="margin-right:30px;" for="edit-submitted-other-fieldset-did-you-find-this-tour-value-for-money">Did you find this tour value for money? </label>
   <select id="edit-submitted-other-fieldset-did-you-find-this-tour-value-for-money" name="did_you_find_this_tour_value_for_money" class="form-select"><option value="" selected="selected">- None -</option><option value="yes">Yes</option><option value="no">No</option></select>
   </p>
  </td>
  <td>

</td>
</tr>

<tr><td>
  <p class="large box border shadow clearfix"><label style="margin-right:30px;" for="edit-submitted-other-fieldset-would-you-travel-with-us-again">Would you travel with us again? </label>
   <select id="edit-submitted-other-fieldset-would-you-travel-with-us-again" name="would_you_travel_with_us_again" class="form-select"><option value="" selected="selected">- None -</option><option value="yes">Yes</option><option value="no">No</option></select>
   </p>
  </td>
  <td>

</td>
</tr>

<tr><td>
  <p class="large box border shadow clearfix"><label style="margin-right:30px;" for="edit-submitted-other-fieldset-would-you-recommend-us-to-others">Would you recommend us to others? </label>
   <select id="edit-submitted-other-fieldset-would-you-recommend-us-to-others" name="would_you_recommend_us_to_others" class="form-select"><option value="" selected="selected">- None -</option><option value="yes">Yes</option><option value="no">No</option></select>
   </p>
  </td>
  <td>

</td>
</tr>

<tr><td>
  <p class="medium box border shadow clearfix"><label class="hidden" style="margin-right:30px;" for="edit-submitted-other-fieldset-where-would-you-like-to-visit-next">Where would you like to visit next? </label>
   <input type="text" id="edit-submitted-other-fieldset-where-would-you-like-to-visit-next" name="where_would_you_like_to_visit_next"  maxlength="100" placeholder="Where would you like to visit next?"  ></p>
   </p>
  </td>
  <td>

</td>
</tr>

<tr><td>
  <p class="medium box border shadow clearfix"><label class="hidden" for="edit-submitted-other-fieldset-any-further-comments-you-would-like-to-give">Any further comments you would like to give </label>
   <textarea id="edit-submitted-other-fieldset-any-further-comments-you-would-like-to-give" name="any_further_comments_you_would_like_to_give" placeholder="Any further comments you would like to give" cols="60" rows="5" class="form-textarea" style="resize:none"></textarea></p>
  </td>
  <td>

</td>
</tr>

<tr>
<td><input type="submit" value="submit" class="btn big-btn typ16"></td>
<td>

</td>
</tr>
</table>
</form>
	

	</div>

	


</section>

</div> <!-- .row -->


	</div> <!-- #main -->
      <script src="js/ratings.js"></script>
     <script>
	 
	 $(function () {
 
  $("#pre_tour").rateYo({
     ratedFill: "#e2bb3d"
  }).on("rateyo.change", function (e, data) {
 			
                var rating = data.rating;
                $('#pre_tour_rating').val(rating);
				
              });
 
});
	 
	  $(function () {
 
  $("#guides").rateYo({
     ratedFill: "#e2bb3d"
  }).on("rateyo.change", function (e, data) {
 			
                var rating = data.rating;
                $('#guides_rating').val(rating);
				
              });
 
});
	 
	  $(function () {
 
  $("#transportation").rateYo({
     ratedFill: "#e2bb3d"
  }).on("rateyo.change", function (e, data) {
 			
                var rating = data.rating;
                $('#transportation_rating').val(rating);
				
              });
 
});
	 
	  $(function () {
 
  $("#accommodation").rateYo({
     ratedFill: "#e2bb3d"
  }).on("rateyo.change", function (e, data) {
 			
                var rating = data.rating;
                $('#accommodation_rating').val(rating);
				
              });
 
});
 $(function () {
 
  $("#sightseeing").rateYo({
     ratedFill: "#e2bb3d"
  }).on("rateyo.change", function (e, data) {
 			
                var rating = data.rating;
                $('#sightseeing_rating').val(rating);
				
              });
 
});
	 
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
	
	
    $( "#bd" ).datepicker({
	
      changeMonth: true,
      numberOfMonths: 3,
	  maxDate : 0,
	  dateFormat: 'dd/mm/yy',
	  onClose: function( selectedDate ) {
       
      }
  });
	
  </script>

<?php require_once('footer.php'); ?>