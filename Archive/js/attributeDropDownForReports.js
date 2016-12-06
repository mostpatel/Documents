// JavaScript Document

function loadAttrType()
{	
emptyAttributeNameRows();
var product_str = "";
var cat_str = "";
var super_cat_str = "";


var super_cat = document.getElementById("super_cat_list");
var cat = document.getElementById("category_list");
var all_products=document.getElementById("bs3Select");

var product_tr = document.getElementById('productTr');
var product_table = $(product_tr).parent()[0];


for (var i=0; i<all_products.options.length; i++)
{
	if(all_products.options[i].selected==true)
	{
	 // alert(all_products.options[i].value); // Add this values in a string
	product_str = product_str + all_products.options[i].value;
	
    product_str = product_str + ",";
	}
	
	
};

if(product_str!="")
	product_str = product_str.substring(0, product_str.length - 1);

for(var i=0; i<cat.options.length; i++)
{
	
	if(cat.options[i].selected==true) //alert(cat.options[i].value); // Add this values in a string
	{
	cat_str = cat_str + cat.options[i].value;
	
    cat_str = cat_str + ",";
	}

};

	if(cat_str!="")
	cat_str = cat_str.substring(0, cat_str.length - 1);

for (var i=0; i<super_cat.options.length; i++)
{
	if(super_cat.options[i].selected==true) //alert(super_cat.options[i].value); // Add this values in a string
	{
	super_cat_str = super_cat_str + super_cat.options[i].value;
    super_cat_str = super_cat_str + ",";
	}
	
};

if(super_cat_str!="")
	super_cat_str = super_cat_str.substring(0, super_cat_str.length - 1);

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
	
	if(myarray.length>0)
	{
	

		for (var i=0; i<myarray.length; i++)
		{
		
		var new_tr = document.createElement('tr');
		var new_td1 = document.createElement('td');	
		var new_td2 = document.createElement('td');	
		
		
		type_id = myarray[i];
		type_name = myarray[++i];
		new_td1.innerHTML=type_name+" : ";
		
		var selectList = document.createElement("select");
		
		selectList.setAttribute("name","attribute_name_array[]");
		selectList.setAttribute("class","selectpic show-tick form-control");
		selectList.setAttribute("multiple","multiple");
		selectList.setAttribute("data-live-search","true");
		new_tr.setAttribute('class','attribute_name_tr');
		
		new_td2.appendChild(selectList);
	
		myarray2=getAttributeNamesFromTypeIdAndCatSubCatSuperCatIdArrays(product_str, super_cat_str, cat_str, type_id, selectList);
		
		new_tr.appendChild(new_td1);
		new_tr.appendChild(new_td2);
	
		product_table.insertBefore(new_tr,document.getElementById('insertBeforeTr'));
		
		} 
		
		
		
	}
	}
  }
	
  xmlhttp1.open('GET', "getRelatedAttributesForReports.php?str1="+super_cat_str+"&str2="+cat_str+"&str3="+product_str, true);    
  xmlhttp1.send(null);

	
}

function emptyAttributeNameRows()
{
	$('.attribute_name_tr').each(function(index, element) {
        
		element.innerHTML = "";
		
    });
}

function getAttributeNamesFromTypeIdAndCatSubCatSuperCatIdArrays(product_str, super_cat_str, cat_str, type_id, selectList)
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
  xmlhttp2.open('GET', "getRelatedAttributeNames.php?id="+type_id+"&str1="+super_cat_str+"&str2="+cat_str+"&str3="+product_str, true);    
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
for(var i=0; i<removeArray.length; i++)
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