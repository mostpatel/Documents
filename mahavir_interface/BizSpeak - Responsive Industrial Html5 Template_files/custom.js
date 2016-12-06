/*
  * @package BizSpeak
  * @subpackage BizSpeak HTML
  * 
  * Template Scripts
  * Created by Tripples
  
   1.    Style Switcher
   2.    Navigation
   3.    Fixed Header
   4.    Main Slideshow (Carousel)
   5.    Counter
   6.    Owl Carousel
   7.    Flex Slider
   8.    Wow Animation
   9.    Contact Map
   10.   Video Background
   11.   Back To Top

  
*/


jQuery(function($) {
  "use strict";


   /* ----------------------------------------------------------- */
   /*  Style Switcher
   /* ----------------------------------------------------------- */

    (function($) { "use strict";
       $(document).ready(function(){
           $('.style-switch-button').click(function(){
           $('.style-switch-wrapper').toggleClass('active');
           });
           $('a.close-styler').click(function(){
           $('.style-switch-wrapper').removeClass('active');
           });
      });
    })(jQuery);




   /* ----------------------------------------------------------- */
   /*  Fixed header
   /* ----------------------------------------------------------- */

     $(window).on('scroll', function(){
       if ( $(window).scrollTop() > 100 ) {
         $('.ts-mainnav').addClass('navbar-fixed');
       } else {
         $('.ts-mainnav').removeClass('navbar-fixed');
       }
     });

   
  /* ----------------------------------------------------------- */
  /*  Main slideshow
  /* ----------------------------------------------------------- */

   $('#main-slide').carousel({
      pause: true,
      interval: 100000,
   });

    /* ----------------------------------------------------------- */
    /*  Counter
    /* ----------------------------------------------------------- */

    $('.counterUp').counterUp({
     delay: 10,
     time: 1000
    });



  /* ----------------------------------------------------------- */
  /*  Owl Carousel
  /* ----------------------------------------------------------- */


   //Clients

   $("#client-carousel").owlCarousel({

      loop:false,
      margin:20,
      nav:true,
      dots:false,
      navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
      responsive:{
           0:{
               items:1
           },
           600:{
               items:3
           },
           1000:{
               items:5
               }
      }

   });

   //News carousel

   $("#news-carousel").owlCarousel({

      loop:true,
      margin:20,
      nav:false,
      pagination: true,
      responsive:{
           0:{
               items:1
           },
           600:{
               items:2
           },
           1000:{
               items:2
           }
      }

   });

   //Team

   $("#team-carousel").owlCarousel({

      loop:true,
      margin:20,
      nav:true,
      dots:false,
      navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
      responsive:{
           0:{
               items:1
           },
           600:{
               items:3
           },
           1000:{
               items:4
               }
      }

   });





   /* ----------------------------------------------------------- */
   /*  Flex slider
   /* ----------------------------------------------------------- */

      //Second item slider
      $(window).load(function() {
        $('.flexSlideshow').flexslider({
           animation: "fade",
           controlNav: false,
           directionNav: true ,
           slideshowSpeed: 8000
        });
      });


   /* ----------------------------------------------------------- */
   /*  Back to top
   /* ----------------------------------------------------------- */

       $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
      // scroll body to 0px on click
      $('#back-to-top').click(function () {
          $('#back-to-top').tooltip('hide');
          $('body,html').animate({
              scrollTop: 0
          }, 800);
          return false;
      });
      
      $('#back-to-top').tooltip('hide');

});