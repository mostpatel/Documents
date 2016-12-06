<?php
$start    = '2010-12-02';
$start = date('Y-m-01',strtotime($start));



$end    = '2012-06-05';
$end = date('Y-m-01',strtotime('+1 month',strtotime($end)));

echo $end;

$mont_year_array=array();
$i=0;
do
{

$month=date('m',strtotime($start));	
$year=date('Y',strtotime($start));

	$mont_year_array[$i]['month']=$month;
	$mont_year_array[$i]['year']=$year;
    
	$start=date('Y-m-d',strtotime('+1 month',strtotime($start)));	
$i++;
	
}while(strtotime($start)!=strtotime($end));


print_r($mont_year_array);?>