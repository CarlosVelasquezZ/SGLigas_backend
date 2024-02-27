<?php
use PHPUnit\Framework\TestCase;

class prueba_1_recuperar_contraseña extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => 'carlospnppm@gmail.com' // Correo de un usuario registrado en tu sistema
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_recuperar_contrase%C3%B1a.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
        $this->assertArrayHasKey('message', $cuerpoRespuesta);
        $this->assertArrayHasKey('correoUsuario', $cuerpoRespuesta);
        $this->assertEquals($datosUsuario['correo'], $cuerpoRespuesta['correoUsuario']);
        $this->assertArrayHasKey('codigoVerificacion', $cuerpoRespuesta);
        $this->assertEquals(6, strlen($cuerpoRespuesta['codigoVerificacion'])); // Verificar que el código de verificación tiene 6 caracteres
    }

    public function testEnvioCorreoNoRegistrado() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => 'correo_que_no_existe@ejemplo.com' // Correo que no está registrado en tu sistema
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_recuperar_contrase%C3%B1a.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertFalse($cuerpoRespuesta['success']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}

?>