<?php
class Login_system{

	private $login_user;
	private $password_user;
	private $what_to_do;
	private $name_user;
	
	public function __construct($_what_to_do = "", $_login_user, $_password_user = "", $_name_user = "") {
		
		$this->login_user = $_login_user;
		if( !empty($_password_user) )$this->password_user = $_password_user;
		if( !empty($_what_to_do) )$this->what_to_do = $_what_to_do;
		if( !empty($_name_user) )$this->name_user = $_name_user;

    }
	
	
	private function log_in() {
		if(empty($this->login_user) || empty($this->password_user) ){
			header('Location: login.php?login=no_login_or_password');	
		}
		else{
			$sql_client = "SELECT * FROM `inz_users` WHERE `login_user`='".$this->login_user."'";
			$result_client = $_SESSION["DB_connection"]->query($sql_client); 
			if ($result_client->num_rows > 0) { 
				$a_user = $result_client->fetch_assoc();
				$id_user = $a_user["id_user"];
				$hashedpassword_user_db = $a_user["password_user"];	
				$password_user_correctness = password_verify($this->password_user, $hashedpassword_user_db);
										
				if ( $password_user_correctness === TRUE ){ 
					$_SESSION["Client"] = new Client($a_user["id_user"], $a_user["login_user"], $a_user["password_user"],$a_user['id_group'],$a_user['name_user']);
					if( $a_user['id_group_user']!==NULL){ 
						$_SESSION["Client"]->load_the_group( new Group( $a_user['id_group_user']));
						$_SESSION["Client"]->load_the_voting_right($a_user['is_allowed_to_vote']);
						header('Location: user_panel/user_panel.php?login=success&group_exist=true');
					}
					else header('Location: user_panel/group_choice.php');
					exit();					
				}
				else
				{ 
						header('Location: login.php?login=fail');
						exit();
				}					
			} 
			else
			{
				header('Location: login.php?login=db_req_fail'); // user does not exist	
			}
		}//END OF if(empty($this->login_user) || empty($this->password_user)){...}else{ 
	}//END OF public function log_in() { 
	

	private function register(){
		$password_user = password_hash(trim($this->password_user), PASSWORD_BCRYPT);
			$sql_register= "INSERT INTO `inz_users` (`login_user`, `password_user`, `name_user`) VALUES ('".$this->login_user."', '$password_user', '".$this->name_user."')";
			
			if(	$result_register = $_SESSION["DB_connection"]->query($sql_register) ){		
				$sql_client = "SELECT * FROM `inz_users` WHERE `login_user`='".$this->login_user."'";
				$result_client = $_SESSION["DB_connection"]->query($sql_client); 
				
				if ($result_client->num_rows > 0) { 
					$a_user = $result_client->fetch_assoc();
					$_SESSION["Client"] = new Client($a_user["id_user"], $a_user["login_user"], $a_user["password_user"],$a_user['name_user']);
				}
			//	print_r($_SESSION["Client"]);
				//Save progress from session
	
				header('Location: user_panel/group_choice.php');
			
			}
			else{
			
				header('Location: login.php?login=fail_to_register$error='.$result_register->errno); 
			}
	}	

	public function turn_on(){
		switch ( $this->what_to_do ){
			case "login":
				$this->log_in();				
				break;
			case "register_account":
				$this->register();
				break;
			case "":
				$this->log_in();				
				break;
		}
		
	}
	
	
}
?>