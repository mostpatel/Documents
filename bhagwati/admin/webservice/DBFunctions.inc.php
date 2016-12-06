<?php

function fireQuery($DBHandler,$query,$returnJSON=false,$JSONObjectName="data")
{
	$resultArray=array();
	$result=$DBHandler->query($query);
	while($singleData=$result->fetch(PDO::FETCH_ASSOC))
	{
		$resultArray[]=$singleData;	
	}
	if($returnJSON==true)
	{
		return json_encode(array($JSONObjectName=>$resultArray));
	}
	return $resultArray;
}


?>