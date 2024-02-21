<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la actualización de un equipo en una base de datos.
 */

/**
 * @mainpage Documentación de Actualización de Equipo en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la actualización de un equipo en una base de datos. 
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
 * Actualiza un equipo en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $nombre_equipo El nombre del equipo a actualizar.
    * @param string $presidente El nombre del presidente del equipo.
    * @param string $escudo La URL del escudo del equipo.
    * @param int $id_equipo El ID del equipo que se desea mostrar.
    */
    $nombre_equipo=isset($_POST['nombre_equipo']) ? trim($_POST['nombre_equipo']) : "";
    $presidente=isset($_POST['presidente']) ? trim($_POST['presidente']) : "";
    $escudo=isset($_POST['escudo']) ? trim($_POST['escudo']) : "";
    $id_equipo = isset($_POST['id_equipo']) ? intval(trim($_POST['id_equipo'])) : 0;

    // Crea una instancia de la clase equipo. 
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de equipo.
    $id_equipo = $objeto_equipo->verificar_ID($id_equipo, $conexion);
    if ($id_equipo > 0) {

        // Verifica que los datos no estén vacíos.
        if (!empty($nombre_equipo) && !empty($presidente) && !empty($escudo)) {

            // Actualizar el equipo en la base de datos.
            $resultado = $objeto_equipo->actualizar_equipo($nombre_equipo, $presidente, $escudo, $id_equipo, $conexion);
            
            // Verifica que se actualizo los datos del equipo.
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
        // Si el equipo no existe.
        echo json_encode(array('no_existe_equipo' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>