<?php

class Tienda{
    var $id = [];
    var $codigo;
    var $nombre;
    var $extension;
    var $telefono;
    function __construct($id = [], $codigo = null, $nombre = null,  $extension = null, $telefono = null) {
        if (!empty($id)) {
            $this->setId($id);
        }
        if($codigo !== null){
            $this->setCodigo($codigo);
        }
        if ($nombre !== null) {
            $this->setNombre($nombre);
        }
        if($extension !== null){
            $this->setExtension($extension);
        }
        if($telefono !== null){
            $this->setTelefono($telefono);
        }
    }

    function setId($id){
        if (is_array($id)) {
            foreach ($id as $ids) {
                if (!is_numeric($ids)) {
                    throw new InvalidArgumentException('Cada ID de estado debe ser válido.');
                }
            }
            $this->id = $id;
        } elseif (is_numeric($id)) {
            $this->id = $id;
        } else {
            throw new InvalidArgumentException('El ID debe ser un número o un arreglo de números.');
        }
    }

    function getId(){
        return $this->id;
    }

    function setCodigo($codigo){
        $this->codigo = $codigo;
    }
    
    function getCodigo(){
        return $this->codigo;
    }

    function setNombre($nombre){
        $this->nombre = $nombre;
    }

    function getNombre(){
        return $this->nombre;
    }

    function setExtension($extension){
        $this->extension = $extension;
    }

    function getExtension(){
        return $this->extension;
    }

    function setTelefono($telefono){
        $this->telefono = $telefono;
    }

    function getTelefono(){
        return $this->telefono;
    }
}