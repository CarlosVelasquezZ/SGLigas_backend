<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la autenticación de usuarios en el sistema basado 
 * en los datos ingresados previamente en una base de datos.
 */

/**
 * @mainpage Documentación de Autenticación de Usuarios
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la autenticación de usuarios en un sistema basado en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos, así como la librería firebase/php-jwt para generar y manejar tokens JWT.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_usuario.php');
include("conexion.php");
require 'vendor/autoload.php'; // La librería firebase/php-jwt
use \Firebase\JWT\JWT; // Clase JWT

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Realiza la autenticación del usuario y maneja el inicio de sesión.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $correo_usuario El correo del usuario.
    * @param string $contraseña La contraseña del usuario.
    */
    $correo_usuario = isset($_POST['correo']) ? trim($_POST['correo']) : "";
    $contrasena = isset($_POST['password']) ? trim($_POST['password']) : "";

    // Crea una instancia de la clase usuario.
    $objeto_usuario = new usuario();
    
    // Verifica que los datos no estén vacíos.
    if (!empty($correo_usuario) && !empty($contrasena)) {
        
        // Consulta para verificar si el usuario existe.
        $existe_usuario = $objeto_usuario->verificar_usuario($correo_usuario, $conexion);
        if (!empty($existe_usuario)) {

            // Obtiene la contraseña registrada en la BD.
            $contrasena_encriptada = $existe_usuario['contraseña'];

            // Comprueba si la contraseña es correcta.
            if (password_verify($contrasena, $contrasena_encriptada)) {
                    
                    // Inicio de sesión exitoso y verificar si el usuario es presidente.
                    $userType = $existe_usuario['tipo_usuario'];
                    $correoUsuario = $existe_usuario['correo'];
                    // Generar el token JWT.
                    $key = 'admin_web1019';
                    $payload = array(
                        "correo" => $correoUsuario,
                        "tipo_usuario" => $userType
                    );
                    $token = JWT::encode($payload, $key, 'HS256');
                    $response = array(
                        'token' => $token,
                        'userData' => array(
                            'correo' => $correoUsuario,
                            'tipo_usuario' => $userType
                        )
                    );
                    echo json_encode($response);
            } else {
                // Contraseña incorrecta.
                echo json_encode(array('success' => false));
            }
        } else {
            // Usuario no encontrado.
            echo json_encode(array('correo' => false));
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