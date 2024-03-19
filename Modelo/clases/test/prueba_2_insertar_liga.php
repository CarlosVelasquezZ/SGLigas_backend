<?php
use PHPUnit\Framework\TestCase;

class prueba_2_insertar_liga extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos del usuario
        $datosLiga = [
            'nombre_liga' => 'Nueva Liga',
            'fecha_fundacion' => '2022-02-14',
            'direccion' => 'Dirección de la Liga',
            'correo_admin' => 'correo_admin@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_insertar_liga.php'
        , $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioDatosVacios() {
        // Datos vacíos
        $datosLiga = [
            'nombre_liga' => '',
            'fecha_fundacion' => '',
            'direccion' => '',
            'correo_admin' => ''
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_insertar_liga.php', $datosLiga);

            // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testCorreoNoRegistrado() {
        // Datos de correo no registrado
        $datosLiga = [
            'nombre_liga' => 'Nueva Liga',
            'fecha_fundacion' => '2022-02-14',
            'direccion' => 'Dirección de la Liga',
            'correo_admin' => 'correo_no_registrado@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_insertar_liga.php', $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        //$this->assertArrayHasKey('no_existe_usuario', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_usuario']);
    }

    public function testCorreoNoPresidente() {
        // Datos de correo no presidente
        $datosLiga = [
            'nombre_liga' => 'Nueva Liga',
            'fecha_fundacion' => '2022-02-14',
            'direccion' => 'Dirección de la Liga',
            'correo_admin' => 'nuevo_usuario@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_insertar_liga.php', $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_es_presidente', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_es_presidente']);
    }

    public function testCorreoLigaRegistrada() {
        // Datos de correo con liga registrada
        $datosLiga = [
            'nombre_liga' => 'Nueva Liga',
            'fecha_fundacion' => '2022-02-14',
            'direccion' => 'Dirección de la Liga',
            'correo_admin' => 'correo_admin@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_insertar_liga.php', $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existe_registro', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existe_registro']);
    }

    public function testNombreLigaExistente() {
        // Datos de nombre de liga existente
        $datosLiga = [
            'nombre_liga' => 'Nueva Liga',
            'fecha_fundacion' => '2022-02-14',
            'direccion' => 'Dirección de la Liga',
            'correo_admin' => 'correo_admin2@example.com'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/2_insertar_liga.php', $datosLiga);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existe_nombre', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existe_nombre']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>