<?php
// ------------------------------------------------
// Controlador que realiza la gestión de usuarios
// ------------------------------------------------

include_once 'config.php';
include_once 'modeloPeliDB.php';


/**********
/*
 * Inicio Muestra o procesa el formulario (POST)
 */

function  ctlPeliInicio(){
   }

/*
 *  Muestra y procesa el formulario de alta 
 */

function ctlPeliAlta (){
    //carga la vista de  registro nuevo
    include ('plantilla/registro.php');

    
    
}

function ctlPeliGuardar(){
    
   if(ModeloUserDB::insertarPeli($_POST)){
        header('Location:index.php');
    }else{
        //falla insercion,habria que validar campos pasados por POST cn funcion en modeloPeliDB;
        header('Location:registro.php?mensaje="error al introducir datos"');
    }


}
function ctlPeliBuscar(){
    include ('plantilla/filtrar.php');
}

function ctlPeliFiltro(){
  $peliculas=ModeloUserDB::getFiltro($_POST);
  // Invoco la vista 
  include_once 'plantilla/verpeliculas.php';
  

}



/*
 *  Muestra y procesa el formulario de Modificación 
 */
function ctlPeliModificar (){
   
}



/*
 *  Muestra detalles de la pelicula
 */

function ctlPeliDetalles(){
    $peli=modeloUserDB::detallesPeli($_GET['codigo']);
    include('plantilla/detallespeli.php');
    
}
/*
 * Borrar Peliculas
 */

function ctlPeliBorrar(){
   
}

/*
 * Cierra la sesión y vuelca los datos
 */
function ctlPeliCerrar(){
    session_destroy();
    modeloUserDB::closeDB();
    header('Location:index.php');
}

/*
 * Muestro la tabla con los usuario 
 */ 
function ctlPeliVerPelis (){
    // Obtengo los datos del modelo
    $peliculas = ModeloUserDB::GetAll(); 
    // Invoco la vista 
    include_once 'plantilla/verpeliculas.php';
   
}