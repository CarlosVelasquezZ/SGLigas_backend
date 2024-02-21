<?php
/**
 * @file
 * Este archivo contiene un script PHP para mostrar información detallada de un equipo en una base de datos.
 */

/**
 * @mainpage Documentación de Mostrar Información Detallada de un Equipo en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de información detallada de un equipo en una base de datos. 
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
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
 * Obtiene información detallada de un equipo.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_equipo El ID del equipo para mostrar.
    */
    $id_equipo = isset($_POST['id_equipo']) ? intval(trim($_POST['id_equipo'])) : 0;

    // Crea una instancia de la clase equipo.
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de equipo.
    $verificar=$objeto_equipo->verificar_ID($id_equipo,$conexion);
    if ($id_equipo > 0) {

        // Obtiene los resultados para mostrar los equipos.
        $resultado = $objeto_equipo->mostrar_equipo($id_equipo, $conexion);

        // Devuelve la informacion detallada del equipo en formato JSON, si no hay datos retorna array vacio.
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