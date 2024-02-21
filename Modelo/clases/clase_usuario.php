<?php
/**
 * Clase para realizar operaciones respecto a usuarios en la base de datos.
 */
class usuario{
    /**
     * Inserta un nuevo usuario en la base de datos.
     *
     * @param string $correo_usuario El correo del usuario.
     * @param string $nombre El nombre del usuario.
     * @param string $contraseña La contraseña del usuario.
     * @param string $tipo El tipo de usuario (por ejemplo, 'presidente' o 'hincha').
     * @param mysqli $conexion La instancia de conexión a la base de datos.
     *
     * @return bool true si la inserción fue exitosa, false en caso contrario.
     */
    public function insertar_usuario($correo_usuario, $nombre, $contrasena, $tipo, $conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Encripta el password
        $pass_fuerte = password_hash($contrasena, PASSWORD_DEFAULT);

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO usuarios VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        if($stmt){
            // Vincular parámetros a la sentencia
            mysqli_stmt_bind_param($stmt, "ssss", $correo_usuario, $nombre, $pass_fuerte, $tipo);
            
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
     * Verifica si un usuario ya está registrado en la base de datos.
     *
     * @param string $correo_usuario El correo del usuario a verificar.
     * @param mysqli $conexion La instancia de conexión a la base de datos.
     *
     * @return array Un array que contiene información del usuario si existe, o un array vacío si no existe
     * o existe algun error.
     */
    public function verificar_usuario($correo_usuario, $conexion){
        // Inicializar variable de retorno
        $usuario = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT correo, contraseña, tipo_usuario FROM usuarios WHERE correo = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "s", $correo_usuario);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($resultado) > 0) {
                    $usuario = mysqli_fetch_assoc($resultado);
                }
            } 
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver un array de usuario (array vacío si no encontro).
        return $usuario;
    }

    /**
     * Actualiza la información de un usuario en la base de datos.
     *
     * @param string $nombre El nuevo nombre del usuario.
     * @param string $tipo El nuevo tipo de usuario.
     * @param string $contrasena La nueva contraseña del usuario (puede ser vacía para no cambiarla).
     * @param string $correo_usuario El correo del usuario a actualizar.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool Devuelve `true` si la actualización fue exitosa, `false` en caso contrario.
     */
    public function actualizar_usuario($nombre, $tipo, $contrasena, $correo_usuario, $conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        if(empty($contrasena)){
            // Crear la sentencia SQL con una consulta preparada.
            $sql = "UPDATE usuarios SET nombre = ?, tipo_usuario = ? WHERE correo = ?";
            $stmt = mysqli_prepare($conexion, $sql);

            if ($stmt) {
                // Vincular parámetros a la sentencia
                mysqli_stmt_bind_param($stmt, "sss", $nombre, $tipo, $correo_usuario);
            
                // Ejecutar la sentencia
                if (mysqli_stmt_execute($stmt)) {
                    $respuesta = mysqli_stmt_execute($stmt);
                }
            }
        } else {
            // Crear una sentencia preparada para actualizar contraseña
            $sql = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
            $stmt = mysqli_prepare($conexion, $sql);

            if ($stmt) {
                // Vincular parámetros a la sentencia
                mysqli_stmt_bind_param($stmt, "ss", $contrasena, $correo_usuario);
            
                // Ejecutar la sentencia
                if (mysqli_stmt_execute($stmt)) {
                    $respuesta = mysqli_stmt_execute($stmt);
                }
            }
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si la actualización fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

     /**
     * Elimina un usuario hincha de la base de datos.
     *
     * @param string $correo_usuario El correo del usuario hincha a eliminar.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool Devuelve `true` si la eliminación fue exitosa, `false` en caso contrario.
     */
    public function eliminar_usuario_hincha($correo_usuario, $conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql=" DELETE FROM usuarios WHERE correo = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "s", $correo_usuario);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                $respuesta = mysqli_stmt_execute($stmt);
            }
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si la eliminación fue exitosa, `false` en caso contrario.
        return $respuesta;
    }
}
?>