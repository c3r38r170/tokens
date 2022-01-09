<?php

session_start(['read_and_close'=>true]);
if(!isset($_SESSION['ID']))
	header('Location : /');

define('DR',$_SERVER['DOCUMENT_ROOT']);
require DR.'/api/utils/db.php';
	
if(!$db->query("SELECT * FROM `users_perms` WHERE `permID`=1 AND `userID`=".$_SESSION['ID'])->num_rows){
	http_response_code(403);
	die;
}

$data=json_decode(file_get_contents('php://input'),true);

if(
	!(isset($data['name'])
	&& isset($data['nick'])
	&& isset($data['pass']))
){
	http_response_code(400);
	die('Deben definirse nombre completo, de usuario y contraseña.');
}

foreach (['name','nick','pass'] as $value) {
	$$value=trim($data[$value]);
}

$name_len=strlen($name);
if($name_len<2||$name_len>100){
	http_response_code(400);
	die('El nombre completo debe tener entre 2 y 100 caracteres.');
}

if(preg_match('/[^a-z0-9_.\-+$]/',$nick)){
	http_response_code(400);
	die('El nombre de usuario puede estar compuesto de minúsculas, números y los símbolos _ . - + $.');
}
$nick_len=strlen($nick);
if($nick_len<2||$nick_len>100){
	http_response_code(400);
	die('El nombre de usuario debe tener entre 6 y 100 caracteres.');
}

$pass_len=strlen($pass);
if($pass_len<10){
	http_response_code(400);
	die('La contraseña debe tener más de 10 caracteres.');
}

if(
	$db->prepared("SELECT * FROM `user` WHERE `nick`=?",'s',$nick)->num_rows
){
	http_response_code(400);
	die('El nombre de usuario ya existe.');
}

$db->prepared("INSERT INTO `user` (`name`,`nick`,`password`) VALUES (?,?,'".password_hash($pass,PASSWORD_DEFAULT)."')",'ss',[$name,$nick]);
$insertID=$db->insert_id();
if($insertID)
	echo $insertID;
else{
	http_response_code(501);
}

?>