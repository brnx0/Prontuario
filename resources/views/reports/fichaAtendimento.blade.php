

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Médico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        .patient-info, .clinical-data {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            border: 1px solid #000;
        }
        .case-description {
            margin-bottom: 20px;
        }
        .signature {
            border-bottom: 1px solid;
            padding-bottom: 10px;
        }
         .signatureBorder {
            border: none
           
        }
        .data-vital {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>POLICLÍNICA MUNICIPAL REGIS PACHECO</h1>
        <h2>PREFEITURA MUNICIPAL DE SANTO AMARO</h2>
        <p>Praça da Purificação, 51 - Centro - Santo Amaro da Purificação-BA</p>
    </div>
    <div class="patient-info">
        <table>
            <tr>
                <td colspan="2"><b>DATA DE ATENDIMENTO:</b>  {{$dados['original']['dt_atendimento'] }}</td>
            </tr>
            <tr>
                <td><b>NOME DO PACIENTE:</b> {{$dados['original']['nome'] }}</td>
                <td><b>PROFISSÃO:</b> {{$dados['original']['nome'] }}</td>
            </tr>
            <tr>
                <td><b>ENDEREÇO:</b>  {{$dados['original']['endereco'] }}</td>
                <td><b>NASCIMENTO:</b> {{$dados['original']['nascimento'] }}</td>
            </tr>
            <tr>
                <td><b>IDADE:</b> {{$dados['original']['idade'] }}</td>
                <td><b>TELEFONE:</b> {{$dados['original']['tel'] }}</td>
            </tr>
            <tr>
                <td><b>Nº DE DOC IDENTIFICAÇÃO:</b> {{$dados['original']['nome'] }}</td>
                <td><b>CARTÃO DO SUS:</b> {{$dados['original']['cartao_sus'] }} </td>
            </tr>
            <tr>
                <td colspan="2"><b>SITUAÇÃO/QUEIXA:</b> {{wordwrap($dados['original']['situacao_queixa'], 80, "<br>\n", true)}}'</td>
            </tr>
        </table>
    </div>
    <div class="clinical-data">
        <table>
            <tr>
                <th>PRESSÃO (mmHg)</th>
                <th>FC (bpm)</th>
                <th>FR (rpm)</th>
                <th>SPO2 (%)</th>
                <th>Temperatura (°C)</th>
                <th>Peso (kg)</th>
                <th>HGT (mg/dl)</th>
            </tr>
            <tr>
                <td class="data-vital">{{$dados['original']['mmhg'] }}</td>
                <td class="data-vital">{{$dados['original']['bpm'] }}</td>
                <td class="data-vital">{{$dados['original']['rpm'] }}</td>
                <td class="data-vital">{{$dados['original']['spo2'] }}</td>
                <td class="data-vital">{{$dados['original']['temp'] }}</td>
                <td class="data-vital">{{$dados['original']['kg'] }}</td>
                <td class="data-vital">{{$dados['original']['hgt'] }}</td>
            </tr>
        </table>
    </div>
    <div class="case-description">
        <h3>DESCRIÇÃO DO CASO CLÍNICO</h3>
        <p>{{nl2br($dados['original']['desc_caso'])}}</p>
    </div>
<table>
    <tr>
        <td class="signatureBorder">
            <div>
                <p class="signature">Enfermeiro</p>
                <p class="signatureBorder">Carimbo/Assinatura</p>
            </div>
        </td>
        <td class="signatureBorder">
            <div>
                <p class="signature">Médico</p>
                <p class="signatureBorder">Carimbo/Assinatura</p>
            </div>
        </td>
            <div class="signature">      
            </div>
    </tr>
</table>
</body>
</html>