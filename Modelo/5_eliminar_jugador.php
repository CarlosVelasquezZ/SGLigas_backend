<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la eliminación de un jugador en una base de datos.
 */

/**
 * @mainpage Documentación de Eliminacion de jugadores en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la eliminación de jugadores en una base de datos. 
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
 * Elimina un jugador en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $CI La CI del jugador a eliminar.
    */
    $CI = isset($_POST['CI']) ? intval(trim($_POST['CI'])) : 0;

    // Crea una instancia de la clase categoria. 
    $objeto_jugador = new jugador();

    // Verifica si existe ese ID de liga.
    $CI = $objeto_jugador->verificar_CI($CI, $conexion);
    if ($CI>0) {

        // Consulta para eliminar categoría.
        $resultado = $objeto_jugador->eliminar_jugador($CI, $conexion);

        // Verifica si se desactivó el jugador.
        if ($resultado) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('noInserto' => true));
        }                    
    } else {
        // Si el id de categoría no existe
        echo json_encode(array('no_existe_jugador' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>