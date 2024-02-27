<?php
use PHPUnit\Framework\TestCase;

class prueba_8_mostrar_acta_de_juego extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42,
            'id_partido' => 52
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_mostrar_acta_de_juego.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos_partido', $cuerpoRespuesta);
        $this->assertArrayHasKey('estadisticas', $cuerpoRespuesta);
        $this->assertArrayHasKey('sanciones', $cuerpoRespuesta);
    }

    public function testEnvioIdTorneoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 0, // ID de un torneo que no existe
            'id_partido' => 1 // ID de un partido existente en un torneo existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_mostrar_acta_de_juego.php', $datosPartido);

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
            'id_torneo' => 42, // ID de un torneo existente
            'id_partido' => 0 // ID de un partido que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_mostrar_acta_de_juego.php', $datosPartido);

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
            'id_torneo' => 42, // ID de un torneo existente
            'id_partido' => 54 // ID de un partido existente en un torneo existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_mostrar_acta_de_juego.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partido_por_jugar', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['partido_por_jugar']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>