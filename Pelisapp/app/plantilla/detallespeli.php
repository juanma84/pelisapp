<?php
include_once 'app/Pelicula.php';
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];

?>
		
			
				<div class="form-group">
					<label for="nombre" class="col-sm-2 control-label">Nombre</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $peli->nombre ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label for="director" class="col-sm-2 control-label">Director</label>
					<div class="col-sm-10">
						<input type="director" class="form-control" id="director" name="director" value="<?php echo $peli->director ?>" >
					</div>
				</div>
				
				<div class="form-group">
					<label for="genero" class="col-sm-2 control-label">genero</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="genero" name="genero" value="<?php echo $peli->genero ?>" >
					</div>
				</div>
                
                <div class="form-group">
					<label for="imagen" class="col-sm-2 control-label">imagen</label>
					<div class="col-sm-10">
						<img id="imagen"  width="300" name="imagen" src="app/img/<?php echo $peli->imagen?>" >
					</div>
					<label for="trailer" class="col-sm-2 control-label">trailer</label>
					<div class="col-sm-10"></br></br>
					<iframe width="560" height="315" src="<?php echo $peli->trailer?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>		
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<a href="index.php?orden=VerPelis" class="boton">Regresar</a>
					</div>
				</div>
			
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>