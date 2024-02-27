<?php
use PHPUnit\Framework\TestCase;

class prueba_8_insertar_sancion_tribunal extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del partido y sanciones
        $datosSanciones = [
            'id_partido' => 52,
            'informe_local' => 'Informe sanción local',
            'informe_visitante' => 'Informe sanción visitante',
            'responsables' => 'Responsable 1, Responsable 2'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_tribunal.php', $datosSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdPartidoIncorrecto() {
        // Datos del partido y sanciones
        $datosSanciones = [
            'id_partido' => 0, // ID de un partido que no existe
            'informe_local' => 'Informe sanción local',
            'informe_visitante' => 'Informe sanción visitante',
            'responsables' => 'Responsable 1, Responsable 2'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_tribunal.php', $datosSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_partido', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_partido']);
    }

    public function testEnvioIdPartidoEstadoIncorrecto() {
        // Datos del partido y sanciones
        $datosSanciones = [
            'id_partido' => 54,
            'informe_local' => 'Informe sanción local',
            'informe_visitante' => 'Informe sanción visitante',
            'responsables' => 'Responsable 1, Responsable 2'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_tribunal.php', $datosSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partido_no_jugado', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['partido_no_jugado']);
    }

    public function testEnvioIdPartidoSinSanciones() {
        // Datos del partido y sanciones
        $datosSanciones = [
            'id_partido' => 2, // ID de un partido existente que está jugado pero no tiene sanciones
            'informe_local' => 'Informe sanción local',
            'informe_visitante' => 'Informe sanción visitante',
            'responsables' => 'Responsable 1, Responsable 2'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_tribunal.php', $datosSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_sancion', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_sancion']);
    }

    public function testEnvioDatosVacios() {
        // Datos del partido y sanciones
        $datosSanciones = [
            'id_partido' => 52, // Datos vacíos
            'informe_local' => '',
            'informe_visitante' => '',
            'responsables' => ''
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_tribunal.php', $datosSanciones);

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