<?php
use PHPUnit\Framework\TestCase;

class prueba_7_mostrar_alineacion extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del partido
        $datosPartido = [
            'id_partido' => 54 // ID de un partido existente en estado jugado
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_alineacion.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
        // Verificar que se devuelvan datos de la alineación del partido
        $this->assertNotEmpty($cuerpoRespuesta['datos']);
    }

    public function testEnvioIdPartidoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_partido' => 0 // ID de un partido que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_alineacion.php', $datosPartido);

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
            'id_partido' => 53 // ID de un partido en estado que no es jugado
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_alineacion.php', $datosPartido);

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