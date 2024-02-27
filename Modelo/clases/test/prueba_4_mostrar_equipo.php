<?php
use PHPUnit\Framework\TestCase;

class prueba_4_mostrar_equipo extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos del equipo
        $datosEquipo = [
            'id_equipo' => 86,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_mostrar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
        $this->assertNotEmpty($cuerpoRespuesta['datos']);
    }

    public function testIdEquipoIncorrecto() {
        // Datos de ID de equipo incorrecto
        $datosEquipo = [
            'id_equipo' => -1,
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_mostrar_equipo.php', $datosEquipo);

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