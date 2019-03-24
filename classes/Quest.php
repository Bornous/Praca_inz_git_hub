<?php
class Quest{
	private $id_quest;
	private $name_quest;
	private $descr_quest;
	private $points_quest;
	private $renewable_period_quest_string;
	private $renewable_period_quest_arr;
	private $date_last_execution;
	private $activation_status_quest;
	private $edit_str_quest;
	private $premium_multiplier;
	
	public  function __construct($data_arr) {
		$this->id_quest= $data_arr["id_quest"];
		$this->name_quest= $data_arr["name_quest"];
		$this->descr_quest= $data_arr["descr_quest"];
		$this->points_quest= $data_arr["points_quest"];
		$this->renewable_period_quest_string= $data_arr["renewable_period_quest"];
		$this->renewable_period_quest_arr= $this->explode_data_str($data_arr["renewable_period_quest"],false);
		$this->date_last_execution= $this->explode_data_str($data_arr["date_execution"]);
		$this->activation_status_quest= $data_arr["activation_status_quest"];
		$this->edit_str_quest= $data_arr["edit_str_quest"];
		$this->premium_multiplier = 1;
	}
	
	public function display_quest($do_add_premium_multiplier = false){
		if($do_add_premium_multiplier){
			$points = $this->points_quest*$this->premium_multiplier;
			if($this->premium_multiplier > 1) $prem_points_class = "premium_points";
			else $prem_points_class = "";
			echo '
			<div class="name_quest">'.$this->name_quest.'</div>
			<div class="desc_quest"><span>'.$this->descr_quest.'</span></div>
			<div class="points_quest '.$prem_points_class.'"><span>'.$points.' pkt</span></div>
			<div class="form_inputs">
				<input type="hidden" name="id_quest" 			value="'.$this->id_quest.'">
				<input type="hidden" name="points_quest" 	value="'.$points.'">
				<input type="hidden" name="name_quest" 	value="'.$this->name_quest.'">
				<input type="hidden" name="descr_quest" 	value="'.$this->descr_quest.'">
				<input type="hidden" name="quest_renewable_period_month"  	value="'.$this->renewable_period_quest_arr["months"].'">
				<input type="hidden" name="quest_renewable_period_day"  		value="'.$this->renewable_period_quest_arr["days"].'">
				<input type="hidden" name="quest_renewable_period_hour"  	value="'.$this->renewable_period_quest_arr["hours"].'">
				<input type="hidden" name="quest_renewable_period_min"  		value="'.$this->renewable_period_quest_arr["minutes"].'">
			</div>';
		}
		else{
			echo '
			<div class="name_quest">'.$this->name_quest.'</div>
			<div class="desc_quest"><span>'.$this->descr_quest.'</span></div>
			<div class="points_quest"><span>'.$this->points_quest.' pkt</span></div>
			<div class="form_inputs">
				<input type="hidden" name="id_quest" 			value="'.$this->id_quest.'">
				<input type="hidden" name="points_quest" 	value="'.$this->points_quest.'">
				<input type="hidden" name="name_quest" 	value="'.$this->name_quest.'">
				<input type="hidden" name="descr_quest" 	value="'.$this->descr_quest.'">
				<input type="hidden" name="quest_renewable_period_month"  	value="'.$this->renewable_period_quest_arr["months"].'">
				<input type="hidden" name="quest_renewable_period_day"  		value="'.$this->renewable_period_quest_arr["days"].'">
				<input type="hidden" name="quest_renewable_period_hour"  	value="'.$this->renewable_period_quest_arr["hours"].'">
				<input type="hidden" name="quest_renewable_period_min"  		value="'.$this->renewable_period_quest_arr["minutes"].'">
			</div>';
		}
		
		
	}
	public function display_quest_on_voting_page($mode = "default"){
		switch($mode){
			case "default":
									echo '
										<div class="name_quest"><span>Nazwa: </span><span>'.$this->name_quest.'</span></div>
										<div class="desc_quest"><span>Opis: </span><span>'.$this->descr_quest.'</span></div>
										<div class="renewable_period"><span>Czas odnowienia: </span><span>'.$this->renewable_period_quest_arr["months"].' miesięcy '.$this->renewable_period_quest_arr["days"].' dni '.$this->renewable_period_quest_arr["hours"].' godzin '.$this->renewable_period_quest_arr["minutes"].' minut <span></div>
										<div class="points_quest"><span>Ilość przydzielanych punktów: </span><span>'.$this->points_quest.' pkt</span></div>
										<div><input type="hidden" name="id_quest" value="'.$this->id_quest.'"><input type="hidden" name="points_quest" value="'.$this->points_quest.'"></div>
									';
									break;
			case "edit":
									$data_arr_arr=explode(", ",$this->edit_str_quest);
									foreach($data_arr_arr as $a_row){
										$data_arr=explode("=",$a_row);
										$key_name =trim(trim($data_arr[0]),"`") ;
										$value_name =trim(trim($data_arr[1]),"'") ;
										//echo "key=".$key_name.":::value=".$value_name."<<<<";
										$edited_data_arr[$key_name]=$value_name;
										
									}
									//print_r($edited_data_arr);
									$edited_data_arr["renewable_period_quest_arr"]=$this->explode_data_str($edited_data_arr["renewable_period_quest"],false);
									$edited_name_quest=(($edited_data_arr["name_quest"]==$this->name_quest)?"":"<span> ->".$edited_data_arr["name_quest"]."</span>");
									$edited_descr_quest=($edited_data_arr["descr_quest"]==$this->descr_quest)?"":"<p><span>Nowy opis:</span><span>".$edited_data_arr["descr_quest"]."</span></p>";
									$edited_renewable_period_quest=($edited_data_arr["renewable_period_quest"]==$this->renewable_period_quest_string)?"":"<span> -->".$edited_data_arr["renewable_period_quest_arr"]["months"]." miesięcy ".$edited_data_arr["renewable_period_quest_arr"]["days"]." dni ".$edited_data_arr["renewable_period_quest_arr"]["hours"]." godzin ".$edited_data_arr["renewable_period_quest_arr"]["minutes"]." minut </span>";
									
									$edited_points_quest=($edited_data_arr["points_quest"]==$this->points_quest)?"":"<span> ->".$edited_data_arr["points_quest"]."</span>";
									
									
									echo '
										<div class="name_quest">
											<span>Nazwa: </span><span>'.$this->name_quest.'</span>'.$edited_name_quest.'
										</div>
										<div class="desc_quest">
											<span>Opis: </span><span>'.$this->descr_quest.'</span>'.$edited_descr_quest.'
										</div>
										<div class="renewable_period">
											<span>Czas odnowienia: </span>
											<span>'.$this->renewable_period_quest_arr["months"].' miesięcy '.$this->renewable_period_quest_arr["days"].' dni '.$this->renewable_period_quest_arr["hours"].' godzin '.$this->renewable_period_quest_arr["minutes"].' minut </span>'.$edited_renewable_period_quest.'
										</div>
										<div class="points_quest">
											<span>Ilość przydzielanych punktów: </span><span>'.$this->points_quest.' pkt</span>'.$edited_points_quest.'
										</div>
										<div>
											<input type="hidden" name="id_quest" value="'.$this->id_quest.'"><input type="hidden" name="points_quest" value="'.$this->points_quest.'">
										</div>
									';
									break;
		}
		
	}
	
