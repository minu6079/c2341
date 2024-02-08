<?php
function authenticate ($db,$ucid,$pass)
{
    $hash = password_hash($pass, PASSWORD_DEFAULT);
	echo "<br>hash $hash<br>";

    $up = "update users set hash = '$hash' where ucid = '$ucid' and pass = '$pass'";
    ($update = mysqli_query($db, $up)) or die(mysqli_error($db));

    $s = "select * from users where ucid='$ucid' and pass = '$pass'";
    echo "<br>SQL select: $s<br>";
    ($t = mysqli_query($db,$s )) or die ( mysqli_error($db));
    $num = mysqli_num_rows($t);
    if($num==0){ return false   ;}
    //else       { return true  ;}
    else {
        $r = mysqli_fetch_array($t, MYSQLI_ASSOC);
        $hash = $r['hash'];
        if (password_verify($pass,$hash)) {
            return true; 
        }
        else {
            return false;
        }
    }
}


function sanitize($db, $ucid, $pass){
	echo "regular ucid: $ucid<br>";
	echo "regular pass: $pass<br><br>";
	$ucidSanitized= filter_var($ucid, FILTER_SANITIZE_STRING);
	$passSanitized = filter_var($pass, FILTER_SANITIZE_STRING);
	echo "ucidSanitized: '$ucidSanitized'<br>";
	echo "passSanitized: '$passSanitized'<br>";
	
	$ucidValidated = $ucidSanitized ."@njit.edu";
	regex($db, $ucidSanitized);
   if($ucidValidated = filter_var($ucidValidated, FILTER_VALIDATE_EMAIL)!= false){
	   echo "validated ucid $ucidValidated<br>";
	   return $ucidValidated;
   }
	   else{
		   echo "invalidated ucid $ucidValidated<br>";
		   return $ucidValidated;
			}
}

function regex($db, $ucidSanitized){
	if($_GET["ucid"]==""){
		echo "No Match";
		exit();
	}
	
	$count = preg_match("/^[a-zA-Z]+/", $ucidSanitized, $matches);
	if($count == 0){
		echo "No match pattern<br><br>";
		echo "invalidated ucid<br>";
	}
	else{
		echo "Match<br>";
		echo "Matched pattern is:". $matches[0]."<br>";
	}
	
}


