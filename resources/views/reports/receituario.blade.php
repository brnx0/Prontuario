
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Receituário</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
      
        .logo { width: 150px; }
        .title { font-size: 24px; font-weight: bold; margin-top: 10px; }
        .paciente { font-size: 18px; font-weight: bold; margin-top: 20px; }
        .content { font-size: 16px; margin-top: 10px; }
        .footer { margin-top: 40px; font-size: 14px; text-align: center; }
        .assinatura { margin: 30px 0px; text-align: center; }
        .linha-assinatura { margin-top: 10px; border-top: 1px solid black; width: 250px; margin-left: auto; margin-right: auto; }
        

    </style>
</head>
<body>
    <img src="file://{{ asset('assets/img/brasao-municipio.png')  }}" 
     style="width: 150px; display: block;" alt="">




  


    <div class="header">
        
        <div class="title">Receituário</div>
    </div>

    {{-- <div class="paciente">Paciente: '.$dadosPDF->original->nome. '</div>
    <div class="content">
        <p><strong>Data do Atendimento:</strong> ' .$dadosPDF->original->dt_atendimento.'</p>
        <p><strong>Prescrição:</strong> ' . $dadosPDF->original->receituario . '</p> --}}
    </div>

    <div class="footer">
        <div class="assinatura">Assinatura/Carimbo</div>
        <div class="linha-assinatura"></div>
        {{-- <p>' . date('d/m/Y') . '</p> --}}
    </div>
</body>
</html>';