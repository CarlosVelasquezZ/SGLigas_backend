<?php
/**
 * Clase para realizar operaciones respecto a jugadores en la base de datos.
 */
class jugador{
    /**
     * Inserta un nuevo jugador en la base de datos.
     *
     * @param int $CI El número de cédula del jugador.
     * @param string $nombre El nombre del jugador.
     * @param string $posicion La posición del jugador.
     * @param string $foto La foto del jugador (como un dato BLOB).
     * @param string $fecha_nacimiento La fecha de nacimiento del jugador en formato 'Y-m-d'.
     * @param float $estatura La estatura del jugador.
     * @param int $id_equipo El ID del equipo al que pertenece el jugador.
     * @param mysqli $conexion La conexión a la base de datos.
     * @param int $estado El estado del jugador.
     * @param int $num_camiseta El número de camiseta del jugador.
     * 
     * @return bool Devuelve `true` si la inserción fue exitosa, `false` en caso contrario.
     */
    public function insertar_jugador($CI, $nombre, $posicion, $foto, $fecha_nacimiento, $estatura, $id_equipo, $estado, $num_camiseta, $conexion) {
        // Inicializar variable de retorno
        $respuesta = false;

        // Dar formato a la fecha acorde a la BD
        $fecha_nacimiento = date('Y-m-d', strtotime($fecha_nacimiento));

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO jugadores(CI, nombre, posicion, foto, fecha_nacimiento, estatura, num_camiseta, id_equipo, estado) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if($stmt){
            // Vincular parámetros a la sentencia
            mysqli_stmt_bind_param($stmt, "issssiiis", $CI, $nombre, $posicion, $foto, $fecha_nacimiento, $estatura, $num_camiseta, $id_equipo, $estado);

            // Ejecutar la sentencia
            if(mysqli_stmt_execute($stmt)){
                $respuesta = true;
            }
        }
        // Cerrar la sentencia preparada.
        mysqli_stmt_close($stmt);

        // Devolver `true` si la inserción fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
     * Verifica la existencia de una camiseta para un equipo específico.
     *
     * @param int $id_equipo El ID del equipo.
     * @param int $num_camiseta El número de camiseta a verificar.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return int El número de camiseta si existe, 0 en caso contrario.
     */
    public function verificar_camiseta($id_equipo, $num_camiseta, $conexion){
        // Inicializar la variable de retorno
        $respuesta = 0;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT num_camiseta FROM jugadores WHERE id_equipo = ? AND num_camiseta = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "ii", $id_equipo, $num_camiseta);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultado) > 0) { 
                    $respuesta = mysqli_fetch_assoc($resultado)['num_camiseta'];
                }
            } 
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver el numero de camiseta del jugador (0 si no se encontró).
        return $respuesta;
    }

    /**
     * Muestra la información de un jugador a partir de su CI.
     *
     * @param int $CI El número de cédula del jugador.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return array Devuelve un array con la información del jugador si se encuentra, o un array vacío si no se encontró.
     */
    public function mostrar_informacion_jugador($CI, $conexion) {
        // Inicializar variable de retorno
        $jugador = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT * FROM jugadores WHERE CI = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $CI);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    $jugador = mysqli_fetch_assoc($resultado);
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información del jugador si se encuentra (array vacio si no encontro).
        return $jugador;
    }

    /**
     * Verifica si un CI de jugador existe en la base de datos.
     *
     * @param int $CI El número de cédula del jugador.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return int Devuelve el CI del jugador si se encontró, o 0 si no se encontró.
     */
    public function verificar_CI($CI, $conexion) {
        // Inicializar la variable de retorno
        $respuesta = 0;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT CI FROM jugadores WHERE CI = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $CI);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultado) > 0) { 
                    $respuesta = mysqli_fetch_assoc($resultado)['CI'];
                }
            } 
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver el CI del jugador (0 si no se encontró).
        return $respuesta;
    }

    /**
     * Muestra la lista de jugadores activos de un equipo.
     *
     * @param int $id_equipo El ID del equipo.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return array Devuelve un array con la información de los jugadores activos del equipo o un array vacío si no hay jugadores activos.
     */
    public function mostrar_jugadores($id_equipo, $conexion){
        // Inicializar variable de retorno
        $jugadores = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT * FROM jugadores WHERE id_equipo = ? AND estado='activo'";
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
                        $jugadores[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información de los jugadores (array vacio si no encontro).
        return $jugadores;
    }

    /**
     * Inserta o actualiza las estadísticas de los jugadores en la base de datos.
     *
     * @param array $jugadores Un array de arrays que contiene la información de los jugadores y sus estadísticas.
     * @param int $id_torneo El ID del torneo.
     * @param int $id_partido El ID del partido.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool Devuelve `true` si la inserción o actualización de estadísticas fue exitosa, `false` en caso contrario.
     */
    function insertar_actualizar_estadisticas_jugador($jugadores, $id_torneo, $id_partido, $conexion){
        // Inicializar la variable de retorno
        $respuesta=false;

        // Crear la sentencia SQL con una consulta preparada.
        $existe = "SELECT CI, rojas, amarilla, goles, goles_recibidos, autogoles, id_partidos_jugados FROM estadistica_jugador WHERE CI = ? AND id_torneo = ?";
        $stmtExistencia = mysqli_prepare($conexion, $existe);
        
        // Actualizar o insertar para cada jugador
        $cont=0;
        foreach ($jugadores as $jugador) {
            mysqli_stmt_bind_param($stmtExistencia, "ii", $jugador[0], $id_torneo);
            mysqli_stmt_execute($stmtExistencia);
            mysqli_stmt_store_result($stmtExistencia);

            // Verificar si el jugador ya existe
            if (mysqli_stmt_num_rows($stmtExistencia) > 0) {
                // El jugador ya existe, obtener las estadísticas actuales
                mysqli_stmt_bind_result($stmtExistencia, $CI, $rojas, $amarillas, $goles, $goles_recibidos, $autogoles, $id_partidos_jugados);
                mysqli_stmt_fetch($stmtExistencia);

                // Actualizar estadísticas sumando los valores
                $nuevas_rojas = $rojas.','.$jugador[1];
                $nuevas_amarillas = $amarillas.','.$jugador[2];
                $nuevos_goles = $goles.','.$jugador[3];
                $nuevos_goles_recibidos = $goles_recibidos.','.$jugador[4];
                $nuevos_autogoles = $autogoles.','.$jugador[5];
                $nuevo_id_partidos_jugados = $id_partidos_jugados.','.$id_partido;

                // Actualizar la base de datos con las nuevas estadísticas
                $actualizarEstadisticas = "UPDATE estadistica_jugador SET rojas = ?, amarilla = ?, goles = ?, goles_recibidos = ?, autogoles = ?, id_partidos_jugados = ? WHERE CI = ? AND id_torneo = ?";
                $stmt = mysqli_prepare($conexion, $actualizarEstadisticas);
                mysqli_stmt_bind_param($stmt, "ssssssii", $nuevas_rojas, $nuevas_amarillas, $nuevos_goles, $nuevos_goles_recibidos, $nuevos_autogoles, $nuevo_id_partidos_jugados,$jugador[0], $id_torneo);
                if (mysqli_stmt_execute($stmt)) {
                    $cont++;
                } 
            } else {
                // El jugador no existe, insertar nuevas estadísticas
                $insertarEstadisticas = "INSERT INTO estadistica_jugador (CI, rojas, amarilla, goles, goles_recibidos, autogoles, id_partidos_jugados, id_torneo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conexion, $insertarEstadisticas);
                mysqli_stmt_bind_param($stmt, "issssssi", $jugador[0], $jugador[1], $jugador[2], $jugador[3], $jugador[4], $jugador[5], $id_partido, $id_torneo);
                if (mysqli_stmt_execute($stmt)) {
                    $cont++;
                } 
            }

            // Reiniciar el statement para la siguiente iteración
            mysqli_stmt_reset($stmtExistencia);
        }

        if($cont==count($jugadores)){
            $respuesta=true;
        }

        // Cerrar las consultas
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmtExistencia);

        // Devolver `true` si la inserción o actualizacion fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
     * Determina si un jugador ha jugado 7 partidos consecutivos en un torneo.
     *
     * @param int $id_torneo El ID del torneo.
     * @param int $id_partido El ID del partido.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool Devuelve `true` si el jugador ha jugado 7 partidos consecutivos, `false` en caso contrario.
     */
    function extraer_partidos_jugados($id_torneo,$id_partido,$conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT id_partidos_jugados FROM estadistica_jugador WHERE id_torneo = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_torneo);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);
                $cont=0;

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Agregar cada categoría al array de categorías.
                        $valores = explode(",", $fila['id_partidos_jugados']);
                        $ultimo_valor = end($valores);//Obtener el ultimo id_partido
                        if(intval($ultimo_valor)==$id_partido){
                            $cont++;
                        }
                        if($cont==7){
                            $respuesta=true;
                            break;
                        }
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si la extraccion es mas de 7, `false` en caso contrario.
        return $respuesta;
    }

    /**
     * Obtiene las estadísticas de los jugadores de un equipo.
     *
     * @param int $id_equipo El ID del equipo.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return array Devuelve un array con las estadísticas de los jugadores del equipo.
     */
    function mostrar_estadisticas_jugador_equipo($id_equipo,$conexion){
        // Inicializar variable de retorno
        $jugadores = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT estadistica_jugador.*,
                    jugadores.nombre,
                    jugadores.posicion,
                    jugadores.foto,
                    jugadores.num_camiseta
                FROM estadistica_jugador
                JOIN jugadores ON estadistica_jugador.CI = jugadores.CI
                JOIN equipo ON jugadores.id_equipo = equipo.id_equipo
                WHERE equipo.id_equipo = ?;";
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
                        $fila['pj']=$fila['id_partidos_jugados'];
                        unset($fila['id_partidos_jugados']);

                        foreach ($fila as $columna => $valores) {
                            // Comprobar si los valores contienen números separados por comas
                            if (strpos($valores, ',') !== false) {
                                if($columna=="pj"){
                                    $cadena_sin_comas = str_replace(",", "", $valores);
                                    $suma=strlen($cadena_sin_comas);
                                } else {
                                    // Dividir los valores en números individuales
                                    $numeros = explode(",", $valores);

                                    // Inicializar la suma para este dato
                                    $suma = 0;

                                    // Iterar sobre cada número y sumarlo
                                    foreach ($numeros as $numero) {
                                        $suma += intval($numero); // Convertir el número a entero y sumarlo
                                    }
                                }
                                //Reemplazar en la variable fila de los resultados obtenidos
                                $fila[$columna]=$suma;
                            }
                        }
                        $jugadores[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver las estadisticas de jugadores (array vacio si no encontro).
        return $jugadores;
    }

    /**
     * Obtiene las estadísticas de los jugadores de un equipo en un partido específico.
     *
     * @param int $id_partido El ID del partido.
     * @param int $id_torneo El ID del torneo.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return array Devuelve un array con las estadísticas de los jugadores del equipo en el partido específico.
     */
    function mostrar_estadisticas_jugador_partido($id_partido, $id_torneo, $conexion){
        // Inicializar variable de retorno
        $estadisticas = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT estadistica_jugador.*,
                    jugadores.nombre,
                    jugadores.posicion,
                    jugadores.foto,
                    jugadores.num_camiseta,
                    equipo.id_equipo
                FROM estadistica_jugador
                JOIN jugadores ON estadistica_jugador.CI = jugadores.CI
                JOIN equipo ON jugadores.id_equipo = equipo.id_equipo
                WHERE estadistica_jugador.id_torneo = ?;";
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
                        // Iterar sobre cada fila de los resultados obtenidos
                        $valores = explode(",", $fila['id_partidos_jugados']);
                        $posicion = array_search($id_partido, $valores);
                        if($posicion !== false){
                            foreach ($fila as $columna => $valores) {
                                // Comprobar si los valores contienen números separados por comas
                                if (strpos($valores, ',') !== false) {
                                    if($columna!="id_partidos_jugados"){
                                        // Dividir los valores en números individuales
                                        $numeros = explode(",", $valores);
                                        if (isset($numeros[$posicion])) {
                                            $elemento = $numeros[$posicion];
                                            //Reemplazar en la variable fila de los resultados obtenidos
                                            $fila[$columna]=$elemento;
                                        } 
                                    }
                                }
                            }
                            $estadisticas[] = $fila; 
                        }
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver las estadisticas si se encuentra (array vacio si no encontro).
        return $estadisticas;
    }

    /**
     * Actualiza los detalles de un jugador en la base de datos.
     *
     * @param int $CI El número de cédula del jugador.
     * @param string $posicion La posición del jugador.
     * @param string $foto La URL de la foto del jugador.
     * @param int $estatura La estatura del jugador en cm ejemplo (160).
     * @param int $num_camiseta El numero de camiseta del jugador.
     * @param int $id_equipo El ID del equipo al que pertenece el jugador.
     * @param mysqli $conexion La conexión a la base de datos.
     *
     * @return bool `true` si se actualizó el jugador correctamente, `false` si no se pudo actualizar.
     */
    public function actualizar_jugador($CI, $posicion, $foto, $estatura, $num_camiseta, $id_equipo, $conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE jugadores SET posicion = ?, foto = ?, estatura = ?, num_camiseta = ?, id_equipo= ? WHERE CI= ?";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "ssiiii", $posicion, $foto, $estatura, $num_camiseta, $id_equipo, $CI);
        
            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                $respuesta = mysqli_stmt_execute($stmt);
            }
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si se actualizo jugador, `false` en caso contrario.
        return $respuesta;
    }

    /**
    * Elimina un jugador de la base de datos.
    *
    * @param int $CI La CI del jugador a eliminar.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la eliminación fue exitosa, `false` en caso contrario.
    */
    public function eliminar_jugador($CI,$conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE jugadores SET estado = 'inactivo' WHERE CI= ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $CI);
        
            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                $respuesta = mysqli_stmt_execute($stmt);
            }
        }
        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver `true` si actualizo el jugador en estado inactivo, `false` en caso contrario.
        return $respuesta;
    }
}
?>