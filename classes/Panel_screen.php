<?php
class Panel_screen{
	private $action_name;

	
	public  function __construct() {
		if( isset($_POST["action_name"]) ){
			$this->action_name = $_POST["action_name"];
		}
		elseif( isset($_GET["redirect"]) ){
			$this->action_name =$_GET["redirect"];
		}
		else{
			$this->action_name = "ranking_page";
		}
	}
	
	public function quests_list(){
		$id_group_user =  $_SESSION["Client"]->give_id_group();
		
		$sql_search = "
		SELECT * FROM `inz_quests`
			LEFT JOIN `inz_groups_history`
				ON `inz_quests`.`id_last_execution`=`inz_groups_history`.`id_last_execution`
			WHERE
				`inz_quests`.`id_group_user`='$id_group_user'
				AND
				`inz_quests`.`activation_status_quest`='1'
				AND 
				`inz_quests`.`id_last_execution` IS NOT NULL
					
		UNION
			
		SELECT * FROM `inz_quests`	
			LEFT JOIN `inz_groups_history`
				ON `inz_quests`.`id_last_execution`=`inz_groups_history`.`id_last_execution`
			WHERE 
				`inz_quests`.`activation_status_quest`='1'
				AND
				`inz_quests`.`id_group_user`='$id_group_user'
				AND 
				`inz_quests`.`id_last_execution` IS NULL";
				
				
		if($results_quests=$_SESSION["DB_connection"]->query_arr($sql_search)){
			//echo ">>> ".$sql_search." <<<";
			//print_r($results_quests);
			foreach($results_quests as $a_quest){
				
				$the_quest = new Quest($a_quest);
				if($a_quest["id_last_execution"] === NULL){
					echo "<div class=\"new_quest active_quest\">";
					$the_quest->display_quest();
					echo "</div>";
					$array_of_quests[] = $the_quest ;
				}
				elseif( $the_quest->calculate_last_execution() ){
						
						echo "<div class=\"a_quest active_quest\">";
						$the_quest->display_quest();
						echo "</div>";
						$array_of_quests[] = $the_quest ;
					
				}
			}
		}
		
	}
	
	public function create_quests_page(){
		
		
		echo '	
					<div class="background_white hidden"></div>
					<div class="confirm_popup">
						<div class="succesfull_completion_tick"><i class="fas fa-check"></i></div>
						<div class="text_popup">
							<p>
								<span>Zadanie: </span><span id="quest_name_popup"></span>
							</p>						
							<p>
								<span>Czy potwierdzasz wykonanie zadania?</span>
							</p>
							
						</div>
						<div class = "answers_box_popup">
							<form action="#" method="POST">
							<input type="hidden" name="action_name" value="quests_page">
							<div class="answer_popup answ_yes" >Tak</div>
							</form>
							<div class="answer_popup answ_no">Nie</div>
						</div>
					</div>';
		echo '<div class="right_contener with_quests">';
		echo '<div class="add_quest"><span>Dodaj nowe zadanie</span></div>';
		$this->quests_list();		
		echo '</div>';
		echo '<div class="clear_both"></div>';
	}
	
	public function create_ranking_page(){
		$current_date = date('Y-m');
		$sql_search = "
				SELECT `inz_users`.`id_user`, `inz_users`.`name_user`, `points_table`.`points_rewarded` FROM `inz_users`
					INNER JOIN
						(SELECT  `id_user`, SUM(`points_rewarded`) as 'points_rewarded' 
							FROM `inz_groups_history` 
							WHERE  
								`inz_groups_history`.`date_execution` LIKE '".trim($current_date)."%' 
							GROUP BY  `id_user`
						)	AS points_table
						ON `inz_users`.`id_user`=`points_table`.`id_user`
					WHERE 
						`inz_users`.`id_group_user`= ".$_SESSION["Client"]->give_id_group()."
					ORDER BY `points_table`.`points_rewarded` DESC 
						";
		if($results_quests=$_SESSION["DB_connection"]->query_arr($sql_search)){
			$id_user =  $_SESSION["Client"]->get_id_user();
			echo '<div class="right_contener">';
			$counter=1;
			foreach($results_quests as $a_person){
				$special_class="";
				$this_user_class="";
				
				if($counter == 1 AND $counter <4) $special_class="first_place";
				elseif($counter == 2) $special_class="second_place";
				elseif($counter == 3) $special_class="third_place";
				
				if($id_user == $a_person["id_user"])$this_user_class="this_user";
				
				echo'	<div class="ranking_position '.$special_class.' '.$this_user_class.'">
								<div class="persons_place float_left">'.$counter.'</div>
								<div class="persons_name float_left">'.$a_person["name_user"].'</div>
								<div class="persons_points float_right">'.$a_person["points_rewarded"].'</div>								
								<div class="clear_both"></div>								
							</div>
				';
				$counter++;
			}
			echo '</div>';
			echo '<div class="clear_both"></div>';
		}			
		else{
			echo '<div class="right_contener">Obecnie żaden z użytkowników grupy nie wykonał żadnych zadań - proszę przejść do zakłądki "Zadania".';
			echo '</div>';
			echo '<div class="clear_both"></div>';
		}

		
	}
	
