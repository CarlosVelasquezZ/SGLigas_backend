<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la actualización de un partido en la base de datos.
 */

/**
 * @mainpage Documentación de Actualización de Partidos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la actualización de un partido en la base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_partido.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Maneja la actualización de un partido en la base de datos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido que se quiere programa, el partido debe estar en programa o pospuesto.
    * @param array $partido Un array con los datos del partido. Por ejemplo
    * [YYYY-MM-DD,hh:mm,cancha1,equipo_escogido,""],el dato de veedor es opcional
    * El orden de cada array de partido debe ser [fecha_partido,hora_partido,cancha,vocal,veedor]
    */
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;
    $partido = isset($_POST['partido']) ? $_POST['partido'] : "";
    $estado = "porjugar";

    // Crea una instancia de la clase partido.
    $objeto_partido = new partido();
    
    // Verifica si existe ese torneo.
    $verificar = $objeto_partido->verificar_ID($id_partido, $conexion);
    if(!empty($verificar)){

        // Obtener el estado del partido.
        $estado_BD=$verificar['estado'];

        // Verificar el estado del partido.
        switch ($estado_BD) {
            case "programar":
            case "posponer":
                // Verificar que los datos no esten vacios.
                if(!empty($partido)){ 

                    // Actualizar partido en la base de datos.
                    $respuesta=$objeto_partido->modificar_partido($id_partido, $partido, $estado, $conexion);
                    
                    // Verificar que se actualizo el partido.
                    if ($respuesta) {
                        echo json_encode(array('success' => true));
                    } else {
                        echo json_encode(array('noInserto' => true));
                    }
                } else {
                    // Al menos una de las variables está vacía.
                    echo json_encode(array('noHayDatos' => true));
                }
                break;
            case "porjugar":
                echo json_encode(array('partido_programado' => true));
                break;
            case "jugado":
                echo json_encode(array('partido_jugado' => true));
                break;
            default:
                echo json_encode(array('error' => true));
        }
    } else {
        // Si el partido no existe.
        echo json_encode(array('no_existe_partido' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>