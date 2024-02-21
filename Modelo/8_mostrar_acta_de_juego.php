<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la obtención de detalles de un partido.
 */

/**
 * @mainpage Documentación de la obtención de detalles de un partido
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de detalles de un partido, incluyendo estadísticas de jugadores y sanciones.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_partido.php');
include('clases/clase_torneo.php');
include('clases/clase_jugador.php');
include('clases/clase_sanciones.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Maneja la obtención de detalles de un partido.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido que se quiere ver la informacion.
    * @param int $id_torneo El ID del torneo.
    */
    $id_torneo = isset($_POST['id_torneo']) ? intval(trim($_POST['id_torneo'])) : 0;
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_partido = new partido();
    $objeto_jugador = new jugador();
    $objeto_sancion = new sanciones();

    // Verifica si existe ese torneo.
    $verificar = $objeto_torneo->verificar_ID($id_torneo, $conexion);
    if(!empty($verificar)){
        // Verificar si existe ese partido.
        $verificar = $objeto_partido->verificar_ID($id_partido, $conexion);
        if(!empty($verificar)){
            $estado_BD=$verificar['estado'];
            // Verificar si el estado del partido es jugado.
            switch ($estado_BD) {
                case "jugado":
                    // Consultas para obtener el partido, las estadisticas de jugadores y las sanciones de ese partido.
                    $partido=$objeto_partido->mostrar_partido($id_partido,$conexion);
                    $estadisticas=$objeto_jugador->mostrar_estadisticas_jugador_partido($id_partido, $id_torneo, $conexion);
                    $sanciones=$objeto_sancion->mostrar_sancion($id_partido,$conexion);

                    // Devuelve la informacion detallada en formato JSON, si no hay datos retorna array vacios.
                    echo json_encode(array('datos_partido' => $partido,'estadisticas' => $estadisticas,'sanciones'=>$sanciones));
                    break;
                case "porjugar":
                    echo json_encode(array('partido_por_jugar' => true));
                    break;
                case "programar":
                    echo json_encode(array('partido_no_programado' => true));
                    break;
                case "posponer":
                    echo json_encode(array('partido_pospuesto' => true));
                    break;
                default:
                    echo json_encode(array('error' => true));
            }
        } else {
            // Si el partido no existe.
            echo json_encode(array('no_existe_partido' => true));
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