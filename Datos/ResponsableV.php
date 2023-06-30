<?php
include_once "BaseDatos.php";
class ResponsableV
{

    private $nombre;
    private $apellido;
    private $numLicencia;
    private $numEmpleado;
    private $mensajeOperacion;

    public function __construct()
    {

        $this->nombre = "";
        $this->apellido = "";
        $this->numLicencia = "";
        $this->numEmpleado = "";
    }

    public function cargar($numEmpleado, $nombre, $apellido, $numLicencia)
    {
        $this->setNumEmpleado($numEmpleado);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumLicencia($numLicencia);

    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function setNumEmpleado($numEmpleado){
        $this->numEmpleado = $numEmpleado;
    }

    public function setNumLicencia($numLicencia){
        $this->numLicencia = $numLicencia;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getNumEmpleado(){
        return $this->numEmpleado;
    }
    public function getNumLicencia(){
        return $this->numLicencia;
    }

    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }


    public function Buscar($numEmpleado)
    {
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable WHERE rnumeroempleado = '{$numEmpleado}'";
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                if ($row2 = $base->Registro()) {
                    $this->setNombre($row2['rnombre']);
                    $this->setApellido($row2['rapellido']);
                    $this->setNumEmpleado($row2['rnumeroempleado']);
                    $this->setNumLicencia($row2['rnumerolicencia']);
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
        $arregloResponsables = null;
        $base = new BaseDatos();
        $consultaResponsable = "Select * from responsable ";
        if ($condicion != "") {
            $consultaResponsable .= ' where ' . $condicion;
        }
        $consultaResponsable .= " order by rapellido ";
        //echo $consultaPasajeros;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                $arregloResponsables = array();
                while ($row2 = $base->Registro()) {

                    $nombre = $row2['rnombre'];
                    $apellido = $row2['rapellido'];
                    $numeroEmpleado = $row2['rnumeroempleado'];
                    $numeroLicencia = $row2['rnumerolicencia'];

                    $responsable = new ResponsableV();
                    $responsable->cargar($numeroEmpleado, $nombre, $apellido, $numeroLicencia);
                    array_push($arregloResponsables, $responsable);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloResponsables;
    }



    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO responsable(rapellido, rnombre, rnumerolicencia) 
				VALUES ('{$this->getApellido()}', '{$this->getNombre()}', {$this->getNumLicencia()})";

        if ($base->Iniciar()) {

            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setNumEmpleado($id);
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
        $consultaModificar = "UPDATE responsable SET rapellido = '{$this->getApellido()}', rnombre = '{$this->getNombre()}', rnumerolicencia = {$this->getNumLicencia()} WHERE rnumeroempleado = {$this->getNumEmpleado()}";
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
            $consultaBorrar = "DELETE FROM responsable WHERE rnumeroempleado = {$this->getNumEmpleado()}";
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
        return "Nombre: {$this->getNombre()} - Apellido: {$this->getApellido()}\nNúmero de licencia: {$this->getNumLicencia()}\nNúmero de empleado: {$this->getNumEmpleado()}";
    }

}