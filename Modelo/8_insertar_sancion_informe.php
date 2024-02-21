<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción del informe de sanciones en partidos.
 */

/**
 * @mainpage Documentación de Inserción del informe de Sanciones
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción del informe de sanciones en partidos.
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
 * Maneja la inserción del informe de sanciones en partidos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido al que se asocia la sanción.
    * @param string $informe_local El informe del equipo local sobre la sanción puede no exitir.
    * @param string $informe_visitante El informe del equipo visitante sobre la sanción puede no exitir.
    * @param string $arbitro El nombre del árbitro que sancionó.
    */
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;
    $informe_local = isset($_POST['informe_local']) ? trim($_POST['informe_local']) : "";
    $informe_visitante = isset($_POST['informe_visitante']) ? trim($_POST['informe_visitante']) : "";
    $arbitro = isset($_POST['arbitro']) ? trim($_POST['arbitro']) : "";

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
            if($verificar==0){

                // Verificar que los datos no esten vacios.
                if(!empty($arbitro)){ 

                    // Inseratar sanciones en la base de datos.
                    $respuesta=$objeto_sanciones->insertar_sancion($id_partido, $informe_local, $informe_visitante, $arbitro, $conexion);
                    
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
                // Si existe sanción.
                echo json_encode(array('existe_sancion' => true));
            }
        } else {
            // Si el partido no esta en estado jugado.
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