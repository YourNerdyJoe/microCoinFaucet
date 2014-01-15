<?php
$sqlhost = "";
$sqluser = "";
$sqlpass = "";
$sqldbname = "";
$rpcuser = "microcoinrpc";
$rpcpass = "mrcpass";
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
<center>MRC in faucet:
<?php
//Script made by Smiba from doges.org - http://bartstuff.eu/doge/
//DO NOT REMOVE MADE BY! I'm trying to help here, so just leaving 1 line would be the minimum you could do for me
//Seriously lots of credit to Smiba for the base scripts .. modifications added by unklStewy of doges.org - http://www.w3msg.net
 require_once 'jsonrpcphp/jsonRPCClient.php';
 $mrc = new jsonRPCClient("http://$rpcuser:$rpcpass@$rpcipport/");
 print_r($mrc->getbalance());

echo "</center><br/> - Please Donate:";
	print_r($mrc->getaccountaddress(""));
echo "<br/>";

$username = $_POST['address'];
$ip = $_SERVER['REMOTE_ADDR'];
if(!empty($username)) {
        if($mrc->getbalance() < 20){
                echo "Dry faucet, please donate";
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
                                $amount = rand(1,10);
                                $mrc->sendtoaddress($username, $amount);
                                echo "You've been sent ";
                                echo $amount;
                                echo " MRC!";

                        }else{
                                echo "Oops! You can get new MRC every 8 hours!";
                        }
                }
        }
}
?>
<br />
<Form Name="form1" Method="POST" ACTION="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
<INPUT TYPE="Text" VALUE="<?php echo $_POST['address']; ?>" NAME = "address">
<INPUT TYPE="Submit" Name="Submit" VALUE="Send">
<br/>
  Much Awesome Faucet Script  
</FORM>
<p>
Get the source code on <a href="https://github.com/YourNerdyJoe">GitHub</a>!
</p>
<p>
Forked from unklStewy's <a href="https://github.com/grimd34th/DogeFaucet">DogeFaucet</a>
</p>
<div id="logo">
<img src="image/mrc-logo.png"  width="300" height="300" alt="microCoin logo" />
</div>
</div>
</body>
</html>
