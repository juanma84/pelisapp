<?php
include_once 'app/Pelicula.php';
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];

?>

<h3>PELICULAS ENCONTRADAS EN NUESTRA BASE DE DATOS</h3>
<?php if(empty($peliculas)){
    echo "Lo siento,película no incluida  en nuestra base de datos"."</br>";
}else{?>
<table>

<?php 
echo "<th>Código</th><th>Nombre</th><th>Director</th><th>Genero</th>";


     foreach ($peliculas as $peli) : ?>
<tr>		
<td><?= $peli->codigo_pelicula ?></td>
<td><?= $peli->nombre ?></td>
<td><?= $peli->director ?></td>
<td><?= $peli->genero ?></td>
<td><a href="#"
			onclick="confirmarBorrar('<?= $peli->nombre."','".$peli->codigo_pelicula."'"?>);">Borrar</a></td>
<td><a href="<?= $auto?>?orden=Modificar&codigo=<?=$peli->codigo_pelicula?>">Modificar</a></td>
<td><a href="<?= $auto?>?orden=Detalles&codigo=<?= $peli->codigo_pelicula?>">Detalles</a></td>
</tr>
<?php endforeach;} ?>
</table>
<br>

</form>
<form name='f3' action='index.php'>

<input type='hidden' name='orden' value='BuscarPeli'> 
<input type='submit' value='Buscar Película' ></br></br>
<a href="index.php?orden=VerPelis" class="boton">Regresar</a>

</form>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>