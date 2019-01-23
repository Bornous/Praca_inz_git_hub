<?php
require_once("../content/header.php");
if(!isset($_SESSION["Client"])) header("Location: ../login.php");
$user_panel_screen = new Panel_screen();
print_r($_SESSION["Client"]);
?>
<script>
$(document).ready(function(){
/*
  $(".active_quest").click(function(){
	  var this_quest = $(this);
	  var response = confirm("Czy napewno wykonałeś to zadanie?");
	  if(response == true){
			var id_quest= this_quest.find( "input[name='id_quest']" ).val();
			$.post("complicated_actions_solver.php",
				{
				  id_quest: id_quest,
				  submit_quest: true
				},
				function(data,status){
					if(status=="success"){
						alert("Data: " + data + "\nStatus: " + status);
						this_quest.unbind("click");
						this_quest.removeClass("active_quest");
						this_quest.addClass("done_quest");
					}
					else{
						alert("FAILURE !!!" + data + "\nStatus: " + status);
					}
			});
	  }
  });
*/ 
	
	$(".answ_yes").click(function(){
		var id_quest = $(".confirm_popup").find("input[name=\"id_quest_val\"]").val();
		var points_quest = $(".confirm_popup").find("input[name=\"points_quest_val\"]").val();
		$.post("complicated_actions_solver.php",
			{
			  id_quest: id_quest,
			  points_quest: points_quest,
			  submit_quest: true
			},
			function(data,status){
				if(status=="success"){
					//alert("Data: " + data + "\nStatus: " + status+ "\nId_quest: " + id_quest);
					var width_popup = $(window).width()/2-$(".succesfull_completion_tick").width()/2;
					var height_popup = $(window).height()/2-$(".succesfull_completion_tick").height()/2;
					$(".text_popup").css("opacity",'0');
					$(".answers_box_popup").css("opacity",'0');
					$(".succesfull_completion_tick").css("z-index",'1');
					$(".succesfull_completion_tick").css("opacity",'1');
					//$(".confirm_popup").html("<div style=\"height:"+$(".confirm_popup").height()+"; width: "+$(".confirm_popup").width()+"; background-color:#3C9F40;font-size:3em;text-align:center\"><i class=\"fas fa-check\"></i></div>");
					//$(".confirm_popup").hide();
					setTimeout(function(){window.location="user_panel.php";}, 1500);
										
				}
				else{
					alert("FAILURE !!!" + data + "\nStatus: " + status);
				}
		},	"text");	
	});
	
	$(".answ_no").click(function(){
		$(".background_white").hide().fadeOut( 400 );
	  $(".confirm_popup").hide().fadeOut( 400 );
	});
	$(".background_white").click(function(){
		$(".background_white").hide().fadeOut( 400 );
		$(".confirm_popup").hide().fadeOut( 400 );
	});
	$(document).keyup(function(e) {
	  if (e.keyCode === 27) {
			$(".background_white").hide().fadeOut( 400 );
			$(".confirm_popup").hide().fadeOut( 400 );
	  }
	});
  $(".active_quest").click(function(){
	  var this_quest = $(this);
	  var quest_name = this_quest.find(".name_quest").html();
	  var id_quest = this_quest.find("input[name=\"id_quest\"]").val();
	  var points_quest = this_quest.find("input[name=\"points_quest\"]").val();
	  $("#quest_name_popup").html(quest_name+'<input type="hidden" name="id_quest_val" value="'+id_quest+'" />'+'<input type="hidden" name="points_quest_val" value="'+points_quest+'" />');
	  var width_popup = $(window).width()/2-$(".confirm_popup").width()/2;
	  var height_popup = $(window).height()/2-$(".confirm_popup").height()/2;
	  
	  $(".confirm_popup").css("top", height_popup);
	  $(".confirm_popup").css("right", width_popup);
	  $(".background_white").show();
	  $(".confirm_popup").show();
/*
	  if(response == true){
			var id_quest= this_quest.find( "input[name='id_quest']" ).val();
			$.post("complicated_actions_solver.php",
				{
				  id_quest: id_quest,
				  submit_quest: true
				},
				function(data,status){
					if(status=="success"){
						alert("Data: " + data + "\nStatus: " + status);
						this_quest.unbind("click");
						this_quest.removeClass("active_quest");
						this_quest.addClass("done_quest");
					}
					else{
						alert("FAILURE !!!" + data + "\nStatus: " + status);
					}
			});
	  }
*/
  });
  
  
  $(".vote_option").click(function(){
	   var this_option = $(this);
	   if(this_option.find( "input[name='vote_action_name']" ).val()== "add_edit_quest"){
		   $.post("complicated_actions_solver.php",
				{
				  load_all_group_quests: true
				},
				function(data,status){
					if(status=="success"){
						$(".right_contener").html(data);
					}
					else{
						alert("FAILURE !!!" + data + "\nStatus: " + status);
					}
			});
			console.log("vote_option_add_edit_quest");
			
	   }
	   else if (this_option.find( "input[name='vote_action_name']" ).val()== "new_incomer"){
		   $.post("complicated_actions_solver.php",
				{
				  load_new_incomers: true
				},
				function(data,status){
					if(status=="success"){
						$(".right_contener").html(data);
					}
					else{
						alert("FAILURE !!!" + data + "\nStatus: " + status);
					}
			});
	   }
	   else if (this_option.find( "input[name='vote_action_name']" ).val()== "voting_right"){
		   $.post("complicated_actions_solver.php",
				{
				  load_peoples_to_ban: true
				},
				function(data,status){
					if(status=="success"){
						$(".right_contener").html(data);
					}
					else{
						alert("FAILURE !!!" + data + "\nStatus: " + status);
					}
			});
	   }
	   else if (this_option.find( "input[name='vote_action_name']" ).val()== "quest_checking"){
		   $.post("complicated_actions_solver.php",
				{
				  load_quests_compliction_to_check: true
				},
				function(data,status){
					if(status=="success"){
						$(".right_contener").html(data);
					}
					else{
						alert("FAILURE !!!" + data + "\nStatus: " + status);
					}
			});
	   }
	   else{
	   console.log("vote_option_failed_to_rocognite");
	   }
  });
  
});
</script>
<div class="big_contener">
	<?php require_once("../content/user_left_menu.php"); ?>
	<?php $user_panel_screen->create_main_content(); ?>
	<?php	require_once("../content/footer.php"); ?>
</div>