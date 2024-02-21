<?php
/**
 * @file
 * Este archivo contiene un script PHP para manejar la inserción de un equipo en una base de datos.
 */

/**
 * @mainpage Documentación de Inserción de un Equipo en una Base de Datos
 *
 * @section intro_sec Introducción
 * Este script PHP se utiliza para manejar la inserción de un equipo en una base de datos. 
 * Utiliza clases y archivos necesarios para conectar y manipular la base de datos.
 */

 // Incluye las clases y archivos necesarios.
include('clases/clase_equipo.php');
include('clases/clase_categoria.php');
include('clases/clase_liga.php');
include('conexion.php');

/**
 * Obtiene la conexión a la base de datos.
 * @return mysqli La conexión a la base de datos.
 */
function obtener_conexion() {
    return conexion_DB();
}

function main() {
    // Obtiene la conexión a la base de datos.
    $conexion = obtener_conexion();

    /**
    * Obtiene los datos ingresados por parte del cliente.
    *
    * @param string $nombre_equipo El nombre del equipo a insertar.
    * @param string $fecha_fundacion La fecha de fundación del equipo en formato 'YYYY-MM-DD'.
    * @param string $presidente El nombre del presidente del equipo.
    * @param string $colores Los colores del equipo.
    * @param string $escudo La URL del escudo del equipo.
    * @param int $id_categoria El ID de la categoría a la que pertenece el equipo.
    * @param int $id_liga El ID de la liga a la que pertenece el equipo.
    */
    $nombre_equipo=isset($_POST['nombre_equipo']) ? trim($_POST['nombre_equipo']) : "";
    $fecha_fundacion=isset($_POST['fecha_fundacion']) ? trim($_POST['fecha_fundacion']) : "";
    $presidente=isset($_POST['presidente']) ? trim($_POST['presidente']) : "";
    $colores=isset($_POST['color']) ? trim($_POST['color']) : "";
    $escudo=isset($_POST['escudo']) ? trim($_POST['escudo']) : "";
    $id_categoria=isset($_POST['id_categoria']) ? intval(trim($_POST['id_categoria'])) : 0;
    $id_liga = isset($_POST['id_liga']) ? intval(trim($_POST['id_liga'])) : 0;
    $estado="activo";

    // Crea instancias de las clases a ser usadas.
    $objeto_categoria = new categoria();
    $objeto_liga = new liga();
    $objeto_equipo = new equipo();

    // Verifica si existe ese ID de liga.
    $verificar=$objeto_liga->verificar_ID($id_liga,$conexion);
    if($verificar){

        //Obtiene las categorias de asociadas al ID de liga ingresado.
        $categoria=$objeto_categoria->mostrar_categorias($id_liga,$conexion);
    
        //Verifica que exista el ID de categoria ingresado en la liga.
        $verificar=false;
        foreach ($categoria as $elemento) {
            if ($elemento["id_categoria"] === $id_categoria) {
                $verificar = true;
                break; // Terminar el bucle cuando se encuentra el resultado.
            }
        }
        if($verificar){
            // Verificar que los datos no esten vacios.
            if (!empty($nombre_equipo) && !empty($fecha_fundacion) && !empty($presidente) && !empty($colores) && !empty($escudo)) {
                
                // Verificar si existe el nombre de equipo.
                $verificar=$objeto_equipo->verificar_nombre_equipo($nombre_equipo,$id_liga,$conexion);
                if(!$verificar){

                    // Consulta para insertar equipo.
                    $resultado=$objeto_equipo->insertar_equipo($nombre_equipo,$fecha_fundacion,$presidente,$colores,$escudo,$estado,$id_categoria,$conexion);

                    // Verificar que se inserto el equipo.
                    if ($resultado) {
                        echo json_encode(array('success' => true));
                    } else {
                        echo json_encode(array('noInserto' => true));
                    }
                } else {
                    echo json_encode(array('existe_nombre' => true));
                }
            }
            else {
                // Al menos una de las variables está vacía.
                echo json_encode(array('noHayDatos' => true));
            }
        } else {
            // Si la categoría no existe.
            echo json_encode(array('no_existe_categoria' => true));
        }
    } else {
        // Si la liga no existe.
        echo json_encode(array('no_existe_liga' => true));
    }

    // Cierra la conexión a la base de datos.
    mysqli_close($conexion);
}

// Ejecuta la función principal.
main();
?>