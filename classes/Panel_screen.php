<?php
class Panel_screen{
	private $action_name;

	
	public  function __construct() {
		if( isset($_POST["action_name"]) ){
			$this->action_name = $_POST["action_name"];
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
				`inz_quests`.`activation_status_quest`='1'";
				
		if($results_quests=$_SESSION["DB_connection"]->query_arr($sql_search)){
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
		else{
			echo $sql_search;
			return false;
		}
	}
	
	public function create_quests_page(){
		
		
		echo '	
					<div class="background_white hidden"></div>
					<div class="confirm_popup">
						
						<div class="text_popup">
							<p>
								<span>Zadanie: </span><span id="quest_name_popup"></span>
							</p>						
							<p>
								<span>Czy potwierdzasz wykonanie zadania?</span>
							</p>
							
						</div>
						<div class = "answers_box_popup">
							<div class="answer_popup answ_yes">Tak</div><div class="answer_popup answ_no">Nie</div>
						</div>
					</div>';
		echo '<div class="right_contener with_quests">';
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
		else echo "DB ERROR sql:".$sql_search;

		
	}
	
	public function create_vote_page(){
		if($_SESSION["Client"]->has_voting_right() == 1){
			$sql_finds_numbers="SELECT `voting_subject` FROM `inz_voting_system` WHERE `id_group_user`='".$_SESSION["Client"]->give_id_group()."' AND `voting_status`='1' AND `voting_subject` <> 'incomers%id_user%".$_SESSION["Client"]->get_id_user()."' AND `voting_subject` <> 'voting_rights%id_user%".$_SESSION["Client"]->get_id_user()."'  ";
			echo $sql_finds_numbers;
			if($results_find_numbers = $_SESSION["DB_connection"]->query_arr($sql_finds_numbers)){
				print_r($results_find_numbers);
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
														
						case "voting_rights":				$numbers_arr[$_voting_subject_arr[0]]++;													
																	break;
														
						case "completed_quests":		$numbers_arr[$_voting_subject_arr[0]]++;													
																	break;
					}
				}
			}
			else{
				$sql_search_for_voting_decision = "SELECT `voting_decision_date` FROM `inz_voting_system` WHERE `voting_subject`='voting_rights%id_user%".$_SESSION["Client"]->get_id_user()."' AND `voting_status`='2' ORDER BY `voting_decision_date` DESC LIMIT 1";
				if($results_voting_ban = $_SESSION["DB_connection"]->query_arr($sql_search_for_voting_decision)){
					
				}
				echo "<div>Sth wrong with these searchings... maybve...</div>"; 
			}
			
			foreach($numbers_arr as $a_type => $an_vote_option_number){
				if($an_vote_option_number==0)	$hidden_class[$a_type] = " hidden";
				else 													$hidden_class[$a_type] = " ";
			}
			
			echo '<div class="right_contener">';
			echo '	<div class="vote_option" ><span class="vote_option_text">Dodaj/Usuń/Edytuj Zadania</span><input type="hidden" name="vote_action_name" value="add_edit_quest"><span class="numbers_of_cases'.$hidden_class["quest_"].'">'.$numbers_arr["quest_"].'</span></div>';
			echo '	<div class="vote_option" ><span class="vote_option_text">Przyjęcie członka</span><input type="hidden" name="vote_action_name" value="new_incomer"><span class="numbers_of_cases'.$hidden_class["incomers"].'">'.$numbers_arr["incomers"].'</span></div>';
			echo '	<div class="vote_option" ><span class="vote_option_text">Miesięczne zablokowanie prawa do głosu</span><input type="hidden" name="vote_action_name" value="voting_right"><span class="numbers_of_cases'.$hidden_class["voting_rights"].'">'.$numbers_arr["voting_rights"].'</span></div>';
			echo '	<div class="vote_option" ><span class="vote_option_text">Demokratyczne zweryfikowanie wykonania zadania</span><input type="hidden" name="vote_action_name" value="quest_checking"><span class="numbers_of_cases'.$hidden_class["quest_"].'">'.$numbers_arr["completed_quests"].'</span></div>';
			echo '</div>';
			echo '<div class="clear_both"></div>';
		}
		else{
			echo '<div class="right_contener"><div class="vote_option_not_allowed" >Obecnie nie posiadasz uprawnień wymaganych do głosowania. Jeżeli niedawno dołączyłeś do grupy jest duża szansa, że członkowie nie podjęli decyzji o przyjęciu. Jeżeli natomiast jesteś już przyjętym członkiem to najprawdopodobniej twoje prawo do głosowania zostało zawieszone - odnawia się ono na początku każdego miesiąca.</div></div>';
		}
	}
		
	public function create_sett_page(){
		echo '<div class="right_contener">';
		echo '	<div style="cursor:pointer">Zmiana nazwy</div>';
		echo '	<div style="cursor:pointer">Usunięcie konta</div>';
		echo '	<div style="cursor:pointer">Dodaj/Usuń/Edytuj Zadania</div>';
		echo '</div>';
		echo '<div class="clear_both"></div>';
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
			case "sett_page":
				$this->create_sett_page();				
				break;
			default:
				$this->create_quests_page();				
				break;			
		}
	}
	
	
}
?>				