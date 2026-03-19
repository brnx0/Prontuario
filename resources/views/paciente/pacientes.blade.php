@extends('principal')
@section('content')
<head>
    <script src="{{asset('js/paciente.js')}}"></script>
</head>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Pacientes</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pacienteModal" id="btnCadastro"> 
            <i class="fa-solid fa-plus fa-lg" style="color:rgb(245, 245, 245);"></i>
        </button>
    </div>   
    <!-- Filtros -->
    <div class="card p-3 mb-3">
        <form id="filterForm" method="GET" action="{{route('filtrar.paciente')}}">
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" id="filtroNome" placeholder="Digite um nome" name="nome">
                </div>
                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" class="form-control" id="filtroCPF" placeholder="Digite um CPF" name="cpf">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="filtroData" name="filtroData">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" >Filtrar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabela de Registros -->
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered dataTable-style" id="tdPacientes" style="width: 100%;">
        <thead class="table-dark">
            <tr>
                <th>Nome Do Paciente</th>
                <th>CPF</th>
                <th>Data de Nascimento</th>
                <th>Nº Cartão SUS</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody id="tabelaRegistros"> 

            @foreach ($query as $paciente)
            
                <tr> 
                    <td>{{$paciente->nome}}</td>
                    <td class="cpf">{{$paciente->cpf}}</td>
                    <td>{{$paciente->nascimento}}</td>
                    <td>{{$paciente->sus}}</td>
                    <td class="text-center"> 
                        @if ($paciente->ativo == 'S')
                            <button class="btn btn-sm btn-success ms-1 me-1" onclick="ativarInativarRegistro('{{$paciente->pac_cod}}','N','{{ csrf_token() }}')" title="Inativar">
                                <i class="fas fa-toggle-on fa-xl" ></i>
                            </button>
                        @else
                            <button class="btn btn-sm btn-danger ms-1 me-1" onclick="ativarInativarRegistro('{{$paciente->pac_cod}}','S','{{csrf_token()}}')"title="Ativar">
                                <i class="fas fa-toggle-off fa-xl" ></i>
                            </button>
                        @endif 
                        
                        <button class="btn btn-sm btn-warning edit-btn ms-1 me-1"  title="Clique aqui para editar" onclick="editPaciente('{{$paciente->pac_cod}}')">
                            <i class="fa-solid fa-pen-to-square fa-xl" style="color: #ffffff;"></i>
                        </button>
                        
                        <button class="btn btn-sm btn-danger ms-1 me-1" onclick="confirmarExclusao('{{$paciente->pac_cod}}','{{csrf_token()}}')">
                            <i class="fa-solid fa-trash fa-xl" style="color: #ffffff;"></i>
                        </button>
                    </td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
</div>
<script>  
    $(document).ready(function() {
        $('#tdPacientes').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
            },
            responsive: true, 
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            columnDefs: [{ 
                "orderable": false, 
                "targets": 4 
            }] 
        });
    });
</script>
</div>
 @include('paciente.modalPaciente')
 <form id="editStatusPaciente" method="POST">
    @csrf
    @method('PUT') 
    <input type="text" name="pac_cod" id="pac_cod" hidden>
    <input type="text" name="status" id="status" hidden>
</form>
@if(session('open_modal'))
    <script>
     document.addEventListener('DOMContentLoaded', function() {
    var pacienteModal = new bootstrap.Modal(document.getElementById('pacienteModal'));
    pacienteModal.show(); // Abre o modal
});
    </script>
@endif


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
@endsection
