<?php
use PHPUnit\Framework\TestCase;

class prueba_4_validar_numero_equipos extends TestCase {

    public function testEnvioDatosCorrectosYLimiteAlcanzado() {
        // Datos del equipo
        $datosEquipo = [
            'id_categoria' => 82 // ID de una categoría que alcanzo el límite de equipos
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_validar_numero_equipos.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('limite_equipos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['limite_equipos']);
    }

    public function testEnvioDatosCorrectosYLimiteNoAlcanzado() {
        // Datos del equipo
        $datosEquipo = [
            'id_categoria' => 111 // ID de una categoría que no haya alcanzado el límite de equipos
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_validar_numero_equipos.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('limite_equipos', $cuerpoRespuesta);
        $this->assertFalse($cuerpoRespuesta['limite_equipos']);
    }

    public function testEnvioIdCategoriaIncorrecto() {
        // Datos del equipo
        $datosEquipo = [
            'id_categoria' => 0 // Asignar un ID de una categoría que no exista
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/4_validar_numero_equipos.php', $datosEquipo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_categoria', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_categoria']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>