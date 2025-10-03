<?php
require_once( __DIR__ .'/controller/ControlRuta.php');

try {
    if (isset($_GET['view'])) {
        $segmentos = explode("/", $_GET['view']);  // Divide la URL en segmentos
        
        // El primer segmento es la vista principal
        $rutaPrincipal = array_shift($segmentos) ?: 'index'; // Si no hay segmentos, usar 'index'
        
        // Los segmentos restantes son parámetros (pueden ser acciones, IDs, etc.)
        $parametros = $segmentos;

        // Pasamos los valores a la lógica del controlador
        $objRuta = Ruta::getInstance($rutaPrincipal, $parametros); 
        $objControlRutas = ControlRuta::getInstance($objRuta);
    
        // Manejar la respuesta de la ruta
        $objControlRutas->respuestaRuta();
    } else {
        // Ruta por defecto cuando no hay parámetros en la URL
        $objRuta = Ruta::getInstance('index', []);
        $objControlRutas = ControlRuta::getInstance($objRuta);
        $objControlRutas->respuestaRuta();
    }
} catch(Exception $e) {
    echo $e->getMessage();
}