<?php
require_once "../../../../lib/cg.php";
require_once "../../../../lib/bd.php";
require_once "../../../../lib/common.php";
require_once "../../../../lib/team-functions.php";


if(isset($_GET['catId']))
{
$catId=$_GET['catId'];
$result=array();
$result=getTeamDetailsByTeamId($catId);
$str="";

$team_member_ids = $result['member_ids'];
$team_member_names = $result['member_names'];
 
$member_id_array = (explode(' , ',$team_member_ids));
$member_name_array = (explode(' , ',$team_member_names));

for($i=0; $i<count($member_id_array); $i++)
{
	 $member_id = $member_id_array[$i];
	 $member_name = $member_name_array[$i];
	 $str=$str . "\"$member_id\"".",". "\"$member_name\"".",";
	 
}

$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

}

?>