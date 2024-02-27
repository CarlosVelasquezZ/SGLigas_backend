<?php
use PHPUnit\Framework\TestCase;

class prueba_5_mostrar_estadisticas_jugador_equipo extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del equipo
        $datosEquipo = [
            'id_equipo' => 86,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_mostrar_estadisticas_jugador_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
        $this->assertNotEmpty($cuerpoRespuesta['datos']);
    }

    public function testEnvioIdEquipoIncorrecto() {
        // Datos del equipo
        $datosEquipo = [
            'id_equipo' => 0, 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_mostrar_estadisticas_jugador_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_equipo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_equipo']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>