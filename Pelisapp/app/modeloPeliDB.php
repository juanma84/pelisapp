<?php

include_once 'config.php';
include_once 'Pelicula.php';
include_once 'Usuario.php';

class ModeloUserDB
{

    private static $dbh = null;
    private static $consulta_peli = "Select * from peliculas where codigo_pelicula = ?";
    //insertar peliculas
    private static $insert_peli = "Insert into  peliculas (nombre,director,genero,imagen,trailer)" . " VALUES (?,?,?,?,?)";
    //filtrar peliculas
    private static $filtrar_peli = "select * from peliculas";
    private static $validar_user = "SELECT COUNT(*) as cuantos FROM usuarios WHERE nombre=? and password=?";
    private static $borrar_peli = "delete from peliculas  where codigo_pelicula=?";
    private static $modificar_peli = "UPDATE peliculas set nombre=?,  director=?, genero=?, trailer=? 
                                    where  codigo_pelicula=?";
    private static $insert_user   = "Insert into usuarios (nombre,password,email,plan)
                                     VALUES (?,?,?,?)";
    /*

     private static $delete_peli   = "Delete from Usuarios where id = ?"; 
     
     private static $update_user    = "UPDATE Usuarios set  clave=?, nombre =?, ".
                                     "email=?, plan=?, estado=? where id =?";
 */

    public static function init()
    {

        if (self::$dbh == null) {
            try {
                // Cambiar  los valores de las constantes en config.php
                $dsn = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME . ";charset=utf8";
                self::$dbh = new PDO($dsn, DBUSER, DBPASSWORD);
                // Si se produce un error se genera una excepción;
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Error de conexión " . $e->getMessage();
                exit();
            }
        }
    }


    //entrada usuario,validar datos

    public static function EntradaUser($datos):bool
    {

        $user = $_POST['nombre'];
        $clave = $_POST['password'];
        $resultado = false;

        $stmt = self::$dbh->prepare(self::$validar_user);
        $stmt->bindValue(1, htmlentities($user));
        $stmt->bindValue(2, htmlentities($clave));
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetch();
        if ($resultado['cuantos'] == 1) {
            $resultado = true;

            $_SESSION['iniciosesion'] = $resultado;
        } else {
            $resultado = false;
        }
        return $resultado;
    }

    public static function GetLoginError($datos): Usuario
    {
        $resultado = new Usuario;

        if ($datos == null) {
            $resultado->nombre = '';
            $resultado->password = '';
        } else {
            $resultado->nombre = $datos['nombre'];
            $resultado->password = $datos['password'];
            $resultado->mensajerror = 'error al iniciar sesion,compruebe nombre  y contraseña';
        }
        return $resultado;
    }

    /***
// Borrar un usuario (boolean)
public static function UserDel($userid){
    $stmt = self::$dbh->prepare(self::$delete_user);
    $stmt->bindValue(1,$userid);
    $stmt->execute();
    if ($stmt->rowCount() > 0 ){
        return true;
    }
    return false;
}
// Añadir un nuevo usuario (boolean)
public static function UserAdd($userid, $userdat):bool{
    $stmt = self::$dbh->prepare(self::$insert_user);
    $stmt->bindValue(1,$userid);
    $clave = Cifrador::cifrar($userdat[0]);
    $stmt->bindValue(2,$clave);
    $stmt->bindValue(3,$userdat[1] );
    $stmt->bindValue(4,$userdat[2] );
    $stmt->bindValue(5,$userdat[3] );
    $stmt->bindValue(6,$userdat[4] );
    if ($stmt->execute()){
       return true;
    }
    return false; 
}

// Actualizar un nuevo usuario (boolean)
// GUARDAR LA CLAVE CIFRADA
public static function UserUpdate ($userid, $userdat){
    $clave = $userdat[0];
    // Si no tiene valor la cambio
    if ($clave == ""){ 
        $stmt = self::$dbh->prepare(self::$update_usernopw);
        $stmt->bindValue(1,$userdat[1] );
        $stmt->bindValue(2,$userdat[2] );
        $stmt->bindValue(3,$userdat[3] );
        $stmt->bindValue(4,$userdat[4] );
        $stmt->bindValue(5,$userid);
        if ($stmt->execute ()){
            return true;
        }
    } else {
        $clave = Cifrador::cifrar($clave);
        $stmt = self::$dbh->prepare(self::$update_user);
        $stmt->bindValue(1,$clave );
        $stmt->bindValue(2,$userdat[1] );
        $stmt->bindValue(3,$userdat[2] );
        $stmt->bindValue(4,$userdat[3] );
        $stmt->bindValue(5,$userdat[4] );
        $stmt->bindValue(6,$userid);
        if ($stmt->execute ()){
            return true;
        }
    }
    return false; 
}
     ****/

