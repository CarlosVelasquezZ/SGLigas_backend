<?php
/**
 * Clase para realizar operaciones respecto a equipo en la base de datos.
 */
class equipo{

    /**
    * Inserta un nuevo equipo en la base de datos.
    *
    * @param string $nombre_equipo El nombre del equipo a insertar.
    * @param string $fecha_fundacion La fecha de fundación del equipo en formato 'YYYY-MM-DD'.
    * @param string $presidente El nombre del presidente del equipo.
    * @param string $colores Los colores del equipo.
    * @param string $escudo La URL del escudo del equipo.
    * @param int $id_categoria El ID de la categoría a la que pertenece el equipo.
    * @param string $estado El estado del equipo (activo o inactivo).
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la inserción fue exitosa, `false` en caso contrario.
    */
    public function insertar_equipo($nombre_equipo,$fecha_fundacion,$presidente,$colores,$escudo,$estado,$id_categoria,$conexion){
        
        // Dar formato a la fecha acorde a la BD
        $fecha_fundacion = date('Y-m-d', strtotime($fecha_fundacion));
        // Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO equipo(nombre_equipo, fecha_fundacion, presidente, colores, escudo, id_categoria, estado) VALUES (?,?,?,?,?,?,?)";
        // Crear la sentencia preparada
        $stmt = mysqli_prepare($conexion, $sql);

        if($stmt){
            // Vincular parámetros a la sentencia.
            mysqli_stmt_bind_param($stmt, "sssssis", $nombre_equipo, $fecha_fundacion, $presidente, $colores, $escudo, $id_categoria, $estado);
            
            // Ejecutar la sentencia.
            if(mysqli_stmt_execute($stmt)){
                $respuesta = true;
            }
        }
        
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si la inserción fue exitosa, `false` en caso contrario.
        return $respuesta;
    } 
    
    /**
    * Verifica si un equipo ya ha alcanzado el límite de equipos permitidos para su categoría.
    *
    * @param int $id_categoria El ID de la categoría del equipo.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si el equipo ya ha alcanzado el límite de equipos permitidos, `false` en caso contrario.
    */
    public function verificar_num_equipos($id_categoria,$conexion){
        // Inicializar variable de retorno
        $respuesta=false;

        // Obtener el límite de equipos para la categoría
        $sqlLimiteEquipos = "SELECT num_equipos FROM categoria WHERE id_categoria = ?";
        $stmtLimiteEquipos = mysqli_prepare($conexion, $sqlLimiteEquipos);
        mysqli_stmt_bind_param($stmtLimiteEquipos, "i", $id_categoria);
        mysqli_stmt_execute($stmtLimiteEquipos);
        mysqli_stmt_bind_result($stmtLimiteEquipos, $limiteEquipos);
        mysqli_stmt_fetch($stmtLimiteEquipos);
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmtLimiteEquipos);

