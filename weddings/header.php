<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class=""><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Weddings By Anar Gawarvala</title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="robots" content="INDEX,FOLLOW">
<style>
	div.page{
		margin-left:0;	
	}
</style>
<link href="css/css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="css/styles.css" media="all">

<link rel="stylesheet" type="text/css" href="css/widgets.css" media="all">
<link rel="stylesheet" type="text/css" href="css/media.css" media="all">
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" media="all">
<link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
<script type="text/javascript" src="js/controls.js"></script>
<script type="text/javascript" src="js/slider.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/form.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/translate.js"></script>
<script type="text/javascript" src="js/cookies.js"></script>
<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>


    <script type="text/javascript">//<![CDATA[
        var Translator = new Translate([]);
        //]]></script><link href="css/css(1)" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="css/colorbox.css">

<script src="js/jquery.bxslider.js" type="text/javascript"></script>
<script src="js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="js/jquery.colorbox.js" type="text/javascript"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.ad-gallery.css">
<script type="text/javascript" src="js/jquery.ad-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="css/colorbox.css">
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="css/jquery.bxslider.css">
<script type="text/javascript" src="js/jquery.fancybox.js"></script>
<script type="text/javascript" src="js/imageflow.js"></script>


<script type="text/javascript">




  jQuery(function() {
    var galleries = jQuery('.ad-gallery').adGallery();
    jQuery('#switch-effect').change(
      function() {
        galleries[0].settings.effect = jQuery(this).val();
        return false;
      }
    );
    jQuery('#toggle-slideshow').click(
      function() {
        galleries[0].slideshow.toggle();
        return false;
      }
    );
    jQuery('#toggle-description').click(
      function() {
        if(!galleries[0].settings.description_wrapper) {
          galleries[0].settings.description_wrapper = jQuery('#descriptions');
        } else {
          galleries[0].settings.description_wrapper = false;
        }
        return false;
      }
    );
  });





jQuery(document).ready(function(){



jQuery('.fancybox').fancybox();


jQuery('#istermsSelected').click(function(){
					if(jQuery('#istermsSelected').prop('checked') == true) {						
						jQuery('#continuebtn').show();
					} else {
						jQuery('#continuebtn').hide();
					}
				});


jQuery('.vidimg').click(function(){
jQuery('#wrapdiv').fadeOut(1000);

});



jQuery('ul.color li').addClass('rkcolor');
jQuery('ul.sizeboxes li').addClass('rksize');

jQuery('.rkcolor').click(function(){
jQuery('.rkcolor').removeClass('active');
   jQuery(this).addClass('active');
   
});

jQuery('.rksize').click(function(){
jQuery('.rksize').removeClass('active');
   jQuery(this).addClass('active');
   
});



jQuery('.navigation ul li').hover(function(){
//jQuery(this).children('.rkdrop').removeClass('navdrop');
jQuery(this).children('.rkdrop').addClass('navdrop1');
    },function(){
jQuery(this).children('.rkdrop').removeClass('navdrop1');
//jQuery(this).children('.rkdrop').addClass('navdrop');
    });
	
	jQuery('.rkdrop').mouseenter(function(){
	jQuery(this).addClass('navdrop1');
    }).mouseleave(function(){
	jQuery(this).removeClass('navdrop1');
});


	jQuery('.rk').click(function(){
		jQuery('.rk').removeClass('active');
		jQuery(this).addClass('active');
		var id = jQuery(this).attr('id');
		jQuery('.store-div-right').hide();
		
		jQuery('#addressdiv'+id).show();
		
		/*for(var x=1;x<=20;x++){
				if(id==x)jQuery('#addressdiv'+id).show();
				else jQuery('#addressdiv'+x).hide();
		}*/
	});
	jQuery('#').click();
	
});



