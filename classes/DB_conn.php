<?php
class DB_conn{
	private $conn;
	
	public function __construct(){
		$servername = "localhost";
		$username = "wierzacy";
		$password = "Tamara48@";
		$dbname = "wierzacy";
		
		if($this->conn =  new mysqli($servername, $username, $password, $dbname) ){			
			$this->conn->set_charset("utf8");
		}
		else{
			$this->conn->FALSE;
		}
			
	}
	
	function __destruct() {
       $this->conn->close();
   }
	
	public function give_db_conn(){
		
		return $this->conn;		
	}
	
	public function query($sql){
		if($result_sql =  $this->conn->query($sql) ){
			
			return $result_sql;
		}
		else{
			
			return FALSE ;	
		}
			
	}
	
	public function query_arr($sql){
		if( $result_sql =  $this->conn->query($sql) ){
			if( $result_sql->num_rows >= 1){
				while( $a_row = $result_sql->fetch_assoc()){
					$result_array[]=$a_row;	
				}
				return $result_array;
			}
			else{
				
				return FALSE;	
			}
		}
		else{
			
			return FALSE;	
		}
			
	}
	
	public function real_escape_string($string){
		
		return $this->conn->real_escape_string($string);			
	}
	
	public function close_connection(){
		
		$this->conn->close();
	}
	
	public function give_insert_id(){
		return $this->conn->insert_id;
	}
	
	public function prepare_bind_param($sql, $params_arr){
		$prepared_stat =  $this->conn->prepare($sql); 
		$ref    = new ReflectionClass('mysqli_stmt');
		$method = $ref->getMethod("bind_param");
		$method->invokeArgs($prepared_stat,$params_arr);
		$prepared_stat->execute();  
		$prepared_stat->close();  
		return true;
	}
	
}


?>