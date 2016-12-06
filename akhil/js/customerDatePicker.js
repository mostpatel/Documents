// JavaScript Document
 $( ".datepicker1" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy'
    });

$( ".datepicker2" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: 'dd/mm/yy'
    });
	
$( ".datepicker3" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat: 'dd/mm/yy'
    });		
	
$( ".datepicker4" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat: 'dd/mm/yy'
    });		
	
var today = new Date();
var y = today.getFullYear();
$('#multiyear').multiDatesPicker({
	
	numberOfMonths: [3,4],
	defaultDate: '1/1/'+y
});		