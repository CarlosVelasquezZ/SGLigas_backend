<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la actualizacion de jugadores en una base de datos.
 */

/**
 * @mainpage Documentación de Manejar actualizacion de Jugadores en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la actualizacion de jugadores en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_equipo.php');
include('clases/clase_jugador.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Ejecuta la función principal para actualizar un nuevo jugador.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param int $CI El número de cédula del jugador.
    * @param string $posicion La posición del jugador.
    * @param string $foto La URL de la foto del jugador.
    * @param int $estatura La estatura del jugador en cm ejemplo (160).
    * @param int $num_camiseta El numero de camiseta del jugador.
    * @param int $id_equipo El ID del equipo al que pertenece el jugador.
    */
    $CI = isset($_POST['CI']) ? intval(trim($_POST['CI'])) : 0;
    $posicion = isset($_POST['posicion']) ? trim($_POST['posicion']) : "";
    $foto = isset($_POST['foto']) ? trim($_POST['foto']) : "";
    $estatura = isset($_POST['estatura']) ? intval(trim($_POST['estatura'])) : 0;
    $num_camiseta = isset($_POST['num_camiseta']) ? intval(trim($_POST['num_camiseta'])) : 0;
    $id_equipo = isset($_POST['id_equipo']) ? intval(trim($_POST['id_equipo'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_equipo = new equipo();
    $objeto_jugador = new jugador();

    // Verifica si existe ese ID de equipo.
    $id_equipo = $objeto_equipo->verificar_ID($id_equipo, $conexion);
    if ($id_equipo > 0) {

        // Verificar si la CI de ese jugador no existe.
        $verificar=$objeto_jugador->verificar_CI($CI,$conexion);
        if(!empty($verificar)){

            // Verifica que los datos no estén vacíos.
            if (!empty($posicion) && !empty($foto) && !empty($estatura) && !empty($num_camiseta)) {
                
                // Consulta para verificar el numero de camiseta.
                $verificar=$objeto_jugador->verificar_camiseta($id_equipo, $num_camiseta, $conexion);
                if($verificar == 0){

                    // Actualizar el nuevo jugador en la base de datos.
                    $resultado = $objeto_jugador->actualizar_jugador($CI, $posicion, $foto, $estatura, $num_camiseta, $id_equipo, $conexion);
                    
                    // Verificar que se inserto el jugador.
                    if ($resultado) {
                        echo json_encode(array('success' => true));
                    } else {
                        echo json_encode(array('noInserto' => true));
                    }
                } else {
                    // Existe el numero de camiseta.
                    echo json_encode(array('exite_numero_camiseta' => true));
                }
            } else {
                // Al menos una de las variables está vacía.
                echo json_encode(array('noHayDatos' => true));
            }
        } else {
            // Si existe el jugador.
            echo json_encode(array('no_existe_jugador' => true));
        }
    } else {
        // Si el equipo no existe.
        echo json_encode(array('no_existe_equipo' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();

?>