        // Verificar si el límite de equipos en la categoría se ha alcanzado
        $sqlVerificacion = "SELECT COUNT(*) as numEquipos FROM equipo WHERE id_categoria = ?";
        $stmtVerificacion = mysqli_prepare($conexion, $sqlVerificacion);
        mysqli_stmt_bind_param($stmtVerificacion, "i", $id_categoria);
        mysqli_stmt_execute($stmtVerificacion);
        mysqli_stmt_bind_result($stmtVerificacion, $numEquipos);
        mysqli_stmt_fetch($stmtVerificacion);
        
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmtVerificacion);

        if ($numEquipos >= $limiteEquipos) {
            $respuesta=true;
        } 

        return $respuesta;
    }

    /**
    * Obtiene la información de un equipo dado su ID.
    *
    * @param int $id_equipo El ID del equipo a obtener.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un arreglo asociativo con la información del equipo (nombre, fecha de fundación, presidente, colores, escudo, ID de la categoría y estado).
    */
    public function mostrar_equipo($id_equipo, $conexion) {
        // Inicializar variable de retorno
        $equipo = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT * FROM equipo WHERE id_equipo=?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_equipo);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Agregar cada categoría al array de categorías.
                        $equipo[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información del equipo si se encuentra (array vacio si no encontro).
        return $equipo;
    }

    /**
    * Obtiene la lista de equipos de una categoría dada.
    *
    * @param int $id_categoria El ID de la categoría.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un arreglo de arreglos asociativos, cada uno conteniendo la información de un equipo (nombre, fecha de fundación, presidente, colores, escudo, ID de la categoría y estado).
    */
    public function mostrar_equipos_categoria($id_categoria, $conexion) {
        // Inicializar variable de retorno
        $equipo = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT * FROM equipo WHERE id_categoria=?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_categoria);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Agregar cada categoría al array de categorías.
                        $equipo[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información del equipo si se encuentra (array vacio si no encontro).
        return $equipo;
    }

    /**
    * Obtiene la lista de equipos de una liga dada.
    *
    * @param int $id_liga El ID de la liga.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un arreglo de arreglos asociativos, cada uno conteniendo la información de un equipo (nombre, fecha de fundación, presidente, colores, escudo, ID de la categoría y estado).
    */
    public function mostrar_equipos_ligas($id_liga,$conexion){
        // Inicializar variable de retorno
        $equipos = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT * FROM equipo
                WHERE id_categoria IN (SELECT id_categoria FROM categoria WHERE id_liga = ?);";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_liga);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Agregar cada categoría al array de categorías.
                        $equipos[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información del equipo si se encuentra (array vacio si no encontro).
        return $equipos;
    }

    /**
    * Verifica si un ID de equipo dado existe en la base de datos.
    *
    * @param int $id_equipo El ID del equipo a verificar.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return int El ID del equipo si existe, 0 si no existe.
    */
    public function verificar_ID($id_equipo, $conexion) {
        // Inicializar la variable de retorno
        $respuesta = 0;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT id_equipo FROM equipo WHERE id_equipo = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_equipo);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultado) > 0) { 
                    $respuesta = mysqli_fetch_assoc($resultado)['id_equipo'];
                }
            } 
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver el ID del equipo (0 si no se encontró).
        return $respuesta;
    }

    /**
    * Registra las estadísticas de los equipos en un torneo dado.
    *
    * @param array $equipos Un arreglo de IDs de equipos a registrar.
    * @param int $id_torneo El ID del torneo.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la inserción fue exitosa, `false` en caso contrario.
    */
    public function registar_estadisticas_equipo($equipos, $id_torneo, $conexion){
        // Inicializar la variable de retorno
        $respuesta=false;

        //Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO estadistica_equipo (pg, pe, pp, gf, gc, id_equipo, id_torneo)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Iterar sobre los equipos y ejecutar la consulta para cada uno
            $cont=0;

            for($i=0;$i<count($equipos);$i++){
                mysqli_stmt_bind_param($stmt, "iiiiiii", $pg, $pe, $pp, $gf, $gc, $id_equipo, $id_torneo);
                $id_equipo=$equipos[$i];
                $pg=0; 
                $pe=0; 
                $pp=0; 
                $gf=0; 
                $gc=0;
                if (mysqli_stmt_execute($stmt)) {
                    $cont++;
                } 
            }

            if($cont==count($equipos)){
                $respuesta=true;
            }
        }

        // Cerrar la consulta de inserción
        mysqli_stmt_close($stmt);

        // Devolver `true` si la inserción fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
    * Actualiza las estadísticas de los equipos en un torneo dado tras la finalización de un partido.
    *
    * @param array $partido Un arreglo con los IDs de los equipos que participaron en el partido y el resultado del partido (goles a favor y goles en contra).
    * @param int $id_torneo El ID del torneo.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la actualización fue exitosa, `false` en caso contrario.
    */
    public function actualizar_estadisticas_equipo($partido, $id_torneo, $conexion){
        // Inicializar la variable de retorno
        $respuesta=false;

        //Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE estadistica_equipo 
                SET 
                    pg = CASE 
                        WHEN id_equipo = ? AND ? > ? THEN pg + 1
                        ELSE pg
                    END,
                    pe = CASE 
                        WHEN id_equipo = ? AND ? = ? THEN pe + 1
                        ELSE pe
                    END,
                    pp = CASE 
                        WHEN id_equipo = ? AND ? < ? THEN pp + 1
                        ELSE pp
                    END,
                    gf = gf + ?,
                    gc = gc + ?
                WHERE id_equipo = ? AND id_torneo = ?";

        $cont=0;
        for($i=0; $i<2; $i++){
            if($i==0){
                $id_equipo = $partido[0];
                $gf = $partido[1];
                $gc = $partido[2];
            } else {
                $id_equipo = $partido[3];
                $gf = $partido[2];
                $gc = $partido[1];
            }
            $stmt = mysqli_prepare($conexion, $sql);
            if($stmt){
                mysqli_stmt_bind_param($stmt, "iiiiiiiiiiiii", $id_equipo,$gf,$gc, $id_equipo,$gf,$gc,$id_equipo,$gf,$gc,$gf,$gc, $id_equipo, $id_torneo);
                if (mysqli_stmt_execute($stmt)) {
                    $cont++;
                } 
            }
        }
        if($cont==2){
            $respuesta=true;
        }

        // Devolver `true` si la inserción fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
    * Muestra las posiciones de los equipos en un torneo dado.
    *
    * @param int $id_torneo El ID del torneo.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un arreglo de arreglos asociativos, cada uno conteniendo la información de un equipo (nombre, escudo, partidos ganados, partidos empatados, goles a favor, goles en contra, puntos y gol diferencia).
    */
    function mostrar_posiciones($id_torneo,$conexion){
        // Inicializar variable de retorno
        $posiciones = array();

        //Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT 
                    equipo.nombre_equipo,
                    equipo.escudo,
                    estadistica_equipo.pg,
                    estadistica_equipo.pe,
                    estadistica_equipo.gf,
                    estadistica_equipo.gc,
                    (estadistica_equipo.pg * 3 + estadistica_equipo.pe) AS puntos,
                    (estadistica_equipo.gf - estadistica_equipo.gc) AS gol_diferencia
                FROM estadistica_equipo
                JOIN equipo ON estadistica_equipo.id_equipo = equipo.id_equipo
                WHERE estadistica_equipo.id_torneo = ?
                ORDER BY puntos DESC, gol_diferencia DESC, estadistica_equipo.gf DESC, estadistica_equipo.gc ASC;";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_torneo);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $posiciones[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la posiciones (array vacio si no encontro).
        return $posiciones;
    }
    
    /**
    * Verifica si ya existe un equipo con un nombre específico en una liga dada.
    *
    * @param string $nombre El nombre del equipo a verificar.
    * @param int $id_liga El ID de la liga.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si existe un equipo con el mismo nombre en la liga, `false` en caso contrario.
    */
    public function verificar_nombre_equipo($nombre, $id_liga, $conexion) {
        // Inicializar variable de retorno
        $existe = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT e.nombre_equipo
                FROM equipo e
                INNER JOIN categoria c ON e.id_categoria = c.id_categoria
                INNER JOIN liga l ON c.id_liga = l.id_liga
                WHERE l.id_liga = ? AND e.nombre_equipo = ?;";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "is", $id_liga,$nombre);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    $existe = true;
                } 
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);
        
        // Devolver `true` si existe un equipo con el mismo nombre en la liga, `false` en caso contrario.
        return $existe;
    }

    /**
     * Elimina un equipo de la base de datos.
     *
     * @param int $id_equipo El ID del equipo a eliminar.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool `true` si se eliminó el equipo correctamente, `false` si no se pudo eliminar.
     */
    public function eliminar_equipo($id_equipo,$conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE equipo SET estado = 'inactivo' WHERE id_equipo= ?";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_equipo);
        
            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                $respuesta = mysqli_stmt_execute($stmt);
            }
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si se actualizo, `false` en caso contrario.
        return $respuesta;
    }

    /**
     * Actualiza los detalles de un equipo en la base de datos.
     *
     * @param string $nombre_equipo El nuevo nombre del equipo.
     * @param string $presidente El nuevo nombre del presidente del equipo.
     * @param string $escudo La nueva URL del escudo del equipo.
     * @param int $id_equipo El ID del equipo a actualizar.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool `true` si se actualizó el equipo correctamente, `false` si no se pudo actualizar.
     */
    public function actualizar_equipo($nombre_equipo, $presidente, $escudo, $id_equipo, $conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE equipo SET nombre_equipo = ?, presidente = ?, escudo = ? WHERE id_equipo= ?";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "sssi", $nombre_equipo, $presidente, $escudo, $id_equipo);
        
            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                $respuesta = mysqli_stmt_execute($stmt);
            }
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si se actualizo equipo, `false` en caso contrario.
        return $respuesta;
    }
}
?>