ddaccordion.init({
	headerclass: "ddhead", //Shared CSS class name of headers group
	contentclass: "sub-navigation", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: true, //Should contents open by default be animated into view?
	persiststate: false, //persist state of opened contents within browser session?
	toggleclass: ["closedlanguage", "openlanguage"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "slow", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})










function slideSwitch() {
    var jqactive = jQuery('#slideshow IMG.active');

    if ( jqactive.length == 0 ) jqactive = jQuery('#slideshow IMG:last');

    // use this to pull the images in the order they appear in the markup
    var jqnext =  jqactive.next().length ? jqactive.next()
        : jQuery('#slideshow IMG:first');

    // uncomment the 3 lines below to pull the images in random order
    
    // var jqsibs  = jqactive.siblings();
    // var rndNum = Math.floor(Math.random() * jqsibs.length );
    // var jqnext  = jQuery( jqsibs[ rndNum ] );


    jqactive.addClass('last-active');

    jqnext.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 3000, function() {
            jqactive.removeClass('active last-active');
        });
}

jQuery(function() {
    setInterval( "slideSwitch()", 5000 );
});






 jQuery(document).ready(function(){
    jQuery('#slider1').bxSlider({
	controls: true,
	pager: true,
	auto: true,
	infiniteLoop: true,
 
});
	jQuery('.bxslider').bxSlider({
  adaptiveHeight: true,
  mode: 'horizontal',
  controls: true
});
	
	jQuery(".group1").colorbox({rel:'group1'});	
	
  });
  
  jQuery(window).load(function(){  
    //for each description div...  
    jQuery('div.description').each(function(){  
        //...set the opacity to 0...  
        jQuery(this).css('opacity', 0);  
        //..set width same as the image...  
        jQuery(this).css('width', jQuery(this).siblings('img').width());  
        //...get the parent (the wrapper) and set it's width same as the image width... '  
        jQuery(this).parent().css('width', jQuery(this).siblings('img').width());  
        //...set the display to block  
        jQuery(this).css('display', 'block');  
    });  
  
    jQuery('div.wrapper').hover(function(){  
        //when mouse hover over the wrapper div  
        //get it's children elements with class description '  
        //and show it using fadeTo  
        jQuery(this).children('.description').stop().fadeTo(500, 0.7);  
    },function(){  
        //when mouse out of the wrapper div  
        //use fadeTo to hide the div  
        jQuery(this).children('.description').stop().fadeTo(500, 0);  
    }); 
	
  
});  


function popitup(url) {
	newwindow=window.open(url,'name','height=600,width=740,scrollbars=1,top=0,left=0,resizable=0');
	if (window.focus) {newwindow.focus()}
	return false;
}
//Responsive Menu
jQuery(document).ready(function() {
    jQuery('a.top-nav-icon').click(function(){
		var margin_right = jQuery('.page').css('margin-left');
			margin_right = parseInt(margin_right);
			if(margin_right > 0){
				jQuery('.page').animate({'margin-left' : 0})
				jQuery('.nav-container').removeClass('navshowmob');
				jQuery('.wrapper').removeClass('overflow');
			}else{
				jQuery('.page').animate({'margin-left' : 200});
				setTimeout(function(){
					jQuery('.nav-container').addClass('navshowmob');
				}, 500);
				jQuery('.wrapper').addClass('overflow');
			}
		});
		
		//Responsive Filter
		jQuery('a.top-filter').click(function(){
			var margin_right = jQuery('.page').css('margin-left');
				margin_right = parseInt(margin_right);
				if(margin_right < 0){
					jQuery('.page').animate({'margin-left' : 0})
					jQuery('.sidebar ').removeClass('nav-fliter');
					jQuery('.wrapper').removeClass('overflow');
				}else{
					jQuery('.page').animate({'margin-left' : -200});
					setTimeout(function(){
						jQuery('.sidebar ').addClass('nav-fliter');
					}, 500);
					jQuery('.wrapper').addClass('overflow');
				}
		});
});


</script><style type="text/css">
.sub-navigation{display: none}
a.hiddenajaxlink{display: none}
</style>

<script type="text/javascript">





   /*jQuery(document).ready(function(){
				

   jQuery(".slidesubs").animate({bottom : 20},700, 'swing');

   jQuery(".closeslidesubs").click(function(){									
   jQuery(".slidesubs").animate({bottom : -400},700, 'swing');
});
}); */

 
</script>

