<?php
class menus_model{
    private $db;
    private $personas;
 
    public function __construct(){
        $this->db = Conectar::conexion();
        $this->ObtenerMenusPadre = array();
        $this->ObtenerMenusSubmenus = array();
        $this->ObtenerMenus = array();

        if(isset($_POST['nombre_menu']) && $_POST['nombre_menu'] != ''){
            $this->IngresarDatos($_POST); 
        }
    }
    public function ObtenerMenusPadre(){

        try {
            $consulta = $this->db->query("SELECT * FROM menus_padre WHERE status = 1;");
            foreach($consulta as $i => $rec) {
                $this->ObtenerMenusPadre[]=$rec;
            }
            // $this->db = null;
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        
        return $this->ObtenerMenusPadre;
    }

    public function ObtenerMenusSubmenus(){

        try {
            $con = $this->db->query("SELECT * FROM menus_submenus WHERE status = 1;");
            foreach($con as $i => $rec) {
                $this->ObtenerMenusSubmenus[] = $rec;
            }
            // $this->db = null;
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        
        return $this->ObtenerMenusSubmenus;
    }

    public function ObtenerMenus(){

        try {
            $con = $this->db->query("SELECT 
                                        id,
                                        nombre_menu,
                                        descripcion,
                                        COALESCE((
                                            SELECT 
                                                nombre_menu
                                            FROM
                                                menus_padre
                                            WHERE 
                                                id = s.idmenu_padre
                                        ), NULL) AS menu_padre
                                    FROM 
                                        menus_submenus s 

                                    WHERE s.status = 1

                                    UNION

                                    SELECT 
                                        id,
                                        nombre_menu,
                                        descripcion,
                                        NULL AS menu_padre
                                    FROM 
                                        menus_padre p 

                                    WHERE p.status = 1
                            ;");
            foreach($con as $i => $rec) {
                $this->ObtenerMenus[] = $rec;
            }
            // $this->db = null;
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        
        return $this->ObtenerMenus;
    }

    public function IngresarDatos($datos){

        $idmenu_padre = (isset($datos['idmenu_padre']) && $datos['idmenu_padre'] > 0) ? $datos['idmenu_padre']: 0;
        //submenu
        if($idmenu_padre > 0){
            $stmt = $this->db->prepare("INSERT INTO menus_submenus(idmenu_padre, nombre_menu, descripcion) VALUES (:idmenu_padre, :nombre_menu, :descripcion)");
            
            $stmt->execute([
                'idmenu_padre' => $idmenu_padre,
                'nombre_menu' => $datos['nombre_menu'],
                'descripcion' => $datos['descripcion']
            ]);
        }
        else{
            //menu principal

            $stmt = $this->db->prepare("INSERT INTO menus_padre(nombre_menu, descripcion) VALUES (:nombre_menu, :descripcion)");

        
            $stmt->execute([
                'nombre_menu' => $datos['nombre_menu'],
                'descripcion' => $datos['descripcion']
            ]);
        }
        
    }
}
?>
