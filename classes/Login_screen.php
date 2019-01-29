<?php
class Login_screen{
	private $event;

	
	public  function __construct($_event) {
		$this->event = $_event;		
	}
	
	public function get_event(){
			return $this->event ;
	}
	
	public function create_login_form(){
		
		if		(	$this->event == "just_login"				OR	$this->event == "fail" 								)	echo 	$this->echo_login_screen();
		elseif(	$this->event == "register"				OR	$this->event == "fail_to_register" 				)	echo 	$this->echo_register_screen();
		else 																																			echo 	$this->echo_login_screen();
				
	}
	public function create_login_fail_message(){
		
		if( 		$this->event == "fail" 								)	echo	$this->echo_login_fail();
		else																			echo	"";		
		
	}
	
	
	public function echo_login_screen(){
		return '
		<div class="login_panel_head">
			<h3 class="login_panel_head_title">Zaloguj się</h3>
		</div>
		<div class="login_panel_body">
			<form role="form" action="logincheck.php" method="post">
				<input name="what_to_do" type="hidden" value="login">				
				<div class="login_group">
					<input class="login_control" placeholder="E-mail" name="login_user" id="un" autofocus>
				</div>
				<div class="login_group">
					<input class="login_control" placeholder="Hasło" name="password_user" type="password" autocomplete="off" id="pw" value="">
				</div>
				<!-- <div class="login_checkbox">
					<label>
						<input name="remember" type="checkbox" value="Remember Me">Remember Me
					</label>
				</div> -->
				<div class="login_checkbox">
					<div class="g-recaptcha" data-sitekey="6LeI_E4UAAAAAIDY4A3mf38xPf3WOvM6b2OB-SY8"></div>
				</div>
				
				<input id="submitbutton" type="submit" value="Zaloguj" class="login_submit_button">			
			</form>
		</div>
		';
		
	}
	
	public function echo_login_fail(){
		return '
		<div class="row">
                <div class="col-md-4 col-md-offset-4">
                   	<div class="panel panel-danger">
                        <div class="panel-heading">
                            Error
                        </div>
                        <div class="panel-body">
							Either the username or password you entered were incorrect. Please try again.	
                        </div>
                    </div>
                </div>			
			</div>
		';
		
	}

	
	public function echo_register_screen(){
		return '
		<div class="login_panel_head">
			<h3 class="login_panel_head_title">Rejestracja</h3>
		</div>
		<div class="login_panel_body">
			<form role="form" action="logincheck.php" method="post">
						<input name="what_to_do" type="hidden" value="register_account">
						<div class="login_group">
							<span>Pamiętaj, że hasło powinno składać się z więcej niż 3 znaki </span>
						</div>
						<div class="login_group">
							<input class="login_control" name="login_user" placeholder="Email" type="email" >
						</div>
						<div id="email_exists" class="hidden">
							Ten email zostałjuż zarejestrowany.
						</div>
						<div class="login_group">
							<input class="login_control" name="name_user" placeholder="Nazwa" type="text" >
						</div>
						<div class="login_group">
							<input class="login_control" name="password_user" placeholder="Hasło" type="password" id="new_pw_1" >
						</div>
						<div class="login_group">
							<input class="login_control" name="password_user" placeholder="Ponownie wpisz hasło" type="password" id="new_pw_2" >
						</div>
						<div id="different_pw" class="warning_message">
							<span>Wpisałeś dwa różne hasła - proszę wpisać to samo hasło w obu linijkach </span>
						</div>
						<input id="submitbutton" type="submit" value="Zarejestruj" class="login_submit_button">
						
						
						<div class="login_checkbox">
							<a href="login.php"><i class="fa fa-arrow-left"></i> Wróć</a> 
						</div>
										 
			</form>
		</div>
		';
		
	}
	
}
?>