<?php
require_once("../content/header.php");
if(!isset($_POST["login_user"]) || !isset($_POST["password_user"]) ){
	return false;
}
else{
	$sql_client = "SELECT * FROM `inz_users` WHERE `login_user`='".$_POST["login_user"]."'";
	$result_client = $_SESSION["DB_connection"]->query($sql_client); 
	if ($result_client->num_rows > 0) { 
		$a_user = $result_client->fetch_assoc();
		$id_user = $a_user["id_user"];
		$hashedpassword_user_db = $a_user["password_user"];	
		$password_user_correctness = password_verify($_POST["password_user"], $hashedpassword_user_db);
								
		if ( $password_user_correctness === TRUE ){ 
			$_SESSION["Client"] = new Client($a_user["id_user"], $a_user["login_user"], $a_user["password_user"],$a_user['id_group'],$a_user['name_user']);
			if( $a_user['id_group_user']!==NULL){ 
				$_SESSION["Client"]->load_the_group( new Group( $a_user['id_group_user']));
				$_SESSION["Client"]->load_the_voting_right($a_user['is_allowed_to_vote']);
				return true;
			}
			else	return false;					
		}
		else	return false;
	} 
	else	return false;
}

?>