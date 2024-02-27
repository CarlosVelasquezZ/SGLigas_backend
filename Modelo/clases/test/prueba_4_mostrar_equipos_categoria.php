<?php
use PHPUnit\Framework\TestCase;

class prueba_4_mostrar_equipos_categoria extends TestCase {
    public function testConsultaCorrecta() {
        // Datos de la categoria
        $datosCategoria = [
            'id_categoria' => 111 // ID de la categoria
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_mostrar_equipos_categoria.php', $datosCategoria);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
    }

    public function testConsultaIncorrecta() {
        // Datos de la categoria
        $datosCategoria = [
            'id_categoria' => 0 // ID de la categoria incorrecto
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_mostrar_equipos_categoria.php', $datosCategoria);

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