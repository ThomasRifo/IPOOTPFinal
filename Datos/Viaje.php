<?php
include_once "BaseDatos.php";
include_once 'ResponsableV.php';
class Viaje{
    private $pasajeros; //array de objetos
    private $objResponsable; 
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $empresa; 
    private $importe;
    private $mensajeOperacion;

    public function __construct()
    {

        $this->idViaje = 0;
        $this->destino = "";
        $this->pasajeros = [];
        $this->objResponsable = new ResponsableV();
        $this->cantMaxPasajeros = 0;
        $this->empresa = new Empresa();
        $this->importe = 0;
    }

    public function cargar($idViaje, $destino, $objResponsable, $cantMaxPasajeros, $empresa, $importe){
        $this->setIdViaje($idViaje);
        $this->setDestino($destino);
        $this->setObjResponsable($objResponsable);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setEmpresa($empresa);
        $this->setImporte($importe);
    }

    public function setIdViaje($idViaje){
        $this->idViaje = $idViaje;
    }
    public function setDestino($destino){
        $this->destino = $destino;
    }
    public function setObjResponsable($objResponsable){
        $this->objResponsable = $objResponsable;
    }
    public function setCantMaxPasajeros($cantMaxPasajeros){
        $this->cantMaxPasajeros = $cantMaxPasajeros;
    }
    public function setEmpresa($empresa){
        $this->empresa = $empresa;
    }
    public function setImporte ($importe){
        $this->importe = $importe;
    }
    public function setPasajeros($pasajeros){
        $this->pasajeros = $pasajeros;
    }

    public function getIdViaje(){
        return $this->idViaje;
    }
    
    public function getDestino(){
        return $this->destino;
    }
    public function getObjResponsable(){
        return $this->objResponsable;
    }
    public function getCantMaxPasajeros(){
        return $this->cantMaxPasajeros;
    }
    public function getEmpresa(){
        return $this->empresa;
    }
    public function getImporte(){
        return $this->importe;
    }
    public function getPasajeros(){
        return $this->pasajeros;
    }

    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }


    public function Buscar($idViaje)
{
    $base = new BaseDatos();
    $consultaViaje = "SELECT * FROM viaje WHERE idviaje=" . $idViaje;
    $resp = false;
    if ($base->Iniciar()) {
        if ($base->Ejecutar($consultaViaje)) {
            if ($row2 = $base->Registro()) {
                //$this->setIdViaje($idViaje);
               // $this->setDestino($row2['vdestino']);
               // $this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);

                $responsable = new ResponsableV();
                $responsable->Buscar($row2['rnumeroempleado']);
                //$this->setObjResponsable($responsable);

                $empresa = new Empresa;
                $empresa->Buscar($row2['idempresa']);
                //$this->setEmpresa($empresa);


                //$this->setImporte($row2['vimporte']);

                
                $this->cargar($idViaje, $row2['vdestino'], $responsable, $row2['vcantmaxpasajeros'], $empresa, $row2['vimporte']);
                
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
        $arregloViaje = null;
        $base = new BaseDatos();
        $consultaViaje = "Select * from viaje ";
        if ($condicion != "") {
            $consultaViaje .= ' where ' . $condicion;
        }
        $consultaViaje .= " order by idviaje ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViaje)) {
                $arregloViaje = array();
                while ($row2 = $base->Registro()) {

                    $idViaje = $row2['idviaje'];
                    $destino = $row2['vdestino'];
                    $cantMaxPasajeros = $row2['vcantmaxpasajeros'];

                    $empresa = new Empresa();
                    $empresa->Buscar($row2['idempresa']);
                    
                    $empleado = new ResponsableV();
                    $empleado->Buscar($row2['rnumeroempleado']);

                    $importe = $row2['vimporte'];
                    
                    $viaje = new Viaje();
                    $viaje->cargar($idViaje, $destino, $empleado, $cantMaxPasajeros, $empresa, $importe);
                    array_push($arregloViaje, $viaje);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloViaje;
    }



    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;

        $consultaInsertar = "INSERT INTO viaje(vdestino, idempresa, vcantmaxpasajeros, rnumeroempleado, vimporte) 
				VALUES ('{$this->getDestino()}', {$this->getEmpresa()->getIdEmpresa()}, {$this->getCantMaxPasajeros()}, {$this->getObjResponsable()->getNumEmpleado()}, {$this->getImporte()})";
        if ($base->Iniciar()) {

            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdViaje($id);
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
        $consultaModificar = "UPDATE viaje SET vdestino = '{$this->getDestino()}', vcantmaxpasajeros = {$this->getCantMaxPasajeros()}, idempresa = {$this->getEmpresa()->getIdEmpresa()}, vimporte = {$this->getImporte()}, rnumeroempleado = {$this->getObjResponsable()->getNumEmpleado()} WHERE idviaje = {$this->getIdViaje()}";
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
            $consultaBorrar = "DELETE FROM viaje WHERE idviaje = {$this->getIdViaje()}";
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
        $condicion = "idviaje=".$this->getIdViaje();
        $pasajero = new Pasajero();
        $colPasajeros = $pasajero->listar($condicion);
        $textoPasajeros = "";
        foreach($colPasajeros as $objPasajero){
            $textoPasajeros .= $objPasajero->__toString() . "\n-----------------";
        }

        return "*************************\nDestino: {$this->getDestino()}\nID Viaje: {$this->getIdViaje()} - ID Empresa: {$this->getEmpresa()->getIdEmpresa()}\nImporte: {$this->getImporte()}\n\n\tResponsable:\n{$this->getObjResponsable()->__toString()}\nCant. Máxima de pasajeros: {$this->getCantMaxPasajeros()}\n\n\tPasajeros:\n {$textoPasajeros}";
    }
}