	public function create_vote_page(){
		if($_SESSION["Client"]->has_voting_right() == 1){
			$sql_finds_numbers="SELECT `voting_subject`FROM `inz_voting_system` WHERE `id_group_user`='".$_SESSION["Client"]->give_id_group()."' AND `voting_status`='1' AND `voting_subject` <> 'incomers%id_user%".$_SESSION["Client"]->get_id_user()."' AND `voting_subject` <> 'voting_rights%id_user%".$_SESSION["Client"]->get_id_user()."'  ";
			if($results_find_numbers = $_SESSION["DB_connection"]->query_arr($sql_finds_numbers)){
				$numbers_arr=array("quest_" => 0, "incomers" => 0, "voting_rights" => 0, "completed_quests" => 0);
				foreach($results_find_numbers as $a_subject){
					$_voting_subject_arr=explode("%",$a_subject["voting_subject"]);
					switch($_voting_subject_arr[0]){
						case "quest_add":		$numbers_arr["quest_"]++;													
														break;
						case "quest_delete":	$numbers_arr["quest_"]++;													
														break;
						case "quest_edit":		$numbers_arr["quest_"]++;													
														break;
														
						case "incomers":		$numbers_arr[$_voting_subject_arr[0]]++;													
														break;
														
/*						case "voting_rights":				$numbers_arr[$_voting_subject_arr[0]]++;													
																	break;
														
						case "completed_quests":		$numbers_arr[$_voting_subject_arr[0]]++;													
																	break;*/
					}
				}
			}
	/*		else{
				$sql_search_for_voting_decision = "SELECT `voting_decision_date` FROM `inz_voting_system` WHERE `voting_subject`='voting_rights%id_user%".$_SESSION["Client"]->get_id_user()."' AND `voting_status`='2' ORDER BY `voting_decision_date` DESC LIMIT 1";
				if($results_voting_ban = $_SESSION["DB_connection"]->query_arr($sql_search_for_voting_decision)){
					
				}
				echo "<div>Sth wrong with these searchings... maybve...</div>"; 
			}*/
			
			foreach($numbers_arr as $a_type => $an_vote_option_number){
				if($an_vote_option_number==0)	$hidden_class[$a_type] = " hidden";
				else 													$hidden_class[$a_type] = " ";
			}
			
			echo '<div class="right_contener">';
			echo '	<div class="vote_option" ><span class="vote_option_text">Dodaj/Usuń/Edytuj Zadania</span><input type="hidden" name="vote_action_name" value="add_edit_quest"><span class="numbers_of_cases'.$hidden_class["quest_"].'">'.$numbers_arr["quest_"].'</span></div>';
			echo '	<div class="vote_option" ><span class="vote_option_text">Przyjęcie członka</span><input type="hidden" name="vote_action_name" value="new_incomer"><span class="numbers_of_cases'.$hidden_class["incomers"].'">'.$numbers_arr["incomers"].'</span></div>';
			//echo '	<div class="vote_option" ><span class="vote_option_text">Miesięczne zablokowanie prawa do głosu</span><input type="hidden" name="vote_action_name" value="voting_right"><span class="numbers_of_cases'.$hidden_class["voting_rights"].'">'.$numbers_arr["voting_rights"].'</span></div>';
			//echo '	<div class="vote_option" ><span class="vote_option_text">Demokratyczne zweryfikowanie wykonania zadania</span><input type="hidden" name="vote_action_name" value="quest_checking"><span class="numbers_of_cases'.$hidden_class["completed_quests"].'">'.$numbers_arr["completed_quests"].'</span></div>';
			echo '</div>';
			echo '<div class="clear_both"></div>';
		}
		else{
			echo '<div class="right_contener"><div class="vote_option_not_allowed" >Obecnie nie posiadasz uprawnień wymaganych do głosowania. Jeżeli niedawno dołączyłeś do grupy jest duża szansa, że członkowie nie podjęli decyzji o przyjęciu. Jeżeli natomiast jesteś już przyjętym członkiem to najprawdopodobniej twoje prawo do głosowania zostało zawieszone - odnawia się ono na początku każdego miesiąca.</div></div><div class="clear_both"></div>';
		}
	}
	
