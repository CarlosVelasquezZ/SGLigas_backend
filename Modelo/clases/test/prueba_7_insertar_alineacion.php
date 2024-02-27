<?php
use PHPUnit\Framework\TestCase;

class prueba_7_insertar_alineacion extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos de la alineación
        $datosAlineacion = [
            'id_partido' => 54,
            'alineacion' => '{"local":[{"id_jugador":1,"numero_camisa":10},{"id_jugador":2,"numero_camisa":5}],"visitante":[{"id_jugador":3,"numero_camisa":7},{"id_jugador":4,"numero_camisa":3}]}' 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_alineacion.php', $datosAlineacion);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdPartidoIncorrecto() {
        // Datos de la alineación
        $datosAlineacion = [
            'id_partido' => 0, 
            'alineacion' => '{"local":[{"id_jugador":1,"numero_camisa":10},{"id_jugador":2,"numero_camisa":5}],"visitante":[{"id_jugador":3,"numero_camisa":7},{"id_jugador":4,"numero_camisa":3}]}' 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_alineacion.php', $datosAlineacion);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_partido', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_partido']);
    }

    public function testEnvioIdPartidoEstadoIncorrecto() {
        // Datos de la alineación
        $datosAlineacion = [
            'id_partido' => 52, 
            'alineacion' => '{"local":[{"id_jugador":1,"numero_camisa":10},{"id_jugador":2,"numero_camisa":5}],"visitante":[{"id_jugador":3,"numero_camisa":7},{"id_jugador":4,"numero_camisa":3}]}'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_alineacion.php', $datosAlineacion);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partido_jugado', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['partido_jugado']);
    }

    public function testEnvioDatosVacios() {
        // Datos de la alineación
        $datosAlineacion = [
            'id_partido' => 54,
            'alineacion' => ''
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_alineacion.php', $datosAlineacion);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testEnvioIdPartidoConAlineacionRegistrada() {
        // Datos de la alineación
        $datosAlineacion = [
            'id_partido' => 7, 
            'alineacion' => '{"local":[{"id_jugador":1,"numero_camisa":10},{"id_jugador":2,"numero_camisa":5}],"visitante":[{"id_jugador":3,"numero_camisa":7},{"id_jugador":4,"numero_camisa":3}]}' 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_insertar_alineacion.php', $datosAlineacion);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existe_alienacion', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existe_alienacion']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>