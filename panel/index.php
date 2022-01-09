<?php
	
session_start(['read_and_close'=>true]);
if(!isset($_SESSION['ID']))
	header('Location : /');

define('DR',$_SERVER['DOCUMENT_ROOT']);
require DR.'/api/utils/db.php';
	
$cantidadTokens=$db->query("SELECT COUNT(*) FROM `token` WHERE `ownerID`=".(int)$_SESSION['ID'])->fetch_row()[0];
$permisos=$db->query("SELECT `permID` FROM `users_perms` WHERE `userID`=".(int)$_SESSION['ID'])->fetch_all(MYSQLI_NUM);
$users=$db->query("SELECT * FROM `user` WHERE `enabled`=1")->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sistema de Tokens</title>

	<script src="js/main.js" defer></script>
	<script>
		var tokens=<?=$cantidadTokens?>
			,tokensCirculating=<?=$db->query("SELECT COUNT(*) FROM `token`")->fetch_row()[0]?>
			,usuarios=<?=json_encode(array_map(fn($user)=>$user['nick'],$users))?>;
	</script>
<?php
	require_once DR.'/head-resources.html';
?>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	
<h1>Inicio</h1>
<p>Tus tokens: <b id="tokens"><?=$cantidadTokens?></b></p>
<fieldset>
	<legend>Administración de Tokens</legend>
	<details>
		<summary>Envío de tokens</summary>
		<form id=form-enviar>
			<fieldset >
			<input type="number" name="form-enviar-cantidad" id="form-enviar-cantidad" step=1 value=1 min=1 max=<?=$cantidadTokens?> required>
			<select name="form-enviar-usuario" id="form-enviar-usuario" required>
				<option value="0" >Elegir destinatario</option>
				<?php

	foreach($users as $user){
		if($user['ID']==$_SESSION['ID'])
			continue;
		echo "<option value={$user['ID']}>{$user['name']}</option>";
	}
	
	?>
				</select>
				<input type=submit value="Enviar tokens" id=form-enviar-submit disabled title="Debe elegir un usuario para enviarle tokens.">
			</fieldset>
		</form>
	</details>
<?php
if(in_array([1],$permisos)){
	?>
	<script src="js/modulos-permisos/crear-usuario.js" defer></script>
	<details>
		<summary>Crear usuario</summary>
		<form id=form-crear>
			<fieldset>
				<input type="text" placeholder="Nombre completo (ej: Juan Perez)" maxlength="100" name="form-crear-name" required minlength="2">
				<input type="text" placeholder="Nombre de usuario (ej: jperez, juan_perez, etc.)" pattern="[a-z0-9_.\-+$]+" minlength="6" maxlength="100" name="form-crear-nick" required>
				<small>Solo se permiten minúsculas, números y los símbolos _ . - + $</small>
				<input type="text" placeholder="Contraseña temporal" name="form-crear-pass" required minlength=10>
				<small>Recuerde que la contraseña debe usarse exactamente igual a como fue ingresada (mismos símbolos, mayúsculas, minúsculas, etc.).</small>
				<input type=submit value="Crear" id=form-crear-submit>
			</fieldset>
		</form>
	</details>
	<?php
}

if(in_array([2],$permisos)){
	?>
	<script src="js/modulos-permisos/generar-tokens.js" defer></script>
	<details>
		<summary>Generar tokens</summary>
		<form id="form-generar">
			<fieldset>
				<legend>Generar tokens</legend>
				<input type="number" name="form-generar-cantidad" id="" min=1 step=1 max=100000>
				<input type=submit value=Generar >
				<p>Tokens en circulación: <b id=tokens-circulando></b></p>
			</fieldset>
		</form>
	</details>
<?php
}
?>
</fieldset>
<fieldset>
	<legend>Administración de Usuario</legend>
	<details>
		<summary>Cambiar contraseña</summary>
		<form id=form-contraseña>
			<fieldset>
				<input type="password" name="form-contraseña-old" placeholder="Contraseña actual" required minlength=10>
				<input type="password" name="form-contraseña-new-1" placeholder="Contraseña nueva" required minlength=10>
				<input type="password" name="form-contraseña-new-2" placeholder="Confirmar contraseña nueva" required minlength=10>
				<input type="submit" value="Cambiar contraseña" id=form-contraseña-submit>
			</fieldset>
		</form>
	</details>
	<button id=salir>Salir</button>
</fieldset>

</body>

</html>