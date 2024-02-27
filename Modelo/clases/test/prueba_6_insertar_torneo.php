<?php
use PHPUnit\Framework\TestCase;

class prueba_6_insertar_torneo extends TestCase {
    public function testEnvioDeDatosCorrectosSinGrupos() {
        // Datos del torneo
        $datosTorneo = [
            'etapa' => 'Primera etapa',
            'fecha_inicio' => '2024-03-01',
            'fecha_fin' => '2024-03-10',
            'canchas' => 'Cancha1,Cancha2',
            'num_clasificados' => 2,
            'id_categoria' => 111
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_insertar_torneo.php', $datosTorneo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioDeDatosCorrectosConGrupos() {
        // Datos del torneo
        $datosTorneo = [
            'etapa' => 'Fase de grupos',
            'fecha_inicio' => '2024-03-01',
            'fecha_fin' => '2024-03-10',
            'canchas' => 'Cancha1,Cancha2',
            'grupo' => 2,
            'num_clasificados' => '2,2',
            'id_categoria' => 112
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_insertar_torneo.php', $datosTorneo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioDeIdCategoriaIncorrecto() {
        // Datos del torneo
        $datosTorneo = [
            'etapa' => 'Fase de grupos',
            'fecha_inicio' => '2024-03-01',
            'fecha_fin' => '2024-03-10',
            'canchas' => 'Cancha1,Cancha2',
            'num_clasificados' => 2,
            'id_categoria' => 0 
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_insertar_torneo.php', $datosTorneo);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_categoria', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_categoria']);
    }

    public function testEnvioDeDatosVacios() {
        // Datos del torneo
        $datosTorneo = [
            'etapa' => '',
            'fecha_inicio' => '',
            'fecha_fin' => '',
            'canchas' => '',
            'num_clasificados' => '',
            'id_categoria' => 113
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/6_insertar_torneo.php', $datosTorneo);

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