	public function create_vote_page_quests(){		
		echo '<div class="background_white hidden"><div class="return_button_new_quest voting_page_return_button" ><span><i class="fas fa-arrow-circle-left"></i></span><span>Wróć</span></div></div>';
		echo '<div class="right_contener">';
		$id_group=$_SESSION["Client"]->give_id_group();
		$sql_new_quests="SELECT * FROM `inz_quests` WHERE `id_group_user`= '$id_group' AND `activation_status_quest`='2'";
		if($result_quests = $_SESSION["DB_connection"]->query_arr($sql_new_quests)){
			$FLAG_title=true;
			foreach($result_quests as $a_quest){
				if($FLAG_title){
					echo '<div class="title_quests_list">Nowe zadania czekające na zaakceptowanie</div>';
					$FLAG_title=false;
				}
				$the_quest = new Quest($a_quest);
				$voting_subject = "quest_add%id_quest%".$the_quest->get_id_quest();
				$this_voting_process = new Voting_system($voting_subject);
				echo "<div class=\"a_quest_to_edit voting_new_quest ".$this_voting_process->has_already_user_voted()." \">";
				$the_quest->display_quest();
				echo "</div>";
				echo '						
						<div class="voting_popup new_q">
							<div><span class="popup_title">Czy zgadzasz się na dodanie tego zadania:</span></div>
							<div class="popup_q_info">';
					$the_quest->display_quest_on_voting_page();
					echo'</div>
							<div class = "answers_box_popup">
								<form action="complicated_actions_solver.php" method="POST">
								<input type="hidden" name="voting_quest_add" value="yes">
								<input type="hidden" name="quest_add" value="'.$the_quest->get_id_quest().'">
								<div class="answer_popup answ_yes" >Tak</div>
								</form>
								<form action="complicated_actions_solver.php" method="POST">
								<input type="hidden" name="voting_quest_add" value="no">
								<input type="hidden" name="quest_add" value="'.$the_quest->get_id_quest().'">
								<div class="answer_popup answ_no" >Nie</div>
								</form>
							</div>
						</div>
				';
				
			}
		}
		$sql_edited_quests="SELECT * FROM `inz_quests` WHERE `id_group_user`= '$id_group' AND `activation_status_quest`='1' AND `edit_str_quest` IS NOT NULL";
		if($result_quests = $_SESSION["DB_connection"]->query_arr($sql_edited_quests)){
			$FLAG_title=true;
			foreach($result_quests as $a_quest){
				if($FLAG_title){
					echo '<div class="title_quests_list">Edytowane zadania, czekające na zaakceptowanie</div>';
					$FLAG_title=false;
				}
				$the_quest = new Quest($a_quest);
				$voting_subject = "quest_edit%id_quest%".$the_quest->get_id_quest();
				$this_voting_process = new Voting_system($voting_subject);
				echo "<div class=\"a_quest_to_edit voting_edited_quest ".$this_voting_process->has_already_user_voted()." \">";
				$the_quest->display_quest();
				echo "</div>";
				echo '						
						<div class="voting_popup new_q">
							<div><span class="popup_title">Proponowana edycja:</span></div>
							<div class="popup_q_info">';
					$the_quest->display_quest_on_voting_page("edit");
					echo'</div>
							<div class = "answers_box_popup">
								<form action="complicated_actions_solver.php" method="POST">
								<input type="hidden" name="voting_quest_edit" value="yes">
								<input type="hidden" name="quest_edit" value="'.$the_quest->get_id_quest().'">
								<div class="answer_popup answ_yes" >Tak</div>
								</form>
								<form action="complicated_actions_solver.php" method="POST">
								<input type="hidden" name="voting_quest_edit" value="no">
								<input type="hidden" name="quest_edit" value="'.$the_quest->get_id_quest().'">
								<div class="answer_popup answ_no" >Nie</div>
								</form>
							</div>
						</div>
				';
					
				
			}
		}
		$sql_to_delete_q="SELECT `voting_subject` FROM `inz_voting_system` WHERE `voting_status`='1' AND `id_group_user`= '$id_group'  AND `voting_subject` LIKE 'quest_delete%id_quest%'  ";
		if($result_quests = $_SESSION["DB_connection"]->query_arr($sql_to_delete_q)){
			$list_of_quests = "";
			foreach($result_quests as $a_voting_subject){
				$exploded = explode("%",$a_voting_subject["voting_subject"]);
				$list_of_quests_arr[]="`id_quest`='".$exploded[2]."'";
			}
			if(isset($list_of_quests_arr))$list_of_quests=" AND (".implode(" OR ",$list_of_quests_arr)." )";
			$sql_find_this_quests = "SELECT * FROM `inz_quests` WHERE `id_group_user`= '$id_group' $list_of_quests ";
			if($result_quests_arr = $_SESSION["DB_connection"]->query_arr($sql_find_this_quests)){
				$FLAG_title=true;
				foreach($result_quests_arr as $a_quest){
					if($FLAG_title){
						echo '<div class="title_quests_list">Zgłoszone do usunięcia</div>';
						$FLAG_title=false;
					}
					$the_quest = new Quest($a_quest);
					$voting_subject = "quest_delete%id_quest%".$the_quest->get_id_quest();
					$this_voting_process = new Voting_system($voting_subject);
					echo "<div class=\"a_quest_to_edit voting_delete_quest ".$this_voting_process->has_already_user_voted()." \">";
					$the_quest->display_quest();
					echo "</div>";
					echo '						
							<div class="voting_popup to_delete_popup">
								<div><span class="popup_title">Czy chcesz usunąć to zadanie?</span></div>
								<div class="popup_q_info">';
						$the_quest->display_quest_on_voting_page();
						echo'</div>
								<div class = "answers_box_popup">
									<form action="complicated_actions_solver.php" method="POST">
									<input type="hidden" name="voting_quest_delete" value="yes">
									<input type="hidden" name="quest_delete" value="'.$the_quest->get_id_quest().'">
									<div class="answer_popup answ_yes" >Tak</div>
									</form>
									<form action="complicated_actions_solver.php" method="POST">
									<input type="hidden" name="voting_quest_delete" value="no">
									<input type="hidden" name="quest_delete" value="'.$the_quest->get_id_quest().'">
									<div class="answer_popup answ_no" >Nie</div>
									</form>
								</div>
							</div>
					';
					
				}
			}
		}
		$sql="SELECT * FROM `inz_quests` WHERE `id_group_user`= '$id_group' AND `activation_status_quest`='1' AND `edit_str_quest` IS NULL ";
		if($result_quests = $_SESSION["DB_connection"]->query_arr($sql)){
			$FLAG_title=true;
			echo '	
						
						<div class="form_adding_quest voting_page_edit_form">
							<form action="complicated_actions_solver.php" method="post">
								<input type="hidden" name="edit_a_quest" value="true">
								<input type="hidden" name="edit_quest_id" value="">
								<div class="login_group">
								<span>Nazwa: </span>
												<input class="login_control" name="edit_name" placeholder="Wpisz nazwę zadania" type="text">
								</div>
								<div class="login_group">
								<span>Opis: </span>
												<textarea class="login_control" name="edit_descr" rows="7" cols="60" placeholder="Tutaj zamieść opis zadania"></textarea>
								</div>
								<div class="login_group datetime_inputs">
												<p><span>Wybierz co jaki czas zadanie ma się ponownie pojawiać:</span></p>
												<label>Miesięcy: <input type="number" name="edit_renewable_period_month" min="0" max="12" step="1" value="0"></label>
												<label>Dni: <input type="number" name="edit_renewable_period_day" min="0" max="31" step="1" value="1"></label>
												<label>Godzin: <input type="number" name="edit_renewable_period_hour" min="0" max="23" step="1" value="0"></label>
												<label>Minut: <input type="number" name="edit_renewable_period_min" min="0" max="59" step="1" value="0"></label>
												<input type="hidden" 	name="edit_renewable_period_sec" value="0">
								</div>
								<div class="login_group">
												<label>Liczba punktów za zrobienie zadania: <input type="number" name="edit_points" min="1"  max="999999" step="1" value="100"></label>
								</div>
								<input id="submitbutton" type="submit" value="Wyślij propozycję edycji" class="login_submit_button">
							</form>
						</div>';
			foreach($result_quests as $a_quest){			
				if($FLAG_title){
					echo '<div class="title_quests_list grey_color_title">Wszystkie zaakceptowane zadania: </div>';
					$FLAG_title=false;
				}
				$the_quest = new Quest($a_quest);
				echo "<div class=\"a_quest_to_edit voting_quests_to_edit grey_color\">";
				$the_quest->display_quest();
				echo "</div>";				
				
			}
		}
		echo '</div>';
		echo '<div class="clear_both"></div>';
	}
		