function list_transactions_wrapper($db, $ucid)
{
    $s = "select * from ztransactions where ucid='$ucid'";
    echo "<br>SQL select: $s<br>";
    ($t = mysqli_query($db, $s)) or die (mysqli_error($db));
    $num = mysqli_num_rows($t);
    echo "<br> There were $num rows retrieved from DB table. <br><br><br>";
    if( $num == 0)
    {
        echo "No transactions<br>";
    }
    echo "<table>";
    echo "<tr><th>ucid</th><th>amount</th><th>account</th><th>timestamp</th></tr>";
    while($r = mysqli_fetch_array($t, MYSQLI_ASSOC))
    {
        echo"<tr>";
        $ucid = $r["ucid"];
        $amount =$r["amount"];
        $account = $r["account"];
        $timestamp = $r["timestamp"];
        echo "<td>$ucid</td>";
        echo "<td>$amount</td>";
        echo "<td>$account</td>";
        echo "<td>$timestamp</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function perform($db, $ucid, $account, $amount)
{

  $s = "Select * from accounts where ucid = '$ucid' and account = '$account' and balance + '$amount' >= 0.00";
  ($t = mysqli_query($db, $s)) or die(mysqli_error($db));
  

  $inst = "insert into transactions values ('$ucid', '$amount', '$account', NOW())";
  ($insert = mysqli_query($db, $inst)) or die(mysqli_error($db));
 
  $up = "update accounts set balance = balance + '$amount', mostRecentTrans = NOW() where ucid = '$ucid' and account = '$account'";
  ($update = mysqli_query($db, $up)) or die(mysqli_error($db));
  $sel = "select * from accounts where ucid='$ucid' and account='$account'";
    ($g= mysqli_query($db, $sel)) or die (mysqli_error($db));
    $num = mysqli_num_rows($g);
    echo "<br> There were $num rows retrieved from DB table. <br><br><br>";
    if( $num == 0)
    {
        echo "No accounts match that UCID!<br>";
    }
    echo "<table border = 2 width = 30%>";
    echo "<tr><th>ucid</th><th>balance</th><th>account</th><th>mostRecentTrans</th></tr>";
    while($r = mysqli_fetch_array($g, MYSQLI_ASSOC))
    {
        echo"<tr>";
        $ucid = $r["ucid"];
        $balance = $r["balance"];
        $account =$r["account"];
        $mostRecentTrans = $r["mostRecentTrans"];
        echo "<td>$ucid</td>";
        echo "<td>$balance</td>";
        echo "<td>$account</td>";
        echo "<td>$mostRecentTrans</td>";
        echo "</tr>";
    }
    echo "</table>";
}


function list_accounts($db, $ucid)
{
    $s = "select * from accounts where ucid='$ucid'";
    echo "<br>SQL select: $s<br>";
    ($t= mysqli_query($db, $s)) or die (mysqli_error($db));
    $num = mysqli_num_rows($t);
    echo "<br> There were $num rows retrieved from DB table. <br><br><br>";
    if( $num == 0)
    {
        echo "No accounts match that UCID!<br>";
    }
    echo "<table border = 2 width = 30%>";
    echo "<tr><th>ucid</th><th>balance</th><th>account</th><th>mostRecentTrans</th></tr>";
    while($r = mysqli_fetch_array($t, MYSQLI_ASSOC))
    {
        echo"<tr>";
        $ucid = $r["ucid"];
        $balance = $r["balance"];
        $account =$r["account"];
        $mostRecentTrans = $r["mostRecentTrans"];
        echo "<td>$ucid</td>";
        echo "<td>$balance</td>";
        echo "<td>$account</td>";
        echo "<td>$mostRecentTrans</td>";
        echo "</tr>";
    }
    echo "</table>";
}


function reset_account($db, $ucid, $account)
{
    $up = "update accounts set balance = '0', mostRecentTrans = NOW() where ucid='$ucid' and account='$account'";
    $del = "delete from transactions where ucid='$ucid' and account='$account'";
	($t= mysqli_query($db, $up)) or die (mysqli_error($db));
	($s= mysqli_query($db, $del)) or die (mysqli_error($db));
    echo "Re-initialized the account: $account and deleted corresponding transactions.";
	$sel = "select * from accounts where ucid='$ucid' and account = '$account'";
    ($g= mysqli_query($db, $sel)) or die (mysqli_error($db));
    $num = mysqli_num_rows($g);
    echo "<br> There were $num rows retrieved from DB table. <br><br><br>";
    if( $num == 0)
    {
        echo "No accounts match that UCID!<br>";
    }
    echo "<table border = 2 width = 30%>";
    echo "<tr><th>ucid</th><th>balance</th><th>account</th><th>mostRecentTrans</th></tr>";
    while($r = mysqli_fetch_array($g, MYSQLI_ASSOC))
    {
        echo"<tr>";
        $ucid = $r["ucid"];
        $balance = $r["balance"];
        $account =$r["account"];
        $mostRecentTrans = $r["mostRecentTrans"];
        echo "<td>$ucid</td>";
        echo "<td>$balance</td>";
        echo "<td>$account</td>";
        echo "<td>$mostRecentTrans</td>";
        echo "</tr>";
    }
    echo "</table>";
}





function list_number_transactions($db, $ucid, $account, $n)
{
    $s = "select * from transactions where ucid='$ucid' and account='$account' ORDER BY timestamp DESC LIMIT $n";
    echo "<br>SQL select: $s<br>";
    ($t= mysqli_query($db, $s)) or die (mysqli_error($db));
    $num = mysqli_num_rows($t);
    echo "<br> There were $num rows retrieved from DB table. <br><br><br>";
    if( $num == 0)
    {
        echo "No transactions<br>";
    }
    echo "<table border = 2 width = 30%>";
    echo "<tr><th>ucid</th><th>amount</th><th>account</th><th>timestamp</th></tr>";
    while($r = mysqli_fetch_array($t, MYSQLI_ASSOC))
    {
        echo"<tr>";
        $ucid = $r["ucid"];
        $amount =$r["amount"];
        $account = $r["account"];
        $timestamp = $r["timestamp"];
        echo "<td>$ucid</td>";
        echo "<td>$amount</td>";
        echo "<td>$account</td>";
        echo "<td>$timestamp</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>