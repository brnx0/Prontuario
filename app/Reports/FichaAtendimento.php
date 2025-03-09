<?php
namespace App\Reports;
use App\Services\Relatorios;
Class FichaAtendimento{
    public function gerarHtmlAtendimento($dados) {
        $dadosPDF = json_decode($dados, true);
        return Relatorios::gerarPDF('reports.fichaAtendimento',  ['dados'=>$dadosPDF]  , 'ficha.pdf');
      
 
    }    
}

