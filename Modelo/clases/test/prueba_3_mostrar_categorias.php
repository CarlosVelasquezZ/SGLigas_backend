<?php
use PHPUnit\Framework\TestCase;

class prueba_3_mostrar_categorias extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos del cliente
        $datosCliente = [
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_mostrar_categorias.php', $datosCliente);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
        $this->assertNotEmpty($cuerpoRespuesta['datos']);
    }

    public function testEnvioIdLigaIncorrecto() {
        // Datos del cliente
        $datosCliente = [
            'id_liga' => 0
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_mostrar_categorias.php', $datosCliente);

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