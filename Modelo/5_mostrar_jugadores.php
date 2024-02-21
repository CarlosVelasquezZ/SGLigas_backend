<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la visualización de jugadores de un equipo en una base de datos.
 */

/**
 * @mainpage Documentación de Manejar Visualización de Jugadores de un Equipo en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la visualización de jugadores de un equipo en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_jugador.php');
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
 * Muestra los jugadores de un equipo.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_equipo El ID del equipo para mostrar los jugadores.
    */
    $id_equipo = isset($_POST['id_equipo']) ? intval(trim($_POST['id_equipo'])) : 0;

    // Crea instancias de las clases a ser usadas.  
    $objeto_jugador = new jugador();
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de equipo.
    $id_equipo = $objeto_equipo->verificar_ID($id_equipo, $conexion);
    if ($id_equipo > 0) {
        // Obtiene los jugadores de un equipo.
        $resultado = $objeto_jugador->mostrar_jugadores($id_equipo, $conexion);

        // Devuelve los jugadores de un equipo en formato JSON, si no hay datos retorna array vacio.
        echo json_encode(array('datos' => $resultado));
    } else {
        // Si el equipo no existe.
        echo json_encode(array('no_existe_equipo' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>