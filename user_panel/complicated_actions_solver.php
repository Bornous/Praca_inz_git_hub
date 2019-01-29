<?php
require_once '../content/general_config.php'; 

if(isset($_POST["group_name"])){
	$new_group = new Group();
	if( $creating_group=$new_group->create_new_one($_POST["group_name"])){
		$_SESSION["Client"]->assigment_to_a_group($new_group);
		$sql_voting_right = "UPDATE `inz_users` SET `is_allowed_to_vote` = '1' WHERE `id_user` = '".$_SESSION["Client"]->get_id_user()."'";
		$_SESSION["DB_connection"]->query($sql_voting_right);
		$_SESSION["Client"]->load_the_voting_right('1');
		header('Location: user_panel.php?login=success&group_added=true');
	}
	else{
		echo "Error with creating group:::".$creating_group;
	}
}
elseif(isset($_POST["group_user"])){
	$new_group = new Group();
	if( $joining_group=$new_group->join_one($_POST["group_user"])){
		$_SESSION["Client"]->assigment_to_a_group($new_group);
		$voting_for_right = new Voting_system("incomers%id_user%".$_SESSION["Client"]->get_id_user());
		header('Location: user_panel.php?login=success&group_exist=true');
	}
	else{
		echo "Error with creating group:::".$joining_group;
	}
}
elseif(isset($_POST["submit_quest"])){
	$id_quest=$_POST["id_quest"];
	$points_quest=$_POST["points_quest"];
	$id_user=$_SESSION["Client"]->get_id_user();
	$data_execution = date("Y-m-d G:i:s");
	$sql_submit_quest="INSERT INTO `inz_groups_history` (`id_user`,`id_quest_history`,`date_execution`,`points_rewarded`)  VALUES ('".$id_user."','".$id_quest."','".$data_execution."','".$points_quest."')";
	if( $_SESSION["DB_connection"]->query($sql_submit_quest) ){
		$id_last_execution = $_SESSION["DB_connection"]->give_insert_id();
		$sql_update_last_exe_id="UPDATE `inz_quests` SET `id_last_execution` = '".$id_last_execution."' WHERE `id_quest` = '".$id_quest."' ";
		if ($_SESSION["DB_connection"]->query($sql_update_last_exe_id) )	 echo "Udao se: ".$sql_update_last_exe_id;		
		else echo $sql_update_last_exe_id;		
	}
	else return false;
}
elseif(isset($_POST["load_new_incomers"])){
	$id_group=$_SESSION["Client"]->give_id_group();
	$id_me=$_SESSION["Client"]->get_id_user();
	$sql="SELECT * FROM `inz_users` WHERE `id_group_user`= '$id_group' AND `is_allowed_to_vote`='0' AND `id_user`<>'$id_me'";
	if($result_incomers = $_SESSION["DB_connection"]->query_arr($sql)){
		foreach($result_incomers as $an_incomer){
			echo "<div class=\"an_incomer\">";
			echo'<div class="an_incomer_name">Nazwa: '.$an_incomer["name_user"].'</div>';
			echo'<div class="an_incomer_email">Email: '.$an_incomer["login_user"].'</div>';
			echo "</div>";
				
			
		}
	}
	else{
		echo "<div class=\"a_list_of_incomers\">Brak zgłoszeń</div>";
	}
}
elseif(isset($_POST["voting_quest_add"])){
	$id_me=$_SESSION["Client"]->get_id_user();
	$voting_process_quest_add = new Voting_system("quest_add%id_quest%".$_POST["quest_add"]);
	if($_POST["voting_quest_add"]=="yes")	$voting_process_quest_add->voting(1);
	else	$voting_process_quest_add->voting(0);
	header('Location: user_panel.php?redirect=vote_page_quests');		
}
elseif(isset($_POST["create_quest_form"])){
	echo '
	<form action="#" method="POST">
	<input type="hidden" name="action_name" value="quests_page">
	<div class="return_button_new_quest" onclick="javascript:this.parentNode.submit();"><span><i class="fas fa-arrow-circle-left"></i></span><span>Wróć</span></div>
	</form>
	<div class="form_adding_quest">
		<form action="complicated_actions_solver.php" method="post">
			<input type="hidden" name="add_a_quest" value="true">
			<div class="login_group">
			<span>Nazwa: </span>
							<input class="login_control" name="quest_name" placeholder="Wpisz nazwę zadania" type="text">
			</div>
			<div class="login_group">
			<span>Opis: </span>
							<textarea class="login_control" name="quest_descr" rows="7" cols="60" placeholder="Tutaj zamieść opis zadania"></textarea>
			</div>
			<div class="login_group datetime_inputs">
							<p><span>Wybierz co jaki czas zadanie ma się ponownie pojawiać:</span></p>
							<label>Miesięcy: <input type="number" name="quest_renewable_period_month" min="0" max="12" step="1" value="0"></label>
							<label>Dni: <input type="number" name="quest_renewable_period_day" min="0" max="31" step="1" value="1"></label>
							<label>Godzin: <input type="number" name="quest_renewable_period_hour" min="0" max="23" step="1" value="0"></label>
							<label>Minut: <input type="number" name="quest_renewable_period_min" min="0" max="59" step="1" value="0"></label>
							<input type="hidden" 	name="quest_renewable_period_sec" value="0">
			</div>
			<div class="login_group">
							<label>Liczba punktów za zrobienie zadania: <input type="number" name="quest_points" min="1"  max="999999" step="1" value="100"></label>
			</div>
			<input id="submitbutton" type="submit" value="Wyślij propozycję dodania zadania" class="login_submit_button">
		</form>
	</div>
	
	';
}
elseif(isset($_POST["add_a_quest"])){
	
	$renewable_period = ($_POST["quest_renewable_period_month"] < 10 ? "0".$_POST["quest_renewable_period_month"] : $_POST["quest_renewable_period_month"])."-".($_POST["quest_renewable_period_day"] < 10 ?"0".$_POST["quest_renewable_period_day"] : $_POST["quest_renewable_period_day"])." ".($_POST["quest_renewable_period_hour"] < 10 ?"0".$_POST["quest_renewable_period_hour"] : $_POST["quest_renewable_period_hour"]).":".($_POST["quest_renewable_period_min"] < 10 ? "0".$_POST["quest_renewable_period_min"] : $_POST["quest_renewable_period_min"]).":00";
	$id_group_user = $_SESSION["Client"]->give_id_group();

	
	$params_to_bind = array("issis",&$id_group_user,&$_POST["quest_name"],&$_POST["quest_descr"],&$_POST["quest_points"],&$renewable_period);

	$sql_insert="INSERT INTO `inz_quests` (`id_group_user`, `name_quest`, `descr_quest`, `points_quest`, `renewable_period_quest`, `activation_status_quest`) VALUES(?,?,?,?,?,'2')";
	if($_SESSION["DB_connection"]->prepare_bind_param($sql_insert, $params_to_bind)){
		$id_quest = $_SESSION["DB_connection"]->give_insert_id();
		$start_voting_procedure= new Voting_system("quest_add%id_quest%$id_quest");
		//print_r($start_voting_procedure);
		header('Location: user_panel.php?redirect=quests_page&quest_adding=success');
	}
	else{
		header('Location: user_panel.php?redirect=quests_page&quest_adding=failure');
	}
}
elseif(isset($_POST["edit_a_quest"])){
	
	$id_quest = $_POST["edit_quest_id"];
	$renewable_period = ($_POST["edit_renewable_period_month"] < 10 ? "0".$_POST["edit_renewable_period_month"] : $_POST["edit_renewable_period_month"])."-".($_POST["edit_renewable_period_day"] < 10 ?"0".$_POST["edit_renewable_period_day"] : $_POST["edit_renewable_period_day"])." ".($_POST["edit_renewable_period_hour"] < 10 ?"0".$_POST["edit_renewable_period_hour"] : $_POST["edit_renewable_period_hour"]).":".($_POST["edit_renewable_period_min"] < 10 ? "0".$_POST["edit_renewable_period_min"] : $_POST["edit_renewable_period_min"]).":00";
	$edit_str_quest="`name_quest`='".$_POST["edit_name"] ."', `descr_quest`='".$_POST["edit_descr"] ."', `points_quest`='".$_POST["edit_points"] ."', `renewable_period_quest`='".$renewable_period ."'";
	
	$params_to_bind = array("s",&$edit_str_quest);

	$sql_update="UPDATE `inz_quests` SET `edit_str_quest`=? WHERE `id_quest`='$id_quest'";
	if($_SESSION["DB_connection"]->prepare_bind_param($sql_update, $params_to_bind)){
		$start_voting_procedure= new Voting_system("quest_edit%id_quest%$id_quest");
		header('Location: user_panel.php?redirect=vote_page_quests&quest_edit=success');
	}
	else{
		header('Location: user_panel.php?redirect=vote_page_quests&quest_edit=failure');
	}
}
elseif(isset($_POST["voting_quest_edit"])){
	
	$id_me=$_SESSION["Client"]->get_id_user();
	$voting_process_quest_edit = new Voting_system("quest_edit%id_quest%".$_POST["quest_edit"]);
	if($_POST["voting_quest_edit"]=="yes")	$voting_process_quest_edit->voting(1);
	else	$voting_process_quest_edit->voting(0);
	header('Location: user_panel.php?redirect=vote_page_quests');	
}
elseif(isset($_POST["voting_quest_delete"])){
	
	$id_me=$_SESSION["Client"]->get_id_user();
	$voting_process_quest_edit = new Voting_system("quest_delete%id_quest%".$_POST["quest_delete"]);
	if($_POST["voting_quest_delete"]=="yes")	$voting_process_quest_edit->voting(1);
	else	$voting_process_quest_edit->voting(0);
	header('Location: user_panel.php?redirect=vote_page_quests');	
}
elseif(isset($_POST["voting_incomers"])){
	
	$id_me=$_SESSION["Client"]->get_id_user();
	$voting_process_quest_edit = new Voting_system("incomers%id_user%".$_POST["incomer_id"]);
	if($_POST["voting_incomers"]=="yes")	$voting_process_quest_edit->voting(1);
	else	$voting_process_quest_edit->voting(0);
	header('Location: user_panel.php?redirect=vote_page');	
}

?>