	public function create_vote_page_incomers(){
		echo '<div class="background_white hidden"><div class="return_button_new_quest voting_page_return_button" ><span><i class="fas fa-arrow-circle-left"></i></span><span>Wróć</span></div></div>';
		echo '<div class="right_contener">';
		$id_group=$_SESSION["Client"]->give_id_group();
		$sql_new_incomers="SELECT * FROM `inz_users` WHERE `id_group_user`= '$id_group' AND `is_allowed_to_vote`='2'";
		if($result_incomers = $_SESSION["DB_connection"]->query_arr($sql_new_incomers)){
			$FLAG_title=true;
			foreach($result_incomers as $an_incomer){
				if($FLAG_title){
					echo '<div class="title_quests_list">Nowo czekające osoby na przyznanie prawa głosu:</div>';
					$FLAG_title=false;
				}
				$voting_subject = "incomers%id_user%".$an_incomer["id_user"];
				$this_voting_process = new Voting_system($voting_subject);
				echo "<div class=\"a_quest_to_edit voting_incomer ".$this_voting_process->has_already_user_voted()." \">";
				echo '<div class="name_quest">'.$an_incomer["name_user"].'</div>';
				echo '<div class="desc_quest">'.$an_incomer["login_user"].'</div>';
				echo "</div>";
				echo '						
						<div class="voting_popup new_q">
							<div><span class="popup_title">Czy zgadzasz się na przyznanie praw ( '.$an_incomer["name_user"].' ) ?</span></div>
							<div class="popup_q_info">';
					echo'</div>
							<div class = "answers_box_popup">
								<form action="complicated_actions_solver.php" method="POST">
								<input type="hidden" name="voting_incomers" value="yes">
								<input type="hidden" name="incomer_id" value="'.$an_incomer["id_user"].'">
								<div class="answer_popup answ_yes" >Tak</div>
								</form>
								<form action="complicated_actions_solver.php" method="POST">
								<input type="hidden" name="voting_incomers" value="no">
								<input type="hidden" name="incomer_id" value="'.$an_incomer["id_user"].'">
								<div class="answer_popup answ_no" >Nie</div>
								</form>
							</div>
						</div>
				';
				
			}
		}
		echo '</div>';
		echo '<div class="clear_both"></div>';
	}

	public function get_action_name(){
		return $this->action_name;
	}
	public function create_main_content(){
		switch($this->action_name){
			
			case "quests_page":
				$this->create_quests_page();				
				break;
			case "ranking_page":
				$this->create_ranking_page();				
				break;
			case "vote_page":
				$this->create_vote_page();				
				break;
			case "vote_page_quests":
				$this->create_vote_page_quests();
				break;
			case "vote_page_incomers":
				$this->create_vote_page_incomers();				
				break;

			default:
				$this->create_quests_page();				
				break;			
		}
	}
	
	
}
?>				