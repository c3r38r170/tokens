<?php

class dbcon{
	const NUM=-1;
	const BOTH=0;
	const ASSOC=1;
	var $dblink;
	var $error;
	
	function __construct($database){
		if(!defined('DR'))
			define('DR',$_SERVER['DOCUMENT_ROOT']);
		$parts=explode('/',DR);
		array_pop($parts);
		$data=parse_ini_file(join('/',$parts).'/config.ini',true)['mysql'];
		$this->dblink = new mysqli($data['host'],$data['user'],$data['password'],$database);//$dbhost, $dbuser, $dbpass, $dbname);
		$this->dblink->set_charset("utf8mb4");
		register_shutdown_function(array(&$this, 'destruct'));
	}
	function destruct(){
		$this->dblink->close();
	}
	function query($q){
		$result=$this->dblink->query($q);
		if($this->dblink->error){
			throw new Exception("MySQL error {$this->dblink->error}
Query: \"$q\"
",$this->dblink->errno);
		}
		return $result;
	}
	function result($consulta,$fila=0,$columna=0){
		if(is_string($consulta)){
			if(!($consulta=trim($consulta)))
				return false;
			$consulta=$this->query($consulta);
		}
		$consulta->data_seek($fila);
		$result=$consulta->fetch_array();
		return $result[$columna];
	}
	function insert_id(){
		return $this->dblink->insert_id;
	}
	function affected_rows(){
		return $this->dblink->affected_rows;
	}
	function prepared($q,$t,$a,$o=0){ //o...?  options?? TODO investigar prepared statements parameters
		if($s=$this->dblink->prepare($q)){
			$error=false;
			if(is_array($a)?$s->bind_param($t,...$a):$s->bind_param($t,$a))
				if($s->execute())
					switch(strtoupper(substr($q,0,3))){
						case 'INS':
						case 'UPD':
						case 'DEL':
							$a=$s->affected_rows;
							$s->close();
							return $a;
							break;
						case 'SEL':
							$a=$s->get_result();
							$s->close();
							return $a?:false;
							break;
						default:
							$this->error='Not prepared statement-supported query type.';
							break;
					}
				else $error=true;
			else $error=true;
			if($error)
				$this->error=$s->error;
			return false;
		}else $this->error=$this->dblink->error;
		return false;
	}
	function lastQuerySuccess(){
		return $this->dblink->sqlstate=='00000';
	}
}

$db=new dbcon('tokens');

?>