    // Tabla de objetos con todas las peliculas
    public static function GetAll(): array
    {
        // Genero los datos para la vista que no muestra la contraseña
        $contador = 0;
        $stmt = self::$dbh->query("select * from peliculas");
        $tpelis = [];
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
        while ($peli = $stmt->fetch()) {

            $tpelis[] = $peli;
        }

        return $tpelis;
    }

    public static function Getvalidar($pelicula, $img): object
    {
        $resultado = new Pelicula;
        $valido = true;
        if ($pelicula != null) {

            $imagen = null;
            $imagen = $img['imagen']['name'];
            $mensajerror = "";

            if ($imagen == "") {

                $imagen = "default.jpg";
            } else {

                $tipo = $img['imagen']['type'];
                $tamaño = $img['imagen']['size'];
                //tamaño maximo permitido 2MB es decir,2x1024x1024=2097152 bytes
                if ($tamaño > 2097152 || $tipo != 'image/jpeg') {
                    if ($tamaño > 2097152) {
                        $mensajerror .= "tamaño de imagen no aceptado";
                    }
                    if ($tipo != 'image/jpeg') {
                        $mensajerror .= "formato de imagen no aceptado";
                    }
                    $valido = false;
                }
            }
            if (trim($pelicula['nombre']) == "") {
                $valido = false;
                $mensajerror .= "introduce nombre" . '<br>';
            }
            if (trim($pelicula['director']) == "") {
                $valido = false;
                $mensajerror .= "introduce director" . '<br>';
            }


            /* if($valido){ 
                    $resultado->nombre=$pelicula['nombre'];
                    $resultado->director=$pelicula['director'];
                    $resultado->genero=$pelicula['genero'];
                    $resultado->imagen=$imagen;
                    $resultado->trailer=$pelicula['trailer'];
                    $resultado->mensajerror=$mensajerror;
                }
                else{
                    $resultado->nombre=$pelicula['nombre'];
                    $resultado->director=$pelicula['director'];
                    $resultado->genero=$pelicula['genero'];
                    $resultado->imagen=$imagen;
                    $resultado->trailer=$pelicula['trailer'];
                    $resultado->mensajerror=$mensajerror;
                }*/
            $resultado->nombre = $pelicula['nombre'];
            $resultado->director = $pelicula['director'];
            $resultado->genero = $pelicula['genero'];
            $resultado->imagen = $imagen;
            $resultado->trailer = $pelicula['trailer'];
            $resultado->mensajerror = $mensajerror;
        } else {
            $resultado->nombre = '';
            $resultado->director = '';
            $resultado->genero = '';
            $resultado->imagen = '';
            $resultado->trailer = '';
        }
        // echo $resultado->imagen;
        return $resultado;
    }


    /***

// Datos de una película para visualizar
public static function UserGet ($codigo){
    $datosuser = [];
    $stmt = self::$dbh->prepare(self::$consulta_user);
    $stmt->bindValue(1,$userid);
    $stmt->execute();
    if ($stmt->rowCount() > 0 ){
        // Obtengo un objeto de tipo Usuario, pero devuelvo una tabla
        // Para no tener que modificar el controlador
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $uobj = $stmt->fetch();
        $datosuser = [ 
                     $uobj->clave,
                     $uobj->nombre,
                     $uobj->email,
                     $uobj->plan,
                     $uobj->estado
                     ];
        return $datosuser;
    }
    return null;    
    
}
     ***/


    public static function insertarPeli($pelicula): bool
    {

        $respuesta = true;



        $stmt = self::$dbh->prepare(self::$insert_peli);
        $stmt->bindValue(1, htmlentities(strtoupper($pelicula->nombre)));
        $stmt->bindValue(2, htmlentities(strtoupper($pelicula->director)));
        $stmt->bindValue(3, htmlentities(strtoupper($pelicula->genero)));
        $stmt->bindValue(4, htmlentities($pelicula->imagen));
        $stmt->bindValue(5, htmlentities(strtoupper($pelicula->trailer)));
        if ($stmt->execute()) {
            move_uploaded_file($_FILES['imagen']['tmp_name'], './app/img/' . $_FILES['imagen']['name']);
            //    return true;
        } else {
            $respuesta = false;
        }
        return $respuesta;
    }

