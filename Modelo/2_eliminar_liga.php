<?php
/**
 * @file
 * Este archivo contiene un script PHP para eliminar una liga en una base de datos.
 */

/**
 * @mainpage Documentación de Desactivación de Liga
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la eliminación de una liga en una base de datos.
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
 * Elimina una liga en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_liga El ID de la liga que se desea eliminar. 
    */
    $id_liga = isset($_POST['id_liga']) ? intval(trim($_POST['id_liga'])) : 0;

    // Crea una instancia de la clase liga.
    $objeto_liga = new liga();

    // Verifica si existe ese ID  de liga.
    $id_liga = $objeto_liga->verificar_ID($id_liga, $conexion);
    if ($id_liga > 0) {

        // Consulta para eliminar la liga.
        $resultado = $objeto_liga->eliminar_liga($id_liga, $conexion);

        // Verifica si se eliminó la liga.
        if ($resultado) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('noInserto' => true));
        }                    
    } else {
        // Al menos una de las variables está vacía.
        echo json_encode(array('no_existe_liga' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>