<?php
use PHPUnit\Framework\TestCase;

class prueba_7_programar_partidos extends TestCase {
    public function testEnvioDeDatosCorrectos() {
        // Datos del partido
        $datos = [
            'id_partido' => 52,
            'partido' => [
                '2024-02-14',
                '08:00',
                'cancha 1',
                'equipo 1',
                ''
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_programar_partidos.php', $datos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
    }

    public function testEnvioDeIdPartidoIncorrecto() {
        // Datos del partido
        $datos = [
            'id_partido' => 9999,
            'partido' => [
                '2024-02-14',
                '08:00',
                'cancha 1',
                'equipo 1',
                ''
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_programar_partidos.php', $datos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_partido', $cuerpoRespuesta);
    }

    public function testEnvioDeIdPartidoEnEstadoIncorrecto() {
        // Datos del partido
        $datos = [
            'id_partido' => 52,
            'partido' => [
                '2024-02-14',
                '08:00',
                'cancha 1',
                'equipo 1',
                ''
            ]
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_programar_partidos.php', $datos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partido_programado', $cuerpoRespuesta);
    }

    public function testEnvioDeDatosVacios() {
        // Datos del partido
        $datos = [
            'id_partido' => 53,
            'partido' => []
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_programar_partidos.php', $datos);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>