<?php
include_once "BaseDatos.php";
include_once "Viaje.php";
class Pasajero
{

    private $dni;
    private $nombre;
    private $apellido;
    private $telefono;
    private $objViaje;
    private $mensajeoperacion;


    public function __construct()
    {

        $this->dni = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->telefono = "";
        $this->objViaje = new Viaje();
    }

    public function cargar($dni, $nombre, $apellido, $telefono, $objViaje)
    {
        $this->setDni($dni);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setTelefono($telefono);
        $this->setobjViaje($objViaje);
    }

    public function setObjViaje($objViaje){
        $this->objViaje = $objViaje;
    }
    public function getObjViaje(){
        return $this->objViaje;
    }
    public function setDni($dni)
    {
        $this->dni = $dni;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function setMensajeOperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getDni()
    {
        return $this->dni;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }


    public function getMensajeOperacion()
    {
        return $this->mensajeoperacion;
    }


    public function Buscar($dni)
    {
        $base = new BaseDatos();
        $consultaPasajero = "Select * from pasajero where pdocumento=" . $dni;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                if ($row2 = $base->Registro()) {
                    /*$this->setDni($dni);
                    $this->setNombre($row2['pnombre']);
                    $this->setApellido($row2['papellido']);
                    $this->setTelefono($row2['ptelefono']);*/
                    $viaje = new Viaje();
                    $viaje->Buscar($row2['idviaje']);
                    $this->cargar($dni, $row2['pnombre'], $row2['papellido'],$row2['ptelefono'], $viaje);
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
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajeros = "Select * from pasajero ";
        if ($condicion != "") {
            $consultaPasajeros = $consultaPasajeros . ' where ' . $condicion;
        }
        $consultaPasajeros .= " group by pdocumento order by papellido ";
        //echo $consultaPasajeros;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajeros)) {
                $arregloPasajero = array();
                while ($row2 = $base->Registro()) {

                    $dni = $row2['pdocumento'];
                    $nombre = $row2['pnombre'];
                    $apellido = $row2['papellido'];
                    $telefono = $row2['ptelefono'];
                    $viaje = new Viaje();
                    $viaje->Buscar($row2['idviaje']);

                    $pasajero = new Pasajero();
                    $pasajero->cargar($dni, $nombre, $apellido, $telefono, $viaje);
                    array_push($arregloPasajero, $pasajero);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloPasajero;
    }



    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero(pdocumento, papellido, pnombre, ptelefono, idviaje) 
				VALUES ('{$this->getDni()}', '{$this->getApellido()}', '{$this->getNombre()}', {$this->getTelefono()}, {$this->getObjViaje()->getIdViaje()})";

        if ($base->Iniciar()) {

            if ($base->Ejecutar($consultaInsertar)) {

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
        $consultaModificar = "UPDATE pasajero SET papellido = '{$this->getApellido()}', pnombre = '{$this->getNombre()}', ptelefono = '{$this->getTelefono()}' WHERE pdocumento = '{$this->getDni()}'";
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
    //Agregue como parámetro el idViaje para poder eliminar al pasajero solo del viaje al que se lo quiere eliminar y no de toda la base de datos.
    public function eliminar($idViaje)
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorrar = "DELETE FROM pasajero WHERE pdocumento = '{$this->getDni()}' AND idviaje = $idViaje";
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
        return "Apellido: " . $this->getApellido() . " - Nombre: " . $this->getNombre()   . "\nDNI: " . $this->getDni() . "\nTeléfono: ". $this->getTelefono();
    }
}
