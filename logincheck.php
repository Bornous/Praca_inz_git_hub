<?php
require_once 'content/general_config.php'; 


$login_user = isset($_POST['login_user']) ? $_POST['login_user'] : "";
$password_user = isset($_POST['password_user']) ? $_POST['password_user'] : "";
$what_to_do = isset($_POST['what_to_do']) ? $_POST['what_to_do'] : "login";
if(  $what_to_do == "register_account" ) $name_user=$_POST['name_user'];
else	 $name_user="";

$action_login = new Login_system( $what_to_do ,$login_user, $password_user,$name_user);

$action_login->turn_on();

?>