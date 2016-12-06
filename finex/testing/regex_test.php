<?php
$temp_array =  array(4590 => 3, 4690 => 5);
echo array_key_exists(4590,$temp_array);
exit;
 $date_01_01_1900 = date("Y-m-d",mktime(0,0,0,1,1,1970));
 
 $days_between_1900_1970 = 25569;
 $loan_approval_date_excel_serial_no = 39307;
 $days_from_1970 = $loan_approval_date_excel_serial_no - $days_between_1900_1970;
 $loan_approval_date_add_days = $date_01_01_1900.' + '.$days_from_1970.' Days';
 echo $loan_approval_date_add_days;
$loan_approval_date = date("d/m/Y",strtotime($loan_approval_date_add_days));
echo $loan_approval_date;
exit;		
echo preg_match("/[A-Z]+[\s\.\-]*KHEDA[\(D\)]*/","S/O JAVEDAHMED 4981,ASTANA SOCIETY (H) NR NADIYAD NADIAD KAIRA. - KHEDA (D)GUJARAT -68920"); 
/*
$customer_address = preg_replace( '/AHAMEDABAD/','AHMEDABAD','S/O PRAHLAD BHAI BAROT AKASH DEEP APARTMERNT NR AKBAR NR NAVA VADEJ AHAMEDABAD.P.O. - AHAMEDABAD(D) GUJARAT');
echo $customer_address;
exit; */
$customer_phone_array = array();
 preg_match( '/AHMEDABAD|KHEDA|GANDHINAGAR|MEHSANA|HIMMATNAGAR|BHAVNAGAR|SURENDRANAGAR|SABARKANTHA|RAJKOT|BANASKANTHA/', "S/O PRAHLAD BHAI BAROT
AKASH DEEP APARTMERNT
NR AKBAR NR NAVA VADEJ
KHEDA
AHMEDABAD.P.O.380015 
 (D) GUJARAT
Ph. : 9904641031
Mob.: 9998661178
Mob.: 9998661178/9824143009",$customer_phone_array);

print_r($customer_phone_array); ?>