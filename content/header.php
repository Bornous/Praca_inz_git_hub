<?php require_once 'general_config.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
	if($_SERVER["SCRIPT_NAME"]== "/user_panel/user_panel.php") 
		echo '<link rel="stylesheet" href="../main_style.css" type="text/css" media="screen and (min-width:750px)" >
		<link rel="stylesheet" href="../CSS/smaller_style.css" type="text/css" media="screen and (max-width:749px)">'; 
	elseif ($_SERVER["SCRIPT_NAME"]== "/user_panel/group_choice.php")
		echo '<link rel="stylesheet" href="../main_style.css" type="text/css" media="screen and (min-width:750px)" >
		<link rel="stylesheet" href="../CSS/smaller_style.css" type="text/css" media="screen and (max-width:749px)">'; 
	else 
		echo '<link rel="stylesheet" href="main_style.css" type="text/css" media="screen and (min-width:750px)" >
		<link rel="stylesheet" href="CSS/smaller_style.css" type="text/css" media="screen and (max-width:749px)">';
?>
		
		<link href="https://fonts.googleapis.com/css?family=Spectral" rel="stylesheet"> 
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		
	</head>
	<body>