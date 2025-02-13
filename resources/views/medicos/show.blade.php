@extends('principal') 
@section('content')
<head>
    <script src="{{asset('js/medicos.js')}}"></script>
</head>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Médicos</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#medicoModal">
            Cadastrar Médico
        </button>
    </div>
    
    <!-- Filtros -->
    <div class="card p-3 mb-3">
        <form id="filterForm" method="GET" action="">
            <div class="row g-2">
                <div class="col-md-7">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" id="filtroNome" placeholder="Digite um nome" name="NOME">
                </div>
                <div class="col-md-3">
                    <label class="form-label">CRM</label>
                    <input type="text" class="form-control" id="filtroCPF" placeholder="Digite um CPF" name="CPF">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" >Filtrar</button>
                </div>
            </div>
        </form>
    </div>
    <!-- Tabela de Registros -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>CRM</th>
                    <th> Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaRegistros">
               
                @foreach ($medicos as $medico)
                    <tr> 
                        <td class="col-md-6">{{$medico->med_nome}}</td>
                        <td class="col-md-2">{{$medico->crm}}</td>
                        <td class="col-md-2">
                            @if ($medico->ativo == 'S')
                                <button class="btn btn-sm btn-secondary ms-2" onclick="ativarInativarMed('{{$medico->med_cod}}','N','{{ csrf_token() }}')">Desativar</button>
                            @else
                                <button class="btn btn-sm btn-secondary ms-2" onclick="ativarInativarMed('{{$medico->med_cod}}','S','{{csrf_token()}}')">Ativar</button>
                            @endif
                            
                            <button class="btn btn-sm btn-warning edit-btn ms-2" data-bs-toggle="modal" data-bs-target="#pacienteModal"> 
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger ms-2" onclick="excluirMedico('{{$medico->med_cod}}')">Excluir</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form id="editStatusMed" method="POST">
            @csrf
            @method('PUT') 
            <input type="text" name="med_cod" id="edMED_COD" hidden>
            <input type="text" name="status" id="Status" hidden>
            

        </form>
        <form  id="deletMedico" method="POST" >
            @csrf
            @method('DELETE') 
            <input type="text" name="med_cod" id="MED_COD" hidden>
        </form>
        <div class=" justify-content-center">
            {{-- {{ $query->links('pagination::bootstrap-4', ['next' => 'Próximo', 'previous' => 'Anterior'])}}  --}}
        </div>
    </div>
</div>

@include('medicos.form');


@endsection
