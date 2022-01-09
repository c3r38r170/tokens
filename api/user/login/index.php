<?php

session_start();

$data=json_decode(file_get_contents('php://input'),true);
define('DR',$_SERVER['DOCUMENT_ROOT']);
require DR.'/api/utils/db.php';

$matchingUsers=$db->prepared("SELECT * FROM user WHERE nick=?",'s',$data['nick']);
if(!$matchingUsers->num_rows){
	http_response_code(404);
	die('Nombre de usuario incorrecto.');
}
while($user=$matchingUsers->fetch_assoc()){
	if(password_verify($data['password'],$user['password'])){
		if(!$user['enabled']){
			http_response_code(403);
			die('Su usuario se encuentra deshabilitado.');
		}
		$_SESSION['ID']=$user['ID'];
		session_regenerate_id();
		die;
	}
}

http_response_code(401);
die('Contraseña incorrecta.');

?>