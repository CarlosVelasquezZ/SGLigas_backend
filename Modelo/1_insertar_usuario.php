<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción de usuarios en una base de datos.
 */

/**
 * @mainpage Documentación de Inserción de Usuarios en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción de usuarios en una base de datos. 
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
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
 * Inserta un nuevo usuario en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $correo_usuario El correo del usuario.
    * @param string $nombre El nombre del usuario.
    * @param string $contraseña La contraseña del usuario.
    * @param string $tipo El tipo de usuario (por ejemplo, 'presidente' o 'hincha').
    */
    $correo_usuario = isset($_POST['correo']) ? trim($_POST['correo']) : "";
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
    $contrasena = isset($_POST['password']) ? trim($_POST['password']) : "";
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : "";

    // Crea una instancia de la clase usuario.
    $objeto_usuario = new usuario();

    // Verifica que los datos no estén vacíos.
    if (!empty($correo_usuario) && !empty($nombre) && !empty($contrasena) && !empty($tipo)) {
        
        // Consulta para verificar que ese usuario no existe.
        $existe_usuario = $objeto_usuario->verificar_usuario($correo_usuario, $conexion);
        if (empty($existe_usuario)) {
            
            // Consulta para insertar usuario.
            $resultado = $objeto_usuario->insertar_usuario($correo_usuario, $nombre, $contrasena, $tipo, $conexion);
            
            // Verifica que se insertó el usuario.
            if ($resultado) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('noInserto' => true));
            }
        } else {
            // El usuario ya existe y no se puede insertar.
            echo json_encode(array('success' => false)); 
        }
    } else {
        // Al menos una de las variables está vacía.
        echo json_encode(array('noHayDatos' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>