    public static function getFiltro($arrayfiltro): array
    {



        $filtro = "where";


        if (strtoupper(trim($arrayfiltro['nombre'])) != "") {
            if ($filtro == 'where') {
                $filtro = $filtro . " nombre=:nombre";
            } else {
                $filtro = $filtro . " AND nombre=:nombre";
            }
        }
        if (strtoupper(trim($arrayfiltro['director'])) != "") {
            if ($filtro == 'where') {
                $filtro = $filtro . " director=:director";
            } else {
                $filtro = $filtro . " AND director=:director";
            }
        }
        if (strtoupper(trim($arrayfiltro['genero'])) != "") {
            if ($filtro == 'where') {
                $filtro = $filtro . " genero=:genero";
            } else {
                $filtro = $filtro . " AND genero=:genero";
            }
        }
        if ($filtro != "where") {
            self::$filtrar_peli .= " " . $filtro;
        }

        $stmt = self::$dbh->prepare(self::$filtrar_peli);


        $tpelis = [];



        if ($arrayfiltro['nombre'] != "") {

            $stmt->bindValue(':nombre', strtoupper(trim($arrayfiltro['nombre'])));
        }
        if ($arrayfiltro['director'] != "") {

            $stmt->bindValue(':director', strtoupper(trim($arrayfiltro['director'])));
        }
        if ($arrayfiltro['genero'] != "") {

            $stmt->bindValue(':genero', strtoupper(trim($arrayfiltro['genero'])));
        }

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');

        while ($peli = $stmt->fetch()) {
            $tpelis[] = $peli;
        }

        return $tpelis;
    }

    public static function detallesPeli($codigo_pelicula): ?object
    {
        $resultado = null;

        $stmt = self::$dbh->prepare(self::$consulta_peli);
        $stmt->bindValue(1, $codigo_pelicula);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
        $resultado = $stmt->fetch();
        if ($resultado == false) {
            $resultado = null;
        }
        return $resultado;
    }

    public static function borrarPeli($datos)
    {
        $resultado = false;
        $codigo_pelicula = $datos['codigo_pelicula'];

        $stmt = self::$dbh->prepare(self::$borrar_peli);
        $stmt->bindValue(1, $codigo_pelicula);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $resultado = true;
        }
        return $resultado;
    }



    public static function Getvalidarmodifica($pelicula): string
    {

        $mensajerror = "";


        if (trim($pelicula['nombre']) == "") {

            $mensajerror .= "introduce nombre" . '<br>';
        }
        if (trim($pelicula['director']) == "") {

            $mensajerror .= "introduce director" . '<br>';
        }

        return $mensajerror;
    }
    public static function Getmodifica($pelicula, $mensaje): object
    {
        $datos = $pelicula;
        $datos->mensajerror = $mensaje;
        return $datos;
    }

    public static function modificarpeli($pelicula): bool
    {


        $respuesta = true;



        $stmt = self::$dbh->prepare(self::$modificar_peli);
        $stmt->bindValue(1, htmlentities(strtoupper($pelicula['nombre'])));
        $stmt->bindValue(2, htmlentities(strtoupper($pelicula['director'])));
        $stmt->bindValue(3, htmlentities(strtoupper($pelicula['genero'])));
        $stmt->bindValue(4, htmlentities(strtoupper($pelicula['trailer'])));
        $stmt->bindValue(5, htmlentities(strtoupper($pelicula['codigo_pelicula'])));
        if ($stmt->execute() == false) {
            $respuesta = false;
        }

        return $respuesta;
    }

    public static function GetUser($user): Usuario
    {
        $resultado = new Usuario;
        if ($user == null) {
            $resultado->nombre = "";
            $resultado->password = "";
            $resultado->password2 = "";
            $resultado->email = "";
            $resultado->plan = "";
        } else {
            $resultado->nombre = $user['nombre'];
            $resultado->password = $user['password'];
            $resultado->password2 = $user['password2'];
            $resultado->email = $user['email'];
            $resultado->plan = $user['plan'];
        }
        return $resultado;
    }
    public static function validarUser($user)
    {
        $mensajerror="";
        if(trim($user->nombre)==""){
            $mensajerror.="campo nombre vacio"."<br>";
        }
        if(trim($user->password)==""){
            $mensajerror.="campo contraseña vacio"."<br>";
        }
        if(trim($user->password2)==""){
            $mensajerror.="campo repetir contraseña vacio <br>";
        }
        if(trim($user->email)==""){
            $mensajerror.="campo email vacio <br>";
        }
        if(trim($user->plan)==""){
            $mensajerror.="campo plan vacio <br>";
        }
        if($user->password2!=$user->password){
            $mensajerror.="Contraseñas  diferentes <br>";
        }
        $user->mensajerror=$mensajerror;
    return $mensajerror;
    }

    public static function  insertarUser($user):bool{

        $respuesta=true;
        $stmt = self::$dbh->prepare(self::$insert_user);

        $stmt->bindValue(1, htmlentities(strtoupper($user->nombre)));
        $stmt->bindValue(2, htmlentities(strtoupper($user->password)));
        $stmt->bindValue(3, htmlentities(strtoupper($user->email)));
        $stmt->bindValue(4, htmlentities(strtoupper($user->plan)));
        if ($stmt->execute() == false) {
            $respuesta = false;
        }

        return $respuesta;
        
    }



    public static function closeDB()
    {
        self::$dbh = null;
    }
} // class
