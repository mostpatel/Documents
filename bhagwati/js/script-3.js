jQuery(function($){


	// find all external links
	// @url http://jquery-howto.blogspot.com/2009/06/find-select-all-external-links-with.html
	$.expr[':'].external=function(a){return (a.hostname!=document.location.hostname);};

	// Add 'external' CSS class to all external links
	$('a:external').addClass('external');
	
	$('a.email').removeClass('external'); // email fix

	// open external links in new window
	// $('a.external').click( function() {
	// 	window.open( $(this).attr('href') );
	// 	return false;
	// });

	// google analytics = track external links
	$('a:external').click(function() {
		var category = 'links';
		var action = 'external';
		var label = $(this);
		_gaq.push(['_trackEvent', category, action, label.attr('href')]);
	});


	$('input, textarea').placeholder();



	// GENERAL
	// ------------------------------------------------------------

	// Mobile Nav
	$(document).on("click", ".mobile-nav", function() {

		$('#nav').slideToggle();
		$('.util').slideToggle();

		$('.mobile-nav').toggleClass("open");
		if ($('.mobile-nav').hasClass('open')) {
			$(this).html("Hide Menu <span class='icon-up'></span>");
		} else {
			$(this).html("Show Menu <span class='icon-down'></span>");
		}

	return false;
	});


	// Set same column height
	function initSameHeight(){
		$('.boxes').sameHeight({
			elements: '.box',
			flexible: true,
			multiLine: true
		});


		$('.sameheight').sameHeight({
			elements: '.col',
			flexible: true,
			multiLine: true
		});

		$('.other-regions').sameHeight({
			elements: 'li',
			flexible: true,
			multiLine: true
		});

		$('.interests').sameHeight({
			elements: 'li',
			flexible: true,
			multiLine: true
		});

		$('.interests2').sameHeight({
			elements: 'li',
			flexible: true,
			multiLine: true
		});

	}
	initSameHeight();


	// MORE
	$(document).on("click", ".more-link", function() {

		$(this).toggleClass("open");
		if ($(this).hasClass('open')) {
			$(this).next('.more-after').hide().slideDown();
			$(this).html('Read Less <span class="icon-up"></span>');
		} else {
			$(this).next('.more-after').show().slideUp();
			$(this).html('Read More <span class="icon-down"></span>');
		}

		return false;

	});


	// FAQ
	$(document).on("click", ".faq .box", function() {

		$(this).toggleClass("open");
		if ($(this).hasClass('open')) {
			$(this).next('.answer').hide().slideDown();
			$(this).find('h2').removeClass('icon-right').addClass('icon-down');
		} else {
			$(this).next('.answer').show().slideUp();
			$(this).find('h2').removeClass('icon-down').addClass('icon-right');
		}

		return false;

	});


	// BACK TO TOP
	$('a[href=#top]').click(function(){
		$('html, body').animate({scrollTop:0}, 'slow');
		return false;
	});




	// DESTINATIONS
	// ------------------------------------------------------------

	$(".co-list .group").each(function() {
		var group = $(this);

		// Create the dropdown base
		$("<select />").appendTo(group);

		// Create default option "Go to..."
		$("<option />", {
			"selected": "selected",
			"value"   : "",
			"text"    : "Select A Country..."
		}).appendTo(group.find("select"));

		// Populate dropdown with menu items
		$(this).find("a").each(function() {
			var el = $(this);
			$("<option />", {
			"value"   : el.attr("href"),
			"text"    : el.text()
			}).appendTo(group.find("select"));
		});

	});

	$(".co-list .group select").change(function() {
		window.location = $(this).find("option:selected").val();
	});




	// PROPERTIES
	// ------------------------------------------------------------


	$(document).on('mouseenter','.co-name a', function (event) {
			var entryId = $(this).attr('class');
			entryId = '.'+entryId;
			$(entryId).addClass('active');
	}).on('mouseleave','.co-name a',  function(){
			$(this).removeClass('active');
			$('.co-img').find('.active').removeClass('active');
	});

	$(document).on('mouseenter','.co-img a', function (event) {
			var entryId = $(this).attr('class');
			entryId = '.'+entryId;
			$(entryId).addClass('active');
	}).on('mouseleave','.co-img a',  function(){
			$(this).removeClass('active');
			$('.co-name').find('.active').removeClass('active');
	});




	// EMAIL SIGNUP
	$("#em-signup").validate({
		rules: {
			"contact.firstname": "required",
			"contact.lastname": "required",
			"contact.emailaddress1": {
				required: true,
				email: true
			}
		},
		messages: {
			"contact.emailaddress1": "Please enter a valid email address, example: you@yourdomain.com"
		},
		errorPlacement: function(error, element) { },
		submitHandler: function(form) {
			var fn = $('#fn').val();
			var ln = $('#ln').val();
			var em = $('#em').val();
			var ph = $('#ph').val();

			
			
			$("body").append('<div id="send-wrapper"><div class="inner"><p>Please wait while we process your request...</p><p><img src="/ajax-loader.gif"></p></div></div>');
			$("#send-wrapper").fadeIn('fast', function() {
				form.submit();
			});
		}
	});



	// CONTACT PAGE
	$("#contact-form").validate({
		rules: {
			"contact.firstname": "required",
			"contact.lastname": "required",
			"contact.booking_date": "required",
			"contact.destination": "required",
			"contact.tour_date": "required",
			"contact.emailaddress1": {
				email: true
			},
			"contact.mobile":{
			  required: true,
			  digits: true,
			  minlength: 10,
			  maxlength: 10
			},
			"contact.return_date":{
			  required: { 
						  depends: function(element) 
									{
										if($("#return_type").val()==2) return true; else return false;
									}
						}
						
			},
			"contact.to_destination":{
			  required: { 
						  depends: function(element) 
									{
										if($("#return_type").val()==2) return true; else return false;
									}
						}
			}
  
		},
		messages: {
			"contact.emailaddress1": "Please enter a valid email address, example: you@yourdomain.com",
			"contact.firstname": "Please Enter Your Name!",
			"contact.mobile": "Please Enter Your Mobile Number (10 Digits)!",
			"contact.booking_date": "Please Enter Booking Date (dd/mm/yyyy)!",
			"contact.tour_date": "Please Enter Tour Date (dd/mm/yyyy)!",
			"contact.destination": "Please Enter Destination!",
			"contact.return_date": "Please Enter Return Date (dd/mm/yyyy)!",
			"contact.to_destination": "Please Enter Destination!"
		},
		submitHandler: function(form) {
			var fn = $('#fn').val();
			var ln = $('#ln').val();
			var em = $('#em').val();
			var ph = $('#ph').val();

			
			$("body").append('<div id="send-wrapper"><div class="inner"><p>Please wait while we process your request...</p></div></div>');
			$("#send-wrapper").fadeIn('fast', function() {
				form.submit();
			});
		}
	});




	// PEAK 15 Google Integration

	//
	// This is a function that parses a string and returns a value. We use it to get
	// data from the __utmz cookie
	//
	function _uGC(l, n, s) {
		if (!l || l == "" || !n || n == "" || !s || s == "") return "-";
		var i, i2, i3, c = "-";
		i = l.indexOf(n);
		i3 = n.indexOf("=") + 1;
		if (i > -1) {
		i2 = l.indexOf(s, i); if (i2 < 0) { i2 = l.length; }
		c = l.substring((i + i3), i2);
		}
		return c;
	}
	//
	// Get the __utmz cookie value. This is the cookie that
	// stores all campaign information.
	//
	var z = _uGC(document.cookie, '__utmz=', ';');
	//
	// The cookie has a number of name-value pairs.
	// Each identifies an aspect of the campaign.
	//
	// utmcsr = campaign source
	// utmcmd = campaign medium
	// utmctr = campaign term (keyword)
	// utmcct = campaign content
	// utmccn = campaign name
	// utmgclid = unique identifier used when AdWords auto tagging is enabled
	//
	// This is very basic code. It separates the campaign-tracking cookie
	// and populates a variable with each piece of campaign info.
	//
	var source = _uGC(z, 'utmcsr=', '|');
	var medium = _uGC(z, 'utmcmd=', '|');
	var term = _uGC(z, 'utmctr=', '|');
	var content = _uGC(z, 'utmcct=', '|');
	var campaign = _uGC(z, 'utmccn=', '|');
	var gclid = _uGC(z, 'utmgclid=', '|');
	var term = term.replace(/%20/g," ");
	//
	// The gclid is ONLY present when auto tagging has been enabled.
	// All other variables, except the term variable, will be '(not set)'.
	// Because the gclid is only present for Google AdWords we can
	// populate some other variables that would normally
	// be left blank.
	//
	if (gclid != "-") {
		source = 'google';
		medium = 'cpc';
	}
	var csegment = _uGC(document.cookie, '__utmv=', ';');
	if (csegment != '-') {
		var csegmentex = /[1-9]*?\.(.*)/;
		csegment = csegment.match(csegmentex);
		csegment = csegment[1];
	} else {
		csegment = '(not set)';
	}
	//
	// One more bonus piece of information.
	// We're going to extract the number of visits that the visitor
	// has generated. It's also stored in a cookie, the __utma cookis
	//
	var a = _uGC(document.cookie, '__utma=', ';');
	var aParts = a.split(".");
	var nVisits = aParts[5];
	function populateHiddenFields(f) {
		//
		//
		f.source.value = source;
		//f.medium.value = medium;
		f.term.value = term;
		//f.content.value = content;
		//f.campaign.value = campaign;
		//f.segment.value = csegment;
		//f.numVisits.value = nVisits;
		//
		//The alerts below are handy for testing to ensure that the
		//campaign informaiton is getting correctly passed.
		//Once you've tested, just comment them out
		//
		//alert('source=' + f.source.value);
		//alert('medium=' + f.medium.value);
		//alert('term=' + f.term.value);
		//alert('content=' + f.content.value);
		//alert('campaign=' + f.campaign.value);
		//alert('custom segment=' + f.segment.value);
		//alert('number of visits=' + f.numVisits.value);
		
		return false;
	}





	doReasons();

		   
});









