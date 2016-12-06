// JavaScript Document
function alterNoOfVehicles(no_vehicles)
{
   
	var prev_no_vehicles = document.no_vehicles;
	
	var vehicle_inner_html = document.getElementById('vehicle_details').innerHTML;
     
	var vehicle_table = document.getElementById('insertVehicleTable');
	if(prev_no_vehicles==(no_vehicles+1))
	{
		
		
	}
	else if(prev_no_vehicles<=no_vehicles)
	{
		
		for(i=prev_no_vehicles;i<=no_vehicles;i++)
		{
			
			var new_tbody=document.createElement('tbody');
			new_tbody.setAttribute('id','vehicle_details'+i);
			new_tbody.innerHTML=vehicle_inner_html;
			vehicle_table.appendChild(new_tbody);
			$('#vehicle_details'+i+' span:first-child')[0].innerHTML='Vehicle '+i;
			changePurchaseJvInputName(i);
			
				
		}
	}
	else if(prev_no_vehicles>no_vehicles)
	{
		
		for(i=(prev_no_vehicles-1);i>no_vehicles;i--)
		{
			$('#vehicle_details'+i).remove();
			}
	}	
	
	document.no_vehicles=parseInt(no_vehicles)+1;
	
}

function changePurchaseJvInputName(j)
{
	
	var purchase_jvs_input_array=$('#vehicle_details'+i+' .purchase_jvs_input');
	for(v=0;v<purchase_jvs_input_array.length;v++)
	{
		 purchase_jvs_input =  purchase_jvs_input_array[v];
		elem_name=purchase_jvs_input.getAttribute("name");
		new_elem_name = elem_name.substr(0,19) + (j-1) + elem_name.substr(20,elem_name.length);
		purchase_jvs_input.setAttribute("name",new_elem_name);
	}
}