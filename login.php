<?php
if( @empty($_GET) ){
	session_start();
	// remove all session variables
	session_unset();

	// destroy the session
	session_destroy();
}	
include("content/header.php");
$login_screen_object = new Login_screen( isset($_GET['login']) ? $_GET['login'] : "just_login");
//echo  password_hash("1234abcd" , PASSWORD_BCRYPT); 

?>

<script>
$(document).ready(function() {
	
	var specialChars = "<>@!#$%^&*()_+[]{}?:;|'\"\\,./~`-=";
	var check = function(string){
		for(i = 0; i < specialChars.length;i++){
			if(string.indexOf(specialChars[i]) > -1){
				return true
			}
		}
		return false;
	}
<?php if($login_screen_object->get_event()=="just_login"){?>
/*login screen*/
	function check_to_submit_login(event){
		 if($('#un').val().length >2   && $('#pw').val().length >2 ) {
			
           $('input').removeClass('input_red_outliner');
           $('input[type="submit"]').prop('disabled', false);
		   return 1;
        }
		else
		{
			event.preventDefault();
			$('input[type="submit"]').prop('disabled', true);
			if($('#un').val().length <=2)$('#un').addClass("input_red_outliner");
			else   $('#un').removeClass('input_red_outliner');
			if($('#pw').val().length <=2)$('#pw').addClass("input_red_outliner");
			else   $('#pw').removeClass('input_red_outliner');
			return 0;			
		}
	}
	function check_un_pw_login(){
		 if($('#un').val().length >2 && $('#pw').val().length >2 ) {
			
           $('input').removeClass('input_red_outliner');
           $('input[type="submit"]').prop('disabled', false);
		   return 1;
        }
		else
		{
			$('input[type="submit"]').prop('disabled', true);
			if($('#un').val().length <=2 && $('#un').val().length >0 )$('#un').addClass("input_red_outliner");
			else if($('#un').val().length >0)   $('#un').removeClass('input_red_outliner');
			if($('#pw').val().length <=2 && $('#pw').val().length >0 )$('#pw').addClass("input_red_outliner");
			else  if($('#pw').val().length >0)   $('#pw').removeClass('input_red_outliner');
			return 0;			
		}
	}
	
     $('input[type="submit"]').click(function(event) {
        check_to_submit_login(event);
	
     });
	
     $('#un').keyup(function() {
      check_un_pw_login();
     });
	 
     $('#pw').keyup(function() {
       check_un_pw_login();
     });
<?php
}
elseif($login_screen_object->get_event()=="register" )
{
?>
 /* register screen */
	function check_all_for_submit(event){
		var submit = true;
		/*Email*/
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email = $('input[type="email"]').val();
		 if( regex.test(  email.trim()  ) ) {
			 $('input[type="email"]').removeClass('input_red_outliner');
		 }
		 else{
			 $('input[type="email"]').addClass('input_red_outliner');
			 submit = false;
		 }
		 /*User name*/
		 if($('input[name="name_user"]').val().length >2 && check($('input[name="name_user').val())==false){
			 $('input[name="name_user"]').removeClass('input_red_outliner');
		}
		else{
			$('input[name="name_user"]').addClass('input_red_outliner');
			submit = false;
		}		
		 /*Password_1*/
		 if($('#new_pw_1').val().length >2){
			 $('#new_pw_1').removeClass('input_red_outliner');
		}
		else{
			$('#new_pw_1').addClass('input_red_outliner');
			submit = false;
		}
		 /*Password_2*/
		 if($('#new_pw_2').val().length >2){
			 $('#new_pw_2').removeClass('input_red_outliner');
		}
		else{
			$('#new_pw_2').addClass('input_red_outliner');
			submit = false;
		}
		/*Both passwords */
		if($('#new_pw_1').val() == $('#new_pw_2').val()){
			 $('#different_pw').addClass('hidden');
		}
		else{
			$('#different_pw').removeClass('hidden');
			submit = false;
		}
		
		/* To submit or not to submit */
		 if(submit ){
			  $('input[type="submit"]').prop('disabled', false);
		 }
		 else{
			 event.preventDefault();
			  $('input[type="submit"]').prop('disabled', true);
		 }
		
	}
	
	function check_all_for_submit_without_outliner(){
		var submit = true;
		
		/*Email*/
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email = $('input[type="email"]').val();
		 if( regex.test(  email.trim()  ) ) {
			 
		 }
		 else{
			
			 submit = false;
		 }
		 /*First name*/
		 if($('input[name="name_user"]').val().length <=2  && check($('input[name="name_user').val())==false){
			submit = false;
		}
		 /*Password_1*/
		 if($('#new_pw_1').val().length <=2){
			submit = false;
		}
		 /*Password_2*/
		 if($('#new_pw_2').val().length <=2){
			submit = false;
		}
		/*Both passwords */
		if($('#new_pw_1').val() != $('#new_pw_2').val()){
			submit = false;
		}
		
		/* To submit or not to submit */
		 if(submit ){
			  $('input[type="submit"]').prop('disabled', false);
		 }
		 else{
			  $('input[type="submit"]').prop('disabled', true);
		 }
		
	}
	
	 $('input[type="submit"]').click(function(event) {
        check_all_for_submit(event);
	
     });
	 
	
	 $('input[type="email"]').keyup(function() {
		 var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email_val = $('input[type="email"]').val();
		 if( regex.test(  email_val.trim()  ) ) {
			 $('input[type="email"]').removeClass('input_red_outliner');
			  check_all_for_submit_without_outliner();
			  $.ajax({
				  method: "POST",
				  url: "checking_on_reg_page.php",
				  data: { email: email_val, password: "df0090a4c59f141d203ef0dbd5710fc0"}
				}).done(function( is_this_email_exist ) {
					console.log(is_this_email_exist);
					if(is_this_email_exist=="1" ){
						$('#email_exists').removeClass('hidden');
					}
					else{
						$('#email_exists').addClass('hidden');
					}
				  });
		 }
		 else{
			$('#email_exists').addClass('hidden');
			$('input[type="email"]').addClass('input_red_outliner');
			$('input[type="submit"]').prop('disabled', true);
		 }
	 });
	 
	 $('input[name="name_user"]').keyup(function() {
		  if($('input[name="name_user"]').val().length >2 && check($('input[name="name_user').val())==false){
			 $('input[name="name_user"]').removeClass('input_red_outliner');
			  check_all_for_submit_without_outliner();
		}
		else{
			$('input[name="name_user"]').addClass('input_red_outliner');
			  $('input[type="submit"]').prop('disabled', true);
		}
	 });
	 
	 $('#new_pw_1').keyup(function() {
		  if($('#new_pw_1').val().length >2){
			 $('#new_pw_1').removeClass('input_red_outliner');
			  check_all_for_submit_without_outliner();
		}
		else{
			$('#new_pw_1').addClass('input_red_outliner');
			$('input[type="submit"]').prop('disabled', true);
		}
		if($('#new_pw_2').val().length >0){
			 if($('#new_pw_1').val() == $('#new_pw_2').val()){
				 $('#different_pw').addClass('hidden');
				  check_all_for_submit_without_outliner();
			}
			else{
				$('#different_pw').removeClass('hidden');
				$('input[type="submit"]').prop('disabled', true);
			}
		}
	 });
	 
	 $('#new_pw_2').keyup(function() {
		 if($('#new_pw_2').val().length >2){
			 $('#new_pw_2').removeClass('input_red_outliner');
			  check_all_for_submit_without_outliner();
		}
		else{
			$('#new_pw_2').addClass('input_red_outliner');
			$('input[type="submit"]').prop('disabled', true);
		}
		 if($('#new_pw_1').val() == $('#new_pw_2').val()){
			 $('#different_pw').addClass('hidden');
			  check_all_for_submit_without_outliner();
		}
		else{
			$('#different_pw').removeClass('hidden');
			$('input[type="submit"]').prop('disabled', true);
		}
	 });
		 
	  $('#different_pw').addClass('hidden');
	  
<?php 
}
elseif($login_screen_object->get_event()=="write_new_pw" ){
?>	 
	 $('#different_pw').addClass('hidden');
	 function check_all_for_submit(event){
		var submit = true;
		
		 /*Password_1*/
		 if($('#new_pw_1').val().length >2){
			 $('#new_pw_1').removeClass('input_red_outliner');
		}
		else{
			$('#new_pw_1').addClass('input_red_outliner');
			submit = false;
		}
		 /*Password_2*/
		 if($('#new_pw_2').val().length >2){
			 $('#new_pw_2').removeClass('input_red_outliner');
		}
		else{
			$('#new_pw_2').addClass('input_red_outliner');
			submit = false;
		}
		/*Both passwords */
		if($('#new_pw_1').val() == $('#new_pw_2').val()){
			 $('#different_pw').addClass('hidden');
		}
		else{
			$('#different_pw').removeClass('hidden');
			submit = false;
		}
		
		/* To submit or not to submit */
		 if(submit ){
			  $('input[type="submit"]').prop('disabled', false);
		 }
		 else{
			 event.preventDefault();
			  $('input[type="submit"]').prop('disabled', true);
		 }
		
	}
	
	 function check_all_for_submit_without_outliner(){
		var submit = true;
		
		
		 /*Password_1*/
		 if($('#new_pw_1').val().length <=2){
			submit = false;
		}
		 /*Password_2*/
		 if($('#new_pw_2').val().length <=2){
			submit = false;
		}
		/*Both passwords */
		if($('#new_pw_1').val() != $('#new_pw_2').val()){
			submit = false;
		}
		
		/* To submit or not to submit */
		 if(submit ){
			  $('input[type="submit"]').prop('disabled', false);
		 }
		 else{
			  $('input[type="submit"]').prop('disabled', true);
		 }
		
	}
	 
	  $('input[type="submit"]').click(function(event) {
        check_all_for_submit(event);
	
     });
	 
	 $('#new_pw_1').keyup(function() {
		  if($('#new_pw_1').val().length >2){
			 $('#new_pw_1').removeClass('input_red_outliner');
			  check_all_for_submit_without_outliner();
		}
		else{
			$('#new_pw_1').addClass('input_red_outliner');
			$('input[type="submit"]').prop('disabled', true);
		}
		if($('#new_pw_2').val().length >0){
			 if($('#new_pw_1').val() == $('#new_pw_2').val()){
				 $('#different_pw').addClass('hidden');
				  check_all_for_submit_without_outliner();
			}
			else{
				$('#different_pw').removeClass('hidden');
				$('input[type="submit"]').prop('disabled', true);
			}
		}
	 });
	 
	 $('#new_pw_2').keyup(function() {
		 if($('#new_pw_2').val().length >2){
			 $('#new_pw_2').removeClass('input_red_outliner');
			  check_all_for_submit_without_outliner();
		}
		else{
			$('#new_pw_2').addClass('input_red_outliner');
			$('input[type="submit"]').prop('disabled', true);
		}
		 if($('#new_pw_1').val() == $('#new_pw_2').val()){
			 $('#different_pw').addClass('hidden');
			  check_all_for_submit_without_outliner();
		}
		else{
			$('#different_pw').removeClass('hidden');
			$('input[type="submit"]').prop('disabled', true);
		}
	 });
		
<?php 
}
elseif($login_screen_object->get_event()=="forgot_password"){
?>
	   $('#fp').keyup(function() {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email = $('#fp').val();
        if( regex.test(  email.trim()  ) ) {/*
		if($('#fp').val().length >2 ){*/
           $('input[type="submit"]').prop('disabled', false);
        }
		else
		{
			$('input[type="submit"]').prop('disabled', true);	
		}
     });
	 
	  $('input[type="submit"]').click(function(event) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email = $('#fp').val();
        if( regex.test(  email.trim()  ) ) {/*
		if($('#fp').val().length >2 ){*/
           $('input[type="submit"]').prop('disabled', false);
        }
		else
		{
			event.preventDefault;
			$('input[type="submit"]').prop('disabled', true);	
		}
	
     });
	
	 
<?php
}else{
?>
	 
	 
<?php
}
?>

 });
</script>

		<div class="register register_button" ><a href="login.php?login=register">Register for free</a></div>
			
		<div class="login_wrapper">
						
			<div class="login_panel">
				<?php $login_screen_object->create_login_form();		?>
			</div>
						
			<?php	$login_screen_object->create_login_fail_message();	?>
		</div>


<?php

include("content/footer.php");
?>