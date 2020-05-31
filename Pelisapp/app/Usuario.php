<?php
/* DATOS DE UN USUARIO */

class Usuario
{
   private $id;
   private $nombre;
   private $password;
   private $password2;
   private $email;
   private $plan;
   private $mensajerror;  
   // Getter con método mágico
   public function __get($atributo){
       $class = get_class($this);
       if(property_exists($class, $atributo)) {
           return $this->$atributo;
       }
   }
   
   // Set con método mágico
   public function __set($atributo,$valor){
       $class = get_class($this);
       if(property_exists($class, $atributo)) {
           $this->$atributo = $valor;
       }
   }
   
}