<?php
use PHPUnit\Framework\TestCase;

class prueba_5_insertar_jugador extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos del jugador
        $datosJugador = [
            'CI' => 12345678,
            'nombre' => 'Jugador de Prueba',
            'posicion' => 'Delantero',
            'fecha_nacimiento' => '1990-01-01',
            'foto' => 'https://ruta-de-la-imagen.com/foto.jpg',
            'estatura' => 180,
            'num_camiseta' => 10,
            'id_equipo' => 86
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_insertar_jugador.php', $datosJugador);

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
            'CI' => 12345677,
            'nombre' => 'Jugador de Prueba',
            'posicion' => 'Delantero',
            'fecha_nacimiento' => '1990-01-01',
            'foto' => 'https://ruta-de-la-imagen.com/foto.jpg',
            'estatura' => 180,
            'num_camiseta' => 11,
            'id_equipo' => 9999 // Un ID de equipo inexistente
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_insertar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_equipo', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_equipo']);
    }

    public function testEnvioCedulaExistente() {
        // Datos del jugador con una cédula existente
        $datosJugador = [
            'CI' => 12345678, // Una cédula que ya existe en la base de datos
            'nombre' => 'Jugador de Prueba',
            'posicion' => 'Delantero',
            'fecha_nacimiento' => '1990-01-01',
            'foto' => 'https://ruta-de-la-imagen.com/foto.jpg',
            'estatura' => 180,
            'num_camiseta' => 11,
            'id_equipo' => 86
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_insertar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existe_jugador', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existe_jugador']);
    }

    public function testEnvioDatosVacios() {
        // Datos del jugador vacíos
        $datosJugador = [
            'CI' => 12345677,
            'nombre' => '',
            'posicion' => '',
            'fecha_nacimiento' => '',
            'foto' => '',
            'estatura' => '',
            'num_camiseta' => '',
            'id_equipo' => 86
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_insertar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testEnvioNumeroCamisetaExistente() {
        // Datos del jugador con un número de camiseta existente
        $datosJugador = [
            'CI' => 12345677,
            'nombre' => 'Jugador de Prueba',
            'posicion' => 'Delantero',
            'fecha_nacimiento' => '1990-01-01',
            'foto' => 'https://ruta-de-la-imagen.com/foto.jpg',
            'estatura' => 180,
            'num_camiseta' => 10, 
            'id_equipo' => 86
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/5_insertar_jugador.php', $datosJugador);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('exite_numero_camiseta', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['exite_numero_camiseta']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>