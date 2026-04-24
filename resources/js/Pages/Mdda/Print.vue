<script setup lang="ts">
import { onMounted } from 'vue';

defineOptions({ layout: null });

interface MddaCaso {
    id: string | null;
    numero_ordem: number;
    data_atendimento: string;
    nome_paciente: string;
    procedencia: string;
    faixa_etaria: string;
    idade_display: string;
    zona: string;
    data_primeiros_sintomas: string;
    plano_tratamento: string;
}

interface MddaRelatorio {
    id: string;
    semana_epidemiologica: number;
    ano: number;
    municipio: string;
    unidade_saude: string;
    responsavel_nome: string;
    status: string;
    casos: MddaCaso[];
}

const props = defineProps<{ relatorio: MddaRelatorio }>();

const MIN_ROWS = 20;

const formatDate = (val: string): string => {
    if (!val) return '';
    const [y, m, d] = val.split('-');
    return `${d}/${m}/${y}`;
};

const padRows = (): MddaCaso[] => {
    const rows = [...props.relatorio.casos];
    while (rows.length < MIN_ROWS) {
        rows.push({
            id: null, numero_ordem: rows.length + 1,
            data_atendimento: '', nome_paciente: '', procedencia: '',
            faixa_etaria: '', idade_display: '', zona: '',
            data_primeiros_sintomas: '', plano_tratamento: '',
        });
    }
    return rows;
};

onMounted(() => {
    setTimeout(() => window.print(), 400);
});
</script>

