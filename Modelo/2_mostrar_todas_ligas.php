<?php
/**
 * @file
 * Este archivo contiene un script PHP para mostrar todas las ligas activas en la base de datos.
 */

/**
 * @mainpage Documentación de Mostrar Todas las Ligas activas
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para mostrar todas las ligas activas en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_liga.php');
include('conexion.php');



/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Muestra todas las ligas activas en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    // Crea una instancia de la clase liga. 
    $objeto_liga = new liga();

    // Obtiene los datos de todas las ligas en la BD.
    $resultado = $objeto_liga->mostrar_todas_ligas('', $conexion);

    //Devuelve la información de todas las liga activas en formato JSON.
    echo json_encode(array('datos' => $resultado));

    // Cierra la conexión.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>