<?php
include_once "BaseDatos.php";
class Empresa
{
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $colViajes;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->idEmpresa = "";
        $this->nombre = "";
        $this->direccion = "";
        //$this->colViajes = [];
    }

    public function cargar($idEmpresa,$nombre, $direccion)
    {
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
        $this->setIdEmpresa($idEmpresa);
    }

    public function setColViajes($colViajes){
        $this->colViajes = $colViajes;
    }
    public function getColViajes(){
        return $this->colViajes;
    }
    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    public function Buscar($idEmpresa)
    {
        $base = new BaseDatos();
        $consultaEmpresa = "Select * from empresa where idempresa=" . $idEmpresa;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdEmpresa($idEmpresa);
                    $this->setNombre($row2['enombre']);
                    $this->setDireccion($row2['edireccion']);
                    $resp = true;
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = "")
    {
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresa = "Select * from empresa ";
        if ($condicion != "") {
            $consultaEmpresa .= ' where ' . $condicion;
        }
        $consultaEmpresa .= " order by idempresa ";
        //echo $consultaPasajeros;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                $arregloEmpresa = array();
                while ($row2 = $base->Registro()) {

                    $idEmpresa = $row2['idempresa'];
                    $nombre = $row2['enombre'];
                    $direccion = $row2['edireccion'];

                    $empresa = new Empresa();
                    $empresa->cargar($idEmpresa, $nombre, $direccion);
                    array_push($arregloEmpresa, $empresa);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloEmpresa;
    }


    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(enombre, edireccion) 
				VALUES ('{$this->getNombre()}', '{$this->getDireccion()}')";

        if ($base->Iniciar()) {

            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdEmpresa($id);
                $resp =  true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModificar = "UPDATE empresa SET enombre = '{$this->getNombre()}', edireccion = '{$this->getDireccion()}' WHERE idempresa = {$this->getIdEmpresa()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModificar)) {
                $resp =  true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorrar = "DELETE FROM empresa WHERE idempresa = {$this->getIdEmpresa()}";
            if ($base->Ejecutar($consultaBorrar)) {
                $resp =  true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function __toString()

    {
        return "ID Empresa: {$this->getIdEmpresa()} \nNombre: {$this->getNombre()}\nDirección: {$this->getDireccion()}\n";
    }

}
