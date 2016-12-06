<?php


class JSONBox
{
	
	function makeJsonObj()
	{
		
	}
	
	function makeJsonArray()
	{
		
	}
	
	function makeJsonErrorObj($errorMsg,$comment)
	{
		$post_data=array('message'=>$errorMsg,'comment'=>$comment);
		$post_data = json_encode(array('error' => $post_data), JSON_FORCE_OBJECT);
		return $post_data;
	}
	
	function makeJsonResponseObj($responseMsg,$comment)
	{
		$post_data=array('message'=>$responseMsg,'comment'=>$comment);
		$post_data = json_encode(array('response' => $post_data), JSON_FORCE_OBJECT);
		return $post_data;
	}
	
}


?>