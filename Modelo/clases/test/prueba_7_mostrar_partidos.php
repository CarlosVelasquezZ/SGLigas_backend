<?php
use PHPUnit\Framework\TestCase;

class prueba_7_mostrar_partidos extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del torneo
        $datosTorneo = [
            'id_torneo' => 42 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_partidos.php', $datosTorneo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('partidos', $cuerpoRespuesta);
        $this->assertIsArray($cuerpoRespuesta['partidos']);
        $this->assertNotEmpty($cuerpoRespuesta['partidos']);
    }

    public function testEnvioIdTorneoIncorrecto() {
        // Datos del torneo
        $datosTorneo = [
            'id_torneo' => 0 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/7_mostrar_partidos.php', $datosTorneo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_torneo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_torneo']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>