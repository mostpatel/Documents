// JavaScript Document

function getPrefixFromAgency(id)
{
if (id==-1)
  {
  document.getElementById('agencyPrefix').innerHTML="";
  return false;
  } 
 document.getElementById('available_file_no').innerHTML = ""; 
 document.getElementById('fileNumber').value = "";
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {	
    var res=eval(xmlhttp.responseText);
	document.getElementById('agencyPrefix').innerHTML=res[0];
	document.agency_type=res[1];
	if(res[1]==2)
	{
		
		document.getElementById('agencyParticipationDetails').style.display="block";		
	}
	else if(res[1]==1)
	{
		document.getElementById('agencyParticipationDetails').style.display="none";	
		}
    }
  }
xmlhttp.open("GET","getPrefixFromAgency.php?p="+id,true);
xmlhttp.send();

	elem=document.getElementById('agreementNo');
	elemValue=document.getElementById('agreementNo').value;
	url="ajax/agreementNo.php?id=";
	url=url+id+"&value="+elemValue;	
	

		var xmlhttp2;
	
	  xmlhttp2 = new XMLHttpRequest();
	 
	
	 
	  

	  xmlhttp2.onreadystatechange=function()                        
	  {
		if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
		{   
		
		var myarray=eval(xmlhttp2.responseText);
		
		if(myarray!=0)
		{
			$(elem).addClass("ErrorField");
			$(elem).next(".availError").show();
		}
		else
		{
			$(elem).removeClass("ErrorField");
			$(elem).next(".availError").hide();
			}	
		}
	  }
  
  xmlhttp2.open('GET', url, true );    
  xmlhttp2.send(null);
  
  var xmlhttp3;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp3 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp3 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp3.onreadystatechange=function()                        
  {
  if (xmlhttp3.readyState==4 && xmlhttp3.status==200)
    {
	
    var myarray2=eval(xmlhttp3.responseText);
// Before adding new we must remove previously loaded elements
var bank_select_id='by_ledger';
removeData(bank_select_id);

if(myarray2.length==0)
{
var plsOptn = document.createElement("OPTION");

plsOptn.value = -1;
plsOptn.text = "--Please Select--";

document.getElementById(bank_select_id).options.add(plsOptn);
}
for (var i=0; i<myarray2.length; i++)
{
	
var optn = document.createElement("OPTION");

optn.value = myarray2[i];
optn.text = myarray2[++i];

document.getElementById(bank_select_id).options.add(optn);

}

    }
  }
  
  xmlhttp3.open('GET', "getBanksFromAgency.php?p="+id, true );    
  xmlhttp3.send(null);
  
  
  
   var xmlhttp4;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp4 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp4 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp4.onreadystatechange=function()                        
  {
  if (xmlhttp4.readyState==4 && xmlhttp4.status==200)
    {
	if(xmlhttp4.responseText!=0)
	{
    var myarray2=eval(xmlhttp4.responseText);
	
// Before adding new we must remove previously loaded elements


var file_nos_string="Available File Nos : ";
for (var i=0; i<myarray2.length; i++)
{
	

if(i==0)
{
	document.getElementById('fileNumber').value = myarray2[i];
}
file_nos_string = file_nos_string + myarray2[i];
if(i<myarray2.length-1)
file_nos_string = file_nos_string + " , ";


}

document.getElementById('available_file_no').innerHTML=file_nos_string;

    }
	}
  }

  xmlhttp4.open('GET', "generate_file_no.php?p="+id, true );    
  xmlhttp4.send(null);
	
	}

function checkAgreementFromAgencyDropDown()
{
	}	

function removeData(id){
	
	for(j=document.getElementById(id).options.length-1;j>=0;j--)
	{	
	document.getElementById(id).remove(j);
	}
	
}