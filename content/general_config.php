<?php 
/* GENERAL THINGS TO SET UP */
/* automatical class loading */
spl_autoload_register(function ($class_name) {
	if($_SERVER["SCRIPT_NAME"]== "/user_panel/user_panel.php")    require_once '../classes/'.$class_name . '.php';
	elseif($_SERVER["SCRIPT_NAME"]== "/user_panel/group_choice.php" OR $_SERVER["SCRIPT_NAME"]== "/user_panel/complicated_actions_solver.php" )    require_once '../classes/'.$class_name . '.php';
	else    require_once './classes/'.$class_name . '.php';
});
date_default_timezone_set('Europe/Warsaw');
/* session start */
session_start();

/* DB connection */
$_SESSION["DB_connection"] = new DB_conn();
// Db connection is closed at the bottom of ech page 

?>