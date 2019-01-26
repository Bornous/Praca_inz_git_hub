<?php
class Client{
	private $id_user;
	private $login_user;
	private $password_user;
	private $group_user;
	private $name_user;
	private $voting_right;

	
	public function __construct($_id_user, $_login_user, $_password_user,$_name_user){
		$this->id_user = $_id_user;
		$this->login_user = $_login_user;
		$this->password_user = $_password_user;		
		$this->name_user = $_name_user;
	}
	
	public function load_the_group($_group_user){
		$this->group_user = $_group_user;
	}
	
	public function load_the_voting_right($_is_allowed_to_vote){
		$this->voting_right = $_is_allowed_to_vote;
	}
	
	public function assigment_to_a_group($_group_user){
		$id_group = $_group_user->give_id_group();
		$sql_assign_the_group = "UPDATE `inz_users` SET `id_group_user` = '$id_group' WHERE `id_user` = '".$this->id_user."'";

		if ($_SESSION["DB_connection"]->query($sql_assign_the_group)){
			$this->group_user=$_group_user;
		}
		else{
			echo "Query failure! |:::>".$sql_assign_the_group."<:::|";
		}
	}
	
	public function give_id_group(){
		return $this->group_user->give_id_group(); //it returns value from Group's method called the same
	}

	public function get_id_user(){
		return $this->id_user;
	}
	
	public function has_voting_right(){
		return $this->voting_right;
	}
	
}
?>