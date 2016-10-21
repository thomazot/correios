<?php

namespace Thomazot\Correios;

use Thomazot\Core\Curl;
use PhpQuery\PhpQuery as phpQuery;

class Consulta
{

    public function cep($cep)
    {

        $data = array(
            'cepEntrada' => $cep,
            'tipoCep'    =>'',
            'cepTemp'    =>'',
            'metodo'     =>'buscarCep',
        );

        $curl = new Curl;
        $html = $curl->simple( 'http://m.correios.com.br/movel/buscaCepConfirma.do', $data);

        phpQuery::newDocumentHTML($html, $charset = 'utf-8');

        $pesquisa = array();

        foreach(phpQuery::pq('#frmCep > div') as $pq_div){

            if(phpQuery::pq($pq_div)->is('.caixacampobranco') || phpQuery::pq($pq_div)->is('.caixacampoazul')){
                $dados = array();

                if(count(phpQuery::pq('.resposta:contains("Endereço: ") + .respostadestaque:eq(0)',$pq_div))) {
                    $dados['logradouro'] = trim(phpQuery::pq('.resposta:contains("Endereço: ") + .respostadestaque:eq(0)', $pq_div)->text());
                } else {
                    $dados['logradouro'] = trim(phpQuery::pq('.resposta:contains("Logradouro: ") + .respostadestaque:eq(0)', $pq_div)->text());
                }

                $dados['bairro']    = trim(phpQuery::pq('.resposta:contains("Bairro: ") + .respostadestaque:eq(0)',$pq_div)->text());
                $dados['cidade/uf'] = trim(phpQuery::pq('.resposta:contains("Localidade") + .respostadestaque:eq(0)',$pq_div)->text());
                $dados['cep']       = trim(phpQuery::pq('.resposta:contains("CEP: ") + .respostadestaque:eq(0)',$pq_div)->text());
                $dados['cidade/uf'] = explode('/',$dados['cidade/uf']);
                $dados['cidade']    = trim($dados['cidade/uf'][0]);
                $dados['uf']        = trim($dados['cidade/uf'][1]);

                unset($dados['cidade/uf']);

                $pesquisa = $dados;
            }

        }

        return $pesquisa;
    }

}