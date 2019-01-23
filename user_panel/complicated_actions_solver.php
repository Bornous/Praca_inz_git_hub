<?php
require_once '../content/general_config.php'; 

if(isset($_POST["group_name"])){
	$new_group = new Group();
	if( $creating_group=$new_group->create_new_one($_POST["group_name"])){
		$_SESSION["Client"]->assigment_to_a_group($new_group);
		$sql_voting_right = "UPDATE `inz_users` SET `is_allowed_to_vote` = '1' WHERE `id_user` = '".$_SESSION["Client"]->get_id_user()."'";
		$_SESSION["DB_connection"]->query($sql_voting_right);
		$_SESSION["Client"]->load_the_voting_right( '1');
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
elseif(isset($_POST["load_all_group_quests"])){
	$id_group=$_SESSION["Client"]->give_id_group();
	$sql="SELECT * FROM `inz_quests` WHERE `id_group_user`= '$id_group'";
	if($result_quests = $_SESSION["DB_connection"]->query_arr($sql)){
		foreach($result_quests as $a_quest){
			$the_quest = new Quest($a_quest);
			echo "<div class=\"a_quest_to_edit\">";
			$the_quest->display_quest();
			echo "</div>";
			$array_of_quests[] = $the_quest ;
				
			
		}
	}
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
elseif(isset($_POST["create_quest_form"])){
	echo '
	<div class="form_adding_quest">
		<form action="complicated_actions_solver.php" method="post">
			<input type="hidden" name="add_a_quest" value="true">
			<div class="login_group">
							<input class="login_control" name="quest_name" placeholder="Nazwa zadania" type="text">
			</div>
			<div class="login_group">
			<spam>Opis zadania:</spam>
							<textarea name="decr_quest" rows="7" cols="60">Enter your text here...</textarea>
			</div>
			<input id="submitbutton" type="submit" value="Accept" class="login_submit_button">
		</form>
	</div>
	
	';
}

?>