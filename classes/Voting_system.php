<?php
class Voting_system{
	private $id_voting_process;
	private $voting_subject;
	private $voting_results;
	private $table_name;

	
	public  function __construct($_voting_subject_string) {
		$_voting_subject_arr=explode("%",$_voting_subject_string);
		$this->voting_subject=array( "type" =>$_voting_subject_arr[0], $_voting_subject_arr[1] => $_voting_subject_arr[2]);
		// 
		// TYPES: quest_delete ::: quest_add ::: quest_edit ::: incomers ::: voting_rights ::: completed_quests
		$id_name = $_voting_subject_arr[1];
		$id_val= $_voting_subject_arr[2];
		switch($id_name){
			case "id_quest":	
											$table_name = "inz_quests";
											break;
			case "id_user":
											$table_name = "inz_users";
											break;
			case "id_last_execution":
											$table_name = "inz_groups_history";
											break;
			
		}
		$this->table_name=$table_name;
		$sql = "SELECT * FROM `inz_voting_system` WHERE `voting_subject`='$_voting_subject_string' AND `voting_status`='1' LIMIT 1";
		$result=$_SESSION["DB_connection"]->query_arr($sql);
			
		if(empty($result)){
			$id_group_user=$_SESSION["Client"]->give_id_group();
			$sql_insert_a_voting_process="INSERT INTO `inz_voting_system` (`id_group_user`, `voting_subject`, `voting_status`) VALUES ('$id_group_user', '$_voting_subject_string','1') ";
			if($_SESSION["DB_connection"]->query($sql_insert_a_voting_process) ){
				$this->id_voting_process=$_SESSION["DB_connection"]->give_insert_id();
				
			}
		}
		else{
			$this->id_voting_process=$result[0]["id_voting_process"];
			$voting_results_votes=explode("%",$result[0]["voting_results"]);
			if(count($voting_results_votes)>=2){
				foreach($voting_results_votes as $a_vote){
					$a_vote_arr=explode("&&&",$a_vote);
					$this->voting_results[]=array("id_user" => $a_vote_arr[0], "vote" => $a_vote_arr[1]);
				}
			}
			elseif(!empty($result[0]["voting_results"])){
				$a_vote_arr=explode("&&&",$result[0]["voting_results"]);
				$this->voting_results[0]=array("id_user" => $a_vote_arr[0], "vote" => $a_vote_arr[1]);
			}
			else $this->voting_results=NULL;
			
		}
		
	}
	
	public function voting($vote){
		$sql_how_many_voters="SELECT COUNT(`id_user`) as 'counted' FROM `inz_users` WHERE `id_group_user`='".$_SESSION["Client"]->give_id_group()."' AND `is_allowed_to_vote`='1' ";
		$how_many_votes_arr=$_SESSION["DB_connection"]->query_arr($sql_how_many_voters);
		$how_many_voters=(int)$how_many_votes_arr[0]["counted"];
		if($this->voting_subject["type"]=="voting_rights" OR $this->voting_subject["type"]=="completed_quests")$how_many_voters--;
		$how_many_votes=0;
		$decision_sum=0;
		if(!empty($this->voting_results)){
			$voting_string="";
			$vote_submitted=false;
			$id_current_user=$_SESSION["Client"]->get_id_user();
			foreach($this->voting_results as $a_vote_arr){
				if($a_vote_arr["id_user"]==$id_current_user){
					$vote_submitted=true;
					$a_vote[]=$a_vote_arr["id_user"]."&&&$vote";
					$decision_sum+=(int)$vote;
				}
				else{
					$a_vote[]=implode("&&&",$a_vote_arr);
					$decision_sum+=(int)$a_vote_arr["vote"];
				}
			}
			if($vote_submitted==false){
				$decision_sum+=(int)$vote;
				$a_vote[]="$id_current_user&&&$vote";
			}
			$how_many_votes=count($a_vote);
			$voting_results_string=implode("%",$a_vote);
		}
		else{
			$id_current_user=$_SESSION["Client"]->get_id_user();
			$voting_results_string="$id_current_user&&&$vote";
		}

		if((float)$how_many_votes/(float)$how_many_voters>=0.51){

			if((float)$decision_sum/(float)$how_many_voters>=0.51){
				$sql_finish_voting="UPDATE `inz_voting_system` SET  `voting_results`='$voting_results_string', `voting_status`='2' WHERE `id_voting_process`='".$this->id_voting_process."' ";
				$_SESSION["DB_connection"]->query($sql_finish_voting);
				return $this->voting_complete_procedure("yes");
			}
			else{
				$sql_finish_voting="UPDATE `inz_voting_system` SET  `voting_results`='$voting_results_string', `voting_status`='0' WHERE `id_voting_process`='".$this->id_voting_process."' ";
				
				$_SESSION["DB_connection"]->query($sql_finish_voting);
				return $this->voting_complete_procedure("no");
			}
		}
		else{
			$sql_update_voting_results="UPDATE `inz_voting_system` SET  `voting_results`='$voting_results_string' WHERE `id_voting_process`='".$this->id_voting_process."' ";
			if($_SESSION["DB_connection"]->query($sql_update_voting_results))		return TRUE;
			else return FALSE;
		}
	}
	
