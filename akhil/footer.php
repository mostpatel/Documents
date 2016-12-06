<section id="footer">
  <div class="container">
    <div class="row center">
      <div class="col-sm-12  whybook_content clr">
        
        <div class="row clr whybookpoints">
          <div class="col-sm-4 col-sm-12">
            <div class="subtitle">Best Price and Value</div>
            <p>Getting the best price is great, getting the best value is even better. We have have been delivering the promise of providing the support and service you expect since last 65 years.</p>
          </div>
          <div class="col-sm-4 col-sm-12">
            <div class="subtitle">Have a Group? We can Help.</div>
            <p>We handle all sorts of group requests and can help you customize the perfect package whatever your group size or needs.</p>
          </div>
          <div class="col-sm-4 col-sm-12">
            <div class="subtitle">Top Notch Customer Service</div>
            <p>Our team is standing by to assist from the time you enquire about a tour until the last day. Our goal is to ensure everyone has a great holiday experience.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 morereasons clr">
          <a href="#" title=""><img src="images/akhil_bharat_tours_and_travels_logo_med.png" alt=""> <br> <br>You can count on us!</a></div>
        </div>
      </div>
    </div>
  </div>
  <div class="bbottom">
    <div class="container">
      <div class="row content">
        <ul class="col-sm-9 footerlink clearfix">
          
          <li> <a title="Contact Us" href="/contactus.html">Contact Us</a></li>
          <li> <a title="Sitemap" href="/content/sitemap.html">Sitemap</a></li>
          <li> <a title="Privacy Policy" href="/content/privacy-policy.html">Privacy Policy</a></li>
          <li> <a title="Terms &amp; Conditions" href="/content/terms-and-condition.html">Terms &amp; Conditions </a></li>
        </ul>
        <div class="col-sm-3 followus text-right">Follow us: &nbsp;&nbsp; <a href="#" rel="publisher" title="Follow us on Google Plus" target="_blank"><span class="sharesprite gplus_16"></span></a>&nbsp;&nbsp;&nbsp;<a href="#" title="Follow us on Facebook" target="_blank" rel="nofollow"><span class="sharesprite facebook_16"></span></a>&nbsp;&nbsp;&nbsp;<a href="#" title="Follow us on Twitter" target="_blank" rel="nofollow"><span class="sharesprite tweet_16"></span></a>&nbsp;&nbsp;&nbsp;<a rel="nofollow" target="_blank" title="Follow us on Pinterest" href="#"><span class="sharesprite pinterest_16"></span></a> </div>
        <div class="col-sm-12"><hr style="background-color:#fff;" class="clearfix clr"></div>
      </div>
    </div>
    <div class="container">
      <div class="row content">
        <div class="col-sm-12 copyright">© Akhil Bharat 2016, All Rights Reserved.</div>
      </div>
    </div>
  </div>
