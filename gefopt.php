<html>
  <head>
    <title>1 April</title>
  </head>
  <body>
    Kijk eens naar de datum <img src="http://www.spelcodes.nl/img/smileys/SMILEY10.GIF" alt="">
  </body>
</html>

<?php  
$file= "count.txt"; 
$file_open = fopen($file, "a+"); 
define ('NEWLINE', "\n", true);  

$day =date("d"); 
$month =date("m"); 
$year =date("Y"); 
$date="$day-$month-$year"; 

$hour = date("H"); 
$minuit = date("i"); 
$time="$hour:$minuit"; 

if ($_SERVER['HTTP_X_FORWARDED_FOR'] == "") {   
$ip = getenv($_SERVER['REMOTE_ADDR']);   
}   
else {   
$ip = getenv($_SERVER['HTTP_X_FORWARDED_FOR']);   
} 
    $i= file_get_contents($file); 
    $i_extra= $i+1; 
echo "In totaal ".$i_extra." unieke bezoekers."; 

    $inhoud="$i_extra|$ip|$time|$date".NEWLINE; 
    fwrite($file_open, "$inhoud");  

fclose($file_open); 
?>