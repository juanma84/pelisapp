<?php
include_once 'app/Pelicula.php';
include_once 'app/modeloPeliDB.php';
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];

?>
<?php
if (isset($_GET["mensaje"])) {
	echo $_GET['mensaje'];
}

?>
<span class="error"><?php echo $datos->mensajerror ?></span>
<form class="form-horizontal" method="POST" action="index.php?orden=GuardarPeli" enctype="multipart/form-data">
	<div class="form-group">
		<label for="nombre" class="col-sm-2 control-label">Nombre</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="nombre" name='nombre'>
			
		</div>
	</div>

	<div class="form-group">
		<label for="director" class="col-sm-2 control-label">Director</label>
		<div class="col-sm-10">
			<input type="director" class="form-control" id="director" name="director" >
		</div>
	</div>

	<div class="form-group">
		<label for="genero" class="col-sm-2 control-label">genero</label>
		<div class="col-sm-10">
			<select id="genero" name="genero">
				<option value="ciencia ficcion">Ciencia Ficción</option>
				<option value="suspense">Suspense</option>
				<option value="aventuras">Aventuras</option>
				<option value="terror">Terror</option>
				<option value="drama">Drama</option>
				<option value="belica">Bélica</option>
				<option value="comedia">Comedia</option>
				
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="imagen" class="col-sm-2 control-label">imagen (tamaño maximo 2 MB )</label>
		<div class="col-sm-10">
			<input type="file" class="form-control" id="imagen" name="imagen">
		</div>
	</div>

	<div class="form-group">
		<label for="imagen" class="col-sm-2 control-label">trailer(codigo embebido de la url de origen)</label></br>
		<label for="imagen" class="col-sm-2 control-label">ejemplo:'https://www.youtube.com/embed/Rb_Puc7JzZ4'</label></br>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="trailer" name="trailer">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name='guardar' class="btn btn-primary">Guardar</button>
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