</section>
<div id="fb-root"></div>
<script type="text/javascript" charset="utf-8" src="js/jquery-2.1.3.js"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery-common.js"></script>
<script type="text/javascript" charset="utf-8" src="js/common_v3.js"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="js/jquery.magnific-popup.min.js" ></script>
<link rel="stylesheet" href="css/magnific-popup.css" type="text/css" >
<script type="text/javascript" charset="utf-8">
// <![CDATA[
$(document).ready(function() {
;$("body").scrollspy({target: ".navbar-fixed-top"});;$(".navbar-collapse ul li a").click(function() {$(".navbar-toggle:visible").click();});;var userjsaction={
	actionurl: '',action: '',ancestor: '',	
	uniqID:function(idlength) {
		var charstoformid = '_0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
		if(! idlength){idlength = Math.floor(Math.random() * charstoformid.length);}
		var uniqid = '';
		for (var i = 0; i < length; i++) {
			uniqid += charstoformid[Math.floor(Math.random() * charstoformid.length)];
		}
		// one last step is to check if this ID is already taken by an element before 
		if($("#"+uniqid).length == 0)
			return uniqid;
		else
			return uniqID(20)
	},initialize:function($ele){
		if($ele){
			if($ele.attr('id') == '') $ele.attr('id',this.uniqID(32));
			this.ancestor = $ele.attr('id');
		}
	},executeajaxaction:function(){
		$.fancybox({scrolling : 'no',fitToView: false,topRatio : 0.1,width: 'auto',height: 'auto',autoSize: false,transitionIn:'elastic',transitionOut:'elastic',speedIn:	600,speedOut:200,padding:0,autoScale: false,arrows : false,helpers : {title : null} ,href: this.actionurl,'onClosed': function(){}});		
	},loadaction:function($ele,e){
		e.preventDefault();e.stopPropagation();
		switch($ele.attr("triggeraction")){
			case 'toggle':	
				actionclass = $ele.attr("actionclass");
				if($("."+actionclass)){
					$("."+actionclass).removeClass('hidden').slideToggle("slow",function(){
						$("."+actionclass).is(':hidden') ? $ele.html($ele.attr('showtext')) :$ele.html($ele.attr('hidetext'));
					});
				}
				break;					
			case 'classclick':	
				actionclass = $ele.attr("actionclass");
				if($("."+actionclass)){
					$("."+actionclass).trigger('click');
					if($ele.attr("oncomplete") =='hidetriggerer') $ele.hide(1000);
				}
				break;	
			case 'fbgallery':
				if($ele.attr('rel') != '' &&  typeof($ele.attr('rel')) != 'undefined' ){
					$('a[rel="'+$ele.attr('rel')+'"]').fancybox({transitionIn:'elastic',transitionOut:'elastic',titlePosition:'over',fitToView:true, type:'image',autoSize:true,autoDimensions:true,autoScale:true});
					$ele.trigger('fancybox');					
				}else if($ele.attr('href') != ''){					
					 this.actionurl = $ele.attr('href');
					 this.executeajaxaction();	
				}
				break;		
		}
		
	}
};
if($(".useractionitem")){$(".useractionitem").click(function(e){userjsaction.loadaction($(this),e);});};
$('.status_box_hide').click(function(e){e.preventDefault();e.stopPropagation();$('.status_box').slideToggle(500);});$('.emaillink').fancybox({'autoDimensions':false,'width':520,'height':800,'transitionIn':'none','transitionOut': 'none','closeClick':true,'type':'iframe'})
var click_events = (function ($) {
    "use strict";
    var click_elements = [];
    var track_event = function (category, action, label) {
        var event_category = !category ? '' : category;
        var event_action = !action ? '' : action;        
        var event_label = !label ? '' : label;
        if(label == '') return false;
        if(typeof ga !== 'undefined'){
        	ga('send','event', category, action, label); 
        }
    };
    var click_event = function (event){
      if(event.data.label == 'inlinelabel') event.data.label =  $(this).attr('event_label'); 	
      track_event(event.data.category, event.data.action, event.data.label);
      if (event.currentTarget.hasOwnProperty('href')) {
        event.preventDefault();
        setTimeout(function(){window.location = event.currentTarget.href;}, 100);
      }
    };
    return {
        bind_events : function (settings) { 
            click_elements = settings.click_elements; 
            var i;
            for (i = 0; i < click_elements.length; i++) {
                var clicked = click_elements[i];
                $(clicked.select).on('click', clicked, click_event);
            }
        }
    };
}(jQuery))				
click_events.bind_events({
    click_elements: [
    	{'select':'.logo a','category':'Header','action':'click','label':'Logo'},
        {'select':'.topnav .social a','category':'Header','action':'click','label':'Social'},
        {'select':'.topmenu a','category':'Header','action':'click','label':'inlinelabel'},
        {'select':'.breadcrumbs a','category':'Header','action':'click','label':'Breadcrumb'},

               
         {'select':'.toppackage a','category':'Home Page','action':'click','label':'Kilimanjaro HomePage'},        
         {'select':'.bestpackage a','category':'Home Page','action':'click','label':'Annapurna HomePage'},        
         {'select':'.secondbestpackage a','category':'Home Page','action':'click','label':'EBC HomePage'},        
		        
        {'select':'#fancybox-close','category':'Pop-up','action':'click','label':'Close Inquire'},	
        
        
    	{'select':'.morereasons a','category':'Footer','action':'click','label':'Why book'},
        {'select':'#footer .followus a','category':'Footer','action':'click','label':'Social'},

    ],
});});
// ]]>
</script>



</body></html>