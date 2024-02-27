<?php
use PHPUnit\Framework\TestCase;

class prueba_4_mostrar_equipos_liga extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos del ID de liga
        $datosLiga = [
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_mostrar_equipos_liga.php', $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
        // Comprueba si la respuesta contiene al menos un equipo
        $this->assertGreaterThanOrEqual(1, count($cuerpoRespuesta['datos']));
    }

    public function testIdLigaIncorrecto() {
        // Datos del ID de liga incorrecto
        $datosLiga = [
            'id_liga' => "dos"
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_mostrar_equipos_liga.php', $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_liga', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_liga']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>