<style type="text/css">.fancybox-margin{margin-right:0px;}</style><style type="text/css">.fb_hidden{position:absolute;top:-10000px;z-index:10001}.fb_reposition{overflow:hidden;position:relative}.fb_invisible{display:none}.fb_reset{background:none;border:0;border-spacing:0;color:#000;cursor:auto;direction:ltr;font-family:"lucida grande", tahoma, verdana, arial, sans-serif;font-size:11px;font-style:normal;font-variant:normal;font-weight:normal;letter-spacing:normal;line-height:1;margin:0;overflow:visible;padding:0;text-align:left;text-decoration:none;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;white-space:normal;word-spacing:normal}.fb_reset>div{overflow:hidden}.fb_link img{border:none}@keyframes fb_transform{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}.fb_animate{animation:fb_transform .3s forwards}
.fb_dialog{background:rgba(82, 82, 82, .7);position:absolute;top:-10000px;z-index:10001}.fb_reset .fb_dialog_legacy{overflow:visible}.fb_dialog_advanced{padding:10px;-moz-border-radius:8px;-webkit-border-radius:8px;border-radius:8px}.fb_dialog_content{background:#fff;color:#333}.fb_dialog_close_icon{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 0 transparent;_background-image:url(http://static.xx.fbcdn.net/rsrc.php/v2/yL/r/s816eWC-2sl.gif);cursor:pointer;display:block;height:15px;position:absolute;right:18px;top:17px;width:15px}.fb_dialog_mobile .fb_dialog_close_icon{top:5px;left:5px;right:auto}.fb_dialog_padding{background-color:transparent;position:absolute;width:1px;z-index:-1}.fb_dialog_close_icon:hover{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -15px transparent;_background-image:url(http://static.xx.fbcdn.net/rsrc.php/v2/yL/r/s816eWC-2sl.gif)}.fb_dialog_close_icon:active{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -30px transparent;_background-image:url(http://static.xx.fbcdn.net/rsrc.php/v2/yL/r/s816eWC-2sl.gif)}.fb_dialog_loader{background-color:#f6f7f8;border:1px solid #606060;font-size:24px;padding:20px}.fb_dialog_top_left,.fb_dialog_top_right,.fb_dialog_bottom_left,.fb_dialog_bottom_right{height:10px;width:10px;overflow:hidden;position:absolute}.fb_dialog_top_left{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/ye/r/8YeTNIlTZjm.png) no-repeat 0 0;left:-10px;top:-10px}.fb_dialog_top_right{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/ye/r/8YeTNIlTZjm.png) no-repeat 0 -10px;right:-10px;top:-10px}.fb_dialog_bottom_left{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/ye/r/8YeTNIlTZjm.png) no-repeat 0 -20px;bottom:-10px;left:-10px}.fb_dialog_bottom_right{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/ye/r/8YeTNIlTZjm.png) no-repeat 0 -30px;right:-10px;bottom:-10px}.fb_dialog_vert_left,.fb_dialog_vert_right,.fb_dialog_horiz_top,.fb_dialog_horiz_bottom{position:absolute;background:#525252;filter:alpha(opacity=70);opacity:.7}.fb_dialog_vert_left,.fb_dialog_vert_right{width:10px;height:100%}.fb_dialog_vert_left{margin-left:-10px}.fb_dialog_vert_right{right:0;margin-right:-10px}.fb_dialog_horiz_top,.fb_dialog_horiz_bottom{width:100%;height:10px}.fb_dialog_horiz_top{margin-top:-10px}.fb_dialog_horiz_bottom{bottom:0;margin-bottom:-10px}.fb_dialog_iframe{line-height:0}.fb_dialog_content .dialog_title{background:#6d84b4;border:1px solid #3a5795;color:#fff;font-size:14px;font-weight:bold;margin:0}.fb_dialog_content .dialog_title>span{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/yd/r/Cou7n-nqK52.gif) no-repeat 5px 50%;float:left;padding:5px 0 7px 26px}body.fb_hidden{-webkit-transform:none;height:100%;margin:0;overflow:visible;position:absolute;top:-10000px;left:0;width:100%}.fb_dialog.fb_dialog_mobile.loading{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/ya/r/3rhSv5V8j3o.gif) white no-repeat 50% 50%;min-height:100%;min-width:100%;overflow:hidden;position:absolute;top:0;z-index:10001}.fb_dialog.fb_dialog_mobile.loading.centered{width:auto;height:auto;min-height:initial;min-width:initial;background:none}.fb_dialog.fb_dialog_mobile.loading.centered #fb_dialog_loader_spinner{width:100%}.fb_dialog.fb_dialog_mobile.loading.centered .fb_dialog_content{background:none}.loading.centered #fb_dialog_loader_close{color:#fff;display:block;padding-top:20px;clear:both;font-size:18px}#fb-root #fb_dialog_ipad_overlay{background:rgba(0, 0, 0, .45);position:absolute;bottom:0;left:0;right:0;top:0;width:100%;min-height:100%;z-index:10000}#fb-root #fb_dialog_ipad_overlay.hidden{display:none}.fb_dialog.fb_dialog_mobile.loading iframe{visibility:hidden}.fb_dialog_content .dialog_header{-webkit-box-shadow:white 0 1px 1px -1px inset;background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#738ABA), to(#2C4987));border-bottom:1px solid;border-color:#1d4088;color:#fff;font:14px Helvetica, sans-serif;font-weight:bold;text-overflow:ellipsis;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0;vertical-align:middle;white-space:nowrap}.fb_dialog_content .dialog_header table{-webkit-font-smoothing:subpixel-antialiased;height:43px;width:100%}.fb_dialog_content .dialog_header td.header_left{font-size:12px;padding-left:5px;vertical-align:middle;width:60px}.fb_dialog_content .dialog_header td.header_right{font-size:12px;padding-right:5px;vertical-align:middle;width:60px}.fb_dialog_content .touchable_button{background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#4966A6), color-stop(.5, #355492), to(#2A4887));border:1px solid #2f477a;-webkit-background-clip:padding-box;-webkit-border-radius:3px;-webkit-box-shadow:rgba(0, 0, 0, .117188) 0 1px 1px inset, rgba(255, 255, 255, .167969) 0 1px 0;display:inline-block;margin-top:3px;max-width:85px;line-height:18px;padding:4px 12px;position:relative}.fb_dialog_content .dialog_header .touchable_button input{border:none;background:none;color:#fff;font:12px Helvetica, sans-serif;font-weight:bold;margin:2px -12px;padding:2px 6px 3px 6px;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}.fb_dialog_content .dialog_header .header_center{color:#fff;font-size:16px;font-weight:bold;line-height:18px;text-align:center;vertical-align:middle}.fb_dialog_content .dialog_content{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/y9/r/jKEcVPZFk-2.gif) no-repeat 50% 50%;border:1px solid #555;border-bottom:0;border-top:0;height:150px}.fb_dialog_content .dialog_footer{background:#f6f7f8;border:1px solid #555;border-top-color:#ccc;height:40px}#fb_dialog_loader_close{float:left}.fb_dialog.fb_dialog_mobile .fb_dialog_close_button{text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}.fb_dialog.fb_dialog_mobile .fb_dialog_close_icon{visibility:hidden}#fb_dialog_loader_spinner{animation:rotateSpinner 1.2s linear infinite;background-color:transparent;background-image:url(http://static.xx.fbcdn.net/rsrc.php/v2/yD/r/t-wz8gw1xG1.png);background-repeat:no-repeat;background-position:50% 50%;height:24px;width:24px}@keyframes rotateSpinner{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
.fb_iframe_widget{display:inline-block;position:relative}.fb_iframe_widget span{display:inline-block;position:relative;text-align:justify}.fb_iframe_widget iframe{position:absolute}.fb_iframe_widget_fluid_desktop,.fb_iframe_widget_fluid_desktop span,.fb_iframe_widget_fluid_desktop iframe{max-width:100%}.fb_iframe_widget_fluid_desktop iframe{min-width:220px;position:relative}.fb_iframe_widget_lift{z-index:1}.fb_hide_iframes iframe{position:relative;left:-10000px}.fb_iframe_widget_loader{position:relative;display:inline-block}.fb_iframe_widget_fluid{display:inline}.fb_iframe_widget_fluid span{width:100%}.fb_iframe_widget_loader iframe{min-height:32px;z-index:2;zoom:1}.fb_iframe_widget_loader .FB_Loader{background:url(http://static.xx.fbcdn.net/rsrc.php/v2/y9/r/jKEcVPZFk-2.gif) no-repeat;height:32px;width:32px;margin-left:-16px;position:absolute;left:50%;z-index:4}</style><script src="js/jsonp"></script></head>
<body class=" cms-page-view cms-home">

<div class="wrapper">
       
    <div class="page">
        	

 

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
<style>
	.rkloader{
		border:0;
		background:#666 url(http://www.ritukumar.com//media/loader.gif) no-repeat center center;
		color:#666;
		min-width:100px;
		min-height:100px;
		height:auto;
		outline:none;
		width:100%;
	}
	
</style>
<style>
	#nav > li.last a {
    color: red !important;
}
</style>
<div class="header-container">

<div class="top-links-div">
<div class="main-div-top ">
<div class="left-link"> 
<div class="w-msg">
<p> </p>   </div>

<div class="cs-link">
<ul class="links">
                   <li class="first"><a href="#" title="Log In">Log In</a></li>
                           <li><a href="#" title="Register">Register</a></li>
                               
            </ul>

</div>
</div>
<!--<a href="https://www.surveymonkey.com/s/TLYJSPW" target="_blank" class="take-survey">Take a Survey</a>-->
  
<div class="right-link">
<ul>


<li><div class="slink">
<a class="fblinkimage" target="_blank" href="#" title="Facebook Fan Page"><img src="images/fb-link.png"></a>
<a class="twlinkimage" target="_blank" href="#" title="Twitter Fan Page"><img src="images/tw.png"></a>

</div></li>
</ul>
 </div>
 

 
</div>
</div>

	<div class="header">
		<div class="head_part">
       
							<span class="logo" style=""><a href="" title="" class="logo"><img src="images/fullLogo.png" height="140px;"alt=""></a></span>
			 
			
			         
            
                        
			     
		</div>
		
		

		
	  

</div>
<div class="main-div-top">
<div class="container">
<div class="nav-container">
    <ul id="nav">
        <li title="Home" class="level0 nav-1 level-top first">
<a href="index.php" class="level-top">
<span>Home</span>
</a>
</li>
<li title="About Us" class="level0 nav-2 level-top parent">
<a href="about.php" class="level-top">
<span>About Us</span>
</a>
</li><li title="Services" class="level0 nav-3 level-top parent">
<a href="services.php" class="level-top">
<span>Services</span>
</a>
</li>
<li title="Procedure" class="level0 nav-4 level-top parent">
<a href="procedure.php" class="level-top">
<span>Procedure</span>
</a>
</li><li title="Rules" class="level0 nav-5 level-top parent">
<a href="#" class="level-top">
<span>Rules And Regulations</span>
</a>
</li>
</li><li title="FAQs" class="level0 nav-5 level-top parent">
<a href="#" class="level-top">
<span>FAQs</span>
</a>
</li>   </ul>
</div>
<a href="javascript:void(0)" class="top-nav-icon"><img src="images/top-menu.png" alt=""></a>
<a href="javascript:void(0)" class="my-account-icon"><img src="images/my-account.png" alt=""></a>
<a href="javascript:void(0)" class="top-filter"><img src="images/filter.png" alt=""></a>


<div class="linking-div">


</div>
</div>

</div>



</div>

<style>
li.level0.nav-9.level-top.last {
    padding-right: 30px;
}
.head_part span {
    float: none;
    display: inline-block;
}
.head_part {
    text-align: center;
    padding-top: 10px;
}
#nav > li.last a {
    color: #dac881 !important;
}
</style>

       