	public function calculate_last_execution(){
		$now_arr = array(
											"years" => (int)date("Y"),
											"months"=>(int)date("n"),
											"days" =>  (int)date("j"),
											"hours" => (int)date("G"),
											"minutes" => (int)date("i"),
											"seconds" =>  (int)date("s")
		);

		$years 	= $now_arr["years"] - $this->date_last_execution["years"];
		$months = $years*12 +$now_arr["months"] - $this->date_last_execution["months"];
		$days 		= $now_arr["days"] - $this->date_last_execution["days"];
		$hours 	= $now_arr["hours"] - $this->date_last_execution["hours"];
		$minutes 	= $hours*60 + $now_arr["minutes"] - $this->date_last_execution["minutes"];
		$seconds 	= $minutes*60 + $now_arr["seconds"] - $this->date_last_execution["seconds"];
		
		$time = array(
									"months" => $months,
									"days" => $days,
									"seconds" => $seconds
		);
		
		return $this->compare_time_to_renewable_period($time, $now_arr);

	}
	
	private function compare_time_to_renewable_period($_time, $_now_arr){
		
		
		if($_time["days"]<0 ){
			$_time["months"]--;
			$starting_point_y=$_now_arr["years"];
			if($_now_arr["months"]-1<1){
				$starting_point_m=12;
				$starting_point_y--;
			}
			else $starting_point_m=$_now_arr["months"]-1;
			$starting_point= array ("years" => $starting_point_y, "months" => $starting_point_m);
			$ending_point= array ("years" => $_now_arr["years"], "months" => $_now_arr["months"]);
			
			$_time["days"]+=$this->months_to_days($starting_point, $ending_point);
			
		}
		
		if($_time["seconds"]<0){
			$_time["days"]--;
			$_time["seconds"]+=86400; //24x60x60
		}
		$dif_m = $_time["months"]-$this->renewable_period_quest_arr["months"];
		$dif_d = $_time["days"]-$this->renewable_period_quest_arr["days"];
		$renewable_period_seconds = $this->renewable_period_quest_arr["hours"]*3600+$this->renewable_period_quest_arr["minutes"]*60+$this->renewable_period_quest_arr["seconds"];
		$dif_s =$_time["seconds"]-$renewable_period_seconds;
		if( $dif_m>=0	&&	$dif_d >=0	&&	$dif_s >=-30 ){
			
			return $this->how_many_periods($dif_m, $dif_d, $dif_s);
		}		
		
		else return false;
		
	}
	
