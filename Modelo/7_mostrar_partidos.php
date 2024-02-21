<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la obtención de partidos en un torneo.
 */

/**
 * @mainpage Documentación de Obtención de Partidos en un Torneo
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de partidos en un torneo.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_torneo.php');
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
 * Maneja la obtención de partidos en un torneo.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_torneo El ID del torneo para mostrar los partidos.
    */
    $id_torneo = isset($_POST['id_torneo']) ? intval(trim($_POST['id_torneo'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_partido = new partido();

    // Verifica si existe ese torneo.
    $verificar = $objeto_torneo->verificar_ID($id_torneo, $conexion);
    if(!empty($verificar)){
        // Obtiene los partidos de ese torneo.
        $resultado = $objeto_partido->mostrar_partidos($id_torneo,$conexion);

        // Devuelve los partidos en formato JSON, si no hay datos retorna array vacio.
        echo json_encode(array('partidos' => $resultado));
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