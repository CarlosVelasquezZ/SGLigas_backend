<?php
use PHPUnit\Framework\TestCase;

class prueba_5_actualizar_jugador extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos del jugador
        $datosJugador = [
            'CI' => 1313131313, // CI no existente
            'posicion' => 'Delantero',
            'foto' => 'https://example.com/jugador1.jpg',
            'estatura' => 180, // cm
            'num_camiseta' => 11, // Número de camiseta
            'id_equipo' => 86 // ID de un equipo existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_actualizar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdEquipoIncorrecto() {
        // Datos del jugador
        $datosJugador = [
            'CI' => 123456789, // CI no existente
            'posicion' => 'Delantero',
            'foto' => 'https://example.com/jugador1.jpg',
            'estatura' => 180, // cm
            'num_camiseta' => 10, // Número de camiseta
            'id_equipo' => 0 // ID de un equipo que no existe
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_actualizar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_equipo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_equipo']);
    }

    public function testEnvioCIExistente() {
        // Datos del jugador
        $datosJugador = [
            'CI' => 0, // CI que ya existe en la base de datos
            'posicion' => 'Delantero',
            'foto' => 'https://example.com/jugador1.jpg',
            'estatura' => 180, // cm
            'num_camiseta' => 10, // Número de camiseta
            'id_equipo' => 86 // ID de un equipo existente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_actualizar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_jugador', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_jugador']);
    }

    public function testEnvioDatosVacios() {
        // Datos del jugador
        $datosJugador = [
            'CI' => 1313131313, // Datos vacíos
            'posicion' => '',
            'foto' => '',
            'estatura' => '',
            'num_camiseta' => '',
            'id_equipo' => 86
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_actualizar_jugador.php', $datosJugador);

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