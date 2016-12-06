<?php 
    require_once("cg.php");

    require_once("bd.php");

    require_once("common.php");

	require_once("image-functions.php");

	require_once("package-itenary-functions.php");

	require_once("package-type-functions.php");



function listAddresses()

    {

        try

        {

            $sql="SELECT office_address_id, address, contact_person, contact_number, email, location_id 

		      FROM trl_office_address";

            $result=dbQuery($sql);

            $resultArray=dbResultToArray($result);

			return $resultArray;

        }

   catch(Exception $e)

        {

        }



}

	

function listAddressForLocationId($id)
{
try
  {
          $sql="SELECT  office_address_id, address, contact_person, contact_number, email, location_id
		      FROM trl_office_address 
			  WHERE location_id = $id";
            $result=dbQuery($sql);
			$resultArray=dbResultToArray($result);

			return $resultArray;

        }



        catch(Exception $e)

        {

        }



}









    function insertOfficeAddress($address, $contact_person, $contact_number, $email, $location_id)

    {
        try
{
	        $contact_person=clean_data($contact_person);

			$contact_number=clean_data($contact_number);

			$email=clean_data($email);

            $address = mysql_real_escape_string($address);
		
			$contact_person = ucfirst(strtolower($contact_person));
			
			
            if(validateForNull($address) && checkForNumeric($location_id))

            {

				

                $admin_id=$_SESSION['adminSession']['admin_id'];

                $sql="INSERT INTO trl_office_address (address, contact_person, contact_number, email, location_id)

			  VALUES ('$address', '$contact_person', '$contact_number', '$email', '$location_id')";

			 
				
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

	


function deleteAddress($id)

{

	if(checkForNumeric($id))

	{

		$sql="DELETE FROM trl_office_address WHERE office_address_id = $id";

		dbQuery($sql);
         return "success";
		}

	

	} 
	
	





function updateOfficeAddress($id,$address, $contact_person, $contact_number, $email, $location_id)
    {

        try

        {

			if(validateForNull($address) && checkForNumeric($location_id))
            {
            $contact_person=clean_data($contact_person);

			$contact_number=clean_data($contact_number);

			$email=clean_data($email);

            $address = mysql_real_escape_string($address);
		
			$contact_person = ucfirst(strtolower($contact_person));
        

                $admin_id=$_SESSION['adminSession']['admin_id'];

                $sql="UPDATE trl_office_address 
				SET address= '$address', contact_person='$contact_person', contact_number= '$contact_number', email= '$email', location_id= $location_id
			  WHERE office_address_id=$id";
			  
			  

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





function checkForDuplicateOfficeAddress($name,$location_array,$id=false)

    {

        try

        {

            $sql="SELECT location_id 

			  FROM 

			  trl_locations 

			  WHERE location_name='$name' AND super_location_id=$super_location_id ";

            

            if($id==false)$sql=$sql."";

            else $sql=$sql." AND location_id!=$id";

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



   

function getOfficeAddressByID($id)

    {

        $sql="SELECT office_address_id, office_address_id, address, contact_person, contact_number, email, location_id 

		      FROM trl_office_address

			  WHERE office_address_id=$id";

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