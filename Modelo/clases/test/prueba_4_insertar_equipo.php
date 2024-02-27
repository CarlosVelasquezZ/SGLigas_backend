<?php
use PHPUnit\Framework\TestCase;

class prueba_4_insertar_equipo extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos del nuevo equipo
        $datosEquipo = [
            'nombre_equipo' => 'Nuevo Equipo',
            'fecha_fundacion' => '2022-01-01',
            'presidente' => 'Presidente Nuevo',
            'color' => 'Rojo',
            'escudo' => 'http://example.com/escudo',
            'id_categoria' => 111,
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_insertar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdLigaIncorrecto() {
        // Datos del nuevo equipo con un ID de liga incorrecto
        $datosEquipo = [
            'nombre_equipo' => 'Nuevo Equipo',
            'fecha_fundacion' => '2022-01-01',
            'presidente' => 'Presidente Nuevo',
            'color' => 'Rojo',
            'escudo' => 'http://example.com/escudo',
            'id_categoria' => 111,
            'id_liga' => 0
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_insertar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_liga', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_liga']);
    }

    public function testEnvioIdCategoriaIncorrecto() {
        // Datos del nuevo equipo con un ID de categoría incorrecto
        $datosEquipo = [
            'nombre_equipo' => 'Nuevo Equipo',
            'fecha_fundacion' => '2022-01-01',
            'presidente' => 'Presidente Nuevo',
            'color' => 'Rojo',
            'escudo' => 'http://example.com/escudo',
            'id_categoria' => 0,
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_insertar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_categoria', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_categoria']);
    }

    public function testEnvioDatosVacios() {
        // Datos vacíos
        $datosEquipo = [
            'nombre_equipo' => '',
            'fecha_fundacion' => '',
            'presidente' => '',
            'color' => '',
            'escudo' => '',
            'id_categoria' => 111,
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_insertar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testEnvioNombreEquipoExistenteEnLiga() {
        // Datos del nuevo equipo con un nombre ya existente en la liga
        $datosEquipo = [
            'nombre_equipo' => 'Nuevo Equipo',
            'fecha_fundacion' => '2022-01-01',
            'presidente' => 'Presidente Nuevo',
            'color' => 'Rojo',
            'escudo' => 'http://example.com/escudo',
            'id_categoria' => 111,
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_insertar_equipo.php', $datosEquipo);

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