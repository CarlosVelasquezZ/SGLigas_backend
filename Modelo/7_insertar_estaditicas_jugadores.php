<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción de estadísticas de jugadores en partidos.
 */
/**
 * @mainpage Documentación de Inserción de Estadísticas de Jugadores
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción de estadísticas de jugadores en partidos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_partido.php');
include('clases/clase_torneo.php');
include('clases/clase_jugador.php');
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
 * Maneja la inserción de estadísticas de jugadores en partidos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $id_partido El ID del partido que se quiere insertar las estaditicas debe estar en estado jugado.
    * @param int $id_torneo El ID del torneo al que pertenece ese partido
    * @param array $jugadores Una matriz con un array de las estaditicas de jugadores. Por ejemplo
    * [[1234567890,1,0,1,0,1],[1234567891,1,0,1,0,1]]
    * El orden de cada array de jugador debe ser [CI_jugador,rojas,amarillas,goles,goles_recibidos,autogoles]
    */
    $id_torneo = isset($_POST['id_torneo']) ? intval(trim($_POST['id_torneo'])) : 0;
    $id_partido = isset($_POST['id_partido']) ? intval(trim($_POST['id_partido'])) : 0;
    $jugadores = isset($_POST['jugadores']) ? $_POST['jugadores'] : "";

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_partido = new partido();
    $objeto_jugador= new jugador();
    $objeto_equipo= new equipo();

    // Verifica si existe ese torneo.
    $verificar = $objeto_torneo->verificar_ID($id_torneo, $conexion);
    if(!empty($verificar)){
        // Verificar si ya existen partidos creados en ese torneo.
        $existen_partidos = $objeto_partido->mostrar_partidos($id_torneo,$conexion);
        if(!empty($existen_partidos)){
            
            // Verifica si existe ese partido.
            $verificar = $objeto_partido->verificar_ID($id_partido, $conexion);
            if(!empty($verificar)){

                // Obtener el estado del partido.
                $estado_BD=$verificar['estado'];

                // Verificar el estado del partido.
                switch ($estado_BD) {
                    case "jugado":

                        // Obtener las estadisticas de jugadores.
                        $respuesta=$objeto_jugador->extraer_partidos_jugados($id_torneo,$id_partido,$conexion);
                        if($respuesta){
                            echo json_encode(array('existen_registros' => true));
                        } else {

                            // Verificar que los datos no esten vacios.
                            if(!empty($jugadores)){
                                // Consulta para insertar estadisticas de jugadores.
                                $respuesta=$objeto_jugador->insertar_actualizar_estadisticas_jugador($jugadores,$id_torneo,$id_partido,$conexion);
                                
                                // Verificar que se inserto las estadisticas de jugadores.
                                if($respuesta){
                                    echo json_encode(array('success' => true));
                                } else {
                                    echo json_encode(array('noInserto' => true));
                                }
                            } else {
                                // Al menos una de las variables está vacía.
                                echo json_encode(array('noHayDatos' => true));
                            }
                        }
                        break;
                    case "porjugar":
                        echo json_encode(array('partido_por_jugar' => true));
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
        } else {
            // Si no existe partidos.
            echo json_encode(array('no_existen_partidos' => true));
        }
    } else {
        // Si el torneo no existe.
        echo json_encode(array('no_existe_torneo' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>