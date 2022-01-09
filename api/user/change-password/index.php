<?php

session_start();
if(!isset($_SESSION['ID']))
	header('Location : /');

$data=json_decode(file_get_contents('php://input'),true);

if(
	!(isset($data['old'])
	&& isset($data['new']))
){
	http_response_code(400);
	die('Se deben proveer la contraseña anterior y una nueva.');
}
if(
	!(strlen($data['old'])>10
	&& strlen($data['new'])>10)
){
	http_response_code(400);
	die('La contraseña debe tener más de 10 caracteres.');
}

foreach (['old','new'] as $value) {
	$$value=trim($data[$value]);
}

define('DR',$_SERVER['DOCUMENT_ROOT']);
require DR.'/api/utils/db.php';

$old_hash=$db->query("SELECT `password` FROM `user` WHERE `ID`=".$_SESSION['ID'])->fetch_array()[0];
if(!password_verify($old,$old_hash)){
	http_response_code(401);
	die('Contraseña incorrecta.');
}

$res=$db->query("UPDATE `user` SET `password`='".password_hash($new,PASSWORD_DEFAULT)."' WHERE `ID`=".$_SESSION['ID']);
if($res && $db->affected_rows()){
	session_regenerate_id();
	echo 1;
}else echo 0;

?>