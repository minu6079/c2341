<?php
session_start();
//REMEMBER THIS IS PSEUDO CODE I AM WRITING, NOT COMLETE PHP 
unset( $_SESSION["pinpassed"] );
/*  
code to redirect  back to KB1.php
if $_SESSION["pin"]  not defined
*/
if (!isset($_SESSION["pin"])){
  echo "<br> SUCCESS.";
  header ("refresh: 2 , url = KP1.php ");
;}
/*
Checks if you submitted correct pin sent to your email.
If does not match then redirects to pin1.php
If does  match then redirects sets  session "pinpassed" to true and redirects to services.1.php
*/


$PIN=$_GET["pin"];
$PIN=mysqli_real_escape_string($db, $PIN );
echo"<br>pin is:$PIN<br> ";



if($PIN ==$_SESSION["pin"] )
{

    $_SESSION["pinPassed"] =True;
    header("refresh:4  ,url=services1.php ");
    exit();
} 
else
{
    header("refresh:4  ,url = pin1.php ");
    exit();
}


?>