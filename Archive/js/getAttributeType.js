// JavaScript Document

function loadAttrType(selectBox)
{
	var return_string = "";
	$('#bs3Select :selected').each(function(i, selectedElement) {
   return_string = return_string + "sup"+$(selectedElement).val() + ",";
});
$('#bs4Select :selected').each(function(i, selectedElement) {
   return_string = return_string + "cat" +$(selectedElement).val() + ",";
});
$('#bs5Select :selected').each(function(i, selectedElement) {
   return_string = return_string +  "sub" +$(selectedElement).val() + ",";
});


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
	
// Before adding new we must remove previously loaded elements

removeData("attribute_type_id");

var plsOptn = document.createElement("OPTION");

plsOptn.value = -1;
plsOptn.text = "--Please Select--";

document.getElementById("attribute_type_id").options.add(plsOptn);

for (var i=0; i<myarray.length; i++)
{
	
var optn = document.createElement("OPTION");

optn.value = myarray[i];
optn.text = myarray[++i];

document.getElementById("attribute_type_id").options.add(optn);

} 
    }
  }
  
  xmlhttp1.open('GET', "getRelatedAttributes.php?id="+return_string, true );    
  xmlhttp1.send(null);
	

	}

function removeData(id){
	
	for(j=document.getElementById(id).options.length-1;j>=0;j--)

	{
	
	document.getElementById(id).remove(j);
	}
	
	}