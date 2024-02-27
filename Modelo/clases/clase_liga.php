<?php
/**
 * Clase para realizar operaciones respecto a liga en la base de datos.
 */
class liga{
    /**
    * @file
    * Esta función permite insertar una nueva liga en la base de datos.
    *
    * @param string $nombre_liga El nombre de la liga a insertar.
    * @param string $fecha_fundacion La fecha de fundación de la liga.
    * @param string $direccion La dirección de la liga.
    * @param string $correo_admin El correo del administrador de la liga.
    * @param string $estado El estado sera para saber si la liga siguie activa o no
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool Devuelve true si la inserción se realiza con éxito, de lo contrario, false.
    */
    public function insertar_liga($nombre_liga, $fecha_fundacion, $direccion, $correo_admin, $estado, $conexion) {
        // Inicializar variable de retorno
        $respuesta = false;

        // Dar formato a la fecha acorde a la BD
        $fecha_fundacion = date('Y-m-d', strtotime($fecha_fundacion));

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO liga(nombre_liga, fecha_fundacion, direccion, correoAdmin, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        if($stmt){
            // Vincular parámetros a la sentencia
            mysqli_stmt_bind_param($stmt, "sssss", $nombre_liga, $fecha_fundacion, $direccion, $correo_admin, $estado);
            
            // Ejecutar la sentencia
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
    * Obtiene la información de las ligas asociadas a un presidente a partir de la dirección de correo.
    *
    * @param string $correo_admin La dirección de correo del presidente.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un array que contiene la información de las ligas asociadas al presidente.
    */
    public function mostrar_liga_presidente($correo_admin, $conexion) {
        // Inicializar la variable de retorno
        $liga = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT * FROM liga WHERE correoAdmin = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "s", $correo_admin);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $liga[] = $fila;
                }
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        return $liga; // Devolver un array vacío si ocurre un error
    }

    /**
    * Verifica si existe una liga con el ID proporcionado en la base de datos.
    *
    * @param int $id_liga El ID de la liga que se desea verificar.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return int El ID de la liga si existe en la base de datos; de lo contrario, se devuelve 0.
    */
    public function verificar_ID($id_liga, $conexion) {
        // Inicializar la variable de retorno
        $respuesta = 0;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT id_liga FROM liga WHERE id_liga = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_liga);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultado) > 0) { 
                    $respuesta = mysqli_fetch_assoc($resultado)['id_liga'];
                }
            } 
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver el ID de la liga (0 si no se encontró).
        return $respuesta;
    }

    /**
    * Función para obtener todas las ligas, filtradas opcionalmente por nombre de liga.
    *
    * @param string $nombre_liga (opcional) El nombre de la liga para filtrar. Dejar vacío para obtener todas las ligas.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un array de datos de las ligas que cumplen con el filtro (si se proporciona) o todas las ligas si no se proporciona un filtro. 
    */
    public function mostrar_todas_ligas($nombre_liga, $conexion) {
        // Inicializar la variable de retorno
        $liga = array();

        // Crear la sentencia SQL
        if (empty($nombre_liga)) {
            // Obtener todas las ligas
            $sql = "SELECT DISTINCT liga.*
                    FROM liga
                    JOIN categoria ON liga.id_liga = categoria.id_liga
                    JOIN equipo ON categoria.id_categoria = equipo.id_categoria
                    WHERE liga.estado = 'activo'
                    AND categoria.estado = 'activo'
                    AND equipo.estado = 'activo';";
            
            // Obtener los resultados
            $resultado = mysqli_query($conexion, $sql);
        } else {
            // Verificar que existe una liga con un nombre dado
            $sql = "SELECT nombre_liga FROM liga WHERE nombre_liga=?";
            $stmt = mysqli_prepare($conexion, $sql);

            if ($stmt) {
                // Vincular parámetro a la sentencia
                mysqli_stmt_bind_param($stmt, "s", $nombre_liga);

                // Ejecutar la sentencia
                if (mysqli_stmt_execute($stmt)) {
                    // Obtener resultados
                    $resultado = mysqli_stmt_get_result($stmt);
                }    
            }

            // Cerrar la sentencia preparada
            mysqli_stmt_close($stmt);
        }

        // Almacenar las ligas en un array
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $liga[] = $fila;
            }
        }

        // Devolver todas las ligas o el nombre de una sola liga (array vacío si no encontro)
        return $liga;
    }

    /**
     * Elimina una liga de la base de datos.
     *
     * @param int $id_liga El ID de la liga a eliminar.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool Devuelve `true` si la eliminación fue exitosa, `false` en caso contrario.
     */
    public function eliminar_liga($id_liga, $conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE liga SET estado = 'inactivo' WHERE id_liga = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if($stmt){
            // Vincular parámetros a la sentencia
            mysqli_stmt_bind_param($stmt, "s", $id_liga);
            
            // Ejecutar la sentencia
            if(mysqli_stmt_execute($stmt)){
                $respuesta = true;
            }
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si la eliminación fue exitosa, `false` en caso contrario.
        return $respuesta;
    }
}
?>