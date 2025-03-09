@extends('principal')
@section('content')
@if (session('success'))
    <script>
        Swal.fire({
        icon: 'success',
        title: '',
        text: '{{ session('success') }}',
    });
</script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
        icon: 'error',
        title: '',
        text: '{{ session('error') }}',
    });
</script>
@endif

<div class="container mt-4">
    <input type="text" name="atend_cod" value="{{$atendimento->atend_cod}}" hidden id="atend_cod">
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="paciente" class="form-label">Paciente</label>
                <input type="text" class="form-control" value="{{$paciente->nome}}"  disabled> 
            </div>
            <div class="col-md-2">
                <label for="dtAtendimento" class="form-label" >Data do Atendimento</label>
                <input type="text" class="form-control" id="dtAtendimento" name="dtAtendimento" placeholder="DD/MM/YYYY" value="{{$atendimento->dt_atendimento}}" readonly disabled>
            </div>
            <div class="col-md-2" id="btnImprimir" >
                <button class="btn btn-success" onclick="imprimirAtendimento('atend_cod')">
                    <i class="fa-solid fa-print"></i>
                </button>
                <button class="btn btn-primary" onclick="imprimirReceita('atend_cod')">
                    <i class="fa-solid fa-laptop-medical"></i>
                </button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="medico" class="form-label">Médico(a)</label>
                <input type="text" class="form-control" value="{{$medico?->med_nome}}"  disabled> 
            </div>
            <div class="col-md-4">
                <label for="especialidade" class="form-label">Especialidade</label>
                <input type="text" class="form-control" value="{{$especialidade?->escp_desc}}"  disabled> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="enfermeiro" class="form-label">Enfermeiro(a)</label>
                <input type="text" class="form-control" id="enfermeiro" name="enfermeiro" value ="{{$enfermeiro?->enf_nome}}"disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="div col-md-12">
                <label for="queixaSituacao" readonly>Situação/Queixa</label>
                <input type="text" class="form-control" id="queixaSituacao" name="situacao" value="{{$atendimento->situacao_queixa}}" readonly disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="pressao" class="form-label text-nowrap">Pressão (mmHg)</label>
                <input type="text" class="form-control " id="pressao" name="mmhg" value="{{$atendimento->mmhg}}"readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcbpm" class="form-label text-nowrap">FC (bpm)</label>
                <input type="text" class="form-control " id="fc" name="bpm" value="{{$atendimento->bpm }}" readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcrpm" class="form-label text-nowrap">FC (rpm)</label>
                <input type="text" class="form-control " id="fcrpm" name="rpm" value="{{$atendimento->rpm }}" readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="spo2" class="form-label text-nowrap">SPO2 (%)</label>
                <input type="text" class="form-control " id="spo2" name="spo2" value="{{$atendimento->spo2 }}" readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="temp" class="form-label text-nowrap">Temperatura &deg;C</label>
                <input type="text" class="form-control " id="temp" name="temp" value="{{$atendimento->temp }}" readonly disabled>
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="peso" class="form-label text-nowrap">Peso (Kg)</label>
                <input type="text" class="form-control " id="peso" name="kg" value="{{$atendimento->kg }}" readonly disabled > 
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="hgt" class="form-label text-nowrap">HGT (mg/dl)</label>
                <input type="text" class="form-control " id="hgt" name="hgt" value="{{$atendimento->hgt}}" readonly disabled >
            </div>
        </div>
        <div class="mb-4">
            <div class="col-sm-12 d-flex flex-column">
            <label class="label form-label text-nowrap">Descrição do Caso Clínico</label>
            <textarea name="descricaoCaso" id="descricaoCaso"  disabled>{{$atendimento->desc_caso }}</textarea>
            </div>
        </div>
        <div>
            <a href="/atendimento">
                <button type="button" class="btn btn-primary">Voltar</button>
            </a>
        </div>
       
    
</div>
</div>

<script src="{{asset('js/atendimento.js')}}"></script>

@endsection