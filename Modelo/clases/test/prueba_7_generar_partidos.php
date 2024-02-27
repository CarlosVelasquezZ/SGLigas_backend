<?php
use PHPUnit\Framework\TestCase;

class prueba_7_generar_partidos extends TestCase {
    public function testEnvioDeDatosCorrectos() {
        // Datos del torneo
        $datosPartidos = [
            'id_torneo' => 42,
            'equipos' => [86, 87, 88, 89]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_generar_partidos.php', $datosPartidos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioDeIdTorneoIncorrecto() {
        // Datos del torneo
        $datosPartidos = [
            'id_torneo' => 1000,
            'equipos' => [1, 2, 3, 4]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_generar_partidos.php', $datosPartidos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_torneo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_torneo']);
    }

    public function testEnvioDeIdTorneoConPartidosRegistrados() {
        // Datos del torneo
        $datosPartidos = [
            'id_torneo' => 42,
            'equipos' => [86, 87, 88, 89]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_generar_partidos.php', $datosPartidos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existen_partidos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existen_partidos']);
    }

    public function testEnvioDeDatosVacios() {
        // Datos del torneo
        $datosPartidos = [
            'id_torneo' => 43,
            'equipos' => []
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_generar_partidos.php', $datosPartidos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testEnvioDeIdEquipoIncorrecto() {
        // Datos del torneo
        $datosPartidos = [
            'id_torneo' => 43,
            'equipos' => [1, 2, 99, 4] // 99 es un ID de equipo que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_generar_partidos.php', $datosPartidos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existen_equipo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existen_equipo']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>