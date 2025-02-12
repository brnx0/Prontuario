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
    

<<div class="container mt-4">
    <form method="POST"  id="formAtendimento">
        @csrf
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="paciente" class="form-label">
                    Paciente
                    <span style="color: red;">*</span>
                </label>
                <div class="select-container">
                    <div class="custom-select" onclick="toggleDropdown('dropdown')">
                        Selecione uma paciente
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="dropdown" id="dropdown">
                        <input type="text" name="PAC_COD" id="PAC_COD"  hidden>
                        <input type="text" id="filterInput" placeholder="Filtrar..." onkeyup="filterOptions(this,'optionsContainer')"  >
                        <div class="options" id="optionsContainer">
                          
                                @foreach ($pacientes as $paciente)
                                <div class="option" onclick="selectOption(this,'{{$paciente->PAC_COD}}','PAC_COD')">{{$paciente->NOME}}</div> 
                                @endforeach 

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label for="dtAtendimento" class="form-label">Data do Atendimento</label>
                <input type="datetime-local" class="form-control" id="dtAtendimento" name="dtAtendimento" value="{{$Data}}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-success">Imprimir Atendimento</button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="medico" class="form-label">Médico(a)</label>
                <div class="select-container">
                    <div class="custom-select" onclick="toggleDropdown('dropdown01')">
                        Selecione uma médico(a)
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="dropdown" id="dropdown01">
                        <input type="text" name="MED_COD" hidden>
                        <input type="text" id="filterInput" placeholder="Filtrar..." onkeyup="filterOptions(this,'optionsContainer01')" >
                        <div class="options" id="optionsContainer01">
                            @foreach ($medicos as $medico)
                                <div class="option" onclick="selectOption(this,'{{$medico->MED_COD}}','MED_COD')">{{$medico->MED_NOME}}</div> 
                                
                            @endforeach
                           {{-- 
                             Adicionar os medicos aqui --}}
                        </div>
                    </div>
                </div>  
            </div>
            <div class="col-md-4">
                <label for="especialidade" class="form-label">Especialidade</label>
                <div class="select-container">
                    <div class="custom-select" onclick="toggleDropdown('dropdown02')">
                        Selecione uma especialidade
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="dropdown" id="dropdown02">
                        <input type="text" id="filterInput" placeholder="Filtrar..." onkeyup="filterOptions(this,'optionsContainer02')" >
                        <input type="text" name="ESP_COD" hidden>
                        <div class="options" id="optionsContainer02">
                            {{-- Adicionar os medicos aqui --}}
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="enfermeiro" class="form-label">Enfermeiro(a)</label>
                <input type="text" class="form-control" id="enfermeiro" name="enfermeiro" hiden>
                
            </div>
        </div>
        <div class="row mb-3">
            <div class="div col-md-12">
                <label for="queixaSituacao">Situação/Queixa</label>
                <input type="text" class="form-control" id="queixaSituacao" name="SITUACAO">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="pressao" class="form-label text-nowrap">Pressão (mmHg)</label>
                <input type="text" class="form-control " id="pressao" name="mmhg">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcbpm" class="form-label text-nowrap">FC (bpm)</label>
                <input type="text" class="form-control " id="fc" name="bpm">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcrpm" class="form-label text-nowrap">FC (rpm)</label>
                <input type="text" class="form-control " id="fcrpm" name="rpm">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="spo2" class="form-label text-nowrap">SPO2 (%)</label>
                <input type="text" class="form-control " id="spo2" name="spo2">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="temp" class="form-label text-nowrap">Temperatura &deg;C</label>
                <input type="text" class="form-control " id="temp" name="temp">
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="peso" class="form-label text-nowrap">Peso (Kg)</label>
                <input type="text" class="form-control " id="peso" name="kg">
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="hgt" class="form-label text-nowrap">HGT (mg/dl)</label>
                <input type="text" class="form-control " id="hgt" name="hgt">
            </div>
        </div>
        <div class="mb-4">
            <div class="col-sm-12 d-flex flex-column">
            <label class="label form-label text-nowrap">Descrição do Caso Clínico</label>
            <textarea name="descricaoCaso" id="descricaoCaso"></textarea>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-success" onclick="salvarAtendimento()">Salvar</button>
        </div>
    </form>
</div>
</div>

<script src="{{asset('js/atendimento.js')}}"></script>

@endsection