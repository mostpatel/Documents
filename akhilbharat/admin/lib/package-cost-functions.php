<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");
	require_once("image-functions.php");
 
// -- Function Name : insertCity
// -- Params : $name
// -- Purpose : 
function insertIndividualPackageCost($package_id,$from_date,$to_date,$full_ticket,$extra_person,$half_ticket_with_seat,$half_ticket_without_seat,$per_couple)
    {
        try
        {
			if(isset($from_date) && validateForNull($from_date))
			{
		    $from_date = str_replace('/', '-', $from_date);
			$from_date=date('Y-m-d',strtotime($from_date));
			}	
			
			if(isset($to_date) && validateForNull($to_date))
			{
		    $to_date = str_replace('/', '-', $to_date);
			$to_date=date('Y-m-d',strtotime($to_date));
			}	
	
            $duplicate=checkForDuplicateIndividualPackageCost($package_id,$from_date,$to_date);
			
            if(!$duplicate && checkForNumeric($full_ticket,$extra_person,$half_ticket_with_seat,$half_ticket_without_seat,$per_couple) && validateForNull($from_date,$to_date) && strtotime($from_date)<strtotime($to_date))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_package_ind_cost
 ( `full_ticket`, `extra_person`, `half_ticket_with_seat`, `half_ticket_without_seat`, `per_couple`, `package_id`, `from_date`, `to_date`)
			  VALUES
			  ('$full_ticket', '$extra_person' ,'$half_ticket_with_seat' , '$half_ticket_without_seat' , '$per_couple', $package_id , '$from_date' , '$to_date')";
			  
			 
                $result=dbQuery($sql);
				$pkg_cost_id=dbInsertId();
                return $pkg_cost_id;
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

    



    

// -- Function Name : deleteCity
// -- Params : $id
// -- Purpose : 
    function deleteIndividualPackageCost($id)
    {
        try
        {
            
            if(checkForNumeric($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE from_date
			  trl_package_ind_cost
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

    

// -- Function Name : updateCity
// -- Params : $id,$name
// -- Purpose : 
    function updateIndividualPackageCost($id,$package_id,$from_date,$to_date,$full_ticket,$extra_person,$half_ticket_with_seat,$half_ticket_without_seat,$per_couple)
    {
        try
        {
           
			if(isset($from_date) && validateForNull($from_date))
			{
		    $from_date = str_replace('/', '-', $from_date);
			$from_date=date('Y-m-d',strtotime($from_date));
			}	
			
			if(isset($to_date) && validateForNull($to_date))
			{
		    $to_date = str_replace('/', '-', $to_date);
			$to_date=date('Y-m-d',strtotime($to_date));
			}	
             $duplicate=checkForDuplicateIndividualPackageCost($package_id,$from_date,$to_date,$id);
			
            if(!$duplicate && checkForNumeric($full_ticket,$extra_person,$half_ticket_with_seat,$half_ticket_without_seat,$per_couple,$id) && validateForNull($from_date,$to_date) && strtotime($from_date)<strtotime($to_date))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_package_ind_cost
			  SET `full_ticket` = $full_ticket, `extra_person` = $extra_person, `half_ticket_with_seat` = $half_ticket_with_seat, `half_ticket_without_seat` = $half_ticket_without_seat , `per_couple` = $per_couple, `package_id` = $package_id, `from_date` = '$from_date', `to_date` = '$to_date'
			  WHERE package_id=$package_id AND ind_cost_id = $id";
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

    

// -- Function Name : checkForDuplicateCity
// -- Params : $name,$id=false
// -- Purpose : 
    function checkForDuplicateIndividualPackageCost($package_id,$from_date,$to_date,$id=false)
    {
        try
        {
            $sql="SELECT ind_cost_id 
			  FROM 
			  trl_package_ind_cost
			  WHERE package_id = $package_id AND ((from_date <= '$from_date' AND to_date >='$from_date') OR (from_date <= '$to_date' AND to_date >='$to_date'))";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND ind_cost_id!=$id";
			
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

        catch(Exception $e)
        {
        }

    }
	
function insertVehiclePackageCost($package_id,$from_date,$to_date,$vehicle_id,$pax_2,$pax_3,$pax_4,$pax_6,$pax_9,$parent_id)
    {
        try
        {
			
			
	if(isset($from_date) && validateForNull($from_date))
			{
		    $from_date = str_replace('/', '-', $from_date);
			$from_date=date('Y-m-d',strtotime($from_date));
			}	
			
			if(isset($to_date) && validateForNull($to_date))
			{
		    $to_date = str_replace('/', '-', $to_date);
			$to_date=date('Y-m-d',strtotime($to_date));
			}	
            $duplicate=checkForDuplicateVehiclePackageCost($package_id,$from_date,$to_date,$vehicle_id);
			
            if(!$duplicate && checkForNumeric($vehicle_id,$pax_2,$pax_3,$pax_4,$pax_6,$pax_9,$package_id) && validateForNull($from_date,$to_date) && strtotime($from_date)<strtotime($to_date))
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_package_vehicle_cost
 (  `vehicle_id`, `2_pax`, `3_pax`, `4_pax`, `6_pax`, `9_pax`, `package_id`, `from_date`, `to_date`,parent_id)
			  VALUES
			  ('$vehicle_id', '$pax_2' ,'$pax_3' , '$pax_4' , '$pax_6', '$pax_9', $package_id , '$from_date' , '$to_date',$parent_id)";
			  
			  
			  
                $result=dbQuery($sql);
				$pkg_cost_id=dbInsertId();
				
				if($parent_id==0)
				{
					$sql="UPDATE trl_package_vehicle_cost SET parent_id = $pkg_cost_id WHERE vehicle_cost_id=$pkg_cost_id";
					dbQuery($sql);
				}
				
                return $pkg_cost_id;
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

    
	function insertVehiclePackageCostArray($package_id,$from_date,$to_date,$pax_2_array,$pax_3_array,$pax_4_array,$pax_6_array,$pax_9_array)
    {
        try
        {
			$i=0;
	foreach($pax_2_array as $vehicle_id => $pax_2)
	{
		$pax_3 = $pax_3_array[$vehicle_id];
		$pax_4 = $pax_4_array[$vehicle_id];
		$pax_6 = $pax_6_array[$vehicle_id];
		$pax_9 = $pax_9_array[$vehicle_id];
		
		if($i==0)
		$parent_id=0;
		
		
		
		$vehicle_cost_id=insertVehiclePackageCost($package_id,$from_date,$to_date,$vehicle_id,$pax_2,$pax_3,$pax_4,$pax_6,$pax_9,$parent_id);
		
		if($i==0)
		$parent_id = $vehicle_cost_id;
		
		$i++;
		
	}
	
		return $parent_id;
        }

        catch(Exception $e)
        {
        }

    }
	
	function updateVehiclePackageCostArray($package_id,$from_date,$to_date,$pax_2_array,$pax_3_array,$pax_4_array,$pax_6_array,$pax_9_array,$parent_id)
    {
        try
        {
			
	foreach($pax_2_array as $vehicle_id => $pax_2)
	{
		$vehicle_package_cost=getVehiclePackageCostByParentIDAndVehicleId($parent_id,$vehicle_id);
		
		$pax_3 = $pax_3_array[$vehicle_id];
		$pax_4 = $pax_4_array[$vehicle_id];
		$pax_6 = $pax_6_array[$vehicle_id];
		$pax_9 = $pax_9_array[$vehicle_id];
		
		updateVehiclePackageCost($vehicle_package_cost['vehicle_cost_id'],$package_id,$from_date,$to_date,$vehicle_id,$pax_2,$pax_3,$pax_4,$pax_6,$pax_9);
	}
	return "success";
        }

        catch(Exception $e)
        {
        }

    }	



    

// -- Function Name : deleteCity
// -- Params : $id
// -- Purpose : 
    function deleteVehiclePackageCost($id)
    {
        try
        {
            
            if(checkForNumeric($id))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="DELETE from_date
			  trl_package_vehicle_cost
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

    

// -- Function Name : updateCity
// -- Params : $id,$name
// -- Purpose : 
    function updateVehiclePackageCost($id,$package_id,$from_date,$to_date,$vehicle_id,$pax_2,$pax_3,$pax_4,$pax_6,$pax_9)
    {
        try
        {
           
			if(isset($from_date) && validateForNull($from_date))
			{
		    $from_date = str_replace('/', '-', $from_date);
			$from_date=date('Y-m-d',strtotime($from_date));
			}	
			
			if(isset($to_date) && validateForNull($to_date))
			{
		    $to_date = str_replace('/', '-', $to_date);
			$to_date=date('Y-m-d',strtotime($to_date));
			}	
             $duplicate=checkForDuplicateVehiclePackageCost($package_id,$from_date,$to_date,$vehicle_id,$id);
            if(!$duplicate && checkForNumeric($id,$vehicle_id,$pax_2,$pax_3,$pax_4,$pax_6,$pax_9,$package_id) && validateForNull($from_date,$to_date))
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_package_vehicle_cost
			  SET vehicle_id = $vehicle_id,`2_pax` = $pax_2, `3_pax` = $pax_3, `4_pax` = $pax_4, `6_pax` = $pax_6 , `9_pax` = $pax_9, `package_id` = $package_id, `from_date` = '$from_date', `to_date` = '$to_date'
			  WHERE package_id=$package_id AND vehicle_cost_id = $id";
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

    

// -- Function Name : checkForDuplicateCity
// -- Params : $name,$id=false
// -- Purpose : 
    function checkForDuplicateVehiclePackageCost($package_id,$from_date,$to_date,$vehicle_id,$id=false)
    {
        try
        {
            $sql="SELECT vehicle_cost_id 
			  FROM 
			  trl_package_vehicle_cost
			  WHERE package_id = $package_id AND ((from_date <= '$from_date' AND to_date >='$from_date') OR (from_date <= '$to_date' AND to_date >='$to_date')) AND vehicle_id = $vehicle_id";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND vehicle_cost_id!=$id";
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

        catch(Exception $e)
        {
        }

    }	

    

// -- Function Name : getCityByID
// -- Params : $id
// -- Purpose : 
    function getIndividualPackageCostByPackageID($id)
    {
        $sql="SELECT * from_date `trl_package_ind_cost` 
			  WHERE package_id=$id";
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
        
        if(dbNumRows($result)>0)
        {
            return $resultArray[0];
        }
        else
        {
            return false;
        }

    }
	
	 function getIndividualPackageCostFromToday($package_id)
    {
        $sql="SELECT * FROM `trl_package_ind_cost` 
			  WHERE to_date>=CURDATE() AND package_id = $package_id";
			
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
       
        if(dbNumRows($result)>0)
        {
            return $resultArray;
        }
        else
        {
            return false;
        }

    }
	
		 function getLowestIndividualPackageCostFromToday($package_id)
    {
        $sql="SELECT * FROM `trl_package_ind_cost` 
			  WHERE to_date>=CURDATE() AND package_id = $package_id ORDER BY full_ticket";
		
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
       
        if(dbNumRows($result)>0)
        {
            return $resultArray[0]['full_ticket'];
        }
        else
        {
            return false;
        }

    }
	
		 function getVehiclePackageCostIdFromToday($package_id)
    {
        $sql="SELECT DISTINCT parent_id FROM `trl_package_vehicle_cost` 
			  WHERE to_date>=CURDATE() AND package_id = $package_id";
			
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
       
        if(dbNumRows($result)>0)
        {
            return $resultArray;
        }
        else
        {
            return false;
        }

    }
	
	
  function getIndividualPackageCostByPackageCostID($id)
    {
        $sql="SELECT * FROM `trl_package_ind_cost` 
			  WHERE ind_cost_id=$id";
			  
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
       
        if(dbNumRows($result)>0)
        {
            return $resultArray[0];
        }
        else
        {
            return false;
        }

    }	
	
	    function getVehiclePackageCostByPackageID($id)
{
        $sql="SELECT * FROM `trl_package_vehicle_cost` 
			  WHERE package_id=$id";
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
        
        if(dbNumRows($result)>0)
        {
            return $resultArray;
        }
        else
        {
            return false;
        }

    }
	
	    function getVehiclePackageCostByVehicleCostID($id)
{
        $sql="SELECT * FROM `trl_package_vehicle_cost` 
			  WHERE vehicle_cost_id=$id";
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
        
        if(dbNumRows($result)>0)
        {
            return $resultArray[0];
        }
        else
        {
            return false;
        }

    }	

function getVehiclePackageCostByParentID($id)
{
        $sql="SELECT * FROM `trl_package_vehicle_cost` 
			  WHERE parent_id=$id";
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
        
        if(dbNumRows($result)>0)
        {
            return $resultArray;
        }
        else
        {
            return false;
        }

    }
	
function getVehiclePackageCostByParentIDAndVehicleId($id,$vehicle_id)
{
        $sql="SELECT * FROM `trl_package_vehicle_cost` 
			  WHERE parent_id=$id AND vehicle_id = $vehicle_id";
        $result=dbQuery($sql);
        $resultArray=dbResultToArray($result);
        
        if(dbNumRows($result)>0)
        {
            return $resultArray[0];
        }
        else
        {
            return false;
        }

    }		
?>