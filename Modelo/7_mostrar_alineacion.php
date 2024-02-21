<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la obtención de alineaciones de jugadores en partidos.
 */

/**
 * @mainpage Documentación de Obtención de Alineaciones de Jugadores
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de alineaciones de jugadores en partidos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_partido.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Maneja la obtención de alineaciones de jugadores en partidos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido que se quiere mostrar la alineación el partido debe estar en estado jugado o por jugar.
    */
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;

    // Crea una instancia de la clase partido.
    $objeto_partido = new partido();

    // Verifica si existe ese partido.
    $verificar = $objeto_partido->verificar_ID($id_partido, $conexion);
    if(!empty($verificar)){

        // Obtener el estado del partido.
        $estado_BD=$verificar['estado'];

        // Verificar el estado del partido.
        switch ($estado_BD) {
            case "porjugar":
            case "jugado":
                // Consulta para obtener la alineacion del partido.
                $alineacion=$objeto_partido->verificar_existencia_alineacion($id_partido, $conexion);

                // Devuelve la alineación en formato JSON, si no hay datos retorna array vacio.
                echo json_encode(array('datos' => $alineacion['datos_alineacion']));
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

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>