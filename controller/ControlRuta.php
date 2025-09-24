<?php
require_once 'config/config.php';
require_once(MODEL_PATH . 'Ruta.php');

class ControlRuta {
    private static $instance = null;
    private $viewBasePath;
    private $objRutas;

    private function __construct($rutas) {
        $this->objRutas = $rutas;
        $this->viewBasePath = HTML_PATH; // Usar la constante HTML_PATH para la ruta base
    }

    public static function getInstance($rutas) {
        if (self::$instance === null) {
            self::$instance = new ControlRuta($rutas);
        }
        return self::$instance;
    }
    
    public function respuestaRuta() {
        $parametros = $this->objRutas->getParametros();
        $vistaPrincipal = $this->objRutas->getVista();
        
        $segmentos = array_merge([$vistaPrincipal], $parametros);
        
        // Eliminar parámetros numéricos
        $segmentosNoNumericos = array_filter($segmentos, function($s) { return !is_numeric($s); });
        
        // Construir ruta con todos los segmentos no numéricos
        $rutaBase = implode('/', $segmentosNoNumericos);
        $rutaArchivo = $this->viewBasePath . $rutaBase . '.php';
        
        if (is_file($rutaArchivo)) {
            $this->cargarVista($rutaArchivo);
            return;
        }
        
        $this->cargarVista404();
    }
    
    
    
    
    

    private function cargarVista($path) {
        if (is_file($path)) {
            include $path;
            return true;
        }
        return false;
    }

    private function cargarVista404() {
        http_response_code(404);
        include $this->viewBasePath . '404.php';
    }
}