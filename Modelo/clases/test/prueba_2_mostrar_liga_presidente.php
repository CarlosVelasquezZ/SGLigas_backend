<?php
use PHPUnit\Framework\TestCase;

class prueba_2_mostrar_liga_presidente extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos del presidente
        $datosPresidente = [
            'correo_admin' => 'correo_admin@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_mostrar_liga_presidente.php', $datosPresidente);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('datos', $cuerpoRespuesta);
        $this->assertTrue(count($cuerpoRespuesta['datos']) > 0);
    }

    public function testCorreoNoRegistrado() {
        // Datos de correo no registrado
        $datosPresidente = [
            'correo_admin' => 'correo_no_registrado@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_mostrar_liga_presidente.php', $datosPresidente);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_usuario', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_usuario']);
    }

    public function testCorreoNoPresidente() {
        // Datos de correo no presidente
        $datosPresidente = [
            'correo_admin' => 'nuevo_usuario@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_mostrar_liga_presidente.php', $datosPresidente);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_es_presidente', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_es_presidente']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}

?>