<?php
class sanciones{

    /**
    * Inserta una nueva sanción en la base de datos.
    *
    * @param int $id_partido El ID del partido al que se asocia la sanción.
    * @param string $informe_local El informe del equipo local sobre la sanción.
    * @param string $informe_visitante El informe del equipo visitante sobre la sanción.
    * @param string $arbitro El nombre del árbitro que sancionó.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la inserción fue exitosa, `false` en caso contrario.
    */
    public function insertar_sancion($id_partido, $informe_local, $informe_visitante, $arbitro, $conexion) {
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO sanciones(id_partidos, informe_local, informe_visitante, nombre_arbitro) VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);

        if($stmt){
            mysqli_stmt_bind_param($stmt, "isss", $id_partido, $informe_local, $informe_visitante,$arbitro);
            $respuesta=mysqli_stmt_execute($stmt);
        }
        // Cerrar la sentencia preparada.
        mysqli_stmt_close($stmt);
        // Devolver `true` si la inserción fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
    * Inserta la información del tribunal para un partido en particular en la base de datos.
    *
    * @param int $id_partido El ID del partido asociado al tribunal.
    * @param string $tribunal_local El tribunal local.
    * @param string $tribunal_visitante El tribunal visitante.
    * @param string $responsables Los responsables de la sanción.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la inserción fue exitosa, `false` en caso contrario.
    */
    public function insertar_tribunal($id_partido, $tribunal_local, $tribunal_visitante, $responsables, $conexion) {
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE sanciones SET tribunal_local = ?, tribunal_visitante = ? responsables = ? WHERE id_partidos = ?;";
        $stmt = mysqli_prepare($conexion, $sql);

        if($stmt){
            mysqli_stmt_bind_param($stmt, "sssi", $tribunal_local, $tribunal_visitante, $responsables, $id_partido);
            $respuesta=mysqli_stmt_execute($stmt);
        }
        // Cerrar la sentencia preparada.
        mysqli_stmt_close($stmt);

        // Devolver `true` si la actualización fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
    * Verifica si existe una sanción para un partido en particular.
    *
    * @param int $id_partido El ID del partido.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return int El ID del partido dentro de la tabla sancion (0 si no se encontró).
    */
    public function verificar_sancion($id_partido,$conexion){
        // Inicializar la variable de retorno
        $respuesta = 0;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT id_partidos FROM sanciones WHERE id_partidos = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_partido);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultado) > 0) { 
                    $respuesta = mysqli_fetch_assoc($resultado)['id_partidos'];
                }
            } 
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver el ID del partido dentro de sanciones (0 si no se encontró).
        return $respuesta;
    }

    /**
    * Muestra la información de la sanción para un partido en particular.
    *
    * @param int $id_partido El ID del partido.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un array con los detalles de la sanción si se encuentra, o un array vacío si no hay sanción.
    */
    public function mostrar_sancion($id_partido, $conexion){
        // Inicializar variable de retorno
        $sancion = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT informe_local,informe_visitante,nombre_arbitro FROM sanciones WHERE id_partidos = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_partido);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Agregar cada categoría al array de categorías.
                        $sancion[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver un array de sanciones asociadas al partido (array vacío si no encontro).
        return $sancion;
    }
}
?>