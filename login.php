<?php
session_start();
unset( $_SESSION["login"]);
require "connect.php";
$email = "";
$name = "";
$errors = array();
include("myfunctions.php");
include("error-report-connect-db.php");
if(isset($_POST['login'])){
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$password = mysqli_real_escape_string($con, $_POST['password']);
	$check_email = "SELECT * FROM assign3 WHERE email = '$email'";
	$res = mysqli_query($con, $check_email);
	if(mysqli_num_rows($res) > 0){
	$fetch = mysqli_fetch_assoc($res);
	$fetch_pass = $fetch['password'];
	if(password_verify($password, $fetch_pass)){
	$_SESSION['email'] = $email;
	$status = $fetch['status'];
	if($status == 'verified'){
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;
		header ("refresh: 3 , url = home.php ");
	}else{
		$info = "It's look like you haven't still verify your email - $email";
		$_SESSION['info'] = $info;
		header ("refresh: 3 , url = pin1.php ");
		)
	}else{
		$errors['email'] = "Incorrect email or password!";
		}
	}else{
		$errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
		}
	}
    if(count($errors) > 0){
?>
<div class="alert alert-danger text-center">
<?php
foreach($errors as $showerror){
    echo $showerror;
}
?>
</div>
<?php
}
?>
