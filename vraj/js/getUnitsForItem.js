// JavaScript Document
function getUnitsFromItemId(item_id,item_option_el)
{		
var unit_el = $(item_option_el).parent().next().next().children()[2];

	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    var myarray=eval(xmlhttp1.responseText);
	
	removeDataEl(unit_el);
	
	var plsOptn = document.createElement("OPTION");
	
	plsOptn.value = -1;
	plsOptn.text = "--Please Select--";
	
	//unit_el.options.add(plsOptn);

	for (var i=0; i<myarray.length; i++)
	{
		
	var optn = document.createElement("OPTION");
	
	optn.value = myarray[i];
	optn.text = myarray[++i];
	unit_el.options.add(optn);
	
	}

	}
  }
var url = document.web_root+"json/getUnitsForItem.php?id="+item_id;

 xmlhttp1.open('GET',url, true );    
  xmlhttp1.send(null);
	
}

function getGodownForItemId(item_id,item_option_el)
{
	var unit_el = $(item_option_el).parent().next().children()[0];
var qty_el = $(item_option_el).parent().next().next().children()[0];
	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
	qty_el.focus();	
    var godown_id=xmlhttp1.responseText;
	selectItemByValue(unit_el,godown_id);
	$('.selectpicker').selectpicker('render');
	}
  }
var url = document.web_root+"json/getGodownForItem.php?id="+item_id;

 xmlhttp1.open('GET',url, true );    
  xmlhttp1.send(null);
	
}

function selectItemByValue(elmnt, value){

  for(var i=0; i < elmnt.options.length; i++)
  {
    if(elmnt.options[i].value === value) {
      elmnt.selectedIndex = i;
      break;
    }
  }
}


function removeDataEl(el){
	
	for(j=el.options.length-1;j>=0;j--)
	{	
	el.remove(j);
	}
	
}

function getUnitsFromItemIdPurchase(item_id,item_option_el)
{	

var unit_el = $(item_option_el).parent().next().children()[2];

	var xmlhttp1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
    var myarray=eval(xmlhttp1.responseText);
	
	removeDataEl(unit_el);
	
	var plsOptn = document.createElement("OPTION");
	
	plsOptn.value = -1;
	plsOptn.text = "--Please Select--";
	
	//unit_el.options.add(plsOptn);

	for (var i=0; i<myarray.length; i++)
	{
		
	var optn = document.createElement("OPTION");
	
	optn.value = myarray[i];
	optn.text = myarray[++i];
	unit_el.options.add(optn);
	
	}

	}
  }
var url = document.web_root+"json/getUnitsForItem.php?id="+item_id;

 xmlhttp1.open('GET',url, true );    
  xmlhttp1.send(null);
	
}

function removeDataEl(el){
	
	for(j=el.options.length-1;j>=0;j--)
	{	
	el.remove(j);
	}
	
}

function getIndex(input){
	var index = -1, i = 0, found = false;
	while (i < input.form.length && index == -1)
		if (input.form[i] == input)index = i;
		else i++;	
		return index;
}  
 
function SendTab(objForm, strField, evtKeyPress){  

	var aKey = evtKeyPress.keyCode ?
	evtKeyPress.keyCode :evtKeyPress.which ?
	evtKeyPress.which : evtKeyPress.charCode;
 	
	if (aKey == 13){
		event.preventDefault();
		/* objForm[(getIndex(strField)+1) % objForm.length].focus(); */
		
	}
}