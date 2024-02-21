<?php
/**
 * @file
 * Este archivo contiene un script PHP para mostrar equipos de una categoría en una base de datos.
 */

/**
 * @mainpage Documentación de Mostrar Equipos de una Categoría en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la obtención de equipos de una categoría en una base de datos. 
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_equipo.php');
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
 * Obtiene la información de los equipos de una categoría.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_categoria El ID de la categoría para mostrar los equipos.
    */
    $id_categoria = isset($_POST['id_categoria']) ? intval(trim($_POST['id_categoria'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_categoria = new categoria();
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de categoria.
    $verificar=$objeto_categoria->verificar_ID($id_categoria,$conexion);
    if ($id_categoria>0) {

        // Obtiene los resultados para mostrar los equipos de una categoria.
        $resultado = $objeto_equipo->mostrar_equipos_categoria($id_categoria, $conexion);

        // Devuelve los equipos de una categoria en formato JSON, si no hay datos retorna array vacio. 
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