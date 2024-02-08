
<?php
session_start();

/*
code to redirect  back to KB1.php
if $_SESSION["KBpassed"]  not defined
*/
 
 if (!isset($_SESSION["KBpassed"])){
  echo "<br> SUCCESS.";
  header ("refresh: 2 , url = KP1.php ");
;}

include("error-report-connect-db.php");

//you only get here if u passed personal knowledge inquiry  

//must do standard functions file setup, etc

/*
 Generates random 4 digit pin and mails to yourself
 
 For instructor's grading convenience must also echo pin  here too.
 Sets session "pin"  to true pin value
 You  submit pin from your  njit mail in form below.
 */
 

$pin =mt_rand(1000,9999);

$to ="mbp64@njit.edu"; //this is where i sent my 4 digit code too.
$subject = "pin";
$message = $pin;
$headers = "MIME-Version:1.0"."\r\n";
$headers = "Content-type:text/html;charset=UTF-8".
"\r\n";
mail( $to,$subject,$message,$headers );

$_SESSION["pin"]=$pin;
echo"<br>pin is $pin for grading";


?>
<br><br><br>
<html>
<body>

<form action="pin2.php">
PIN: <input type= text name="PIN">
<input type=submit>
</form>

</body>
</html>