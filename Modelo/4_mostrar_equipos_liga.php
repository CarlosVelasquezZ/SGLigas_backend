<?php
/**
 * @file
 * Este archivo contiene un script PHP para mostrar equipos de una liga en una base de datos.
 */

/**
 * @mainpage Documentación de Mostrar Equipos de una Liga en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de equipos de una liga en una base de datos. 
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_equipo.php');
include('clases/clase_liga.php');
include('clases/clase_categoria.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Obtiene la información de los equipos de una liga.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_liga El ID de la liga para mostrar los equipos.
    */
    $id_liga = isset($_POST['id_liga']) ? intval(trim($_POST['id_liga'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_liga = new liga();
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de liga.
    $id_liga = $objeto_liga->verificar_ID($id_liga, $conexion);
    if ($id_liga > 0) {
        
        // Obtiene los resultados.
        $resultado = $objeto_equipo->mostrar_equipos_ligas($id_liga, $conexion);

        // Devuelve los equipos de una liga en formato JSON, si no hay datos retorna array vacio. 
        echo json_encode(array('datos' => $resultado));
    } else {
        // Si la liga no existe
        echo json_encode(array('no_existe_liga' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>