<?php 
require_once('cg.php');
require_once('bd.php');
require_once("common.php");

function backup_tables($tables='*',$prefix=false,$path=false)
{
	
	$return="SET FOREIGN_KEY_CHECKS = 0; ";
	
	if($tables=='*')
	{
	$tables=array();
	$result=dbQuery('SHOW TABLES');
	$resultArray=dbResultToArray($result);
	$tables=array();
	foreach($resultArray as $re)
	{
		$tables[]=$re[0];
		}
	
	}
	else
	{
		$tables=is_array($tables)?$tables:explode(',',$tables);
	}
	foreach($tables as $table)
	{   
		$fields = getFieldsForTable($table);
		
		$fields_name_null_array = array();
		
		foreach($fields as $field)
		{
						
						if($field['Null']=="YES")
						$fields_name_null_array[]=$field['Field'];
		}
	
	
		$result=dbQuery('SELECT * FROM '.$table);
		
		$num_fields=mysql_num_fields($result);
		$num_of_rows = dbNumRows($result);
		//$return1.='DROP TABLE IF EXISTS '.$table.';';
		$row2=mysql_fetch_row(dbQuery('SHOW CREATE TABLE '.$table));
		//$return1="\n\n".$row2[1].";\n\n";
		
		for($i=0,$num=0,$k=0;$i<$num_fields;$i++)
		{
			
			while($row=mysql_fetch_assoc($result))
			{
				if($num==50000)
				$num=0;
				
				if($num==0)
				$return.='INSERT INTO '.$table.' VALUES ';
				
				$return.='(';
				$j=0;
				
				foreach($row as $key => $value)
				{
					
					$value=addslashes($value);
					$value=preg_replace('/\\\n/','\\\\\n',$value);
					$key_null=false;
								
					
					if(isset($key))
					{
						if(!validateForNull($value) && in_array($key,$fields_name_null_array))
						{	
						$return.="NULL";
						}
						else
						$return.='"'.$value.'"';
					}
					else
					{
							$return.='""';
					}
					if($j<($num_fields-1))
					{
							$return.=',';
					}
					$j++;	
				}
				
				if($num==49999 || $k==($num_of_rows-1))
				$return.=");\n";
				else
				$return.="),\n";
				
				$num++;
				$k++;
			}
		}
	$return.="\n\n\n";
	
	}
	
	if(isset($prefix) && $prefix!="" && $prefix!=false)
	{
		$fileName=$prefix;
		}
	else
	$fileName="";	
	$fileName.='dbbackup'.time().(md5(implode(',',$tables))).'.sql';
	
	if($path==false)
	{
	$fullName=SRV_ROOT.'backups/'.$fileName;
	}
	else
	{
	$fullName=$path."\\".$fileName;
	}
	
	$handle=fopen($fullName,'w+');
	fwrite($handle,$return);
	fclose($handle);
	if($path!=false)
	return "success";
	return saveFile($fileName);
}
function saveFile($file)
{
	$sql="INSERT INTO fin_backups(date,file_name) VALUES (NOW(),'$file')";
	dbQuery($sql);
	return "success";
}
function listBackups()
{
	$sql="SELECT * FROM fin_backups ORDER BY date DESC";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;
}
function getFieldsForTable($table)
{
	$sql="SHOW COLUMNS FROM ".$table;
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;
}
?>