<template>
    <div class="impresso">
        <!-- Cabeçalho -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div class="logo-text">MINISTÉRIO DA SAÚDE</div>
                    <div class="logo-text">SECRETARIA DE VIGILÂNCIA EM SAÚDE</div>
                </td>
                <td class="title-cell">
                    <div class="title-main">IMPRESSO I - MONITORIZAÇÃO DAS DOENÇAS DIARREICAS AGUDAS</div>
                    <div class="title-sub">PLANILHA DE CASOS</div>
                </td>
                <td class="se-cell">
                    <div>Semana Epidemiológica de Atendimento</div>
                    <div class="se-value">{{ relatorio.semana_epidemiologica }}</div>
                    <div>Ano</div>
                    <div class="se-value">{{ relatorio.ano }}</div>
                </td>
            </tr>
        </table>

        <!-- Identificação -->
        <div class="identificacao">
            <span>Município: <span class="campo-valor">{{ relatorio.municipio }}</span></span>
            <span style="margin-left: 40px">Unidade de Saúde: <span class="campo-valor">{{ relatorio.unidade_saude }}</span></span>
        </div>

        <!-- Tabela de Casos -->
        <table class="casos-table">
            <thead>
                <tr>
                    <th rowspan="2" class="col-nr">N.º de<br>ordem</th>
                    <th rowspan="2" class="col-data">Data do<br>atendimento</th>
                    <th rowspan="2" class="col-nome">NOME</th>
                    <th colspan="5" class="col-group">FAIXA ETÁRIA *</th>
                    <th rowspan="2" class="col-proc">PROCEDÊNCIA<br><small>(RUA, BAIRRO, LOCALIDADE, SÍTIO, FAZENDA, ETC.)</small></th>
                    <th colspan="2" class="col-group">ZONA **</th>
                    <th rowspan="2" class="col-datasint">Data dos<br>primeiros<br>sintomas</th>
                    <th colspan="4" class="col-group">PLANO DE TRATAMENTO ***</th>
                </tr>
                <tr>
                    <th class="col-faixa">&lt;1</th>
                    <th class="col-faixa">1a4</th>
                    <th class="col-faixa">5a9</th>
                    <th class="col-faixa">10+</th>
                    <th class="col-faixa">IGN</th>
                    <th class="col-zona">Urbana</th>
                    <th class="col-zona">Rural</th>
                    <th class="col-plano">A</th>
                    <th class="col-plano">B</th>
                    <th class="col-plano">C</th>
                    <th class="col-plano">IGN</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="caso in padRows()" :key="caso.numero_ordem">
                    <td class="text-center">{{ caso.nome_paciente ? caso.numero_ordem : '' }}</td>
                    <td class="text-center">{{ formatDate(caso.data_atendimento) }}</td>
                    <td>{{ caso.nome_paciente }}</td>
                    <!-- Faixa Etária -->
                    <td class="text-center">{{ caso.faixa_etaria === '<1' ? caso.idade_display : '' }}</td>
                    <td class="text-center">{{ caso.faixa_etaria === '1a4' ? caso.idade_display : '' }}</td>
                    <td class="text-center">{{ caso.faixa_etaria === '5a9' ? caso.idade_display : '' }}</td>
                    <td class="text-center">{{ caso.faixa_etaria === '10+' ? caso.idade_display : '' }}</td>
                    <td class="text-center">{{ caso.faixa_etaria === 'IGN' && caso.nome_paciente ? 'X' : '' }}</td>
                    <!-- Procedência -->
                    <td>{{ caso.procedencia }}</td>
                    <!-- Zona -->
                    <td class="text-center">{{ caso.zona === 'Urbana' ? 'X' : '' }}</td>
                    <td class="text-center">{{ caso.zona === 'Rural' ? 'X' : '' }}</td>
                    <!-- Data sintomas -->
                    <td class="text-center">{{ formatDate(caso.data_primeiros_sintomas) }}</td>
                    <!-- Plano -->
                    <td class="text-center">{{ caso.plano_tratamento === 'A' ? 'X' : '' }}</td>
                    <td class="text-center">{{ caso.plano_tratamento === 'B' ? 'X' : '' }}</td>
                    <td class="text-center">{{ caso.plano_tratamento === 'C' ? 'X' : '' }}</td>
                    <td class="text-center">{{ caso.plano_tratamento === 'IGN' && caso.nome_paciente ? 'X' : '' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Notas de rodapé -->
        <div class="notas">
            <p>* FAIXA ETÁRIA - Escrever a idade do paciente na faixa etária correspondente (em dias até 1 mês, em meses até 1 ano e depois em anos);</p>
            <p>** ZONA - Assinalar com um (X) (Urbano ou Rural);</p>
            <p>*** PLANO DE TRATAMENTO - Assinar com um (X) se o plano de tratamento for A (diarreia sem desidratação, paciente atendido com cuidados domiciliares);</p>
            <p style="padding-left: 14px">B (diarreia com desidratação, paciente em observação na sala de TRO); C (diarreia grave com desidratação, paciente com reidratação venosa) ou Ignorado</p>
        </div>

        <!-- Responsável -->
        <div class="responsavel-row">
            <span>Responsável: <span class="campo-linha">{{ relatorio.responsavel_nome }}</span></span>
            <span style="margin-left: 60px">Assinatura: <span class="campo-linha assinatura-linha"></span></span>
        </div>

        <!-- Rodapé institucional -->
        <div class="rodape">SVS/DEVEP/CGDT/COVEH/Impresso 001</div>
    </div>
</template>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body { background: white; }

.impresso {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8pt;
    padding: 8mm 10mm;
    width: 297mm;
    min-height: 210mm;
    color: #000;
}

/* Cabeçalho */
.header-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 2px; }
.header-table td { padding: 3px 5px; vertical-align: middle; }
.logo-cell { width: 80px; border-right: 1px solid #000; font-size: 6pt; line-height: 1.3; }
.logo-text { font-weight: bold; }
.title-cell { text-align: center; }
.title-main { font-size: 10pt; font-weight: bold; }
.title-sub { font-size: 9pt; font-weight: bold; margin-top: 2px; }
.se-cell { width: 120px; border-left: 1px solid #000; font-size: 7pt; text-align: center; line-height: 1.5; }
.se-value { font-weight: bold; font-size: 9pt; }

/* Identificação */
.identificacao { border: 1px solid #000; border-top: none; padding: 3px 5px; font-size: 8pt; margin-bottom: 2px; }
.campo-valor { border-bottom: 1px solid #000; display: inline-block; min-width: 120px; }

/* Tabela de casos */
.casos-table { width: 100%; border-collapse: collapse; font-size: 7pt; }
.casos-table th, .casos-table td { border: 1px solid #000; padding: 2px 3px; vertical-align: middle; }
.casos-table thead th { background: #fff; text-align: center; font-size: 7pt; line-height: 1.2; }
.casos-table tbody td { height: 12px; }
.col-group { background: #f0f0f0; }
.col-nr    { width: 22px; }
.col-data  { width: 45px; }
.col-nome  { min-width: 100px; }
.col-faixa { width: 28px; }
.col-proc  { min-width: 100px; }
.col-zona  { width: 28px; }
.col-datasint { width: 40px; }
.col-plano { width: 22px; }
.text-center { text-align: center; }

/* Notas */
.notas { margin-top: 4px; font-size: 6.5pt; line-height: 1.6; }

/* Responsável */
.responsavel-row { margin-top: 6px; font-size: 8pt; display: flex; align-items: flex-end; }
.campo-linha { border-bottom: 1px solid #000; display: inline-block; min-width: 160px; }
.assinatura-linha { min-width: 180px; }

/* Rodapé */
.rodape { margin-top: 4px; font-size: 6pt; }

/* Print */
@media print {
    @page { size: A4 landscape; margin: 8mm; }
    body { background: white; }
    .impresso { width: 100%; padding: 0; }
}
</style>
