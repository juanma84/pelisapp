<?php
include_once 'app/Pelicula.php';
include_once 'app/modeloPeliDB.php';
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];

?>
<?php
//if (isset($_GET["mensaje"])) {
//	echo $_GET['mensaje'];
//}

?>
<!--<span class="error"><?php echo $datos->mensajerror ?></span>-->
<form class="form-horizontal" method="POST" action="index.php?orden=Modificar" enctype="multipart/form-data">

    <input type="hidden" name="codigo_pelicula" value="<?php echo $datos->codigo_pelicula ?>">
	<div class="form-group">
		<label for="nombre" class="col-sm-2 control-label">Nombre</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="nombre" name='nombre'value="<?php echo $datos->nombre ?>">
			
		</div>
	</div>

	<div class="form-group">
		<label for="director" class="col-sm-2 control-label">Director</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="director" name="director" value="<?php echo $datos->director ?>">
		</div>
	</div>

	<div class="form-group">
		<label for="genero" class="col-sm-2 control-label">genero</label></br>
        <input type="text" class="form-control" id="genero" name="genero" value="<?php  echo $datos->genero?>">
	</div>

	<div class="form-group">
		<label for="imagen" class="col-sm-2 control-label">imagen</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="imagen" name="imagen" value="<?php echo $datos->imagen?>" readonly>
		</div>
	</div>

	<div class="form-group">
		<label for="trailer" class="col-sm-2 control-label">trailer(codigo embebido de la url de origen)</label></br>
		<label for="trailer" class="col-sm-2 control-label">ejemplo:'https://www.youtube.com/embed/Rb_Puc7JzZ4'</label></br>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="trailer" name="trailer" value="<?php echo $datos->trailer?>">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name='modificar' class="btn btn-primary">Modificar</button>
			</br></br>
			<a href="index.php?orden=VerPelis" class="boton">Cancelar</a>
		</div>
	</div>
</form>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>