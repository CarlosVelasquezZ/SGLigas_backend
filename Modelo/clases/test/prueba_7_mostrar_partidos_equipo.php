<?php
use PHPUnit\Framework\TestCase;

class prueba_7_mostrar_partidos_equipo extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42, 
            'id_equipo' => 86 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_partidos_equipo.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partidos', $cuerpoRespuesta);
        $this->assertNotEmpty($cuerpoRespuesta['partidos']);
    }

    public function testEnvioIdTorneoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 0, // ID de un torneo que no existe
            'id_equipo' => 1 // ID de un equipo existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_partidos_equipo.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_torneo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_torneo']);
    }

    public function testEnvioIdEquipoIncorrecto() {
        // Datos del partido
        $datosPartido = [
            'id_torneo' => 42, // ID de un torneo existente con partidos
            'id_equipo' => 0 // ID de un equipo que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_partidos_equipo.php', $datosPartido);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_equipo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_equipo']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>