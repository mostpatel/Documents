<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listSMSRecords(){
	
	try
	{
		$sql="SELECT sms_id, vehicle_type
		      FROM fin_sms_records
			  ORDER BY vehicle_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getLastSmsSentForJobCard($job_card_id)
{
	$sql="SELECT sms_id,msg_id, type, type_id, date_added, mobile_no,message,delivery_time FROM fin_sms_records WHERE type=1 And type_id = $job_card_id";
	$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
		$resultArray=dbResultToArray($result);
		return $resultArray;
		}
		else return false;
}

function getLastSmsSentForEmiPayment($id)
{
	$sql="SELECT sms_id,msg_id, type, type_id, date_added, mobile_no,message,delivery_time FROM fin_sms_records WHERE type=1 And type_id = $id";
	$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
		$resultArray=dbResultToArray($result);
		return $resultArray;
		}
		else return false;
}

function updateDeliveryStatusForSms()
{
$upto_time = getTodaysDateTimeBeforeMinute(5);	
$sql="SELECT msg_id, type, type_id, date_added, mobile_no,message,delivery_time  FROM fin_sms_records WHERE delivery_time = '1970-01-01'";
$result=dbQuery($sql);
if(dbNumRows($result))
{
		$resultArray=dbResultToArray($result);
		foreach($resultArray as $re)
		{
		$return_array=getDeliveryStatusForMsgId($re['msg_id']);
		if($return_array[0]=="DELIVRD")
		{
			$delivery_time = $return_array[1];
			$msg_id= $re['msg_id'];
			if(isset($delivery_time) && validateForNull($delivery_time))
			{
		    $delivery_time = str_replace('/', '-', $delivery_time);
			$delivery_time=date('Y-m-d H:i:s',strtotime($delivery_time));
			}	
			$sql="UPDATE fin_sms_records SET status = '$return_array[0]' , delivery_time='$delivery_time' WHERE msg_id ='$msg_id' ";
			
		dbQuery($sql);	
		}
		}
}


		
}
function getDeliveryStatusForMsgId($msg_id)
{
	$type="delivery_status";
	require_once('sms-settings.php');
	$url .= '?' . http_build_query($params);
	
  
    $ch = curl_init();

  
    curl_setopt($ch, CURLOPT_URL, $url);

  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   
    curl_setopt($ch, CURLOPT_HEADER, false);

  
    $data = curl_exec($ch);
	
	
	$dataArray = explode(':', $data);
	$sms_status = $dataArray[2];
	$delivery_time = $dataArray[3].":".$dataArray[4].":".$dataArray[5];
	
	

   
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
  
    curl_close($ch);
   
    $returnAray = array();
    $returnAray[0] = $sms_status;
    $returnAray[1] = $delivery_time;

   return $returnAray;
	
}
function getNumberOfSMSRecordsForJobCardId($id)
{
	$sql="SELECT count(sms_id)
		      FROM fin_sms_records WHERE type = 1 AND type_id = $id
			  GROUP BY type_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
	
function getNumberOfSMSRecordsForTypeAndId($type,$id)
{
	$sql="SELECT count(sms_id)
		      FROM fin_sms_records WHERE type = $type AND type_id = $id
			  GROUP BY type_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}	
	
function insertSMSRecord($msg_id,$type,$mobile_no,$message,$delivery_time,$type_id){
	
	try
	{
		$msg_id=clean_data($msg_id);
		$type=clean_data($type);
		$type_id=clean_data($type_id);
		
		$mobile_no=clean_data($mobile_no);
		$message = clean_data($message);
		$delivery_time=clean_data($delivery_time);
		$type_id=clean_data($type_id);
		
		
		if(validateForNull($msg_id,$message) && checkForNumeric($type,$type_id,$mobile_no) )
		{
		$admin_id=$_SESSION['finAdminSession']['admin_id'];
		$sql="INSERT INTO fin_sms_records
		      (msg_id, type, type_id, date_added, mobile_no,message,delivery_time)
			  VALUES
			  ('$msg_id', $type, $type_id, NOW(), '$mobile_no','$message','$delivery_time')";
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

function deleteSMSRecord($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfSMSRecordInUse($id))
		{
		$sql="DELETE FROM fin_sms_records
		      WHERE sms_id=$id";
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

function updateSMSRecord($id,$delivery_time){
	
	try
	{
		$delivery_time=clean_data($delivery_time);
		
		if(checkForNumeric($id))
		{
			
		$sql="UPDATE fin_sms_records
		      SET delivery_time='$delivery_time'
			  WHERE sms_id=$id";
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

function getSMSRecordById($id){
	
	try
	{
		$sql="SELECT sms_id, vehicle_type
		      FROM fin_sms_records
			  WHERE sms_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getSMSRecordNameById($id){
	
	try
	{
		$sql="SELECT sms_id, vehicle_type
		      FROM fin_sms_records
			  WHERE sms_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateSMSRecord($vehicle_type,$id=false)
{
	    if(validateForNull($vehicle_type))
		{
		$sql="SELECT sms_id
		      FROM fin_sms_records
			  WHERE vehicle_type='$vehicle_type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND sms_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfSMSRecordInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			fin_vehicle
			WHERE sms_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>