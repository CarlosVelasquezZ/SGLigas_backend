<?php
/**
 * @file
 * Este archivo contiene un script PHP para mostrar las posiciones de un torneo en una base de datos.
 */

/**
 * @mainpage Documentación de Mostrar las Posiciones de un Torneo en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de las posiciones de un torneo en una base de datos. 
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
 * Obtiene las posiciones de un torneo y la devuelve en formato JSON.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();
    
    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_torneo El ID del torneo para mostrar las posiciones.
    */
    $id_torneo = isset($_POST['id_torneo']) ? intval(trim($_POST['id_torneo'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_equipo = new equipo();

    // Verifica si existe ese torneo.
    $verificar = $objeto_torneo->verificar_ID($id_torneo, $conexion);
    if(!empty($verificar)){

        // Obtiene las posiciones del torneo.
        $resultado = $objeto_equipo->mostrar_posiciones($id_torneo, $conexion);

        // Devuelve las posiciones en formato JSON, si no hay datos retorna array vacio.
        echo json_encode(array('tabla' => $resultado));
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