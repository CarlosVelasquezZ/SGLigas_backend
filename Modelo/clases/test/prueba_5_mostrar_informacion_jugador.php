<?php
use PHPUnit\Framework\TestCase;

class prueba_5_mostrar_informacion_jugador extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del jugador
        $datosJugador = ['CI' => 1313131313];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_mostrar_informacion_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
        $this->assertNotEmpty($cuerpoRespuesta['datos']);
    }

    public function testEnvioCedulaIncorrecta() {
        // Datos del jugador con CI incorrecta
        $datosJugador = ['CI' => 0];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_mostrar_informacion_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_CI', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_CI']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>