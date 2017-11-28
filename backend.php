<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "pass";
$dbname = "Lisabase";
$store_num = 10;
$display_num = 10;

error_reporting(E_ALL);
// header("Content-type: text/xml");
header("Cache-Control: no-cache");

$dbconn=mysqli_connect("localhost","root","pass","Lisabase");
// if (($dbconn))
// {
// echo "ctdfdas: " ;
// }else{
// 	echo "fail";
// }
// mysqli_select_db($dbname,$dbconn);

// foreach($_POST as $key => $value){
// 	$$key = mysqli_real_escape_string($value, $dbconn);
// }


// $newpers=$_POST['name'];
// $newpers=mysqli_real_escape_string($dbconn,$newpers);
// $query = "INSERT INTO messages (`user`,`msg`,`time`) 
// 	             VALUES ('lisa', '$newpers' ,1)";
// mysqli_query($dbconn, $query);


$name=$_POST['name'];
$name=mysqli_real_escape_string($dbconn,$name);
$message=$_POST['message'];
$message=mysqli_real_escape_string($dbconn,$message);
$action=$_POST['action'];
$action=mysqli_real_escape_string($dbconn,$action);
$time=$_POST['time'];
$time=mysqli_real_escape_string($dbconn,$time);


if(@$action == "postmsg"){
	mysqli_query($dbconn, "INSERT INTO messages (`user`,`msg`,`time`) 
	             VALUES ('$name','$message',".time().")");
	 
	mysqli_query($dbconn, "DELETE FROM messages WHERE id <= ".
				(mysqli_insert_id($dbconn)-$store_num));
}

 // $messages = mysqli_query($dbconn,"SELECT user,msg FROM messages  WHERE time > '".$curtime."' ORDER BY id ASC LIMIT 10");

// if (mysqli_num_rows($messages) == 0){
// 	$status_code = 2;
// } 
// else {$status_code = 1;}
// if (mysqli_num_rows($messages) == 0){
// 	$status_code = 2;
// } 
// else {$status_code = 1;}
$messages = mysqli_query($dbconn, "SELECT user,msg
						 FROM messages
						 WHERE time>$time
						 ORDER BY id ASC
						 LIMIT $display_num");

if(mysqli_num_rows($messages) == 0) $status_code = 2;
else $status_code = 1;

 
echo "<?xml version=\"1.0\"?>\n";
echo "<response>\n";
echo "\t<status>$status_code</status>\n";
echo "\t<time>".time()."</time>\n";
if($status_code == 1){  
	while($message = mysqli_fetch_array($messages)){
		$message['msg'] = htmlspecialchars(stripslashes($message['msg']));
		echo "\t<message>\n";
		echo "\t\t<author>$message[user]</author>\n";
		echo "\t\t<text>$message[msg]</text>\n";
		echo "\t</message>\n";
	}
}
echo "</response>";

mysqli_close($dbconn);
?>