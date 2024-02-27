<?php
use PHPUnit\Framework\TestCase;

class prueba_3_actualizar_categoria extends TestCase {
    public function testEnvioDatosCorrectos() {
        // Datos de la categoría
        $datosCategoria = [
            'categoria' => 'Super Maxima', // Nombre de la categoría
            'num_equipos' => 4, // Número de equipos
            'id_categoria' => 111 // ID de una categoría existente en la base de datos
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_actualizar_categoria.php', $datosCategoria);

        // Verificar si la solicitud fue exitosa
        $this->assertEquals(200, $respuesta->getStatusCode());

        // Verificar el cuerpo de la respuesta
        $cuerpoRespuesta = json_decode($respuesta->getBody(), true);
        $this->assertArrayHasKey('success', $cuerpoRespuesta);
        $this->assertTrue($cuerpoRespuesta['success']);
    }

    public function testEnvioIdCategoriaIncorrecto() {
        // Datos de la categoría
        $datosCategoria = [
            'categoria' => 'NombreCategoria', // Nombre de la categoría
            'num_equipos' => 10, // Número de equipos
            'id_categoria' => 0 // ID de una categoría que no existe en la base de datos
        ];

        // Realizar la solicitud POST al script PHP
        $respuesta = $this->solicitarPost('http://localhost:8080/SGLIGAS/Modelo/3_actualizar_categoria.php', $datosCategoria);

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