<?php
use PHPUnit\Framework\TestCase;

class prueba_5_eliminar_jugador extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del jugador
        $datosJugador = [
            'CI' => 2121212121 // CI de un jugador existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_eliminar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioCIIncorrecto() {
        // Datos del jugador
        $datosJugador = [
            'CI' => 0 // CI de un jugador que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_eliminar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_jugador', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_jugador']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>