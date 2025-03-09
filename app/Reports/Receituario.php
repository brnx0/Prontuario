<?php
namespace App\Reports;
use App\Services\Relatorios;
use Illuminate\Support\Facades\URL;

class Receituario{
    public function gerarHtmlReceituario($dados) {
        $dadosPDF = json_decode($dados, true);
        
      
      

       return Relatorios::gerarPDF('reports.receituario',  $dadosPDF , 'receituario.pdf');
    }

}