/*function doHero(imgW,imgH) {

	var w = $(window).width();
	var cutoff = 1500;
	var cutoff2 = 1000;
	var cutoff3 = 620;
	var ratio = imgW / imgH;

	$('#hero').cycle('stop');

	if (w >= cutoff) {

		var adjustLeft = 0;

		$('#hero').cycle({ 
			fx:     'fade', 
			speed:  1000, 
			timeout: 5000, 
			containerResize: 0,
			slideResize: 1,
			width: '100%',
			fit: 1,
		});
	} else if (w >= cutoff2 ) {

		var adjustLeft = ( (cutoff - w) / 2 ) * -1;

		$('#hero').cycle({ 
			fx:     'fade', 
			speed:  1000, 
			timeout: 5000, 
			containerResize: 0,
			slideResize: 1,
			width: cutoff,
			fit: 1,
		});

	} else if (w >= cutoff3 ) {

		var adjustLeft = ( (cutoff2 - w) / 2 ) * -1;

		$('#hero').cycle({ 
			fx:     'fade', 
			speed:  1000, 
			timeout: 5000, 
			containerResize: 0,
			slideResize: 1,
			width: cutoff2,
			fit: 1,
		});

	} else {

		var adjustLeft = ( (cutoff3 - w) / 2 ) * -1;

		$('#hero').cycle({ 
			fx:     'fade', 
			speed:  1000, 
			timeout: 5000, 
			containerResize: 0,
			slideResize: 1,
			width: cutoff3,
			fit: 1,
		});
	}

	doResize('#hero', ratio);
	$("#hero").css("marginLeft", adjustLeft);

} */

