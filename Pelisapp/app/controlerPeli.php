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

function  ctlPeliInicio()
{
    $datos = null;
    $loginOk = null;

    if (isset($_POST["orden"])) {

        $loginOk = ModeloUserDB::EntradaUser($_POST);
        //echo $loginOk;
        if ($loginOk) {
            header('location:index.php?orden=VerPelis');
        } else {
            $datos = ModeloUserDB::GetLoginError($_POST);
        }
    } else {
        $datos = ModeloUserDB::GetLoginError(null);
    }
    include('plantilla/formAcceso.php');
}




/*
 *  Muestra y procesa el formulario de alta 
 */

function ctlPeliAlta()
{

    if (isset($_SESSION['iniciosesion'])) {
        //carga la vista de  registro nuevo

        include('plantilla/registro.php');
    } else {
        header('Location:index.php');
    }
}

function ctlPeliGuardar()
{

    if (isset($_SESSION['iniciosesion'])) {
        $datos = null;

        if (isset($_POST['guardar'])) {
            // print_r($_POST); 
            $datos = ModeloUserDB::Getvalidar($_POST, $_FILES);
            if ($datos->mensajerror == "") {

                if (ModeloUserDB::insertarPeli($datos)) {

                    header('Location:index.php?orden=VerPelis');
                }
            } else {
                include('plantilla/registro.php');
            }
        } else {

            $datos = ModeloUserDB::Getvalidar(null, null);
            //echo $datos->nombre;
            include('plantilla/registro.php');
        }
    } else {
        header('location:index.php');
    }
}
function ctlPeliBuscar()
{

    include('plantilla/filtrar.php');
}

function ctlPeliFiltro()
{
    $peliculas = ModeloUserDB::getFiltro($_POST);
    // Invoco la vista 
    include_once 'plantilla/verpeliculasfiltro.php';
}



/*
 *  Muestra y procesa el formulario de Modificación 
 */
function ctlPeliModificar()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $datos = ModeloUserDB::detallesPeli($_GET['codigo']);
        if ($datos == null) {
            include('plantilla/errordetallepeli.php');
        }
    } elseif (isset($_POST['modificar'])) {
        $mensajerror = ModeloUserDB::Getvalidarmodifica($_POST);
        if ($mensajerror == "") {
            if (ModeloUserDB::modificarpeli($_POST) == false) {

                include('plantilla/errormodificapeli.php');
            } else {

                header('location:index.php?orden=VerPelis');
            }
        } else {
            $datos = ModeloUserDB::Getmodifica($_POST, $mensajerror);
        }
    }
    include('plantilla/modificarpeli.php');
}




/*
 *  Muestra detalles de la pelicula
 */

function ctlPeliDetalles()
{
    $peli = modeloUserDB::detallesPeli($_GET['codigo']);
    include('plantilla/detallespeli.php');
}
/*
 * Borrar Peliculas
 */

function ctlPeliBorrar()
{
    $resu = modeloUserDB::borrarPeli($_GET);
    if ($resu) {
        header('Location:index.php?orden=VerPelis');
    } else {
        include('plantilla/errorborrado.php');
    }
}

/*
 * Cierra la sesión y vuelca los datos
 */
function ctlPeliCerrar()
{
    session_destroy();
    modeloUserDB::closeDB();
    header('Location:index.php');
}

/*
 * Muestro la tabla con los usuario 
 */
function ctlPeliVerPelis()
{
    // Obtengo los datos del modelo
    if (isset($_SESSION['iniciosesion'])) {
        $peliculas = ModeloUserDB::GetAll();
        // Invoco la vista 
        include_once 'plantilla/verpeliculas.php';
    } else {
        header('location:index.php');
    }
}

function ctlAltaUser()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $datos=ModeloUserDB::GetUser(null);
    } else {
        $datos=ModeloUserDB::GetUser($_POST);
        if(ModeloUserDB::validarUser($datos)==""){
            $resultado=ModeloUserDB::insertarUser($datos);
            if($resultado){
                header('location:index.php'); 
            }else{

            }
        }
    }
    include_once 'plantilla/altauser.php';
}
