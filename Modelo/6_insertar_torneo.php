<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción de torneos en una base de datos.
 */

/**
 * @mainpage Documentación de Manejar Inserción de Torneos en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción de torneos en una base de datos.
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

// Incluye las clases y archivos necesarios.
include('clases/clase_torneo.php');
include('clases/clase_categoria.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

/**
 * Maneja la inserción de torneos.
 */
function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $etapa El nombre de la etapa del torneo.
    * @param string $fecha_inicio La fecha de inicio del torneo en formato 'YYYY-MM-DD'.
    * @param string $fecha_fin La fecha de fin del torneo en formato 'YYYY-MM-DD'.
    * @param string $canchas Los nombres de las canchas separadas por como ejemplo (cancha1,cancha2).
    * @param string $grupo Los id de los torneos para cada grupo separados por coma ejemplo (1,2) es opcional si y solo si la etapa es de grupos.
    * @param int $num_clasificados El número de equipos que clasifican en cada etapa.
    * @param int $id_categoria El ID de la categoría a la que pertenece el torneo.
    */
    $etapa = isset($_POST['etapa']) ? trim($_POST['etapa']) : "";
    $fecha_inicio = isset($_POST['fecha_inicio']) ? trim($_POST['fecha_inicio']) : "";
    $fecha_fin = isset($_POST['fecha_fin']) ? trim($_POST['fecha_fin']) : "";
    $canchas = isset($_POST['canchas']) ? trim($_POST['canchas']) : "";
    $grupo = isset($_POST['grupo']) ? trim($_POST['grupo']) : "";
    $num_clasificados = isset($_POST['num_clasificados']) ? trim($_POST['num_clasificados']) : "";
    $id_categoria = isset($_POST['id_categoria']) ? intval(trim($_POST['id_categoria'])) : 0;

    // Crea instancias de las clases a ser usadas.
    $objeto_torneo = new torneo();
    $objeto_categoria = new categoria();

    // Verifica si existe ese ID de categoria.
    $id_categoria = $objeto_categoria->verificar_ID($id_categoria, $conexion);
    if ($id_categoria > 0) {
        
        // Verifica que los datos no estén vacíos.
        if (!empty($etapa) && !empty($fecha_inicio) && !empty($fecha_fin) && !empty($canchas) && !empty($num_clasificados)) {
            
            // Verifica que los datos no estén vacíos si es etapa de grupos.
            if(!empty($grupo)){
                // Inserta los nuevos torneos en la base de datos.
                for($i=0;$i<$grupo;$i++){
                    $resultado[$i] = $objeto_torneo->insertar_torneo($etapa, $fecha_inicio, $fecha_fin, "GRUPO ".$i+1, $num_clasificados[$i], $id_categoria, $canchas, $conexion);
                }
                
                // Devuelve los id de los torneos insertados.
                if (!empty($resultado)) {
                    echo json_encode(array('success' => true,'datos' => $resultado));
                } else {
                    echo json_encode(array('noInserto' => true));
                }            
            }
            else{

                // Inserta el nuevo torneo en la base de datos.
                $resultado = $objeto_torneo->insertar_torneo($etapa, $fecha_inicio, $fecha_fin, $grupo, $num_clasificados, $id_categoria, $canchas, $conexion);
                
                // Verificar que se inserto el torneo.
                if (!empty($resultado)) {
                    echo json_encode(array('success' => true));
                } else {
                    echo json_encode(array('noInserto' => true));
                }
            }
        } else {
            // Al menos una de las variables está vacía.
            echo json_encode(array('noHayDatos' => true));
        }
    } else {
        // Si la categoría no existe.
        echo json_encode(array('no_existe_categoria' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>