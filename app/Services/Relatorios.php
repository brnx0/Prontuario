<?php
namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class Relatorios
{
    public static function gerarPDF($view, $data, $fileName = 'document.pdf')
    {
        // Configurações do Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('tempDir', storage_path('app/tmp'));

        // Inicializa o Dompdf
        $dompdf = new Dompdf($options);

        // Renderiza a view Blade para HTML
      
        $html = View::make($view, $data)->render();
        $html = self::resolveImagePaths($html);

        // Carrega o HTML no Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Limpa o buffer de saída, se necessário
        if (ob_get_length()) {
            ob_end_clean();
        }
        ob_clean();

        // Define os cabeçalhos HTTP
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Envia o PDF para o navegador
        $dompdf->stream($fileName, ['Attachment' => false]);
        exit;
    }
    private static function resolveImagePaths($html)    {
        // Substitui caminhos relativos de imagens por URLs absolutas
        $baseUrl = url('/'); // URL base do projeto
        $html = preg_replace('/src="\/(.*?)"/', 'src="' . $baseUrl . '/$1"', $html);

        return $html;
    }
}