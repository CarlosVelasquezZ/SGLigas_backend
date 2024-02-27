<?php
use PHPUnit\Framework\TestCase;

class prueba_7_comprobar_estadisticas_jugador extends TestCase {
    public function testEnvioDatosCorrectosTieneRegistros() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 36,
            'id_partido' => 17,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_comprobar_estadisticas_jugador.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existen_registros', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existen_registros']);
    }

    public function testEnvioDatosCorrectosNoTieneRegistros() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42,
            'id_partido' => 52,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_comprobar_estadisticas_jugador.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existen_registros', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existen_registros']);
    }

    public function testEnvioIdTorneoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 0,
            'id_partido' => 1,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_comprobar_estadisticas_jugador.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_torneo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_torneo']);
    }

    public function testEnvioIdPartidoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 36,
            'id_partido' => 0,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_comprobar_estadisticas_jugador.php', $datosPartido);

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
            'id_partido' => 55,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_comprobar_estadisticas_jugador.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partido_no_programado', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['partido_no_programado']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}

?>