<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la generación de partidos en un torneo.
 */

/**
 * @mainpage Documentación de Generación de Partidos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la generación de partidos en un torneo.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_partido.php');
include('clases/clase_torneo.php');
include('clases/clase_equipo.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Maneja la generación de partidos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_torneo El ID del torneo que se quieren generar partido.
    * @param array $equipos Un array con los id de los equipos participantes en el torneo
    */
    $id_torneo = isset($_POST['id_torneo']) ? intval(trim($_POST['id_torneo'])) : 0;
    $equipos = isset($_POST['equipos']) ? $_POST['equipos'] : "";

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_partido = new partido();
    $objeto_equipo = new equipo();

    // Verifica si existe ese torneo.
    $verificar = $objeto_torneo->verificar_ID($id_torneo, $conexion);
    if(!empty($verificar)){
        // Verificar si ya existen partidos creados en ese torneo.
        $existen_partidos = $objeto_partido->mostrar_partidos($id_torneo,$conexion);
        if(empty($existen_partidos)){

            // Verificar que los datos no esten vacios.
            if(!empty($equipos)){

               // Obtener el id de categoria asociado a ese torneo.
                $id_categoria=$verificar['id_categoria'];

                // Verficar los id de equipos.
                $cont=0;
                $valido=false;
                for($i=0; $i < count($equipos); $i++){
                    $verificar=$objeto_equipo->verificar_ID($equipos[$i],$conexion);
                    if($verificar){
                        $cont++;
                    }
                    if($i==count($equipos)-1){
                        if($i==$cont-1){
                            $valido=true;
                        }
                    }
                }

                // Verificar si todos los id de equipos son correctos.
                if($valido){
                    $num_equipos=count($equipos);// Obtener le numero de equipos

                    // Se registran las estadisticas de los equipos inicialmente en 0
                    $objeto_equipo->registar_estadisticas_equipo($equipos,$id_torneo,$conexion);

                    // Verificar si el numero de equipos es impar
                    if($num_equipos%2!=0){
                        $equipos[$num_equipos]=30; //se asigna el id de equipo descansa
                        $num_equipos++;//se aumenta el num de equipos porque es impar
                    }
                    $num_partidos=$num_equipos/2;
                    
                    // Consulta para ganerar los partidos para ese torneo.
                    $resultado = $objeto_partido->generar_partidos($equipos,$num_equipos);

                    // Consulta para insertar equipos.
                    $respuesta = $objeto_partido->insertar_partido($resultado, $num_equipos, $id_torneo, $conexion);
                    
                    // Verificar que se inserto los partidos.
                    if ($respuesta) {
                        echo json_encode(array('success' => true));
                    } else {
                        echo json_encode(array('noInserto' => true));
                    }
                } else {
                    // Si no existe equipo o equipos.
                    echo json_encode(array('no_existen_equipo' => true));
                }
            } else {
                // Al menos una de las variables está vacía.
                echo json_encode(array('noHayDatos' => true));
            }
        } else {
            // Si no existen partidos.
            echo json_encode(array('existen_partidos' => true));
        }
    } else {
        // Si el torneo no existe.
        echo json_encode(array('no_existe_torneo' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>