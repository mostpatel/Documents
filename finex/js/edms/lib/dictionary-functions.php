<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("inventory-item-functions.php");
		
function listDictionaryWords(){
	
	try
	{
		$sql="SELECT word_id, word, soundex
		      FROM edms_dictionary_item
			  ORDER BY word";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}
function clean($string) {
   $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}	

function getNumberOfDictionaryWords()
{
	$sql="SELECT count(word_id)
		      FROM edms_dictionary_item
			  ORDER BY word";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
		
	}

function insertDictionaryWords($words)
{
	$words_array = explode(' ',$words);
	foreach($words_array as $word)
	{
		insertDictionaryWord($word);
	}
	
}	
function getOurSoundexForWord($word)
{
	if(validateForNull($word))
	{
	    $word=clean_data($word);
		$word = ucwords(strtolower($word));
		$word=str_ireplace('W','V',$word);
		$word="a".$word;
		return soundex($word);
	}
}	

function insertDictionaryWord($word){
	
	try
	{
		$word=clean_data($word);
		$soundex = getOurSoundexForWord($word);
		$word = clean($word);
		
		if(validateForNull($word,$soundex) && !checkForDuplicateDictionaryWord($word) && strlen($word)>3 && !checkForNumeric($word))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_dictionary_item
		      (word, soundex, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$word', '$soundex',  $admin_id, $admin_id, NOW(), NOW())";
		  
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

function deleteDictionaryWord($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfDictionaryWordInUse($id))
		{
		$sql="DELETE FROM edms_dictionary_item
		      WHERE word_id=$id";
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

function updateDictionaryWord($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateDictionaryWord($type,$id))
		{
			
		$sql="UPDATE edms_dictionary_item
		      SET word='$type'
			  WHERE word_id=$id";
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

function getDictionaryWordById($id){
	
	try
	{
		$sql="SELECT word_id, word
		      FROM edms_dictionary_item
			  WHERE word_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getDictionaryWordNameById($id){
	
	try
	{
		$sql="SELECT word_id, word
		      FROM edms_dictionary_item
			  WHERE word_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateDictionaryWord($word,$id=false)
{
	    if(validateForNull($word))
		{
		$sql="SELECT word_id
		      FROM edms_dictionary_item
			  WHERE word='$word'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND word_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfDictionaryWordInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			edms_vehicle
			WHERE word_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
}

function getSoundexStringForWords($words)
{
	$ret_soundex_array = array();
	$words_array = explode(' ',$words);
	foreach($words_array as $word)
	{
		if(validateForNull($word) &&  strlen($word)>=3 && !checkForNumeric($word))
		{
		$soundex = getOurSoundexForWord($word);
		$ret_soundex_array[]="'".$soundex."'";
		}
		
		
	}
	return implode(',',$ret_soundex_array);
	
}

function getSimilarWordsArray($word)
{
	$result_array = array();
	$word = trim($word);
	if(validateForNull($word) &&  strlen($word)>=3 && !checkForNumeric($word))
		{
			$soundex=getSoundexStringForWords($word);
			
			$sql="SELECT word_id, word FROM edms_dictionary_item WHERE soundex IN ($soundex) ";
		
			$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{	
		$return_same_word = false;
		foreach($resultArray as $sim_word)
		{
		if($sim_word['word']==$word)
		$return_same_word=true;	
		$result_array[]=$sim_word['word'];	
		}
		if(!$return_same_word)
		$result_array[]=$word;
		return $result_array;
		}
		else
		return array($word);
		}
	else
	return array($word);	
	
}

function getsimilarWordsRegExString($words)
{
	$ret_words_string = "";
	$words_array = explode(' ',$words);
	
	for($i=0;$i<count($words_array);$i++)
	{
		    $word=$words_array[$i];
			$similar_Words = getSimilarWordsArray($word);
			
		if($i==0)
		{
		$ret_words_string=$ret_words_string.".*(".implode('|',$similar_Words).")";
		}
		else if($i<(count($words_array)-1))
		{
			$ret_words_string=$ret_words_string.".*(".implode('|',$similar_Words).")";
		}
		else
		{
		    $ret_words_string=$ret_words_string.".*(".implode('|',$similar_Words).").*";	
		}
	}

	return $ret_words_string;
}

function insertAllItemNamesToDictionary()
{
	$items=listInventoryItems();
	
	foreach($items as $item)
	{
		$item_name = $item['item_name'];
		insertDictionaryWords($item_name);
		$item_name = $item['alias'];
		insertDictionaryWords($item_name);
	}
	
	$items=listNonStockItems();
	
	foreach($items as $item)
	{
		$item_name = $item['item_name'];
		insertDictionaryWords($item_name);
		$item_name = $item['alias'];
		insertDictionaryWords($item_name);
	}
	
}	
?>