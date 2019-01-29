<?php
require_once("../content/header.php");
if(!isset($_SESSION["Client"])) header("Location: ../login.php");
$user_panel_screen = new Panel_screen();
?>
<script>
$(document).ready(function(){
	<?php
	if($user_panel_screen->get_action_name() == "quests_page"){
	?>
	$(".add_quest").click(function(){
		$.post("complicated_actions_solver.php",
			{
			  create_quest_form: true
			},
			function(data,status){
				if(status=="success"){					
					$(".right_contener").toggleClass("quest_form");										
					$(".right_contener").removeClass("with_quests");										
					$(".right_contener").html(data);										
				}
				else{
					alert("FAILURE !!!" + data + "\nStatus: " + status);
				}
		},	"html");	
	});
	
	$(".answ_yes").click(function(){
		var this_el =this;
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
					setTimeout(function(){this_el.parentNode.submit();}, 1500);
										
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
  <?php
	}
	elseif($user_panel_screen->get_action_name() == "vote_page_quests"){
  
  ?>
  	
	$(".answ_yes").click(function(){
		this.parentNode.submit();
	});
	
	$(".answ_no").click(function(){
		this.parentNode.submit();
	});
	
	$(".background_white").click(function(){
		$(".background_white").hide().fadeOut( 400 );
		$(".voting_popup ").hide().fadeOut( 400 );		
		$(".form_adding_quest ").hide().fadeOut( 400 );
	});
	
	$(document).keyup(function(e) {
	  if (e.keyCode === 27) {
			$(".background_white").hide().fadeOut( 400 );
			$(".voting_popup ").hide().fadeOut( 400 );
			$(".form_adding_quest ").hide().fadeOut( 400 );
	  }
	});
   
	$('.voting_new_quest').click(function(){
			var this_quest = $(this);
			var width_popup = $(window).width()/2- this_quest.next().width()/2;
			var height_popup = $(window).height()/2- this_quest.next().height()/2;	  
			this_quest.next().css("top", height_popup);
			this_quest.next().css("left", width_popup);
			this_quest.next().show();
			$(".background_white").show();		
			setTimeout(function(){this_quest.next().css("opacity","1");}, 10);
	});
	
	$('.voting_edited_quest').click(function(){
			var this_quest = $(this);
			var width_popup = $(window).width()/2- this_quest.next().width()/2;
			var height_popup = $(window).height()/2- this_quest.next().height()/2;	  
			this_quest.next().css("top", height_popup);
			this_quest.next().css("left", width_popup);
			this_quest.next().show();
			$(".background_white").show();		
			setTimeout(function(){this_quest.next().css("opacity","1");}, 10);
	});
	
	
	$('.voting_quests_to_edit').click(function(){
			var this_quest = $(this);
			$(".form_adding_quest").show();		
			$(".background_white").show();
			//$(".background_white").find(".voting_page_return_button");
			var width_popup = $(window).width()/2-$(".form_adding_quest").width()/2;
			var height_popup = $(window).height()/2- $(".form_adding_quest").height()/2;	  
			$(".form_adding_quest").css("top", height_popup);
			$(".form_adding_quest").css("left", width_popup);
			$(".form_adding_quest").find("input[name=\"edit_name\"]").val(this_quest.find("input[name=\"name_quest\"]").val());
			$(".form_adding_quest").find("textarea[name=\"edit_descr\"]").html(this_quest.find("input[name=\"descr_quest\"]").val());
			$(".form_adding_quest").find("input[name=\"edit_points\"]").val(this_quest.find("input[name=\"points_quest\"]").val());
			$(".form_adding_quest").find("input[name=\"edit_renewable_period_month\"]").val(this_quest.find("input[name=\"quest_renewable_period_month\"]").val());
			$(".form_adding_quest").find("input[name=\"edit_renewable_period_day\"]").val(this_quest.find("input[name=\"quest_renewable_period_day\"]").val());
			$(".form_adding_quest").find("input[name=\"edit_renewable_period_hour\"]").val(this_quest.find("input[name=\"quest_renewable_period_hour\"]").val());
			$(".form_adding_quest").find("input[name=\"edit_renewable_period_min\"]").val(this_quest.find("input[name=\"quest_renewable_period_min\"]").val());
			$(".form_adding_quest").find("input[name=\"edit_quest_id\"]").val(this_quest.find("input[name=\"id_quest\"]").val());
			
	});
	
	$('.voting_delete_quest ').click(function(){
			var this_quest = $(this);
			var width_popup = $(window).width()/2- this_quest.next().width()/2;
			var height_popup = $(window).height()/2- this_quest.next().height()/2;	  
			this_quest.next().css("top", height_popup);
			this_quest.next().css("left", width_popup);
			this_quest.next().show();
			$(".background_white").show();		
			setTimeout(function(){this_quest.next().css("opacity","1");}, 10);
	});
	
	
	$('.return_button_new_quest').click(function(){	
		$(".background_white").hide().fadeOut( 400 );
		$(".form_adding_quest ").hide().fadeOut( 400 );
	});
	
	
   <?php
	}
	elseif($user_panel_screen->get_action_name() == "vote_page"){
  
  ?>
  
  $(".vote_option").click(function(){
	   var this_option = $(this);
	   if(this_option.find( "input[name='vote_action_name']" ).val()== "add_edit_quest"){
		  window.location.href="user_panel.php?redirect=vote_page_quests";
			
	   }
	   else if (this_option.find( "input[name='vote_action_name']" ).val()== "new_incomer"){
		   window.location.href="user_panel.php?redirect=vote_page_incomers";
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
 
   <?php
	}
	elseif($user_panel_screen->get_action_name() == "vote_page_incomers"){
  
  ?>
 
	$(".answ_yes").click(function(){
		this.parentNode.submit();
	});
	
	$(".answ_no").click(function(){
		this.parentNode.submit();
	});
	
	$(".background_white").click(function(){
		$(".background_white").hide().fadeOut( 400 );
		$(".voting_popup ").hide().fadeOut( 400 );		
	});
	
	$(document).keyup(function(e) {
	  if (e.keyCode === 27) {
			$(".background_white").hide().fadeOut( 400 );
			$(".voting_popup ").hide().fadeOut( 400 );
	  }
	});
  
  $('.voting_incomer ').click(function(){
			var this_quest = $(this);
			var width_popup = $(window).width()/2- this_quest.next().width()/2;
			var height_popup = $(window).height()/2- this_quest.next().height()/2;	  
			this_quest.next().css("top", height_popup);
			this_quest.next().css("left", width_popup);
			this_quest.next().show();
			$(".background_white").show();		
			setTimeout(function(){this_quest.next().css("opacity","1");}, 10);
	});
	
	
	$('.return_button_new_quest').click(function(){	
		$(".background_white").hide().fadeOut( 400 );
		$(".voting_popup ").hide().fadeOut( 400 );
	});
 
   <?php
	}
  ?>
  
  
});
</script>
<div class="big_contener">
	<?php require_once("../content/user_left_menu.php"); ?>
	<?php $user_panel_screen->create_main_content(); ?>
	<?php	require_once("../content/footer.php"); ?>
</div>