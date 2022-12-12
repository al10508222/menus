<?php
//Llamada al modelo
require_once("model/menus_model.php");
$menus = new menus_model();
$datos_padre = $menus->ObtenerMenusPadre();
$datos_hijo = $menus->ObtenerMenusSubmenus();
$datos_menus = $menus->ObtenerMenus();


//Llamada a la vista
require_once("views/menus_view.phtml");


?>
