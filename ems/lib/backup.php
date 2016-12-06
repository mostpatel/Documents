<?php require_once('cg.php');
require_once('bd.php');

function backup_tables($tables='*',$prefix=false)
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
		
		$result=dbQuery('SELECT * FROM '.$table);
		$num_fields=mysql_num_fields($result);
		//$return1.='DROP TABLE IF EXISTS '.$table.';';
		$row2=mysql_fetch_row(dbQuery('SHOW CREATE TABLE '.$table));
		//$return1="\n\n".$row2[1].";\n\n";
		
		for($i=0;$i<$num_fields;$i++)
		{
			while($row=mysql_fetch_row($result))
			{
				$return.='INSERT INTO '.$table.' VALUES(';
				for($j=0;$j<$num_fields;$j++)
				{
					
					$row[$j]=addslashes($row[$j]);
					if($table=="fin_file" && $row[$j]=="")
					$row[$j]=NULL;
					$row[$j]=preg_replace('/\\\n/','\\\\\n',$row[$j]);
					
					if(isset($row[$j]))
					{
						if(($table=="fin_file" || $table=="fin_loan_emi") && $row[$j]=="")
						{	
						$return.="NULL";
						}
						else
						$return.='"'.$row[$j].'"';
					}
					else if($table=="fin_file")
					{
						$return.=NULL;
						}
					else
					{
							$return.='""';
					}
					if($j<($num_fields-1))
					{
							$return.=',';
					}
					
				}
				$return.=");\n";
				
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
	
	$fullName=SRV_ROOT.'backups/'.$fileName;
	$handle=fopen($fullName,'w+');
	fwrite($handle,$return);
	fclose($handle);
	return saveFile($fileName);
}
function saveFile($file)
{
	$sql="INSERT INTO ems_backups(date,file_name) VALUES (NOW(),'$file')";
	dbQuery($sql);
	return "success";
}
function listBackups()
{
	$sql="SELECT * FROM ems_backups ORDER BY date DESC";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;
}
?>