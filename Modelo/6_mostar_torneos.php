<?php
/**
 * @file
 * Este archivo contiene un script PHP para mostrar los torneos de una categoría de una base de datos.
 */

/**
 * @mainpage Documentación de Mostrar Torneos por Categoría
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para mostrar torneos de una categoría en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_torneo.php');
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
 * Muestra los torneos de una categoria.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_categoria El ID de la categoría para mostrar los torneo.
    */
    $id_categoria = isset($_POST['id_categoria']) ? intval(trim($_POST['id_categoria'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_categoria = new categoria();

    // Verifica si existe ese ID de categoría.
    $id_categoria = $objeto_categoria->verificar_ID($id_categoria, $conexion);
    if ($id_categoria > 0) {

        // Obtiene los resultados para mostrar los torneos de una categoría.
        $resultado = $objeto_torneo->mostrar_torneos($id_categoria, $conexion);

        // Devuelve los torneos de una categoria en formato JSON, si no hay datos retorna array vacio.
        echo json_encode(array('datos' => $resultado));
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