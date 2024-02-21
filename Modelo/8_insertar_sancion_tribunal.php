<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción del tribunal de sanciones en partidos.
 */

/**
 * @mainpage Documentación de Inserción del tribunal de Sanciones
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción del tribunal de sanciones en partidos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_sanciones.php');
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
 * Maneja la inserción del tribunal de sanciones en partidos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido al que se asocia la sanción.
    * @param string $tribunal_local El informe del tribunal del equipo local sobre la sanción.
    * @param string $tribunal_visitante El informe del tribunal del equipo visitante sobre la sanción.
    * @param string $responsables Los nombres de los responsables de aceptar el tribunal, debe ser separado
    * por comas ejemplo (Responsable 1,Responsable 2)
    */
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;
    $tribunal_local = isset($_POST['informe_local']) ? trim($_POST['informe_local']) : "";
    $tribunal_visitante = isset($_POST['informe_visitante']) ? trim($_POST['informe_visitante']) : "";
    $responsables = isset($_POST['responsables']) ? trim($_POST['responsables']) : "";

    // Crea instancias de las clases a ser usadas.
    $objeto_sanciones = new sanciones();
    $objeto_partido = new partido();

    // Verifica si existe ese ID de partido.
    $verificar = $objeto_partido->verificar_ID($id_partido, $conexion);
    if (!empty($verificar)){

        // Obtener el estado del partido.
        $estado=$verificar['estado'];

        // Verificar el esatdo del partido.
        if($estado==='jugado'){

            // Consulta para verificar si existe una sancion registrada.
            $verificar=$objeto_sanciones->verificar_sancion($id_partido, $conexion);
            if($verificar>0){

                // Verificar que los datos no esten vacios.
                if(!empty($tribunal_local) & !empty($tribunal_visitante) & !empty($responsables)){ 
                    
                    // Inseratar sanciones en la base de datos.
                    $respuesta=$objeto_sanciones->insertar_tribunal($id_partido, $tribunal_local, $tribunal_visitante, $responsables, $conexion);
                    
                    // Verificar que se inserto la sanción.
                    if ($respuesta) {
                        echo json_encode(array('success' => true));
                    } else {
                        echo json_encode(array('noInserto' => true));
                    }
                } else {
                    // Al menos una de las variables está vacía.
                    echo json_encode(array('noHayDatos' => true));
                }
            } else {
                // Si la sancion no existe.
                echo json_encode(array('no_existe_sancion' => true));
            }
        } else {
            // Si el partido no fue jugado.
            echo json_encode(array('partido_no_jugado' => true));
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