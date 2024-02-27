<?php
use PHPUnit\Framework\TestCase;

class prueba_7_insertar_estaditicas_jugadores extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42,
            'id_partido' => 52,
            'jugadores' => [
                [1313131313,1,0,1,0,1],
                [1515151515,1,0,1,0,1]
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_estaditicas_jugadores.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdTorneoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 0,
            'id_partido' => 1,
            'jugadores' => [
                [1313131313,1,0,1,0,1],
                [1515151515,1,0,1,0,1]
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_estaditicas_jugadores.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_torneo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_torneo']);
    }

    public function testEnvioIdTorneoSinPartidos() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 43,
            'id_partido' => 1,
            'jugadores' => [
                [1313131313,1,0,1,0,1],
                [1515151515,1,0,1,0,1]
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_estaditicas_jugadores.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existen_partidos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existen_partidos']);
    }

    public function testEnvioIdPartidoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42,
            'id_partido' => 0, 
            'jugadores' => [
                [1313131313,1,0,1,0,1],
                [1515151515,1,0,1,0,1]
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_estaditicas_jugadores.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_partido', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_partido']);
    }

    public function testEnvioIdPartidoEstadoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42,
            'id_partido' => 53,
            'jugadores' => [
                [1313131313,1,0,1,0,1],
                [1515151515,1,0,1,0,1]
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_estaditicas_jugadores.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partido_no_programado', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['partido_no_programado']);
    }

    public function testEnvioDatosVacios() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42, // Datos vacíos
            'id_partido' => 52,
            'jugadores' => ''
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_estaditicas_jugadores.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>