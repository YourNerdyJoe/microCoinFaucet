<?php
$sqlhost = "127.0.0.1";
$sqluser = "root";
$sqlpass = "sqlroot1234joe";
$sqldbname = "faucet";
$rpcuser = "microcoinrpc";
$rpcpass = "randomrpcpassword";
$rpcipport = "127.0.0.1:44444";
?>
<!DOCTYPE html>
<html>
<head>
	<title>microCoinFaucet</title>
</head>

<body>
<div id="main">
<h1>YourNerdyJoe's microCoin Faucet</h1>
MRC in faucet:
<?php
//Script made by Smiba from doges.org - http://bartstuff.eu/doge/
//DO NOT REMOVE MADE BY! I'm trying to help here, so just leaving 1 line would be the minimum you could do for me
//Seriously lots of credit to Smiba for the base scripts .. modifications added by unklStewy of doges.org - http://www.w3msg.net
//modified and de-doged by Joseph LoManto aka YourNerdyJoe for microCoin
require_once 'jsonrpcphp/jsonRPCClient.php';
$mrc = new jsonRPCClient("http://$rpcuser:$rpcpass@$rpcipport/");
$balance = $mrc->getbalance();
if($balance>120){
print_r($balance);
}else{
echo "Dry faucet, please donate";
}

echo "<br/>  Faucet Donation:";
	print_r($mrc->getaccountaddress(""));
echo "<br/>";

$username = $_POST['address'];
$ip = $_SERVER['REMOTE_ADDR'];
if(!empty($username)) {
        if($mrc->getbalance() <= 120){
               echo "<h4>Dry faucet, please donate</h4>";
        }else{
                $check = $mrc->validateaddress($username);
                if($check["isvalid"] == 1){
                        mysql_connect($sqlhost, $sqluser, $sqlpass)or die("cannot connect to server - Sorry");
                        mysql_select_db($sqldbname)or die("cannot select DB");
                        $time=time();
                        // echo time();
						$time_check=$time-28800; //SET TIME 8 HOURS
                        $sql4="DELETE FROM users WHERE time<$time_check";
                        $result4=mysql_query($sql4);
                        $sql=sprintf("SELECT * FROM users WHERE address='%s' OR ip='$ip'",
                        mysql_real_escape_string($username));
                        $result=mysql_query($sql);
                        $count=mysql_num_rows($result);
                        if($count=="0"){
                                $sql1=sprintf("INSERT INTO users(address, time, ip)VALUES('%s', '$time', '$ip')",
                                mysql_real_escape_string($username));
                                $result1=mysql_query($sql1);
                                $amount = rand(80,120);
				if($amount>$balance){
					$amount = $balance;
				}
                                $mrc->sendtoaddress($username, $amount);
                                echo "<h4>You've been sent $amount MRC!</h4>";

                        }else{
                                echo "Oops! You have already submitted recently. <br/>You can come back for new MRC every 8 hours!";
                        }
                }
        }
}
?>
<br />
<Form Name="form1" Method="POST" ACTION="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
Your Address: <INPUT TYPE="Text" VALUE="<?php echo $_POST['address']; ?>" NAME = "address">
<INPUT TYPE="Submit" Name="Submit" VALUE="Send">
<br/>  
</FORM>
<p>
Get the source code on <a href="https://github.com/YourNerdyJoe">GitHub</a>!
</p>
<p>
Forked from unklStewy's <a href="https://github.com/grimd34th/DogeFaucet">DogeFaucet</a>
</p>
<div id="logo">
<img src="mrc-logo.png"  width="300" height="300" alt="microCoin logo" />
</div>
</div>
</body>
</html>
