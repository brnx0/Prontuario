<?php 
namespace App\Services;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class Relatorios{
    public static function gerarPDF($view, $data, $fileName = 'document.pdf') {       
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('tempDir', sys_get_temp_dir());

        // Criar a instância do DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf = new Dompdf($options);
    
        // Gerar HTML a partir da view Blade
      
        $html = View::make($view, $data)->render();
      

        // Carregar e renderizar HTML no DOMPDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        header('Content-Type: application/pdf');
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		header('Content-Disposition: inline; filename="' . $fileName . '"');
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Retornar PDF como resposta (sem download automático)
        return $dompdf->stream($fileName, ['Attachment' => false]);
    }
}