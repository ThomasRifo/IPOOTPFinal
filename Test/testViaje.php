<?php

include_once './Datos/BaseDatos.php';
include_once './Datos/Empresa.php';
include_once './Datos/Pasajero.php';
include_once './Datos/ResponsableV.php';
include_once './Datos/Viaje.php';


//MENU

$opcion = 0;
while ($opcion != 15) {
    echo "\n*****MENÚ PRINCIPAL*****\n";
    echo "1. Cargar una empresa de viajes\n";
    echo "2. Modificar una empresa de viajes\n";
    echo "3. Eliminar una empresa de viajes\n";
    echo "4. Cargar un viaje\n";
    echo "5. Modificar Información de un viaje\n";
    echo "6. Eliminar un viaje\n";
    echo "7. Modificar información de un pasajero\n";
    echo "8. Cargar un responsable de viaje.\n";
    echo "9. Modificar información de un responsable de viaje.\n";
    echo "10. Eliminar un responsable de viaje.\n";
    echo "11. Mostrar Empresa.\n";
    echo "12. Mostrar Viajes.\n";
    echo "13. Mostar Responsables.\n";
    echo "14. Mostar Pasajeros.\n";
    echo "15. Salir\n";
    echo "Ingrese una opcion: ";
    $opcion = trim(fgets(STDIN));
    echo "\n**************************\n";
    switch ($opcion) {
        case 1:
            echo "\nIngrese el nombre de la Empresa: ";
            $nombre = trim(fgets(STDIN));
            echo "\nIngrese la dirección de la Empresa: ";
            $direccion = trim(fgets(STDIN));
            $objEmpresa = new Empresa();
            $objEmpresa->cargar(0, $nombre, $direccion);
            $objEmpresa->insertar();
            echo "\nLa empresa fue cargada con éxito!\n";
            break;

        case 2:
            $empresa = new Empresa();

            $colEmpresas = $empresa->listar();
            foreach ($colEmpresas as $objEmpresa) {
                echo $objEmpresa->__toString() . "-----------------\n";
            }

            echo "\nIngrese el id de la Empresa a modificar: ";
            $idEmpresa = trim(fgets(STDIN));
            $existe = $empresa->Buscar($idEmpresa);
            if ($existe) {
                $opcion = 0;
                while ($opcion != 3) {
                    echo "\n*******MENÚ MODIFICAR EMPRESA*******";
                    echo "\n1. Modificar el nombre de la Empresa.\n";
                    echo "2. Modificar la dirección de la Empresa.\n";
                    echo "3. Salir.\n";
                    echo "Ingrese una opción: ";
                    $opcion = trim(fgets(STDIN));
                    echo "\n****************\n";

                    switch ($opcion) {
                        case 1:
                            echo "\nIngrese el nuevo nombre de la Empresa: ";
                            $nuevoNombre = trim(fgets(STDIN));
                            $empresa->setNombre($nuevoNombre);
                            $empresa->modificar();
                            echo "\nEl nombre de la Empresa fue modificado con éxito!";
                            break;
                        case 2:
                            echo "\nIngrese la nueva dirección de la Empresa: ";
                            $nuevaDireccion = trim(fgets(STDIN));
                            $empresa->setDireccion($nuevaDireccion);
                            $empresa->modificar();
                            echo "\nLa dirección de la Empresa fue modificada con éxito!";
                            break;
                        case 3:
                            break;
                    }
                }
            } else {
                echo "\nLa empresa que desea modificar no existe.";
            }
            break;
        case 3:
            $empresa = new Empresa();
            $viaje = new Viaje();
            $colEmpresas = $empresa->listar();
            foreach ($colEmpresas as $objEmpresa) {
                $objEmpresa->Buscar($objEmpresa->getIdEmpresa());
                echo $objEmpresa->__toString() . "-----------------\n";
            }
            echo "\nIngrese el id de la Empresa que desea eliminar: ";
            $idEmpresa = trim(fgets(STDIN));
            $existe = $empresa->Buscar($idEmpresa);
            if ($existe) {
                $colViajesEmpresa = $viaje->listar($condicion = "idempresa=" . $idEmpresa);
                if (count($colViajesEmpresa) > 0) {
                    echo "La empresa tiene viajes a su cargo, en caso de eliminar la empresa se eliminarán los viajes que ésta tenga a su cargo y todo lo correspondiente a esos viajes. \n¿Desea continuar?(s/n): ";
                    $continuar = trim(fgets(STDIN));
                    if ($continuar == "s") {
                        $empresa->eliminar();
                        echo "\nLa empresa fue eliminada con éxito.";
                    } else {
                        echo "La empresa no será eliminada del sistema.";
                    }
                } else {
                    $empresa->eliminar();
                    echo "\nLa empresa fue eliminada con éxito";
                }
            } else {
                echo "\nLa empresa que desea eliminar no existe.";
            }
            break;
        case 4:
            echo "\nIngrese el destino: ";
            $destino = trim(fgets(STDIN));
            echo "\nIngrese la cantidad máxima de pasajeros: ";
            $cantMax = trim(fgets(STDIN));
            echo "\nIngrese el importe del viaje: ";
            $importe = trim(fgets(STDIN));
            $empresa = new Empresa();

            $colEmpresas = $empresa->listar();
            foreach ($colEmpresas as $objEmpresa) {
                $objEmpresa->Buscar($objEmpresa->getIdEmpresa());
                echo "***************\n". $objEmpresa->__toString() . "***************\n";
            }
            echo "\nIngrese el id de la Empresa a cargo: ";
            $idEmpresa = trim(fgets(STDIN));
            $empleado = new ResponsableV();

            $colEmpleados = $empleado->listar();
            foreach ($colEmpleados as $objEmpleado) {
                echo "***************\n". $objEmpleado->__toString() . "\n-----------------\n";
            }
            echo "\nIngrese el nro de empleado del responsable del viaje: ";
            $numResponsable = trim(fgets(STDIN));

            $objResponsable = new ResponsableV();
            $existeResponsable = $objResponsable->Buscar($numResponsable);
            $empresa = new Empresa();
            $existeEmpresa = $empresa->buscar($idEmpresa);

            if ($existeEmpresa) {
                if ($existeResponsable) {
                    $viaje = new Viaje();
                    $viaje->cargar(0, $destino, $objResponsable, $cantMax, $empresa, $importe);
                    $viaje->insertar();
                } else {
                    echo "\nEl responsable indicado no existe.\n";
                }
            } else {
                echo "\nLa Empresa a cargo no existe.\n";
            }
            break;
        case 5:
            $viaje = new Viaje();

            $colViajes = $viaje->listar();
            foreach ($colViajes as $objViaje) {
                $objViaje->buscar($objViaje->getIdViaje());
                echo $objViaje->__toString() . "\n---------------------\n";
            }


            echo "\nIngrese el id del viaje a modificar: ";
            $idViaje = (int)trim(fgets(STDIN));
            $existe = $viaje->Buscar($idViaje);
            if ($existe) {
                $opcion = 0;
                while ($opcion != 8) {
                    echo "\n*****MENÚ MODIFICAR VIAJE*****\n";
                    echo "\n1. Modificar el destino del viaje.";
                    echo "\n2. Modificar la cantidad máxima de pasajeros.";
                    echo "\n3. Modificar la empresa a cargo.";
                    echo "\n4. Modificar el importe del viaje.";
                    echo "\n5. Agregar pasajero.";
                    echo "\n6. Eliminar pasajero.";
                    echo "\n7. Modificar responsable.";
                    echo "\n8. Salir.";
                    echo "\nElija una opción: ";

                    $opcion = trim(fgets(STDIN));
                    echo "\n***************************\n";

                    switch ($opcion) {
                        case 1:
                            echo "\nIngrese el nuevo destino: ";
                            $destino = trim(fgets(STDIN));
                            $viaje->setDestino($destino);
                            $viaje->modificar();
                            echo "\nEl destino fue modificado con éxito!";
                            break;
                        case 2:
                            echo "\nIngrese la nueva capacidad máxima del viaje: ";
                            $cantMax = trim(fgets(STDIN));
                            $viaje->setCantMaxPasajeros($cantMax);
                            $viaje->modificar();
                            echo "\nLa capacidad fue modificada con éxito!";
                            break;
                        case 3:
                            $empresa = new Empresa();

                            $colEmpresas = $empresa->listar();
                            foreach ($colEmpresas as $objEmpresa) {
                                $objEmpresa->Buscar($objEmpresa->getIdEmpresa());
                                echo $objEmpresa->__toString() . "-----------------\n";
                            }
                            echo "\nIngrese el id de la nueva Empresa a cargo: ";
                            $idEmpresa = trim(fgets(STDIN));
                            $existe = $empresa->Buscar($idEmpresa);
                            if ($existe) {
                                $viaje->setEmpresa($empresa);
                                $viaje->modificar();
                                echo "\nLa empresa a cargo del viaje fue modificada exitosamente.";
                            } else {
                                echo "\nEl id ingresado no corresponde a una empresa existente.";
                            }
                            break;
                        case 4:
                            echo "\nIngresar el nuevo importe del viaje: ";
                            $importe = trim(fgets(STDIN));
                            $viaje->setImporte($importe);
                            $viaje->modificar();
                            echo "\nEl importe fue modificado con éxito!";
                            break;
                        case 5:
                            echo "\nIngrese el documento del pasajero que desea agregar: ";
                            $dni = trim(fgets(STDIN));
                            $pasajero = new Pasajero();
                            $existe = $pasajero->Buscar($dni);
                            if ($existe && $idViaje == $pasajero->getObjViaje()->getIdViaje()) {
                                echo "\nEl pasajero ya se encuentra en el viaje.";
                            } else {
                                if ($viaje->getCantMaxPasajeros() > count($pasajero->listar($condicion = "idViaje =" . $idViaje ))) {
                                    echo "\nIngrese el nombre: ";
                                    $nombre = trim(fgets(STDIN));
                                    echo "\nIngrese el apellido: ";
                                    $apellido = trim(fgets(STDIN));
                                    echo "\nIngrese el número de teléfono: ";
                                    $telefono = trim(fgets(STDIN));
                                    $pasajero->cargar($dni, $nombre, $apellido, $telefono, $viaje);
                                    $pasajero->insertar();
                                    echo "\nEl pasajero fue agregado al viaje con éxito.";
                                } else {
                                    echo "\nEl viaje se encuentra completo.";
                                }
                            }
                            break;
                        case 6:
                            $pasajero = new Pasajero();

                            $colPasajeros = $pasajero->listar();
                            foreach ($colPasajeros as $objPasajero) {

                                echo $objPasajero->__toString() . "\n-----------------\n";
                            }
                            echo "\nIngrese el DNI del pasajero que desea eliminar del viaje: ";
                            $dni = trim(fgets(STDIN));

                            $existe = $pasajero->Buscar($dni);
                            if ($existe && $idViaje == $pasajero->getObjViaje()->getIdViaje()) {
                                //Codigo para eliminar al pasajero del viaje específico
                                $pasajero->eliminar($idViaje);
                                echo "\nEl pasajero fue eliminado del viaje correctamente.";
                            } else {
                                echo "\nEl pasajero no se encuentra en este viaje.";
                            }
                            break;
                        case 7:
                            $empleado = new ResponsableV();
                            $colEmpleados = $empleado->listar();
                            foreach ($colEmpleados as $objEmpleado) {
                                echo $objEmpleado->__toString() . "\n-----------------\n";
                            }
                            echo "\nIngrese el número de empleado del nuevo responsable del viaje: ";
                            $numResponsable = trim(fgets(STDIN));

                            $existeResponsable = $empleado->Buscar($numResponsable);
                            if ($existeResponsable) {
                                $viaje->setObjResponsable($empleado);
                                $viaje->modificar();
                                echo "El responsable fue modificado con éxito.\n";
                            }else{
                                echo "El responsable que ingresó no existe.\n";
                            }
                            break;
                        case 8:
                            break;
                    }
                }
            } else {
                echo "\nEl viaje ingresado no existe.";
            }
            break;
        case 6:
            $viaje = new Viaje();

            $colViajes = $viaje->listar();
            foreach ($colViajes as $objViaje) {
                $objViaje->buscar($objViaje->getIdViaje());
                echo $objViaje->__toString() . "\n---------------------\n";
            }

            echo "\nIngresar el id del viaje que desea eliminar: ";
            $idViaje = trim(fgets(STDIN));

            $existe = $viaje->Buscar($idViaje);
            if ($existe) {
                $viaje->eliminar();
                echo "\nEl viaje fue eliminado correctamente.";
            } else {
                echo "\nEl viaje que desea eliminar no existe.";
            }
            break;
        case 7:
            $pasajero = new Pasajero();
            $colPasajeros = $pasajero->listar();
            foreach ($colPasajeros as $objPasajero) {

                echo $objPasajero->__toString() . "\n-----------------\n";
            }

            echo "\nIngrese el DNI del pasajero a modificar: ";
            $dni = trim(fgets(STDIN));
            $existe = $pasajero->Buscar($dni);
            if ($existe) {
                $opcion = 0;
                while ($opcion != 4) {
                    echo "\n*****MENÚ MODIFICAR PASAJERO******\n";
                    echo "\n1. Modificar el nombre.";
                    echo "\n2. Modificar el apellido.";
                    echo "\n3. Modificar el número de teléfono.";
                    echo "\n4. Salir.";
                    echo "\nElija una opción: ";
                    $opcion = trim(fgets(STDIN));
                    echo "\n*****************\n";

                    switch ($opcion) {
                        case 1:
                            echo "\nIngrese el nombre actualizado: ";
                            $nombre = trim(fgets(STDIN));
                            $pasajero->setNombre($nombre);
                            $pasajero->modificar();
                            echo "\nEl nombre fue modificado con éxito!";
                            break;
                        case 2:
                            echo "\nIngrese el nuevo apellido: ";
                            $apellido = trim(fgets(STDIN));
                            $pasajero->setApellido($apellido);
                            $pasajero->modificar();
                            echo "\nEl apellido fue modificado con éxito!";
                            break;
                        case 3:
                            echo "\nIngrese el número de teléfono actualizado: ";
                            $telefono = trim(fgets(STDIN));
                            $pasajero->setTelefono($telefono);
                            $pasajero->modificar();
                            echo "\nEl número de teléfono fue actualizado con éxito!";
                            break;
                        case 4:
                            break;
                    }
                }
            } else {
                echo "\nEl pasajero que desea modificar no existe.";
            }
            break;
        case 8:
            echo "\nIngrese el nombre del nuevo responsable: ";
            $nombre = trim(fgets(STDIN));
            echo "\nIngrese el apellido del nuevo responsable: ";
            $apellido = trim(fgets(STDIN));
            echo "\nIngrese el número de licencia del nuevo responsable: ";
            $numLicencia = trim(fgets(STDIN));
            $responsable = new ResponsableV();
            $responsable->cargar(0, $nombre, $apellido, $numLicencia);
            $responsable->insertar();
            echo "\nEl responsable fue cargado con éxito.";

            break;
        case 9:

            $empleado = new ResponsableV();

            $colEmpleados = $empleado->listar();
            foreach ($colEmpleados as $objEmpleado) {
                echo $objEmpleado->__toString() . "\n-----------------\n";
            }

            echo "\nIngrese el número de empleado del responsable que desea modificar: ";
            $numEmpleado = trim(fgets(STDIN));
            $responsable = new ResponsableV();
            $existe = $responsable->Buscar($numEmpleado);
            if ($existe) {
                $opcion = 0;
                while ($opcion != 3) {
                    echo "\n*******MENÚ MODIFICAR RESPONSABLE*******";
                    echo "\n1. Modificar el nombre.";
                    echo "\n2. Modificar el apellido.";
                    echo "\n3. Salir";
                    echo "\nElija una opción: ";
                    $opcion = trim(fgets(STDIN));
                    echo "\n****************\n";

                    switch ($opcion) {
                        case 1:
                            echo "\nIngrese el nombre actualizado: ";
                            $nombre = trim(fgets(STDIN));
                            $responsable->setNombre($nombre);
                            $responsable->modificar();
                            echo "\nEl nombre fue actualizado con éxito.";
                            break;
                        case 2:
                            echo "\nIngrese el apellido actualizado: ";
                            $apellido = trim(fgets(STDIN));
                            $responsable->setApellido($apellido);
                            $responsable->modificar();
                            echo "\nEl apellido fue actualizado con éxito.";
                            break;
                    }
                }
            }
            break;
        case 10:
            $empleado = new ResponsableV();
            $viaje = new Viaje();
            $colEmpleados = $empleado->listar();
            foreach ($colEmpleados as $objEmpleado) {
                echo $objEmpleado->__toString() . "\n-----------------\n";
            }
            echo "\nIngrese el nro de empleado del Responsable que desea eliminar: ";
            $numResponsable = trim(fgets(STDIN));
            $responsable = new ResponsableV();
            $existe = $responsable->Buscar($numResponsable);
            if ($existe) {
                $colViajesResponsable = $viaje->listar($condicion = "rnumeroempleado=" . $numResponsable);
                if (count($colViajesResponsable) > 0) {
                    echo "El empleado tiene viajes a su cargo, en caso de eliminar al responsable se eliminarán los viajes que se encuentren a su cargo. ¿Desea continuar?(s/n): ";
                    $continuar = trim(fgets(STDIN));
                    if ($continuar == "s") {
                        $responsable->eliminar();
                        echo "\nEl empleado fue eliminado del sistema con éxito.";
                    } else {
                        echo "\nEl empleado no será borrado del sistema.";
                    }
                } else {
                    $responsable->eliminar();
                    echo "\nEl empleado fue eliminado del sistema con éxito.";
                }
            } else {
                echo "\nEl empleado que desea eliminar no existe en el sistema.";
            }
            break;
        case 11:
            while ($opcion != 3) {
                echo "\n*****MENÚ MOSTRAR EMPRESA******";
                echo "\n1. Mostrar todas las empresas.";
                echo "\n2. Mostrar una empresa.";
                echo "\n3. Salir.";
                echo "\nElija una opción: ";
                $opcion = trim(fgets(STDIN));
                echo "\n*****************\n";

                switch ($opcion) {
                    case 1:
                        $empresa = new Empresa();
                        $colEmpresas = $empresa->listar();
                        foreach ($colEmpresas as $objEmpresa) {
                            echo $objEmpresa->__toString() . "\n-----------------\n";
                        }
                        break;
                    case 2:
                        echo "\nIngrese el id de la empresa que desea mostrar: ";
                        $idEmpresa = trim(fgets(STDIN));
                        $empresa = new Empresa();
                        $existe = $empresa->Buscar($idEmpresa);
                        if ($existe) {
                            $colEmpresas = $empresa->listar($condicion = "idempresa=" . $idEmpresa);
                            foreach ($colEmpresas as $objEmpresa) {
                                echo $objEmpresa->__toString() . "\n-----------------\n";
                            }
                        } else {
                            echo "\nLa empresa que desea mostrar no se encuentra en el sistema.";
                        }

                        break;
                    case 3:
                        break;
                }
            }
            break;
        case 12:
            while ($opcion != 3) {
                echo "\n*****MENÚ MOSTRAR VIAJES******";
                echo "\n1. Mostrar todos los viajes.";
                echo "\n2. Mostrar un viaje.";
                echo "\n3. Salir.";
                echo "\nElija una opción: ";
                $opcion = trim(fgets(STDIN));
                echo "\n*****************\n";

                switch ($opcion) {
                    case 1:
                        $viaje = new Viaje();
                        $colViajes = $viaje->listar();
                        foreach ($colViajes as $objViaje) {
                            echo $objViaje->__toString() . "\n-----------------\n";
                        }
                        break;
                    case 2:
                        echo "\nIngrese el id del viaje que desea mostrar: ";
                        $idViaje = trim(fgets(STDIN));
                        $viaje = new Viaje();
                        $existe = $viaje->Buscar($idViaje);
                        if ($existe) {
                            $colViajes = $viaje->listar($condicion = "idviaje=" . $idViaje);
                            foreach ($colViajes as $objViaje) {
                                echo $objViaje->__toString() . "\n-----------------\n";
                            }
                        } else {
                            echo "\nEl viaje que desea mostrar no se encuentra en el sistema.";
                        }

                        break;
                    case 3:
                        break;
                }
            }
            break;
        case 13:
            //AGREGAR MOSTRAR RESPONSABLE DE X VIAJE!!
            while ($opcion != 3) {
                echo "\n*****MENÚ MOSTRAR RESPONSABLES******";
                echo "\n1. Mostrar todos los responsables.";
                echo "\n2. Mostrar un responsable.";
                echo "\n3. Salir.";
                echo "\nElija una opción: ";
                $opcion = trim(fgets(STDIN));
                echo "\n*****************\n";

                switch ($opcion) {
                    case 1:
                        $responsable = new ResponsableV();
                        $colResponsable = $responsable->listar();
                        foreach ($colResponsable as $objResponsable) {
                            echo $objResponsable->__toString() . "\n-----------------\n";
                        }
                        break;
                    case 2:
                        echo "\nIngrese el numero de empleado del responsable que desea mostrar: ";
                        $nroResponsable = trim(fgets(STDIN));
                        $responsable = new ResponsableV();
                        $existe = $responsable->Buscar($nroResponsable);
                        if ($existe) {
                            $colResponsable = $responsable->listar($condicion = "rnumeroempleado=" . $nroResponsable);
                            foreach ($colResponsable as $objResponsable) {
                                echo $objResponsable->__toString() . "\n-----------------\n";
                            }
                        } else {
                            echo "\nEl responsable que desea mostrar no se encuentra en el sistema.";
                        }

                        break;
                    case 3:
                        break;
                }
            }
            break;
        case 14:
            while ($opcion != 4) {
                echo "\n*****MENÚ MOSTRAR PASAJEROS******";
                echo "\n1. Mostrar todos los pasajeros.";
                echo "\n2. Mostrar un pasajero.";
                echo "\n3. Mostrar los pasajeros de un viaje en específico.";
                echo "\n4. Salir.";
                echo "\nElija una opción: ";
                $opcion = trim(fgets(STDIN));
                echo "\n*****************\n";

                switch ($opcion) {
                    case 1:
                        $pasajero = new Pasajero();
                        $colPasajeros = $pasajero->listar();
                        foreach ($colPasajeros as $objPasajero) {
                            echo $objPasajero->__toString() . "\n-----------------\n";
                        }
                        break;
                    case 2:
                        echo "\nIngrese el documento del pasajero que desea mostrar: ";
                        $nroDocumento = trim(fgets(STDIN));
                        $pasajero = new Pasajero();
                        $existe = $pasajero->Buscar($nroDocumento);
                        if ($existe) {
                            $colPasajeros = $pasajero->listar($condicion = "pdocumento=" . $nroDocumento);
                            foreach ($colPasajeros as $objPasajero) {
                                echo $objPasajero->__toString() . "\n-----------------\n";
                            }
                        } else {
                            echo "\nEl pasajero que desea mostrar no se encuentra en el sistema.";
                        }
                        break;
                    case 3:
                        echo "\nIngrese el id del viaje del que desea mostrar los pasajeros: ";
                        $idViaje = trim(fgets(STDIN));
                        $viaje = new Viaje();
                        $pasajero = new Pasajero();
                        $existe = $viaje->Buscar($idViaje);
                        if ($existe) {
                            $colPasajeros = $pasajero->listar($condicion = "idviaje=" . $idViaje);
                            if(count($colPasajeros) > 0){
                                foreach ($colPasajeros as $objPasajero) {
                                    echo $objPasajero->__toString() . "\n-----------------\n";
                                }
                            } else {
                                echo "\nNo se encuentran pasajeros en este viaje.";
                            }
                            
                        } else {
                            echo "\nEl viaje del que desea mostrar los pasajeros no se encuentra en el sistema.";
                        }

                        break;
                }
            }
        case 15:
            break;
    }
}
