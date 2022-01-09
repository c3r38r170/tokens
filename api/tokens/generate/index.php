<?php

session_start(['read_and_close'=>true]);
if(!isset($_SESSION['ID']))
	header('Location : /');

define('DR',$_SERVER['DOCUMENT_ROOT']);
require DR.'/api/utils/db.php';
	
if(!$db->query("SELECT * FROM `users_perms` WHERE `permID`=2 AND `userID`=".$_SESSION['ID'])->num_rows){
	http_response_code(403);
	die;
}

$data=json_decode(file_get_contents('php://input'),true);

if(
	!(isset($data['cantidad'])
	&& ($cantidad=(int)$data['cantidad'])
	&& $cantidad>0
	&& $cantidad<=100000)
){
	http_response_code(400);
	die('Se necesita designar una cantidad válida de tokens a generar.');
}

$res=$db->query("INSERT INTO `token` (`ownerID`) VALUES ".join(',',array_fill(0,$cantidad,"({$_SESSION['ID']})")));
echo (int)($res && $db->affected_rows()==$cantidad);

?>