	private function voting_complete_procedure($decision){
		
		switch($this->voting_subject["type"]){
			case "quest_add":	
											if($decision=="yes")	$sql="UPDATE `".$this->table_name."` SET `activation_status_quest`='1' WHERE `id_quest`='".$this->voting_subject["id_quest"]."' ";
											else $sql="UPDATE `".$this->table_name."` SET `activation_status_quest`='-1' WHERE `id_quest`='".$this->voting_subject["id_quest"]."' ";
											
											break;
			case "quest_delete":	
											if($decision=="yes")	$sql="UPDATE `".$this->table_name."` SET `activation_status_quest`='-1' WHERE `id_quest`='".$this->voting_subject["id_quest"]."' ";
											
											break;
			case "quest_edit":	
											if($decision=="yes"){
												$sql_extract_edits="SELECT `edit_str_quest` FROM `".$this->table_name."` WHERE `id_quest`='".$this->voting_subject["id_quest"]."' ";
												$string_with_edits="";
												if($result_edits_data=$_SESSION["DB_connection"]->query_arr($sql_extract_edits)){
													$string_with_edits=$result_edits_data[0]["edit_str_quest"];
													
												}
												$sql="UPDATE `".$this->table_name."` SET $string_with_edits, `edit_str_quest`=NULL WHERE `id_quest`='".$this->voting_subject["id_quest"]."' ";	
											}
											else $sql="UPDATE `".$this->table_name."` SET `edit_str_quest`=NULL WHERE `id_quest`='".$this->voting_subject["id_quest"]."' ";	
										
											break;
											
			case "incomers":
											$sql="UPDATE `".$this->table_name."` SET `is_allowed_to_vote`='1' WHERE `id_user`='".$this->voting_subject["id_user"]."' ";
											
											break;
											
			case "voting_rights":
											$sql="UPDATE `".$this->table_name."` SET `is_allowed_to_vote`='0' WHERE `id_user`='".$this->voting_subject["id_user"]."' ";
											
											break;
											
			case "completed_quests":
											$sql="UPDATE `".$this->table_name."` SET `date_execution`='2000-01-01 00:00:00', `points_rewarded`='0' WHERE `id_last_execution`='".$this->voting_subject["id_last_execution"]."' ";	
		}
		if(isset($sql)) 	return $_SESSION["DB_connection"]->query($sql);
		else return true;
	}
	
	public function has_already_user_voted(){
		$id_current_user=$_SESSION["Client"]->get_id_user();
		if(!empty($this->voting_results)){
			$id_current_user=$_SESSION["Client"]->get_id_user();
			foreach($this->voting_results as $a_vote_arr){
				if($a_vote_arr["id_user"]==$id_current_user){
					if($a_vote_arr["vote"]) return "user_voted_yes";
					else return "user_voted_no";
				}
			}
		}
		else return "user_voted_not";
	}
	
}
?>