<?php

class Cliente{
    var $tDocumento;
    var $documento;
    var $nombre;
    var $apellido;
    var $sexo;
    var $dia;
    var $mes;
    var $anio;
    var $correo;
    var $celular;
    var $direccion;
    var $departamento;
    var $ciudad;
    var $terminos; 

    function __construct($tDocumento = null, $documento = null, $nombre = null, $apellido = null, $sexo = null, $dia = null, $mes = null, $anio = null, $correo = null, $celular = null, $direccion = null, $departamento = null, $ciudad = null, $terminos = null){
        if($tDocumento !== null){
            $this->setTDocumento($tDocumento);
        }

        if($documento !== null){
            $this->setDocumento($documento);
        }

        if($nombre !== null){
            $this->setNombre($nombre);
        }

        if($apellido !== null){
            $this->setApellido($apellido);
        }

        if($sexo !== null){
            $this->setSexo($sexo);
        }

        if($dia !== null){
            $this->setDia($dia);
        }

        if($mes !== null){
            $this->setMes($mes);
        }

        if($anio !== null){
            $this->setAnio($anio);
        }

        if($correo !== null){
            $this->setCorreo($correo);
        }

        if($celular !== null){
            $this->setCelular($celular);
        }

        if($direccion !== null){
            $this->setDireccion($direccion);
        }
        
        if($departamento !== null){
            $this->setDepartamento($departamento);
        }

        if($ciudad !== null){
            $this->setCiudad($ciudad);
        }

        if($terminos !== null){
            $this->setTerminos($terminos);
        }
    }

    function getTDocumento(){
        return $this->tDocumento;
    }

    function getDocumento(){
        return $this->documento;
    }

    function getNombre(){
        return $this->nombre;
    }

    function getApellido(){
        return $this->apellido;
    }

    function getSexo(){
        return $this->sexo;
    }

    function getDia(){
        return $this->dia;
    }

    function getMes(){
        return $this->mes;
    }

    function getAnio(){
        return $this->anio;
    }

    function getCorreo(){
        return $this->correo;
    }

    function getCelular(){
        return $this->celular;
    }

    function getDireccion(){
        return $this->direccion;
    }

    function getDepartamento(){
        return $this->departamento;
    }

    function getCiudad(){
        return $this->ciudad;
    }

    function setTDocumento($tDocumento){
        $this->tDocumento = $tDocumento;
    }

    function setDocumento($documento){
        $this->documento = $documento;
    }

    function setNombre($nombre){
        $this->nombre = $nombre;
    }

    function setApellido($apellido){
        $this->apellido = $apellido;
    }

    function setSexo($sexo){
        $this->sexo = $sexo;
    }

    function setDia($dia){
        $this->dia = $dia;
    }

    function setMes($mes){
        $this->mes = $mes;
    }

    function setAnio($anio){
        $this->anio = $anio;
    }

    function setCorreo($correo){
        $this->correo = $correo;
    }

    function setCelular($celular){
        $this->celular = $celular;
    }

    function setDireccion($direccion){
        $this->direccion = $direccion;
    }

    function setDepartamento($departamento){
        $this->departamento = $departamento;
    }

    function setCiudad($ciudad){
        $this->ciudad = $ciudad;
    }

    function setTerminos($terminos){
        $this->terminos = $terminos;
    }

    function getTerminos(){
        return $this->terminos;
    }
    
}