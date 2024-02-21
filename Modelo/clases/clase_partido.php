<?php
/**
 * Clase para realizar operaciones respecto a partido en la base de datos.
 */
class partido{
    
    /**
    * Inserta un nuevo partido en el fixture del torneo.
    *
    * @param int $num_jornada El número de la jornada a la que pertenece el partido.
    * @param string $partidos Los detalles de los partidos (como un dato BLOB).
    * @param int $id_torneo El ID del torneo al que pertenece el partido.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool Devuelve `true` si la inserción fue exitosa, `false` en caso contrario.
    */
    public function insertar_partido($partidos, $num_equipos, $id_torneo, $conexion) {
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO partidos(num_jornada, id_equipo_local,goles_local,goles_visitante,id_equipo_visitante,fecha_partido,hora_partido,id_torneo,estado) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $cont=0;
        $num_jornada=1;
        $goles_local=0;
        $goles_visitante=0;
        $fecha_partido="1970-01-01";
        $hora_partido="00:00";
        $estado="programar";
        if($stmt){
            foreach ($partidos as $equipos) {
                for($i=0;$i<$num_equipos;$i+=2){
                    $id_equipo_local=$equipos[$i];
                    $id_equipo_visitante=$equipos[$i+1];
                    mysqli_stmt_bind_param($stmt, "iiiiissis", $num_jornada, $id_equipo_local,$goles_local,$goles_visitante,$id_equipo_visitante,$fecha_partido,$hora_partido,$id_torneo,$estado);
                    if (mysqli_stmt_execute($stmt)) {
                        $cont++;
                    } 
                }
                $num_jornada++;
                if($cont!=($num_equipos/2)){
                    return $respuesta;
                }
                else{
                    $cont=0;
                }
            }
            $respuesta = true;
        }
        // Cerrar la sentencia preparada.
        mysqli_stmt_close($stmt);
        // Devolver `true` si la inserción fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
    * Verifica si un partido con un ID específico existe en la base de datos.
    *
    * @param int $id_partido El ID del partido que se quiere verificar.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un array asociativo con los detalles del partido si se encuentra, o un array vacío si no se encontró.
    */
    public function verificar_ID($id_partido, $conexion) {
        // Inicializar la variable de retorno
        $respuesta = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT id_partidos,estado FROM partidos WHERE id_partidos = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_partido);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    //while ($fila = ) {
                        // Agregar cada categoría al array de categorías.
                        $respuesta = mysqli_fetch_assoc($resultado); 
                    //}
                } 
            } 
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver un array de id_partido y estado si existe (array vacío si no encontro).
        return $respuesta;
    }

    /**
    * Muestra los partidos de un torneo específico.
    *
    * @param int $id_torneo El ID del torneo del cual se quieren obtener los partidos.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Devuelve un array con los detalles de los partidos si se encuentran, o un array vacío si no hay partidos.
    */
    public function mostrar_partidos($id_torneo,$conexion){
        // Inicializar variable de retorno
        $partidos = array();
        
        // Crear la sentencia SQL con una consulta preparada.
        $sql="SELECT
                p.*,
                e_local.nombre_equipo AS nombre_local,
                e_local.escudo AS escudo_local,
                e_visitante.nombre_equipo AS nombre_visitante,
                e_visitante.escudo AS escudo_visitante
            FROM
                partidos p
            JOIN
                equipo e_local ON p.id_equipo_local = e_local.id_equipo
            JOIN
                equipo e_visitante ON p.id_equipo_visitante = e_visitante.id_equipo
            WHERE
                p.id_torneo = ?
            ORDER BY
                p.num_jornada, p.fecha_partido, p.hora_partido;";
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
                        // Agregar cada categoría al array de categorías.
                        $partidos[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información del partidos si se encuentra (array vacio si no encontro).
        return $partidos;
    }

    /**
    * Genera partidos para un torneo de equipos.
    *
    * @param array $equipos Un array que contiene los nombres de los equipos participantes.
    * @param int $num_equipos El número total de equipos en el torneo.
    *
    * @return array Un array bidimensional que representa el calendario de juegos del torneo.
    */
    public function generar_partidos($equipos,$num_equipos){
        // Inicializar variable de retorno
        $fixture=array();

        // Generar matriz de equipos solos
        $k=0;
        for($i=0;$i<$num_equipos-1;$i++){
            for($j=0;$j<$num_equipos;$j++){
                if(($i%2)!=0 & $j==0){
                    $fixture[$i][$j]="";
                        $fixture[$i][$j+1]=$equipos[$k];
                }
                else{
                    $fixture[$i][$j]=$equipos[$k];
                    $fixture[$i][$j+1]=""; 
                }
                $j++;
                $k++;
                if($k==$num_equipos-1){
                    $k=0;
                }
            }
        }

        //Asignar el ultimo equipo si el numero de equipos es impar se asignara el id que no juega
        //if(($num_equipos%2)==0){
            for($i=0;$i<$num_equipos-1;$i++){
                if($i%2==0){
                    $fixture[$i][1]=$equipos[$num_equipos-1];
                } else {
                    $fixture[$i][0]=$equipos[$num_equipos-1];
                }
            }
        //}
        // Asignar los rivales de cada equipo
        $k=$num_equipos-2;
        for($i=0;$i<$num_equipos-1;$i++){
            for($j=2;$j<$num_equipos;$j++){
                //if($j%2!=0){
                    $fixture[$i][$j]=$equipos[$k];
                    $k--;
                    if($k==-1){
                        $k=$num_equipos-2;
                    }
                //}
            }
        }
        
        // Devolver el fixture
        return $fixture;
    }

    /**
    * Muestra los partidos de un equipo en un torneo específico.
    *
    * @param int $id_torneo El ID del torneo del cual se quieren obtener los partidos.
    * @param int $id_equipo El ID del equipo del cual se quieren obtener los partidos.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Un array con los detalles de los partidos del equipo en el torneo si se encuentran, o un array vacío si no hay partidos.
    */
    public function mostrar_partidos_equipos($id_torneo, $id_equipo, $conexion){
        // Inicializar variable de retorno
        $partidos = array();
        
        // Crear la sentencia SQL con una consulta preparada.
        $sql="SELECT
                p.*,
                e_local.nombre_equipo AS nombre_local,
                e_local.escudo AS escudo_local,
                e_visitante.nombre_equipo AS nombre_visitante,
                e_visitante.escudo AS escudo_visitante
            FROM
                partidos p
            JOIN
                equipo e_local ON p.id_equipo_local = e_local.id_equipo
            JOIN
                equipo e_visitante ON p.id_equipo_visitante = e_visitante.id_equipo
            WHERE
                p.id_torneo = ?
                AND (e_local.id_equipo = ? OR e_visitante.id_equipo = ?)
            ORDER BY
                p.num_jornada;";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "iii", $id_torneo,$id_equipo,$id_equipo);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Agregar cada categoría al array de categorías.
                        $partidos[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver partidos de un equipo si se encuentra (array vacio si no encontro).
        return $partidos;
    }

    /**
    * Modifica el resultado de un partido en la base de datos.
    *
    * @param int $id_partido El ID del partido que se va a modificar.
    * @param array $partido Un array que contiene los datos del partido que se va a modificar.
    * @param string $estado El nuevo estado del partido.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la modificación fue exitosa, `false` en caso contrario.
    */
    public function modificar_resultado($id_partido, $partido, $estado, $conexion) {
        // Inicializar variable de retorno
        $respuesta = false;
    
        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE partidos SET goles_local = ?, goles_visitante = ?, estado = ? WHERE id_partidos = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            $goles_local = intval($partido[1]);
            $goles_visitante = intval($partido[2]);

            mysqli_stmt_bind_param($stmt, "iisi", $goles_local,$goles_visitante,$estado, $id_partido);
            
            $respuesta=mysqli_stmt_execute($stmt);
        }
    
        // Cerrar la sentencia preparada.
        mysqli_stmt_close($stmt);
    
        // Devolver `true` si la actualizacion fue exitosa, `false` en caso contrario.
        return $respuesta;
    }

    /**
    * Modifica la información de un partido en la base de datos.
    *
    * @param int $id_partido El ID del partido que se va a modificar.
    * @param array $partido Un array que contiene los datos del partido que se va a modificar.
    * @param string $estado El nuevo estado del partido.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la modificación fue exitosa, `false` en caso contrario.
    */
    public function modificar_partido($id_partido, $partido, $estado, $conexion) {
        // Inicializar variable de retorno
        $respuesta = false;
    
        // Crear la sentencia SQL con una consulta preparada.
        $sql = "UPDATE partidos SET fecha_partido = ?, hora_partido = ?, vocal = ?, veedor = ?, cancha = ?, estado = ? WHERE id_partidos = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            $fecha_partido = date('Y-m-d', strtotime($partido[0]));
            $hora_partido = $partido[1];
            $cancha = $partido[2];
            $vocal = $partido[3];
            $veedor = $partido[4];

            mysqli_stmt_bind_param($stmt, "ssssssi", $fecha_partido,$hora_partido,$vocal,$veedor,$cancha,$estado, $id_partido);
            
            $respuesta=mysqli_stmt_execute($stmt);
        }
    
        // Cerrar la sentencia preparada.
        mysqli_stmt_close($stmt);
    
        // Devolver `true` si la actualizacion fue exitosa, `false` en caso contrario.
        return $respuesta;
    }
    
    /**
    * Elimina todos los partidos de un torneo específico.
    *
    * @param int $id_torneo El ID del torneo del cual se eliminarán los partidos.
    * @param resource $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la eliminación fue exitosa, `false` en caso contrario.
    */
    public function eliminar_partidos($id_torneo,$conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "DELETE * FROM fixture VALUES (?,?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if($stmt){
            // Vincular parámetros a la sentencia
            mysqli_stmt_bind_param($stmt, "ibi", $num_jornada, $partidos, $id_torneo);
            // Enviar el dato BLOB
            mysqli_stmt_send_long_data($stmt, 1, $partidos);

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
    * Inserta una alineación en la base de datos.
    *
    * @param string $alineacion Los datos de la alineación.
    * @param int $id_partido El ID del partido asociado a la alineación.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return bool `true` si la inserción fue exitosa, `false` en caso contrario.
    */
    public function insertar_alineacion($alineacion, $id_partido, $conexion){
        // Inicializar variable de retorno
        $respuesta = false;

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "INSERT INTO alineacion(datos_alineacion,id_partido) VALUES (?,?)";
        $stmt = mysqli_prepare($conexion, $sql);
        
        if($stmt){
            // Vincular parámetros a la sentencia
            mysqli_stmt_bind_param($stmt, "si", $alineacion, $id_partido);

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
    * Verifica si existe una alineación para un partido en particular.
    *
    * @param int $id_partido El ID del partido.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Devuelve un array con los datos de la alineación si se encuentra, o un array vacío si no hay alineación.
    */
    public function verificar_existencia_alineacion($id_partido, $conexion){
        // Inicializar la variable de retorno
        $respuesta = array();

        // Crear la sentencia SQL con una consulta preparada.
        $sql = "SELECT id_partido,datos_alineacion FROM alineacion WHERE id_partido = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_partido);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Obtener resultados
                $resultado = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultado) > 0) { 
                    $respuesta = mysqli_fetch_assoc($resultado);
                }
            } 
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver un array de id patido y datos de alineacion si existe (array vacío si no encontro).
        return $respuesta;
    }

    /**
    * Muestra los detalles de un partido.
    *
    * @param int $id_partido El ID del partido.
    * @param mysqli $conexion La conexión a la base de datos.
    *
    * @return array Devuelve un array con los detalles del partido si se encuentra, o un array vacío si no hay partido.
    */
    public function mostrar_partido($id_partido, $conexion){
        // Inicializar variable de retorno
        $partido = array();
        
        // Crear la sentencia SQL con una consulta preparada.
        $sql="SELECT * FROM partidos WHERE id_partidos = ?;";
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
                    // Agregar partido al array de partido.
                    $partido[] = mysqli_fetch_assoc($resultado); 
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información del partido si se encuentra (array vacio si no encontro).
        return $partido;
    }

    /*public function mostrar_alineacion($id_alineacion, $conexion){
        // Inicializar variable de retorno
        $alineacion = array();
        
        // Crear la sentencia SQL para obtener los equipos de la categoría
        //$sql = "SELECT * FROM partidos WHERE id_torneo=?";
        $sql="SELECT datos_alineacion FROM alineacion WHERE id_alineacion = ?;";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            // Vincular el parámetro a la sentencia
            mysqli_stmt_bind_param($stmt, "i", $id_alineacion);

            // Ejecutar la sentencia
            if (mysqli_stmt_execute($stmt)) {
                // Ejecutar la sentencia SQL y obtener los resultados.
                $resultado = mysqli_stmt_get_result($stmt);

                // Verificar si hay resultados en la consulta.
                if (mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        // Agregar cada categoría al array de categorías.
                        $alineacion[] = $fila; 
                    }
                }  
            }
        }

        // Cerrar la sentencia preparada
        mysqli_stmt_close($stmt);

        // Devolver la información del equipo si se encuentra (array vacio si no encontro).
        return $alineacion;
    }*/
}
?>