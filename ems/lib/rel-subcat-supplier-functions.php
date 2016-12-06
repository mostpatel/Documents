<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("supplier-functions.php");
require_once("sub-category-functions.php");
require_once("common.php");
require_once("bd.php");


function insertRelSubCatSupplier($sub_cat_id, $supplier_id_array)
{	

	try
	{
		
		if(checkForNumeric($sub_cat_id))
		{
				foreach($supplier_id_array as $supplier_id)
		{
			
				$sql="INSERT INTO ems_rel_sub_cat_supplier (sub_cat_id, supplier_id)				
				      VALUES ($sub_cat_id, $supplier_id)";
				
				$result=dbQuery($sql);
		}
		
           return "success";
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


function getSectorWithSuppliers(){
	
	try
	{
		$sql="SELECT sub_cat_name, ems_subCategory.sub_cat_id, 
		(SELECT GROUP_CONCAT(DISTINCT ems_sub_cat_suppliers.supplier_name SEPARATOR '<br>') FROM ems_rel_sub_cat_supplier, ems_sub_cat_suppliers 
		WHERE ems_rel_sub_cat_supplier.sub_cat_id = ems_subCategory.sub_cat_id AND ems_sub_cat_suppliers.supplier_id = ems_rel_sub_cat_supplier.supplier_id GROUP BY ems_subCategory.sub_cat_id) as supplier_names
		
			  FROM ems_subCategory
			  
			  JOIN ems_rel_sub_cat_supplier
			  ON ems_rel_sub_cat_supplier.sub_cat_id = ems_subCategory.sub_cat_id GROUP BY ems_subCategory.sub_cat_id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	
	catch(Exception $e)
	{
	}
	
}
	

function getSuppliersBySubCatId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT supplier_id
			  FROM ems_rel_sub_cat_supplier
			  WHERE sub_cat_id = $id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
        return false;
	}
	catch(Exception $e)
	{
	}
	
}



	
?>