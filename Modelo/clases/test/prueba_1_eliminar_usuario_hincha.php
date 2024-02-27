<?php
use PHPUnit\Framework\TestCase;

class prueba_1_eliminar_usuario_hincha extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => 'wilmertituana10@gmail.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_eliminar_usuario_hincha.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioDatosVacios() {
        // Datos vacíos
        $datosUsuario = [
            'correo' => ''
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_eliminar_usuario_hincha.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testEnvioCorreoNoRegistrado() {
        // Datos del usuario con correo no registrado
        $datosUsuario = [
            'correo' => 'correo_no_registrado@ejemplo.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_eliminar_usuario_hincha.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertFalse($cuerpoRespuesta['success']);
    }

    public function testEnvioCorreoEsPresidente() {
        // Datos del usuario con correo del presidente
        $datosUsuario = [
            'correo' => 'admin2@gmail.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_eliminar_usuario_hincha.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('es_presidente', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['es_presidente']);
    }

    private function solicitarPost($url, $datos) {
        // Simular una solicitud POST a través de GuzzleHTTP
        $cliente = new \GuzzleHttp\Client();
        $respuesta = $cliente->post($url, [
            'form_params' => $datos
        ]);
        return $respuesta;
    }
}
?>