<?php
/**
 * @file
 * Este archivo contiene un script PHP para verificar el límite de equipos en una categoría en una base de datos.
 */

/**
 * @mainpage Documentación de Verificar Límite de Equipos en una Categoría en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para verificar si un torneo ha alcanzado su límite de equipos en una categoría en una base de datos.
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
 * Obtiene la información del numero de equipos en una categoría.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_categoria El ID de la categoría para validar el número de equipos.
    */
    $id_categoria=isset($_POST['id_categoria']) ? intval(trim($_POST['id_categoria'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_categoria = new categoria();
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de categoria.
    $verificar=$objeto_categoria->verificar_ID($id_categoria,$conexion);
    if ($verificar>0) {
   
        // Consultar el numero de equipos en esa categoria.
        $verificar=$objeto_equipo->verificar_num_equipos($id_categoria,$conexion);

        // Verificar que se obtuvo de la consulta.
        if($verificar){
            echo json_encode(array('limite_equipos' => true));
        } else {
            echo json_encode(array('limite_equipos' => false));
        }
    }
    else {
        // Si la categoría no existe.
        echo json_encode(array('no_existe_categoria' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>