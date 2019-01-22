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
	
	private function forgot_password_user(){
		
		$sql_hash_pw= "SELECT `password_user`, `login_user` FROM `inz_users` WHERE`login_user`='".$this->login_user."'";
		
		if(	$result_client_hash_pw = $_SESSION["DB_connection"]->query_arr($sql_hash_pw) )
		{		
	
			$check_number = $result_client_hash_pw[0]['user_passwd'].$this->cn_separator."".md5($this->login_user);
			$user_email=$result_client_hash_pw[0]['user_email'];
			$link_to_reset_password_user=(empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER['SERVER_NAME']."/webmaxer/logincheck.php?check_number=".urlencode($check_number);
			$message="\nHello!\n\nYou recently requested a new password_user.\n\nPlease click the link below to complete your new password_user request:\n".$link_to_reset_password_user." \n\n";
			
			mail($user_email,"Forgot your password_user?",$message);
			header('Location: login.php?mail=sent');
		}
		else{
			
			header('Location: login.php?login=forgot_password_user_fail'); // user does not exist	
		}
	}	
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
	
	private function change_password_user(){
		$check_number_arr=explode( $this->cn_separator,urldecode( $this->check_number ) );
		$hash_pw=$check_number_arr[0];
		$hash_un=$check_number_arr[1];
		
		$sql_clients="SELECT * FROM seo_users_base WHERE user_passwd='$hash_pw'";
		if( $result_clients = $_SESSION["DB_connection"]->query_arr($sql_clients)  ){
			if( count($result_clients) == 1 ){
				$_SESSION["id_user"]=$result_clients[0]["id_user"];
				header('Location: login.php?login=write_new_pw'); 
			}
			elseif( count($result_clients) > 1 ){
				foreach($result_clients as $a_user){
					if( md5($a_user["user_login"] ) == $hash_un){
						$_SESSION["id_user"]=$result_clients[0]["id_user"];
						header('Location: login.php?login=write_new_pw'); 
					}					
				}
				header('Location: login.php?login=forgot_password_user_fail&user_from_mail=false_l156'); // this happens only if user from mail does not exist	
			}
		}
		else{
			
			header('Location: login.php?login=forgot_password_user_fail&user_from_mail=false_l160'); // user does not exist	
		}
	}
	
	private function set_new_password_user(){
		$new_password_user = password_user_hash(trim($this->password_user), password_user_BCRYPT);
		$id_user = $_SESSION['id_user']; 
		
		$sql_set_new_pw = "UPDATE seo_users_base SET user_passwd='$new_password_user' WHERE id_user='$id_user'";
			
		if ( $_SESSION["DB_connection"]->query($sql_set_new_pw)  ) {
			header('Location: login.php?login=password_user_updated'); 
		} 
		else {
			header('Location: login.php?login=fail_to_write_new_pw'); 
		}					
		
	}
	/*	
	private function change_user(){
			if( isset($_GET["id_user"]) ) $_SESSION["Client"]->admins_set_a_user($_GET["id_user"]);
			header('Location: report.php'); 
	}
	*/
	public function turn_on(){
		switch ( $this->what_to_do ){
			case "login":
				$this->log_in();				
				break;
			case "forgot_password_user":
				$this->forgot_password_user();
				break;
			case "change_password_user":
				$this->change_password_user();
				break;
			case "set_new_password_user":
				$this->set_new_password_user();
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