<?php
use PHPUnit\Framework\TestCase;

class prueba_6_mostrar_posiciones extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del torneo
        $datosTorneo = [
            'id_torneo' => 42 // ID del torneo válido
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_mostrar_posiciones.php', $datosTorneo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('tabla', $cuerpoRespuesta);
    }

    public function testEnvioIdTorneoIncorrecto() {
        // Datos del torneo
        $datosTorneo = [
            'id_torneo' => 0 // ID del torneo no válido
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_mostrar_posiciones.php', $datosTorneo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_torneo', $cuerpoRespuesta);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>