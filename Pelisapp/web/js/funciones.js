/**
 * Funciones auxiliares de javascripts 
 */
function confirmarBorrar(nombre,codigo_pelicula){
    
  if (confirm("¿Quieres eliminar la pelicula:  "+nombre+"?"))
  {
   document.location.href="index.php?orden=Borrar&codigo_pelicula="+codigo_pelicula;
  }
}

function validaAlta( formulario){
	return f.elements['clave1'] == f.elements['clave2'];
}

function confirmarBorrarFile(nombre){
	  if (confirm("¿Quieres eliminar el archivo:  "+nombre+"?"))
	  {
	   document.location.href="./index.php?operacion=Borrar&nombre="+nombre;
	  }
	}

function nuevoNombre(nombreantiguo){
		var nombrenuevo = prompt("Nuevo Nombre", nombreantiguo);
		if (nombrenuevo != nombreantiguo && nombrenuevo != null){
	       document.location.href="./index.php?operacion=Renombrar&nombre="+nombreantiguo+"&nombrenuevo="+nombrenuevo;	
		}	
}

