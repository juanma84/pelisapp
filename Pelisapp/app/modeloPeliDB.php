<?php

include_once 'config.php';
include_once 'Pelicula.php';

class ModeloUserDB {

     private static $dbh = null; 
     private static $consulta_peli = "Select * from peliculas where codigo_pelicula = ?";
    //insertar peliculas
     private static $insert_peli= "Insert into  peliculas (nombre,director,genero,imagen)"." VALUES (?,?,?,?)";
    //filtrar peliculas
    private static $filtrar_peli= "select * from peliculas";
  /*
     private static $delete_peli   = "Delete from Usuarios where id = ?"; 
     private static $insert_user   = "Insert into Usuarios (id,clave,nombre,email,plan,estado)".
                                     " VALUES (?,?,?,?,?,?)";
     private static $update_user    = "UPDATE Usuarios set  clave=?, nombre =?, ".
                                     "email=?, plan=?, estado=? where id =?";
 */    
     
public static function init(){
   
    if (self::$dbh == null){
        try {
            // Cambiar  los valores de las constantes en config.php
            $dsn = "mysql:host=".DBSERVER.";dbname=".DBNAME.";charset=utf8";
            self::$dbh = new PDO($dsn,DBUSER,DBPASSWORD);
            // Si se produce un error se genera una excepción;
            self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }
        
    }
    
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
public static function GetAll ():array{
    // Genero los datos para la vista que no muestra la contraseña
    
    $stmt = self::$dbh->query("select * from peliculas");
    
    $tpelis = [];
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    while ( $peli = $stmt->fetch()){

        $tpelis[] = $peli;       
    }
    return $tpelis;
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


public static function insertarPeli($pelicula):bool{
    
    $imagen=$_FILES['imagen']['name'];

    $stmt = self::$dbh->prepare(self::$insert_peli);
    $stmt->bindValue(1,$pelicula["nombre"]);
    $stmt->bindValue(2,$pelicula["director"]);
    $stmt->bindValue(3,$pelicula["genero"]);
    $stmt->bindValue(4,$imagen);
    if ($stmt->execute()){
        move_uploaded_file($_FILES['imagen']['tmp_name'], './app/img/'.$_FILES['imagen']['name']);
       return true;
    }
    return false; 
}

public static function getFiltro($arrayfiltro):array{

    

    $filtro="where";


    if(strtoupper(trim($arrayfiltro['nombre']))!=""){
        if($filtro=='where'){
            $filtro=$filtro." nombre=:nombre";
        }else{
            $filtro=$filtro." AND nombre=:nombre";
        }
        
    }
    if(strtoupper(trim($arrayfiltro['director']))!=""){
        if($filtro=='where'){
            $filtro=$filtro." director=:director";
        }else{
            $filtro=$filtro." AND director=:director";
        }
        
    }
    if(strtoupper(trim($arrayfiltro['genero']))!=""){
        if($filtro=='where'){
            $filtro=$filtro." genero=:genero";
        }else{
            $filtro=$filtro." AND genero=:genero";
        }
        
    }
    if($filtro!="where"){
       self::$filtrar_peli.=" ".$filtro;
    }
   
    $stmt = self::$dbh->prepare(self::$filtrar_peli);
   

    $tpelis = [];

    

    if($arrayfiltro['nombre']!=""){
        
        $stmt->bindValue(':nombre',strtoupper(trim($arrayfiltro['nombre'])));
        
    }
    if($arrayfiltro['director']!=""){
        
        $stmt->bindValue(':director',strtoupper(trim($arrayfiltro['director'])));
    }
    if($arrayfiltro['genero']!=""){
        
        $stmt->bindValue(':genero',strtoupper(trim($arrayfiltro['genero'])));
    }
     
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
   
   while($peli=$stmt->fetch()){
      $tpelis[]=$peli;
   }

    return $tpelis;
}

public static function detallesPeli($codigo_pelicula):object{
    
    $stmt = self::$dbh->prepare(self::$consulta_peli);
    $stmt->bindValue(1,$codigo_pelicula);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    $peli=$stmt->fetch();
    return $peli;
} 


public static function closeDB(){
    self::$dbh = null;
}




} // class
