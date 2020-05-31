<?php
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];

?>

<h1>error al borrar pelicula</h1>    
</body>
</html>

<a href="index.php?orden=VerPelis" class="boton">Regresar</a>

<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>