$('#hero').cycle({ 
			fx:     'fade', 
			speed:  1000, 
			timeout: 5000, 
		 pager:  '#scroll_pager',
		 pause:  1 
});

$('#testonomials').cycle({ 
			fx:     'scrollLeft', 
			speed:  1000, 
			timeout: 1000,
			 pause:  1 
});




function doQuote() {

	var w = $(window).width();
	var cutoff = 1024;


	$('.quoteshow').cycle('stop');

		$('.quoteshow').cycle({ 
			fx: 'fade', 
			speed: 1000, 
			timeout: 6000, 
			containerResize: 1,
			slideResize: 1,
			width: '100%',
			height: 'auto',
			fit: 1,
		});

		var maxHeight = -1;
		
		$('.quoteshow .slide').each(function() {
			maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
		});

		$('.quoteshow .slide').each(function() {
			$(this).height(maxHeight);
		});

		$('.quoteshow').height(maxHeight);


}


function doReasons() {

	var w = $(window).width();
	var cutoff = 479;

	$('.reason-slides').cycle('destroy');

	if (w > cutoff) {
		$('.reason-slides').cycle({ 
			fx:     'fade', 
			slideExpr: '.item',
			speed:  400, 
			timeout: 0, 
			containerResize: 1,
			slideResize: 1,
			width: '100%',
			height: 'auto',
			fit: 1,
			pager: '.circ-nav .numbers'
		});
	} else {
		$('.reason-slides').cycle('destroy');
	    $('.reason-slides').each(function(){
	        $(this).css({'position' : 'static', 'height' : 'auto', 'width' : 'auto'});
	        $(this).children('.item').css({'position' : 'static', 'display' : 'block', 'opacity' : 1, 'z-index' : 1, 'width' : 'auto'});
	    });
   	}

}





