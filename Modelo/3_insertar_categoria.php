<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción de categorías en una base de datos.
 */

/**
 * @mainpage Documentación de Inserción de Categorías en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción de categorías en una base de datos. 
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_categoria.php');
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
 * Realiza la inserción de la categoría en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $nombre El nombre de la categoría a insertar.
    * @param int $num_equipos El número de equipos en la categoría.
    * @param int $id_liga El ID de la liga a la que se asociará la categoría.
    */
    $nombre = isset($_POST['categoria']) ? trim($_POST['categoria']) : "";
    $num_equipos = isset($_POST['num_equipos']) ? trim($_POST['num_equipos']) : "";
    $id_liga = isset($_POST['id_liga']) ? intval(trim($_POST['id_liga'])) : 0;
    $estado="activo";

    // Crea instancias de las clases a ser usadas.
    $objeto_liga = new liga();
    $objeto_categoria = new categoria();

    // Verifica si existe ese ID de liga.
    $id_liga = $objeto_liga->verificar_ID($id_liga, $conexion);
    if ($id_liga > 0) {

        // Verifica que los datos no estén vacíos.
        if (!empty($nombre) && !empty($num_equipos)) {

            // Verifica que no exista una categoría con el mismo nombre en la liga.
            $verificar = $objeto_categoria->verificar_nombre_categoria($nombre, $id_liga, $conexion);
            if (empty($verificar)) {

                // Inserta la nueva categoría en la base de datos.
                $resultado = $objeto_categoria->insertar_categoria($nombre, $num_equipos, $estado, $id_liga, $conexion);
                
                // Verifica que se insertó la categoría.
                if ($resultado) {
                    echo json_encode(array('success' => true));
                } else {
                    echo json_encode(array('noInserto' => true));
                }
            } else {
                // Si existe una categoría con el mismo nombre.
                echo json_encode(array('existe_nombre' => true));
            }
        } else {
            // Al menos una de las variables está vacía.
            echo json_encode(array('noHayDatos' => true));
        }
    } else {
        // Si la liga no existe.
        echo json_encode(array('no_existe_liga' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>