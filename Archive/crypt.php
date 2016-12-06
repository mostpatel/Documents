<?php
$ma="";
exec("ipconfig /all", $out, $res); foreach (preg_grep('/^\s*Physical Address[^:
]*:\s*([0-9a-f-]+)/i', $out) as $line) { $ma=$ma.substr(strrchr($line, ' '), 1); } 
$ma=crypt($ma,"sanket24@gmail.com");
echo "mac address: ".$ma."<br>";
$key=pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
 $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $plaintext_utf8 = utf8_encode("2016-12-01");
   
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                 $plaintext_utf8, MCRYPT_MODE_CBC, $iv);
	$ciphertext = $iv . $ciphertext;
	$ciphertext_base64 = base64_encode($ciphertext);
	echo $ciphertext_base64."<br>";							 
 $ciphertext_dec = base64_decode($ciphertext_base64);
   $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);
	 $plaintext_utf8_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
                                         $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    
 ?>