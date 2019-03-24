<?php
class Panel_group_choice_screen{
	private $action_name;
	
	public  function __construct() {
		if( isset($_POST["action_name"]) ){
			$this->action_name = $_POST["action_name"];
		}
		else{
			$this->action_name = "main_choice";
		}
	}
	
	public function create_main_choice_page(){
		echo '
			<div class="boxes_contener">
				<form action="#" method="POST">
					<input type="hidden" name="action_name" value="new_group">
					<div class="box_1" onclick="javascript:this.parentNode.submit();">Stwórz grupę</div>
				</form>
				<form action="#" method="POST">
					<input type="hidden" name="action_name" value="join_group">
					<div class="box_2" onclick="javascript:this.parentNode.submit();">Dołącz do grupy</div>
				</form>
				<div class="clear_both">
				</div>
			</div>';
	}
	
	public function create_new_group_page(){
		echo '<form action="../user_panel/complicated_actions_solver.php" method="POST">';
		echo '<div class="new_group_form">';
		echo '		<div class="new_group_form_title">Utwórz grupę</div>';
		echo '		<div class="new_group_input">';
		echo '			<input type="text" name="group_name" autofocus="autofocus" placeholder="Nazwa"/>';
		echo '		</div>';
		echo '		<div class="new_group_submit_button" onclick="javascript:this.parentNode.parentNode.submit();">Zatwierdź</div>';
		echo '</div>';
		echo '</form>';
	}
	
	public function create_join_group_page(){
		echo '<form action="../user_panel/complicated_actions_solver.php" method="POST">';
		echo '<div class="new_group_form">';
		echo '		<div class="new_group_form_title">Dołącz do grupy</div>';
		echo '		<div class="new_group_input"><span>Wprowadź email użytkownika, który należy do grupy, do której chcesz się dostać:</span></div>';
		echo '		<div class="new_group_input email_centered">';
		echo '			<input type="email" name="group_user" autofocus="autofocus" placeholder="Email"/>';
		echo '		</div>';
		echo '		<div class="new_group_submit_button" onclick="javascript:this.parentNode.parentNode.submit();">Zatwierdź</div>';
		echo '</div>';
		echo '</form>';
	}
	
	public function create_main_content(){
		switch($this->action_name){
			
			case "main_choice":
				$this->create_main_choice_page();				
				break;
			case "new_group":
				$this->create_new_group_page();				
				break;
			case "join_group":
				$this->create_join_group_page();				
				break;
			default:
				$this->create_main_choice_page();				
				break;			
		}
	}
	
}
?>