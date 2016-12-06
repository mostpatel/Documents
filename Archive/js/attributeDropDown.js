// JavaScript Document

function createAttributeDropDown(sub_cat_id,select_el)
{	

var product_table=$(select_el).parent().parent().parent().parent().parent()[0];
var product_tbody=$(select_el).parent().parent().parent().parent()[0];
var product_tbody_next=$(select_el).parent().parent().parent().parent().next('.attributeTbody')[0];


		var xmlhttp1;
if (window.XMLHttpRequest)
  { // code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp1 = new XMLHttpRequest();
  }
else
  { // code for IE6, IE5
  xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp1.onreadystatechange=function()                        
  {
  if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
    {
	
    var myarray=eval(xmlhttp1.responseText);
   
	if(myarray.length>0)
	
	{
// Before adding new we must remove previously loaded elements

		if(product_tbody_next)
		{	
		product_tbody_next.innerHTML="";
		new_tbody=product_tbody_next;
		}
		else
		{
		product_tbody_next_product = $(select_el).parent().parent().parent().parent().next()[0];
		var new_tbody = document.createElement('tbody');
		}

		for (var i=0; i<myarray.length; i++)
		{
		
		var new_tr = document.createElement('tr');
		var new_td1 = document.createElement('td');	
		var new_td2 = document.createElement('td');	
		
		
		type_id = myarray[i];
		type_name = myarray[++i];
		single_multiple = myarray[++i];
		new_td1.innerHTML=type_name+" : ";
		
		
		
		var selectList = document.createElement("select");
		
		selectList.setAttribute("name","attribute_name_array["+sub_cat_id+"]["+type_id+"][]");
		selectList.setAttribute("class","selectpic show-tick form-control");
		if(single_multiple==1)
		selectList.setAttribute("multiple","multiple");
		selectList.setAttribute("data-live-search","true");
		new_tbody.setAttribute("class","attributeTbody");
		new_td2.appendChild(selectList);
		
		myarray2=getAttributeNamesFromAttributeId(sub_cat_id,type_id,selectList);
		
		
		
		new_tr.appendChild(new_td1);
		new_tr.appendChild(new_td2);
		
		new_tbody.appendChild(new_tr);
		
		
		} 
		var new_tr1 = document.createElement('tr');
		var new_td3 = document.createElement('td');	
		var new_td4 = document.createElement('td');	
		new_td3.innerHTML='<hr class="firstTableFinishing" />';
		new_td4.innerHTML='<hr class="firstTableFinishing" />';
		new_tr1.appendChild(new_td3);
		new_tr1.appendChild(new_td4);
		new_tbody.appendChild(new_tr1);
		if(!product_tbody_next)
		{	
			if(product_tbody_next_product)
			{
				product_table.insertBefore(new_tbody,product_tbody_next_product);
			}
			else
			product_table.appendChild(new_tbody);
			}



   		 }
	}
	else if(product_tbody_next)
		{	
		product_tbody_next.innerHTML="";
		
		}
}
  
  xmlhttp1.open('GET', "getRelatedAttributes.php?id="+sub_cat_id, true );    
  xmlhttp1.send(null);

	
}

function getAttributeNamesFromAttributeId(sub_cat_id,type_id,selectList)
{
	var xmlhttp2;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp2.onreadystatechange=function()                        
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {

    var myarray2=eval(xmlhttp2.responseText);
	
// Before adding new we must remove previously loaded elements
	
	var plsOptn1 = document.createElement("OPTION");

plsOptn1.value = -1;
plsOptn1.text = "--Please Select--";

selectList.options.add(plsOptn1);

for (var i=0; i<myarray2.length; i++)
{
	
var optn1 = document.createElement("OPTION");

optn1.value = myarray2[i];
optn1.text = myarray2[++i];

selectList.options.add(optn1);

} 

$(selectList).selectpicker();
 
    }
  }
  xmlhttp2.open('GET', "getRelatedAttributeNames.php?id="+sub_cat_id+"&state="+type_id, true );    
  xmlhttp2.send(null);
	
	
}

function createDropDownAreaCustomer(city_id)
{
	
	createDropDown("getAreaFromCity.php?id="+city_id,"customer_area_id",null);
	}	

function createDropDownAreaGuarantor(city_id)
{
	
	createDropDown("getAreaFromCity.php?id="+city_id,"guarantor_area_id",null);
	
	}		

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

function createDropDown2(url,id,removeArray)  
{

var xmlhttp2;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp2 = new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp2.onreadystatechange=function()                        
  {
  if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
    {
	
    var myarray=eval(xmlhttp2.responseText);
	
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
  
  xmlhttp2.open('GET', url, true );    
  xmlhttp2.send(null);

}

function removeData(id){
	
	for(j=document.getElementById(id).options.length-1;j>=0;j--)

	{
	
	document.getElementById(id).remove(j);
	}
	
	}