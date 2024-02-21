<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la verificación de estadisticas de jugadores.
 */

/**
 * @mainpage Documentación de Verificación de estadisticas de jugadores.
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la estadisticas de jugadores. en un torneo.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_partido.php');
include('clases/clase_torneo.php');
include('clases/clase_jugador.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Maneja la verificación de estadisticas de jugadores.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido que ya fue jugado.
    * @param int $id_torneo El ID del torneo al que pertenece ese partido.
    */
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;
    $id_torneo = isset($_POST['id_torneo']) ? intval(trim($_POST['id_torneo'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_partido = new partido();
    $objeto_torneo= new torneo();
    $objeto_jugador= new jugador();

    // Verifica si existe ese torneo.
    $verificar=$objeto_torneo->verificar_ID($id_torneo,$conexion);
    if(!empty($verificar)){

        // Verifica si existe ese partido.
        $verificar = $objeto_partido->verificar_ID($id_partido, $conexion);

        if(!empty($verificar)){
            
            // Obtener el estado del partido.
            $estado_BD=$verificar['estado'];

            // Verificar el estado del partido.
            switch ($estado_BD) {
                case "jugado":
                    // Obtener las estadisticas de jugadores.
                    $respuesta=$objeto_jugador->extraer_partidos_jugados($id_torneo,$id_partido,$conexion);
                    
                    // Verificar que existen estadisticas de jugadores.
                    if($respuesta){
                        echo json_encode(array('existen_registros' => true));
                    } else {
                        echo json_encode(array('no_existen_registros' => true));
                    }
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