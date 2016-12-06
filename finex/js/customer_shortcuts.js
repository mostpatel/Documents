// JavaScript Document
jQuery(document).bind("keyup keydown", function(e){
    if(e.altKey && e.keyCode == 80){
	   alert(document.getElementById('payment_anchor'));
		var hrf=document.getElementById('payment_anchor').getAttribute('href');
		
		window.document.location = hrf;
    }
});