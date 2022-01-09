<?php

session_start(['read_and_close'=>true]);
if(!isset($_SESSION['ID']))
	header('Location : /');

$data=json_decode(file_get_contents('php://input'),true);

if(
	!(isset($data['usuario'])
	&& isset($data['cantidad'])
	&& (int)$data['usuario']
	&& (int)$data['cantidad']
	&& (int)$data['cantidad']>0)
){
	http_response_code(400);
	die('Para enviar tokens se necesitan designar otro usuario y una cantidad válida.');
}

foreach (['usuario','cantidad'] as $value) {
	$$value=(int)$data[$value];
}

if($usuario==$_SESSION['ID']){
	http_response_code(400);
	die('No puede enviarse tokens a sí mismo.');
}

define('DR',$_SERVER['DOCUMENT_ROOT']);
require DR.'/api/utils/db.php';

if(!$db->query("SELECT * FROM `user` WHERE `ID`=$usuario")->num_rows){
	http_response_code(400);
	die('Debe especificarse un usuario existente.');
}

$currentTokens=$db->query("SELECT COUNT(*) FROM token WHERE ownerID=".$_SESSION['ID'])->fetch_array()[0];
if($currentTokens<$cantidad){
	http_response_code(400);
	die('No puede dar más tokens de los que tiene.');
}

$res=$db->query("UPDATE `token` SET `ownerID`=$usuario WHERE `ownerID`={$_SESSION['ID']} LIMIT $cantidad");
echo (int)($res && $db->affected_rows()==$cantidad);

?>