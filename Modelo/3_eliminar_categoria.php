<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la eliminación de una categoría en una base de datos.
 */

/**
 * @mainpage Documentación de Inserción de Categorías en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la eliminación de categorías en una base de datos. 
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
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
 * Elimina una categoría en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_categoria El ID de la categoría a eliminar.
    */
    $id_categoria = isset($_POST['id_categoria']) ? intval(trim($_POST['id_categoria'])) : 0;

    // Crea una instancia de la clase categoria. 
    $objeto_categoria = new categoria();

    // Verifica si existe ese ID de liga.
    $id_categoria = $objeto_categoria->verificar_ID($id_categoria, $conexion);
    if ($id_categoria>0) {

        // Consulta para eliminar categoría.
        $resultado = $objeto_categoria->eliminar_categoria($id_categoria, $conexion);

        // Verifica si se desactivó la categoría.
        if ($resultado) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('noInserto' => true));
        }                    
    } else {
        // Si el id de categoría no existe
        echo json_encode(array('no_existe_categoria' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>