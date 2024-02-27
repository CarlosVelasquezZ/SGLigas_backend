<?php
/**
 * @file
 * Este archivo contiene un script PHP para recuperar la contraseña de usuarios en una base de datos.
 */

/**
 * @mainpage Documentación de Recuperación de Contraseña de Usuario
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la recuperación de la contraseña de un usuario en una base de datos. 
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
 * Recuperar la contraseña de un usuario registrado en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $correo_usuario El correo del usuario.
    */
    $correo_usuario = isset($_POST['correo']) ? trim($_POST['correo']) : "";

    // Crea una instancia de la clase usuario.
    $objeto_usuario = new usuario();

    // Verifica que los datos no estén vacíos.
    if (!empty($correo_usuario)) {

        // Consulta para verificar que ese usuario existe.
        $existe_usuario = $objeto_usuario->verificar_usuario($correo_usuario, $conexion);
        if (!empty($existe_usuario)) {

            // Obtener el nombre del usuario
            $nombreUsuario = $existe_usuario['nombre'];

            // Generar un código de verificación aleatorio
            $codigoVerificacion = generarCodigoVerificacion();
            
            
            // Envío de correo electrónico con el código de verificación
            enviarCorreoRecuperacionContraseña($correo_usuario, $nombreUsuario, $codigoVerificacion);

            // Crear un arreglo con los datos a devolver en la respuesta JSON
            $response = array(
                'success' => true,
                'message' => 'Se ha enviado un correo con instrucciones para recuperar tu contraseña.',
                'correoUsuario' => $correo_usuario,
                'codigoVerificacion' => $codigoVerificacion
            );
        } else {
             // El usuario no existe y no se puede modificar.
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

// Función para generar un código de verificación
function generarCodigoVerificacion() {
    // Ejemplo básico: Generar una cadena de caracteres aleatoria de longitud 6
    $caracteres = '0123456789';
    $codigoVerificacion = '';
    for ($i = 0; $i < 6; $i++) {
        $codigoVerificacion .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    
    return $codigoVerificacion;
}

// Función para enviar el correo de recuperación de contraseña
function enviarCorreoRecuperacionContraseña($correoDestino, $nombreUsuario, $codigoVerificacion) {
    $asunto = 'Recuperación de contraseña';
    $mensaje = "Estimado/a $nombreUsuario,\r\n\r\n";
    $mensaje .= "Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Por favor, utiliza el siguiente código de verificación: $codigoVerificacion\r\n\r\n";
    $mensaje .= "Si no has solicitado esta acción, te recomendamos que tomes las medidas necesarias para proteger tu cuenta, como cambiar tu contraseña y habilitar la autenticación de dos factores.\r\n\r\n";
    $mensaje .= "Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactarnos. Estamos aquí para ayudarte.\r\n\r\n";
    $mensaje .= "Atentamente:\r\n";
    $mensaje .= "SGLigas\r\n";
    $cabeceras = 'From: tu_correo@example.com' . "\r\n" .
                 'Reply-To: tu_correo@example.com' . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();
    
                 
    mail($correoDestino, $asunto, $mensaje, $cabeceras);
}
?>
