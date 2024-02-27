<?php
use PHPUnit\Framework\TestCase;

class prueba_6_mostar_torneos extends TestCase {
    public function testEnvioDeDatosCorrectos() {
        // Datos del torneo
        $datos = [
            'id_categoria' => 111
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_mostar_torneos.php', $datos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
    }

    public function testEnvioDeIdCategoriaIncorrecto() {
        // Datos del torneo
        $datos = [
            'id_categoria' => 9999
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_mostar_torneos.php', $datos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_categoria', $cuerpoRespuesta);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>