	private function how_many_periods($_dif_m, $_dif_d, $_dif_s){
		
		$main_counter = 0;
		if($this->renewable_period_quest_arr["months"]>0){
			$main_counter = $_dif_m/$this->renewable_period_quest_arr["months"];
		}
		elseif($this->renewable_period_quest_arr["days"]>0){
			$main_counter = $_dif_d/$this->renewable_period_quest_arr["days"];
		}
		else{
			$renewable_period_seconds = $this->renewable_period_quest_arr["hours"]*3600+$this->renewable_period_quest_arr["minutes"]*60+$this->renewable_period_quest_arr["seconds"];
			$main_counter = $_dif_s/$renewable_period_seconds;
		}
		
		if($main_counter >0)	$this->premium_multiplier = $main_counter;
		return true;
	}
	
	private function explode_data_str($_datetime_string, $is_year = true){
	
		$datetime = explode(" ",$_datetime_string);
		$date_arr = explode("-",$datetime[0]);
		$time_arr = explode(":",$datetime[1]);
		if($is_year)		$result_arr = array(
																	"years" => (int)$date_arr[0],
																	"months"=> (int)$date_arr[1],
																	"days" =>  (int)$date_arr[2],
																	"hours" => (int)$time_arr[0],
																	"minutes" => (int)$time_arr[1],
																	"seconds" =>  (int)$time_arr[2]
		);
		else				$result_arr = array(
																	"months"=> (int)$date_arr[0],
																	"days" =>  (int)$date_arr[1],
																	"hours" => (int)$time_arr[0],
																	"minutes" => (int)$time_arr[1],
																	"seconds" =>  (int)$time_arr[2]
		);		
				
		return $result_arr;
	}
	
	private function months_to_days($_starting_point, $_ending_point){
		$starting_point_month = $_starting_point["months"];
		$starting_point_year = $_starting_point["years"];
		
		$ending_point_month =  $_ending_point["months"];
		$ending_point_year =  $_ending_point["years"];

		
		$days=0;
		while($starting_point_year!=$ending_point_year && $starting_point_month!=$ending_point_year){
			if($starting_point_month == 2){
				if($starting_point_year%4==0) $days+=29;
				else $days+= 28;
				if($starting_point_year%100==0) $days--;
				if($starting_point_year%400==0) $days++;
				
			}
			else{
					if($starting_point_month==1 || $starting_point_month==3 || $starting_point_month==5 || $starting_point_month==7 || $starting_point_month==8 || $starting_point_month==10 || $starting_point_month==12) $days+=31;
					if($starting_point_month==4 || $starting_point_month==6 || $starting_point_month==9 || $starting_point_month==11) $days+=30;
			}
			$starting_point_month++;
			if($starting_point_month==13){
				$starting_point_month=1;
				$starting_point_year++;
			}
		}
		return $days;
	}
	
	public function get_id_quest(){
		return $this->id_quest;
	}
	public function is_deleting_in_progress(){
		$sql_search = "SELECT COUNT(`id_voting_process`) as 'number' FROM `inz_voting_system` WHERE `voting_subject`='quest_delete%id_quest%". $this->get_id_quest()."' AND `voting_status`='1' ";
		if($result = $_SESSION["DB_connection"]->query_arr($sql_search)){
			return $result[0]["number"]; 
		}
		else{
			return 0;
		}
	}
}
?>