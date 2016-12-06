<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");

// -- Function Name : listCities
// -- Params : 
// -- Purpose : 
    function listPurchaseJvs()
    {
        try
        {
            $sql="SELECT purchase_sales_jv_id,ledger_id,cr_dr,type
		      FROM edms_purchase_sales_jv WHERE type=0 ORDER BY purchase_sales_jv_id";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	
	 function listPurchaseJvLedgerIds()
    {
        try
        {
            $sql="SELECT GROUP_CONCAT(ledger_id)
		      FROM edms_purchase_sales_jv WHERE type=0 GROUP BY type";
			
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			{
		
			return explode(",",$resultArray[0][0]);
			}
			else
			return false;
        }

        catch(Exception $e)
        {
        }

    }
	
	function listSalesJvLedgerIds()
    {
        try
        {
            $sql="SELECT GROUP_CONCAT(ledger_id)
		      FROM edms_purchase_sales_jv WHERE type=1 GROUP BY type";
			  
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
			
			if(dbNumRows($result)>0)
            {
			if(checkForNumeric($resultArray[0][0]))
			return array($resultArray[0][0]);
			else
			return explode($resultArray[0][0]);
			}
			else
			return false;
        }

        catch(Exception $e)
        {
        }

    }

 function listSalesJvs()
    {
        try
        {
            $sql="SELECT purchase_sales_jv_id,ledger_id,cr_dr,type
		      FROM edms_purchase_sales_jv WHERE type=1 ORDER BY purchase_sales_jv_id";
            $result=dbQuery($sql);
            $resultArray=dbResultToArray($result);
            return $resultArray;
        }

        catch(Exception $e)
        {
        }

    }
	
	function listPurchaseSalesJvs()
    {
        try
        {
            $sql="SELECT purchase_sales_jv_id,ledger_id,cr_dr,type
		      FROM edms_purchase_sales_jv ORDER BY purchase_sales_jv_id";
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
function insertPurchaseSaleJv($ledger_id,$cr_dr,$type)
{
        try
        {
           $duplicate = checkForDuplicatePurchaseSaleJv($ledger_id,$cr_dr,$type);
            if(checkForNumeric($ledger_id,$cr_dr,$type) && !$duplicate)
            {
               
                $sql="INSERT INTO
		      edms_purchase_sales_jv (ledger_id, cr_dr, type)
			  VALUES
			  ($ledger_id, $cr_dr, $type)";
			  
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

    



    

// -- Function Name : deleteCity
// -- Params : $id
// -- Purpose : 
    function deletePurchaseSalesJv($id)
    {
        try
        {
            
            if(checkForNumeric($id))
            {
                
                $sql="DELETE FROM
			  edms_purchase_sales_jv
			  WHERE edms_purchase_sales_jv_id=$id";
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
    function updatePurchaseSaleJv($id,$ledger_id,$cr_dr,$type)
    {
        try
        {
           
            $duplicate=checkForDuplicatePurchaseSaleJv($ledger_id,$cr_dr,$type,$id);
            
            if(checkForNumeric($id,$ledger_id,$cr_dr,$type) && !$duplicate)
            {
               
                $sql="UPDATE purchase_sales_jv
			  SET ledger_id=$ledger_id, cr_dr = $cr_dr, type = $type
			  WHERE purchase_sales_jv_id=$id";
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
    function checkForDuplicatePurchaseSaleJv($ledger_id,$cr_dr,$type,$id=false)
    {
        try
        {
            $sql="SELECT purchase_sales_jv_id 
			  FROM 
			  edms_purchase_sales_jv
			  WHERE ledger_id=$ledger_id AND cr_dr = $cr_dr AND type = $type";
            
            if($id==false)$sql=$sql."";
            else $sql=$sql." AND purchase_sales_jv_id!=$id";
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
    function getPurchaseSaleJvByID($id)
    {
        $sql="SELECT purchase_sales_jv_id,ledger_id,cr_dr,type
		      FROM edms_purchase_sales_jv
			  WHERE purchase_sales_jv_id=$id";
			 
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