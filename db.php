<?php

class Conectar{
    public static function conexion(){
        
        try {
            $conexion = new PDO('mysql:host=212.1.212.52;dbname=u206450586_menus', 'u206450586_menus', 'MKuFmdxW2061');
            $conexion->query("SET NAMES 'utf8'");
            
            
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        return $conexion;
    }
}
