<?php
/**
 * @file
 * Este archivo contiene un script PHP para mostrar la liga de un presidente en la base de datos.
 */

/**
 * @mainpage Documentación de Mostrar Liga de Presidente
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la consulta de liga de un presidente en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_liga.php');
include('clases/clase_usuario.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Muestra las liga de un presidente en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $correo_admin El correo del administrador de la liga.
    */
    $correo_admin = isset($_POST['correo_admin']) ? trim($_POST['correo_admin']) : "";

    // Crea instancias de las clases a ser usadas.
    $objeto_usuario = new usuario();
    $objeto_liga = new liga();

    // Verifica si existe ese correo.
    $verificar = $objeto_usuario->verificar_usuario($correo_admin, $conexion);
    if (!empty($verificar)){

        // Verifica si el correo pertenece a un presidente.
        if ($verificar['tipo_usuario'] == "presidente") {

            // Obtiene los datos de la liga del correo ingresado.
            $resultado = $objeto_liga->mostrar_liga_presidente($correo_admin, $conexion);

            //Devuelve la información detallada de la liga en formato JSON.
            echo json_encode(array('datos' => $resultado));
        } else {
            // Si el usuario no es presidente.
            echo json_encode(array('no_es_presidente' => true));
        }
    } else {
        // Si el usuario no existe.
        echo json_encode(array('no_existe_usuario' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>