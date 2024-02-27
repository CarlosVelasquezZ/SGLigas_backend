<?php
use PHPUnit\Framework\TestCase;

class prueba_1_insertar_usuario extends TestCase {

    // Prueba de envio de datos correctos
    public function test_insercion_correcta() {
        // Datos del nuevo usuario
        $datosUsuario = [
            'correo' => 'nuevo_usuario@example.com',
            'nombre' => 'Nuevo Usuario',
            'password' => 'contraseña_segura',
            'tipo' => 'hincha'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitar_post('http://localhost:8080/SGLIGAS/Modelo/1_insertar_usuario.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    // Prueba de envio de datos vacios
    public function test_datos_vacios() {
        // Datos vacíos
        $datosUsuario = [
            'correo' => '',
            'nombre' => '',
            'password' => '',
            'tipo' => ''
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitar_post('http://localhost:8080/SGLIGAS/Modelo/1_insertar_usuario.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    // Prueba de envio de correo registrado
    public function test_correo_registrado() {
        // Datos de usuario existente
        $datosUsuario = [
            'correo' => 'nuevo_usuario@example.com',
            'nombre' => 'Usuario Registrado',
            'password' => 'contraseña_segura',
            'tipo' => 'hincha'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitar_post('http://localhost:8080/SGLIGAS/Modelo/1_insertar_usuario.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertFalse($cuerpoRespuesta['success']);
    }

    private function solicitar_post($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>