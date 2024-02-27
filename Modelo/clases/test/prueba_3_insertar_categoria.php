<?php
use PHPUnit\Framework\TestCase;

class prueba_3_insertar_categoria extends TestCase {

    public function testEnvioDatosCorrectos() {
        // Datos de la categoría
        $datosCategoria = [
            'categoria' => 'Maxima',
            'num_equipos' => 4,
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_insertar_categoria.php', $datosCategoria);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testIdLigaIncorrecto() {
        // Datos de categoría con ID de liga incorrecto
        $datosCategoria = [
            'categoria' => 'Maxima',
            'num_equipos' => 4,
            'id_liga' => 100
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_insertar_categoria.php', $datosCategoria);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('no_existe_liga', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['no_existe_liga']);
    }

    public function testEnvioDatosVacios() {
        // Datos vacíos
        $datosCategoria = [
            'categoria' => '',
            'num_equipos' => '',
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_insertar_categoria.php', $datosCategoria);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('noHayDatos', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['noHayDatos']);
    }

    public function testNombreCategoriaExistente() {
        // Datos de nombre de liga existente
        $datosCategoria = [
            'categoria' => 'Maxima',
            'num_equipos' => 4,
            'id_liga' => 1
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_insertar_categoria.php', $datosCategoria);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('existe_nombre', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['existe_nombre']);
    }

    private function solicitarPost($url, $datos) {
        $cliente = new \GuzzleHttp\Client();
        return $cliente->post($url, [
            'form_params' => $datos
        ]);
    }
}
?>