<?php
use PHPUnit\Framework\TestCase;

class prueba_8_insertar_sancion_informe extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del informe de sanciones
        $datosInformeSanciones = [
            'id_partido' => 52, 
            'informe_local' => 'Este es el informe local', 
            'informe_visitante' => 'Este es el informe visitante', 
            'arbitro' => 'Juan Pérez' 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_informe.php', $datosInformeSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdPartidoIncorrecto() {
        // Datos del informe de sanciones
        $datosInformeSanciones = [
            'id_partido' => 0,
            'informe_local' => 'Este es el informe local',
            'informe_visitante' => 'Este es el informe visitante',
            'arbitro' => 'Juan Pérez'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_informe.php', $datosInformeSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_partido', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_partido']);
    }

    public function testEnvioIdPartidoEstadoIncorrecto() {
        // Datos del informe de sanciones
        $datosInformeSanciones = [
            'id_partido' => 54, 
            'informe_local' => 'Este es el informe local', 
            'informe_visitante' => 'Este es el informe visitante', 
            'arbitro' => 'Juan Pérez' 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_informe.php', $datosInformeSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partido_no_jugado', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['partido_no_jugado']);
    }

    public function testEnvioIdPartidoConSancionRegistrada() {
        // Datos del informe de sanciones
        $datosInformeSanciones = [
            'id_partido' => 1,
            'informe_local' => 'Este es el informe local',
            'informe_visitante' => 'Este es el informe visitante',
            'arbitro' => 'Juan Pérez'
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_informe.php', $datosInformeSanciones);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existe_sancion', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existe_sancion']);
    }

    public function testEnvioDatosVacios() {
        // Datos del informe de sanciones
        $datosInformeSanciones = [
            'id_partido' => 2, // ID de un partido vacío
            'informe_local' => '', // Informe local vacío
            'informe_visitante' => '', // Informe visitante vacío
            'arbitro' => '' // Nombre del árbitro vacío
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/8_insertar_sancion_informe.php', $datosInformeSanciones);

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