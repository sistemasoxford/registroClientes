<?php
class Ruta {
    private static $instance = null;
    private $vista;
    private $parametros;

    private function __construct($vista, $parametros = []) {
        $this->vista = $vista;
        $this->parametros = $parametros;
    }

    public static function getInstance($vista, $parametros = []) {
        if (self::$instance === null) {
            self::$instance = new Ruta($vista, $parametros);
        }
        return self::$instance;
    }

    public function setVista($vista) {
        if ($this->sanitizeInput($vista)) {
            if ($this->isValidViewPath($vista)) {
                $this->vista = $vista;
            }
        }
    }

    public function getVista() {
        return $this->vista;
    }

    public function getVistaPath() {
        return HTML_PATH . $this->vista . '.php'; // Construir la ruta completa hacia la vista
    }

    private function sanitizeInput($input) {
        return preg_replace('/[^a-zA-Z0-9\/]/', '', $input);
    }

    private function isValidViewPath($path) {
        return is_file(HTML_PATH . $path . '.php'); // Verificar si el archivo de vista existe en la ruta especificada
    }

    public function setParametros($parametros) {
        $this->parametros = $parametros;
    }

    public function getParametros() {
        return $this->parametros;
    }

    public function getParametro($indice, $valorPorDefecto = null) {
        return $this->parametros[$indice] ?? $valorPorDefecto;
    }
}