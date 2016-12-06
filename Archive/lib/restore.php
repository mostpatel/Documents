<?php
require_once('cg.php');
require_once('bd.php');
require_once('bd1.php');
require_once('backup.php');
require_once('vehicle-type-functions.php');

$thisFile2 = str_replace('\\', '/', __FILE__);
$srvRoot2  = str_replace('lib/restore.php', '', $thisFile2);

define('RESTORE_ROOT', $srvRoot2);
function restore($file){

 global $dbHost, $dbUser, $dbPass, $dbName;

if(isset($file['sqlFile']['tmp_name']) && trim($file['sqlFile']['tmp_name'])!="")	
{

  if (trim($file['sqlFile']['tmp_name']) != '') {
        // get the image extension
        $ext = substr(strrchr($file['sqlFile']['name'], "."), 1); 
 }
  
if($ext=="sql")
{  

backup_tables('*','restore_checkpoint');
		
$sql1="SELECT table_name FROM INFORMATION_SCHEMA.TABLES
WHERE table_schema = '".$dbName."'";
$result=dbQuery($sql1);
$resultArray=dbResultToArray($result);

$sql="SET FOREIGN_KEY_CHECKS=0;";
foreach($resultArray as $re)
{
	$table_name=$re[0];
	$sql.="
TRUNCATE $table_name;
";
}
$sql.="SET FOREIGN_KEY_CHECKS=1;";


dbMultiQuery($sql,$dbHost, $dbUser, $dbPass, $dbName);
$filename="restore". md5(rand() * time()).".sql";
move_uploaded_file($file['sqlFile']['tmp_name'],RESTORE_ROOT.$filename);
$query = file_get_contents(RESTORE_ROOT.$filename);



/* execute multi query */
if (dbMultiQuery($query,$dbHost, $dbUser, $dbPass, $dbName))
{
	
	sleep(10);
     return "success";
	 
}
else 
     return "error";
}

}

return "error";
}
 
?>