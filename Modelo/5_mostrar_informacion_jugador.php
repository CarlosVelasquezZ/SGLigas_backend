<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la visualización de información detallada de un jugador en una base de datos.
 */

/**
 * @mainpage Documentación de Manejar Visualización de Información Detallada de un Jugador en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la visualización de información detallada de un jugador en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
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
 * Muestra la información detallada de un jugador.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $CI El número de cédula del jugador para mostrar la información.
    */
    $CI = isset($_POST['CI']) ? intval(trim($_POST['CI'])) : 0;

    // Crea una instancia de la clase jugador.
    $objeto_jugador = new jugador();

    // Verifica si existe esa CI.
    $verificar=$objeto_jugador->verificar_CI($CI,$conexion);
    if ($verificar > 0) {
        // Obtiene los resultados para mostrar la informacion del jugador.
        $resultado = $objeto_jugador->mostrar_informacion_jugador($CI, $conexion);

        // Devuelve la informacion de un jugador en formato JSON, si no hay datos retorna array vacio.
        echo json_encode(array('datos' => $resultado));
    } else {
        // Si la CI no existe.
        echo json_encode(array('no_existe_CI' => true));
    }
    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>