<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la actualización de una categoría en una base de datos.
 */

/**
 * @mainpage Documentación de Actualización de Categoría en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la actualización de una categoría en una base de datos. 
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
 * Actualiza una categoría en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $nombre El nombre de la categoría a actualizar.
    * @param int $num_equipos El número de equipos en la categoría.
    * @param int $id_categoria El ID de la categoría a actualizar.
    */
    $nombre = isset($_POST['categoria']) ? trim($_POST['categoria']) : "";
    $num_equipos = isset($_POST['num_equipos']) ? trim($_POST['num_equipos']) : "";
    $id_categoria = isset($_POST['id_categoria']) ? intval(trim($_POST['id_categoria'])) : 0;

    // Crea una instancia de la clase categoria. 
    $objeto_categoria = new categoria();

    // Verifica si existe ese ID de categoría.
    $id_categoria = $objeto_categoria->verificar_ID($id_categoria, $conexion);
    if ($id_categoria > 0) {

        // Verifica que los datos no estén vacíos.
        if (!empty($nombre) && !empty($num_equipos)) {

            // Actualizar la nueva categoría en la base de datos.
            $resultado = $objeto_categoria->actualizar_categoria($nombre, $num_equipos, $id_categoria, $conexion);
            
            // Verifica que se actualizo la categoría.
            if ($resultado) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('noInserto' => true));
            }
        } else {
            // Al menos una de las variables está vacía.
            echo json_encode(array('noHayDatos' => true));    
        }
    } else {
        // Si la categoría no existe.
        echo json_encode(array('no_existe_categoria' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>