<?php
/* 
*	--- Start of OOP way ---
*	- Login_screen class
*/
class Login_screen{
	private $event;

	
	public  function __construct($_event) {
		$this->event = $_event;		
	}
	
	public function get_event(){
			return $this->event ;
	}
	
	public function create_login_form(){
		
		if( 		$this->event == "locked" 																							)	echo 	$this->echo_login_locked();
		elseif(	$this->event == "forgot_password"	OR	$this->event == "forgot_password_fail" 	)	echo 	$this->echo_forgot_screen();
		elseif(	$this->event == "just_login"				OR	$this->event == "fail" 								)	echo 	$this->echo_login_screen();
		elseif(	$this->event == "write_new_pw"		OR	$this->event == "fail_to_write_new_pw" 		)	echo 	$this->echo_new_pw_screen();
		elseif(	$this->event == "register"				OR	$this->event == "fail_to_register" 				)	echo 	$this->echo_register_screen();
		else 																																			echo 	$this->echo_login_screen();
				
	}
	public function create_login_fail_message(){
		
		if( 		$this->event == "fail" 								)	echo	$this->echo_login_fail();
		elseif(	$this->event == "forgot_password_fail" 	)	echo	$this->echo_forgot_password_fail();
		elseif(	$this->event == "fail_to_write_new_pw" 	)	echo	$this->echo_fail_to_write_new_pw();
		else																			echo	"";		
		
	}
	
	
	public function echo_login_screen(){
		return '
		<div class="login_panel_head">
			<h3 class="login_panel_head_title">Please Sign In</h3>
		</div>
		<div class="login_panel_body">
			<form role="form" action="logincheck.php" method="post">
				<input name="what_to_do" type="hidden" value="login">				
				<div class="login_group">
					<input class="login_control" placeholder="Username" name="login_user" id="un" autofocus>
				</div>
				<div class="login_group">
					<input class="login_control" placeholder="Password" name="password_user" type="password" autocomplete="off" id="pw" value="">
				</div>
				<!-- <div class="login_checkbox">
					<label>
						<input name="remember" type="checkbox" value="Remember Me">Remember Me
					</label>
				</div> -->
				<div class="login_checkbox">
					<div class="g-recaptcha" data-sitekey="6LeI_E4UAAAAAIDY4A3mf38xPf3WOvM6b2OB-SY8"></div>
				</div>
				
				<input id="submitbutton" type="submit" value="Login" class="login_submit_button">
				<div class="login_checkbox">
					<a href="login.php?login=forgot_password">Forgot your password?</a>
				</div> 				
			</form>
		</div>
		';
		
	}
	
	public function echo_forgot_screen(){
		return '
		<div class="login_panel_head">
			<h3 class="login_panel_head_title">Get password</h3>
		</div>
		<div class="login_panel_body">
			<form role="form" action="logincheck.php" method="post">
						<input name="what_to_do" type="hidden" value="forgot_password">
						<div class="login_group">
							<span>Enter your username or e-mail to retrieve your password. </span>
						</div>
						<div class="login_group">
							<input class="login_control" name="username" placeholder="Username" type="text" id="fp" autofocus>
						</div>
						<input id="submitbutton" type="submit" value="Send" class="login_submit_button">
						<div class="login_checkbox">
							<a href="login.php"><i class="fa fa-arrow-left"></i> Back</a>
					 </div> 
			</form>
		</div>
		';
		
	}
	
	public function echo_new_pw_screen(){
		return '
		<div class="login_panel_head">
			<h3 class="login_panel_head_title">Write a new password</h3>
		</div>
		<div class="login_panel_body">
			<form role="form" action="logincheck.php" method="post">
						<input name="what_to_do" type="hidden" value="set_new_password">
						<div class="login_group">
							<span>Remember that password should be at lest 3 characters long. </span>
						</div>
						<div class="login_group">
							<input class="login_control" name="password" placeholder="New password" type="password" id="new_pw_1" autofocus>
						</div>
						<div class="login_group">
							<input class="login_control" name="password" placeholder="Confirm new password" type="password" id="new_pw_2" autofocus>
						</div>
						<div class="login_group">
						</div>
						<div id="different_pw" class="warning_message">
							<span>Passwords are different - please write the same password in both fields </span>
						</div>
						<input id="submitbutton" type="submit" value="Accept" class="login_submit_button">
						
						<!-- 
						<div class="login_checkbox">
							<a href="login.php"><i class="fa fa-arrow-left"></i> Back</a> 
						</div>
						-->					 
			</form>
		</div>
		';
		
	}
	
	public function echo_login_locked(){
		return '
		<div class="login_panel_head">
			<h3 class="login_panel_head_title">Panel Lock Down</h3>
        </div>
		<div class="login_panel_body">
			<p>Several failed attempts to log in have been made on this account and we suspect you may be a hacker bot.</p>
			<p>Please email your usual contact at link2light to reset this block.</p>
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
	public function echo_forgot_password_fail(){
		return '
		<div class="row">
                <div class="col-md-4 col-md-offset-4">
                   	<div class="panel panel-danger">
                        <div class="panel-heading">
                            Error
                        </div>
                        <div class="panel-body">
                            The username you entered was incorrect. Please try again.	
                        </div>
                    </div>
                </div>			
			</div>
		';
		
	}
	
	public function echo_fail_to_write_new_pw(){
		return '
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-red">
                    <div class="login_panel_head">
                        <h3 class="login_panel_head_title">Error</h3>
                    </div>
					<div class="login_panel_body">
					The password did not change. Please try again.					
					</div>
				</div>
			</div>
		</div>	
		';
		
	}
	
	
	public function echo_register_screen(){
		return '
		<div class="login_panel_head">
			<h3 class="login_panel_head_title">Register for free</h3>
		</div>
		<div class="login_panel_body">
			<form role="form" action="logincheck.php" method="post">
						<input name="what_to_do" type="hidden" value="register_account">
						<div class="login_group">
							<span>Remember that password should be at lest 3 characters long. </span>
						</div>
						<div class="login_group">
							<input class="login_control" name="login_user" placeholder="Email" type="email" >
						</div>
						<div id="email_exists" class="hidden">
							This email has been already used.
						</div>
						<div class="login_group">
							<input class="login_control" name="name_user" placeholder="Nazwa" type="text" >
						</div>
						<div class="login_group">
							<input class="login_control" name="password_user" placeholder="Your password" type="password" id="new_pw_1" >
						</div>
						<div class="login_group">
							<input class="login_control" name="password_user" placeholder="Confirm your password" type="password" id="new_pw_2" >
						</div>
						<div id="different_pw" class="warning_message">
							<span>Passwords are different - please write the same password in both fields </span>
						</div>
						<input id="submitbutton" type="submit" value="Accept" class="login_submit_button">
						
						<!-- 
						<div class="login_checkbox">
							<a href="login.php"><i class="fa fa-arrow-left"></i> Back</a> 
						</div>
						-->					 
			</form>
		</div>
		';
		
	}
	
}

/*
*	--- End of OOP way ---
*/

?>