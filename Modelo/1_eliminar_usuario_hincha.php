<?php
/**
 * @file
 * Este archivo contiene un script PHP para eliminar un usuario de tipo hincha en una base de datos.
 */

/**
 * @mainpage Documentación de Eliminación de Usuarios Tipo Hincha
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la eliminación de un usuario de tipo hincha en una base de datos.
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
 * Elimina un usuario de tipo hincha registrado en la base de datos.
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

            //Verifica el tipo de usuario que es.
            if($existe_usuario['tipo_usuario'] != 'presidente'){
                
                // Consulta para eliminar usuario.
                $resultado = $objeto_usuario->eliminar_usuario_hincha($correo_usuario, $conexion);

                // Verifica que se elimino el usuario.
                if ($resultado) {
                    echo json_encode(array('success' => true));
                } else {
                    echo json_encode(array('noInserto' => true));
                }
            } else {
                echo json_encode(array('es_presidente' => true));    
            }
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
?>