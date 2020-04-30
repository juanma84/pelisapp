<?php
include_once 'app/Pelicula.php';
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];

?>
			<?php 
			if(isset($_GET["mensaje"])){
				echo $_GET['mensaje'];
			}
			
			?>
			<form class="form-horizontal" method="POST" action="index.php?orden=GuardarPeli" enctype="multipart/form-data">
				<div class="form-group">
					<label for="nombre" class="col-sm-2 control-label">Nombre</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="nombre" name="nombre" >
					</div>
				</div>
				
				<div class="form-group">
					<label for="director" class="col-sm-2 control-label">DDirector</label>
					<div class="col-sm-10">
						<input type="director" class="form-control" id="director" name="director" >
					</div>
				</div>
				
				<div class="form-group">
					<label for="genero" class="col-sm-2 control-label">genero</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="genero" name="genero" >
					</div>
				</div>
                
                <div class="form-group">
					<label for="imagen" class="col-sm-2 control-label">imagen</label>
					<div class="col-sm-10">
						<input type="file" class="form-control" id="imagen" name="imagen" >
					</div>
				</div>		
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<a href="index.php" class="btn btn-default">Regresar</a>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</div>
			</form>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>