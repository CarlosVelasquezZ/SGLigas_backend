<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la eliminación de un equipo en una base de datos.
 */

/**
 * @mainpage Documentación de Eliminacion de un equipo en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la eliminación de un equipo en una base de datos. 
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
 * Elimina un equipo en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_equipo El ID del equipo a eliminar.
    */
    $id_equipo = isset($_POST['id_equipo']) ? intval(trim($_POST['id_equipo'])) : 0;

    // Crea una instancia de la clase categoria. 
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de liga.
    $id_categoria = $objeto_equipo->verificar_ID($id_equipo, $conexion);
    if ($id_equipo>0) {

        // Consulta para eliminar categoría.
        $resultado = $objeto_equipo->eliminar_equipo($id_equipo, $conexion);

        // Verifica si se desactivó la categoría.
        if ($resultado) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('noInserto' => true));
        }                    
    } else {
        // Si el id de categoría no existe
        echo json_encode(array('no_existe_equipo' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>