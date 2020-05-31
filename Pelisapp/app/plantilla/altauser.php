<?php

// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
?>
<span class='error'><?= $datos->mensajerror ?></span>
<form name='ALTA' method="POST" action="index.php?orden=Registrarse">

Nombre     : <input type="text" name="nombre" value="<?=$datos->nombre ?>"><br>
Contraseña : <input type="password" name="password" value="<?=$datos->password ?>"><br>
Repetir Contraseña : <input type="password"  name="password2" value="<?$datos->password2 ?>"><br>
Correo electrónico : <input type="text"    name="email" value="<?=$datos->email ?>" ><br>

Plan <select name="plan">
	<option value="Basico" selected="selected">Básico</option>
	<option value="Profesional" >Profesional</option>
	<option value="Premium" >Premium</option>
    <option value="Master" >Máster</option>
</select><br>
	<input type="submit" value="Almacenar">
	<input type="cancel" value="Cancelar" size="10" onclick="javascript:window.location='index.php'" >
</form>
<?php 
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>