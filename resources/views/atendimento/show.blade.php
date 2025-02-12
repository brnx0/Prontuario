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
    <input type="text" name="ATEND_COD" value="{{$atendimento->ATEND_COD}}" hidden>
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="paciente" class="form-label">Paciente</label>
                <input type="text" class="form-control" value="{{$paciente->NOME}}"  disabled> 
            </div>
            <div class="col-md-2">
                <label for="dtAtendimento" class="form-label" >Data do Atendimento</label>
                <input type="datetime-local" class="form-control" id="dtAtendimento" name="dtAtendimento" value="{{$atendimento->DT_ATENDIMENTO}}" readonly disabled>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success">Imprimir Atendimento</button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="medico" class="form-label">Médico(a)</label>
                <input type="text" class="form-control" value="Medico"  disabled> 
            </div>
            <div class="col-md-4">
                <label for="especialidade" class="form-label">Especialidade</label>
                <input type="text" class="form-control" value="Especialidade"  disabled> 
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="enfermeiro" class="form-label">Enfermeiro(a)</label>
                <input type="text" class="form-control" id="enfermeiro" name="enfermeiro" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="div col-md-12">
                <label for="queixaSituacao" readonly>Situação/Queixa</label>
                <input type="text" class="form-control" id="queixaSituacao" name="SITUACAO" readonly disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="pressao" class="form-label text-nowrap">Pressão (mmHg)</label>
                <input type="text" class="form-control " id="pressao" name="mmhg" value="{{$atendimento->MMHG}}"readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcbpm" class="form-label text-nowrap">FC (bpm)</label>
                <input type="text" class="form-control " id="fc" name="bpm" value="{{$atendimento->BPM }}" readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcrpm" class="form-label text-nowrap">FC (rpm)</label>
                <input type="text" class="form-control " id="fcrpm" name="rpm" value="{{$atendimento->RPM }}" readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="spo2" class="form-label text-nowrap">SPO2 (%)</label>
                <input type="text" class="form-control " id="spo2" name="spo2" value="{{$atendimento->SPO2 }}" readonly disabled>
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="temp" class="form-label text-nowrap">Temperatura &deg;C</label>
                <input type="text" class="form-control " id="temp" name="temp" value="{{$atendimento->TEMP }}" readonly disabled>
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="peso" class="form-label text-nowrap">Peso (Kg)</label>
                <input type="text" class="form-control " id="peso" name="kg" value="{{$atendimento->KG }}" readonly disabled > 
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="hgt" class="form-label text-nowrap">HGT (mg/dl)</label>
                <input type="text" class="form-control " id="hgt" name="hgt" value="{{$atendimento->HGT}}" readonly disabled >
            </div>
        </div>
        <div class="mb-4">
            <div class="col-sm-12 d-flex flex-column">
            <label class="label form-label text-nowrap">Descrição do Caso Clínico</label>
            <textarea name="descricaoCaso" id="descricaoCaso"  disabled>{{$atendimento->DESC_CASO }}</textarea>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary">Voltar</button>
        </div>
    
</div>
</div>

<script src="{{asset('js/atendimento.js')}}"></script>

@endsection