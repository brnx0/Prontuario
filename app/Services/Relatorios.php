<?php 
namespace App\Services;
use Dompdf\Dompdf;
use Dompdf\Options;

class Relatorios{
    public static function  gerarPDF($html, $fileName = 'document.pdf'){
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream($fileName, ['Attachment' => false]);
    }
}