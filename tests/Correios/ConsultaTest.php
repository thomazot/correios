<?php

use PHPUnit\Framework\TestCase;
use Thomazot\Correios\Consulta;

class ConsultaTest extends TestCase
{
    public function testCep() {

        $correios = new Consulta();
        $consulta = $correios->cep("17520200");

        $this->assertEquals("Rua Adelmo Mugnai", $consulta['logradouro']);
        $this->assertEquals("Parque São Jorge", $consulta['bairro']);
        $this->assertEquals("Marília", $consulta['cidade']);
        $this->assertEquals("SP", $consulta['uf']);

    }
}