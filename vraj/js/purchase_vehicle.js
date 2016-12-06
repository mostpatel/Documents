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