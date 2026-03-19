<?php
namespace App\Reports;
use App\Services\Relatorios;
use Illuminate\Support\Facades\URL;

class Receituario{
    public function gerarHtmlReceituario($dados) {
        $dadosPDF = json_decode($dados, true);
        $data =[
            'dados' =>$dadosPDF,
            'img' =>  asset('assets/img/brasao-municipio.png') 
        ];
      
      

       return Relatorios::gerarPDF('reports.receituario',  $data , 'receituario.pdf');
    }

}
