<?php
use PHPUnit\Framework\TestCase;

class prueba_4_eliminar_equipo extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del equipo a eliminar
        $datosEquipo = [
            'id_equipo' => 1, // ID de un equipo existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_eliminar_equipo.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdEquipoIncorrecto() {
        // Datos del equipo a eliminar
        $datosEquipo = [
            'id_equipo' => 0, // ID de un equipo que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_eliminar_equipo.php', $datosEquipo);

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