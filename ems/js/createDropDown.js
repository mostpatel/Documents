// JavaScript Document

 function createDropDown(url,id,removeArray)  
{
	

var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange=function()                        
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	
    var myarray=eval(xmlhttp.responseText);
	
	alert(myarray);
	
// Before adding new we must remove previously loaded elements

removeData(id);
if(removeArray!=null)
{
for(var i=0;i<removeArray.length;i++)
{
	removeData(removeArray[i]);
}
}

var plsOptn = document.createElement("OPTION");

plsOptn.value = -1;
plsOptn.text = "--Please Select--";

document.getElementById(id).options.add(plsOptn);

for (var i=0; i<myarray.length; i++)
{
	
var optn = document.createElement("OPTION");

optn.value = myarray[i];
optn.text = myarray[++i];

document.getElementById(id).options.add(optn);

} 
    }
  }
  
  xmlhttp.open('GET', url, true );    
  xmlhttp.send(null);

}

function removeData(id){
	
	for(j=document.getElementById(id).options.length-1;j>=0;j--)

	{
	
	document.getElementById(id).remove(j);
	}
	
	}