function doSlides(imgW,imgH) {

	var ratio = imgW / imgH;

	$('.slides').cycle({ 
		fx: 'fade', 
		speed: 1000, 
		timeout: 5000, 
		containerResize: 0,
		slideResize: 1,
		width: '100%',
		fit: 1,
	});

	doResize('.slides', ratio);

}



// Resize element to keep ratio of its parent's width
function doResize(selector, ratio, round) {
	var e = jQuery(selector);
	var width = e.width();
	var height = e.height();
	if ( ratio == null ) {
		ratio = width / height;
	}
	var newHeight = width / ratio;
	if (round == 1) {
		newHeight = Math.ceil(newHeight);
	}
	e.stop().animate({ height: newHeight }, 0);
}



$(window).resize(function() {

	doReasons();
});






$.fn.photoSlider = function() {


	var _self = this,
		imageHolder = $('.photo-slider .image'),
		textHolder = $('.photo-slider-text'),
		thumbHolder = $('.photo-slider-thumbs'),
		thumbs = $('.photo-slider-thumbs a'),
		infos = $('.infos'),
		controls = $('.controls'),
		next = $('.next'),
		prev = $('.prev'),
		currIndex = 0,
		duration = 300,
		ratio = 470/706,
		padding = 14,
		numSlides = thumbs.length,
		windowWidth = $(window).width(),
		cutoff = 700;


	var init = function() {

		imageHolder.css({'min-height':imageHolder.width()*ratio+padding});

		if (windowWidth > cutoff) {
			controls.css({'position':'absolute', 'bottom': '10px'});
			infos.css({'min-height':imageHolder.width()*ratio+padding});
		} else {
			controls.css({'position':'absolute', 'top': '10px', 'right': '0'});
		}

		thumbs.each(function () {
			fullImage = $(this).attr('href');
			imageHolder.append('<img src="'+fullImage+'">');
		});

		thumbs.on('click', onLiClick);
		next.on('click', onNextClick);
		prev.on('click', onPrevClick);

		highLight(0);

	}

	var onLiClick = function(e) {
		e.preventDefault();

		if($(this).hasClass('active')) { //if the current item is clicked

		} else {
			var num = thumbs.index(this);
			highLight(num, true);
			currIndex = num;
		}
	}

	var onNextClick = function() {
		if (currIndex!=numSlides-1) {
			currIndex++;
			highLight(currIndex);
		}
	}

	var onPrevClick = function() {
		if (currIndex!=0) {
			currIndex--;
			highLight(currIndex);
		}
	}

	var highLight = function(num, clicked) {
		var slides = $('.photo-slider .image > img');
		var theSlide = $(slides[num]);
		var theThumb = $(thumbs[num]);
		var text = theThumb.find('img').attr('alt');

		thumbs.removeClass('active');
		theThumb.addClass('active');

		if (num==0) {
			prev.addClass('disabled');
			if(next.hasClass('disabled')) {
				next.removeClass('disabled');
			}
		} else if (num==numSlides-1) {
			next.addClass('disabled');
			if(prev.hasClass('disabled')) {
				prev.removeClass('disabled');
			}
		} else {
			if(prev.hasClass('disabled')) {
				prev.removeClass('disabled');
			}
			if(next.hasClass('disabled')) {
				next.removeClass('disabled');
			}
		}

		theSlide.css({'z-index':1}).fadeIn(duration * 1.5).siblings().css({'z-index':0}).fadeOut(duration);
		textHolder.html(text);
	}

	function _resize() {

		var newWidth = $(window).width();
		
		imageHolder.css({'min-height':imageHolder.width()*ratio+padding});

		if (newWidth > cutoff) {
			controls.css({'position':'absolute', 'bottom': '10px', 'right': 'auto', 'top': 'auto'});
			infos.css({'min-height':imageHolder.width()*ratio+padding});
		} else {
			controls.css({'position':'absolute', 'top': '10px', 'right': '0', 'bottom': 'auto'});
			infos.css({'min-height':0});
		}

	}


	$(window).resize(function() {
		 _resize();
	});

	//intialize!
	init();

	return this.each(function(){});

}



$(document).ready(function() {

	if($('.photo-slider').length > 0){ $('.photo-slider').photoSlider(); }


	// POP UP
	// CoverPop.start({
	// 	expires: 1,
	// 	cookieName: 'msg1'
	// });

});

$('button.close').click(function(e) {
    $(this).parent().hide();
});
