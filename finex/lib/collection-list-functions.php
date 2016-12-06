<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("customer-functions.php");
require_once("guarantor-functions.php");
require_once("common.php");
require_once("bd.php");



function getLatestCollectionDateForFileId($file_id,$type=0)
{
	if(!checkForNumeric($type))
	$type=0;
	
	if(checkForNumeric($file_id,$type))
	{
		$sql="SELECT MAX(collection_list_date) FROM fin_collection_list, fin_rel_collection_list_file WHERE file_id = $file_id AND fin_collection_list.collection_list_id = fin_rel_collection_list_file.collection_list_id GROUP BY file_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return false;
		
	}
	
}

function insertCollectionList($file_id_array,$list_date)
{
	if(is_array($file_id_array) && count($file_id_array)>0)
	{
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 if(checkForNumeric($admin_id) && validateForNull($list_date))
		 {
			  $list_date = str_replace('/', '-', $list_date);// converts dd/mm/yyyy to dd-mm-yyyy
			  $list_date=date('Y-m-d',strtotime($list_date)); // converts date to Y-m-d format
			  
			  
				$sql="INSERT INTO fin_collection_list (collection_list_date,date_added,created_by) VALUES ('$list_date',NOW(),$admin_id)";
				$result = dbQuery($sql);
				$bulk_notice_id = dbInsertId();
				foreach($file_id_array as $file_id)
				{
					$sql="INSERT INTO fin_rel_collection_list_file (collection_list_id,file_id) VALUES ($bulk_notice_id,$file_id)";
					dbQuery($sql);
				}
		 }
	}
	return $bulk_notice_id;
}

function getFilesForCollectionListId($collection_list_id)
{
	if(checkForNumeric($bulk_notice_id))
	{
		$sql="SELECT file_id FROM fin_rel_collection_list_file WHERE collection_list_id = $collection_list_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$returnArray = array();
			foreach($resultArray as $re)
			{
				$returnArray[] = $re[0];
			}
			return $returnArray;
		}
	}
	return false;
	
}
?>