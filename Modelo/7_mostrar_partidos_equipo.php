<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la obtención de partidos por equipo en un torneo.
 */

/**
 * @mainpage Documentación de Obtención de Partidos por Equipo en un Torneo
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de partidos por equipo en un torneo.
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
 * Maneja la obtención de partidos por equipo en un torneo.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_torneo El ID del torneo.
    * @param int $id_equipo El ID del equipo para mostra los partidos en ese torneo.
    */
    $id_torneo = isset($_POST['id_torneo']) ? intval(trim($_POST['id_torneo'])) : 0;
    $id_equipo = isset($_POST['id_equipo']) ? intval(trim($_POST['id_equipo'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_partido = new partido();
    $objeto_equipo = new equipo();

    // Verifica si existe ese torneo.
    $verificar = $objeto_torneo->verificar_ID($id_torneo, $conexion);
    if(!empty($verificar)){

        // Verficar si existe ese equipo.
        $verificar = $objeto_equipo->verificar_ID($id_equipo,$conexion);
        if(!empty($verificar)){

            // Consulta para obtener los partidos de un equipo.
            $resultado = $objeto_partido->mostrar_partidos_equipos($id_torneo, $id_equipo, $conexion);

            // Devuelve los partidos de un equipo en formato JSON, si no hay datos retorna array vacio.
            echo json_encode(array('partidos' => $resultado));
        } else {
            // Si el equipo no existe.
            echo json_encode(array('no_existe_equipo' => true));
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