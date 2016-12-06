<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");
	require_once("image-functions.php");

// -- Function Name : listCities
// -- Params : 
// -- Purpose : 
    function listTestonomials()
    {
        try
        {
            $sql="SELECT testonomial_id,person_name, person_company, person_designation, testonomial, img_href, created_by, last_updated_by, date_added, date_modified
		      FROM trl_testonomial";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	
    
// -- Function Name : insertCity
// -- Params : $name
// -- Purpose : 
    function insertTestonomial($person_name,$person_company,$person_designation,$img,$testonomial)
    {
        try
        {
			
			
            $person_name=strtoupper(clean_data($person_name));
			$person_company=strtoupper(clean_data($person_company));
			$person_designation=strtoupper(clean_data($person_designation));
			$testonomial = clean_data($testonomial);
			
            $duplicate=checkForDuplicateTestonomial($person_name,$person_company);
         
            if(validateForNull($person_company,$person_name,$person_designation,$testonomial)  && !$duplicate)
            {
				
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="INSERT INTO
		      trl_testonomial (person_name, person_company, person_designation, testonomial, img_href, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$person_name','$person_company','$person_designation' ,'$testonomial', 'NA', $admin_id, $admin_id, NOW(), NOW())";
                $result=dbQuery($sql);
				$testonomial_id=dbInsertId();
				
				 $img_href= addTestonomialImage($testonomial_id,$person_name,$img);
				
				 if(!$img_href)
				 {
					
				 }
				else
				{
					$sql="UPDATE trl_testonomial SET img_href = '$img_href' WHERE testonomial_id=$testonomial_id";
					dbQuery($sql);
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

    



    

// -- Function Name : deleteCity
// -- Params : $id
// -- Purpose : 
    function deleteTestonomial($id)
    {
        try
        {
            
            if(checkForNumeric($id))
            {
               
                $sql="DELETE FROM
			  trl_testonomial
			  WHERE testonomial_id=$id";
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
    function updateTestonomial($id,$person_name,$person_company,$person_designation,$img,$testonomial)
    {
        try
        {
			
			
           $person_name=strtoupper(clean_data($person_name));
			$person_company=strtoupper(clean_data($person_company));
			$person_designation=strtoupper(clean_data($person_designation));
			$testonomial = clean_data($testonomial);
			
            $duplicate=checkForDuplicateTestonomial($person_name,$person_company,$id);
        
            if(validateForNull($person_company,$person_name,$person_designation,$testonomial)  && !$duplicate)
            {
                $admin_id=$_SESSION['adminSession']['admin_id'];
                $sql="UPDATE trl_testonomial
			  SET person_name='$person_name', person_company = '$person_company', person_designation = '$person_designation',    last_updated_by=$admin_id, date_modified=NOW()
			  WHERE testonomial_id=$id";
			  
                dbQuery($sql);
				
				
				
				 
				 if(isset($img['tmp_name']) && validateForNull($img['tmp_name']))
				 {
				 	$img_href= addTestonomialImage($id,$person_name,$img);
					 if(!$img_href)
					 {
						 return "img_error";
						 }
					else
					{
						$sql="UPDATE trl_testonomial SET img_href = '$img_href' WHERE testonomial_id=$id";
						dbQuery($sql);
					}	 
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

    

// -- Function Name : checkForDuplicateCity
// -- Params : $name,$id=false
// -- Purpose : 
    function checkForDuplicateTestonomial($person_name,$person_company,$id=false)
    {
        try
        {
            $sql="SELECT testonomial_id 
			  FROM 
			  trl_testonomial 
			  WHERE person_name='$person_name' AND person_company='$person_company' ";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND testonomial_id!=$id";
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
    function getTestonomialByID($id)
    {
        $sql="SELECT testonomial_id, person_name, person_company, person_designation, testonomial, img_href, created_by, last_updated_by, date_added, date_modified
			  FROM 
			  trl_testonomial 
			  WHERE testonomial_id=$id";
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