@extends('principal')
@section('content')
<head><script src="{{asset('js/atendimento.js')}}"></script></head>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Histórico de Atendimento</h2>

    <!-- Filtros -->
    <div class="card p-3 mb-4">
        <form class="row g-3" action="/historico">
            <div class="col-md-4">
                <label for="nomePaciente" class="form-label">Nome do Paciente</label>
                <input type="text" class="form-control" id="nomePaciente" name="nome" placeholder="Digite o nome">
            </div>
            <div class="col-md-3">
                <label for="dataAtendimento" class="form-label">Data do Atendimento</label>
                <input type="date" class="form-control" id="dataAtendimento" name="dataAtendimento">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Tabela de Histórico -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th class="col-md-1">Visualizar</th>
                    <th class="col-md-5">Nome do Paciente</th>
                    <th class="col-md-2">Data do Atendimento</th>
                    <th class="col-md-5">Queixa Relatada</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($atendimentos as $atendimento)
                    <tr>
                        <td class="col-md-1">
                            <button class="btn" onclick="abrirAtendimento('{{$atendimento->atend_cod}}')">
                                <i class="fa-solid fa-eye" style="color: #74C0FC;"></i>
                            </button>
                        </td>
                        <td class="col-md-5">{{$atendimento?->paciente->nome}}</td>
                        <td class="col-md-2">{{$atendimento->dt_atendimento}}</td>
                        <td class="col-md-5" style="word-break: break-word; white-space: normal;">
                            {{ $atendimento?->situacao_queixa}}
                        </td>
                    </tr>
                
                

                {{-- {{$atendimento->dt_atendimento}}
                {{$atendimento?->situacao_queixa}}
                
                {{$atendimento?->mmhg}}
                {{$atendimento?->bpm}}
                {{$atendimento?->rpm}}
                {{$atendimento?->spo2}}
                {{$atendimento?->temp}}
                {{$atendimento?->kg}}
                {{$atendimento?->hgt}}
                {{$atendimento?->paciente->nome}}
                {{$atendimento->enfermeiro?->enf_nome}}
                {{$atendimento?->medico?->med_nome}}
                {{$atendimento?->especialidade?->escp_desc}} --}}
                    
                @endforeach
               
            </tbody>
            {{ $atendimentos->links('pagination::bootstrap-4', ['next' => 'Próximo', 'previous' => 'Anterior'])}} 
        </table>
    </div>
</div>

<div class="modal fade" id="atendimentoModal" tabindex="-1" aria-labelledby="atendimentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="atendimentoModalLabel">Detalhes do Atendimento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <input type="text" name="atend_cod" value="" hidden>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="paciente" class="form-label">Paciente</label>
                <input type="text" class="form-control" id="pacienteHistorico" disabled> 
              </div>
              <div class="col-md-3">
                <label for="dtAtendimento" class="form-label" >Data do Atendimento</label>
                <input type="" class="form-control" id="dtAtendimento" name="dtAtendimento"  readonly disabled>
              </div>
              <div class="col-md-3" id="btnImprimir">
                <button class="btn btn-success">Imprimir Atendimento</button>
              </div>
            </div>
  
            <div class="row mb-3">
              <div class="col-md-8">
                <label for="medico" class="form-label">Médico(a)</label>
                <input type="text" class="form-control" id="medicoHistorico" disabled> 
              </div>
              <div class="col-md-4">
                <label for="especialidade" class="form-label">Especialidade</label>
                <input type="text" class="form-control" id="especialistaHistorico" disabled> 
              </div>
            </div>
  
            <div class="row mb-3">
              <div class="col-md-12">
                <label for="enfermeiro" class="form-label">Enfermeiro(a)</label>
                <input type="text" class="form-control" id="enfermeiroHistorico" name="enfermeiro" value ="" disabled>
              </div>
            </div>
  
            <div class="row mb-3">
              <div class="div col-md-12">
                <label for="queixaSituacao" readonly>Situação/Queixa</label>
                <input type="text" class="form-control" id="queixaSituacao" name="situacao" value="" readonly disabled>
              </div>
            </div>
  
            <div class="row mb-3">
              <div class="col-md-2 me-4 customCol d-flex flex-column">
                <label for="pressao" class="form-label text-nowrap">Pressão (mmHg)</label>
                <input type="text" class="form-control" id="mmhg" name="mmhg" value="" readonly disabled>
              </div>
  
              <div class="col-md-2 me-4 customCol d-flex flex-column">
                <label for="fcbpm" class="form-label text-nowrap">FC (bpm)</label>
                <input type="text" class="form-control" id="bpm" name="bpm" value="" readonly disabled>
              </div>
  
              <div class="col-md-2 me-4 customCol d-flex flex-column">
                <label for="fcrpm" class="form-label text-nowrap">FC (rpm)</label>
                <input type="text" class="form-control" id="rpm" name="rpm" value="" readonly disabled>
              </div>
  
              <div class="col-md-2 me-4 customCol d-flex flex-column">
                <label for="spo2" class="form-label text-nowrap">SPO2 (%)</label>
                <input type="text" class="form-control" id="spo2" name="spo2" value="" readonly disabled>
              </div>
  
              <div class="col-md-2 me-4 customCol d-flex flex-column">
                <label for="temp" class="form-label text-nowrap">Temperatura &deg;C</label>
                <input type="text" class="form-control" id="temp" name="temp" value="" readonly disabled>
              </div>
  
              <div class="col-md-2 me-4 customCol d-flex flex-column">
                <label for="peso" class="form-label text-nowrap">Peso (Kg)</label>
                <input type="text" class="form-control" id="kg" name="kg" value="" readonly disabled> 
              </div>
  
              <div class="col-md-2 me-4 customCol d-flex flex-column">
                <label for="hgt" class="form-label text-nowrap">HGT (mg/dl)</label>
                <input type="text" class="form-control" id="hgt" name="hgt" value="" readonly disabled>
              </div>
            </div>
  
            <div class="mb-4">
              <div class="col-md-12 d-flex flex-column">
                <label class="label form-label text-nowrap">Descrição do Caso Clínico</label>
                <textarea name="descricaoCaso" id="descricaoCaso" disabled></textarea>
              </div>
            </div>
          </form>
        </div>
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary">Voltar</button>
        </div>
      </div>
    </div>
  </div>

@endsection

