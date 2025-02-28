.<?php
function gerarHtmlReceituario($dados) {
    return '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Receituário</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .header { text-align: center; }
            .logo { width: 150px; }
            .title { font-size: 24px; font-weight: bold; margin-top: 10px; }
            .paciente { font-size: 18px; font-weight: bold; margin-top: 20px; }
            .content { font-size: 16px; margin-top: 10px; }
            .footer { margin-top: 40px; font-size: 14px; text-align: center; }
            .assinatura { margin-top: 30px; text-align: right; }
            .linha-assinatura { margin-top: 10px; border-top: 1px solid black; width: 200px; margin-left: auto; margin-right: auto; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="assets/imagens/logo.png" class="logo" alt="Logo">
            <div class="title">Receituário</div>
        </div>

        <div class="paciente">Paciente: ' . $dados['nome'] . '</div>
        <div class="content">
            <p><strong>Data do Atendimento:</strong> ' . $dados['data_atendimento'] . '</p>
            <p><strong>Prescrição:</strong> ' . nl2br($dados['receituario']) . '</p>
        </div>

        <div class="footer">
            <p>' . date('d/m/Y') . '</p>
            <div class="assinatura">Assinatura/Carimbo</div>
            <div class="linha-assinatura"></div>
        </div>
    </body>
    </html>';
}
