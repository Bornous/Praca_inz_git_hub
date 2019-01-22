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
	$id_user
	$sql="";
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

?>