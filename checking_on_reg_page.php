<?php
require_once 'content/general_config.php'; 

if(isset($_POST["password"])){
	if(isset($_POST["email"]) AND $_POST["password"]=="df0090a4c59f141d203ef0dbd5710fc0"){
		$email =  trim($_POST["email"]);
		$sql = "SELECT * FROM `inz_users` WHERE `login_user`='".$email."'";
		$result=$_SESSION["DB_connection"]->query_arr($sql);
		if($result == FALSE){
			echo 0;
		}
		else echo 1;
	}
	else header("Location: index.php");
	
}
else header("Location: index.php");
?>