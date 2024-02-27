<?php
use PHPUnit\Framework\TestCase;

class prueba_1_actualizar_datos_usuario extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => 'nuevo_usuario@example.com', // Correo de un usuario existente
            'nombre' => 'Nuevo Nombre', // Nuevo nombre para el usuario
            'tipo' => 'hincha' // Tipo de usuario
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_actualizar_datos_usuario.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioDatosCorrectosContraseña() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => 'nuevo_usuario@example.com', // Correo de un usuario existente
            'password' => 'nueva_contra', // Nuevo nombre para el usuario
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_actualizar_contraseña_usuario.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioDatosVacios() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => '', // Correo vacío
            'nombre' => '', // Nombre vacío
            'tipo' => '' // Tipo vacío
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_actualizar_datos_usuario.php', $datosUsuario);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testEnvioCorreoNoRegistrado() {
        // Datos del usuario
        $datosUsuario = [
            'correo' => 'correo_no_registrado@example.com', // Correo de un usuario no registrado
            'nombre' => 'Nuevo Nombre', // Nuevo nombre para el usuario
            'tipo' => 'presidente' // Tipo de usuario
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/1_actualizar_datos_usuario.php', $datosUsuario);

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