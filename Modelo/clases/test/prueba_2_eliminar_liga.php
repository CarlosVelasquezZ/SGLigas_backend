<?php
use PHPUnit\Framework\TestCase;

class prueba_2_eliminar_liga extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos de la liga
        $datosLiga = [
            'id_liga' => 1 // ID de una liga existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_eliminar_liga.php', $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdLigaIncorrecto() {
        // Datos de la liga
        $datosLiga = [
            'id_liga' => 0 // ID de una liga que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_eliminar_liga.php', $datosLiga);

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