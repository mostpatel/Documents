<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
	
function listVehicleTypes(){
	
	try
	{
		
		$sql="SELECT vehicle_id,vehicle_type
		      FROM trl_vehicle_type ORDER BY vehicle_id";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
	}
	catch(Exception $e)
	{
	}
	
}	

function insertVehicleType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
		  $duplicate=checkForDuplicateVehicleType($name);
          
            if(validateForNull($name)  && !$duplicate )
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_vehicle_type (vehicle_type)
			  VALUES
			  ('$name')";
                $result=dbQuery($sql);
				
                return "success";
            }
            else
            {
                return "error";
            }

        
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteVehicleType($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) && !checkIVehicleTypeInUse($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE FROM
			  trl_vehicle_type
			  WHERE vehicle_id=$id";
                dbQuery($sql);
                return  "success";
            }
            else
            {
                return "error";
            }

        }

        catch(Exception $e)
        {
        }
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateVehicleType($name,$id=false)
{
	 $sql="SELECT vehicle_id 
			  FROM 
			  trl_vehicle_type
			  WHERE vehicle_type='$name' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND vehicle_id!=$id";
            $result=dbQuery($sql);
            
            if(dbNumRows($result)>0)
            {
                $resultArray=dbResultToArray($result);
                return $resultArray[0][0];
                //duplicate found
            }
            else
            {
                return false;
            }

	
	}

function updateVehicleType($name){
	
	try
	{
		 $name=clean_data($name);
		 $name = ucfirst(strtolower($name));
			
            $duplicate=checkForDuplicateVehicleType($name);
	 if(validateForNull($name)  && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_vehicle_type
			  SET vehicle_type='$name'
			  WHERE vehicle_id=$id";
                dbQuery($sql);
			
                return "success";
            }
            else
            {
                return "error";
            }

        }
	catch(Exception $e)
	{
	}
	
}	

function getVehicleTypeById($id){
	try
	{
		$sql="SELECT vehicle_type
		      FROM trl_vehicle_type WHERE vehicle_id = $id  ORDER BY vehicle_type";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray[0][0];
		
	}
	catch(Exception $e)
	{
	}
	
}	

 function checkIVehicleTypeInUse($id)
    {
        $sql="SELECT vehicle_id
	      FROM trl_rel_vehicle_type
		  WHERE vehicle_id=$id LIMIT 0, 1";
        $result=dbQuery($sql);
        
        if(dbNumRows($result)>0)
        {
            return true;
        }

        return false;
    }
	
function insertVehicleTypesForVehicle($vehicle_id,$price,$package_id){
	
	try
	{
		
          
            if(validateForNull($vehicle_id[0],$price[0]) && checkForNumeric($package_id))
            {
				
				for($i=0;$i<count($vehicle_id);$i++)
				{
				$p_t_id=$vehicle_id[$i];
				$pr=$price[$i];	
			
				insertVehicleTypeForVehicle($p_t_id,$pr,$package_id);	
				}
               
				
                return "success";
            }
            else
            {
                return "error";
            }

        
	}
	catch(Exception $e)
	{
	}
	
}

function insertVehicleTypeForVehicle($vehicle_id,$price,$package_id)
{
	
	if(checkForNumeric($package_id,$vehicle_id,$price))
	{
		$sql="INSERT INTO trl_rel_vehicle_type (package_id, vehicle_id, price) VALUES($package_id,$vehicle_id,$price)";
		dbQuery($sql);
		}
	
	}	

function deleteVehicleTypesForVehicle($id){
	
	try
	{
		
		 try
        {
            
            if(checkForNumeric($id) )
            {
               
                $sql="DELETE FROM
			  trl_rel_vehicle_type
			  WHERE package_id=$id";
                dbQuery($sql);
                return  "success";
            }
            else
            {
                return "error";
            }

        }

        catch(Exception $e)
        {
        }
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleTypeForVehicle($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT trl_vehicle_type.vehicle_id, vehicle_type, price FROM trl_rel_vehicle_type, trl_vehicle_type WHERE package_id = $id AND trl_vehicle_type.vehicle_id = trl_rel_vehicle_type.vehicle_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray=dbResultToArray($result);
			return $resultArray;
			}
		return false;	
		}
		
	}	
	
?>