<?php
use PHPUnit\Framework\TestCase;

class prueba_4_actualizar_equipo extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del equipo
        $datosEquipo = [
            'nombre_equipo' => 'Nuevo nombre', // Nuevo nombre para el equipo
            'presidente' => 'Nuevo presidente', // Nuevo presidente del equipo
            'escudo' => 'http://url.com/escudo', // Nueva URL del escudo
            'id_equipo' => 86 // ID de un equipo existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_actualizar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdEquipoIncorrecto() {
        // Datos del equipo
        $datosEquipo = [
            'nombre_equipo' => 'Nuevo nombre', // Nuevo nombre para el equipo
            'presidente' => 'Nuevo presidente', // Nuevo presidente del equipo
            'escudo' => 'http://url.com/escudo', // Nueva URL del escudo
            'id_equipo' => 0 // ID de un equipo que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_actualizar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_equipo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_equipo']);
    }

    public function testEnvioDatosVacios() {
        // Datos del equipo
        $datosEquipo = [
            'nombre_equipo' => '', // Datos vacíos
            'presidente' => '',
            'escudo' => '',
            'id_equipo' => 86
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_actualizar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>