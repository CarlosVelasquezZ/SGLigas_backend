<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción de alineaciones en partidos.
 */

/**
 * @mainpage Documentación de Inserción de Alineaciones
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción de alineaciones en partidos.
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
 * Maneja la inserción de alineaciones en partidos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido que se quiere insertar la alineacion, el partido debe estar en programa.
    * @param json $alineacion Un json con la informacion de la alineación de los equipos local y visitante.
    */
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;
    $alineacion = isset($_POST['alineacion']) ? $_POST['alineacion'] : "";

    // Crea una instancia de la clase partido.
    $objeto_partido = new partido();

    // Verifica si existe ese partido.
    $verificar = $objeto_partido->verificar_ID($id_partido, $conexion);
    if(!empty($verificar)){

        // Obtener el estado del partido.
        $estado_BD=$verificar['estado'];

        // Verificar el estado del partido.
        switch ($estado_BD) {
            case "porjugar":
                // Verificar que los datos no esten vacios.
                if(!empty($alineacion)){
                    
                    // Consulta para verificar si existen una alineación registrada para ese partido.
                    $verfificar_id_partido=$objeto_partido->verificar_existencia_alineacion($id_partido, $conexion);
                    if(!empty($verfificar_id_partido)){
                        // Si existe registro de alineación.
                        echo json_encode(array('existe_alienacion' => true));
                    } else {

                        // Consulta para insertar alineación.
                        $respuesta=$objeto_partido->insertar_alineacion($alineacion,$id_partido,$conexion);
                        
                        // Verificar que se inserto alineación.
                        if($respuesta){
                            echo json_encode(array('success' => true));
                        } else {
                            echo json_encode(array('noInserto' => true));
                        }
                    }
                } else {
                    // Al menos una de las variables está vacía.
                    echo json_encode(array('noHayDatos' => true));
                }
                break;
            case "jugado":
                echo json_encode(array('partido_jugado' => true));                
                break;
            case "programar":
                echo json_encode(array('partido_no_programado' => true));
                break;
            case "posponer":
                echo json_encode(array('partido_pospuesto' => true));
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