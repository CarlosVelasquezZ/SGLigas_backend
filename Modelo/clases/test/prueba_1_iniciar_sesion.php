<?php
use PHPUnit\Framework\TestCase;

include('../clase_usuario.php');
include('../../conexion.php');
require 'vendor/autoload.php';

class prueba_1_iniciar_sesion extends TestCase {

    // Prueba de envio de datos correctos
    public function testEnvioDatosCorrectos() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => 'nuevo_usuario@example.com',
            'password' => 'contraseña_segura'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_iniciar_sesion.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('token', $cuerpoRespuesta);
        $this->assertArrayHasKey('userData', $cuerpoRespuesta);
        $this->assertEquals('usuario_existente@example.com', $cuerpoRespuesta['userData']['correo']);
        $this->assertEquals('cliente', $cuerpoRespuesta['userData']['tipo_usuario']);
    }

    public function testEnvioDatosVacios() {
        // Datos vacíos
        $datosUsuario = [
            'correo' => '',
            'password' => ''
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_iniciar_sesion.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testCorreoNoRegistrado() {
        // Datos de correo no registrado
        $datosUsuario = [
            'correo' => 'correo_no_registrado@example.com',
            'password' => 'contraseña'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_iniciar_sesion.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('correo', $cuerpoRespuesta);
        $this->assertFalse($cuerpoRespuesta['correo']);
    }

    public function testContrasenaIncorrecta() {
        // Datos de contraseña incorrecta
        $datosUsuario = [
            'correo' => 'nuevo_usuario@example.com',
            'password' => 'contraseña_segura'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_iniciar_sesion.php', $datosUsuario);

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