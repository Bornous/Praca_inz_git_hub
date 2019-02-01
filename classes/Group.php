<?php
class Group{
	private $id_group;
	private $name_group;

	
	public function __construct($_id_group_user = ""){
		if($_id_group_user != ""){
			$sql_name_gr="SELECT `name_group` FROM `inz_group_users` WHERE `id_group`='$_id_group_user'";
			$this->id_group=$_id_group_user;
			$this->name_group= $_SESSION["DB_connection"]->query_arr($sql_name_gr)[0]["name_group"];
		}
	}	

	public function give_id_group(){
		return $this->id_group;
	}
		
	public function create_new_one($_name_group){
		$sql_create_new_group = "INSERT INTO `inz_group_users` (`name_group`) VALUES('$_name_group')";
		if ($_SESSION["DB_connection"]->query($sql_create_new_group)){
			$this->id_group=$_SESSION["DB_connection"]->give_insert_id();
			$this->name_group=$_name_group;
			
			return TRUE;
		}
		else{
			echo "Query failure! |:::>".$sql_create_new_group."<:::|";
		}
	}
		
	public function join_one($_name_user){
		$sql_find_group="SELECT `id_group_user` FROM `inz_users` WHERE `login_user`='$_name_user'";
		if($id_group_user_res = $_SESSION["DB_connection"]->query_arr($sql_find_group)){
			$id_new_group = $id_group_user_res[0]["id_group_user"];
		$sql_group_name="SELECT `name_group` FROM `inz_group_users` WHERE `id_group`='$id_new_group'";
		$this->id_group=$id_new_group;
		$this->name_group= $_SESSION["DB_connection"]->query_arr($sql_group_name)[0]["name_group"];
		$_SESSION["Client"]->load_the_voting_right('0');
		return TRUE;
		}
		else{
			echo "Query failure! |:::>".$sql_find_group."<:::|";
		}
	}
	

}
?>